@extends('layouts.admin-layout')

@section('title', 'BookShare - Administration des Échanges')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-cogs text-primary mr-2"></i>
                Administration des Échanges
            </h1>
            <p class="mb-0 text-gray-600">Gérez et supervisez tous les échanges de la plateforme BookShare</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Retour Dashboard
            </a>
            <a href="{{ route('admin.exchanges.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Nouvel Échange
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Échanges</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $exchanges->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
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
                                En Attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $exchanges->where('status', 'EN_ATTENTE')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                En Cours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $exchanges->where('status', 'EN_COURS')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
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
                                Terminés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $exchanges->where('status', 'TERMINE')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter fa-sm text-primary mr-2"></i>Filtres
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row">
                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">Statut</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Tous les statuts</option>
                        <option value="EN_ATTENTE" {{ request('status') == 'EN_ATTENTE' ? 'selected' : '' }}>En attente</option>
                        <option value="EN_COURS" {{ request('status') == 'EN_COURS' ? 'selected' : '' }}>En cours</option>
                        <option value="TERMINE" {{ request('status') == 'TERMINE' ? 'selected' : '' }}>Terminé</option>
                        <option value="ANNULE" {{ request('status') == 'ANNULE' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-control">
                        <option value="">Tous les types</option>
                        <option value="RESERVATION" {{ request('type') == 'RESERVATION' ? 'selected' : '' }}>Réservation</option>
                        <option value="PRET" {{ request('type') == 'PRET' ? 'selected' : '' }}>Prêt</option>
                        <option value="ECHANGE" {{ request('type') == 'ECHANGE' ? 'selected' : '' }}>Échange</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Rechercher</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           placeholder="Nom utilisateur ou livre..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.exchanges.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Exchanges Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list fa-sm text-primary mr-2"></i>Liste des Échanges
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Initiateur</th>
                            <th>Récepteur</th>
                            <th>Livre Demandé</th>
                            <th>Statut</th>
                            <th>Dates</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exchanges as $exchange)
                            <tr>
                                <td>{{ $exchange->id }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $exchange->type }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-primary">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-gray-800">{{ $exchange->initiateur?->name ?? 'N/A' }}</div>
                                            <div class="small text-gray-600">{{ $exchange->initiateur?->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-secondary">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-gray-800">{{ $exchange->recepteur?->name ?? 'N/A' }}</div>
                                            <div class="small text-gray-600">{{ $exchange->recepteur?->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="font-weight-bold text-gray-800">{{ $exchange->bookDemande?->title ?? 'N/A' }}</div>
                                        <div class="small text-gray-600">Propriétaire: {{ $exchange->bookDemande?->user?->name ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td>
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
                                    <span class="badge badge-{{ $statusClass }}">{{ $exchange->status }}</span>
                                </td>
                                <td>
                                    <div class="small">
                                        <div><strong>Début:</strong> {{ \Carbon\Carbon::parse($exchange->dateDebut)->format('d/m/Y') }}</div>
                                        <div><strong>Fin:</strong> {{ \Carbon\Carbon::parse($exchange->dateFin)->format('d/m/Y') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.exchanges.show', $exchange->id) }}" 
                                           class="btn btn-info btn-sm" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.exchanges.edit', $exchange->id) }}" 
                                           class="btn btn-primary btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($exchange->status === 'EN_ATTENTE')
                                            <form method="POST" action="{{ route('admin.exchanges.supervise', $exchange->id) }}" 
                                                  style="display: inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm" title="Approuver">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if(in_array($exchange->status, ['EN_ATTENTE', 'EN_COURS']))
                                            <form method="POST" action="{{ route('admin.exchanges.cancel', $exchange->id) }}" 
                                                  style="display: inline-block;" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cet échange ?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Annuler">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-gray-500">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>Aucun échange trouvé.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Affichant {{ $exchanges->firstItem() ?? 0 }} à {{ $exchanges->lastItem() ?? 0 }} 
                    sur {{ $exchanges->total() }} résultats
                </div>
                {{ $exchanges->links() }}
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.icon-circle {
    height: 2rem;
    width: 2rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table th, .table td {
    vertical-align: middle;
}

.btn-group .btn {
    margin-right: 0;
}
</style>
@endpush
