@extends('layouts.app')
@section('title', 'Ajouter un livre')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Nouveau Livre</h6>
                <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-secondary">Retour</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Auteur</label>
                        <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author') }}" required>
                        @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catégorie</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">Sélectionner une catégorie (optionnel)</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} 
                                    @if($category->age_allowed > 0)
                                        ({{ $category->age_allowed }}+)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Âge recommandé <span class="text-danger">*</span></label>
                        <select name="recommended_age" class="form-select @error('recommended_age') is-invalid @enderror" required>
                            <option value="0" {{ old('recommended_age') == '0' ? 'selected' : '' }}>Tout âge</option>
                            <option value="6" {{ old('recommended_age') == '6' ? 'selected' : '' }}>6+</option>
                            <option value="9" {{ old('recommended_age') == '9' ? 'selected' : '' }}>9+</option>
                            <option value="12" {{ old('recommended_age') == '12' ? 'selected' : '' }}>12+</option>
                            <option value="13" {{ old('recommended_age') == '13' ? 'selected' : '' }}>13+</option>
                            <option value="15" {{ old('recommended_age') == '15' ? 'selected' : '' }}>15+</option>
                            <option value="16" {{ old('recommended_age') == '16' ? 'selected' : '' }}>16+</option>
                            <option value="18" {{ old('recommended_age') == '18' ? 'selected' : '' }}>18+</option>
                        </select>
                        @error('recommended_age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Photo de couverture</label>
                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="form-text text-muted">Formats acceptés: JPEG, PNG, JPG, GIF, WebP. Taille max: 2MB</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Décrivez le livre, son intrigue, ce qui le rend spécial...">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="form-text text-muted">Maximum 1000 caractères</small>
                    </div>

                    <!-- Tags Section -->
                    <div class="mb-3" id="tags-section">
                        <label class="form-label">
                            <i class="fas fa-tags"></i> Tags 
                            <small class="text-muted">(Sélectionnez les tags qui correspondent à ce livre)</small>
                        </label>
                        <div id="tags-container" class="border rounded p-3 bg-light">
                            <p class="text-muted text-center" id="no-category-message">
                                <i class="fas fa-info-circle"></i> Sélectionnez d'abord une catégorie pour voir les tags disponibles
                            </p>
                            <div id="tags-list" class="d-none">
                                <!-- Tags will be loaded here dynamically -->
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                            <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Réservé</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Tags data from categories
const categoriesData = {!! json_encode($categories->map(function($cat) {
    return [
        'id' => $cat->id,
        'name' => $cat->name,
        'tags' => $cat->categoryTags->map(function($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
                'color' => $tag->color,
                'icon' => $tag->icon,
                'type' => $tag->type,
                'description' => $tag->description
            ];
        })
    ];
})) !!};

// Handle category change
document.querySelector('select[name="category_id"]').addEventListener('change', function() {
    const categoryId = parseInt(this.value);
    const tagsListDiv = document.getElementById('tags-list');
    const noMessageDiv = document.getElementById('no-category-message');
    
    if (!categoryId) {
        tagsListDiv.classList.add('d-none');
        noMessageDiv.classList.remove('d-none');
        return;
    }
    
    const category = categoriesData.find(c => c.id === categoryId);
    
    if (!category || category.tags.length === 0) {
        tagsListDiv.innerHTML = '<p class="text-muted text-center"><i class="fas fa-info-circle"></i> Aucun tag disponible pour cette catégorie</p>';
        tagsListDiv.classList.remove('d-none');
        noMessageDiv.classList.add('d-none');
        return;
    }
    
    // Build tags HTML
    let html = '<div class="row">';
    category.tags.forEach(tag => {
        const typeLabel = {
            'genre': '📖 Genre',
            'theme': '🎭 Thème',
            'mood': '😊 Ambiance',
            'pace': '⚡ Rythme',
            'other': '🏷️ Autre'
        }[tag.type] || '🏷️';
        
        html += `
            <div class="col-md-6 mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="tags[]" value="${tag.id}" id="tag-${tag.id}">
                    <label class="form-check-label" for="tag-${tag.id}">
                        <span class="badge" style="background-color: ${tag.color}; color: white;">
                            ${tag.icon ? '<i class="' + tag.icon + '"></i>' : ''} ${tag.name}
                        </span>
                        <small class="text-muted d-block">${typeLabel} ${tag.description ? '- ' + tag.description : ''}</small>
                    </label>
                </div>
            </div>
        `;
    });
    html += '</div>';
    
    tagsListDiv.innerHTML = html;
    tagsListDiv.classList.remove('d-none');
    noMessageDiv.classList.add('d-none');
});
</script>
@endpush
@endsection