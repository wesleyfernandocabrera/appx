@extends ('layouts.default')   
@section('page-title', 'Enviar Documento') 

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

<div class="card">
    <div class="card-body">
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nome do Documento</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="file" class="form-label">Arquivo</label>
                <input type="file" name="file" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="macro_id" class="form-label">Macro</label>
                <select name="macro_id" class="form-control" required>
                    <option value="" disabled selected>Selecione uma Macro</option>
                    @foreach($macros as $macro)
                    <option value="{{ $macro->id }}">{{ $macro->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-upload"></i> Enviar
            </button>
        </form>
    </div>
</div>

@endsection
