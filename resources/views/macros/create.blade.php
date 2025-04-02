@extends('layouts.default')

@section('page-title', 'Adicionar Macro')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('macros.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nome da Macro</label>
                <input 
                    type="text" 
                    name="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name') }}" 
                    required
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea 
                    name="description" 
                    class="form-control @error('description') is-invalid @enderror" 
                    required
                >{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Adicionar Macro
            </button>
        </form>
    </div>
</div>
@endsection
