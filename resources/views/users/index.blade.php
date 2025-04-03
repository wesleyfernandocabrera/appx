@extends ('layouts.default')   
@section('page-title', 'Usuários') 

@section('page-actions')
@can('create', App\Models\User::class)
<a href="{{route('users.create')}}" class="btn btn-primary">
<i class="bi bi-plus-lg"></i> Adicionar</a>

@endcan
@endsection

@section('content')

@if (session('status'))
<div class="alert alert-success">
 {{ session('status') }}
</div>
@endif

<form action="{{ route('users.index') }}" method="GET" class="mb-3">
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
      <th scope="col">Email</th>
      <th scope="col">Setor</th> <!-- Nova Coluna -->
      <th scope="col" class="text-end">Ações</th>
    </tr>
  </thead>
  <tbody>
    @foreach($users as $user)
    <tr>
      <th scope="row">{{ $user->id }}</th>
      <td>{{ $user->name }}</td>
      <td>{{ $user->email }}</td>
      <td>{{ $user->sector->name ?? 'Sem setor' }}</td> <!-- Exibindo o setor -->
      <td class="text-end">
        @can('edit', App\Models\User::class)
        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">Editar</a>
        @endcan
        @can('destroy', App\Models\User::class)
        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
        </form>
        @endcan
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

{{$users->links()}}

<div class="d-flex justify-content-end mt-3">
    <a href="{{ route('users.export') }}" class="btn btn-success me-2">
    Exportar <i class="bi bi-filetype-csv"></i>
    </a>
    <a href="{{ route('users.export.pdf') }}" class="btn btn-danger">
    Exportar <i class="bi bi-file-earmark-pdf"></i>
    </a>
</div>

@endsection
