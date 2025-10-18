@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit fa-sm text-warning"></i>
            Modifier la demande de location
        </h1>
        <a href="{{ route('locations.show', $location) }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour aux détails
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Modifier les informations de la location</h6>
                </div>
                <div class="card-body">
                    <!-- Informations du livre (non modifiables) -->
                    <div class="card mb-4 border-left-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    @if($location->book->hasPhoto())
                                        <img src="{{ $location->book->photo_url }}" alt="{{ $location->book->title }}" class="img-fluid rounded">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 150px;">
                                            <i class="fas fa-book fa-3x text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <h5 class="card-title">{{ $location->book->title }}</h5>
                                    <p class="card-text"><strong>Auteur:</strong> {{ $location->book->author }}</p>
                                    <p class="card-text"><strong>Propriétaire:</strong> {{ $location->proprietaire->name }}</p>
                                    @if($location->book->description)
                                        <p class="card-text">{{ Str::limit($location->book->description, 200) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('locations.update', $location) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Date de location -->
                                <div class="form-group">
                                    <label for="date_location" class="form-label">Date de début de location</label>
                                    <input type="date" 
                                           class="form-control @error('date_location') is-invalid @enderror" 
                                           id="date_location" 
                                           name="date_location" 
                                           value="{{ old('date_location', $location->date_location->format('Y-m-d')) }}"
                                           min="{{ date('Y-m-d') }}"
                                           required>
                                    @error('date_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Durée -->
                                <div class="form-group">
                                    <label for="duree_jours" class="form-label">Durée (en jours)</label>
                                    <input type="number" 
                                           class="form-control @error('duree_jours') is-invalid @enderror" 
                                           id="duree_jours" 
                                           name="duree_jours" 
                                           value="{{ old('duree_jours', $location->duree_jours) }}"
                                           min="1" 
                                           max="90"
                                           required>
                                    @error('duree_jours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Maximum 90 jours</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Localisation -->
                                <div class="form-group">
                                    <label for="localisation" class="form-label">Lieu de récupération/retour</label>
                                    <input type="text" 
                                           class="form-control @error('localisation') is-invalid @enderror" 
                                           id="localisation" 
                                           name="localisation" 
                                           value="{{ old('localisation', $location->localisation) }}"
                                           placeholder="Ex: Bibliothèque centrale, Café du coin..."
                                           required>
                                    @error('localisation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Prix -->
                                <div class="form-group">
                                    <label for="prix" class="form-label">Prix proposé (€)</label>
                                    <input type="number" 
                                           class="form-control @error('prix') is-invalid @enderror" 
                                           id="prix" 
                                           name="prix" 
                                           value="{{ old('prix', $location->prix) }}"
                                           min="0" 
                                           step="0.01"
                                           placeholder="0.00"
                                           required>
                                    @error('prix')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label for="notes" class="form-label">Notes (optionnel)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3"
                                      placeholder="Message pour le propriétaire, conditions particulières...">{{ old('notes', $location->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date de fin calculée -->
                        <div class="alert alert-info" id="date-fin-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Date de fin prévue:</strong> <span id="date-fin-text"></span>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Mettre à jour la demande
                            </button>
                            <a href="{{ route('locations.show', $location) }}" class="btn btn-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Attention
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i>
                        <strong>Modification en attente</strong><br>
                        Vous pouvez modifier votre demande tant qu'elle n'a pas été acceptée ou refusée par le propriétaire.
                    </div>
                    
                    <h6 class="font-weight-bold mb-3">Informations actuelles:</h6>
                    
                    <div class="mb-2">
                        <small class="text-muted">Date actuelle:</small><br>
                        <strong>{{ $location->date_location->format('d/m/Y') }}</strong>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted">Durée actuelle:</small><br>
                        <strong>{{ $location->duree_jours }} jours</strong>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted">Lieu actuel:</small><br>
                        <strong>{{ $location->localisation }}</strong>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted">Prix actuel:</small><br>
                        <strong>{{ number_format($location->prix, 2) }}€</strong>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted">Fin prévue actuelle:</small><br>
                        <strong>{{ $location->date_fin_prevue->format('d/m/Y') }}</strong>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-lightbulb"></i> Conseils
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Choisissez un lieu de rencontre pratique pour vous deux
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Proposez un prix équitable selon la durée
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Soyez précis dans vos notes
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success mr-2"></i>
                            Respectez les délais convenus
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date_location');
    const dureeInput = document.getElementById('duree_jours');
    const dateFinInfo = document.getElementById('date-fin-info');
    const dateFinText = document.getElementById('date-fin-text');
    
    function calculerDateFin() {
        const dateDebut = dateInput.value;
        const duree = parseInt(dureeInput.value);
        
        if (dateDebut && duree > 0) {
            const debut = new Date(dateDebut);
            const fin = new Date(debut);
            fin.setDate(debut.getDate() + duree);
            
            const options = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                weekday: 'long'
            };
            
            dateFinText.textContent = fin.toLocaleDateString('fr-FR', options);
            dateFinInfo.style.display = 'block';
        } else {
            dateFinInfo.style.display = 'none';
        }
    }
    
    dateInput.addEventListener('change', calculerDateFin);
    dureeInput.addEventListener('input', calculerDateFin);
    
    // Calculer au chargement
    calculerDateFin();
});
</script>
@endsection
