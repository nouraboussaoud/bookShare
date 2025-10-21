@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-store fa-sm text-success"></i>
            Marketplace des Locations
        </h1>
        <div>
            <a href="{{ route('locations.help') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm mr-2">
                <i class="fas fa-question-circle fa-sm text-white-50"></i> Guide d'aide
            </a>
            <a href="{{ route('locations.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-calendar-check fa-sm text-white-50"></i> Mes Locations
            </a>
        </div>
    </div>

    <!-- Processus de location expliqué -->
    <div class="alert alert-info border-left-info shadow-sm mb-4">
        <div class="row align-items-center">
            <div class="col-md-1 text-center">
                <i class="fas fa-info-circle fa-3x text-info"></i>
            </div>
            <div class="col-md-11">
                <h5 class="font-weight-bold mb-2">
                    <i class="fas fa-hand-point-right"></i> Comment ça marche ?
                </h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="process-step">
                            <span class="badge badge-primary badge-pill mr-2">1</span>
                            <strong>Choisissez</strong><br>
                            <small class="text-muted">Sélectionnez un livre disponible</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="process-step">
                            <span class="badge badge-warning badge-pill mr-2">2</span>
                            <strong>Demandez</strong><br>
                            <small class="text-muted">Faites votre demande de location</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="process-step">
                            <span class="badge badge-success badge-pill mr-2">3</span>
                            <strong>Attendez</strong><br>
                            <small class="text-muted">Le propriétaire confirme</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="process-step">
                            <span class="badge badge-info badge-pill mr-2">4</span>
                            <strong>Payez</strong><br>
                            <small class="text-muted">Réglez via Stripe après confirmation</small>
                        </div>
                    </div>
                </div>
                <p class="mb-0 mt-2">
                    <i class="fas fa-shield-alt text-success"></i> 
                    <strong>Sécurisé :</strong> Vous ne payez qu'après acceptation du propriétaire !
                </p>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Livres Disponibles</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $livresDisponibles->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Réservations Actives</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $locationsRecentes->where('statut', 'en_cours')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
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
                                Prix Moyen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if($locationsRecentes->count() > 0)
                                    {{ number_format($locationsRecentes->avg('prix'), 2) }}€
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Propriétaires Actifs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $livresDisponibles->pluck('user_id')->unique()->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i>
                Filtres et Recherche
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('locations.marketplace') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="search">Rechercher un livre</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Titre, auteur...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="category">Catégorie</label>
                            <select class="form-control" id="category" name="category">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="price_max">Prix maximum (€)</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="price_max" 
                                   name="price_max" 
                                   value="{{ request('price_max') }}"
                                   placeholder="Ex: 10"
                                   step="0.01">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des livres disponibles -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-book-open"></i>
                Livres Disponibles à la Réservation ({{ $livresDisponibles->total() }})
            </h6>
        </div>
        <div class="card-body">
            @if($livresDisponibles->count() > 0)
                <div class="row">
                    @foreach($livresDisponibles as $livre)
                        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                            <div class="card border-left-success h-100">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <!-- Image du livre -->
                                        <div class="col-4">
                                            @if($livre->hasPhoto())
                                                <img src="{{ $livre->photo_url }}" 
                                                     alt="{{ $livre->title }}" 
                                                     class="img-fluid rounded shadow-sm"
                                                     style="max-height: 120px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="height: 120px;">
                                                    <i class="fas fa-book fa-2x text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Informations du livre -->
                                        <div class="col-8">
                                            <h6 class="card-title mb-2">
                                                <a href="{{ route('books.show', $livre) }}" 
                                                   class="text-decoration-none text-dark">
                                                    {{ Str::limit($livre->title, 30) }}
                                                </a>
                                            </h6>
                                            
                                            <p class="card-text text-muted mb-1">
                                                <small><strong>Auteur:</strong> {{ Str::limit($livre->author, 25) }}</small>
                                            </p>
                                            
                                            <p class="card-text text-muted mb-1">
                                                <small>
                                                    <i class="fas fa-user text-primary"></i>
                                                    <strong>Propriétaire:</strong> {{ $livre->user->name }}
                                                </small>
                                            </p>
                                            
                                            @if($livre->category)
                                                <span class="badge badge-primary mb-2">
                                                    <i class="{{ $livre->category->icon }}"></i>
                                                    {{ $livre->category->name }}
                                                </span>
                                            @endif
                                            
                                            <!-- Prix de l'offre -->
                                            @if($livre->rentalOffer)
                                                <div class="mb-2 mt-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <small class="text-muted"><i class="fas fa-euro-sign"></i> Prix/jour:</small><br>
                                                            <span class="h5 text-success font-weight-bold mb-0">
                                                                {{ number_format($livre->rentalOffer->prix_par_jour, 2) }}€
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <small class="text-muted"><i class="fas fa-map-marker-alt"></i> Lieu:</small><br>
                                                            <small class="font-weight-bold">{{ Str::limit($livre->rentalOffer->localisation, 15) }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                @if(Auth::id() != $livre->user_id)
                                                    <form action="{{ route('rental-offers.rent-now', $livre->rentalOffer) }}" method="POST" class="mt-2">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="btn btn-success btn-block shadow-sm"
                                                                onclick="return confirm('Voulez-vous louer ce livre pour {{ $livre->rentalOffer->duree_min_jours }} jour(s) à {{ number_format($livre->rentalOffer->calculatePrice($livre->rentalOffer->duree_min_jours), 2) }}€ ?')">
                                                            <i class="fas fa-bolt"></i> Louer en 1 Clic
                                                        </button>
                                                    </form>
                                                    <small class="text-muted d-block text-center mt-1">
                                                        <i class="fas fa-info-circle"></i> {{ $livre->rentalOffer->duree_min_jours }}-{{ $livre->rentalOffer->duree_max_jours }} jours
                                                    </small>
                                                @else
                                                    <span class="badge badge-secondary badge-pill">Votre livre</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Informations supplémentaires -->
                                    <div class="mt-3 pt-2 border-top">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <small class="text-muted">Âge</small><br>
                                                <span class="badge badge-info">{{ $livre->age_display }}</span>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted">Statut</small><br>
                                                <span class="badge badge-success">Disponible</span>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted">Ajouté</small><br>
                                                <small class="text-muted">{{ $livre->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $livresDisponibles->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-4x text-gray-300 mb-4"></i>
                    <h4 class="text-gray-500">Aucun livre disponible</h4>
                    <p class="text-gray-400">Il n'y a actuellement aucun livre disponible à la réservation.</p>
                    <a href="{{ route('books.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter un livre
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Locations récentes (exemples) -->
    @if($locationsRecentes->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-history"></i>
                    Réservations Récentes (Exemples de prix)
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($locationsRecentes->take(6) as $location)
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="card border-left-info">
                                <div class="card-body py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ Str::limit($location->book->title, 25) }}</h6>
                                            <small class="text-muted">
                                                Par {{ $location->proprietaire->name }}
                                            </small>
                                        </div>
                                        <div class="text-right">
                                            <div class="h6 text-success mb-0">{{ number_format($location->prix, 2) }}€</div>
                                            <small class="text-muted">{{ $location->duree_jours }} jours</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<style>
/* Bannière processus */
.border-left-info {
    border-left: 4px solid #36b9cc;
}

.process-step {
    padding: 10px;
    text-align: left;
    transition: all 0.3s ease;
}

.process-step:hover {
    transform: translateY(-3px);
}

.process-step .badge-pill {
    font-size: 14px;
    padding: 8px 12px;
}

/* Cards livres améliorées */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
}

.btn-success {
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(28, 200, 138, 0.4);
}

/* Amélioration des badges */
.badge-pill {
    padding: 6px 12px;
    font-size: 13px;
}

/* Statistiques */
.border-left-success {
    border-left: 4px solid #1cc88a;
}

.border-left-primary {
    border-left: 4px solid #4e73df;
}

.border-left-warning {
    border-left: 4px solid #f6c23e;
}
</style>

<script>
    // Activer les tooltips Bootstrap
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
