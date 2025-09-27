@extends('layouts.layout')
@section('title', 'Mon Espace')
@section('content')
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">📚 Mon Espace BookShare</h1>
            <p class="mb-0 text-gray-600">Bienvenue, {{ Auth::user()->name }} ! Trouvez votre prochain livre à lire</p>
        </div>
        <div>
            <a href="{{ route('exchanges.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2">
                <i class="fas fa-book fa-sm text-white-50 mr-1"></i> Réserver un Livre
            </a>
            <a href="{{ route('exchanges.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm mr-2">
                <i class="fas fa-history fa-sm text-white-50 mr-1"></i> Voir l'Historique
            </a>
            <a href="{{ route('books.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-plus fa-sm text-white-50"></i> Ajouter un Livre
            </a>
            <a href="{{ route('books.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2">
                <i class="fas fa-book fa-sm text-white-50"></i> Mes Livres
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger shadow-sm">
                    <i class="fas fa-sign-out-alt fa-sm text-white-50"></i> Déconnexion
                </button>
            </form>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Mes Livres</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $myBooksCount ?? 12 }}</div>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Livres</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBooksCount ?? 8 }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $availableBooksCount ?? 15 }}</div>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                En Cours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book-reader fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exchanges Section -->
    @if(isset($pendingExchanges) && $pendingExchanges->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
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

    <!-- Featured Categories Section -->
    @if(isset($featuredCategories) && $featuredCategories->count() > 0)
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
    @if(isset($categories))
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
                                       value="{{ $search ?? '' }}" placeholder="Titre ou auteur...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="category" class="form-label">Catégorie</label>
                                <select class="form-control" id="category" name="category">
                                    <option value="">Toutes les catégories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ ($categoryId ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="age" class="form-label">Âge maximum</label>
                                <select class="form-control" id="age" name="age">
                                    <option value="">Tous les âges</option>
                                    <option value="6" {{ ($ageFilter ?? '') == '6' ? 'selected' : '' }}>6 ans et moins</option>
                                    <option value="9" {{ ($ageFilter ?? '') == '9' ? 'selected' : '' }}>9 ans et moins</option>
                                    <option value="12" {{ ($ageFilter ?? '') == '12' ? 'selected' : '' }}>12 ans et moins</option>
                                    <option value="15" {{ ($ageFilter ?? '') == '15' ? 'selected' : '' }}>15 ans et moins</option>
                                    <option value="18" {{ ($ageFilter ?? '') == '18' ? 'selected' : '' }}>18 ans et moins</option>
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
    @endif

    <!-- Content Row -->
    <div class="row">
        <!-- Mes Livres Récents -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">📖 Mes Livres Récents</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Actions:</div>
                            <a class="dropdown-item" href="{{ route('books.index') }}">Voir tous</a>
                            <a class="dropdown-item" href="{{ route('books.create') }}">Ajouter un livre</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($recentMyBooks) && $recentMyBooks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Auteur</th>
                                        <th>Genre</th>
                                        <th>Statut</th>
                                        <th>Date d'ajout</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentMyBooks as $book)
                                    <tr>
                                        <td>{{ $book->title }}</td>
                                        <td>{{ $book->author }}</td>
                                        <td>{{ $book->category->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $book->status == 'AVAILABLE' ? 'success' : ($book->status == 'BORROWED' ? 'warning' : 'info') }}">
                                                {{ $book->status }}
                                            </span>
                                        </td>
                                        <td>{{ $book->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Default static data if no books available -->
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Auteur</th>
                                        <th>Genre</th>
                                        <th>Statut</th>
                                        <th>Date d'ajout</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Le Petit Prince</td>
                                        <td>Antoine de Saint-Exupéry</td>
                                        <td>Fiction</td>
                                        <td><span class="badge badge-success">Lu</span></td>
                                        <td>2025-09-20</td>
                                    </tr>
                                    <tr>
                                        <td>1984</td>
                                        <td>George Orwell</td>
                                        <td>Science-Fiction</td>
                                        <td><span class="badge badge-warning">En cours</span></td>
                                        <td>2025-09-18</td>
                                    </tr>
                                    <tr>
                                        <td>L'Étranger</td>
                                        <td>Albert Camus</td>
                                        <td>Philosophie</td>
                                        <td><span class="badge badge-info">À lire</span></td>
                                        <td>2025-09-15</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4 col-lg-5">
            <!-- Recommandations -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">💡 Recommandations</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-lightbulb fa-3x text-gray-300 mb-4"></i>
                    </div>
                    <p>Découvrez de nouveaux livres recommandés par la communauté BookShare !</p>
                    <a href="#" class="btn btn-primary btn-sm btn-block">
                        <i class="fas fa-search mr-2"></i>Explorer les recommandations
                    </a>
                </div>
            </div>

            <!-- Activité Récente -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📈 Activité Récente</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Il y a 2 heures</small>
                                <p class="mb-0">Livre ajouté: "Dune"</p>
                            </div>
                            <i class="fas fa-plus text-success"></i>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Hier</small>
                                <p class="mb-0">Lecture terminée: "Le Petit Prince"</p>
                            </div>
                            <i class="fas fa-check text-primary"></i>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Il y a 3 jours</small>
                                <p class="mb-0">Livre partagé avec la communauté</p>
                            </div>
                            <i class="fas fa-share text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Books -->
    @if(isset($availableBooks))
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-success">📖 Livres Disponibles</h6>
                    @if(($categoryId ?? false) || ($ageFilter ?? false) || ($search ?? false))
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
                                            <span class="badge badge-info">{{ $book->age_display ?? 'Tous âges' }}</span>
                                            @if(isset($book->review) && $book->review)
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
                                            @if(isset($book->reviews))
                                                @php
                                                    $userReview = $book->reviews->where('user_id', Auth::id())->first();
                                                @endphp
                                                @if(!$userReview)
                                                    <a href="{{ route('reviews.create', ['book_id' => $book->id]) }}" 
                                                       class="btn btn-outline-primary" title="Donner un avis">
                                                        <i class="fas fa-star"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('reviews.edit', $userReview) }}" 
                                                       class="btn btn-outline-warning" title="Modifier mon avis">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if(method_exists($availableBooks, 'appends'))
                            <div class="d-flex justify-content-center">
                                {{ $availableBooks->appends(request()->query())->links() }}
                            </div>
                        @endif
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
    @endif

    <!-- Welcome message for new users -->
    @if(isset($myBooksCount) && $myBooksCount == 0)
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
                        <a href="{{ route('books.index', ['scope' => 'others']) }}" class="btn btn-outline-info btn-lg">
                            <i class="fas fa-book-open"></i> Découvrir les livres
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection