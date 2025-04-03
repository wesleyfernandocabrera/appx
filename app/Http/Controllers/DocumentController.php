<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Macro;
use App\Models\Sector;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['macro', 'user', 'sectors', 'companies'])->paginate(10);
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        $macros = Macro::all();
        $sectors = Sector::all();
        $companies = Company::all();
        return view('documents.create', compact('macros', 'sectors', 'companies'));
    }

    public function store(Request $request)
    {
        Log::info('Recebendo request para salvar documento', ['request' => $request->all()]);

        $validatedData = $request->validate([
            'name'      => 'required|string|max:255',
            'file'      => 'required|file|mimes:pdf,doc,docx|max:2048',
            'macro_id'  => 'required|exists:macros,id',
            'sectors'   => 'nullable|array',
            'sectors.*' => 'exists:sectors,id',
            'companies' => 'nullable|array',
            'companies.*' => 'exists:companies,id',
        ]);

        if (!auth()->check()) {
            return redirect()->route('documents.create')->with('error', 'Usuário não autenticado.');
        }

        DB::beginTransaction();

        try {
            // Salvar o arquivo na pasta "storage/app/public/documents/"
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/documents', $fileName);

            Log::info('Arquivo salvo com sucesso', ['path' => $path]);

            // Criar documento no banco
            $document = Document::create([
                'name'      => $validatedData['name'],
                'file_path' => 'documents/' . $fileName, // Ajuste para salvar corretamente
                'macro_id'  => $validatedData['macro_id'],
                'user_id'   => auth()->id(),
            ]);

            Log::info('Documento criado com sucesso', ['document_id' => $document->id]);

            if (!empty($validatedData['sectors'])) {
                $document->sectors()->attach($validatedData['sectors']);
                Log::info('Setores vinculados', ['sectors' => $validatedData['sectors']]);
            }

            if (!empty($validatedData['companies'])) {
                $document->companies()->attach($validatedData['companies']);
                Log::info('Empresas vinculadas', ['companies' => $validatedData['companies']]);
            }

            DB::commit();

            return redirect()->route('documents.index')->with('success', 'Documento enviado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao salvar documento: ' . $e->getMessage());

            if (isset($path)) {
                Storage::delete($path);
                Log::info('Arquivo removido devido a erro no banco', ['path' => $path]);
            }

            return redirect()->route('documents.create')->with('error', 'Erro ao salvar documento.');
        }
    }
}
