@extends('layouts.layout')

@section('title', 'BookShare - Détails de l\'Échange')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-eye text-primary mr-2"></i>
                Détails de l'Échange
            </h1>
            <p class="mb-0 text-gray-600">Informations complètes sur l'échange de livre</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('exchanges.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i>Retour à la liste
            </a>
            @if(auth()->user()->id === $exchange->userInitiateurId && $exchange->status !== 'TERMINE' && $exchange->status !== 'ANNULE')
                <a href="{{ route('exchanges.edit', $exchange->id) }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-edit mr-1"></i>Modifier
                </a>
            @elseif(auth()->user()->id === $exchange->userRecepteurId && $exchange->status === 'EN_ATTENTE')
                <form method="POST" action="{{ route('exchanges.accept', $exchange->id) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success shadow-sm mr-2">
                        <i class="fas fa-check mr-1"></i>Accepter
                    </button>
                </form>
                <form method="POST" action="{{ route('exchanges.reject', $exchange->id) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger shadow-sm">
                        <i class="fas fa-times mr-1"></i>Refuser
                    </button>
                </form>
            @endif
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
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Users Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users fa-sm text-primary"></i> Participants
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
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
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Card -->
            @if($exchange->bookDemande)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book fa-sm text-primary"></i> Livre Demandé
                    </h6>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold text-gray-800">{{ $exchange->bookDemande->title }}</h6>
                    <p class="text-gray-600">Propriétaire: {{ $exchange->bookDemande->user?->name ?? 'N/A' }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection