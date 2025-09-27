@extends('layouts.layout')

@section('title', 'Créer une Catégorie - Admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📚 Créer une Nouvelle Catégorie</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informations de la Catégorie</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">Nom de la catégorie <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Description de la catégorie...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="age_allowed">Âge minimum <span class="text-danger">*</span></label>
                                <select name="age_allowed" id="age_allowed" 
                                        class="form-control @error('age_allowed') is-invalid @enderror" required>
                                    <option value="0" {{ old('age_allowed') == '0' ? 'selected' : '' }}>Tout âge</option>
                                    <option value="3" {{ old('age_allowed') == '3' ? 'selected' : '' }}>3+</option>
                                    <option value="6" {{ old('age_allowed') == '6' ? 'selected' : '' }}>6+</option>
                                    <option value="9" {{ old('age_allowed') == '9' ? 'selected' : '' }}>9+</option>
                                    <option value="12" {{ old('age_allowed') == '12' ? 'selected' : '' }}>12+</option>
                                    <option value="15" {{ old('age_allowed') == '15' ? 'selected' : '' }}>15+</option>
                                    <option value="18" {{ old('age_allowed') == '18' ? 'selected' : '' }}>18+</option>
                                </select>
                                @error('age_allowed')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sort_order">Ordre d'affichage</label>
                                <input type="number" name="sort_order" id="sort_order" 
                                       class="form-control @error('sort_order') is-invalid @enderror" 
                                       value="{{ old('sort_order', 999) }}" min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Plus le nombre est petit, plus la catégorie apparaîtra en premier</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color">Couleur <span class="text-danger">*</span></label>
                                <input type="color" name="color" id="color" 
                                       class="form-control @error('color') is-invalid @enderror" 
                                       value="{{ old('color', '#007bff') }}" required>
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="icon">Icône (FontAwesome)</label>
                                <input type="text" name="icon" id="icon" 
                                       class="form-control @error('icon') is-invalid @enderror" 
                                       value="{{ old('icon') }}" 
                                       placeholder="fas fa-book">
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Ex: fas fa-book, fas fa-dragon, fas fa-heart</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reading_tips">Conseils de lecture</label>
                        <textarea name="reading_tips" id="reading_tips" rows="3" 
                                  class="form-control @error('reading_tips') is-invalid @enderror"
                                  placeholder="Conseils pour les lecteurs de cette catégorie...">{{ old('reading_tips') }}</textarea>
                        @error('reading_tips')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="popular_authors">Auteurs populaires</label>
                        <div id="authors-container">
                            @if(old('popular_authors'))
                                @foreach(old('popular_authors') as $index => $author)
                                    <div class="input-group mb-2 author-input">
                                        <input type="text" name="popular_authors[]" 
                                               class="form-control" 
                                               value="{{ $author }}" 
                                               placeholder="Nom de l'auteur">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-danger remove-author">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2 author-input">
                                    <input type="text" name="popular_authors[]" 
                                           class="form-control" 
                                           placeholder="Nom de l'auteur">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-danger remove-author">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" id="add-author" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus"></i> Ajouter un auteur
                        </button>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_featured" id="is_featured" 
                                   class="form-check-input" value="1" 
                                   {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Mettre en avant cette catégorie
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" 
                                   class="form-check-input" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Catégorie active
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Créer la Catégorie
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
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
                    <i class="fas fa-info-circle"></i> Aide
                </h6>
            </div>
            <div class="card-body">
                <h6>Conseils pour créer une catégorie</h6>
                <ul class="text-muted small">
                    <li><strong>Nom :</strong> Choisissez un nom clair et descriptif</li>
                    <li><strong>Description :</strong> Expliquez le type de livres dans cette catégorie</li>
                    <li><strong>Âge :</strong> Définissez l'âge minimum recommandé</li>
                    <li><strong>Couleur :</strong> Choisissez une couleur distinctive</li>
                    <li><strong>Icône :</strong> Utilisez les icônes FontAwesome</li>
                </ul>
                
                <hr>
                
                <h6>Exemples d'icônes</h6>
                <div class="text-muted small">
                    <div><code>fas fa-book</code> - Livre général</div>
                    <div><code>fas fa-dragon</code> - Fantasy</div>
                    <div><code>fas fa-heart</code> - Romance</div>
                    <div><code>fas fa-rocket</code> - Science-Fiction</div>
                    <div><code>fas fa-ghost</code> - Horreur</div>
                    <div><code>fas fa-child</code> - Jeunesse</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add author functionality
    document.getElementById('add-author').addEventListener('click', function() {
        const container = document.getElementById('authors-container');
        const newAuthor = document.createElement('div');
        newAuthor.className = 'input-group mb-2 author-input';
        newAuthor.innerHTML = `
            <input type="text" name="popular_authors[]" 
                   class="form-control" 
                   placeholder="Nom de l'auteur">
            <div class="input-group-append">
                <button type="button" class="btn btn-outline-danger remove-author">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.appendChild(newAuthor);
    });

    // Remove author functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-author')) {
            const authorInput = e.target.closest('.author-input');
            const container = document.getElementById('authors-container');
            if (container.children.length > 1) {
                authorInput.remove();
            }
        }
    });
});
</script>
@endsection
