@extends('layouts.layout')

@section('title', 'BookShare - Détails de l\'Échange (Admin)')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-eye text-primary mr-2"></i>
                Détails de l'Échange (Administration)
            </h1>
            <p class="mb-0 text-gray-600">Vue administrateur avec toutes les informations et actions</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.exchanges.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i>Retour à la liste
            </a>
            <a href="{{ route('admin.exchanges.edit', $exchange->id) }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-edit mr-1"></i>Modifier
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Exchange Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exchange-alt fa-sm text-primary"></i> Informations de l'Échange
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Type d'échange:</h6>
                            <span class="badge badge-info mb-3">{{ $exchange->type }}</span>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Statut:</h6>
                            @php
                                $statusClass = 'secondary';
                                switch($exchange->status) {
                                    case 'EN_ATTENTE':
                                        $statusClass = 'warning';
                                        break;
                                    case 'EN_COURS':
                                        $statusClass = 'primary';
                                        break;
                                    case 'TERMINE':
                                        $statusClass = 'success';
                                        break;
                                    case 'ANNULE':
                                        $statusClass = 'danger';
                                        break;
                                }
                            @endphp
                            <span class="badge badge-{{ $statusClass }} mb-3">{{ $exchange->status }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Date de début:</h6>
                            <p class="text-gray-600">{{ \Carbon\Carbon::parse($exchange->dateDebut)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Date de fin:</h6>
                            <p class="text-gray-600">{{ \Carbon\Carbon::parse($exchange->dateFin)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Créé le:</h6>
                            <p class="text-gray-600">{{ $exchange->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Dernière modification:</h6>
                            <p class="text-gray-600">{{ $exchange->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participants Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users fa-sm text-primary"></i> Participants
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Initiateur:</h6>
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-weight-bold">{{ $exchange->initiateur?->name ?? 'N/A' }}</div>
                                    <div class="text-gray-600">{{ $exchange->initiateur?->email ?? 'N/A' }}</div>
                                    <div class="small text-muted">ID: {{ $exchange->userInitiateurId }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Récepteur:</h6>
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="icon-circle bg-secondary">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-weight-bold">{{ $exchange->recepteur?->name ?? 'N/A' }}</div>
                                    <div class="text-gray-600">{{ $exchange->recepteur?->email ?? 'N/A' }}</div>
                                    <div class="small text-muted">ID: {{ $exchange->userRecepteurId }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Books Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book fa-sm text-primary"></i> Livres
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Livre Demandé:</h6>
                            @if($exchange->bookDemande)
                                <div class="card border-left-info">
                                    <div class="card-body py-3">
                                        <h6 class="font-weight-bold text-gray-800">{{ $exchange->bookDemande->title }}</h6>
                                        <p class="text-gray-600 mb-1">Auteur: {{ $exchange->bookDemande->author ?? 'N/A' }}</p>
                                        <p class="text-gray-600 mb-0">Propriétaire: {{ $exchange->bookDemande->user?->name ?? 'N/A' }}</p>
                                        <div class="small text-muted">ID: {{ $exchange->bookDemandeId }}</div>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500">Aucun livre demandé</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Livre Offert:</h6>
                            @if($exchange->bookOffert)
                                <div class="card border-left-success">
                                    <div class="card-body py-3">
                                        <h6 class="font-weight-bold text-gray-800">{{ $exchange->bookOffert->title }}</h6>
                                        <p class="text-gray-600 mb-1">Auteur: {{ $exchange->bookOffert->author ?? 'N/A' }}</p>
                                        <p class="text-gray-600 mb-0">Propriétaire: {{ $exchange->bookOffert->user?->name ?? 'N/A' }}</p>
                                        <div class="small text-muted">ID: {{ $exchange->bookOffertId }}</div>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500">Aucun livre offert ({{ $exchange->type === 'ECHANGE' ? 'Échange incomplet' : 'Non applicable' }})</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Admin Actions Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-tools fa-sm text-danger"></i> Actions Administrateur
                    </h6>
                </div>
                <div class="card-body">
                    @if($exchange->status === 'EN_ATTENTE')
                        <form method="POST" action="{{ route('admin.exchanges.supervise', $exchange->id) }}" class="mb-3">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check mr-2"></i>Approuver l'Échange
                            </button>
                        </form>
                    @endif

                    @if(in_array($exchange->status, ['EN_ATTENTE', 'EN_COURS']))
                        <form method="POST" action="{{ route('admin.exchanges.cancel', $exchange->id) }}" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cet échange ?')" class="mb-3">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-times mr-2"></i>Annuler l'Échange
                            </button>
                        </form>
                    @endif

                    <hr>

                    <a href="{{ route('admin.exchanges.edit', $exchange->id) }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-edit mr-2"></i>Modifier l'Échange
                    </a>

                    <form method="POST" action="{{ route('admin.exchanges.destroy', $exchange->id) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cet échange ?')" class="mb-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-block">
                            <i class="fas fa-trash mr-2"></i>Supprimer Définitivement
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line fa-sm text-primary"></i> Statistiques Rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Durée prévue:</strong>
                        <span class="text-gray-600">
                            {{ \Carbon\Carbon::parse($exchange->dateDebut)->diffInDays(\Carbon\Carbon::parse($exchange->dateFin)) + 1 }} jour(s)
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Âge de l'échange:</strong>
                        <span class="text-gray-600">{{ $exchange->created_at->diffForHumans() }}</span>
                    </div>
                    @if($exchange->status === 'EN_COURS')
                        <div class="mb-3">
                            <strong>Temps restant:</strong>
                            <span class="text-gray-600">
                                @if(\Carbon\Carbon::parse($exchange->dateFin)->isFuture())
                                    {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($exchange->dateFin)) + 1 }} jour(s)
                                @else
                                    <span class="text-danger">Dépassé de {{ \Carbon\Carbon::parse($exchange->dateFin)->diffInDays(\Carbon\Carbon::now()) }} jour(s)</span>
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endpush