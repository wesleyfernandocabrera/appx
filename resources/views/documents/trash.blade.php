@extends ('layouts.default')   
@section('page-title', 'Documentos') 

@section('page-actions')
@can('create', App\Models\User::class)
<a href="{{ route('documents.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg"></i> Adicionar Documento
</a>
@endcan
@endsection

@section('content')

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Formulário de Pesquisa -->
<form action="{{ route('documents.index') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" 
               name="search" 
               class="form-control" 
               placeholder="Pesquisar por nome do documento">
        <button class="btn btn-primary btn-sm" type="submit">
            <i class="bi bi-search"></i> Pesquisar
        </button>
    </div>
</form>

<!-- Tabela de Documentos -->
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Nome</th>
      <th scope="col">Macro</th>
      <th scope="col">Setores</th>
      <th scope="col">Responsável Upload</th>
      <th scope="col">Status</th> 
      <th scope="col">Triagem</th>
      <th scope="col" class="text-end">Ações</th>
    </tr>
  </thead>
  <tbody>
    @foreach($documents as $document)
    <tr>
      <th scope="row">{{ $document->id }}</th>
      <td>{{ $document->name }}</td>
      <td>{{ $document->macro->name }}</td>
      <td>
          <span 
            class="badge bg-secondary"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="{{ implode(', ', $document->sectors->pluck('name')->toArray()) }}">
            {{ $document->sectors->count() }} Setor(es)
          </span>
      </td>
      <td>{{ $document->user->name ?? 'Desconhecido' }}</td>
      <td>
        @if($document->locked)
          <span class="badge bg-danger">Inativo</span>
        @else
          <span class="badge bg-success">Ativo</span>
        @endif
      </td>
      <td>{{ $document->status }}</td>
      <td class="text-end">
        <!-- Botão Visualizar -->
        <a href="{{ asset('storage/' . $document->file_path) }}" 
           class="btn btn-info btn-sm mb-1" 
           target="_blank" 
           style="display: inline-block;">
            <i class="bi bi-eye"></i> Visualizar
        </a>
    
        <!-- Botão Toggle Ativar/Inativar -->
        @can('create', App\Models\User::class)
        <form action="{{ route('documents.toggle-lock', $document->id) }}" method="POST" style="display:inline-block;">
            @csrf
            <button type="submit" class="btn btn-sm {{ $document->locked ? 'btn-warning' : 'btn-secondary' }} mb-1">
                <i class="bi {{ $document->locked ? 'bi-lock-fill' : 'bi-unlock-fill' }}"></i>
                {{ $document->locked ? 'Ativar' : 'Desativar' }}
            </button>
        </form>
        @endcan
    
        <!-- Botão Baixar -->
        <a href="{{ asset('storage/' . $document->file_path) }}" 
           class="btn btn-success btn-sm mb-1" 
           download 
           style="display: inline-block;">
            <i class="bi bi-download"></i> Baixar
        </a>
    
        <!-- Botão Restaurar (se for lixeira) -->
        @can('create', App\Models\User::class)
        @if(request()->is('documents/trash'))
        <form action="{{ route('documents.restore', $document->id) }}" method="POST" style="display:inline-block;">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm mb-1">
                <i class="bi bi-arrow-clockwise"></i> Restaurar
            </button>
        </form>
        @endif
        @endcan
    
        <!-- Botão Excluir Permanente -->
        @can('delete', $document)
        <form action="{{ route('documents.destroy', $document->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm mb-1">
                <i class="bi bi-trash"></i> Excluir <strong>(perm.)</strong>
            </button>
        </form>
        @endcan
    
    </tr>
    @endforeach
  </tbody>
</table>

<!-- Paginação -->
{{ $documents->links() }}

@endsection
