<!-- filepath: /Users/wesley/Desktop/app-saas/resources/views/users/parts/basic-details.blade.php -->
<div class="card">
    <div class="card-header">
        <h3>Dados Básicos</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" 
                       name="name" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       value="{{ old('name') ?? $user->name }}">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Endereço de Email</label>
                <input type="email" 
                       name="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       value="{{ old('email') ?? $user->email }}">
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" 
                       name="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password">
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="sector_id" class="form-label">Setor</label>
                <select name="sector_id" class="form-control @error('sector_id') is-invalid @enderror">
                    <option value="">Selecione um Setor</option>
                    @foreach ($sectors as $sector)
                        <option value="{{ $sector->id }}" 
                                {{ old('sector_id', $user->sector_id) == $sector->id ? 'selected' : '' }}>
                            {{ $sector->name }}
                        </option>
                    @endforeach
                </select>
                @error('sector_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            
            
            

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Editar</button>
            </div>
        </form>
    </div>
</div>



 



