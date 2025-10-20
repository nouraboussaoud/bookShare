@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Détails du Paiement #{{ $reservationPayment->id }}</h1>
        <div>
            <a href="{{ route('reservation-payments.edit', $reservationPayment) }}" class="btn btn-sm btn-warning shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Modifier
            </a>
            <a href="{{ route('reservation-payments.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Informations du Paiement -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations du Paiement</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>ID Paiement:</strong>
                            <p class="text-gray-800">#{{ $reservationPayment->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Réservation:</strong>
                            <p>
                                <a href="{{ route('locations.show', $reservationPayment->location_id) }}">
                                    #{{ $reservationPayment->location_id }}
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Montant:</strong>
                            <p class="text-gray-800 h4">{{ number_format($reservationPayment->montant, 2) }} €</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Type de Paiement:</strong>
                            <p>
                                <span class="badge badge-info badge-lg">
                                    {{ $reservationPayment->getTypeLabel() }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Statut:</strong>
                            <p>
                                <span class="badge {{ $reservationPayment->getStatutBadgeClass() }} badge-lg">
                                    {{ $reservationPayment->getStatutLabel() }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Méthode de Paiement:</strong>
                            <p class="text-gray-800">{{ $reservationPayment->methode_paiement ?? 'Non spécifiée' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Référence de Transaction:</strong>
                            <p class="text-gray-800">{{ $reservationPayment->reference_transaction ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Date de Paiement:</strong>
                            <p class="text-gray-800">
                                {{ $reservationPayment->date_paiement ? $reservationPayment->date_paiement->format('d/m/Y') : 'Non payé' }}
                            </p>
                        </div>
                    </div>

                    @if($reservationPayment->date_remboursement)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Date de Remboursement:</strong>
                            <p class="text-gray-800">{{ $reservationPayment->date_remboursement->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($reservationPayment->notes)
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong>Notes:</strong>
                            <p class="text-gray-800">{{ $reservationPayment->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Créé le:</strong>
                            <p class="text-gray-800">{{ $reservationPayment->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Dernière modification:</strong>
                            <p class="text-gray-800">{{ $reservationPayment->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($reservationPayment->location->proprietaire_id === Auth::id())
                    <div class="border-top pt-3 mt-3">
                        <h6 class="font-weight-bold text-primary">Actions</h6>
                        <div class="btn-group" role="group">
                            @if($reservationPayment->estEnAttente())
                            <form action="{{ route('reservation-payments.marquer-complete', $reservationPayment) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success" onclick="return confirm('Marquer ce paiement comme complet ?')">
                                    <i class="fas fa-check"></i> Marquer comme Complet
                                </button>
                            </form>
                            @endif

                            @if($reservationPayment->estComplete() && $reservationPayment->type_paiement === 'caution')
                            <form action="{{ route('reservation-payments.rembourser', $reservationPayment) }}" method="POST" class="d-inline ml-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-info" onclick="return confirm('Rembourser ce paiement ?')">
                                    <i class="fas fa-undo"></i> Rembourser
                                </button>
                            </form>
                            @endif

                            <form action="{{ route('reservation-payments.destroy', $reservationPayment) }}" method="POST" class="d-inline ml-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informations de la Réservation -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Réservation Associée</h6>
                </div>
                <div class="card-body">
                    @if($reservationPayment->location)
                    <div class="mb-3">
                        <strong>Livre:</strong>
                        <p>
                            @if($reservationPayment->location->book)
                                <a href="{{ route('books.show', $reservationPayment->location->book->id) }}">
                                    {{ $reservationPayment->location->book->title }}
                                </a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong>Propriétaire:</strong>
                        <p>
                            @if($reservationPayment->location->proprietaire)
                                {{ $reservationPayment->location->proprietaire->name }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong>Locataire:</strong>
                        <p>
                            @if($reservationPayment->location->locataire)
                                {{ $reservationPayment->location->locataire->name }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong>Prix de Location:</strong>
                        <p>{{ number_format($reservationPayment->location->prix, 2) }} €</p>
                    </div>

                    <div class="mb-3">
                        <strong>Date de Réservation:</strong>
                        <p>{{ $reservationPayment->location->date_location->format('d/m/Y') }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Durée:</strong>
                        <p>{{ $reservationPayment->location->duree_jours }} jours</p>
                    </div>

                    <div class="mb-3">
                        <strong>Statut:</strong>
                        <p>
                            @if($reservationPayment->location->statut === 'en_attente')
                                <span class="badge badge-warning">En attente</span>
                            @elseif($reservationPayment->location->statut === 'confirmee')
                                <span class="badge badge-info">Confirmée</span>
                            @elseif($reservationPayment->location->statut === 'en_cours')
                                <span class="badge badge-primary">En cours</span>
                            @elseif($reservationPayment->location->statut === 'terminee')
                                <span class="badge badge-success">Terminée</span>
                            @else
                                <span class="badge badge-secondary">{{ $reservationPayment->location->statut }}</span>
                            @endif
                        </p>
                    </div>

                    <a href="{{ route('locations.show', $reservationPayment->location->id) }}" class="btn btn-sm btn-primary btn-block">
                        <i class="fas fa-eye"></i> Voir la Réservation
                    </a>
                    @else
                        <p class="text-muted">Informations non disponibles</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
