@extends('layouts.app')
@section('title', 'Mon Espace')
@section('content')

<!-- Animated Stars Background -->
<div class="stars-background" id="starsContainer"></div>

<!-- Secondary Navigation Bar - Search & Filters -->
@if(isset($categories))
<div class="secondary-navbar-wrapper">
    <div class="secondary-navbar">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-3 flex-wrap justify-content-between">
                <form method="GET" action="{{ route('user.dashboard') }}" class="secondary-navbar-form d-flex align-items-center gap-3 flex-grow-1">
                    <div class="search-group flex-grow-1" style="max-width: 400px;">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" name="search" 
                                   value="{{ $search ?? '' }}" placeholder="Rechercher un livre par titre ou auteur...">
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <select class="form-select form-select-sm" name="category" style="min-width: 180px;">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ ($categoryId ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <select class="form-select form-select-sm" name="age" style="min-width: 150px;">
                            <option value="">Tous les âges</option>
                            <option value="6" {{ ($ageFilter ?? '') == '6' ? 'selected' : '' }}>6 ans et moins</option>
                            <option value="9" {{ ($ageFilter ?? '') == '9' ? 'selected' : '' }}>9 ans et moins</option>
                            <option value="12" {{ ($ageFilter ?? '') == '12' ? 'selected' : '' }}>12 ans et moins</option>
                            <option value="15" {{ ($ageFilter ?? '') == '15' ? 'selected' : '' }}>15 ans et moins</option>
                            <option value="18" {{ ($ageFilter ?? '') == '18' ? 'selected' : '' }}>18 ans et moins</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-purple btn-sm px-4">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                    
                    @if(($categoryId ?? false) || ($ageFilter ?? false) || ($search ?? false))
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i> Effacer
                        </a>
                    @endif
                </form>
                
                <!-- Action Buttons -->
                <div class="d-flex gap-2">
                    <a href="{{ route('books.create') }}" class="btn btn-light btn-sm px-3">
                        <i class="fas fa-plus me-1"></i>Ajouter un livre
                    </a>
                    <a href="{{ route('books.index') }}" class="btn btn-outline-light btn-sm px-3">
                        <i class="fas fa-book me-1"></i>Mes Livres
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show modern-alert" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show modern-alert" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Hero Section - YoKart Inspired -->
    <div class="hero-section mb-4">
        <div class="row g-0">
            <!-- Left Section: Image with Title Overlay -->
            <div class="col-lg-7">
                <div class="hero-image-container">
                    <img src="{{ asset('images/reading-old-man.jpg') }}" 
                         alt="BookShare Community" class="hero-image">
                    <div class="hero-overlay">
                        <h2 class="hero-overlay-title"></h2>
                    </div>
                </div>
            </div>
            
            <!-- Right Section: Content -->
            <div class="col-lg-5">
                <div class="hero-content">
                    <h3 class="hero-title text-center">
                        @auth
                            Bonjour, {{ Auth::user()->name }} ! 
                        @else
                            Rejoignez notre communauté
                        @endauth
                    </h3>
                    <p class="hero-date text-center">
                        <i class="far fa-calendar-alt me-2"></i>Mis à jour le {{ now()->format('d F Y') }}
                    </p>
                    <p class="hero-description">
                        @auth
                            Bienvenue sur votre espace personnel. Gérez vos livres, découvrez de nouvelles lectures et échangez avec une communauté passionnée de lecteurs.
                        @else
                            Rejoignez une communauté de lecteurs pour échanger des livres facilement. Trouvez des titres près de chez vous et élargissez votre collection. Inscrivez-vous pour commencer !
                        @endauth
                    </p>
                    <div class="text-center mt-3">
                        <a href="#livres-disponibles" class="btn hero-btn">
                            Découvrir les livres <i class="fas fa-arrow-down ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- News Ticker Bar -->
    <div class="news-ticker-wrapper mb-4">
        <div class="news-ticker-container">
            <div class="news-ticker-content">
                <div class="ticker-item">
                    <i class="fas fa-book"></i>
                    <span class="ticker-label">MES LIVRES</span>
                    <span class="ticker-value">{{ $myBooksCount ?? 0 }}</span>
                    <span class="ticker-badge">Total</span>
                </div>
                <div class="ticker-separator">•</div>
                <div class="ticker-item">
                    <i class="fas fa-book-open"></i>
                    <span class="ticker-label">DISPONIBLES</span>
                    <span class="ticker-value">{{ $availableBooksCount ?? 0 }}</span>
                    <span class="ticker-badge">Dans la communauté</span>
                </div>
                <div class="ticker-separator">•</div>
                <div class="ticker-item">
                    <i class="fas fa-exchange-alt"></i>
                    <span class="ticker-label">ÉCHANGES</span>
                    <span class="ticker-value">{{ $totalBooksCount ?? 0 }}</span>
                    <span class="ticker-badge">Total</span>
                </div>
                <div class="ticker-separator">•</div>
                <div class="ticker-item">
                    <i class="fas fa-users"></i>
                    <span class="ticker-label">COMMUNAUTÉ</span>
                    <span class="ticker-value">{{ $totalBooksCount ?? 0 }}</span>
                    <span class="ticker-badge">Membres actifs</span>
                </div>
                <!-- Duplicate content for seamless loop -->
                <div class="ticker-separator">•</div>
                <div class="ticker-item">
                    <i class="fas fa-book"></i>
                    <span class="ticker-label">MES LIVRES</span>
                    <span class="ticker-value">{{ $myBooksCount ?? 0 }}</span>
                    <span class="ticker-badge">Total</span>
                </div>
                <div class="ticker-separator">•</div>
                <div class="ticker-item">
                    <i class="fas fa-book-open"></i>
                    <span class="ticker-label">DISPONIBLES</span>
                    <span class="ticker-value">{{ $availableBooksCount ?? 0 }}</span>
                    <span class="ticker-badge">Dans la communauté</span>
                </div>
                <div class="ticker-separator">•</div>
                <div class="ticker-item">
                    <i class="fas fa-exchange-alt"></i>
                    <span class="ticker-label">ÉCHANGES</span>
                    <span class="ticker-value">{{ $totalBooksCount ?? 0 }}</span>
                    <span class="ticker-badge">Total</span>
                </div>
                <div class="ticker-separator">•</div>
                <div class="ticker-item">
                    <i class="fas fa-users"></i>
                    <span class="ticker-label">COMMUNAUTÉ</span>
                    <span class="ticker-value">{{ $totalBooksCount ?? 0 }}</span>
                    <span class="ticker-badge">Membres actifs</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Exchanges Section -->
    @if(isset($pendingExchanges) && $pendingExchanges->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card smooth mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">⏳ Échanges en attente</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingExchanges as $exchange)
                                <tr>
                                    <td>{{ $exchange->id }}</td>
                                    <td>{{ $exchange->type }}</td>
                                    <td>{{ $exchange->status }}</td>
                                    <td>
                                        <form action="{{ route('user.confirmExchange', $exchange->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-warning">Confirmer</button>
                                        </form>
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


    <!-- Mes Livres Récents (pleine largeur) -->
    <div class="row">
        <div class="col-12">
            <div class="card modern-card mb-4">
                <div class="card-header-modern">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="section-title mb-0">
                            <i class="fas fa-book-open me-2 text-purple"></i>Mes Livres Récents
                        </h5>
                        <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-purple btn-modern">
                            Voir tout <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if(isset($recentMyBooks) && $recentMyBooks->count() > 0)
                        <!-- Grille de cartes style Yo!Kart -->
                        <div class="row g-4">
                            @foreach($recentMyBooks as $book)
                            <div class="col-md-6 col-lg-4">
                                <div class="book-card-modern">
                                    <!-- Image du livre -->
                                    <div class="book-image-wrapper">
                                        @if($book->photo)
                                            <img src="{{ $book->photo_url }}" class="book-image" alt="{{ $book->title }}">
                                        @else
                                            <div class="book-image book-placeholder">
                                                <i class="fas fa-book fa-3x"></i>
                                            </div>
                                        @endif
                                        @if($book->category)
                                        <span class="book-category-badge" style="background-color: {{ $book->category->color }};">
                                            {{ $book->category->name }}
                                        </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Contenu de la carte -->
                                    <div class="book-card-content">
                                        <h6 class="book-title">{{ Str::limit($book->title, 40) }}</h6>
                                        <p class="book-author">
                                            <i class="fas fa-user me-1"></i>{{ $book->author }}
                                        </p>
                                        
                                        @if($book->description)
                                        <p class="book-description">
                                            {{ Str::limit($book->description, 80) }}
                                        </p>
                                        @endif
                                        
                                        <div class="book-footer">
                                            <span class="book-status book-status-{{ $book->status == 'available' ? 'available' : 'reserved' }}">
                                                {{ $book->status == 'available' ? 'Disponible' : 'Réservé' }}
                                            </span>
                                            <a href="{{ route('books.show', $book) }}" class="book-link">
                                                Voir plus <i class="fas fa-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun livre pour le moment</h5>
                            <p class="text-muted">Commencez par ajouter votre premier livre à votre bibliothèque.</p>
                            <a href="{{ route('books.create') }}" class="btn btn-purple btn-modern mt-3">
                                <i class="fas fa-plus me-2"></i>Ajouter un livre
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Available Books from Community -->
    @if(isset($availableBooks))
    <div class="row" id="livres-disponibles">
        <div class="col-12">
            <div class="card modern-card mb-4">
                <div class="card-header-modern">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="section-title mb-0">
                            <i class="fas fa-globe me-2 text-purple"></i>Livres Disponibles dans la Communauté
                        </h5>
                        @if(($categoryId ?? false) || ($ageFilter ?? false) || ($search ?? false))
                            <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-outline-secondary btn-modern">
                                <i class="fas fa-times me-1"></i> Effacer les filtres
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($availableBooks->count() > 0)
                        <div class="row g-4">
                            @foreach($availableBooks as $book)
                            <div class="col-md-6 col-lg-4">
                                <div class="book-card-modern">
                                    <!-- Image du livre -->
                                    <div class="book-image-wrapper">
                                        @if($book->photo)
                                            <img src="{{ $book->photo_url }}" class="book-image" alt="{{ $book->title }}">
                                        @else
                                            <div class="book-image book-placeholder">
                                                <i class="fas fa-book fa-3x"></i>
                                            </div>
                                        @endif
                                        @if($book->category)
                                        <span class="book-category-badge" style="background-color: {{ $book->category->color }};">
                                            {{ $book->category->name }}
                                        </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Contenu de la carte -->
                                    <div class="book-card-content">
                                        <h6 class="book-title">{{ Str::limit($book->title, 40) }}</h6>
                                        <p class="book-author">
                                            <i class="fas fa-user me-1"></i>{{ $book->author }}
                                        </p>
                                        <p class="book-owner">
                                            <i class="fas fa-user-circle me-1"></i>Propriétaire: {{ $book->user->name }}
                                        </p>
                                        
                                        @if($book->description)
                                        <p class="book-description">
                                            {{ Str::limit($book->description, 80) }}
                                        </p>
                                        @endif
                                        
                                        <div class="book-footer">
                                            <span class="book-status book-status-available">
                                                Disponible
                                            </span>
                                            <a href="{{ route('books.show', $book) }}" class="book-link">
                                                Voir plus <i class="fas fa-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if(method_exists($availableBooks, 'appends'))
                            <div class="d-flex justify-content-center mt-4">
                                {{ $availableBooks->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun livre trouvé</h5>
                            <p class="text-muted">Essayez de modifier vos critères de recherche.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <footer class="bookshare-footer mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="footer-title">
                        <i class="fas fa-book-open me-2"></i>BookShare
                    </h5>
                    <p class="footer-text">
                        Partagez vos livres, découvrez de nouvelles lectures et échangez avec une communauté passionnée de lecteurs.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="footer-subtitle">Navigation</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('books.index') }}">Livres</a></li>
                        <li><a href="{{ route('locations.marketplace') }}">Marketplace</a></li>
                        <li><a href="{{ route('exchanges.index') }}">Échanges</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="footer-subtitle">Ressources</h6>
                    <ul class="footer-links">
                        <li><a href="#">À propos</a></li>
                        <li><a href="#">Aide & Support</a></li>
                        <li><a href="#">Conditions d'utilisation</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="footer-subtitle">Contact</h6>
                    <ul class="footer-contact">
                        <li><i class="fas fa-envelope me-2"></i>contact@bookshare.com</li>
                        <li><i class="fas fa-phone me-2"></i>+33 1 23 45 67 89</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Paris, France</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0">&copy; {{ date('Y') }} BookShare. Tous droits réservés.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0">Fait avec <i class="fas fa-heart text-danger"></i> pour les amoureux des livres</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

@endsection

@push('styles')
<style>
    /* ========================================
       Modern Dashboard Styles - Yo-Kart Inspired
       Clean, Light, Professional Design
       ======================================== */
    
    /* Typography with Poppins/Inter */
    html {
        scroll-behavior: smooth;
    }
    
    body {
        font-family: 'Poppins', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: #f8f9fc;
        color: #1e293b;
        position: relative;
        overflow-x: hidden;
    }
    
    /* Animated Stars Background */
    .stars-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
        overflow: hidden;
    }
    
    .star {
        position: absolute;
        width: 2px;
        height: 2px;
        background: #667eea;
        border-radius: 50%;
        animation: twinkle 3s infinite ease-in-out;
        opacity: 0;
    }
    
    @keyframes twinkle {
        0%, 100% { opacity: 0; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.5); }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .star:nth-child(2n) {
        animation-duration: 4s;
        background: #764ba2;
    }
    
    .star:nth-child(3n) {
        animation-duration: 5s;
        width: 3px;
        height: 3px;
    }
    
    .star:nth-child(4n) {
        animation-delay: 1s;
    }
    
    .star:nth-child(5n) {
        animation-delay: 2s;
    }
    
    /* Ensure content is above stars */
    .container-fluid, .hero-section, .card, .row {
        position: relative;
        z-index: 1;
    }
    
    /* Hero Section - YoKart Inspired */
    .hero-section {
        margin-top: 100px;
        margin-left: auto;
        margin-right: auto;
        background: white;
        border-radius: 0px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        width: 80%;
        height: 50%;
    }
    
    .hero-image-container {
        position: relative;
        height: 100%;
        min-height: 280px;
        overflow: hidden;
    }
    
    .hero-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    
    .hero-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(8px);
        padding: 2rem;
    }
    
    .hero-overlay-title {
        color: white;
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.3;
    }
    
    .hero-content {
        padding: 1.75rem 2rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #f8f9fc;
    }
    
    .hero-badge {
        display: inline-block;
        padding: 0.375rem 1rem;
        background: #dbeafe;
        color: #1e40af;
        font-size: 0.8125rem;
        font-weight: 600;
        border-radius: 20px;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .hero-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        line-height: 1.3;
    }
    
    .hero-date {
        font-size: 0.8125rem;
        color: #64748b;
        margin-bottom: 0.75rem;
    }
    
    .hero-description {
        font-size: 1.125rem;
        color: #475569;
        line-height: 1.7;
        margin-bottom: 1rem;
    }
    
    .hero-btn {
        display: inline-flex;
        align-items: center;
        padding: 0.875rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        font-size: 0.9375rem;
        border-radius: 0px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .hero-btn:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }
    
    .hero-btn i {
        transition: transform 0.3s ease;
    }
    
    .hero-btn:hover i {
        transform: translateY(4px);
    }
    
    @media (max-width: 991px) {
        .hero-image-container {
            min-height: 220px;
        }
        
        .hero-content {
            padding: 1.5rem;
        }
        
        .hero-title {
            font-size: 1.25rem;
        }
        
        .hero-overlay-title {
            font-size: 1.25rem;
        }
    }
    
    /* Secondary Navigation Bar */
    .secondary-navbar-wrapper {
        position: sticky;
        top: 56px;
        z-index: 1010;
        margin: -1rem 0 1.5rem 0;
        margin-left: calc(-50vw + 50%);
        margin-right: calc(-50vw + 50%);
        width: 100vw;
    }
    
    .secondary-navbar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-bottom: none;
        padding: 0.875rem 0;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
        width: 100%;
    }
    
    .secondary-navbar-form {
        margin: 0;
    }
    
    .secondary-navbar .input-group-text {
        border-radius: 10px 0 0 10px;
        border-right: none;
        background: white;
        border-color: rgba(255, 255, 255, 0.3);
    }
    
    .secondary-navbar .form-control {
        border-radius: 0 10px 10px 0;
        border-left: none;
        font-size: 0.9375rem;
        background: white;
        border-color: rgba(255, 255, 255, 0.3);
    }
    
    .secondary-navbar .form-control::placeholder {
        color: #94a3b8;
    }
    
    .secondary-navbar .form-control:focus {
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
        border-color: white;
        background: white;
    }
    
    .secondary-navbar .form-select {
        border-radius: 0px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        font-size: 0.9375rem;
        padding: 0.5rem 0.75rem;
        background-color: white;
        transition: all 0.2s ease;
        color: #1e293b;
    }
    
    .secondary-navbar .form-select:focus {
        background-color: white;
        border-color: white;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
    }
    
    .secondary-navbar .btn {
        border-radius: 0px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .secondary-navbar .btn-primary {
        background: white;
        color: #667eea;
        border: none;
    }
    
    .secondary-navbar .btn-primary:hover {
        background: #f8f9fc;
        color: #667eea;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .secondary-navbar .btn-outline-secondary {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .secondary-navbar .btn-outline-secondary:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .secondary-navbar .btn-light {
        background: white;
        color: #667eea;
        border: none;
        font-weight: 500;
    }
    
    .secondary-navbar .btn-light:hover {
        background: #f8f9fc;
        color: #667eea;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .secondary-navbar .btn-outline-light {
        background: transparent;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.5);
        font-weight: 500;
    }
    
    .secondary-navbar .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border-color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .search-group .input-group {
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 0px;
        overflow: hidden;
        background: white;
        transition: all 0.2s ease;
    }
    
    .search-group .input-group:focus-within {
        border-color: white;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
    }
    
    @media (max-width: 768px) {
        .secondary-navbar {
            padding: 0.75rem 0;
        }
        
        .secondary-navbar .d-flex {
            gap: 0.5rem !important;
        }
        
        .search-group {
            width: 100%;
            max-width: 100% !important;
        }
        
        .filter-group {
            flex: 1;
            min-width: auto !important;
        }
        
        .filter-group select {
            min-width: 120px !important;
        }
    }
    
    /* Modern Breadcrumb */
    .modern-breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
        font-size: 0.875rem;
    }
    
    .modern-breadcrumb .breadcrumb-item a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .modern-breadcrumb .breadcrumb-item a:hover {
        color: #3b82f6;
    }
    
    .modern-breadcrumb .breadcrumb-item.active {
        color: #1e293b;
        font-weight: 500;
    }
    
    /* Dashboard Header */
    .dashboard-header {
        padding: 1.5rem 0;
    }
    
    .dashboard-title {
        font-size: 2rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }
    
    .dashboard-subtitle {
        font-size: 1rem;
        color: #64748b;
        font-weight: 400;
    }
    
    /* Modern Buttons */
    .btn-modern {
        border-radius: 0px;
        padding: 0.625rem 1.25rem;
        font-weight: 500;
        font-size: 0.9375rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
    
    .btn-modern:active {
        transform: translateY(0);
    }
    
    /* Modern Alert */
    .modern-alert {
        border-radius: 0px;
        border: none;
        padding: 1rem 1.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    
    /* Welcome Card for New Users */
    .welcome-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 16px;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        overflow: hidden;
    }
    
    .welcome-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }
    
    .quick-steps {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .step-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 0px;
        backdrop-filter: blur(10px);
    }
    
    .step-number {
        width: 32px;
        height: 32px;
        background: white;
        color: #667eea;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        flex-shrink: 0;
    }
    
    .step-text {
        color: white;
        font-size: 0.9375rem;
    }
    
    /* News Ticker Bar */
    .news-ticker-wrapper {
        width: 100%;
        overflow: hidden;
        position: relative;
        z-index: 1;
    }
    
    .news-ticker-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1rem 0;
        overflow: hidden;
        position: relative;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
    }
    
    .news-ticker-content {
        display: flex;
        align-items: center;
        gap: 2rem;
        animation: ticker-scroll 30s linear infinite;
        white-space: nowrap;
    }
    
    @keyframes ticker-scroll {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }
    
    .news-ticker-content:hover {
        animation-play-state: paused;
    }
    
    .ticker-item {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        color: white;
        padding: 0.5rem 1.5rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    
    .ticker-item:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.05);
    }
    
    .ticker-item i {
        font-size: 1.25rem;
        opacity: 0.9;
    }
    
    .ticker-label {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 1px;
        opacity: 0.9;
    }
    
    .ticker-value {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0.25rem;
    }
    
    .ticker-badge {
        font-size: 0.7rem;
        opacity: 0.8;
        font-weight: 500;
    }
    
    .ticker-separator {
        color: white;
        font-size: 1.5rem;
        opacity: 0.5;
    }
    
    @media (max-width: 768px) {
        .news-ticker-content {
            animation-duration: 20s;
        }
        
        .ticker-item {
            padding: 0.4rem 1rem;
            gap: 0.5rem;
        }
        
        .ticker-value {
            font-size: 1.25rem;
        }
    }
    
    /* Action Card */
    .action-card {
        background: white;
        border-radius: 0px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    
    .action-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .action-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8f9fc;
        border-radius: 0px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
    }
    
    .action-item:hover {
        background: white;
        border-color: #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateX(4px);
    }
    
    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    
    .bg-primary-soft { background: #dbeafe; }
    .bg-success-soft { background: #d1fae5; }
    .bg-info-soft { background: #cffafe; }
    .bg-warning-soft { background: #fef3c7; }
    
    .action-content {
        flex: 1;
    }
    
    .action-title {
        font-size: 0.9375rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }
    
    .action-desc {
        font-size: 0.8125rem;
        color: #64748b;
        margin: 0;
    }
    
    .action-arrow {
        color: #cbd5e1;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }
    
    .action-item:hover .action-arrow {
        color: #3b82f6;
        transform: translateX(4px);
    }
    
    /* Modern Card */
    .modern-card {
        background: white;
        border-radius: 0px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }
    
    .card-header-modern {
        padding: 1.5rem;
        background: white;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e293b;
    }
    
    /* Purple Text Color */
    .text-purple {
        color: #667eea !important;
    }
    
    /* Purple Outline Button */
    .btn-outline-purple {
        color: #667eea;
        border-color: #667eea;
        background: transparent;
    }
    
    .btn-outline-purple:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    /* Purple Badge */
    .badge-purple {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.35rem 0.65rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.75rem;
    }
    
    /* Purple Button */
    .btn-purple {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-purple:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    /* Book Card Modern */
    .book-card-modern {
        background: white;
        border-radius: 0px;
        overflow: hidden;
        border: 1px solid #f1f5f9;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .book-card-modern:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.12);
        border-color: #e2e8f0;
    }
    
    .book-image-wrapper {
        position: relative;
        overflow: hidden;
        height: 220px;
    }
    
    .book-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .book-card-modern:hover .book-image {
        transform: scale(1.08);
    }
    
    .book-placeholder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        opacity: 0.9;
    }
    
    .book-category-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 0.375rem 0.75rem;
        border-radius: 0px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        backdrop-filter: blur(8px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    .book-card-content {
        padding: 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .book-title {
        font-size: 1.0625rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }
    
    .book-author {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 0.5rem;
    }
    
    .book-owner {
        font-size: 0.8125rem;
        color: #667eea;
        font-weight: 500;
        margin-bottom: 0.75rem;
    }
    
    .book-description {
        font-size: 0.875rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 1rem;
        flex: 1;
    }
    
    .book-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
    }
    
    .book-status {
        padding: 0.375rem 0.875rem;
        border-radius: 0px;
        font-size: 0.8125rem;
        font-weight: 500;
    }
    
    .book-status-available {
        background: #d1fae5;
        color: #065f46;
    }
    
    .book-status-reserved {
        background: #fef3c7;
        color: #92400e;
    }
    
    .book-link {
        color: #3b82f6;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .book-link:hover {
        color: #2563eb;
        transform: translateX(2px);
    }
    
    /* Smooth Animations */
    .card.smooth {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card.smooth:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }
    
    /* Pagination Violet Style - Compact */
    .pagination {
        gap: 0.25rem;
        justify-content: center;
    }
    
    .pagination .page-link {
        color: white;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 6px;
        padding: 0.375rem 0.625rem;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        margin: 0 0.125rem;
        min-width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .pagination .page-link:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(102, 126, 234, 0.4);
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        color: white;
        box-shadow: 0 3px 8px rgba(102, 126, 234, 0.5);
        font-weight: 700;
    }
    
    .pagination .page-item.disabled .page-link {
        color: rgba(255, 255, 255, 0.5);
        background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
        opacity: 0.6;
    }
    
    /* Footer Styles */
    .bookshare-footer {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0 1rem;
        margin-top: 4rem;
        position: relative;
        z-index: 1;
    }
    
    .footer-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: white;
    }
    
    .footer-subtitle {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: white;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .footer-text {
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }
    
    .footer-social {
        display: flex;
        gap: 1rem;
    }
    
    .social-link {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    .social-link:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-3px);
        color: white;
    }
    
    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .footer-links li {
        margin-bottom: 0.75rem;
    }
    
    .footer-links a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .footer-links a:hover {
        color: white;
        transform: translateX(5px);
    }
    
    .footer-contact {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .footer-contact li {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
    }
    
    .footer-contact i {
        opacity: 0.8;
    }
    
    .footer-bottom {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.9);
    }
    
    .footer-bottom p {
        font-size: 0.875rem;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 1.5rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
        }
        
        .book-image-wrapper {
            height: 180px;
        }
        
        .bookshare-footer {
            padding: 2rem 0 1rem;
        }
        
        .footer-title {
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Generate animated stars
    document.addEventListener('DOMContentLoaded', function() {
        const starsContainer = document.getElementById('starsContainer');
        const numberOfStars = 50;
        
        for (let i = 0; i < numberOfStars; i++) {
            const star = document.createElement('div');
            star.className = 'star';
            
            // Random position
            star.style.left = Math.random() * 100 + '%';
            star.style.top = Math.random() * 100 + '%';
            
            // Random animation delay
            star.style.animationDelay = Math.random() * 3 + 's';
            
            starsContainer.appendChild(star);
        }
    });
</script>
@endpush