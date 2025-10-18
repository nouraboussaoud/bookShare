@extends('layouts.app')

@section('title', 'BookShare - Détails du signalement #' . $report->id)

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-flag text-primary mr-2"></i>
                Signalement #{{ $report->id }}
            </h1>
            <p class="mb-0 text-gray-600">{{ \App\Models\Report::getTypes()[$report->type] }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Retour à mes signalements
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Report Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle fa-sm text-primary mr-1"></i> Détails du signalement
                        </h6>
                        @if($report->status === 'EN_ATTENTE')
                            <span class="badge badge-warning">En attente</span>
                        @elseif($report->status === 'TRAITE')
                            <span class="badge badge-success">Traité</span>
                        @else
                            <span class="badge badge-danger">Rejeté</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Type de signalement:</h6>
                            @if($report->type === 'CONFLIT_ECHANGE')
                                <span class="badge badge-purple mb-3">Conflit d'échange</span>
                            @else
                                <span class="badge badge-warning mb-3">Comportement inapproprié</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Date de création:</h6>
                            <p class="text-gray-600">{{ $report->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <h6 class="font-weight-bold text-gray-800">Description:</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0">{{ $report->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status History Card -->
            @if($report->status !== 'EN_ATTENTE')
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history fa-sm text-primary mr-1"></i> Historique
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Signalement créé</h6>
                                <p class="timeline-text text-muted">{{ $report->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        @if($report->status === 'TRAITE')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Signalement traité</h6>
                                <p class="timeline-text text-muted">{{ $report->updated_at->format('d/m/Y à H:i') }}</p>
                                <p class="timeline-text">Votre signalement a été examiné et traité par l'équipe d'administration.</p>
                            </div>
                        </div>
                        @elseif($report->status === 'REJETE')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Signalement rejeté</h6>
                                <p class="timeline-text text-muted">{{ $report->updated_at->format('d/m/Y à H:i') }}</p>
                                <p class="timeline-text">Votre signalement a été examiné mais n'a pas été retenu.</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <!-- Related Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-link fa-sm text-primary mr-1"></i> Informations liées
                    </h6>
                </div>
                <div class="card-body">
                    @if($report->reportedUser)
                        <div class="mb-3">
                            <h6 class="font-weight-bold text-gray-800">Utilisateur signalé:</h6>
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="icon-circle bg-warning">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-weight-bold">{{ $report->reportedUser->name }}</div>
                                    <div class="text-gray-600 small">{{ $report->reportedUser->email }}</div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    @endif

                    @if($report->exchange)
                        <div class="mb-3">
                            <h6 class="font-weight-bold text-gray-800">Échange concerné:</h6>
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>Échange #{{ $report->exchange->id }}</strong>
                                    <span class="badge badge-info">{{ $report->exchange->status }}</span>
                                </div>
                                <div class="small text-gray-600">
                                    <div><strong>Initiateur:</strong> {{ $report->exchange->initiateur->name ?? 'N/A' }}</div>
                                    <div><strong>Récepteur:</strong> {{ $report->exchange->recepteur->name ?? 'N/A' }}</div>
                                    <div><strong>Date:</strong> {{ $report->exchange->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    @endif

                    <div>
                        <h6 class="font-weight-bold text-gray-800">Signalé par:</h6>
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="font-weight-bold">Vous</div>
                                <div class="text-gray-600 small">{{ $report->reporter->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-question-circle fa-sm text-primary mr-1"></i> Besoin d'aide ?
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-gray-600 small mb-3">
                        Si vous avez des questions concernant votre signalement ou si vous souhaitez fournir des informations supplémentaires, n'hésitez pas à nous contacter.
                    </p>
                    <div class="d-grid gap-2">
                        <a href="mailto:support@bookshare.com" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope mr-1"></i> Contacter le support
                        </a>
                        <a href="{{ route('reports.create') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Nouveau signalement
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.badge-purple {
    color: #fff;
    background-color: #6f42c1;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -22px;
    top: 8px;
    bottom: -12px;
    width: 2px;
    background-color: #e3e6f0;
}

.timeline-item:last-child:before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: -28px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 3px #e3e6f0;
}

.timeline-content {
    padding-left: 15px;
}

.timeline-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 13px;
    margin-bottom: 3px;
}
</style>
@endpush