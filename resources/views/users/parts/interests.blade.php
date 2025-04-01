<div class="card">
    <div class="card-header">
        <h3>Interesses</h3>
    </div>
    
    <div class="card-body">
        <form action="{{ route('users.updateInterests', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            @foreach(['PHP', 'JavaScript', 'Python', 'Ruby'] as $interest)
            <div class="form-check">
                <input 
                    class="form-check-input @error('interests') is-invalid @enderror" 
                    type="checkbox"
                    name="interests[]" 
                    @checked(in_array($interest, $user->interests->pluck('interest')->toArray()))
                    value="{{ $interest }}" 
                    id="flexCheck{{ $interest }}">
                <label class="form-check-label" for="flexCheck{{ $interest }}">
                    {{ $interest }}
                </label>
            </div>
            @endforeach

            @error('interests')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            <br>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary">Editar</button>
            </div>
        </form>
    </div>
</div>