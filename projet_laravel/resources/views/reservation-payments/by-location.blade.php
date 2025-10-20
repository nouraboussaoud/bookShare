@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Paiements de la Réservation #{{ $location->id }}</h1>
        <div>
            <a href="{{ route('reservation-payments.create', ['location_id' => $location->id]) }}" 
               class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Nouveau Paiement
            </a>
            <a href="{{ route('locations.show', $location) }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour à la Réservation
            </a>
        </div>
    </div>

    <!-- Informations de la Réservation -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informations de la Réservation</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Livre:</strong>
                    <p>{{ $location->book->title }}</p>
                </div>
                <div class="col-md-3">
                    <strong>Prix Total:</strong>
                    <p class="h5 text-primary">{{ number_format($location->prix, 2) }} €</p>
                </div>
                <div class="col-md-3">
                    <strong>Date:</strong>
                    <p>{{ $location->date_location->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-3">
                    <strong>Statut:</strong>
                    <p>
                        @if($location->statut === 'en_attente')
                            <span class="badge badge-warning">En attente</span>
                        @elseif($location->statut === 'confirmee')
                            <span class="badge badge-info">Confirmée</span>
                        @elseif($location->statut === 'en_cours')
                            <span class="badge badge-primary">En cours</span>
                        @elseif($location->statut === 'terminee')
                            <span class="badge badge-success">Terminée</span>
                        @else
                            <span class="badge badge-secondary">{{ $location->statut }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques des Paiements -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Payé
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($payments->where('statut_paiement', 'complete')->sum('montant'), 2) }} €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                En Attente
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($payments->where('statut_paiement', 'en_attente')->sum('montant'), 2) }} €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Remboursé
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($payments->where('statut_paiement', 'rembourse')->sum('montant'), 2) }} €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-undo fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Nombre de Paiements
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $payments->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des Paiements -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Paiements</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Méthode</th>
                            <th>Référence</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>
                                <span class="badge badge-info">{{ $payment->getTypeLabel() }}</span>
                            </td>
                            <td>{{ number_format($payment->montant, 2) }} €</td>
                            <td>
                                <span class="badge {{ $payment->getStatutBadgeClass() }}">
                                    {{ $payment->getStatutLabel() }}
                                </span>
                            </td>
                            <td>{{ $payment->methode_paiement ?? 'N/A' }}</td>
                            <td>{{ $payment->reference_transaction ?? 'N/A' }}</td>
                            <td>{{ $payment->date_paiement ? $payment->date_paiement->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('reservation-payments.show', $payment) }}" 
                                       class="btn btn-sm btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('reservation-payments.edit', $payment) }}" 
                                       class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                Aucun paiement enregistré pour cette réservation
                                <br>
                                <a href="{{ route('reservation-payments.create', ['location_id' => $location->id]) }}" 
                                   class="btn btn-sm btn-primary mt-2">
                                    <i class="fas fa-plus"></i> Ajouter un paiement
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
