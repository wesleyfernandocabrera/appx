<div class="card">
    <div class="card-header">
        <h3>Cargos</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('users.updateRoles', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            @foreach($roles as $role)
            <div class="form-check">
                <input 
                    class="form-check-input @error('roles') is-invalid @enderror" 
                    type="checkbox"
                    value="{{ $role->id }}" 
                    name="roles[]"
                    @if($user->roles && $user->roles->contains($role->id)) checked @endif>
                <label class="form-check-label">
                    {{ $role->name }}
                </label>
            </div>
            @endforeach
            @error('roles')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <br>
            <div class="card-footer text">
                <button type="submit" class="btn btn-primary">Editar</button>
            </div>
        </form>
    </div>
</div>