<div class="card border-warning">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">
            <i class="fas fa-key me-2"></i>Changer le Mot de Passe
        </h5>
        <small>Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester sécurisé.</small>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mb-3">
                <label for="current_password" class="form-label fw-bold">Mot de Passe Actuel</label>
                <input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
                @error('current_password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Nouveau Mot de Passe</label>
                <input id="password" name="password" type="password" class="form-control" autocomplete="new-password" />
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label fw-bold">Confirmer le Mot de Passe</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
                @error('password_confirmation')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save me-1"></i>Changer le Mot de Passe
                </button>

                @if (session('status') == 'password-updated')
                    <div class="alert alert-success py-1 px-2 mb-0 small">
                        <i class="fas fa-check-circle me-1"></i>Mot de passe mis à jour avec succès !
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
