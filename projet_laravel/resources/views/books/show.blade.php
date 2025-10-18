@extends('layouts.app')
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
                                @if($book->reviews_count > 0)
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="rating me-2">
                                            @php
                                                $avgRating = round($book->average_rating);
                                            @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $avgRating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-muted">
                                            <strong>{{ number_format($book->average_rating, 1) }}/5</strong> 
                                            ({{ $book->reviews_count }} {{ $book->reviews_count > 1 ? 'avis' : 'avis' }})
                                        </span>
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

                        <!-- Tags -->
                        @if($book->categoryTags && $book->categoryTags->count() > 0)
                            <div class="mb-4">
                                <h6 class="text-primary">
                                    <i class="fas fa-tags"></i> Tags
                                </h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($book->categoryTags as $tag)
                                        <span class="badge" style="background-color: {{ $tag->color }}; color: white; font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                                            @if($tag->icon)
                                                <i class="{{ $tag->icon }}"></i>
                                            @endif
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- AI Summary -->
                        @if($book->ai_summary)
                            <div class="mb-4">
                                <h6 class="text-primary">
                                    <i class="fas fa-robot"></i> Résumé IA
                                </h6>
                                <div class="bg-gradient-light p-3 rounded border-left-primary">
                                    <p class="mb-0">{{ $book->ai_summary }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Description -->
                        @if($book->description)
                            <div class="mb-4">
                                <h6 class="text-primary">Description</h6>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0">{{ $book->description }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Generate AI Summary Button (for book owner) -->
                        @if(Auth::id() == $book->user_id && !$book->ai_summary)
                            <div class="mb-4">
                                <form action="{{ route('books.generateSummary', $book) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fas fa-magic"></i> Générer un résumé IA
                                    </button>
                                </form>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle"></i> L'IA créera un résumé captivant basé sur les informations du livre
                                </small>
                            </div>
                        @endif

                        <!-- Review Section -->
                        @if($book->reviews_count > 0)
                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-comments"></i> Avis des Lecteurs 
                                    <span class="badge bg-primary">{{ $book->reviews_count }}</span>
                                </h6>
                                
                                @foreach($book->reviews as $review)
                                    <div class="card border-left-warning mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <div class="rating mb-1">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                <i class="fas fa-star text-warning"></i>
                                                            @else
                                                                <i class="far fa-star text-muted"></i>
                                                            @endif
                                                        @endfor
                                                        <span class="ms-2 font-weight-bold">{{ $review->rating }}/5</span>
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-user"></i> Par {{ $review->user->name }}
                                                    </small>
                                                </div>
                                                <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            
                                            @if($review->comment)
                                                <p class="mb-2">{{ $review->comment }}</p>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge badge-{{ $review->status == 'approved' ? 'success' : ($review->status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($review->status) }}
                                                </span>
                                                @if($review->admin_reply)
                                                    <small class="text-info">
                                                        <i class="fas fa-reply"></i> Réponse admin disponible
                                                    </small>
                                                @endif
                                            </div>
                                            
                                            @if($review->admin_reply)
                                                <div class="mt-3 p-2 bg-info text-white rounded">
                                                    <small><strong>Réponse de l'administrateur:</strong></small>
                                                    <p class="mb-0 small">{{ $review->admin_reply }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Recommandations IA -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-primary mb-0">
                                    <i class="fas fa-robot text-success"></i> Recommandations IA pour Échange
                                </h6>
                                <button class="btn btn-sm btn-outline-info" id="refresh-recommendations" title="Actualiser les recommandations">
                                    <i class="fas fa-sync"></i> Actualiser
                                </button>
                            </div>
                            
                            <!-- Status IA -->
                            <div id="ai-recommendations-status" class="text-center" style="display: none;">
                                <div class="d-flex justify-content-center align-items-center mb-3">
                                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                    <small class="text-muted">L'IA analyse les livres disponibles...</small>
                                </div>
                            </div>
                            
                            <!-- Recommandations Container -->
                            <div id="ai-recommendations-container">
                                <div class="row" id="recommendations-list">
                                    <!-- Les recommandations seront chargées ici -->
                                </div>
                                
                                <!-- Message quand aucune recommandation -->
                                <div id="no-recommendations" class="text-center text-muted py-4" style="display: none;">
                                    <i class="fas fa-search fa-2x mb-2"></i>
                                    <p>Aucune recommandation disponible pour le moment</p>
                                    <small>L'IA cherche des livres compatibles...</small>
                                </div>
                                
                                <!-- Erreur IA -->
                                <div id="ai-error" class="alert alert-warning" style="display: none;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Service de recommandations temporairement indisponible
                                </div>
                            </div>
                        </div>
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
    
    <script>
        // Gestion des recommandations IA
        let recommendationsLoaded = false;
        let currentBookId = {{ $book->id }};
        
        // Fonction pour charger les recommandations
        async function loadAIRecommendations() {
            const statusDiv = document.getElementById('ai-recommendations-status');
            const containerDiv = document.getElementById('ai-recommendations-container');
            const listDiv = document.getElementById('recommendations-list');
            const noRecommendationsDiv = document.getElementById('no-recommendations');
            const errorDiv = document.getElementById('ai-error');
            
            // Afficher le loading
            statusDiv.style.display = 'block';
            containerDiv.style.display = 'none';
            
            try {
                const response = await fetch(`/api/recommend-books/${currentBookId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                // Masquer le loading
                statusDiv.style.display = 'none';
                containerDiv.style.display = 'block';
                
                if (data.success && data.recommendations.length > 0) {
                    // Afficher les recommandations
                    displayRecommendations(data.recommendations);
                    noRecommendationsDiv.style.display = 'none';
                    errorDiv.style.display = 'none';
                } else {
                    // Aucune recommandation
                    listDiv.innerHTML = '';
                    noRecommendationsDiv.style.display = 'block';
                    errorDiv.style.display = 'none';
                }
                
                recommendationsLoaded = true;
                
            } catch (error) {
                console.error('Erreur lors du chargement des recommandations:', error);
                statusDiv.style.display = 'none';
                containerDiv.style.display = 'block';
                listDiv.innerHTML = '';
                noRecommendationsDiv.style.display = 'none';
                errorDiv.style.display = 'block';
            }
        }
        
        // Fonction pour afficher les recommandations
        function displayRecommendations(recommendations) {
            const listDiv = document.getElementById('recommendations-list');
            
            listDiv.innerHTML = recommendations.map(rec => {
                const compatibility = Math.round(rec.compatibility_score);
                const compatibilityColor = getCompatibilityColor(compatibility);
                const compatibilityText = getCompatibilityText(compatibility);
                
                return `
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 shadow-sm border-0" style="transition: transform 0.2s;">
                            <div class="card-body p-3">
                                <!-- Score de compatibilité -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge ${compatibilityColor}">
                                        <i class="fas fa-robot"></i> ${compatibility}% compatible
                                    </span>
                                    <small class="text-muted">${compatibilityText}</small>
                                </div>
                                
                                <!-- Informations du livre -->
                                <h6 class="card-title text-dark mb-1" style="font-size: 0.9rem;">
                                    ${truncateText(rec.book.title, 30)}
                                </h6>
                                <p class="card-text small text-secondary mb-1">
                                    <i class="fas fa-user-edit"></i> ${rec.book.author}
                                </p>
                                <p class="card-text small text-info mb-2">
                                    <i class="fas fa-user"></i> ${rec.book.user.name}
                                </p>
                                
                                <!-- Raisons de compatibilité -->
                                ${rec.reasons && rec.reasons.length > 0 ? `
                                    <div class="mb-2">
                                        ${rec.reasons.slice(0, 2).map(reason => `
                                            <span class="badge badge-light text-dark small me-1" style="font-size: 0.7rem;">
                                                ${reason}
                                            </span>
                                        `).join('')}
                                    </div>
                                ` : ''}
                                
                                <!-- Actions -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="/books/${rec.book.id}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                    <button class="btn btn-sm btn-success" onclick="proposeExchange(${rec.book.id})" title="Proposer un échange">
                                        <i class="fas fa-handshake"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
        
        // Fonction pour obtenir la couleur du badge de compatibilité
        function getCompatibilityColor(score) {
            if (score >= 80) return 'badge-success';
            if (score >= 60) return 'badge-warning';
            return 'badge-secondary';
        }
        
        // Fonction pour obtenir le texte de compatibilité
        function getCompatibilityText(score) {
            if (score >= 80) return 'Excellent match';
            if (score >= 60) return 'Bon match';
            return 'Match possible';
        }
        
        // Fonction pour tronquer le texte
        function truncateText(text, maxLength) {
            if (text.length <= maxLength) return text;
            return text.substring(0, maxLength) + '...';
        }
        
        // Fonction pour proposer un échange
        function proposeExchange(bookId) {
            // Redirection vers la création d'échange
            window.location.href = `/exchanges/create?book_id=${bookId}&your_book_id=${currentBookId}`;
        }
        
        // Chargement au démarrage de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Charger les recommandations automatiquement
            setTimeout(loadAIRecommendations, 1000);
            
            // Bouton d'actualisation
            document.getElementById('refresh-recommendations').addEventListener('click', function() {
                loadAIRecommendations();
            });
        });
        
        // Style CSS pour les animations
        const style = document.createElement('style');
        style.textContent = `
            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.12) !important;
            }
            
            .spinner-border-sm {
                width: 1rem;
                height: 1rem;
            }
            
            .badge-light {
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection