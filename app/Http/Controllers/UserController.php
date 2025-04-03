<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use App\Models\UserProfile; 
use App\Models\UserInterest;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query();

        $users->when($request->input('search'), function($query, $keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        });

        $users = $users->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create($input);

        return redirect()->route('users.index')->with('status', 'Usuario adicionando com sucesso.');
    }

    public function edit(User $user)
    {
            Gate::authorize('edit', $user::class);

            $user->load('profile', 'interests');
            $roles= role ::all();
            $sectors = Sector::all();
            $companies = Company::all();
            return view('users.edit', compact('user','roles', 'sectors', 'companies'));
    }
    public function update(Request $request, User $user)
    {
            Gate::authorize('edit', $user::class); 

            $input = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'exclude_if:password,null|min:8',
            'sector_id' => 'nullable|exists:sectors,id',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $user->update($input);

        return redirect()->route('users.index')->with('status', 'Usuario atualizado com sucesso.');
    }
    public function updateRoles(Request $request, User $user)
    {
            Gate::authorize('edit', $user::class);

            $input = $request->validate([
            'roles' => 'required|array',
        ]);

        $user->roles()->sync($input['roles']);

        return redirect()->route('users.index')->with('status', 'Funções atualizadas com sucesso.');
    }


    public function updateProfile(Request $request, User $user)
    {
            Gate::authorize('edit', $user::class);

            $input = $request->validate([
            'type' => 'required',
            'address' => 'nullable',
      
        ]);

            UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            
        $input); 


        return redirect()->route('users.index')->with('status', 'Perfil atualizado com sucesso.');
    }
    public function updateInterests(Request $request, User $user)
    {
            Gate::authorize('edit', $user::class);    

            $input = $request->validate([
            'interests' => 'nullable|array',
    ]);

    $user->interests()->delete();

 
    if (!empty($input['interests'])) {

        $interests = array_map(function($interest) use ($user) {
            return [
                'user_id' => $user->id,
                'interest' => $interest
            ];
        }, $input['interests']);

        $user->interests()->createMany($interests);
    }

    return redirect()->route('users.index')->with('status', 'Interesses atualizados com sucesso.');
    }

    public function destroy(User $user)
    {
                Gate::authorize('destroy', $user::class);  

                $user->delete();
                return redirect()->route('users.index')->with('status', 'Usuario removido com sucesso.');
    }
    public function dashboard()
{
    $totalUsers = User::count();
    return view('admin.dashboard', compact('totalUsers'));
}
public function export()
{
    $response = new StreamedResponse(function () {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['ID', 'Nome', 'Email', 'Setor']); // Cabeçalho

        User::all()->each(function ($user) use ($handle) {
            fputcsv($handle, [
                $user->id,
                $user->name,
                $user->email,
                $user->sector->name ?? 'Sem setor',
            ]);
        });

        fclose($handle);
    });

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="usuarios.csv"');

    return $response;
}
public function exportPdf()
{
    $users = User::all();
    $pdf = Pdf::loadView('users.pdf', compact('users'));

    return $pdf->download('usuarios.pdf');
}

}
