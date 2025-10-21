@extends('layouts.app')
@section('title', 'Mon Profil - BookShare')
@section('content')

<div class="container-fluid py-4">
    <!-- Profile Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <div class="avatar-circle mx-auto mb-3" style="width: 100px; height: 100px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user fa-3x text-white"></i>
                        </div>
                        <h2 class="h3 mb-1">{{ Auth::user()->name }}</h2>
                        <p class="mb-0 opacity-75">{{ Auth::user()->email }}</p>
                        <small class="opacity-50">Membre depuis {{ Auth::user()->created_at->format('M Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center border-primary h-100">
                <div class="card-body">
                    <i class="fas fa-book fa-2x text-primary mb-2"></i>
                    <h4 class="mb-1">{{ \App\Models\Book::where('user_id', Auth::id())->count() }}</h4>
                    <small class="text-muted">Mes Livres</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-success h-100">
                <div class="card-body">
                    <i class="fas fa-exchange-alt fa-2x text-success mb-2"></i>
                    <h4 class="mb-1">{{ \App\Models\Exchange::where('userInitiateurId', Auth::id())->orWhere('userRecepteurId', Auth::id())->count() }}</h4>
                    <small class="text-muted">Échanges</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-info h-100">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-info mb-2"></i>
                    <h4 class="mb-1">{{ \App\Models\GroupMembership::where('user_id', Auth::id())->count() }}</h4>
                    <small class="text-muted">Groupes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-warning h-100">
                <div class="card-body">
                    <i class="fas fa-star fa-2x text-warning mb-2"></i>
                    <h4 class="mb-1">{{ \App\Models\Review::where('user_id', Auth::id())->count() }}</h4>
                    <small class="text-muted">Avis</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-lg-8">
            @include('profile.partials.update-profile-information-form')

            <div class="mt-4">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card border-info mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Actions Rapides
                    </h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('books.create') }}" class="btn btn-outline-primary btn-sm mb-2 w-100">
                        <i class="fas fa-plus me-1"></i>Ajouter un livre
                    </a>
                    <a href="{{ route('books.index') }}" class="btn btn-outline-primary btn-sm mb-2 w-100">
                        <i class="fas fa-book me-1"></i>Mes Livres
                    </a>
                    <a href="{{ route('reading-progress.index') }}" class="btn btn-outline-success btn-sm mb-2 w-100">
                        <i class="fas fa-bookmark me-1"></i>Ma Lecture
                    </a>
                    <a href="{{ route('reading-groups.index') }}" class="btn btn-outline-info btn-sm mb-2 w-100">
                        <i class="fas fa-users me-1"></i>Mes Groupes
                    </a>
                    <a href="{{ route('exchanges.index') }}" class="btn btn-outline-warning btn-sm mb-2 w-100">
                        <i class="fas fa-exchange-alt me-1"></i>Mes Échanges
                    </a>
                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="fas fa-home me-1"></i>Tableau de Bord
                    </a>
                </div>
            </div>

            <!-- Account Settings -->
            <div class="card border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-cog me-2"></i>Paramètres du Compte
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Statut du compte</label>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2">
                                <i class="fas fa-check-circle me-1"></i>Actif
                            </span>
                            <small class="text-muted">Vérifié</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Dernière connexion</label>
                        <small class="text-muted d-block">
                            <i class="fas fa-clock me-1"></i>{{ Auth::user()->updated_at->diffForHumans() }}
                        </small>
                    </div>

                    <hr>

                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
