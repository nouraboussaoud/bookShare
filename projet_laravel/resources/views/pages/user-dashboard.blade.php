@extends('layouts.layout')

@section('title', 'Dashboard Utilisateur')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">📚 Dashboard BookShare</h1>
            <p class="mb-0 text-gray-600">Bienvenue, {{ Auth::user()->name }} ! Trouvez votre prochain livre à lire</p>
        </div>
        <div>
            <a href="{{ route('books.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-plus fa-sm text-white-50"></i> Ajouter un Livre
            </a>
            <a href="{{ route('books.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-book fa-sm text-white-50"></i> Mes Livres
            </a>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Mes Livres</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $myBooksCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Livres</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBooksCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-books fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Disponibles</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $availableBooksCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Communauté</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Active</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Categories Section -->
    @if($featuredCategories->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📚 Catégories Populaires</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($featuredCategories as $category)
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('user.dashboard', ['category' => $category->id]) }}" class="text-decoration-none">
                                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid {{ $category->color ?? '#007bff' }} !important;">
                                    <div class="card-body text-center">
                                        <i class="{{ $category->icon ?? 'fas fa-book' }} fa-2x mb-2" style="color: {{ $category->color ?? '#007bff' }};"></i>
                                        <h6 class="card-title">{{ $category->name }}</h6>
                                        <p class="card-text text-muted small">Découvrir</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">🔍 Rechercher des Livres</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('user.dashboard') }}">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="search" class="form-label">Recherche</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ $search }}" placeholder="Titre ou auteur...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="category" class="form-label">Catégorie</label>
                                <select class="form-control" id="category" name="category">
                                    <option value="">Toutes les catégories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="age" class="form-label">Âge maximum</label>
                                <select class="form-control" id="age" name="age">
                                    <option value="">Tous les âges</option>
                                    <option value="6" {{ $ageFilter == '6' ? 'selected' : '' }}>6 ans et moins</option>
                                    <option value="9" {{ $ageFilter == '9' ? 'selected' : '' }}>9 ans et moins</option>
                                    <option value="12" {{ $ageFilter == '12' ? 'selected' : '' }}>12 ans et moins</option>
                                    <option value="15" {{ $ageFilter == '15' ? 'selected' : '' }}>15 ans et moins</option>
                                    <option value="18" {{ $ageFilter == '18' ? 'selected' : '' }}>18 ans et moins</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i> Rechercher
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Books -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-success">📖 Livres Disponibles</h6>
                    @if($categoryId || $ageFilter || $search)
                        <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times"></i> Effacer les filtres
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($availableBooks->count() > 0)
                        <div class="row">
                            @foreach($availableBooks as $book)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 shadow-sm book-card">
                                    @if($book->photo)
                                        <img src="{{ $book->photo_url }}" class="card-img-top" alt="{{ $book->title }}" 
                                             style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <i class="fas fa-book fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">
                                                <a href="{{ route('books.show', $book) }}" class="text-decoration-none">
                                                    {{ Str::limit($book->title, 25) }}
                                                </a>
                                            </h6>
                                            @if($book->category)
                                                <span class="badge" style="background-color: {{ $book->category->color }}; color: white;">
                                                    {{ $book->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="card-text text-muted mb-2">par {{ $book->author }}</p>
                                        <p class="card-text">
                                            <small class="text-info">
                                                <i class="fas fa-user"></i> {{ $book->user->name }}
                                            </small>
                                        </p>
                                        <div class="mb-3">
                                            <span class="badge badge-info">{{ $book->age_display }}</span>
                                            @if($book->review)
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-star"></i> {{ $book->review->rating }}/5
                                                </span>
                                            @endif
                                        </div>
                                        <div class="btn-group btn-group-sm w-100" role="group">
                                            <a href="{{ route('books.show', $book) }}" 
                                               class="btn btn-outline-info" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!$book->review)
                                                <a href="{{ route('reviews.create', ['book_id' => $book->id]) }}" 
                                                   class="btn btn-outline-primary" title="Donner un avis">
                                                    <i class="fas fa-star"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $availableBooks->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun livre trouvé</h5>
                            <p class="text-muted">Essayez de modifier vos critères de recherche.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- My Recent Books (if user has books) -->
    @if($recentMyBooks->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📚 Mes Livres Récents</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($recentMyBooks as $book)
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card h-100 shadow-sm book-card">
                                @if($book->photo)
                                    <img src="{{ $book->photo_url }}" class="card-img-top" alt="{{ $book->title }}" 
                                         style="height: 180px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 180px;">
                                        <i class="fas fa-book fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <a href="{{ route('books.show', $book) }}" class="text-decoration-none">
                                            {{ Str::limit($book->title, 20) }}
                                        </a>
                                    </h6>
                                    <p class="card-text text-muted small">par {{ $book->author }}</p>
                                    <div class="mb-2">
                                        <span class="badge badge-{{ $book->status === 'AVAILABLE' ? 'success' : 'warning' }}">
                                            {{ $book->status }}
                                        </span>
                                        @if($book->category)
                                            <span class="badge" style="background-color: {{ $book->category->color ?? '#007bff' }}; color: white; font-size: 0.7rem;">
                                                {{ $book->category->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="btn-group btn-group-sm w-100" role="group">
                                        <a href="{{ route('books.show', $book) }}" class="btn btn-outline-info" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('books.edit', $book) }}" class="btn btn-outline-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center">
                        <a href="{{ route('books.index') }}" class="btn btn-primary">Voir tous mes livres</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Welcome message for new users -->
    @if($myBooksCount === 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4 border-left-info">
                <div class="card-body text-center py-5">
                    <i class="fas fa-book-reader fa-4x text-info mb-4"></i>
                    <h4 class="text-info">Bienvenue dans BookShare !</h4>
                    <p class="text-muted mb-4">
                        Commencez votre aventure littéraire en ajoutant votre premier livre ou en explorant notre collection.
                    </p>
                    <div>
                        <a href="{{ route('books.create') }}" class="btn btn-info btn-lg mr-2">
                            <i class="fas fa-plus"></i> Ajouter mon premier livre
                        </a>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-info btn-lg">
                            <i class="fas fa-tags"></i> Voir les catégories
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
