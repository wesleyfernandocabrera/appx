<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Macro;

class MacroController extends Controller
{
    public function index(Request $request)
    {
        $query = Macro::query();

        // Adiciona funcionalidade de busca
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $macros = $query->paginate(10);

        return view('macros.index', compact('macros'));
    }

    public function create()
    {
        $this->authorize('create', Macro::class);
        return view('macros.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Macro::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            Macro::create($request->all());
            return redirect()->route('macros.index')->with('status', 'Macro criada com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors('Erro ao criar macro: ' . $e->getMessage());
        }
    }

    public function edit(Macro $macro)
    {
        $this->authorize('update', $macro);
        return view('macros.edit', compact('macro'));
    }

    public function update(Request $request, Macro $macro)
    {
        $this->authorize('update', $macro);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $macro->update($request->all());
            return redirect()->route('macros.index')->with('status', 'Macro atualizada com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors('Erro ao atualizar macro: ' . $e->getMessage());
        }
    }

    public function destroy(Macro $macro)
    {
        $this->authorize('delete', $macro);

        try {
            $macro->delete();
            return redirect()->route('macros.index')->with('status', 'Macro deletada com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors('Erro ao excluir macro: ' . $e->getMessage());
        }
    }
}
