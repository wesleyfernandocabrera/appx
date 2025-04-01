<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Sector;

class SectorController extends Controller
{
    public function index(Request $request)
    {
        $sectors = Sector::query();

        $sectors->when($request->input('search'), function($query, $keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        });

        $sectors = $sectors->paginate();

        return view('sectors.index', compact('sectors'));
    }
    

    public function create()
    {
        return view('sectors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Sector::create($request->all());

        return redirect()->route('sectors.index')->with('status', 'Setor criado com sucesso!');
    }

    public function edit(Sector $sector)
    {
        return view('sectors.edit', compact('sector'));
    }

    public function update(Request $request, Sector $sector)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $sector->update($request->all());

        return redirect()->route('sectors.index')->with('status', 'Setor atualizado com sucesso!');
    }

    public function destroy(Sector $sector)
    {
        $sector->delete();

        return redirect()->route('sectors.index')->with('status', 'Setor deletado com sucesso!');
    }
}