<x-guest-layout>
    <h2 class="auth-title">Réinitialiser le mot de passe</h2>
    <p class="auth-subtitle">
        <i class="fas fa-lock-open me-2"></i>
        Créez un nouveau mot de passe sécurisé pour votre compte
    </p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-2"></i>Adresse Email
            </label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email', $request->email) }}" 
                   required autofocus autocomplete="username" readonly
                   placeholder="votre@email.com">
            @error('email')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fas fa-key me-2"></i>Nouveau mot de passe
            </label>
            <div class="input-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" required autocomplete="new-password" 
                       placeholder="••••••••">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>
            @error('password')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Minimum 8 caractères avec lettres, chiffres et symboles
            </small>
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">
                <i class="fas fa-check-double me-2"></i>Confirmer le mot de passe
            </label>
            <div class="input-group">
                <input id="password_confirmation" type="password" 
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       name="password_confirmation" required autocomplete="new-password" 
                       placeholder="••••••••">
                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                    <i class="fas fa-eye" id="eyeIconConfirm"></i>
                </button>
            </div>
            @error('password_confirmation')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password Strength Indicator -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted">Force du mot de passe:</small>
                <small id="strength-text" class="text-muted">Non défini</small>
            </div>
            <div class="progress" style="height: 5px;">
                <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary-auth mb-3">
            <i class="fas fa-sync-alt me-2"></i>Réinitialiser le mot de passe
        </button>

        <!-- Back to Login Link -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="auth-link">
                <i class="fas fa-arrow-left me-1"></i>Retour à la connexion
            </a>
        </div>
    </form>

    <!-- Password Requirements Card -->
    <div class="card mt-4 border-0" style="background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);">
        <div class="card-body">
            <h6 class="mb-3">
                <i class="fas fa-shield-alt text-primary me-2"></i>
                Exigences du mot de passe
            </h6>
            <ul class="list-unstyled mb-0 small">
                <li class="mb-2" id="req-length">
                    <i class="fas fa-circle text-muted me-2" style="font-size: 0.5rem;"></i>
                    Au moins 8 caractères
                </li>
                <li class="mb-2" id="req-uppercase">
                    <i class="fas fa-circle text-muted me-2" style="font-size: 0.5rem;"></i>
                    Au moins une lettre majuscule
                </li>
                <li class="mb-2" id="req-lowercase">
                    <i class="fas fa-circle text-muted me-2" style="font-size: 0.5rem;"></i>
                    Au moins une lettre minuscule
                </li>
                <li class="mb-2" id="req-number">
                    <i class="fas fa-circle text-muted me-2" style="font-size: 0.5rem;"></i>
                    Au moins un chiffre
                </li>
                <li class="mb-0" id="req-special">
                    <i class="fas fa-circle text-muted me-2" style="font-size: 0.5rem;"></i>
                    Au moins un caractère spécial (@$!%*?&)
                </li>
            </ul>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');

        // Toggle password visibility
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });

        togglePasswordConfirm.addEventListener('click', function() {
            const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmInput.setAttribute('type', type);
            eyeIconConfirm.classList.toggle('fa-eye');
            eyeIconConfirm.classList.toggle('fa-eye-slash');
        });

        // Password strength checker
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[@$!%*?&]/.test(password)
            };

            // Update requirements UI
            Object.keys(requirements).forEach(req => {
                const element = document.getElementById(`req-${req}`);
                const icon = element.querySelector('i');
                if (requirements[req]) {
                    icon.classList.remove('fa-circle', 'text-muted');
                    icon.classList.add('fa-check-circle', 'text-success');
                    strength++;
                } else {
                    icon.classList.remove('fa-check-circle', 'text-success');
                    icon.classList.add('fa-circle', 'text-muted');
                }
            });

            // Update strength bar
            const percentage = (strength / 5) * 100;
            strengthBar.style.width = percentage + '%';
            
            // Update color and text based on strength
            strengthBar.classList.remove('bg-danger', 'bg-warning', 'bg-info', 'bg-success');
            if (strength <= 2) {
                strengthBar.classList.add('bg-danger');
                strengthText.textContent = 'Faible';
                strengthText.className = 'text-danger';
            } else if (strength === 3) {
                strengthBar.classList.add('bg-warning');
                strengthText.textContent = 'Moyen';
                strengthText.className = 'text-warning';
            } else if (strength === 4) {
                strengthBar.classList.add('bg-info');
                strengthText.textContent = 'Bon';
                strengthText.className = 'text-info';
            } else if (strength === 5) {
                strengthBar.classList.add('bg-success');
                strengthText.textContent = 'Excellent';
                strengthText.className = 'text-success';
            }
        });
    });
    </script>
    @endpush
</x-guest-layout>
