@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye fa-sm text-primary"></i>
            Détails de la réservation
        </h1>
        <a href="{{ route('locations.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour aux réservations
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book"></i>
                        {{ $location->book->title }}
                    </h6>
                    <div>
                        @if($location->statut === 'en_attente')
                            <span class="badge badge-warning badge-lg">En attente</span>
                        @elseif($location->statut === 'confirmee')
                            <span class="badge badge-info badge-lg">Confirmée</span>
                        @elseif($location->statut === 'en_cours')
                            <span class="badge badge-success badge-lg">En cours</span>
                            @if($location->estEnRetard())
                                <span class="badge badge-danger badge-lg">En retard ({{ $location->joursDeRetard() }} jours)</span>
                            @endif
                        @elseif($location->statut === 'terminee')
                            <span class="badge badge-secondary badge-lg">Terminée</span>
                        @else
                            <span class="badge badge-dark badge-lg">Annulée</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Image du livre -->
                        <div class="col-md-4">
                            @if($location->book->hasPhoto())
                                <img src="{{ $location->book->photo_url }}" alt="{{ $location->book->title }}" class="img-fluid rounded shadow">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded shadow" style="height: 250px;">
                                    <i class="fas fa-book fa-4x text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Détails du livre -->
                        <div class="col-md-8">
                            <h5 class="mb-3">{{ $location->book->title }}</h5>
                            <p><strong>Auteur:</strong> {{ $location->book->author }}</p>
                            @if($location->book->category)
                                <p><strong>Catégorie:</strong> {{ $location->book->category->name }}</p>
                            @endif
                            <p><strong>Âge recommandé:</strong> {{ $location->book->age_display }}</p>
                            @if($location->book->description)
                                <p><strong>Description:</strong></p>
                                <p class="text-muted">{{ $location->book->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails de la location -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-handshake"></i>
                        Informations de la location
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong><i class="fas fa-user text-primary"></i> Propriétaire:</strong>
                                <p class="mb-0">{{ $location->proprietaire->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-user-check text-info"></i> Locataire:</strong>
                                <p class="mb-0">{{ $location->locataire->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-map-marker-alt text-warning"></i> Lieu:</strong>
                                <p class="mb-0">{{ $location->localisation }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong><i class="fas fa-calendar-alt text-success"></i> Date de début:</strong>
                                <p class="mb-0">{{ $location->date_location->format('d/m/Y') }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-calendar-check text-danger"></i> Date de fin prévue:</strong>
                                <p class="mb-0">{{ $location->date_fin_prevue->format('d/m/Y') }}</p>
                            </div>
                            
                            @if($location->date_retour_effective)
                                <div class="mb-3">
                                    <strong><i class="fas fa-calendar-times text-secondary"></i> Date de retour effective:</strong>
                                    <p class="mb-0">{{ $location->date_retour_effective->format('d/m/Y') }}</p>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-clock text-info"></i> Durée:</strong>
                                <p class="mb-0">{{ $location->duree_jours }} jours</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-light border-left-success">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-euro-sign fa-2x text-success mr-3"></i>
                                    <div>
                                        <h5 class="mb-1">Prix de la location</h5>
                                        <h3 class="text-success mb-0">{{ number_format($location->prix, 2) }}€</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($location->notes)
                        <div class="mt-3">
                            <strong><i class="fas fa-sticky-note text-warning"></i> Notes:</strong>
                            <div class="alert alert-light mt-2">
                                {{ $location->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions et statut -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs"></i>
                        Actions
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Bouton voir les paiements -->
                    <div class="mb-3">
                        <a href="{{ route('locations.payments', $location) }}" class="btn btn-info btn-block">
                            <i class="fas fa-euro-sign"></i> Voir les Paiements
                        </a>
                    </div>

                    @if(Auth::id() === $location->locataire_id)
                        <!-- Actions pour le locataire -->
                        @if($location->statut === 'en_attente')
                            <div class="mb-3">
                                <a href="{{ route('locations.edit', $location) }}" class="btn btn-warning btn-block">
                                    <i class="fas fa-edit"></i> Modifier la demande
                                </a>
                            </div>
                            <div class="mb-3">
                                <form method="POST" action="{{ route('locations.destroy', $location) }}" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-block">
                                        <i class="fas fa-trash"></i> Supprimer la demande
                                    </button>
                                </form>
                            </div>
                        @endif
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            @if($location->statut === 'en_attente')
                                <strong>En attente</strong><br>
                                Votre demande a été envoyée au propriétaire. Vous recevrez une notification dès qu'il aura pris une décision.
                            @elseif($location->statut === 'confirmee')
                                <strong>Confirmée</strong><br>
                                Votre demande a été acceptée ! Veuillez effectuer le paiement ci-dessous.
                            @elseif($location->statut === 'en_cours')
                                <strong>En cours</strong><br>
                                Profitez de votre lecture ! N'oubliez pas de retourner le livre avant le {{ $location->date_fin_prevue->format('d/m/Y') }}.
                            @elseif($location->statut === 'terminee')
                                <strong>Terminée</strong><br>
                                Cette location est terminée. Merci d'avoir utilisé BookShare !
                            @else
                                <strong>Annulée</strong><br>
                                Cette demande de location a été annulée.
                            @endif
                        </div>

                        {{-- Section Paiement pour le locataire (location confirmée) --}}
                        @if($location->statut === 'confirmee')
                            @php
                                $paiementEnAttente = $location->getPaiementEnAttente();
                                $paiementComplete = $location->getPaiementComplete();
                            @endphp

                            @if($paiementComplete)
                                {{-- Paiement déjà effectué --}}
                                <div class="card border-left-success shadow-sm mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle fa-2x text-success mr-3"></i>
                                            <div>
                                                <h6 class="font-weight-bold text-success mb-1">Paiement effectué ✓</h6>
                                                <p class="small text-muted mb-0">
                                                    Montant: <strong>{{ number_format($paiementComplete->montant, 2) }}€</strong><br>
                                                    Référence: {{ $paiementComplete->reference_transaction }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($paiementEnAttente)
                                {{-- Facture en attente de paiement --}}
                                <div class="card border-left-warning shadow-sm mb-3">
                                    <div class="card-header bg-gradient-warning text-white py-2">
                                        <h6 class="m-0 font-weight-bold">
                                            <i class="fas fa-file-invoice-dollar"></i> Facture à Payer
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="payment-summary mb-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Location de livre:</span>
                                                <strong>{{ $location->duree_jours }} jours</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Prix par jour:</span>
                                                <span>{{ number_format($location->prix / $location->duree_jours, 2) }}€</span>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0">Total à payer:</h5>
                                                <h4 class="text-success font-weight-bold mb-0">
                                                    {{ number_format($paiementEnAttente->montant, 2) }}€
                                                </h4>
                                            </div>
                                        </div>

                                        {{-- Bouton Paiement Stripe Direct --}}
                                        <form action="{{ route('payments.stripe.checkout', $paiementEnAttente) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-stripe btn-lg btn-block shadow mb-2">
                                                <i class="fab fa-stripe fa-lg"></i> Payer avec Stripe
                                                <span class="badge badge-light ml-2">{{ number_format($paiementEnAttente->montant, 2) }}€</span>
                                            </button>
                                        </form>

                                        <div class="text-center my-2">
                                            <small class="text-muted">OU</small>
                                        </div>

                                        {{-- Bouton Autres Méthodes --}}
                                        <a href="{{ route('payments.show', $paiementEnAttente) }}" class="btn btn-outline-primary btn-block">
                                            <i class="fas fa-credit-card"></i> Autres méthodes de paiement
                                        </a>

                                        <div class="text-center mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-lock"></i> Paiement 100% sécurisé
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        
                    @elseif(Auth::id() === $location->proprietaire_id)
                        <!-- Actions pour le propriétaire -->
                        @if($location->statut === 'en_attente')
                            <div class="mb-3">
                                <form method="POST" action="{{ route('locations.confirmer', $location) }}" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-check"></i> Accepter la demande
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('locations.refuser', $location) }}"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir refuser cette demande ?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-block">
                                        <i class="fas fa-times"></i> Refuser la demande
                                    </button>
                                </form>
                            </div>
                        @elseif($location->statut === 'confirmee')
                            <div class="mb-3">
                                <form method="POST" action="{{ route('locations.demarrer', $location) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-play"></i> Démarrer la location
                                    </button>
                                </form>
                            </div>
                        @elseif($location->statut === 'en_cours')
                            <div class="mb-3">
                                <form method="POST" action="{{ route('locations.terminer', $location) }}"
                                      onsubmit="return confirm('Confirmez-vous que le livre a été retourné ?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-flag-checkered"></i> Terminer la location
                                    </button>
                                </form>
                            </div>
                        @endif
                        
                        <div class="alert alert-success">
                            <i class="fas fa-info-circle"></i>
                            @if($location->statut === 'en_attente')
                                <strong>Nouvelle demande</strong><br>
                                {{ $location->locataire->name }} souhaite louer votre livre. Acceptez ou refusez la demande.
                            @elseif($location->statut === 'confirmee')
                                <strong>Demande acceptée</strong><br>
                                Contactez {{ $location->locataire->name }} pour organiser la remise du livre, puis démarrez la location.
                            @elseif($location->statut === 'en_cours')
                                <strong>Location en cours</strong><br>
                                Votre livre est actuellement loué par {{ $location->locataire->name }}. 
                                @if($location->estEnRetard())
                                    <span class="text-danger">Le retour est en retard de {{ $location->joursDeRetard() }} jours.</span>
                                @endif
                            @elseif($location->statut === 'terminee')
                                <strong>Location terminée</strong><br>
                                Cette location est terminée. Votre livre vous a été retourné.
                            @else
                                <strong>Demande refusée</strong><br>
                                Vous avez refusé cette demande de location.
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Historique des statuts -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-history"></i>
                        Chronologie
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Demande créée</h6>
                                <small class="text-muted">{{ $location->created_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                        
                        @if($location->statut !== 'en_attente')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ $location->statut === 'annulee' ? 'danger' : 'success' }}"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">
                                        @if($location->statut === 'annulee')
                                            Demande refusée
                                        @else
                                            Demande acceptée
                                        @endif
                                    </h6>
                                    <small class="text-muted">{{ $location->updated_at->format('d/m/Y à H:i') }}</small>
                                </div>
                            </div>
                        @endif
                        
                        @if(in_array($location->statut, ['en_cours', 'terminee']))
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Location démarrée</h6>
                                    <small class="text-muted">{{ $location->date_location->format('d/m/Y') }}</small>
                                </div>
                            </div>
                        @endif
                        
                        @if($location->statut === 'terminee' && $location->date_retour_effective)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-secondary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Location terminée</h6>
                                    <small class="text-muted">{{ $location->date_retour_effective->format('d/m/Y à H:i') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e3e6f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-content {
    background: #f8f9fc;
    padding: 10px 15px;
    border-radius: 5px;
    border-left: 3px solid #e3e6f0;
}

.badge-lg {
    font-size: 0.9em;
    padding: 0.5em 0.75em;
}

/* Styles pour la section paiement */
.bg-gradient-warning {
    background: linear-gradient(135deg, #f6c23e 0%, #e8a825 100%);
}

.border-left-success {
    border-left: 4px solid #1cc88a;
}

.border-left-warning {
    border-left: 4px solid #f6c23e;
}

.payment-summary {
    background: #f8f9fc;
    padding: 15px;
    border-radius: 10px;
}

/* Bouton Stripe stylisé */
.btn-stripe {
    background: linear-gradient(135deg, #635bff 0%, #4a45d6 100%);
    color: white;
    border: none;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.btn-stripe:hover {
    background: linear-gradient(135deg, #4a45d6 0%, #3730c7 100%);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(99, 91, 255, 0.3);
}

.btn-stripe .badge-light {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    font-weight: 600;
}

.btn-outline-primary {
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
}

/* Amélioration des cards */
.card {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.shadow-sm {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08) !important;
}
</style>
@endsection
