@extends('layouts.app')
@section('title', 'Modifier un livre')

@push('styles')
<style>
.book-form-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
}

.book-form-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: none;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.form-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-control, .form-select {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background: white;
    transform: translateY(-1px);
}

.file-upload-area {
    border: 2px dashed #cbd5e0;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    background: #f8fafc;
    transition: all 0.3s ease;
    cursor: pointer;
}

.file-upload-area:hover {
    border-color: #667eea;
    background: #edf2f7;
}

.file-upload-area.dragover {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.1);
}

.tags-container {
    background: linear-gradient(145deg, #f8fafc 0%, #edf2f7 100%);
    border: 2px solid #e2e8f0;
    border-radius: 15px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.tag-item {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 25px;
    padding: 0.5rem 1rem;
    margin: 0.25rem;
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.tag-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.tag-item.selected {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.preview-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 2px solid #f1f5f9;
    position: sticky;
    top: 2rem;
}

.section-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.form-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: 15px;
    border: 1px solid #e2e8f0;
}

.step-indicator {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2rem;
}

.step {
    flex: 1;
    text-align: center;
    position: relative;
}

.step.active .step-circle {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-weight: bold;
    transition: all 0.3s ease;
}

.step-line {
    position: absolute;
    top: 20px;
    left: 50%;
    width: 100%;
    height: 2px;
    background: #e2e8f0;
    z-index: -1;
}

.floating-elements {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
}

.floating-book {
    position: absolute;
    font-size: 2rem;
    color: rgba(255, 255, 255, 0.1);
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.current-image {
    border: 3px solid #667eea;
    border-radius: 15px;
    overflow: hidden;
    position: relative;
    display: inline-block;
}

.current-image::before {
    content: "Image actuelle";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
    padding: 0.25rem;
    font-size: 0.75rem;
    font-weight: bold;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="book-form-container position-relative">
        <div class="floating-elements">
            <div class="floating-book" style="top: 10%; left: 10%; animation-delay: 0s;">📝</div>
            <div class="floating-book" style="top: 20%; right: 15%; animation-delay: 2s;">📖</div>
            <div class="floating-book" style="bottom: 30%; left: 20%; animation-delay: 4s;">✨</div>
            <div class="floating-book" style="bottom: 20%; right: 10%; animation-delay: 3s;">🔄</div>
        </div>
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-edit me-3"></i>Modifier "{{ $book->title }}"
                </h1>
                <p class="lead mb-0">Mettez à jour les informations de votre livre</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('books.index') }}" class="btn btn-light btn-lg rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Retour à mes livres
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form Column -->
        <div class="col-lg-8">
            <div class="card book-form-card">
                <div class="card-body p-4">
                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step active">
                            <div class="step-circle">1</div>
                            <small>Informations</small>
                            <div class="step-line"></div>
                        </div>
                        <div class="step">
                            <div class="step-circle">2</div>
                            <small>Catégorie</small>
                            <div class="step-line"></div>
                        </div>
                        <div class="step">
                            <div class="step-circle">3</div>
                            <small>Tags</small>
                            <div class="step-line"></div>
                        </div>
                        <div class="step">
                            <div class="step-circle">4</div>
                            <small>Mise à jour</small>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data" id="book-form">
                        @csrf
                        @method('PUT')
                        
                        <!-- Section 1: Informations de base -->
                        <div class="form-section" data-step="1">
                            <div class="section-header">
                                <i class="fas fa-book"></i>
                                <span>Informations du livre</span>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-heading text-primary"></i>
                                        Titre du livre
                                    </label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                           value="{{ old('title', $book->title) }}" required>
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-user-edit text-success"></i>
                                        Auteur
                                    </label>
                                    <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" 
                                           value="{{ old('author', $book->author) }}" required>
                                    @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-child text-warning"></i>
                                        Âge recommandé
                                    </label>
                                    <select name="recommended_age" class="form-select @error('recommended_age') is-invalid @enderror" required>
                                        <option value="">Choisir un âge</option>
                                        <option value="0" {{ old('recommended_age', $book->recommended_age) == '0' ? 'selected' : '' }}>Tout âge</option>
                                        <option value="6" {{ old('recommended_age', $book->recommended_age) == '6' ? 'selected' : '' }}>6+ ans</option>
                                        <option value="9" {{ old('recommended_age', $book->recommended_age) == '9' ? 'selected' : '' }}>9+ ans</option>
                                        <option value="12" {{ old('recommended_age', $book->recommended_age) == '12' ? 'selected' : '' }}>12+ ans</option>
                                        <option value="13" {{ old('recommended_age', $book->recommended_age) == '13' ? 'selected' : '' }}>13+ ans</option>
                                        <option value="15" {{ old('recommended_age', $book->recommended_age) == '15' ? 'selected' : '' }}>15+ ans</option>
                                        <option value="16" {{ old('recommended_age', $book->recommended_age) == '16' ? 'selected' : '' }}>16+ ans</option>
                                        <option value="18" {{ old('recommended_age', $book->recommended_age) == '18' ? 'selected' : '' }}>18+ ans</option>
                                    </select>
                                    @error('recommended_age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-toggle-on text-info"></i>
                                        Statut
                                    </label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="available" {{ old('status', $book->status) == 'available' ? 'selected' : '' }}>📗 Disponible</option>
                                        <option value="reserved" {{ old('status', $book->status) == 'reserved' ? 'selected' : '' }}>📕 Réservé</option>
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-align-left text-secondary"></i>
                                    Description
                                </label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="4" placeholder="Décrivez le livre, son intrigue, ce qui le rend spécial...">{{ old('description', $book->description) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Maximum 1000 caractères
                                </small>
                            </div>
                        </div>

                        <!-- Section 2: Photo de couverture -->
                        <div class="form-section" data-step="2">
                            <div class="section-header">
                                <i class="fas fa-camera"></i>
                                <span>Photo de couverture</span>
                            </div>
                            
                            @if($book->photo)
                                <div class="mb-3 text-center">
                                    <div class="current-image">
                                        <img src="{{ $book->photo_url }}" alt="Couverture actuelle" style="max-height: 200px; width: auto;">
                                    </div>
                                    <p class="text-muted mt-2">
                                        <i class="fas fa-info-circle"></i> 
                                        Sélectionnez une nouvelle image pour remplacer l'actuelle, ou laissez vide pour la conserver
                                    </p>
                                </div>
                            @endif
                            
                            <div class="file-upload-area" onclick="document.getElementById('photo-input').click()">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <h5>{{ $book->photo ? 'Changer l\'image de couverture' : 'Ajouter une image de couverture' }}</h5>
                                <p class="text-muted mb-0">Cliquez pour sélectionner ou glissez-déposez votre fichier ici</p>
                                <small class="text-muted">JPEG, PNG, JPG, GIF, WebP - Max 2MB</small>
                                <input type="file" name="photo" id="photo-input" class="d-none @error('photo') is-invalid @enderror" accept="image/*">
                                @error('photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div id="image-preview" class="mt-3 d-none">
                                <img id="preview-img" src="" alt="Aperçu" class="img-fluid rounded shadow" style="max-height: 200px;">
                                <p class="text-center text-muted mt-2">Nouvelle image sélectionnée</p>
                            </div>
                        </div>

                        <!-- Section 3: Catégorie -->
                        <div class="form-section" data-step="3">
                            <div class="section-header">
                                <i class="fas fa-list"></i>
                                <span>Catégorie</span>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-bookmark text-purple"></i>
                                    Sélectionnez une catégorie
                                </label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" id="category-select">
                                    <option value="">Choisir une catégorie (optionnel)</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }} 
                                            @if($category->age_allowed > 0)
                                                ({{ $category->age_allowed }}+)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Section 4: Tags -->
                        <div class="form-section" data-step="4" id="tags-section">
                            <div class="section-header">
                                <i class="fas fa-tags"></i>
                                <span>Tags du livre</span>
                            </div>
                            
                            <div class="tags-container">
                                <div id="no-category-message" class="text-center text-muted" style="{{ $book->category_id ? 'display: none;' : '' }}">
                                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                                    <h5>Sélectionnez d'abord une catégorie</h5>
                                    <p>Les tags correspondants apparaîtront ici</p>
                                </div>
                                <div id="tags-list" class="{{ $book->category_id ? '' : 'd-none' }}">
                                    <!-- Tags will be loaded here dynamically -->
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center pt-3">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save me-2"></i>Mettre à jour le livre
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Column -->
        <div class="col-lg-4">
            <div class="preview-card">
                <h5 class="text-center mb-3">
                    <i class="fas fa-eye text-primary me-2"></i>Aperçu du livre
                </h5>
                <div id="book-preview">
                    <div class="text-center mb-3">
                        <div id="preview-image" class="mb-3">
                            @if($book->photo)
                                <img src="{{ $book->photo_url }}" alt="{{ $book->title }}" style="width: 60px; height: 80px; object-fit: cover;" class="rounded shadow">
                            @else
                                <i class="fas fa-book fa-4x text-muted"></i>
                            @endif
                        </div>
                        <h6 id="preview-title">{{ $book->title }}</h6>
                        <small id="preview-author" class="text-muted">par {{ $book->author }}</small>
                    </div>
                    <div id="preview-details">
                        <div class="mb-2">
                            <small class="text-muted">Catégorie:</small>
                            <span id="preview-category" class="badge {{ $book->category ? 'bg-primary' : 'bg-light text-dark' }}">
                                {{ $book->category ? $book->category->name : 'Non sélectionnée' }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Âge recommandé:</small>
                            <span id="preview-age" class="badge bg-warning">
                                {{ $book->recommended_age == 0 ? 'Tout âge' : $book->recommended_age . '+ ans' }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Statut:</small>
                            <span id="preview-status" class="badge {{ $book->status === 'available' ? 'bg-success' : 'bg-danger' }}">
                                {{ $book->status === 'available' ? '📗 Disponible' : '📕 Réservé' }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Tags:</small>
                            <div id="preview-tags">
                                @if($book->categoryTags && $book->categoryTags->count() > 0)
                                    @foreach($book->categoryTags as $tag)
                                        <span class="badge me-1 mb-1" style="background-color: {{ $tag->color }}; color: white;">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <small class="text-muted">Aucun tag sélectionné</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedTags = [];
    
    // Get existing tags from the book
    @if($book->categoryTags && $book->categoryTags->count() > 0)
        selectedTags = [
            @foreach($book->categoryTags as $tag)
                {{ $tag->id }}{{ !$loop->last ? ',' : '' }}
            @endforeach
        ];
    @endif

    // Form elements
    const form = document.getElementById('book-form');
    const categorySelect = document.getElementById('category-select');
    const tagsList = document.getElementById('tags-list');
    const noCategoryMessage = document.getElementById('no-category-message');
    
    // Preview elements
    const previewTitle = document.getElementById('preview-title');
    const previewAuthor = document.getElementById('preview-author');
    const previewCategory = document.getElementById('preview-category');
    const previewAge = document.getElementById('preview-age');
    const previewStatus = document.getElementById('preview-status');
    const previewTags = document.getElementById('preview-tags');
    const previewImage = document.getElementById('preview-image');

    // File upload
    const photoInput = document.getElementById('photo-input');
    const fileUploadArea = document.querySelector('.file-upload-area');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    // Update preview in real-time
    function updatePreview() {
        // Title and author
        const titleInput = document.querySelector('input[name="title"]');
        const authorInput = document.querySelector('input[name="author"]');
        
        previewTitle.textContent = titleInput.value || 'Titre du livre';
        previewAuthor.textContent = 'par ' + (authorInput.value || 'Auteur');

        // Category
        const selectedCategory = categorySelect.options[categorySelect.selectedIndex];
        if (categorySelect.value) {
            previewCategory.textContent = selectedCategory.text;
            previewCategory.className = 'badge bg-primary';
        } else {
            previewCategory.textContent = 'Non sélectionnée';
            previewCategory.className = 'badge bg-light text-dark';
        }

        // Age
        const ageSelect = document.querySelector('select[name="recommended_age"]');
        const ageValue = ageSelect.value;
        if (ageValue === '0') {
            previewAge.textContent = 'Tout âge';
        } else if (ageValue) {
            previewAge.textContent = ageValue + '+ ans';
        }

        // Status
        const statusSelect = document.querySelector('select[name="status"]');
        const statusValue = statusSelect.value;
        if (statusValue === 'available') {
            previewStatus.textContent = '📗 Disponible';
            previewStatus.className = 'badge bg-success';
        } else if (statusValue === 'reserved') {
            previewStatus.textContent = '📕 Réservé';
            previewStatus.className = 'badge bg-danger';
        }

        // Update tags preview
        updateTagsPreview();
    }

    function updateTagsPreview() {
        const selectedTagElements = document.querySelectorAll('.tag-item.selected');
        
        if (selectedTagElements.length === 0) {
            previewTags.innerHTML = '<small class="text-muted">Aucun tag sélectionné</small>';
        } else {
            let tagsHtml = '';
            selectedTagElements.forEach(tag => {
                const tagName = tag.querySelector('span').textContent;
                const tagColor = tag.dataset.color;
                tagsHtml += `<span class="badge me-1 mb-1" style="background-color: ${tagColor}; color: white;">${tagName}</span>`;
            });
            previewTags.innerHTML = tagsHtml;
        }
    }

    // Load tags based on category
    function loadCategoryTags(categoryId) {
        if (!categoryId) {
            tagsList.classList.add('d-none');
            noCategoryMessage.style.display = 'block';
            return;
        }

        fetch(`/categories/${categoryId}/tags`)
            .then(response => response.json())
            .then(tags => {
                tagsList.innerHTML = '';
                
                if (tags.length === 0) {
                    tagsList.innerHTML = '<p class="text-muted text-center"><i class="fas fa-info-circle me-2"></i>Aucun tag disponible pour cette catégorie</p>';
                } else {
                    tags.forEach(tag => {
                        const isSelected = selectedTags.includes(tag.id);
                        const tagElement = document.createElement('div');
                        tagElement.className = `tag-item ${isSelected ? 'selected' : ''}`;
                        tagElement.dataset.tagId = tag.id;
                        tagElement.dataset.color = tag.color;
                        tagElement.innerHTML = `
                            <span style="color: ${tag.color};">●</span>
                            <span>${tag.name}</span>
                        `;
                        
                        tagElement.addEventListener('click', function() {
                            toggleTag(this, tag.id);
                        });
                        
                        tagsList.appendChild(tagElement);
                    });
                }
                
                tagsList.classList.remove('d-none');
                noCategoryMessage.style.display = 'none';
                updateTagsPreview();
            })
            .catch(error => {
                console.error('Error loading tags:', error);
                tagsList.innerHTML = '<p class="text-danger text-center"><i class="fas fa-exclamation-triangle me-2"></i>Erreur lors du chargement des tags</p>';
                tagsList.classList.remove('d-none');
                noCategoryMessage.style.display = 'none';
            });
    }

    function toggleTag(tagElement, tagId) {
        if (tagElement.classList.contains('selected')) {
            tagElement.classList.remove('selected');
            selectedTags = selectedTags.filter(id => id !== tagId);
        } else {
            tagElement.classList.add('selected');
            selectedTags.push(tagId);
        }
        updateTagsPreview();
    }

    // File upload handling
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('d-none');
                
                // Update main preview
                previewImage.innerHTML = `<img src="${e.target.result}" alt="Aperçu" style="width: 60px; height: 80px; object-fit: cover;" class="rounded shadow">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Drag and drop for file upload
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.add('dragover');
    });

    fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('dragover');
    });

    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            photoInput.files = files;
            photoInput.dispatchEvent(new Event('change'));
        }
    });

    // Category change handler
    categorySelect.addEventListener('change', function() {
        selectedTags = []; // Reset selected tags when category changes
        loadCategoryTags(this.value);
        updatePreview();
    });

    // Real-time preview updates
    document.querySelectorAll('input[name="title"], input[name="author"], select[name="recommended_age"], select[name="status"]')
        .forEach(element => {
            element.addEventListener('input', updatePreview);
            element.addEventListener('change', updatePreview);
        });

    // Form submission - add selected tags as hidden inputs
    form.addEventListener('submit', function(e) {
        // Remove existing tag inputs
        const existingTagInputs = form.querySelectorAll('input[name="tags[]"]');
        existingTagInputs.forEach(input => input.remove());
        
        // Add selected tags as hidden inputs
        selectedTags.forEach(tagId => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'tags[]';
            hiddenInput.value = tagId;
            form.appendChild(hiddenInput);
        });
    });

    // Step indicators animation
    const steps = document.querySelectorAll('.step');
    const sections = document.querySelectorAll('[data-step]');
    
    function updateStepIndicators() {
        const scrollTop = window.pageYOffset;
        const windowHeight = window.innerHeight;
        
        sections.forEach((section, index) => {
            const rect = section.getBoundingClientRect();
            const sectionTop = rect.top + scrollTop;
            const sectionHeight = rect.height;
            
            if (scrollTop + windowHeight / 2 >= sectionTop && scrollTop < sectionTop + sectionHeight) {
                steps.forEach((step, stepIndex) => {
                    if (stepIndex <= index) {
                        step.classList.add('active');
                    } else {
                        step.classList.remove('active');
                    }
                });
            }
        });
    }

    window.addEventListener('scroll', updateStepIndicators);

    // Initialize
    if (categorySelect.value) {
        loadCategoryTags(categorySelect.value);
    }
    updatePreview();
    updateStepIndicators();
});
</script>
@endpush
@endsection