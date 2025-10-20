@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Modifier le Paiement #{{ $reservationPayment->id }}</h1>
        <a href="{{ route('reservation-payments.show', $reservationPayment) }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Modifier les Informations</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('reservation-payments.update', $reservationPayment) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Réservation (non modifiable) -->
                        <div class="form-group">
                            <label>Réservation</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="#{{ $reservationPayment->location_id }} - {{ $reservationPayment->location->book->title }}" 
                                   disabled>
                            <small class="form-text text-muted">La réservation ne peut pas être modifiée</small>
                        </div>

                        <!-- Montant -->
                        <div class="form-group">
                            <label for="montant">Montant (€) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   step="0.01" 
                                   name="montant" 
                                   id="montant" 
                                   class="form-control @error('montant') is-invalid @enderror" 
                                   value="{{ old('montant', $reservationPayment->montant) }}" 
                                   required>
                            @error('montant')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type de Paiement -->
                        <div class="form-group">
                            <label for="type_paiement">Type de Paiement <span class="text-danger">*</span></label>
                            <select name="type_paiement" id="type_paiement" class="form-control @error('type_paiement') is-invalid @enderror" required>
                                <option value="location" {{ old('type_paiement', $reservationPayment->type_paiement) == 'location' ? 'selected' : '' }}>Location</option>
                                <option value="caution" {{ old('type_paiement', $reservationPayment->type_paiement) == 'caution' ? 'selected' : '' }}>Caution</option>
                                <option value="penalite" {{ old('type_paiement', $reservationPayment->type_paiement) == 'penalite' ? 'selected' : '' }}>Pénalité</option>
                                <option value="remboursement" {{ old('type_paiement', $reservationPayment->type_paiement) == 'remboursement' ? 'selected' : '' }}>Remboursement</option>
                            </select>
                            @error('type_paiement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Statut du Paiement -->
                        <div class="form-group">
                            <label for="statut_paiement">Statut du Paiement <span class="text-danger">*</span></label>
                            <select name="statut_paiement" id="statut_paiement" class="form-control @error('statut_paiement') is-invalid @enderror" required>
                                <option value="en_attente" {{ old('statut_paiement', $reservationPayment->statut_paiement) == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="complete" {{ old('statut_paiement', $reservationPayment->statut_paiement) == 'complete' ? 'selected' : '' }}>Complété</option>
                                <option value="echoue" {{ old('statut_paiement', $reservationPayment->statut_paiement) == 'echoue' ? 'selected' : '' }}>Échoué</option>
                                <option value="rembourse" {{ old('statut_paiement', $reservationPayment->statut_paiement) == 'rembourse' ? 'selected' : '' }}>Remboursé</option>
                                <option value="annule" {{ old('statut_paiement', $reservationPayment->statut_paiement) == 'annule' ? 'selected' : '' }}>Annulé</option>
                            </select>
                            @error('statut_paiement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Méthode de Paiement -->
                        <div class="form-group">
                            <label for="methode_paiement">Méthode de Paiement</label>
                            <select name="methode_paiement" id="methode_paiement" class="form-control @error('methode_paiement') is-invalid @enderror">
                                <option value="">-- Sélectionner --</option>
                                <option value="Carte bancaire" {{ old('methode_paiement', $reservationPayment->methode_paiement) == 'Carte bancaire' ? 'selected' : '' }}>Carte bancaire</option>
                                <option value="Espèces" {{ old('methode_paiement', $reservationPayment->methode_paiement) == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                                <option value="Virement" {{ old('methode_paiement', $reservationPayment->methode_paiement) == 'Virement' ? 'selected' : '' }}>Virement</option>
                                <option value="PayPal" {{ old('methode_paiement', $reservationPayment->methode_paiement) == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                                <option value="Stripe" {{ old('methode_paiement', $reservationPayment->methode_paiement) == 'Stripe' ? 'selected' : '' }}>Stripe</option>
                                <option value="Autre" {{ old('methode_paiement', $reservationPayment->methode_paiement) == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('methode_paiement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Référence de Transaction -->
                        <div class="form-group">
                            <label for="reference_transaction">Référence de Transaction</label>
                            <input type="text" 
                                   name="reference_transaction" 
                                   id="reference_transaction" 
                                   class="form-control @error('reference_transaction') is-invalid @enderror" 
                                   value="{{ old('reference_transaction', $reservationPayment->reference_transaction) }}"
                                   placeholder="Ex: TXN123456">
                            @error('reference_transaction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date de Paiement -->
                        <div class="form-group">
                            <label for="date_paiement">Date de Paiement</label>
                            <input type="date" 
                                   name="date_paiement" 
                                   id="date_paiement" 
                                   class="form-control @error('date_paiement') is-invalid @enderror" 
                                   value="{{ old('date_paiement', $reservationPayment->date_paiement?->format('Y-m-d')) }}">
                            @error('date_paiement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date de Remboursement -->
                        <div class="form-group">
                            <label for="date_remboursement">Date de Remboursement</label>
                            <input type="date" 
                                   name="date_remboursement" 
                                   id="date_remboursement" 
                                   class="form-control @error('date_remboursement') is-invalid @enderror" 
                                   value="{{ old('date_remboursement', $reservationPayment->date_remboursement?->format('Y-m-d')) }}">
                            @error('date_remboursement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3" 
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="Informations complémentaires...">{{ old('notes', $reservationPayment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                            <a href="{{ route('reservation-payments.show', $reservationPayment) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Informations</h6>
                </div>
                <div class="card-body">
                    <p><strong>ID Paiement:</strong> {{ $reservationPayment->id }}</p>
                    <p><strong>Créé le:</strong> {{ $reservationPayment->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Dernière modification:</strong> {{ $reservationPayment->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
