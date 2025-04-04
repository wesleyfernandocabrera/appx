<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Macro;

class MacroController extends Controller
{
    public function index(Request $request)
    {
        $macros = Macro::query();

        $macros->when($request->input('search'), function($query, $keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        });

        $macros = $macros->paginate();

        return view('macros.index', compact('macros'));
    }

    public function create()
    {
        return view('macros.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Macro::create($request->all());

        return redirect()->route('macros.index')->with('status', 'Macro criada com sucesso!');
    }

    public function edit(Macro $macro)
{
    return view('macros.edit', compact('macro'));
}

public function update(Request $request, Macro $macro)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $macro->update($request->all());

    return redirect()->route('macros.index')->with('status', 'Macro atualizada com sucesso!');
}

public function destroy(Macro $macro)
{
    $macro->delete();

    return redirect()->route('macros.index')->with('status', 'Macro deletada com sucesso!');
}
}