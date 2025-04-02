@extends('layouts.default')

@section('page-title', 'Macros')

@section('page-actions')
@can('create', App\Models\User::class)
<a href="{{ route('macros.create') }}" class="btn btn-primary">Adicionar</a>
@endcan
@endsection

@section('content')

@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif

<form action="{{ route('macros.index') }}" method="GET" class="mb-3">
  <div class="input-group">
      <input type="text" 
             name="search" 
             class="form-control" 
             placeholder="Pesquisar por nome ou descrição">
      <button class="btn btn-primary btn-sm" type="submit">
          <i class="bi bi-search"></i> Pesquisar
      </button>
  </div>
</form>

<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Nome</th>
      <th scope="col">Descrição</th>
      <th scope="col" class="text-end">Ações</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($macros as $macro)
    <tr>
      <th scope="row">{{ $macro->id }}</th>
      <td>{{ $macro->name }}</td>
      <td>{{ $macro->description }}</td>
      <td class="text-end">
        @can('edit', App\Models\User::class)
        <a href="{{ route('macros.edit', $macro->id) }}" class="btn btn-primary btn-sm">Editar</a>
        @endcan
        <form action="{{ route('macros.destroy', $macro->id) }}" method="POST" style="display: inline;">
          @csrf
          @method('DELETE')
          @can('delete', App\Models\User::class)
          <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
          @endcan
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

{{ $macros->links() }}

@endsection
