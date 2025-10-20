@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading moderne -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                <i class="fas fa-money-bill-wave text-success"></i>
                Gestion des Paiements
            </h1>
            <p class="text-muted small mb-0 mt-1">Gérez tous vos paiements de réservation</p>
        </div>
        <a href="{{ route('reservation-payments.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus"></i> Nouveau Paiement
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Statistiques Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Payé
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($payments->where('statut_paiement', 'complete')->sum('montant'), 2) }} €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
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
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Paiements
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $payments->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table des paiements avec design moderne -->
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-table"></i> Liste des Paiements
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Réservation</th>
                            <th>Livre</th>
                            <th>Type</th>
                            <th class="text-right">Montant</th>
                            <th class="text-center">Statut</th>
                            <th>Méthode</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td class="text-center font-weight-bold">{{ $payment->id }}</td>
                            <td>
                                <a href="{{ route('locations.show', $payment->location_id) }}" class="text-primary font-weight-bold">
                                    <i class="fas fa-link"></i> #{{ $payment->location_id }}
                                </a>
                            </td>
                            <td>
                                @if($payment->location && $payment->location->book)
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-book text-info mr-2"></i>
                                        <span>{{ Str::limit($payment->location->book->title, 30) }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($payment->type_paiement === 'location')
                                    <span class="badge badge-primary badge-pill">💰 Location</span>
                                @elseif($payment->type_paiement === 'caution')
                                    <span class="badge badge-warning badge-pill">🔒 Caution</span>
                                @elseif($payment->type_paiement === 'penalite')
                                    <span class="badge badge-danger badge-pill">⚠️ Pénalité</span>
                                @else
                                    <span class="badge badge-success badge-pill">🔄 Remboursement</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <span class="font-weight-bold text-success">{{ number_format($payment->montant, 2) }} €</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $payment->getStatutBadgeClass() }} badge-pill px-3 py-2">
                                    {{ $payment->getStatutLabel() }}
                                </span>
                            </td>
                            <td>
                                @if($payment->methode_paiement)
                                    <span class="text-muted">
                                        <i class="fas fa-credit-card mr-1"></i>
                                        {{ $payment->methode_paiement }}
                                    </span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($payment->date_paiement)
                                    <i class="fas fa-calendar text-primary mr-1"></i>
                                    {{ $payment->date_paiement->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('reservation-payments.show', $payment) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Voir"
                                       data-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('reservation-payments.edit', $payment) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Modifier"
                                       data-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($payment->location->proprietaire_id === Auth::id())
                                    <form action="{{ route('reservation-payments.destroy', $payment) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?');"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Supprimer"
                                                data-toggle="tooltip">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Aucun paiement enregistré</h5>
                                <p class="text-muted">Créez votre premier paiement pour commencer</p>
                                <a href="{{ route('reservation-payments.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus"></i> Créer un paiement
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination moderne -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fc;
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
}

.btn-group .btn {
    margin: 0 2px;
}

.badge-pill {
    padding: 8px 15px;
    font-size: 0.85rem;
}

thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}
</style>

<script>
$(document).ready(function() {
    // Activer les tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endsection

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Paiements</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Réservation</th>
                            <th>Livre</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Méthode</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>
                                <a href="{{ route('locations.show', $payment->location_id) }}">
                                    #{{ $payment->location_id }}
                                </a>
                            </td>
                            <td>
                                @if($payment->location && $payment->location->book)
                                    {{ Str::limit($payment->location->book->title, 30) }}
                                @else
                                    N/A
                                @endif
                            </td>
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
                                    @if($payment->location->proprietaire_id === Auth::id())
                                    <form action="{{ route('reservation-payments.destroy', $payment) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?');"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Aucun paiement enregistré</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-3">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
            },
            "order": [[0, "desc"]]
        });
    });
</script>
@endpush
