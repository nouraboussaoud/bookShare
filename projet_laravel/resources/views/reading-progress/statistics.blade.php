@extends('layouts.app')

@section('title', 'Statistiques de Lecture')

@push('styles')
<style>
.evaluation-card {
    transition: transform 0.3s ease;
}

.evaluation-card:hover {
    transform: translateY(-5px);
}

.badge-pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.9;
    }
}

.stat-icon {
    transition: transform 0.3s ease;
}

.card:hover .stat-icon {
    transform: scale(1.1) rotate(5deg);
}

.progress-ring {
    transition: stroke-dasharray 1s ease;
}
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1">📊 Mes Statistiques de Lecture</h1>
            <p class="text-muted mb-0">Suivez vos progrès et vos habitudes de lecture</p>
        </div>
        <a href="{{ route('reading-progress.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <!-- Statistiques principales -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-books fa-3x text-primary mb-3"></i>
                    <h3 class="mb-1">{{ $stats['total_books'] }}</h3>
                    <p class="text-muted mb-0">Livres suivis</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-book-open fa-3x text-info mb-3"></i>
                    <h3 class="mb-1">{{ $stats['reading'] }}</h3>
                    <p class="text-muted mb-0">En cours</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h3 class="mb-1">{{ $stats['completed'] }}</h3>
                    <p class="text-muted mb-0">Terminés</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-bookmark fa-3x text-warning mb-3"></i>
                    <h3 class="mb-1">{{ $stats['to_read'] }}</h3>
                    <p class="text-muted mb-0">À lire</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Évaluation de l'utilisateur -->
    <div class="card shadow-sm mb-4 border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body text-white">
            <div class="row align-items-center">
                <div class="col-md-4 text-center border-end border-white border-opacity-25">
                    <div class="mb-3">
                        <div class="display-1">{{ $evaluation['level']['icon'] }}</div>
                        <h3 class="mb-2">{{ $evaluation['level']['name'] }}</h3>
                        <span class="badge bg-white text-dark px-3 py-2 fs-5">Rang {{ $evaluation['level']['rank'] }}</span>
                    </div>
                    <p class="mb-0 opacity-75">{{ $evaluation['level']['description'] }}</p>
                </div>
                
                <div class="col-md-4 text-center border-end border-white border-opacity-25">
                    <h5 class="mb-3"><i class="fas fa-chart-line me-2"></i>Score Global</h5>
                    <div class="position-relative d-inline-block">
                        <svg width="150" height="150">
                            <circle cx="75" cy="75" r="65" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="10"/>
                            <circle cx="75" cy="75" r="65" fill="none" stroke="white" stroke-width="10"
                                    stroke-dasharray="{{ (($evaluation['score'] / $evaluation['max_score']) * 408) }}, 408"
                                    transform="rotate(-90 75 75)" stroke-linecap="round"/>
                        </svg>
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <div class="display-4 fw-bold">{{ $evaluation['score'] }}</div>
                            <small class="opacity-75">/{{ $evaluation['max_score'] }}</small>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 8px; background: rgba(255,255,255,0.2);">
                            <div class="progress-bar bg-white" role="progressbar" 
                                 style="width: {{ ($evaluation['score'] / $evaluation['max_score']) * 100 }}%"
                                 aria-valuenow="{{ $evaluation['score'] }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <small class="opacity-75 mt-2 d-block">
                            {{ round(($evaluation['score'] / $evaluation['max_score']) * 100) }}% de progression
                        </small>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <h5 class="mb-3"><i class="fas fa-trophy me-2"></i>Badges Obtenus</h5>
                    @if(count($evaluation['badges']) > 0)
                        <div class="d-flex flex-wrap gap-2 justify-content-center mb-3">
                            @foreach($evaluation['badges'] as $badge)
                                <div class="badge bg-white bg-opacity-25 px-3 py-2 rounded-pill">
                                    <span class="fs-5 me-1">{{ $badge['icon'] }}</span>
                                    <span>{{ $badge['name'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center opacity-75 mb-3">
                            <i class="fas fa-medal fs-3 mb-2 d-block"></i>
                            Aucun badge pour le moment
                        </p>
                    @endif
                    
                    @if(count($evaluation['recommendations']) > 0)
                        <div class="mt-3">
                            <h6 class="mb-2"><i class="fas fa-lightbulb me-2"></i>Recommandations</h6>
                            <ul class="list-unstyled mb-0 small opacity-75">
                                @foreach($evaluation['recommendations'] as $recommendation)
                                    <li class="mb-1"><i class="fas fa-arrow-right me-2"></i>{{ $recommendation }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques détaillées -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-percentage fa-2x text-success mb-3"></i>
                    <h4 class="mb-1">{{ $evaluation['completion_rate'] }}%</h4>
                    <p class="text-muted mb-0">Taux de complétion</p>
                    <small class="text-muted">
                        {{ $stats['completed'] }} terminés sur {{ $stats['total_books'] }} livres
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-book-reader fa-2x text-info mb-3"></i>
                    <h4 class="mb-1">{{ $stats['reading'] }}</h4>
                    <p class="text-muted mb-0">Livres en cours</p>
                    <small class="text-muted">
                        @if($stats['reading'] >= 3 && $stats['reading'] <= 7)
                            <i class="fas fa-check-circle text-success"></i> Nombre optimal
                        @elseif($stats['reading'] > 7)
                            <i class="fas fa-exclamation-triangle text-warning"></i> Trop de livres en cours
                        @else
                            <i class="fas fa-info-circle text-info"></i> Vous pouvez en lire plus
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x text-warning mb-3"></i>
                    <h4 class="mb-1">{{ $evaluation['reading_time_hours'] }}h</h4>
                    <p class="text-muted mb-0">Temps total de lecture</p>
                    <small class="text-muted">
                        {{ round($stats['total_reading_time'] / 60, 1) }} heures investies
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques de pages -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-file-alt me-2 text-primary"></i>Pages lues
                    </h5>
                    <div class="text-center">
                        <h2 class="display-4 text-primary mb-2">{{ number_format($stats['total_pages_read']) }}</h2>
                        <p class="text-muted">pages au total</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-clock me-2 text-success"></i>Temps de lecture
                    </h5>
                    <div class="text-center">
                        <h2 class="display-4 text-success mb-2">
                            @php
                                $hours = floor($stats['total_reading_time'] / 60);
                                $minutes = $stats['total_reading_time'] % 60;
                            @endphp
                            {{ $hours }}h {{ $minutes }}m
                        </h2>
                        <p class="text-muted">temps total</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Répartition par statut -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Répartition de vos lectures</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-3">
                    <div class="mb-2">
                        <div class="progress mx-auto" style="width: 80px; height: 80px; border-radius: 50%; position: relative;">
                            @php
                                $readingPercent = $stats['total_books'] > 0 ? round(($stats['reading'] / $stats['total_books']) * 100) : 0;
                            @endphp
                            <div class="progress-bar bg-info" role="progressbar" 
                                 style="width: {{ $readingPercent }}%; height: 100%; border-radius: 50%;">
                            </div>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $readingPercent }}%</h4>
                    <p class="text-muted small mb-0">En cours</p>
                </div>

                <div class="col-3">
                    <div class="mb-2">
                        <div class="progress mx-auto" style="width: 80px; height: 80px; border-radius: 50%;">
                            @php
                                $completedPercent = $stats['total_books'] > 0 ? round(($stats['completed'] / $stats['total_books']) * 100) : 0;
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $completedPercent }}%; height: 100%; border-radius: 50%;">
                            </div>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $completedPercent }}%</h4>
                    <p class="text-muted small mb-0">Terminés</p>
                </div>

                <div class="col-3">
                    <div class="mb-2">
                        <div class="progress mx-auto" style="width: 80px; height: 80px; border-radius: 50%;">
                            @php
                                $toReadPercent = $stats['total_books'] > 0 ? round(($stats['to_read'] / $stats['total_books']) * 100) : 0;
                            @endphp
                            <div class="progress-bar bg-warning" role="progressbar" 
                                 style="width: {{ $toReadPercent }}%; height: 100%; border-radius: 50%;">
                            </div>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $toReadPercent }}%</h4>
                    <p class="text-muted small mb-0">À lire</p>
                </div>

                <div class="col-3">
                    <div class="mb-2">
                        <div class="progress mx-auto" style="width: 80px; height: 80px; border-radius: 50%;">
                            @php
                                $abandonedPercent = $stats['total_books'] > 0 ? round(($stats['abandoned'] / $stats['total_books']) * 100) : 0;
                            @endphp
                            <div class="progress-bar bg-secondary" role="progressbar" 
                                 style="width: {{ $abandonedPercent }}%; height: 100%; border-radius: 50%;">
                            </div>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $abandonedPercent }}%</h4>
                    <p class="text-muted small mb-0">Abandonnés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Objectifs de progression -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-bullseye me-2"></i>Objectifs de Progression</h5>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span><i class="fas fa-book text-primary me-2"></i>Prochain niveau : 
                        @if($evaluation['score'] >= 85)
                            <strong>Niveau Maximum Atteint!</strong> 🎉
                        @elseif($evaluation['score'] >= 70)
                            <strong>Maître Lecteur (85 points)</strong>
                        @elseif($evaluation['score'] >= 50)
                            <strong>Expert (70 points)</strong>
                        @elseif($evaluation['score'] >= 30)
                            <strong>Intermédiaire (50 points)</strong>
                        @else
                            <strong>Débutant (30 points)</strong>
                        @endif
                    </span>
                    <span class="badge bg-primary">
                        @if($evaluation['score'] >= 85)
                            👑 Niveau Max
                        @else
                            {{ $evaluation['score'] >= 70 ? (85 - $evaluation['score']) : ($evaluation['score'] >= 50 ? (70 - $evaluation['score']) : ($evaluation['score'] >= 30 ? (50 - $evaluation['score']) : (30 - $evaluation['score']))) }} 
                            points restants
                        @endif
                    </span>
                </div>
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $evaluation['level']['color'] }}" 
                         role="progressbar" 
                         style="width: {{ ($evaluation['score'] / $evaluation['max_score']) * 100 }}%"
                         aria-valuenow="{{ $evaluation['score'] }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        {{ $evaluation['score'] }} / {{ $evaluation['max_score'] }}
                    </div>
                </div>
            </div>

            <!-- Progression détaillée par critère -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">📚 Livres terminés</small>
                        <small class="fw-bold">{{ $stats['completed'] }} livres</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: {{ min(($stats['completed'] / 20) * 100, 100) }}%"></div>
                    </div>
                    <small class="text-muted">Objectif: 20 livres (max 30 pts)</small>
                </div>

                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">📄 Pages lues</small>
                        <small class="fw-bold">{{ number_format($stats['total_pages_read']) }} pages</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-danger" style="width: {{ min(($stats['total_pages_read'] / 5000) * 100, 100) }}%"></div>
                    </div>
                    <small class="text-muted">Objectif: 5000 pages (max 25 pts)</small>
                </div>

                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">⏰ Temps de lecture</small>
                        <small class="fw-bold">{{ $evaluation['reading_time_hours'] }}h</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: {{ min(($evaluation['reading_time_hours'] / 100) * 100, 100) }}%"></div>
                    </div>
                    <small class="text-muted">Objectif: 100 heures (max 20 pts)</small>
                </div>

                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">✅ Taux de complétion</small>
                        <small class="fw-bold">{{ $evaluation['completion_rate'] }}%</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: {{ min(($evaluation['completion_rate'] / 80) * 100, 100) }}%"></div>
                    </div>
                    <small class="text-muted">Objectif: 80% (max 15 pts)</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Livres récemment terminés -->
    @if($recentlyCompleted->isNotEmpty())
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Livres récemment terminés</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($recentlyCompleted as $progress)
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-center">
                                @if($progress->book->photo)
                                    <img src="{{ asset('storage/' . $progress->book->photo) }}" 
                                         alt="{{ $progress->book->title }}" 
                                         class="rounded me-3"
                                         style="width: 50px; height: 75px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 75px;">
                                        <i class="fas fa-book text-muted"></i>
                                    </div>
                                @endif
                                
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $progress->book->title }}</h6>
                                    <p class="text-muted small mb-0">
                                        par {{ $progress->book->author }} • 
                                        Terminé {{ $progress->finished_at->diffForHumans() }}
                                    </p>
                                </div>

                                <div class="text-end">
                                    <div class="badge bg-success mb-1">{{ $progress->total_pages }} pages</div>
                                    <div class="text-muted small">{{ $progress->formatted_reading_time }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
