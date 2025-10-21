@extends('layouts.layout')

@section('title', 'Mes Paiements')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-credit-card text-primary"></i> Mes Paiements
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Paiements en attente -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-clock"></i> Paiements en Attente
                @if($paiementsEnAttente->count() > 0)
                    <span class="badge bg-warning text-dark ms-2">{{ $paiementsEnAttente->count() }}</span>
                @endif
            </h6>
        </div>
        <div class="card-body">
            @if($paiementsEnAttente->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                    <p class="text-muted">Aucun paiement en attente</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Livre</th>
                                <th>Propriétaire</th>
                                <th>Montant</th>
                                <th>Type</th>
                                <th>Date création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paiementsEnAttente as $payment)
                                <tr>
                                    <td>
                                        <strong>{{ $payment->location->book->title }}</strong><br>
                                        <small class="text-muted">par {{ $payment->location->book->author }}</small>
                                    </td>
                                    <td>
                                        <i class="fas fa-user-circle"></i> {{ $payment->location->proprietaire->name }}
                                    </td>
                                    <td>
                                        <span class="badge bg-primary" style="font-size: 1.1em;">{{ number_format($payment->montant, 2) }}€</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $payment->getTypeLabel() }}</span>
                                    </td>
                                    <td>
                                        {{ $payment->created_at->format('d/m/Y') }}<br>
                                        <small class="text-muted">{{ $payment->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-credit-card"></i> Payer
                                        </a>
                                        <form action="{{ route('payments.cancel', $payment) }}" method="POST" class="d-inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce paiement ?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-times"></i> Annuler
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Historique des paiements -->
    <div class="card shadow mb-4">
        <div class="card-header py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-history"></i> Historique des Paiements
            </h6>
        </div>
        <div class="card-body">
            @if($historiquePaiements->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-folder-open text-muted fa-3x mb-3"></i>
                    <p class="text-muted">Aucun historique de paiement</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Livre</th>
                                <th>Montant</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Méthode</th>
                                <th>Date</th>
                                <th>Référence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historiquePaiements as $payment)
                                <tr>
                                    <td>
                                        <strong>{{ $payment->location->book->title }}</strong><br>
                                        <small class="text-muted">{{ $payment->location->proprietaire->name }}</small>
                                    </td>
                                    <td>{{ number_format($payment->montant, 2) }}€</td>
                                    <td><span class="badge bg-info">{{ $payment->getTypeLabel() }}</span></td>
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
                                            {{ $payment->date_paiement->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->reference_transaction)
                                            <code>{{ $payment->reference_transaction }}</code>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $historiquePaiements->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.card-header {
    border-bottom: 3px solid rgba(255,255,255,0.1);
}

.table th {
    background-color: #f8f9fc;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    color: #5a5c69;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fc;
}

.badge {
    padding: 0.5em 0.8em;
    font-size: 0.85em;
}
</style>
@endsection
