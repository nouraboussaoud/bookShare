@extends('layouts.admin-layout')

@section('title', 'BookShare - Détails du signalement #' . $report->id)

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-flag text-primary mr-2"></i>
                Signalement #{{ $report->id }}
            </h1>
            <p class="mb-0 text-gray-600">{{ \App\Models\Report::getTypes()[$report->type] ?? 'Type inconnu' }} - Administration</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

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

                    @if($report->updated_at->gt($report->created_at))
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="font-weight-bold text-gray-800">Dernière mise à jour:</h6>
                                <p class="text-gray-600">{{ $report->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Admin Actions Card -->
            @if($report->isPending())
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-cogs fa-sm text-danger mr-1"></i> Actions administrateur
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.reports.updateStatus', $report) }}" id="status-form">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Nouveau statut</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="">Sélectionner un statut</option>
                                        <option value="TRAITE">Traité</option>
                                        <option value="REJETE">Rejeté</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="admin_note">Note administrative (optionnel)</label>
                                    <textarea name="admin_note" id="admin_note" class="form-control" rows="3" 
                                              placeholder="Ajouter une note interne..."></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Actions rapides :</small>
                            </div>
                            <div>
                                <button type="button" class="btn btn-success btn-sm mr-2" onclick="quickAction('TRAITE')">
                                    <i class="fas fa-check mr-1"></i> Traiter rapidement
                                </button>
                                <button type="button" class="btn btn-danger btn-sm mr-2" onclick="quickAction('REJETE')">
                                    <i class="fas fa-times mr-1"></i> Rejeter rapidement
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Mettre à jour avec note
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @endif
        </div>
        
        <div class="col-lg-4">
            <!-- Reporter Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user fa-sm text-primary mr-1"></i> Signalé par
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3">
                            <div class="icon-circle bg-primary">
                                <i class="fas fa-user text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="font-weight-bold">{{ $report->reporter->name ?? 'Utilisateur supprimé' }}</div>
                            <div class="text-gray-600 small">{{ $report->reporter->email ?? 'N/A' }}</div>
                            @if($report->reporter)
                                <div class="text-gray-500 small">
                                    Membre depuis {{ $report->reporter->created_at->format('M Y') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($report->reporter)
                        <div class="row text-center small">
                            <div class="col-6">
                                <div class="font-weight-bold text-primary">{{ $report->reporter->reportsCreated()->count() }}</div>
                                <div class="text-gray-600">Signalements créés</div>
                            </div>
                            <div class="col-6">
                                <div class="font-weight-bold text-warning">{{ $report->reporter->reportsReceived()->count() }}</div>
                                <div class="text-gray-600">Fois signalé</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reported User Information -->
            @if($report->reportedUser)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle fa-sm text-warning mr-1"></i> Utilisateur signalé
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3">
                            <div class="icon-circle bg-warning">
                                <i class="fas fa-user text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="font-weight-bold">{{ $report->reportedUser->name }}</div>
                            <div class="text-gray-600 small">{{ $report->reportedUser->email }}</div>
                            <div class="text-gray-500 small">
                                Membre depuis {{ $report->reportedUser->created_at->format('M Y') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="row text-center small">
                        <div class="col-6">
                            <div class="font-weight-bold text-success">{{ $report->reportedUser->reportsCreated()->count() }}</div>
                            <div class="text-gray-600">Signalements créés</div>
                        </div>
                        <div class="col-6">
                            <div class="font-weight-bold text-danger">{{ $report->reportedUser->reportsReceived()->count() }}</div>
                            <div class="text-gray-600">Fois signalé</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Exchange Information -->
            @if($report->exchange)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-exchange-alt fa-sm text-info mr-1"></i> Échange concerné
                    </h6>
                </div>
                <div class="card-body">
                    <div class="border rounded p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Échange #{{ $report->exchange->id }}</strong>
                            <span class="badge badge-info">{{ $report->exchange->status }}</span>
                        </div>
                        <div class="small text-gray-600">
                            <div><strong>Initiateur:</strong> {{ $report->exchange->initiateur->name ?? 'N/A' }}</div>
                            <div><strong>Récepteur:</strong> {{ $report->exchange->recepteur->name ?? 'N/A' }}</div>
                            <div><strong>Type:</strong> {{ $report->exchange->type ?? 'N/A' }}</div>
                            <div><strong>Date:</strong> {{ $report->exchange->created_at->format('d/m/Y') }}</div>
                        </div>
                        @if(Route::has('admin.exchanges.show'))
                        <div class="mt-2">
                            <a href="{{ route('admin.exchanges.show', $report->exchange) }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-eye mr-1"></i> Voir l'échange
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Delete Report -->
            <div class="card shadow border-danger">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-trash fa-sm mr-1"></i> Zone dangereuse
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-gray-600 small mb-3">
                        Supprimer définitivement ce signalement. Cette action est irréversible.
                    </p>
                    <form method="POST" action="{{ route('admin.reports.destroy', $report) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce signalement ? Cette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash mr-1"></i> Supprimer le signalement
                        </button>
                    </form>
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
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#status-form').on('submit', function(e) {
        const status = $('#status').val();
        if (!status) {
            e.preventDefault();
            alert('Veuillez sélectionner un statut');
        }
    });
});

// Quick action function
function quickAction(status) {
    const actionText = status === 'TRAITE' ? 'traiter' : 'rejeter';
    if (confirm(`Êtes-vous sûr de vouloir ${actionText} ce signalement rapidement ?`)) {
        // Set the status and submit
        $('#status').val(status);
        $('#status-form').submit();
    }
}
</script>
@endpush
