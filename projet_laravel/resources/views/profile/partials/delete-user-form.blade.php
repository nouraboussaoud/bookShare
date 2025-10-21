<div class="card border-danger">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">
            <i class="fas fa-trash-alt me-2"></i>Supprimer le Compte
        </h5>
        <small class="text-danger-50">Une fois votre compte supprimé, toutes ses ressources et données seront supprimées définitivement.</small>
    </div>
    <div class="card-body">
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Attention :</strong> Cette action est irréversible. Toutes vos données, livres, échanges et informations seront supprimés définitivement.
        </div>

        <button type="button" class="btn btn-danger" data-bs-toggle="collapse" data-bs-target="#deleteAccountForm" aria-expanded="false">
            <i class="fas fa-trash-alt me-1"></i>Supprimer mon compte
        </button>

        <div class="collapse mt-3" id="deleteAccountForm">
            <div class="card border-danger">
                <div class="card-body">
                    <h6 class="text-danger mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Êtes-vous sûr de vouloir supprimer votre compte ?
                    </h6>
                    <p class="text-muted small mb-3">
                        Une fois votre compte supprimé, toutes ses ressources et données seront supprimées définitivement.
                        Veuillez saisir votre mot de passe pour confirmer la suppression définitive de votre compte.
                    </p>

                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')

                        <div class="mb-3">
                            <label for="delete_password" class="form-label fw-bold text-danger">Mot de Passe</label>
                            <input id="delete_password" name="password" type="password" class="form-control" placeholder="Saisissez votre mot de passe" required />
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#deleteAccountForm">
                                <i class="fas fa-times me-1"></i>Annuler
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt me-1"></i>Supprimer Définitivement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
