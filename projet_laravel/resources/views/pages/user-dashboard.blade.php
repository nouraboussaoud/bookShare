@extends('layouts.layout')
@section('title', 'Mon Espace')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Mon Espace BookShare</h1>
            <p class="mb-0 text-gray-600">Bienvenue, {{ Auth::user()->name }} !</p>
        </div>
        <div>
<<<<<<< Updated upstream
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2">
=======
            <a href="{{ route('locations.marketplace') }}" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm mr-2">
                <i class="fas fa-store fa-sm text-white-50 mr-1"></i> Marketplace Locations
            </a>
            <a href="{{ route('locations.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm mr-2">
                <i class="fas fa-handshake fa-sm text-white-50 mr-1"></i> Mes Locations
            </a>
            <a href="{{ route('exchanges.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2">
                <i class="fas fa-exchange-alt fa-sm text-white-50 mr-1"></i> Réserver un Livre
            </a>
            <a href="{{ route('exchanges.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2">
                <i class="fas fa-history fa-sm text-white-50 mr-1"></i> Historique Échanges
            </a>
            <a href="{{ route('books.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
>>>>>>> Stashed changes
                <i class="fas fa-plus fa-sm text-white-50"></i> Ajouter un Livre
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger shadow-sm">
                    <i class="fas fa-sign-out-alt fa-sm text-white-50"></i> Déconnexion
                </button>
            </form>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Mes Livres Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Mes Livres</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Livres Partagés Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Livres Partagés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-share-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Livres Favoris Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Favoris</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">15</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-heart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lectures en Cours Card -->
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

    <!-- Content Row -->
    <div class="row">
        <!-- Mes Livres Récents -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Mes Livres Récents</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Actions:</div>
                            <a class="dropdown-item" href="#">Voir tous</a>
                            <a class="dropdown-item" href="#">Ajouter un livre</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
<<<<<<< Updated upstream
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
=======
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
                                            <span class="badge badge-{{ $book->status == 'available' ? 'success' : ($book->status == 'borrowed' ? 'warning' : 'info') }}">
                                                {{ $book->status == 'available' ? 'Disponible' : ($book->status == 'borrowed' ? 'Emprunté' : 'Réservé') }}
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
>>>>>>> Stashed changes
                </div>
            </div>
        </div>

        <!-- Recommandations -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recommandations</h6>
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
                    <h6 class="m-0 font-weight-bold text-primary">Activité Récente</h6>
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
@endsection
