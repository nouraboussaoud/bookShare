@extends('layouts.admin-layout')

@section('title', 'BookShare - Gestion des signalements')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-flag text-primary mr-2"></i>
                Gestion des signalements
            </h1>
            <p class="mb-0 text-gray-600">Examinez et traitez les signalements des utilisateurs</p>
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-flag fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Traités</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['processed'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejetés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['rejected'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Liste des signalements</h6>
                <div class="d-flex">
                    <!-- Filters -->
                    <form method="GET" class="form-inline mr-3">
                        <div class="form-group mr-2">
                            <select name="status" class="form-control form-control-sm">
                                <option value="">Tous les statuts</option>
                                @foreach(\App\Models\Report::getStatuses() as $value => $label)
                                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select name="type" class="form-control form-control-sm">
                                <option value="">Tous les types</option>
                                @foreach(\App\Models\Report::getTypes() as $value => $label)
                                    <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm mr-2">
                            <i class="fas fa-filter"></i>
                        </button>
                        @if(request()->hasAny(['status', 'type']))
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-undo"></i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($reports->count() > 0)
                <!-- Bulk Actions -->
                <form id="bulk-action-form" method="POST" action="{{ route('admin.reports.bulkUpdateStatus') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-inline">
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="select-all">
                                    <label class="custom-control-label" for="select-all">Tout sélectionner</label>
                                </div>
                                <select name="status" class="form-control form-control-sm mr-2" id="bulk-status">
                                    <option value="">Action en lot...</option>
                                    <option value="TRAITE">Marquer comme traité</option>
                                    <option value="REJETE">Marquer comme rejeté</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm" id="bulk-submit" disabled>
                                    Appliquer
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="50px">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="select-all-header">
                                            <label class="custom-control-label" for="select-all-header"></label>
                                        </div>
                                    </th>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Signalé par</th>
                                    <th>Utilisateur signalé</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $report)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="reports[]" value="{{ $report->id }}" 
                                                       class="custom-control-input report-checkbox" id="report-{{ $report->id }}">
                                                <label class="custom-control-label" for="report-{{ $report->id }}"></label>
                                            </div>
                                        </td>
                                        <td><strong>#{{ $report->id }}</strong></td>
                                        <td>
                                            @if($report->type === 'CONFLIT_ECHANGE')
                                                <span class="badge badge-purple">Conflit d'échange</span>
                                            @else
                                                <span class="badge badge-warning">Comportement</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $report->reporter->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $report->reporter->email ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            @if($report->reportedUser)
                                                <div>{{ $report->reportedUser->name }}</div>
                                                <small class="text-muted">{{ $report->reportedUser->email }}</small>
                                            @elseif($report->exchange)
                                                <div class="text-info">
                                                    <i class="fas fa-exchange-alt"></i> Échange #{{ $report->exchange->id }}
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($report->status === 'EN_ATTENTE')
                                                <span class="badge badge-warning">En attente</span>
                                            @elseif($report->status === 'TRAITE')
                                                <span class="badge badge-success">Traité</span>
                                            @else
                                                <span class="badge badge-danger">Rejeté</span>
                                            @endif
                                        </td>
                                        <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.reports.show', $report) }}" 
                                                   class="btn btn-info btn-sm" title="Voir les détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($report->isPending())
                                                    <div class="btn-group" role="group">
                                                        <button id="statusDropdown{{ $report->id }}" type="button" 
                                                                class="btn btn-primary btn-sm dropdown-toggle" 
                                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-cog"></i>
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="statusDropdown{{ $report->id }}">
                                                            <form method="POST" action="{{ route('admin.reports.updateStatus', $report) }}" 
                                                                  style="display: inline;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="TRAITE">
                                                                <button type="submit" class="dropdown-item text-success"
                                                                        onclick="return confirm('Marquer comme traité ?')">
                                                                    <i class="fas fa-check mr-1"></i> Traiter
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="{{ route('admin.reports.updateStatus', $report) }}" 
                                                                  style="display: inline;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="REJETE">
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                        onclick="return confirm('Rejeter ce signalement ?')">
                                                                    <i class="fas fa-times mr-1"></i> Rejeter
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                <form method="POST" action="{{ route('admin.reports.destroy', $report) }}" 
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Supprimer définitivement ce signalement ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $reports->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-flag fa-4x text-gray-300 mb-3"></i>
                    <h4 class="text-gray-600 mb-2">Aucun signalement trouvé</h4>
                    <p class="text-gray-500">
                        @if(request()->hasAny(['status', 'type']))
                            Aucun signalement ne correspond à vos critères de recherche.
                        @else
                            Aucun signalement n'a été créé pour le moment.
                        @endif
                    </p>
                </div>
            @endif
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
    // Select all functionality
    $('#select-all, #select-all-header').change(function() {
        $('.report-checkbox').prop('checked', $(this).prop('checked'));
        toggleBulkActions();
    });

    $('.report-checkbox').change(function() {
        var totalCheckboxes = $('.report-checkbox').length;
        var checkedCheckboxes = $('.report-checkbox:checked').length;
        
        $('#select-all, #select-all-header').prop('checked', totalCheckboxes === checkedCheckboxes);
        toggleBulkActions();
    });

    function toggleBulkActions() {
        var checkedCount = $('.report-checkbox:checked').length;
        $('#bulk-submit').prop('disabled', checkedCount === 0);
    }

    // Bulk form submission
    $('#bulk-action-form').on('submit', function(e) {
        var status = $('#bulk-status').val();
        var checkedCount = $('.report-checkbox:checked').length;
        
        if (!status) {
            e.preventDefault();
            alert('Veuillez sélectionner une action');
            return;
        }
        
        if (checkedCount === 0) {
            e.preventDefault();
            alert('Veuillez sélectionner au moins un signalement');
            return;
        }
        
        var actionText = status === 'TRAITE' ? 'traités' : 'rejetés';
        if (!confirm(`Êtes-vous sûr de vouloir marquer ${checkedCount} signalement(s) comme ${actionText} ?`)) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
