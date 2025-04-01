<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::query();

        $companies->when($request->input('search'), function($query, $keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        });

        $companies = $companies->paginate();

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Company::create($request->all());

        return redirect()->route('companies.index')->with('status', 'Empresa criada com sucesso!');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $company->update($request->all());

        return redirect()->route('companies.index')->with('status', 'Empresa atualizada com sucesso!');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('companies.index')->with('status', 'Empresa deletada com sucesso!');
    }
}
