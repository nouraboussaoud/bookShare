<x-guest-layout>
    <h2 class="auth-title">Connexion</h2>
    <p class="auth-subtitle">Bienvenue ! Connectez-vous à votre compte</p>

    <!-- Session Status -->
    @if (session('status'))
        <div class="success-message">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-2"></i>Email
            </label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
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
                   name="password" required autocomplete="current-password" 
                   placeholder="••••••••">
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
            <label class="form-check-label" for="remember_me">
                Se souvenir de moi
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary-auth mb-3">
            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
        </button>

        <!-- Links -->
        <div class="text-center">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link d-block mb-2">
                    <i class="fas fa-key me-1"></i>Mot de passe oublié ?
                </a>
            @endif
            
            @if (Route::has('register'))
                <div class="auth-divider">ou</div>
                <a href="{{ route('register') }}" class="auth-link">
                    <i class="fas fa-user-plus me-1"></i>Créer un nouveau compte
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>
