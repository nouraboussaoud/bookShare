@extends('layouts.admin-layout')

@section('title', 'Détails de la Réservation')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-check text-primary"></i> Réservation #{{ $location->id }}
        </h1>
        <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
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
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informations de la Réservation</h6>
                    @if($location->statut === 'en_attente')
                        <span class="badge badge-warning badge-lg">En attente</span>
                    @elseif($location->statut === 'confirmee')
                        <span class="badge badge-info badge-lg">Confirmée</span>
                    @elseif($location->statut === 'en_cours')
                        <span class="badge badge-primary badge-lg">En cours</span>
                    @elseif($location->statut === 'terminee')
                        <span class="badge badge-success badge-lg">Terminée</span>
                    @else
                        <span class="badge badge-danger badge-lg">Refusée</span>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Livre -->
                    <div class="mb-4 pb-3 border-bottom">
                        <h5 class="text-primary mb-3"><i class="fas fa-book"></i> Livre</h5>
                        @if($location->book)
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Titre:</strong><br>{{ $location->book->titre }}</p>
                                    <p><strong>Auteur:</strong><br>{{ $location->book->auteur }}</p>
                                </div>
                                <div class="col-md-6">
                                    @if($location->book->image_couverture)
                                        <img src="{{ asset('storage/' . $location->book->image_couverture) }}" 
                                             alt="{{ $location->book->titre }}" 
                                             class="img-thumbnail" 
                                             style="max-width: 150px;">
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Livre supprimé</p>
                        @endif
                    </div>

                    <!-- Participants -->
                    <div class="mb-4 pb-3 border-bottom">
                        <h5 class="text-primary mb-3"><i class="fas fa-users"></i> Participants</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="font-weight-bold">Propriétaire</h6>
                                @if($location->proprietaire)
                                    <p>
                                        <strong>{{ $location->proprietaire->name }}</strong><br>
                                        {{ $location->proprietaire->email }}<br>
                                        <small class="text-muted">Tél: {{ $location->proprietaire->telephone ?? 'N/A' }}</small>
                                    </p>
                                @else
                                    <p class="text-muted">N/A</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6 class="font-weight-bold">Locataire</h6>
                                @if($location->locataire)
                                    <p>
                                        <strong>{{ $location->locataire->name }}</strong><br>
                                        {{ $location->locataire->email }}<br>
                                        <small class="text-muted">Tél: {{ $location->locataire->telephone ?? 'N/A' }}</small>
                                    </p>
                                @else
                                    <p class="text-muted">N/A</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="mb-4 pb-3 border-bottom">
                        <h5 class="text-primary mb-3"><i class="fas fa-calendar"></i> Dates</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Date de début:</strong><br>{{ \Carbon\Carbon::parse($location->date_location)->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Date de fin prévue:</strong><br>{{ \Carbon\Carbon::parse($location->date_fin_prevue)->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-md-4">
                                @if($location->date_retour_effective)
                                    <p><strong>Date de retour réelle:</strong><br>
                                        <span class="text-success">{{ \Carbon\Carbon::parse($location->date_retour_effective)->format('d/m/Y') }}</span>
                                    </p>
                                @else
                                    <p class="text-muted">Pas encore retourné</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Créée le -->
                    <div>
                        <p><strong>Réservation créée le:</strong> {{ $location->created_at->format('d/m/Y à H:i') }}</p>
                        <p><strong>Dernière mise à jour:</strong> {{ $location->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    @if($location->statut === 'en_attente')
                        <form method="POST" action="{{ route('admin.locations.approve', $location) }}" class="mb-3">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-block" 
                                    onclick="return confirm('Approuver cette réservation ?')">
                                <i class="fas fa-check"></i> Approuver
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.locations.reject', $location) }}" class="mb-3">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-warning btn-block"
                                    onclick="return confirm('Rejeter cette réservation ?')">
                                <i class="fas fa-times"></i> Rejeter
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Cette réservation a déjà été traitée.
                        </div>
                    @endif

                    <hr>

                    <form method="POST" action="{{ route('admin.locations.destroy', $location) }}"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette réservation ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Historique</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-3">
                            <div class="timeline-badge bg-primary">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h6 class="timeline-title">Réservation créée</h6>
                                    <p><small class="text-muted"><i class="fas fa-clock"></i> {{ $location->created_at->format('d/m/Y H:i') }}</small></p>
                                </div>
                            </div>
                        </div>

                        @if($location->statut !== 'en_attente')
                            <div class="timeline-item mb-3">
                                <div class="timeline-badge bg-{{ $location->statut === 'refusee' ? 'danger' : 'success' }}">
                                    <i class="fas fa-{{ $location->statut === 'refusee' ? 'times' : 'check' }}"></i>
                                </div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h6 class="timeline-title">
                                            @if($location->statut === 'refusee')
                                                Réservation refusée
                                            @else
                                                Réservation confirmée
                                            @endif
                                        </h6>
                                        <p><small class="text-muted"><i class="fas fa-clock"></i> {{ $location->updated_at->format('d/m/Y H:i') }}</small></p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($location->date_retour_effective)
                            <div class="timeline-item">
                                <div class="timeline-badge bg-success">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h6 class="timeline-title">Livre retourné</h6>
                                        <p><small class="text-muted"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($location->date_retour_effective)->format('d/m/Y') }}</small></p>
                                    </div>
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

.timeline-item {
    position: relative;
}

.timeline-badge {
    position: absolute;
    left: -30px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.timeline-panel {
    padding-left: 20px;
}

.badge-lg {
    font-size: 1rem;
    padding: 0.5rem 1rem;
}
</style>
@endsection
