@extends('layouts.layout')

@section('title', 'Modifier la Catégorie - Admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📚 Modifier la Catégorie: {{ $category->name }}</h1>
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
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Nom de la catégorie <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Description de la catégorie...">{{ old('description', $category->description) }}</textarea>
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
                                    <option value="0" {{ old('age_allowed', $category->age_allowed) == '0' ? 'selected' : '' }}>Tout âge</option>
                                    <option value="3" {{ old('age_allowed', $category->age_allowed) == '3' ? 'selected' : '' }}>3+</option>
                                    <option value="6" {{ old('age_allowed', $category->age_allowed) == '6' ? 'selected' : '' }}>6+</option>
                                    <option value="9" {{ old('age_allowed', $category->age_allowed) == '9' ? 'selected' : '' }}>9+</option>
                                    <option value="12" {{ old('age_allowed', $category->age_allowed) == '12' ? 'selected' : '' }}>12+</option>
                                    <option value="15" {{ old('age_allowed', $category->age_allowed) == '15' ? 'selected' : '' }}>15+</option>
                                    <option value="18" {{ old('age_allowed', $category->age_allowed) == '18' ? 'selected' : '' }}>18+</option>
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
                                       value="{{ old('sort_order', $category->sort_order) }}" min="0">
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
                                       value="{{ old('color', $category->color) }}" required>
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
                                       value="{{ old('icon', $category->icon) }}" 
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
                                  placeholder="Conseils pour les lecteurs de cette catégorie...">{{ old('reading_tips', $category->reading_tips) }}</textarea>
                        @error('reading_tips')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="popular_authors">Auteurs populaires</label>
                        <div id="authors-container">
                            @php
                                $authors = old('popular_authors', $category->popular_authors ?? []);
                                if (empty($authors)) $authors = [''];
                            @endphp
                            @foreach($authors as $index => $author)
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
                        </div>
                        <button type="button" id="add-author" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus"></i> Ajouter un auteur
                        </button>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_featured" id="is_featured" 
                                   class="form-check-input" value="1" 
                                   {{ old('is_featured', $category->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Mettre en avant cette catégorie
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" 
                                   class="form-check-input" value="1" 
                                   {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Catégorie active
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Mettre à jour la Catégorie
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
        <!-- Category Preview -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-eye"></i> Aperçu
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    @if($category->icon)
                        <i class="{{ $category->icon }} text-primary mr-2"></i>
                    @endif
                    <strong>{{ $category->name }}</strong>
                    @if($category->is_featured)
                        <i class="fas fa-star text-warning ml-2" title="Mise en avant"></i>
                    @endif
                </div>
                <p class="text-muted small">{{ $category->description }}</p>
                <div class="mb-2">
                    <span class="badge badge-info">{{ $category->age_allowed }}+</span>
                    <span class="badge badge-{{ $category->is_active ? 'success' : 'secondary' }}">
                        {{ $category->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
                <div class="d-flex align-items-center">
                    <div style="width: 20px; height: 20px; background-color: {{ $category->color }}; border-radius: 3px; margin-right: 8px;"></div>
                    <small>{{ $category->color }}</small>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-chart-bar"></i> Statistiques
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Livres dans cette catégorie:</strong>
                    <span class="badge badge-primary">{{ $category->books()->count() }}</span>
                </div>
                <div class="mb-3">
                    <strong>Créée le:</strong><br>
                    <small class="text-muted">{{ $category->created_at->format('d/m/Y à H:i') }}</small>
                </div>
                <div>
                    <strong>Dernière modification:</strong><br>
                    <small class="text-muted">{{ $category->updated_at->format('d/m/Y à H:i') }}</small>
                </div>
            </div>
        </div>

        <!-- Help -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-exclamation-triangle"></i> Attention
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted small">
                    <strong>Suppression:</strong> Cette catégorie contient {{ $category->books()->count() }} livre(s). 
                    @if($category->books()->count() > 0)
                        Vous ne pourrez pas la supprimer tant qu'elle contient des livres.
                    @else
                        Elle peut être supprimée car elle ne contient aucun livre.
                    @endif
                </p>
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
