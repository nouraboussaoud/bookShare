@extends('layouts.layout')
@section('title', 'Nouvelle Catégorie')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Nouvelle Catégorie</h1>
            <p class="mb-0 text-gray-600">Créer une nouvelle catégorie de livres</p>
        </div>
        <a href="{{ route('categories.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations de la Catégorie</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">Nom de la catégorie <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="age_allowed">Âge minimum requis <span class="text-danger">*</span></label>
                            <select class="form-control @error('age_allowed') is-invalid @enderror" 
                                    id="age_allowed" name="age_allowed" required>
                                <option value="0" {{ old('age_allowed') == '0' ? 'selected' : '' }}>Tout âge</option>
                                <option value="6" {{ old('age_allowed') == '6' ? 'selected' : '' }}>6+</option>
                                <option value="9" {{ old('age_allowed') == '9' ? 'selected' : '' }}>9+</option>
                                <option value="12" {{ old('age_allowed') == '12' ? 'selected' : '' }}>12+</option>
                                <option value="13" {{ old('age_allowed') == '13' ? 'selected' : '' }}>13+</option>
                                <option value="16" {{ old('age_allowed') == '16' ? 'selected' : '' }}>16+</option>
                                <option value="18" {{ old('age_allowed') == '18' ? 'selected' : '' }}>18+</option>
                            </select>
                            @error('age_allowed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="color">Couleur de la catégorie <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="color" class="form-control @error('color') is-invalid @enderror" 
                                       id="color" name="color" value="{{ old('color', '#3B82F6') }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">{{ old('color', '#3B82F6') }}</span>
                                </div>
                            </div>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Cette couleur sera utilisée pour identifier visuellement la catégorie.</small>
                        </div>

                        <div class="form-group">
                            <label for="icon">Icône <span class="text-danger">*</span></label>
                            <select class="form-control @error('icon') is-invalid @enderror" 
                                    id="icon" name="icon" required>
                                <option value="fas fa-book" {{ old('icon') == 'fas fa-book' ? 'selected' : '' }}>📚 Livre</option>
                                <option value="fas fa-heart" {{ old('icon') == 'fas fa-heart' ? 'selected' : '' }}>❤️ Romance</option>
                                <option value="fas fa-rocket" {{ old('icon') == 'fas fa-rocket' ? 'selected' : '' }}>🚀 Science-Fiction</option>
                                <option value="fas fa-search" {{ old('icon') == 'fas fa-search' ? 'selected' : '' }}>🔍 Mystère</option>
                                <option value="fas fa-ghost" {{ old('icon') == 'fas fa-ghost' ? 'selected' : '' }}>👻 Horreur</option>
                                <option value="fas fa-child" {{ old('icon') == 'fas fa-child' ? 'selected' : '' }}>👶 Enfants</option>
                                <option value="fas fa-graduation-cap" {{ old('icon') == 'fas fa-graduation-cap' ? 'selected' : '' }}>🎓 Éducation</option>
                                <option value="fas fa-history" {{ old('icon') == 'fas fa-history' ? 'selected' : '' }}>📜 Histoire</option>
                                <option value="fas fa-user" {{ old('icon') == 'fas fa-user' ? 'selected' : '' }}>👤 Biographie</option>
                                <option value="fas fa-star" {{ old('icon') == 'fas fa-star' ? 'selected' : '' }}>⭐ Fiction</option>
                            </select>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sort_order">Ordre d'affichage</label>
                            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                   id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Plus le nombre est petit, plus la catégorie apparaîtra en premier.</small>
                        </div>

                        <div class="form-group">
                            <label for="reading_tips">Conseils de lecture</label>
                            <textarea class="form-control @error('reading_tips') is-invalid @enderror" 
                                      id="reading_tips" name="reading_tips" rows="3" 
                                      placeholder="Donnez des conseils aux lecteurs pour cette catégorie...">{{ old('reading_tips') }}</textarea>
                            @error('reading_tips')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="popular_authors">Auteurs populaires</label>
                            <input type="text" class="form-control @error('popular_authors') is-invalid @enderror" 
                                   id="popular_authors" name="popular_authors" value="{{ old('popular_authors') }}"
                                   placeholder="Ex: J.K. Rowling, Stephen King, Agatha Christie">
                            @error('popular_authors')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Séparez les noms par des virgules.</small>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" 
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Catégorie mise en avant
                                </label>
                            </div>
                            <small class="form-text text-muted">Les catégories mises en avant apparaissent sur la page d'accueil.</small>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Catégorie active
                                </label>
                            </div>
                            <small class="form-text text-muted">Seules les catégories actives sont visibles aux utilisateurs.</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Créer la Catégorie
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aperçu</h6>
                </div>
                <div class="card-body">
                    <div id="category-preview" class="text-center">
                        <div class="badge badge-lg p-3 mb-2" style="background-color: #3B82F6; color: white; font-size: 1rem;">
                            <span id="preview-name">Nom de la catégorie</span>
                        </div>
                        <p id="preview-description" class="text-muted">Description de la catégorie</p>
                        <small id="preview-age" class="text-info">Âge: Tout âge</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Live preview
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const descriptionInput = document.getElementById('description');
            const ageInput = document.getElementById('age_allowed');
            const colorInput = document.getElementById('color');
            
            const previewName = document.getElementById('preview-name');
            const previewDescription = document.getElementById('preview-description');
            const previewAge = document.getElementById('preview-age');
            const previewBadge = document.querySelector('#category-preview .badge');
            
            function updatePreview() {
                previewName.textContent = nameInput.value || 'Nom de la catégorie';
                previewDescription.textContent = descriptionInput.value || 'Description de la catégorie';
                
                const ageValue = ageInput.value;
                previewAge.textContent = ageValue == '0' ? 'Âge: Tout âge' : `Âge: ${ageValue}+`;
                
                previewBadge.style.backgroundColor = colorInput.value;
                document.querySelector('.input-group-text').textContent = colorInput.value;
            }
            
            nameInput.addEventListener('input', updatePreview);
            descriptionInput.addEventListener('input', updatePreview);
            ageInput.addEventListener('change', updatePreview);
            colorInput.addEventListener('input', updatePreview);
        });
    </script>
@endsection
