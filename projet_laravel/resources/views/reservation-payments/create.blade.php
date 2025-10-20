@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading avec design moderne -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                <i class="fas fa-money-bill-wave text-success"></i>
                Nouveau Paiement de Réservation
            </h1>
            <p class="text-muted small mb-0 mt-1">Enregistrez un paiement pour une réservation</p>
        </div>
        <a href="{{ route('reservation-payments.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Formulaire Principal avec design amélioré -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-edit"></i> Informations du Paiement
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('reservation-payments.store') }}" method="POST">
                        @csrf

                        <!-- Réservation avec style moderne -->
                        <div class="form-group mb-4">
                            <label for="location_id" class="font-weight-bold text-primary">
                                <i class="fas fa-book-reader"></i> Réservation 
                                <span class="text-danger">*</span>
                            </label>
                            <select name="location_id" id="location_id" class="form-control form-control-lg @error('location_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner une réservation --</option>
                                @forelse($locations as $loc)
                                    <option value="{{ $loc->id }}" 
                                            {{ old('location_id', $location?->id) == $loc->id ? 'selected' : '' }}
                                            data-price="{{ $loc->prix }}">
                                        #{{ $loc->id }} - {{ $loc->book->title }} 
                                        ({{ $loc->date_location->format('d/m/Y') }}) - 
                                        {{ number_format($loc->prix, 2) }}€
                                    </option>
                                @empty
                                    <option value="" disabled>Aucune réservation disponible</option>
                                @endforelse
                            </select>
                            @error('location_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Sélectionnez la réservation pour laquelle vous souhaitez enregistrer un paiement
                                </small>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Montant -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="montant" class="font-weight-bold text-primary">
                                        <i class="fas fa-euro-sign"></i> Montant (€) 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <input type="number" 
                                               step="0.01" 
                                               name="montant" 
                                               id="montant" 
                                               class="form-control @error('montant') is-invalid @enderror" 
                                               value="{{ old('montant', $location?->prix) }}" 
                                               placeholder="0.00"
                                               required>
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-light">€</span>
                                        </div>
                                        @error('montant')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Type de Paiement -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="type_paiement" class="font-weight-bold text-primary">
                                        <i class="fas fa-tag"></i> Type de Paiement 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="type_paiement" id="type_paiement" class="form-control form-control-lg @error('type_paiement') is-invalid @enderror" required>
                                        <option value="location" {{ old('type_paiement') == 'location' ? 'selected' : '' }}>
                                            💰 Location
                                        </option>
                                        <option value="caution" {{ old('type_paiement') == 'caution' ? 'selected' : '' }}>
                                            🔒 Caution
                                        </option>
                                        <option value="penalite" {{ old('type_paiement') == 'penalite' ? 'selected' : '' }}>
                                            ⚠️ Pénalité
                                        </option>
                                        <option value="remboursement" {{ old('type_paiement') == 'remboursement' ? 'selected' : '' }}>
                                            🔄 Remboursement
                                        </option>
                                    </select>
                                    @error('type_paiement')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Statut du Paiement -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="statut_paiement" class="font-weight-bold text-primary">
                                        <i class="fas fa-check-circle"></i> Statut 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="statut_paiement" id="statut_paiement" class="form-control form-control-lg @error('statut_paiement') is-invalid @enderror" required>
                                        <option value="en_attente" {{ old('statut_paiement', 'en_attente') == 'en_attente' ? 'selected' : '' }}>
                                            ⏳ En attente
                                        </option>
                                        <option value="complete" {{ old('statut_paiement') == 'complete' ? 'selected' : '' }}>
                                            ✅ Complété
                                        </option>
                                        <option value="echoue" {{ old('statut_paiement') == 'echoue' ? 'selected' : '' }}>
                                            ❌ Échoué
                                        </option>
                                        <option value="rembourse" {{ old('statut_paiement') == 'rembourse' ? 'selected' : '' }}>
                                            💸 Remboursé
                                        </option>
                                        <option value="annule" {{ old('statut_paiement') == 'annule' ? 'selected' : '' }}>
                                            🚫 Annulé
                                        </option>
                                    </select>
                                    @error('statut_paiement')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Méthode de Paiement -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="methode_paiement" class="font-weight-bold text-primary">
                                        <i class="fas fa-credit-card"></i> Méthode de Paiement
                                    </label>
                                    <select name="methode_paiement" id="methode_paiement" class="form-control form-control-lg @error('methode_paiement') is-invalid @enderror">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="Carte bancaire" {{ old('methode_paiement') == 'Carte bancaire' ? 'selected' : '' }}>
                                            💳 Carte bancaire
                                        </option>
                                        <option value="Espèces" {{ old('methode_paiement') == 'Espèces' ? 'selected' : '' }}>
                                            💵 Espèces
                                        </option>
                                        <option value="Virement" {{ old('methode_paiement') == 'Virement' ? 'selected' : '' }}>
                                            🏦 Virement
                                        </option>
                                        <option value="PayPal" {{ old('methode_paiement') == 'PayPal' ? 'selected' : '' }}>
                                            🅿️ PayPal
                                        </option>
                                        <option value="Stripe" {{ old('methode_paiement') == 'Stripe' ? 'selected' : '' }}>
                                            🔷 Stripe
                                        </option>
                                        <option value="Autre" {{ old('methode_paiement') == 'Autre' ? 'selected' : '' }}>
                                            ➕ Autre
                                        </option>
                                    </select>
                                    @error('methode_paiement')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Référence de Transaction -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="reference_transaction" class="font-weight-bold text-primary">
                                        <i class="fas fa-hashtag"></i> Référence de Transaction
                                    </label>
                                    <input type="text" 
                                           name="reference_transaction" 
                                           id="reference_transaction" 
                                           class="form-control form-control-lg @error('reference_transaction') is-invalid @enderror" 
                                           value="{{ old('reference_transaction') }}"
                                           placeholder="Ex: TXN123456">
                                    @error('reference_transaction')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Date de Paiement -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="date_paiement" class="font-weight-bold text-primary">
                                        <i class="fas fa-calendar-alt"></i> Date de Paiement
                                    </label>
                                    <input type="date" 
                                           name="date_paiement" 
                                           id="date_paiement" 
                                           class="form-control form-control-lg @error('date_paiement') is-invalid @enderror" 
                                           value="{{ old('date_paiement', now()->format('Y-m-d')) }}">
                                    @error('date_paiement')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="form-group mb-4">
                            <label for="notes" class="font-weight-bold text-primary">
                                <i class="fas fa-sticky-note"></i> Notes
                            </label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="4" 
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="Ajoutez des informations complémentaires...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Boutons d'action -->
                        <div class="form-group text-right">
                            <a href="{{ route('reservation-payments.index') }}" class="btn btn-secondary btn-lg px-5">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5 ml-2">
                                <i class="fas fa-save"></i> Enregistrer le Paiement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Carte d'aide avec design moderne -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-gradient-info text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-lightbulb"></i> Aide & Informations
                    </h6>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold text-info mb-3">
                        <i class="fas fa-tag"></i> Types de Paiement
                    </h6>
                    <div class="mb-3">
                        <div class="d-flex align-items-start mb-2">
                            <span class="badge badge-primary badge-pill mr-2 mt-1">💰</span>
                            <div>
                                <strong>Location</strong>
                                <p class="text-muted small mb-0">Paiement du prix de location du livre</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-2">
                            <span class="badge badge-warning badge-pill mr-2 mt-1">🔒</span>
                            <div>
                                <strong>Caution</strong>
                                <p class="text-muted small mb-0">Dépôt de garantie remboursable</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-2">
                            <span class="badge badge-danger badge-pill mr-2 mt-1">⚠️</span>
                            <div>
                                <strong>Pénalité</strong>
                                <p class="text-muted small mb-0">Frais de retard ou dommages</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-2">
                            <span class="badge badge-success badge-pill mr-2 mt-1">🔄</span>
                            <div>
                                <strong>Remboursement</strong>
                                <p class="text-muted small mb-0">Retour de fonds au locataire</p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="font-weight-bold text-info mb-3 mt-3">
                        <i class="fas fa-check-circle"></i> Statuts de Paiement
                    </h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <span class="badge badge-warning">⏳</span>
                            <strong class="ml-2">En attente:</strong>
                            <small class="text-muted d-block ml-4">Paiement non encore effectué</small>
                        </li>
                        <li class="mb-2">
                            <span class="badge badge-success">✅</span>
                            <strong class="ml-2">Complété:</strong>
                            <small class="text-muted d-block ml-4">Paiement reçu et validé</small>
                        </li>
                        <li class="mb-2">
                            <span class="badge badge-danger">❌</span>
                            <strong class="ml-2">Échoué:</strong>
                            <small class="text-muted d-block ml-4">Transaction échouée</small>
                        </li>
                        <li class="mb-2">
                            <span class="badge badge-info">💸</span>
                            <strong class="ml-2">Remboursé:</strong>
                            <small class="text-muted d-block ml-4">Fonds retournés au payeur</small>
                        </li>
                        <li class="mb-2">
                            <span class="badge badge-secondary">🚫</span>
                            <strong class="ml-2">Annulé:</strong>
                            <small class="text-muted d-block ml-4">Transaction annulée</small>
                        </li>
                    </ul>

                    <hr>

                    <div class="alert alert-warning mt-3" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Important:</strong>
                        <p class="small mb-0 mt-2">
                            Assurez-vous que toutes les informations sont correctes avant d'enregistrer le paiement.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistiques rapides -->
            @if($locations->count() > 0)
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-line"></i> Vos Réservations
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-6 border-right">
                            <h3 class="text-primary font-weight-bold">{{ $locations->count() }}</h3>
                            <p class="text-muted small mb-0">Total</p>
                        </div>
                        <div class="col-6">
                            <h3 class="text-success font-weight-bold">
                                {{ $locations->where('statut', 'confirmee')->count() + $locations->where('statut', 'en_cours')->count() }}
                            </h3>
                            <p class="text-muted small mb-0">Actives</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.form-control-lg {
    border-radius: 10px;
    border: 2px solid #e3e6f0;
    transition: all 0.3s ease;
}

.form-control-lg:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-lg {
    border-radius: 10px;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-lg:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

select option {
    padding: 10px;
}

.badge-pill {
    padding: 8px 12px;
    font-size: 14px;
}
</style>

<script>
// Auto-remplir le montant quand une réservation est sélectionnée
document.getElementById('location_id').addEventListener('change', function() {
    var selectedOption = this.options[this.selectedIndex];
    var price = selectedOption.getAttribute('data-price');
    if (price) {
        document.getElementById('montant').value = price;
    }
});
</script>
@endsection
