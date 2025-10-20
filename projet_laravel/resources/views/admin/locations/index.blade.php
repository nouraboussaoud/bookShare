@extends('layouts.admin-layout')

@section('title', 'Gestion des Réservations')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-check text-primary"></i> Gestion des Réservations
        </h1>
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
        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['en_attente'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Confirmées</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['confirmee'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">En cours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['en_cours'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book-reader fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Terminées</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['terminee'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Refusées</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['refusee'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Liste des Réservations</h6>
                
                <!-- Filters -->
                <form method="GET" class="form-inline">
                    <div class="form-group mr-2">
                        <input type="text" name="search" class="form-control form-control-sm" 
                               placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                    <div class="form-group mr-2">
                        <select name="statut" class="form-control form-control-sm">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmee" {{ request('statut') == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                            <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="terminee" {{ request('statut') == 'terminee' ? 'selected' : '' }}>Terminée</option>
                            <option value="refusee" {{ request('statut') == 'refusee' ? 'selected' : '' }}>Refusée</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mr-2">
                        <i class="fas fa-filter"></i>
                    </button>
                    @if(request()->hasAny(['search', 'statut']))
                        <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-undo"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
        <div class="card-body">
            @if($locations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Livre</th>
                                <th>Propriétaire</th>
                                <th>Emprunteur</th>
                                <th>Dates</th>
                                <th>Statut</th>
                                <th>Créée le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($locations as $location)
                                <tr>
                                    <td><strong>#{{ $location->id }}</strong></td>
                                    <td>
                                        @if($location->book)
                                            <div><strong>{{ $location->book->titre }}</strong></div>
                                            <small class="text-muted">{{ $location->book->auteur }}</small>
                                        @else
                                            <span class="text-muted">Livre supprimé</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($location->proprietaire)
                                            <div>{{ $location->proprietaire->name }}</div>
                                            <small class="text-muted">{{ $location->proprietaire->email }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($location->locataire)
                                            <div>{{ $location->locataire->name }}</div>
                                            <small class="text-muted">{{ $location->locataire->email }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div><strong>Début:</strong> {{ \Carbon\Carbon::parse($location->date_location)->format('d/m/Y') }}</div>
                                        <div><strong>Fin:</strong> {{ \Carbon\Carbon::parse($location->date_fin_prevue)->format('d/m/Y') }}</div>
                                        @if($location->date_retour_effective)
                                            <div class="text-success"><strong>Retour:</strong> {{ \Carbon\Carbon::parse($location->date_retour_effective)->format('d/m/Y') }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($location->statut === 'en_attente')
                                            <span class="badge badge-warning">En attente</span>
                                        @elseif($location->statut === 'confirmee')
                                            <span class="badge badge-info">Confirmée</span>
                                        @elseif($location->statut === 'en_cours')
                                            <span class="badge badge-primary">En cours</span>
                                        @elseif($location->statut === 'terminee')
                                            <span class="badge badge-success">Terminée</span>
                                        @else
                                            <span class="badge badge-danger">Refusée</span>
                                        @endif
                                    </td>
                                    <td>{{ $location->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.locations.show', $location) }}" 
                                               class="btn btn-info btn-sm" title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($location->statut === 'en_attente')
                                                <form method="POST" action="{{ route('admin.locations.approve', $location) }}" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success btn-sm" title="Approuver"
                                                            onclick="return confirm('Approuver cette réservation ?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.locations.reject', $location) }}" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-warning btn-sm" title="Rejeter"
                                                            onclick="return confirm('Rejeter cette réservation ?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form method="POST" action="{{ route('admin.locations.destroy', $location) }}" 
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Supprimer définitivement cette réservation ?')">
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

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $locations->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-check fa-4x text-gray-300 mb-3"></i>
                    <h4 class="text-gray-600 mb-2">Aucune réservation trouvée</h4>
                    <p class="text-gray-500">
                        @if(request()->hasAny(['search', 'statut']))
                            Aucune réservation ne correspond à vos critères de recherche.
                        @else
                            Aucune réservation n'a été créée pour le moment.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
