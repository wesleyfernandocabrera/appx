@extends('layouts.default')

@section('page-title', 'Empresas')

@section('page-actions')
@can('create', App\Models\User::class)
<a href="{{ route('companies.create') }}" class="btn btn-primary">Adicionar Empresa</a>
@endcan
@endsection

@section('content')

@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif

<form action="{{ route('companies.index') }}" method="GET" class="mb-3">
  <div class="input-group">
      <input type="text" 
             name="search" 
             class="form-control" 
             placeholder="Pesquisar por nome">
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
    @foreach ($companies as $company)
    <tr>
      <th scope="row">{{ $company->id }}</th>
      <td>{{ $company->name }}</td>
      <td>{{ $company->description }}</td>
      <td class="text-end">
        @can('edit', App\Models\User::class)
        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-primary btn-sm">Editar</a>
        @endcan
        <form action="{{ route('companies.destroy', $company->id) }}" method="POST" style="display: inline;">
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

{{ $companies->links() }}

@endsection
