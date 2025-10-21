@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading avec design moderne -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                <i class="fas fa-credit-card text-success"></i>
                Effectuer le Paiement
            </h1>
            <p class="text-muted small mb-0 mt-1">Choisissez votre méthode de paiement sécurisée</p>
        </div>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle"></i> {{ session('info') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Détails de la location -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book"></i> Détails de la Location
                    </h6>
                </div>
                <div class="card-body">
                    @if($payment->location->book->hasPhoto())
                        <img src="{{ $payment->location->book->photo_url }}" alt="{{ $payment->location->book->title }}" class="img-fluid rounded mb-3">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded mb-3" style="height: 200px;">
                            <i class="fas fa-book fa-4x text-gray-400"></i>
                        </div>
                    @endif
                    
                    <h5 class="card-title">{{ $payment->location->book->title }}</h5>
                    <p class="card-text"><strong>Auteur:</strong> {{ $payment->location->book->author }}</p>
                    <hr>
                    <p class="mb-2"><strong>Propriétaire:</strong> {{ $payment->location->proprietaire->name }}</p>
                    <p class="mb-2"><strong>Durée:</strong> {{ $payment->location->duree_jours }} jours</p>
                    <p class="mb-2"><strong>Date début:</strong> {{ $payment->location->date_location->format('d/m/Y') }}</p>
                    <p class="mb-2"><strong>Lieu:</strong> {{ $payment->location->localisation }}</p>
                    <hr>
                    <h4 class="text-center text-success">
                        <strong>Montant: {{ number_format($payment->montant, 2) }}€</strong>
                    </h4>
                </div>
            </div>
        </div>

        <!-- Options de paiement -->
        <div class="col-lg-8">
            <!-- Paiement Stripe (Recommandé) -->
            <div class="card shadow-lg border-0 mb-4 stripe-card">
                <div class="card-header bg-gradient-stripe text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fab fa-stripe fa-lg"></i> Paiement par Carte Bancaire
                        <span class="badge badge-light ml-2 pulse">✨ Recommandé</span>
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <h5 class="font-weight-bold text-primary mb-3">
                                Paiement sécurisé et instantané
                            </h5>
                            <div class="feature-list">
                                <div class="feature-item mb-3">
                                    <i class="fas fa-bolt text-warning fa-lg"></i>
                                    <div>
                                        <strong>Paiement instantané</strong>
                                        <p class="text-muted small mb-0">Confirmé en quelques secondes</p>
                                    </div>
                                </div>
                                <div class="feature-item mb-3">
                                    <i class="fas fa-lock text-success fa-lg"></i>
                                    <div>
                                        <strong>Sécurité maximale</strong>
                                        <p class="text-muted small mb-0">SSL 256-bit & 3D Secure</p>
                                    </div>
                                </div>
                                <div class="feature-item mb-3">
                                    <i class="fas fa-shield-alt text-info fa-lg"></i>
                                    <div>
                                        <strong>Protection acheteur</strong>
                                        <p class="text-muted small mb-0">Votre transaction est protégée</p>
                                    </div>
                                </div>
                                <div class="feature-item mb-3">
                                    <i class="fas fa-credit-card text-primary fa-lg"></i>
                                    <div>
                                        <strong>Toutes les cartes</strong>
                                        <p class="text-muted small mb-0">Visa, Mastercard, Amex...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 text-center">
                            <form action="{{ route('payments.stripe.checkout', $payment) }}" method="POST">
                                @csrf
                                <div class="payment-box p-4 mb-3">
                                    <div class="amount-display mb-3">
                                        <h2 class="text-success font-weight-bold mb-0">
                                            {{ number_format($payment->montant, 2) }}€
                                        </h2>
                                        <small class="text-muted">Montant total</small>
                                    </div>
                                    <button type="submit" class="btn btn-stripe btn-lg btn-block shadow">
                                        <i class="fab fa-stripe fa-lg"></i> Payer avec Stripe
                                    </button>
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle"></i> Redirection sécurisée
                                    </small>
                                </div>
                                <div class="trust-badges">
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%236772e5'%3E%3Cpath d='M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z'/%3E%3C/svg%3E" alt="Secure" width="20" class="mr-1">
                                    <small class="text-muted">Paiement 100% sécurisé</small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="text-center mb-4">
                <hr class="d-inline-block" style="width: 45%;">
                <span class="px-3 text-muted">OU</span>
                <hr class="d-inline-block" style="width: 45%;">
            </div>

            <!-- Paiement Manuel -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-money-bill-wave"></i> Autres Méthodes de Paiement
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('payments.process', $payment) }}" method="POST">
                        @csrf
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Pour les paiements manuels, vous devez effectuer le transfert puis confirmer ici.
                        </div>

                        <div class="form-group mb-4">
                            <label for="methode_paiement" class="font-weight-bold text-primary">
                                <i class="fas fa-wallet"></i> Méthode de Paiement 
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control form-control-lg @error('methode_paiement') is-invalid @enderror" id="methode_paiement" name="methode_paiement" required>
                                <option value="">-- Sélectionnez une méthode --</option>
                                <option value="paypal" {{ old('methode_paiement') == 'paypal' ? 'selected' : '' }}>
                                    🅿️ PayPal
                                </option>
                                <option value="virement" {{ old('methode_paiement') == 'virement' ? 'selected' : '' }}>
                                    🏦 Virement bancaire
                                </option>
                                <option value="especes" {{ old('methode_paiement') == 'especes' ? 'selected' : '' }}>
                                    💵 Espèces (en personne)
                                </option>
                                <option value="carte" {{ old('methode_paiement') == 'carte' ? 'selected' : '' }}>
                                    💳 Carte bancaire
                                </option>
                                <option value="autre" {{ old('methode_paiement') == 'autre' ? 'selected' : '' }}>
                                    ➕ Autre
                                </option>
                            </select>
                            @error('methode_paiement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Sélectionnez la méthode que vous allez utiliser pour effectuer le paiement
                                </small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="reference_transaction" class="font-weight-bold text-primary">
                                <i class="fas fa-hashtag"></i> Référence de Transaction
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('reference_transaction') is-invalid @enderror" 
                                   id="reference_transaction" 
                                   name="reference_transaction" 
                                   placeholder="Ex: TXN123456, PayPal-ID..." 
                                   value="{{ old('reference_transaction') }}">
                            @error('reference_transaction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Facultatif - Une référence sera générée automatiquement si vous n'en fournissez pas
                                </small>
                            @enderror
                        </div>

                        <div class="alert alert-warning border-left-warning shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning mr-3"></i>
                                <div>
                                    <strong>Important:</strong>
                                    <p class="mb-0 mt-1 small">
                                        Le propriétaire sera informé de votre paiement. Assurez-vous d'avoir effectué le transfert avant de confirmer.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success btn-lg btn-block shadow-sm">
                                <i class="fas fa-check-circle"></i> Confirmer le Paiement
                                <span class="badge badge-light ml-2">{{ number_format($payment->montant, 2) }}€</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Annulation -->
            <div class="card shadow mb-4 border-left-secondary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="font-weight-bold text-secondary">Vous ne souhaitez plus louer ce livre ?</h6>
                            <p class="text-muted mb-0">Vous pouvez annuler le paiement et la location.</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <form action="{{ route('payments.cancel', $payment) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce paiement et la location ?');">
                                @csrf
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Annuler
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Gradients professionnels */
.bg-gradient-stripe {
    background: linear-gradient(135deg, #635bff 0%, #4a45d6 100%);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Card styling */
.card {
    border-radius: 15px;
    overflow: hidden;
}

.stripe-card {
    border: 2px solid #635bff20;
}

/* Form controls */
.form-control-lg {
    border-radius: 10px;
    border: 2px solid #e3e6f0;
    transition: all 0.3s ease;
    padding: 12px 16px;
}

.form-control-lg:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    transform: translateY(-1px);
}

/* Button styling */
.btn-lg {
    border-radius: 10px;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-stripe {
    background: linear-gradient(135deg, #635bff 0%, #4a45d6 100%);
    color: white;
    border: none;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-stripe:hover {
    background: linear-gradient(135deg, #4a45d6 0%, #3730c7 100%);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(99, 91, 255, 0.3);
}

.btn-success {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #4a9628 0%, #96c955 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(86, 171, 47, 0.3);
}

.btn-secondary {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Feature list styling */
.feature-list .feature-item {
    display: flex;
    align-items-start;
    gap: 15px;
}

.feature-list .feature-item i {
    margin-top: 3px;
    flex-shrink: 0;
}

.feature-list .feature-item div {
    flex: 1;
}

/* Payment box */
.payment-box {
    background: linear-gradient(135deg, #f8f9fc 0%, #e3e6f0 100%);
    border-radius: 15px;
    border: 2px solid #635bff30;
}

.amount-display {
    padding: 15px;
    background: white;
    border-radius: 10px;
}

/* Trust badges */
.trust-badges {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    background: #f8f9fc;
    border-radius: 8px;
}

/* Pulse animation for recommended badge */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.8;
        transform: scale(1.05);
    }
}

.pulse {
    animation: pulse 2s ease-in-out infinite;
}

/* Select options styling */
select option {
    padding: 10px;
    font-size: 15px;
}

/* Alert styling */
.border-left-warning {
    border-left: 4px solid #f6c23e;
}

.border-left-secondary {
    border-left: 4px solid #858796;
}

/* Labels */
.font-weight-bold {
    font-weight: 700;
}

label .text-danger {
    font-size: 16px;
}

/* Shadow improvements */
.shadow-lg {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
}

.shadow-sm {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08) !important;
}

/* Hover effects */
.card:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.stripe-card:hover {
    box-shadow: 0 15px 40px rgba(99, 91, 255, 0.15);
}

/* Responsive */
@media (max-width: 768px) {
    .btn-lg {
        padding: 10px 20px;
        font-size: 14px;
    }
    
    .feature-list .feature-item {
        gap: 10px;
    }
    
    .payment-box {
        padding: 20px !important;
    }
}
</style>
@endsection
