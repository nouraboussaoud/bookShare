@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-store fa-sm text-success"></i>
            {{ $existingOffer ? 'Modifier' : 'Créer' }} l'offre de location
        </h1>
        <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour au livre
        </a>
    </div>

    <!-- Bannière explicative -->
    <div class="alert alert-info border-left-info shadow-sm mb-4">
        <div class="row align-items-center">
            <div class="col-md-1 text-center">
                <i class="fas fa-info-circle fa-3x text-info"></i>
            </div>
            <div class="col-md-11">
                <h5 class="font-weight-bold text-info mb-2">
                    <i class="fas fa-lightbulb"></i> Comment ça marche ?
                </h5>
                <p class="mb-2">
                    <strong>Définissez votre offre de location</strong> en indiquant le prix, le lieu de rencontre et les conditions.
                </p>
                <p class="mb-0">
                    ✅ Votre livre apparaîtra sur le marketplace<br>
                    ✅ Les locataires pourront le louer en 1 clic<br>
                    ✅ Vous recevrez une notification et pourrez accepter ou refuser<br>
                    ✅ Le paiement se fera après votre acceptation
                </p>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-file-contract"></i> Détails de votre offre
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('rental-offers.store', $book) }}">
                        @csrf
                        
                        <div class="row">
                            <!-- Prix par jour -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prix_par_jour" class="form-label">
                                        <i class="fas fa-euro-sign text-success"></i> Prix par jour (€) *
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('prix_par_jour') is-invalid @enderror" 
                                           id="prix_par_jour" 
                                           name="prix_par_jour" 
                                           value="{{ old('prix_par_jour', $existingOffer->prix_par_jour ?? '') }}" 
                                           min="0" 
                                           step="0.01" 
                                           placeholder="Ex: 2.50"
                                           required>
                                    @error('prix_par_jour')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Le prix que vous souhaitez par jour de location</small>
                                </div>
                            </div>
                            
                            <!-- Localisation -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="localisation" class="form-label">
                                        <i class="fas fa-map-marker-alt text-danger"></i> Lieu de rencontre *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('localisation') is-invalid @enderror" 
                                           id="localisation" 
                                           name="localisation" 
                                           value="{{ old('localisation', $existingOffer->localisation ?? '') }}" 
                                           placeholder="Ex: Bibliothèque centrale, Café..."
                                           required>
                                    @error('localisation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Où se fera la remise du livre</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Durée minimum -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duree_min_jours" class="form-label">
                                        <i class="fas fa-calendar-check text-primary"></i> Durée minimum (jours) *
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('duree_min_jours') is-invalid @enderror" 
                                           id="duree_min_jours" 
                                           name="duree_min_jours" 
                                           value="{{ old('duree_min_jours', $existingOffer->duree_min_jours ?? 1) }}" 
                                           min="1" 
                                           max="365"
                                           required>
                                    @error('duree_min_jours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Minimum de jours pour une location</small>
                                </div>
                            </div>
                            
                            <!-- Durée maximum -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duree_max_jours" class="form-label">
                                        <i class="fas fa-calendar-times text-warning"></i> Durée maximum (jours) *
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('duree_max_jours') is-invalid @enderror" 
                                           id="duree_max_jours" 
                                           name="duree_max_jours" 
                                           value="{{ old('duree_max_jours', $existingOffer->duree_max_jours ?? 30) }}" 
                                           min="1" 
                                           max="365"
                                           required>
                                    @error('duree_max_jours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Maximum de jours pour une location</small>
                                </div>
                            </div>
                        </div>

                        <!-- Conditions -->
                        <div class="form-group">
                            <label for="conditions" class="form-label">
                                <i class="fas fa-list-ul text-info"></i> Conditions et règles (optionnel)
                            </label>
                            <textarea class="form-control @error('conditions') is-invalid @enderror" 
                                      id="conditions" 
                                      name="conditions" 
                                      rows="4" 
                                      placeholder="Ex: Livre à manipuler avec soin, ne pas corner les pages, protéger de l'humidité...">{{ old('conditions', $existingOffer->conditions ?? '') }}</textarea>
                            @error('conditions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Vos conditions particulières pour la location</small>
                        </div>

                        <!-- Simulation du prix -->
                        <div class="alert alert-success border-left-success" id="price-simulation">
                            <h6 class="font-weight-bold text-success">
                                <i class="fas fa-calculator"></i> Simulation de prix
                            </h6>
                            <p class="mb-1">
                                <strong>Pour <span id="sim-days">1</span> jour(s) :</strong> 
                                <span id="sim-price" class="h5 text-success">0.00</span>€
                            </p>
                            <p class="mb-0 small text-muted">
                                Ce montant sera proposé par défaut aux locataires
                            </p>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check-circle"></i> {{ $existingOffer ? 'Mettre à jour' : 'Créer' }} l'offre
                            </button>
                            <a href="{{ route('books.show', $book) }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Aperçu du livre -->
        <div class="col-lg-4">
            <div class="card shadow mb-4 border-left-success">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-book"></i> Votre livre
                    </h6>
                </div>
                <div class="card-body text-center">
                    @if($book->hasPhoto())
                        <img src="{{ $book->photo_url }}" alt="{{ $book->title }}" class="img-fluid rounded shadow mb-3" style="max-height: 250px;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 250px;">
                            <i class="fas fa-book fa-4x text-gray-400"></i>
                        </div>
                    @endif
                    
                    <h5 class="mb-2">{{ $book->title }}</h5>
                    <p class="text-muted mb-2">par {{ $book->author }}</p>
                    
                    @if($book->category)
                        <span class="badge badge-primary mb-2">{{ $book->category->name }}</span>
                    @endif
                    
                    <span class="badge badge-{{ $book->status == 'available' ? 'success' : 'warning' }}">
                        {{ $book->status == 'available' ? 'Disponible' : 'Réservé' }}
                    </span>
                </div>
            </div>

            @if($existingOffer)
                <div class="card shadow mb-4 border-left-warning">
                    <div class="card-header py-3 bg-warning text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-cog"></i> Gestion de l'offre
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($existingOffer->is_active)
                            <form action="{{ route('rental-offers.deactivate', $existingOffer) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning btn-block" onclick="return confirm('Désactiver cette offre ?')">
                                    <i class="fas fa-pause"></i> Désactiver l'offre
                                </button>
                            </form>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-check-circle text-success"></i> Offre actuellement active
                            </small>
                        @else
                            <form action="{{ route('rental-offers.activate', $existingOffer) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-play"></i> Réactiver l'offre
                                </button>
                            </form>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-pause-circle text-warning"></i> Offre désactivée
                            </small>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Calculer le prix en temps réel
document.addEventListener('DOMContentLoaded', function() {
    const prixInput = document.getElementById('prix_par_jour');
    const dureeMinInput = document.getElementById('duree_min_jours');
    const simPrice = document.getElementById('sim-price');
    const simDays = document.getElementById('sim-days');
    
    function updateSimulation() {
        const prix = parseFloat(prixInput.value) || 0;
        const jours = parseInt(dureeMinInput.value) || 1;
        const total = (prix * jours).toFixed(2);
        
        simPrice.textContent = total;
        simDays.textContent = jours;
    }
    
    prixInput.addEventListener('input', updateSimulation);
    dureeMinInput.addEventListener('input', updateSimulation);
    
    // Calculer au chargement
    updateSimulation();
});
</script>

<style>
.border-left-info {
    border-left: 4px solid #36b9cc;
}

.border-left-success {
    border-left: 4px solid #1cc88a;
}

.border-left-warning {
    border-left: 4px solid #f6c23e;
}

.card {
    border-radius: 12px;
}

.btn-lg {
    padding: 0.75rem 2rem;
}
</style>
@endsection
