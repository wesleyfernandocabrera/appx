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
      <th scope="col">Responsável Uploud</th>
      <th scope="col">Status</th> 
      <th scope="col">Triagem</th>
      <th scope="col" class="text-end">Ações</th>
    </tr>
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
      <!-- Botão para visualizar -->
      <a href="{{ asset('storage/' . $document->file_path) }}" class="btn btn-info btn-sm" target="_blank">
        <i class="bi bi-eye"></i> Visualizar
      </a>

      <!-- Botão Toggle para bloquear/desbloquear -->
      @can('create', App\Models\User::class)
      <button class="btn btn-sm toggle-lock {{ $document->locked ? 'btn-warning' : 'btn-secondary' }}" data-id="{{ $document->id }}">
      <i class="bi {{ $document->locked ? 'bi-lock-fill' : 'bi-unlock-fill' }}"></i>
      {{ $document->locked ? 'Ativar' : 'Desativar' }}
      </button>
      @endcan

      <!-- Botão para baixar -->
      <a href="{{ asset('storage/' . $document->file_path) }}" class="btn btn-success btn-sm" download>
        <i class="bi bi-download"></i> Baixar
      </a>


      @can('update', $document)
      <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-primary btn-sm">
          <i class="bi bi-pencil"></i> Editar
      </a>
      @endcan
  
   @can('delete', $document)
      <form action="{{ route('documents.destroy', $document->id) }}" method="POST" style="display: inline;">
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

<!-- Paginação -->
{{ $documents->links() }}

@endsection