<div class="card border-primary">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-user me-2"></i>Informations du Profil
        </h5>
        <small class="text-white-50">Mettez à jour vos informations personnelles et votre adresse email.</small>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Nom</label>
                <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Enregistrer
                </button>

                @if (session('status') == 'profile-updated')
                    <div class="alert alert-success py-1 px-2 mb-0 small">
                        <i class="fas fa-check-circle me-1"></i>Profil mis à jour avec succès !
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
