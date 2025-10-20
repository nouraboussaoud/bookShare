@extends('layouts.admin-layout')

@section('title', 'Dashboard Signalements')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="mb-0">
                            <i class="fas fa-chart-line text-primary"></i> Dashboard Analytique des Signalements
                        </h2>
                        <div>
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left"></i> Retour à la liste
                            </a>
                            <a href="{{ route('admin.reports.dashboard.export', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" 
                               class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Exporter CSV
                            </a>
                        </div>
                    </div>

                    <!-- Filtres -->
                    <form method="GET" action="{{ route('admin.reports.dashboard') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Date début</label>
                            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date fin</label>
                            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Statut</label>
                            <select name="status" class="form-select">
                                <option value="">Tous</option>
                                <option value="EN_ATTENTE" {{ $status == 'EN_ATTENTE' ? 'selected' : '' }}>En attente</option>
                                <option value="TRAITE" {{ $status == 'TRAITE' ? 'selected' : '' }}>Traité</option>
                                <option value="REJETE" {{ $status == 'REJETE' ? 'selected' : '' }}>Rejeté</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Priorité</label>
                            <select name="priority" class="form-select">
                                <option value="">Toutes</option>
                                <option value="critique" {{ $priority == 'critique' ? 'selected' : '' }}>Critique</option>
                                <option value="haute" {{ $priority == 'haute' ? 'selected' : '' }}>Haute</option>
                                <option value="moyenne" {{ $priority == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                                <option value="normale" {{ $priority == 'normale' ? 'selected' : '' }}>Normale</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes statistiques -->
    <div class="row g-3 mb-4">
        <!-- Total -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Signalements
                            </div>
                            <div class="h5 mb-0 font-weight-bold">{{ $totalReports }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-flag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- En attente -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                En Attente
                            </div>
                            <div class="h5 mb-0 font-weight-bold">{{ $pendingReports }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Urgents -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-danger shadow h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                🔴 Urgents
                            </div>
                            <div class="h5 mb-0 font-weight-bold">{{ $urgentReports }}</div>
                            <div class="text-xs text-muted">+ {{ $highPriorityReports }} haute priorité</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Taux de résolution -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Taux de Résolution
                            </div>
                            <div class="h5 mb-0 font-weight-bold">{{ $resolutionRate }}%</div>
                            <div class="text-xs text-muted">Temps moyen: {{ $avgResolutionTime }}h</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Signalements urgents récents -->
    @if($urgentRecentReports->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-bell"></i> Signalements Urgents Nécessitant une Attention</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Priorité</th>
                                    <th>Type</th>
                                    <th>Reporter</th>
                                    <th>Signalé</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($urgentRecentReports as $report)
                                <tr>
                                    <td><strong>#{{ $report->id }}</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $report->priority_color }}">
                                            {{ $report->priority_icon }} {{ ucfirst($report->priority_level) }}
                                        </span>
                                        <small class="text-muted d-block">Score: {{ $report->priority_score }}/10</small>
                                    </td>
                                    <td>{{ str_replace('_', ' ', $report->type) }}</td>
                                    <td>{{ $report->reporter->name ?? 'N/A' }}</td>
                                    <td>
                                        {{ $report->reportedUser->name ?? 'N/A' }}
                                        @if($report->is_recurring_offender)
                                            <span class="badge bg-warning ms-1" title="Récidiviste">⚠️</span>
                                        @endif
                                    </td>
                                    <td>{{ $report->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Graphiques -->
    <div class="row g-3 mb-4">
        <!-- Évolution temporelle -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Évolution des Signalements (7 derniers jours)</h5>
                </div>
                <div class="card-body">
                    <canvas id="timelineChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribution par priorité -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Par Priorité</h5>
                </div>
                <div class="card-body">
                    <canvas id="priorityChart" height="200"></canvas>
                    <div class="mt-3">
                        @foreach($priorityDistribution as $level => $count)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ ucfirst($level) }}:</span>
                            <strong>{{ $count }}</strong>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Distribution par type -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Par Type</h5>
                </div>
                <div class="card-body">
                    <canvas id="typeChart" height="200"></canvas>
                    <div class="mt-3 text-center">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-primary fw-bold">{{ $conflitReports }}</div>
                                <div class="text-xs text-muted">Conflits d'échange</div>
                            </div>
                            <div class="col-6">
                                <div class="text-warning fw-bold">{{ $comportementReports }}</div>
                                <div class="text-xs text-muted">Comportements</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribution par statut -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Par Statut</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>En attente:</span>
                            <strong class="text-warning">{{ $pendingReports }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Traités:</span>
                            <strong class="text-success">{{ $processedReports }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Rejetés:</span>
                            <strong class="text-danger">{{ $rejectedReports }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques diverses -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Récidivistes identifiés</span>
                            <span class="badge bg-warning">{{ $recurringOffenders }}</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-warning" style="width: {{ $totalReports > 0 ? ($recurringOffenders / $totalReports * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Taux de traitement</span>
                            <span class="badge bg-success">{{ $resolutionRate }}%</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: {{ $resolutionRate }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Signalements critiques</span>
                            <span class="badge bg-danger">{{ $priorityDistribution['critique'] }}</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-danger" style="width: {{ $totalReports > 0 ? ($priorityDistribution['critique'] / $totalReports * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center">
                        <div class="h2 text-primary mb-0">{{ $avgResolutionTime }}h</div>
                        <small class="text-muted">Temps moyen de résolution</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top utilisateurs -->
    <div class="row g-3 mb-4">
        <!-- Top utilisateurs signalés -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-user-times"></i> Top Utilisateurs Signalés</h5>
                </div>
                <div class="card-body">
                    @if($topReportedUsers->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($topReportedUsers as $item)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $item->reportedUser->name ?? 'Utilisateur supprimé' }}</strong>
                                <br>
                                <small class="text-muted">{{ $item->reportedUser->email ?? 'N/A' }}</small>
                            </div>
                            <span class="badge bg-danger rounded-pill">{{ $item->count }} signalements</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center py-3">Aucune donnée disponible</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top reporters -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-user-check"></i> Top Reporters</h5>
                </div>
                <div class="card-body">
                    @if($topReporters->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($topReporters as $item)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $item->reporter->name ?? 'Utilisateur supprimé' }}</strong>
                                <br>
                                <small class="text-muted">{{ $item->reporter->email ?? 'N/A' }}</small>
                            </div>
                            <span class="badge bg-info rounded-pill">{{ $item->count }} signalements</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center py-3">Aucune donnée disponible</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Performance des modérateurs -->
    @if($moderatorPerformance->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-user-shield"></i> Performance des Modérateurs</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Modérateur</th>
                                    <th>Total traités</th>
                                    <th>Approuvés</th>
                                    <th>Rejetés</th>
                                    <th>Temps moyen (h)</th>
                                    <th>Taux de résolution</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($moderatorPerformance as $perf)
                                <tr>
                                    <td><strong>{{ $perf->moderator->name ?? 'N/A' }}</strong></td>
                                    <td>{{ $perf->total_handled }}</td>
                                    <td><span class="badge bg-success">{{ $perf->processed }}</span></td>
                                    <td><span class="badge bg-danger">{{ $perf->rejected }}</span></td>
                                    <td>{{ round($perf->avg_resolution_hours, 1) }}h</td>
                                    <td>
                                        @php
                                            $rate = $perf->total_handled > 0 ? round(($perf->processed / $perf->total_handled) * 100, 1) : 0;
                                        @endphp
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" style="width: {{ $rate }}%">
                                                {{ $rate }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
.border-left-success {
    border-left: 4px solid #1cc88a !important;
}
.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}
.border-left-danger {
    border-left: 4px solid #e74a3b !important;
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Timeline Chart
    const timelineCtx = document.getElementById('timelineChart').getContext('2d');
    new Chart(timelineCtx, {
        type: 'line',
        data: {
            labels: @json(array_column($timeline, 'date')),
            datasets: [{
                label: 'Total',
                data: @json(array_column($timeline, 'count')),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.3,
                fill: true
            }, {
                label: 'En attente',
                data: @json(array_column($timeline, 'pending')),
                borderColor: '#f6c23e',
                backgroundColor: 'rgba(246, 194, 62, 0.1)',
                tension: 0.3,
                fill: true
            }, {
                label: 'Traités',
                data: @json(array_column($timeline, 'processed')),
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Priority Chart
    const priorityCtx = document.getElementById('priorityChart').getContext('2d');
    new Chart(priorityCtx, {
        type: 'doughnut',
        data: {
            labels: ['Normale', 'Moyenne', 'Haute', 'Critique'],
            datasets: [{
                data: [
                    {{ $priorityDistribution['normale'] }},
                    {{ $priorityDistribution['moyenne'] }},
                    {{ $priorityDistribution['haute'] }},
                    {{ $priorityDistribution['critique'] }}
                ],
                backgroundColor: [
                    '#858796',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Type Chart
    const typeCtx = document.getElementById('typeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Conflits d\'échange', 'Comportement inapproprié'],
            datasets: [{
                data: [{{ $conflitReports }}, {{ $comportementReports }}],
                backgroundColor: ['#4e73df', '#f6c23e']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['En attente', 'Traités', 'Rejetés'],
            datasets: [{
                data: [{{ $pendingReports }}, {{ $processedReports }}, {{ $rejectedReports }}],
                backgroundColor: ['#f6c23e', '#1cc88a', '#e74a3b']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
@endpush
@endsection
