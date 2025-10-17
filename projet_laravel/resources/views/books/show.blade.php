@extends('layouts.layout')
@section('title', $book->title)
@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Livres</a></li>
                <li class="breadcrumb-item active">{{ $book->title }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Book Image -->
            <div class="col-lg-4 col-md-5 mb-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        @if($book->photo)
                            <img src="{{ $book->photo_url }}" alt="{{ $book->title }}" 
                                 class="img-fluid rounded shadow-sm" style="max-height: 500px;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="height: 400px;">
                                <div class="text-center text-muted">
                                    <i class="fas fa-book fa-4x mb-3"></i>
                                    <p>Aucune image disponible</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow mt-3">
                    <div class="card-body">
                        <h6 class="card-title text-primary">Actions Rapides</h6>
                        <div class="d-grid gap-2">
                            @php
                                $userProgress = Auth::user()->readingProgress()->where('book_id', $book->id)->first();
                            @endphp
                            
                            @if(!$userProgress)
                                <!-- Ajouter à Mes Lectures -->
                                <form action="{{ route('books.markToRead', $book) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-info w-100">
                                        <i class="fas fa-bookmark"></i> Ajouter à "À lire"
                                    </button>
                                </form>
                                
                                <form action="{{ route('books.startReading', $book) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-book-open"></i> Commencer la lecture
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('reading-progress.show', $userProgress) }}" class="btn btn-outline-success w-100">
                                    <i class="fas fa-check-circle"></i> Voir ma progression
                                </a>
                            @endif
                            
                            @if(Auth::id() != $book->user_id)
                                <hr>
                                <!-- Actions pour les autres utilisateurs -->
                                @if($book->estDisponiblePourLocation())
                                    <a href="{{ route('locations.create', ['book_id' => $book->id]) }}" 
                                       class="btn btn-primary">
                                        <i class="fas fa-handshake"></i> Louer ce livre
                                    </a>
                                @else
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-lock"></i> Non disponible
                                    </button>
                                @endif
                                
                                @if(!$book->review)
                                    <a href="{{ route('reviews.create', ['book_id' => $book->id]) }}" 
                                       class="btn btn-warning">
                                        <i class="fas fa-star"></i> Donner un Avis
                                    </a>
                                @endif
                                
                                <button class="btn btn-success">
                                    <i class="fas fa-envelope"></i> Contacter le Propriétaire
                                </button>
                            @else
                                <hr>
                                <!-- Actions pour le propriétaire -->
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                
                                @if($book->estEnLocation())
                                    <div class="alert alert-info mt-2 mb-0">
                                        <i class="fas fa-info-circle"></i>
                                        Ce livre est actuellement en location.
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Details -->
            <div class="col-lg-8 col-md-7">
                <div class="card shadow">
                    <div class="card-body">
                        <!-- Title and Category -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h1 class="h2 mb-2">{{ $book->title }}</h1>
                                <p class="h5 text-muted mb-0">par {{ $book->author }}</p>
                            </div>
                            @if($book->category)
                                <span class="badge badge-lg p-2" 
                                      style="background-color: {{ $book->category->color }}; color: white; font-size: 0.9rem;">
                                    <i class="{{ $book->category->icon }}"></i> {{ $book->category->name }}
                                </span>
                            @endif
                        </div>

                        <!-- Rating and Status -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                @if($book->review)
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="rating me-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $book->review->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-muted">({{ $book->review->rating }}/5)</span>
                                    </div>
                                @else
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-star-half-alt"></i> Aucun avis pour le moment
                                    </p>
                                @endif
                            </div>
                            <div class="col-md-6 text-md-end">
                                <span class="badge badge-{{ $book->status == 'available' ? 'success' : 'warning' }} badge-lg p-2">
                                    <i class="fas fa-{{ $book->status == 'available' ? 'check-circle' : 'clock' }}"></i>
                                    {{ $book->status == 'available' ? 'Disponible' : 'Réservé' }}
                                </span>
                            </div>
                        </div>

                        <!-- Book Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-primary">Informations du Livre</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>Âge recommandé:</strong></td>
                                        <td>{{ $book->age_display }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Propriétaire:</strong></td>
                                        <td>{{ $book->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ajouté le:</strong></td>
                                        <td>{{ $book->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @if($book->category)
                                    <tr>
                                        <td><strong>Catégorie:</strong></td>
                                        <td>{{ $book->category->name }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-6">
                                @if($book->category && $book->category->reading_tips)
                                    <h6 class="text-primary">Conseils de Lecture</h6>
                                    <p class="text-muted small">{{ $book->category->reading_tips }}</p>
                                @endif
                                
                                @if($book->category && $book->category->popular_authors)
                                    <h6 class="text-primary">Auteurs Populaires du Genre</h6>
                                    <p class="text-muted small">{{ $book->category->popular_authors_list }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Description -->
                        @if($book->description)
                            <div class="mb-4">
                                <h6 class="text-primary">Description</h6>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0">{{ $book->description }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Review Section -->
                        @if($book->review)
                            <div class="mb-4">
                                <h6 class="text-primary">Avis des Lecteurs</h6>
                                <div class="card border-left-warning">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $book->review->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-muted"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-2 font-weight-bold">{{ $book->review->rating }}/5</span>
                                            </div>
                                            <small class="text-muted">{{ $book->review->created_at->format('d/m/Y') }}</small>
                                        </div>
                                        
                                        @if($book->review->comment)
                                            <p class="mb-2">{{ $book->review->comment }}</p>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge badge-{{ $book->review->status == 'APPROVED' ? 'success' : ($book->review->status == 'REJECTED' ? 'danger' : 'warning') }}">
                                                {{ ucfirst(strtolower($book->review->status)) }}
                                            </span>
                                            @if($book->review->admin_reply)
                                                <small class="text-info">
                                                    <i class="fas fa-reply"></i> Réponse admin disponible
                                                </small>
                                            @endif
                                        </div>
                                        
                                        @if($book->review->admin_reply)
                                            <div class="mt-3 p-2 bg-info text-white rounded">
                                                <small><strong>Réponse de l'administrateur:</strong></small>
                                                <p class="mb-0 small">{{ $book->review->admin_reply }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Similar Books -->
                        @if($book->category)
                            @php
                                $similarBooks = \App\Models\Book::where('category_id', $book->category->id)
                                    ->where('id', '!=', $book->id)
                                    ->where('status', 'available')
                                    ->with(['user', 'category'])
                                    ->limit(3)
                                    ->get();
                            @endphp
                            
                            @if($similarBooks->count() > 0)
                                <div class="mb-4">
                                    <h6 class="text-primary">Livres Similaires</h6>
                                    <div class="row">
                                        @foreach($similarBooks as $similarBook)
                                            <div class="col-md-4 mb-3">
                                                <div class="card h-100 shadow-sm">
                                                    <div class="card-body p-3">
                                                        <h6 class="card-title small">{{ Str::limit($similarBook->title, 25) }}</h6>
                                                        <p class="card-text small text-muted mb-1">{{ $similarBook->author }}</p>
                                                        <p class="card-text small text-info">{{ $similarBook->user->name }}</p>
                                                        <a href="{{ route('books.show', $similarBook) }}" class="btn btn-sm btn-outline-primary">
                                                            Voir
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badge-lg {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }
        
        .rating i {
            font-size: 1.2rem;
        }
        
        .card-title {
            color: #2c3e50;
        }
        
        .breadcrumb-item a {
            color: #007bff;
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            text-decoration: underline;
        }
    </style>
@endsection