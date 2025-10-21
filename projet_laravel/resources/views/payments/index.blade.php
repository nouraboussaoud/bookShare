@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading avec design moderne -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                <i class="fas fa-wallet text-success"></i>
                Mes Paiements
            </h1>
            <p class="text-muted small mb-0 mt-1">Gérez vos paiements de locations de livres</p>
        </div>
        @if($paymentsEnAttente->isNotEmpty())
            <span class="badge badge-warning badge-pill pulse-badge" style="font-size: 1.2rem; padding: 10px 20px;">
                {{ $paymentsEnAttente->count() }} en attente
            </span>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle"></i> {{ session('info') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- Paiements en attente -->
    @if($paymentsEnAttente->isNotEmpty())
        <div class="card shadow-lg border-0 mb-4 pending-payments-card">
            <div class="card-header bg-gradient-warning text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-clock"></i> Paiements en Attente
                    </h6>
                    <span class="badge badge-light badge-pill">{{ $paymentsEnAttente->count() }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 modern-table">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0"><i class="fas fa-book text-primary"></i> Livre</th>
                                <th class="border-0"><i class="fas fa-user text-info"></i> Propriétaire</th>
                                <th class="border-0"><i class="fas fa-euro-sign text-success"></i> Montant</th>
                                <th class="border-0"><i class="fas fa-calendar text-secondary"></i> Date</th>
                                <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paymentsEnAttente as $payment)
                                <tr class="payment-row">
                                    <td>
                                        <div class="book-info">
                                            <strong class="text-primary">{{ $payment->location->book->title }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-feather-alt"></i> {{ $payment->location->book->author }}
                                            </small>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span class="font-weight-medium">{{ $payment->location->proprietaire->name }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge badge-success badge-pill amount-badge">
                                            {{ number_format($payment->montant, 2) }}€
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <small>{{ $payment->created_at->format('d/m/Y') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $payment->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('payments.show', $payment) }}" class="btn btn-primary btn-sm shadow-sm">
                                                <i class="fas fa-credit-card"></i> Payer
                                            </a>
                                            <form action="{{ route('payments.cancel', $payment) }}" method="POST" class="d-inline" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir annuler ce paiement et la location ?');">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info border-left-info shadow-sm">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x text-info mr-3"></i>
                <div>
                    <strong>Aucun paiement en attente</strong>
                    <p class="mb-0 mt-1 small">Tous vos paiements sont à jour !</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Historique des paiements -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history"></i> Historique des Paiements
            </h6>
        </div>
        <div class="card-body">
            @if($paymentsHistorique->isEmpty())
                <p class="text-muted text-center">Aucun paiement dans l'historique.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Livre</th>
                                <th>Propriétaire</th>
                                <th>Montant</th>
                                <th>Méthode</th>
                                <th>Date paiement</th>
                                <th>Statut</th>
                                <th>Référence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paymentsHistorique as $payment)
                                <tr>
                                    <td>
                                        <strong>{{ $payment->location->book->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $payment->location->book->author }}</small>
                                    </td>
                                    <td>{{ $payment->location->proprietaire->name }}</td>
                                    <td>{{ number_format($payment->montant, 2) }}€</td>
                                    <td>
                                        @if($payment->methode_paiement === 'stripe')
                                            <i class="fab fa-stripe"></i> Stripe
                                        @elseif($payment->methode_paiement === 'paypal')
                                            <i class="fab fa-paypal"></i> PayPal
                                        @else
                                            <i class="fas fa-money-bill"></i> {{ ucfirst($payment->methode_paiement ?? 'N/A') }}
                                        @endif
                                    </td>
                                    <td>{{ $payment->date_paiement ? $payment->date_paiement->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <span class="badge {{ $payment->getStatutBadgeClass() }}">
                                            {{ $payment->getStatutLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $payment->reference_transaction ?? '-' }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $paymentsHistorique->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Gradients professionnels */
.bg-gradient-warning {
    background: linear-gradient(135deg, #f6c23e 0%, #e8a825 100%);
}

/* Card styling */
.card {
    border-radius: 15px;
    transition: all 0.3s ease;
}

.pending-payments-card {
    border: 2px solid #f6c23e30;
}

.pending-payments-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(246, 194, 62, 0.2);
}

/* Table styling */
.modern-table {
    font-size: 14px;
}

.modern-table thead th {
    font-weight: 700;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    padding: 15px;
}

.modern-table tbody td {
    padding: 20px 15px;
    vertical-align: middle;
}

.payment-row {
    transition: all 0.3s ease;
}

.payment-row:hover {
    background-color: #f8f9fc;
    transform: scale(1.01);
}

/* Book info */
.book-info strong {
    font-size: 15px;
}

/* Amount badge */
.amount-badge {
    font-size: 14px;
    padding: 8px 15px;
    font-weight: 600;
}

/* Buttons */
.btn-sm {
    border-radius: 8px;
    padding: 6px 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #224abe 0%, #1e3a8a 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
}

.btn-outline-secondary {
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    transform: scale(1.1);
    box-shadow: 0 3px 10px rgba(133, 135, 150, 0.3);
}

/* Pulse animation for badge */
@keyframes pulse-badge {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.9;
    }
}

.pulse-badge {
    animation: pulse-badge 2s ease-in-out infinite;
    box-shadow: 0 4px 15px rgba(246, 194, 62, 0.4);
}

/* Border left styling */
.border-left-info {
    border-left: 4px solid #36b9cc;
}

/* Shadow improvements */
.shadow-lg {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
}

.shadow-sm {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08) !important;
}

/* Alert improvements */
.alert {
    border-radius: 12px;
    border: none;
}

/* Badge improvements */
.badge-pill {
    padding: 8px 15px;
    font-size: 13px;
}

/* Font weights */
.font-weight-medium {
    font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
    .modern-table {
        font-size: 12px;
    }
    
    .modern-table thead th,
    .modern-table tbody td {
        padding: 10px 8px;
    }
    
    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }
    
    .amount-badge {
        font-size: 12px;
        padding: 5px 10px;
    }
}

/* Hover effects for cards */
.card:hover {
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
}
</style>
@endsection
