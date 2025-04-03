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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentController extends Controller
{
    use AuthorizesRequests;

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
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'macro_id' => 'required|exists:macros,id',
            'sectors' => 'nullable|array',
            'sectors.*' => 'exists:sectors,id',
            'companies' => 'nullable|array',
            'companies.*' => 'exists:companies,id',
        ]);

        DB::beginTransaction();

        try {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $fileName, 'public');

            $document = Document::create([
                'name' => $validatedData['name'],
                'file_path' => $path,
                'macro_id' => $validatedData['macro_id'],
                'user_id' => auth()->id(),
                'locked' => false
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

    public function edit(Document $document)
    {
        return view('documents.edit', [
            'document' => $document,
            'macros' => Macro::all(),
            'sectors' => Sector::all(),
            'companies' => Company::all()
        ]);
    }

    public function update(Request $request, Document $document)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'macro_id' => 'required|exists:macros,id',
            'sectors' => 'nullable|array',
            'sectors.*' => 'exists:sectors,id',
            'companies' => 'nullable|array',
            'companies.*' => 'exists:companies,id',
        ]);

        DB::beginTransaction();

        try {
            $document->name = $validatedData['name'];
            $document->macro_id = $validatedData['macro_id'];

            if ($request->hasFile('file')) {
                if ($document->file_path) {
                    Storage::disk('public')->delete($document->file_path);
                }
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents', $fileName, 'public');
                $document->file_path = $path;
            }

            $document->save();
            $document->sectors()->sync($validatedData['sectors'] ?? []);
            $document->companies()->sync($validatedData['companies'] ?? []);

            DB::commit();
            return redirect()->route('documents.index')->with('success', 'Documento atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar documento: ' . $e->getMessage());
            return redirect()->route('documents.edit', $document->id)->with('error', 'Erro ao atualizar documento.');
        }
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

    public function toggleLock(Document $document)
    {
        try {
            $this->authorize('update', $document);

            $document->locked = !$document->locked;
            $document->save();

            return response()->json([
                'success' => true,
                'locked' => $document->locked,
                'status' => $document->locked ? 'Inativo' : 'Ativo' // Retorna o status atualizado
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao alternar bloqueio: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }
}
