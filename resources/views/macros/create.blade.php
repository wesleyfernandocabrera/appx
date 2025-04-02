@extends('layouts.default')

@section('page-title', 'Adicionar Macro')

@section('content')
<form action="{{ route('macros.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Nome da Macro</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Descrição</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Adicionar Macro</button>
</form>
@endsection
