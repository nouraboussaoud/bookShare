@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus fa-sm text-primary"></i>
            Demander une location
        </h1>
        <a href="{{ route('locations.marketplace') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2">
            <i class="fas fa-store fa-sm text-white-50"></i> Retour au Marketplace
        </a>
        <a href="{{ route('locations.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Mes Locations
        </a>
    </div>

    <!-- Bannière Processus de paiement -->
    <div class="alert alert-success border-left-success shadow-sm mb-4">
        <div class="row align-items-center">
            <div class="col-md-1 text-center">
                <i class="fas fa-shield-alt fa-3x text-success"></i>
            </div>
            <div class="col-md-11">
                <h5 class="font-weight-bold text-success mb-2">
                    <i class="fas fa-check-circle"></i> Paiement sécurisé APRÈS confirmation
                </h5>
                <div class="row">
                    <div class="col-md-4">
                        <div class="process-box">
                            <span class="badge badge-warning badge-pill">Étape 1</span>
                            <p class="mb-0 mt-2"><strong>Vous faites la demande</strong><br>
                            <small class="text-muted">Sans engagement financier</small></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="process-box">
                            <span class="badge badge-info badge-pill">Étape 2</span>
                            <p class="mb-0 mt-2"><strong>Le propriétaire accepte</strong><br>
                            <small class="text-muted">Vous recevez une notification</small></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="process-box">
                            <span class="badge badge-success badge-pill">Étape 3</span>
                            <p class="mb-0 mt-2"><strong>Vous payez via Stripe</strong><br>
                            <small class="text-muted">Paiement 100% sécurisé</small></p>
                        </div>
                    </div>
                </div>
                <p class="mb-0 mt-3 text-success">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Important :</strong> Aucun paiement ne sera effectué avant l'acceptation du propriétaire !
                </p>
            </div>
        </div>
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
                    <h6 class="m-0 font-weight-bold text-primary">Informations de la location</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('locations.store') }}">
                        @csrf
                        
                        @if($book)
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            
                            <!-- Informations du livre -->
                            <div class="card mb-4 border-left-primary">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            @if($book->hasPhoto())
                                                <img src="{{ $book->photo_url }}" alt="{{ $book->title }}" class="img-fluid rounded">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 150px;">
                                                    <i class="fas fa-book fa-3x text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-9">
                                            <h5 class="card-title">{{ $book->title }}</h5>
                                            <p class="card-text"><strong>Auteur:</strong> {{ $book->author }}</p>
                                            <p class="card-text"><strong>Propriétaire:</strong> {{ $book->user->name }}</p>
                                            @if($book->description)
                                                <p class="card-text">{{ Str::limit($book->description, 200) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Sélection du livre -->
                            <div class="form-group">
                                <label for="book_id" class="form-label">Livre à louer</label>
                                <select class="form-control @error('book_id') is-invalid @enderror" id="book_id" name="book_id" required>
                                    <option value="">Sélectionnez un livre</option>
                                    @foreach($livresDisponibles as $livre)
                                        <option value="{{ $livre->id }}" {{ old('book_id') == $livre->id ? 'selected' : '' }}>
                                            {{ $livre->title }} - {{ $livre->author }} 
                                            (Propriétaire: {{ $livre->user->name }})
                                            @if($livre->category)
                                                [{{ $livre->category->name }}]
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('book_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($livresDisponibles->isEmpty())
                                    <small class="form-text text-muted text-warning">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        Aucun livre n'est actuellement disponible pour la location.
                                    </small>
                                @else
                                    <small class="form-text text-muted">
                                        {{ $livresDisponibles->count() }} livre(s) disponible(s)
                                    </small>
                                @endif
                            </div>

                            <!-- Aperçu du livre sélectionné -->
                            <div id="book-preview" class="card mb-4 border-left-primary" style="display: none;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div id="book-preview-image" class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 150px;">
                                                <i class="fas fa-book fa-3x text-gray-400"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <h5 class="card-title" id="book-preview-title"></h5>
                                            <p class="card-text"><strong>Auteur:</strong> <span id="book-preview-author"></span></p>
                                            <p class="card-text"><strong>Propriétaire:</strong> <span id="book-preview-owner"></span></p>
                                            <p class="card-text"><strong>Catégorie:</strong> <span id="book-preview-category"></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Date de location -->
                                <div class="form-group">
                                    <label for="date_location" class="form-label">Date de début de location</label>
                                    <input type="date" 
                                           class="form-control @error('date_location') is-invalid @enderror" 
                                           id="date_location" 
                                           name="date_location" 
                                           value="{{ old('date_location', date('Y-m-d')) }}"
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
                                           value="{{ old('duree_jours', 7) }}"
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
                                           value="{{ old('localisation') }}"
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
                                           value="{{ old('prix') }}"
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
                                      placeholder="Message pour le propriétaire, conditions particulières...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date de fin calculée -->
                        <div class="alert alert-info" id="date-fin-info" style="display: none;">
                            <i class="fas fa-info-circle"></i>
                            <strong>Date de fin prévue:</strong> <span id="date-fin-text"></span>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Envoyer la demande
                            </button>
                            <a href="{{ route('locations.index') }}" class="btn btn-secondary">
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
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle"></i> Comment ça marche ?
                    </h6>
                </div>
                <div class="card-body">
                    <div class="step mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 30px; height: 30px;">
                                <small>1</small>
                            </div>
                            <strong>Demande</strong>
                        </div>
                        <p class="text-muted mb-0">Remplissez le formulaire avec vos préférences de location.</p>
                    </div>
                    
                    <div class="step mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 30px; height: 30px;">
                                <small>2</small>
                            </div>
                            <strong>Confirmation</strong>
                        </div>
                        <p class="text-muted mb-0">Le propriétaire reçoit votre demande et peut l'accepter ou la refuser.</p>
                    </div>
                    
                    <div class="step mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 30px; height: 30px;">
                                <small>3</small>
                            </div>
                            <strong>Location</strong>
                        </div>
                        <p class="text-muted mb-0">Une fois acceptée, récupérez le livre au lieu convenu.</p>
                    </div>
                    
                    <div class="step">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 30px; height: 30px;">
                                <small>4</small>
                            </div>
                            <strong>Retour</strong>
                        </div>
                        <p class="text-muted mb-0">Retournez le livre à la date convenue.</p>
                    </div>
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
    
    // Calculer au chargement si les valeurs sont déjà remplies
    calculerDateFin();
    
    // Gérer l'aperçu du livre sélectionné
    const bookSelect = document.getElementById('book_id');
    const bookPreview = document.getElementById('book-preview');
    
    if (bookSelect && bookPreview) {
        // Créer un objet avec les données des livres
        const booksData = {
            @if(isset($livresDisponibles))
                @foreach($livresDisponibles as $livre)
                    '{{ $livre->id }}': {
                        title: '{{ addslashes($livre->title) }}',
                        author: '{{ addslashes($livre->author) }}',
                        owner: '{{ addslashes($livre->user->name) }}',
                        category: '{{ $livre->category ? addslashes($livre->category->name) : "Non catégorisé" }}'
                    },
                @endforeach
            @endif
        };
        
        bookSelect.addEventListener('change', function() {
            const bookId = this.value;
            
            if (bookId && booksData[bookId]) {
                const book = booksData[bookId];
                
                document.getElementById('book-preview-title').textContent = book.title;
                document.getElementById('book-preview-author').textContent = book.author;
                document.getElementById('book-preview-owner').textContent = book.owner;
                document.getElementById('book-preview-category').textContent = book.category;
                
                bookPreview.style.display = 'block';
            } else {
                bookPreview.style.display = 'none';
            }
        });
        
        // Afficher l'aperçu si un livre est déjà sélectionné (old value)
        if (bookSelect.value) {
            bookSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>

<style>
/* Bannière processus */
.border-left-success {
    border-left: 4px solid #1cc88a;
}

.process-box {
    padding: 15px;
    background: #f8f9fc;
    border-radius: 10px;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
}

.process-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.process-box .badge-pill {
    font-size: 13px;
    padding: 8px 15px;
    margin-bottom: 10px;
}

/* Amélioration des alertes */
.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-success h5 {
    color: #155724;
}

/* Boutons améliorés */
.btn-success {
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(28, 200, 138, 0.3);
}

.btn-secondary {
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(133, 135, 150, 0.3);
}

/* Cards */
.card {
    border-radius: 12px;
}

.shadow-sm {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08) !important;
}
</style>
@endsection
