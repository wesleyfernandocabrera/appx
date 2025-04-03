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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Importação correta

class DocumentController extends Controller
{
    use AuthorizesRequests; // Necessário para usar authorize()

    public function index()
    {
        $documents = Document::with(['macro', 'user', 'sectors', 'companies'])->paginate(10);
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create', [
            'macros' => Macro::all(),
            'sectors' => Sector::all(),
            'companies' => Company::all()
        ]);
    }

    public function store(Request $request)
    {
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
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $fileName, 'public'); // Ajustado para armazenar corretamente

            $document = Document::create([
                'name'      => $validatedData['name'],
                'file_path' => $path, // Caminho correto do arquivo
                'macro_id'  => $validatedData['macro_id'],
                'user_id'   => auth()->id(),
            ]);

            $document->sectors()->attach($validatedData['sectors'] ?? []);
            $document->companies()->attach($validatedData['companies'] ?? []);

            DB::commit();
            return redirect()->route('documents.index')->with('success', 'Documento enviado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao salvar documento: ' . $e->getMessage());
            Storage::disk('public')->delete($path ?? '');
            return redirect()->route('documents.create')->with('error', 'Erro ao salvar documento.');
        }
    }

    public function toggleLock(Document $document)
    {
        $document->locked = !$document->locked;
        $document->save();

        return response()->json(['success' => true, 'locked' => $document->locked]);
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        try {
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }

            $document->delete();
            return redirect()->route('documents.index')->with('success', 'Documento excluído com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir documento: ' . $e->getMessage());
            return redirect()->route('documents.index')->with('error', 'Erro ao excluir documento.');
        }
    }
}
