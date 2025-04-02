@extends('layouts.default')

@section('page-title', 'Setores')

@section('page-actions')
@can('create', App\Models\User::class)
<a href="{{ route('sectors.create') }}" class="btn btn-primary">Adicionar</a>
@endcan
@endsection

@section('content')

@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif

<form action="{{ route('sectors.index') }}" method="GET" class="mb-3">
  <div class="input-group">
      <input type="text" 
             name="search" 
             class="form-control" 
             placeholder="Pesquisar por nome ou email">
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
    @foreach ($sectors as $sector)
    <tr>
      <th scope="row">{{ $sector->id }}</th>
      <td>{{ $sector->name }}</td>
      <td>{{ $sector->description }}</td>
      <td class="text-end">
        @can('edit', App\Models\User::class)
        <a href="{{ route('sectors.edit', $sector->id) }}" class="btn btn-primary btn-sm">Editar</a>
        @endcan
        <form action="{{ route('sectors.destroy', $sector->id) }}" method="POST" style="display: inline;">
          @csrf
          @method('DELETE')
          @can('edit', App\Models\User::class)
          <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
          @endcan
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

{{ $sectors->links() }}

@endsection
