<x-guest-layout>
    <h2 class="auth-title">Inscription</h2>
    <p class="auth-subtitle">Créez votre compte BookShare</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">
                <i class="fas fa-user me-2"></i>Nom complet
            </label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                   name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                   placeholder="Votre nom">
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-2"></i>Email
            </label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email') }}" required autocomplete="username" 
                   placeholder="votre@email.com">
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-2"></i>Mot de passe
            </label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                   name="password" required autocomplete="new-password" 
                   placeholder="••••••••">
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">
                <i class="fas fa-lock me-2"></i>Confirmer le mot de passe
            </label>
            <input id="password_confirmation" type="password" class="form-control" 
                   name="password_confirmation" required autocomplete="new-password" 
                   placeholder="••••••••">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary-auth mb-3">
            <i class="fas fa-user-plus me-2"></i>S'inscrire
        </button>

        <!-- Links -->
        <div class="text-center">
            <div class="auth-divider">ou</div>
            <a href="{{ route('login') }}" class="auth-link">
                <i class="fas fa-sign-in-alt me-1"></i>Vous avez déjà un compte ? Connectez-vous
            </a>
        </div>
    </form>
</x-guest-layout>
