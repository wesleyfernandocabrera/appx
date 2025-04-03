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
                    <p class="mt-2">
                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank">Visualizar Arquivo Atual</a>
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

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Salvar Alterações
            </button>
        </form>
    </div>
</div>

@endsection
