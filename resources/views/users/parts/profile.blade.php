<div class="card">
    <div class="card-header">
        <h3>Perfil</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('users.updateProfile', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="type" class="form-label">Tipo de Pessoa</label>
                <select name="type" 
                class="form-control @error('type') is-invalid @enderror" id="type">

                @foreach(['Física', 'Jurídica'] as $type)
                    <option value="{{ $type }}" @if(old('type') == $type || (isset($user->profile) && $user->profile->type == $type)) selected @endif>{{ $type }}</option>
                @endforeach
                </select>

                @error('type')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Endereço</label>
                <input type="text" 
                       name="address" 
                       class="form-control @error('address') is-invalid @enderror" 
                       id="address" 
                       value="{{ old('address') ?? ($user->profile->address ?? '') }}">
                @error('address')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Editar</button>
            </div>
        </form>
    </div>
</div>