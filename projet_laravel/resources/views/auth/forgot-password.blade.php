<x-guest-layout>
    <h2 class="auth-title">Mot de passe oublié</h2>
    <p class="auth-subtitle">
        <i class="fas fa-info-circle me-2"></i>
        Pas de souci ! Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
    </p>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-2"></i>Adresse Email
            </label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email') }}" required autofocus 
                   placeholder="votre@email.com">
            @error('email')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
            <small class="text-muted">
                <i class="fas fa-shield-alt me-1"></i>
                Nous vous enverrons un email sécurisé avec les instructions
            </small>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary-auth mb-3">
            <i class="fas fa-paper-plane me-2"></i>Envoyer le lien de réinitialisation
        </button>

        <!-- Back to Login Link -->
        <div class="text-center">
            <div class="auth-divider">ou</div>
            <a href="{{ route('login') }}" class="auth-link">
                <i class="fas fa-arrow-left me-1"></i>Retour à la connexion
            </a>
        </div>
    </form>

    <!-- Information complémentaire -->
    <div class="card mt-4 border-0" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
        <div class="card-body text-center">
            <i class="fas fa-lightbulb fa-2x text-warning mb-2"></i>
            <h6 class="mb-2">Conseils de sécurité</h6>
            <ul class="list-unstyled text-start small text-muted mb-0">
                <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Vérifiez votre dossier spam si vous ne recevez pas l'email</li>
                <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Le lien de réinitialisation expire après 60 minutes</li>
                <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Utilisez un mot de passe fort avec lettres, chiffres et symboles</li>
            </ul>
        </div>
    </div>
</x-guest-layout>
