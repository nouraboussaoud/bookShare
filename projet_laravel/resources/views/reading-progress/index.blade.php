@extends('layouts.layout')

@section('title', 'Ma Progression de Lecture')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1">📚 Ma Progression de Lecture</h1>
            <p class="text-muted mb-0">Suivez vos lectures en cours et vos objectifs</p>
        </div>
        <a href="{{ route('reading-progress.statistics') }}" class="btn btn-outline-primary">
            <i class="fas fa-chart-bar me-2"></i>Statistiques
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="progressTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="reading-tab" data-bs-toggle="tab" data-bs-target="#reading" type="button">
                <i class="fas fa-book-open me-2"></i>En cours ({{ $grouped['reading']->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="to-read-tab" data-bs-toggle="tab" data-bs-target="#to-read" type="button">
                <i class="fas fa-bookmark me-2"></i>À lire ({{ $grouped['to_read']->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button">
                <i class="fas fa-check-circle me-2"></i>Terminés ({{ $grouped['completed']->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="abandoned-tab" data-bs-toggle="tab" data-bs-target="#abandoned" type="button">
                <i class="fas fa-times-circle me-2"></i>Abandonnés ({{ $grouped['abandoned']->count() }})
            </button>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content" id="progressTabsContent">
        <!-- En cours -->
        <div class="tab-pane fade show active" id="reading" role="tabpanel">
            @if($grouped['reading']->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucune lecture en cours</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($grouped['reading'] as $progress)
                        @include('reading-progress.partials.progress-card', ['progress' => $progress])
                    @endforeach
                </div>
            @endif
        </div>

        <!-- À lire -->
        <div class="tab-pane fade" id="to-read" role="tabpanel">
            @if($grouped['to_read']->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-bookmark fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun livre dans votre liste "À lire"</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($grouped['to_read'] as $progress)
                        @include('reading-progress.partials.progress-card', ['progress' => $progress])
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Terminés -->
        <div class="tab-pane fade" id="completed" role="tabpanel">
            @if($grouped['completed']->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun livre terminé</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($grouped['completed'] as $progress)
                        @include('reading-progress.partials.progress-card', ['progress' => $progress])
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Abandonnés -->
        <div class="tab-pane fade" id="abandoned" role="tabpanel">
            @if($grouped['abandoned']->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-times-circle fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun livre abandonné</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($grouped['abandoned'] as $progress)
                        @include('reading-progress.partials.progress-card', ['progress' => $progress])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
