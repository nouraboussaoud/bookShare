@extends('layouts.admin-layout')

@section('title', 'Gestion des Paiements')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-money-bill-wave text-success"></i> Gestion des Paiements
        </h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Paiements</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
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

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Complétés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['complete'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Montant Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_montant'], 2) }}€</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Liste des Paiements
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="paymentsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Locataire</th>
                            <th>Propriétaire</th>
                            <th>Livre</th>
                            <th>Montant</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Méthode</th>
                            <th>Date paiement</th>
                            <th>Référence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>#{{ $payment->id }}</td>
                                <td>
                                    <i class="fas fa-user"></i> {{ $payment->location->locataire->name }}<br>
                                    <small class="text-muted">{{ $payment->location->locataire->email }}</small>
                                </td>
                                <td>
                                    <i class="fas fa-user-tie"></i> {{ $payment->location->proprietaire->name }}<br>
                                    <small class="text-muted">{{ $payment->location->proprietaire->email }}</small>
                                </td>
                                <td>
                                    <strong>{{ $payment->location->book->title }}</strong><br>
                                    <small class="text-muted">{{ $payment->location->book->author }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-primary" style="font-size: 1em;">{{ number_format($payment->montant, 2) }}€</span>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $payment->getTypeLabel() }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $payment->getStatutBadgeClass() }}">
                                        {{ $payment->getStatutLabel() }}
                                    </span>
                                </td>
                                <td>
                                    @if($payment->methode_paiement)
                                        <i class="fas fa-credit-card"></i> {{ ucfirst($payment->methode_paiement) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->date_paiement)
                                        {{ $payment->date_paiement->format('d/m/Y H:i') }}<br>
                                        <small class="text-muted">{{ $payment->date_paiement->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">En attente</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->reference_transaction)
                                        <code style="font-size: 0.85em;">{{ $payment->reference_transaction }}</code>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-2"></i><br>
                                    Aucun paiement enregistré
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($payments->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#paymentsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
        },
        "order": [[0, "desc"]],
        "pageLength": 25,
        "paging": false,
        "searching": true,
        "info": true
    });
});
</script>
@endpush

<style>
.badge {
    padding: 0.5em 0.8em;
    font-size: 0.85em;
}

.table td {
    vertical-align: middle;
}

.table th {
    background-color: #f8f9fc;
    font-weight: 600;
}
</style>
@endsection
