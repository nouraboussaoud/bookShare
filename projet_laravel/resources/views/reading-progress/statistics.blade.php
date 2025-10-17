@extends('layouts.layout')

@section('title', 'Statistiques de Lecture')

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

    <!-- Statistiques détaillées -->
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
