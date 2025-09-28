@extends('layouts.layout')

@section('title', 'BookShare - Mes signalements')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-flag text-primary mr-2"></i>
                Mes signalements
            </h1>
            <p class="mb-0 text-gray-600">Consultez l'état de vos signalements</p>
        </div>
        <a href="{{ route('reports.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouveau signalement
        </a>
    </div>

    <!-- Main Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste de mes signalements</h6>
        </div>
        <div class="card-body">
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

            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-12">
                    <form method="GET" class="form-inline">
                        <div class="form-group mr-3">
                            <label for="status" class="mr-2">Statut:</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Tous les statuts</option>
                                @foreach(\App\Models\Report::getStatuses() as $value => $label)
                                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mr-3">
                            <label for="type" class="mr-2">Type:</label>
                            <select name="type" id="type" class="form-control">
                                <option value="">Tous les types</option>
                                @foreach(\App\Models\Report::getTypes() as $value => $label)
                                    <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>

                        @if(request()->hasAny(['status', 'type']))
                            <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Réinitialiser
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            @if($reports->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Cible</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                            <tr>
                                <td><strong>#{{ $report->id }}</strong></td>
                                <td>
                                    @if($report->type === 'CONFLIT_ECHANGE')
                                        <span class="badge badge-purple">Conflit d'échange</span>
                                    @else
                                        <span class="badge badge-warning">Comportement</span>
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
                                <td>
                                    @if($report->reportedUser)
                                        <i class="fas fa-user text-primary"></i> {{ $report->reportedUser->name }}
                                    @elseif($report->exchange)
                                        <i class="fas fa-exchange-alt text-info"></i> Échange #{{ $report->exchange->id }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($report->description, 50) }}</td>
                                <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('reports.show', $report) }}" class="btn btn-info btn-sm" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
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
                            Vous n'avez créé aucun signalement pour le moment.
                        @endif
                    </p>
                    @if(!request()->hasAny(['status', 'type']))
                        <a href="{{ route('reports.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus mr-1"></i> Créer mon premier signalement
                        </a>
                    @endif
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