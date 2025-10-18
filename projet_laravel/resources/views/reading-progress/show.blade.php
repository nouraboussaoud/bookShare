@extends('layouts.app')

@section('title', 'Détails de la Progression')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('reading-progress.index') }}" class="btn btn-outline-secondary mb-3">
            <i class="fas fa-arrow-left me-2"></i>Retour à mes lectures
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Colonne gauche: Info du livre -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    @if($readingProgress->book->photo)
                        <img src="{{ asset('storage/' . $readingProgress->book->photo) }}" 
                             alt="{{ $readingProgress->book->title }}" 
                             class="img-fluid rounded mb-3"
                             style="max-height: 300px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded mb-3 d-flex align-items-center justify-content-center" 
                             style="height: 300px;">
                            <i class="fas fa-book fa-5x text-muted"></i>
                        </div>
                    @endif

                    <h3 class="h5 mb-2">{{ $readingProgress->book->title }}</h3>
                    <p class="text-muted mb-3">par {{ $readingProgress->book->author }}</p>
                    
                    @if($readingProgress->book->category)
                        <span class="badge bg-secondary mb-3">{{ $readingProgress->book->category->name }}</span>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('books.show', $readingProgress->book) }}" class="btn btn-outline-primary">
                            <i class="fas fa-book me-2"></i>Voir le livre
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite: Progression -->
        <div class="col-lg-8">
            <!-- Statut et Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">
                            <span class="badge bg-{{ $readingProgress->status === 'reading' ? 'primary' : ($readingProgress->status === 'completed' ? 'success' : ($readingProgress->status === 'to_read' ? 'info' : 'secondary')) }} fs-6">
                                {{ $readingProgress->status_label }}
                            </span>
                        </h4>
                        
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cog me-1"></i>Actions
                            </button>
                            <ul class="dropdown-menu">
                                @if($readingProgress->status !== 'reading')
                                    <li>
                                        <form action="{{ route('books.startReading', $readingProgress->book) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-play me-2"></i>Commencer la lecture
                                            </button>
                                        </form>
                                    </li>
                                @endif
                                @if($readingProgress->status === 'reading')
                                    <li>
                                        <form action="{{ route('reading-progress.complete', $readingProgress) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-check me-2"></i>Marquer comme terminé
                                            </button>
                                        </form>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('reading-progress.destroy', $readingProgress) }}" method="POST" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette progression ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-trash me-2"></i>Supprimer
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Barre de progression -->
                    @if($readingProgress->total_pages)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold">Progression</span>
                                <span class="text-primary fw-bold">{{ $readingProgress->progress_percentage }}%</span>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" 
                                     role="progressbar" 
                                     style="width: {{ $readingProgress->progress_percentage }}%">
                                    {{ $readingProgress->current_page }} / {{ $readingProgress->total_pages }}
                                </div>
                            </div>
                            <small class="text-muted">{{ $readingProgress->pages_remaining }} pages restantes</small>
                        </div>
                    @endif

                    <!-- Statistiques -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <i class="fas fa-book-open text-primary fa-2x mb-2"></i>
                                <div class="fw-bold">{{ $readingProgress->current_page }}</div>
                                <small class="text-muted">Pages lues</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <i class="fas fa-clock text-success fa-2x mb-2"></i>
                                <div class="fw-bold">{{ $readingProgress->formatted_reading_time }}</div>
                                <small class="text-muted">Temps de lecture</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <i class="fas fa-calendar text-info fa-2x mb-2"></i>
                                <div class="fw-bold">
                                    @if($readingProgress->started_at)
                                        {{ $readingProgress->started_at->diffForHumans() }}
                                    @else
                                        -
                                    @endif
                                </div>
                                <small class="text-muted">Démarré</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de mise à jour -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Mettre à jour la progression</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reading-progress.update', $readingProgress) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="current_page" class="form-label">Page actuelle</label>
                                <input type="number" class="form-control" id="current_page" name="current_page" 
                                       value="{{ $readingProgress->current_page }}" min="0" 
                                       max="{{ $readingProgress->total_pages ?? 9999 }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="total_pages" class="form-label">Total de pages</label>
                                <input type="number" class="form-control" id="total_pages" name="total_pages" 
                                       value="{{ $readingProgress->total_pages }}" min="1">
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="to_read" {{ $readingProgress->status === 'to_read' ? 'selected' : '' }}>À lire</option>
                                    <option value="reading" {{ $readingProgress->status === 'reading' ? 'selected' : '' }}>En cours</option>
                                    <option value="completed" {{ $readingProgress->status === 'completed' ? 'selected' : '' }}>Terminé</option>
                                    <option value="abandoned" {{ $readingProgress->status === 'abandoned' ? 'selected' : '' }}>Abandonné</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="reading_time_minutes" class="form-label">Temps de lecture (minutes)</label>
                                <input type="number" class="form-control" id="reading_time_minutes" name="reading_time_minutes" 
                                       value="{{ $readingProgress->reading_time_minutes }}" min="0">
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label">Notes personnelles</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" 
                                          placeholder="Vos impressions, citations préférées...">{{ $readingProgress->notes }}</textarea>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Notes -->
            @if($readingProgress->notes)
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Mes notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $readingProgress->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
