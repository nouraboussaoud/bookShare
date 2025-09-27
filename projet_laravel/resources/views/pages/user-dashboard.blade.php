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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $myBooksCount }}</div>
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
                            <a class="dropdown-item" href="{{ route('books.index') }}">Voir tous</a>
                            <a class="dropdown-item" href="{{ route('books.create') }}">Ajouter un livre</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
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
                                @forelse($recentMyBooks as $b)
                                    <tr>
                                        <td>{{ $b->title }}</td>
                                        <td>{{ $b->author }}</td>
                                        <td>—</td>
                                        <td>
                                            <span class="badge {{ $b->status === 'AVAILABLE' ? 'badge-success' : 'badge-warning' }}">{{ $b->status }}</span>
                                        </td>
                                        <td>{{ $b->created_at->format('Y-m-d') }}</td>
                                        <td class="d-flex gap-2">
                                            <a href="{{ route('books.edit', $b) }}" class="btn btn-sm btn-secondary" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('books.destroy', $b) }}" method="POST" onsubmit="return confirm('Supprimer ce livre ?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Aucun livre récent.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
                    @forelse($recommendations as $r)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $r->title }}</strong> — <small>{{ $r->author }}</small>
                                <div class="text-muted" style="font-size: 12px;">par {{ $r->user?->name }}</div>
                            </div>
                            <span class="badge {{ $r->status === 'AVAILABLE' ? 'badge-success' : 'badge-warning' }}">{{ $r->status }}</span>
                        </div>
                    @empty
                        <p class="mb-0 text-muted">Pas encore de recommandations.</p>
                    @endforelse

                    <a href="{{ route('books.index', ['scope' => 'others']) }}" class="btn btn-primary btn-sm btn-block mt-3">
                        <i class="fas fa-search mr-2"></i>Explorer les livres
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
