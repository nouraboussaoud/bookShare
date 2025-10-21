@extends('layouts.layout')

@section('title', 'Effectuer le Paiement')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Retour -->
            <div class="mb-3">
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Retour aux paiements
                </a>
            </div>

            <!-- Page Heading -->
            <div class="card shadow-lg mb-4" style="border: none; border-radius: 15px;">
                <div class="card-header py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0;">
                    <h3 class="m-0 font-weight-bold text-white text-center">
                        <i class="fas fa-credit-card"></i> Effectuer le Paiement
                    </h3>
                </div>

                <div class="card-body p-4">
                    @if($payment->statut_paiement !== 'en_attente')
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Ce paiement a déjà été traité.
                        </div>
                        <div class="text-center">
                            <a href="{{ route('payments.index') }}" class="btn btn-primary">Retour aux paiements</a>
                        </div>
                    @else
                        <!-- Informations sur la location -->
                        <div class="card mb-4" style="border: 2px solid #e3e6f0; border-radius: 10px;">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="fas fa-book text-primary"></i> Détails de la Location
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Livre:</strong></p>
                                        <p class="text-muted">{{ $payment->location->book->title }}</p>
                                        
                                        <p><strong>Auteur:</strong></p>
                                        <p class="text-muted">{{ $payment->location->book->author }}</p>
                                        
                                        <p><strong>Propriétaire:</strong></p>
                                        <p class="text-muted">
                                            <i class="fas fa-user-circle"></i> {{ $payment->location->proprietaire->name }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Durée:</strong></p>
                                        <p class="text-muted">{{ $payment->location->duree_jours }} jours</p>
                                        
                                        <p><strong>Date de début:</strong></p>
                                        <p class="text-muted">{{ $payment->location->date_location->format('d/m/Y') }}</p>
                                        
                                        <p><strong>Date de fin prévue:</strong></p>
                                        <p class="text-muted">{{ $payment->location->date_fin_prevue->format('d/m/Y') }}</p>
                                    </div>
                                </div>

                                @if($payment->location->localisation)
                                    <p><strong>Lieu de rencontre:</strong></p>
                                    <p class="text-muted">
                                        <i class="fas fa-map-marker-alt text-danger"></i> {{ $payment->location->localisation }}
                                    </p>
                                @endif

                                @if($payment->notes)
                                    <div class="alert alert-light mt-3">
                                        <strong>Note:</strong><br>
                                        {{ $payment->notes }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Montant à payer -->
                        <div class="alert alert-success mb-4" style="border-radius: 10px; border: 2px solid #28a745;">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="mb-0">
                                        <i class="fas fa-money-bill-wave"></i> Montant à payer:
                                    </h4>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <h2 class="mb-0 text-success">
                                        <strong>{{ number_format($payment->montant, 2) }}€</strong>
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <!-- Paiement Stripe (Recommandé) -->
                        @if(config('stripe.secret') && config('stripe.key'))
                        <div class="card mb-4" style="border: 3px solid #635bff; border-radius: 15px; background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="fab fa-stripe fa-3x" style="color: #635bff;"></i>
                                </div>
                                <h5 class="mb-3" style="color: #635bff; font-weight: 700;">
                                    <i class="fas fa-star"></i> Paiement Sécurisé par Carte
                                </h5>
                                <p class="text-muted mb-4">
                                    Paiement instantané et 100% sécurisé via Stripe<br>
                                    <small><i class="fas fa-shield-alt"></i> Protection des acheteurs incluse</small>
                                </p>
                                <form action="{{ route('stripe.checkout', $payment) }}" method="POST" id="stripeForm">
                                    @csrf
                                    <button type="submit" class="btn btn-lg text-white" style="background: linear-gradient(135deg, #635bff 0%, #5469d4 100%); border: none; border-radius: 10px; padding: 15px 40px; font-weight: 600; box-shadow: 0 4px 15px rgba(99, 91, 255, 0.3);">
                                        <i class="fas fa-credit-card"></i> Payer {{ number_format($payment->montant, 2) }}€ avec Stripe
                                    </button>
                                </form>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-lock"></i> Paiement sécurisé SSL 256-bit
                                </small>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-warning mb-4" style="border-radius: 15px;">
                            <h5><i class="fas fa-exclamation-triangle"></i> Paiement Stripe temporairement indisponible</h5>
                            <p class="mb-0">Les clés Stripe ne sont pas encore configurées. Veuillez utiliser une méthode de paiement alternative ci-dessous ou contactez l'administrateur.</p>
                        </div>
                        @endif

                        <!-- Divider -->
                        <div class="text-center mb-4">
                            <span class="badge bg-secondary">OU</span>
                        </div>

                        <!-- Formulaire de paiement manuel -->
                        <form action="{{ route('payments.process', $payment) }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label" style="font-weight: 600; font-size: 1.1rem;">
                                    <i class="fas fa-money-bill-wave"></i> Autres Méthodes de Paiement (Manuel)
                                </label>
                                <p class="text-muted small mb-3">Ces méthodes nécessitent une validation manuelle par le propriétaire</p>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="card payment-method" onclick="selectPayment('carte')" style="cursor: pointer; border: 2px solid #e3e6f0; transition: all 0.3s;">
                                            <div class="card-body text-center">
                                                <i class="fas fa-credit-card fa-2x mb-2 text-primary"></i>
                                                <h6>Carte Bancaire</h6>
                                                <input type="radio" name="methode_paiement" value="carte" id="carte" required class="form-check-input">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card payment-method" onclick="selectPayment('paypal')" style="cursor: pointer; border: 2px solid #e3e6f0; transition: all 0.3s;">
                                            <div class="card-body text-center">
                                                <i class="fab fa-paypal fa-2x mb-2 text-primary"></i>
                                                <h6>PayPal</h6>
                                                <input type="radio" name="methode_paiement" value="paypal" id="paypal" required class="form-check-input">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card payment-method" onclick="selectPayment('virement')" style="cursor: pointer; border: 2px solid #e3e6f0; transition: all 0.3s;">
                                            <div class="card-body text-center">
                                                <i class="fas fa-university fa-2x mb-2 text-primary"></i>
                                                <h6>Virement Bancaire</h6>
                                                <input type="radio" name="methode_paiement" value="virement" id="virement" required class="form-check-input">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card payment-method" onclick="selectPayment('especes')" style="cursor: pointer; border: 2px solid #e3e6f0; transition: all 0.3s;">
                                            <div class="card-body text-center">
                                                <i class="fas fa-money-bill-alt fa-2x mb-2 text-primary"></i>
                                                <h6>Espèces</h6>
                                                <input type="radio" name="methode_paiement" value="especes" id="especes" required class="form-check-input">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @error('methode_paiement')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Référence de transaction (optionnelle) -->
                            <div class="mb-4">
                                <label for="reference_transaction" class="form-label">
                                    <i class="fas fa-hashtag"></i> Référence de Transaction (optionnelle)
                                </label>
                                <input type="text" class="form-control" id="reference_transaction" name="reference_transaction" 
                                       placeholder="Ex: TRX123456789">
                                <small class="text-muted">Si vous avez déjà effectué un virement ou un paiement PayPal, indiquez la référence ici.</small>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg" style="border-radius: 10px;">
                                    <i class="fas fa-check-circle"></i> Confirmer le Paiement
                                </button>
                                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                            </div>
                        </form>

                        <!-- Info sécurité -->
                        <div class="alert alert-info mt-4" style="border-radius: 10px;">
                            <i class="fas fa-shield-alt"></i> <strong>Paiement sécurisé</strong><br>
                            <small>Vos informations de paiement sont sécurisées. Une fois le paiement confirmé, le propriétaire sera notifié et pourra démarrer la location.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-method:hover {
    border-color: #667eea !important;
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.payment-method.selected {
    border-color: #667eea !important;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
}

.form-check-input {
    display: none;
}

.card {
    transition: all 0.3s ease;
}
</style>

<script>
function selectPayment(method) {
    // Désélectionner tous les éléments
    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('selected');
    });
    
    // Sélectionner l'élément cliqué
    document.getElementById(method).checked = true;
    document.getElementById(method).closest('.payment-method').classList.add('selected');
}
</script>
@endsection
