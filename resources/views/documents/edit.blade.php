@extends ('layouts.default')   
@section('page-title', 'Editar Documento') 

@section('page-actions')
<a href="{{ route('documents.index') }}" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i> Voltar</a>
@endsection

@section('content')

@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif

<form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Nome do Documento</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $document->name) }}" required>
    </div>

    <div class="mb-3">
        <label for="file" class="form-label">Arquivo (Deixe em branco para manter o atual)</label>
        <input type="file" name="file" class="form-control">
        @if($document->file_path)
        <p class="mt-3">
    <a href="{{ asset('storage/' . $document->file_path) }}" 
       class="btn btn-info btn-sm" 
       target="_blank">
       <i class="bi bi-eye"></i> Visualizar versão atual
    </a>
</p>

        @endif
    </div>

    <div class="mb-3">
        <label for="macro_id" class="form-label">Macro</label>
        <select name="macro_id" class="form-control" required>
            <option value="" disabled>Selecione uma Macro</option>
            @foreach($macros as $macro)
            <option value="{{ $macro->id }}" {{ $document->macro_id == $macro->id ? 'selected' : '' }}>
                {{ $macro->name }}
            </option>
            @endforeach
        </select>
    </div>

    <!-- Novo Campo: Seleção Múltipla de Setores -->
    <div class="mb-3">
    <label class="form-label d-block">Setores</label>
    <div class="row">
        @foreach($sectors as $sector)
            <div class="col-md-4 mb-2">
                <div class="form-check form-switch">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        name="sectors[]" 
                        value="{{ $sector->id }}" 
                        id="sector{{ $sector->id }}"
                        {{ $document->sectors->contains($sector->id) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="sector{{ $sector->id }}">
                        {{ $sector->name }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>


    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i> Salvar Alterações
    </button>
</form>

@endsection
