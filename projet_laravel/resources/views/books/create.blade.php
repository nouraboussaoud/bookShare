@extends('layouts.app')
@section('title', 'Ajouter un livre')

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
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="book-form-container position-relative">
        <div class="floating-elements">
            <div class="floating-book" style="top: 10%; left: 10%; animation-delay: 0s;">📚</div>
            <div class="floating-book" style="top: 20%; right: 15%; animation-delay: 2s;">📖</div>
            <div class="floating-book" style="bottom: 30%; left: 20%; animation-delay: 4s;">✨</div>
            <div class="floating-book" style="bottom: 20%; right: 10%; animation-delay: 3s;">🔖</div>
        </div>
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-plus-circle me-3"></i>Ajouter un nouveau livre
                </h1>
                <p class="lead mb-0">Partagez vos livres préférés avec la communauté BookShare</p>
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
                            <small>Publication</small>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data" id="book-form">
                        @csrf
                        
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
                                           value="{{ old('title') }}" required placeholder="Ex: Le Petit Prince">
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-user-edit text-success"></i>
                                        Auteur
                                    </label>
                                    <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" 
                                           value="{{ old('author') }}" required placeholder="Ex: Antoine de Saint-Exupéry">
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
                                        <option value="0" {{ old('recommended_age') == '0' ? 'selected' : '' }}>Tout âge</option>
                                        <option value="6" {{ old('recommended_age') == '6' ? 'selected' : '' }}>6+ ans</option>
                                        <option value="9" {{ old('recommended_age') == '9' ? 'selected' : '' }}>9+ ans</option>
                                        <option value="12" {{ old('recommended_age') == '12' ? 'selected' : '' }}>12+ ans</option>
                                        <option value="13" {{ old('recommended_age') == '13' ? 'selected' : '' }}>13+ ans</option>
                                        <option value="15" {{ old('recommended_age') == '15' ? 'selected' : '' }}>15+ ans</option>
                                        <option value="16" {{ old('recommended_age') == '16' ? 'selected' : '' }}>16+ ans</option>
                                        <option value="18" {{ old('recommended_age') == '18' ? 'selected' : '' }}>18+ ans</option>
                                    </select>
                                    @error('recommended_age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-toggle-on text-info"></i>
                                        Statut
                                    </label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>📗 Disponible</option>
                                        <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>📕 Réservé</option>
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
                                          rows="4" placeholder="Décrivez le livre, son intrigue, ce qui le rend spécial...">{{ old('description') }}</textarea>
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
                            
                            <div class="file-upload-area" onclick="document.getElementById('photo-input').click()">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <h5>Cliquez pour sélectionner une image</h5>
                                <p class="text-muted mb-0">ou glissez-déposez votre fichier ici</p>
                                <small class="text-muted">JPEG, PNG, JPG, GIF, WebP - Max 2MB</small>
                                <input type="file" name="photo" id="photo-input" class="d-none @error('photo') is-invalid @enderror" accept="image/*">
                                @error('photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div id="image-preview" class="mt-3 d-none">
                                <img id="preview-img" src="" alt="Aperçu" class="img-fluid rounded shadow" style="max-height: 200px;">
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
                        </div>

                        <!-- Section 4: Tags -->
                        <div class="form-section" data-step="4" id="tags-section">
                            <div class="section-header">
                                <i class="fas fa-tags"></i>
                                <span>Tags du livre</span>
                            </div>
                            
                            <div class="tags-container">
                                <div id="no-category-message" class="text-center text-muted">
                                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                                    <h5>Sélectionnez d'abord une catégorie</h5>
                                    <p>Les tags correspondants apparaîtront ici</p>
                                </div>
                                <div id="tags-list" class="d-none">
                                    <!-- Tags will be loaded here dynamically -->
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center pt-3">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save me-2"></i>Publier le livre
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
                            <i class="fas fa-book fa-4x text-muted"></i>
                        </div>
                        <h6 id="preview-title" class="text-muted">Titre du livre</h6>
                        <small id="preview-author" class="text-muted">par Auteur</small>
                    </div>
                    <div id="preview-details">
                        <div class="mb-2">
                            <small class="text-muted">Catégorie:</small>
                            <span id="preview-category" class="badge bg-light text-dark">Non sélectionnée</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Âge recommandé:</small>
                            <span id="preview-age" class="badge bg-warning">Non défini</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Statut:</small>
                            <span id="preview-status" class="badge bg-success">Disponible</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Tags:</small>
                            <div id="preview-tags">
                                <small class="text-muted">Aucun tag sélectionné</small>
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

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const categorySelect = document.getElementById('category-select');
    const tagsListDiv = document.getElementById('tags-list');
    const noMessageDiv = document.getElementById('no-category-message');
    const photoInput = document.getElementById('photo-input');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const fileUploadArea = document.querySelector('.file-upload-area');

    // Preview elements
    const previewTitle = document.getElementById('preview-title');
    const previewAuthor = document.getElementById('preview-author');
    const previewCategory = document.getElementById('preview-category');
    const previewAge = document.getElementById('preview-age');
    const previewStatus = document.getElementById('preview-status');
    const previewTags = document.getElementById('preview-tags');
    const previewImage = document.getElementById('preview-image');

    // Real-time preview updates
    document.querySelector('input[name="title"]').addEventListener('input', function() {
        previewTitle.textContent = this.value || 'Titre du livre';
    });

    document.querySelector('input[name="author"]').addEventListener('input', function() {
        previewAuthor.textContent = this.value ? `par ${this.value}` : 'par Auteur';
    });

    document.querySelector('select[name="recommended_age"]').addEventListener('change', function() {
        const ageLabels = {
            '0': 'Tout âge',
            '6': '6+ ans',
            '9': '9+ ans',
            '12': '12+ ans',
            '13': '13+ ans',
            '15': '15+ ans',
            '16': '16+ ans',
            '18': '18+ ans'
        };
        previewAge.textContent = ageLabels[this.value] || 'Non défini';
        previewAge.className = this.value ? 'badge bg-warning' : 'badge bg-secondary';
    });

    document.querySelector('select[name="status"]').addEventListener('change', function() {
        previewStatus.textContent = this.value === 'available' ? '📗 Disponible' : '📕 Réservé';
        previewStatus.className = this.value === 'available' ? 'badge bg-success' : 'badge bg-danger';
    });

    // File upload handling
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('d-none');
                previewImage.innerHTML = `<img src="${e.target.result}" alt="Aperçu" class="rounded shadow" style="width: 60px; height: 80px; object-fit: cover;">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Drag and drop for file upload
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            photoInput.files = files;
            photoInput.dispatchEvent(new Event('change'));
        }
    });

    // Category change handler
    categorySelect.addEventListener('change', function() {
        const categoryId = parseInt(this.value);
        
        // Update preview
        if (categoryId) {
            const category = categoriesData.find(c => c.id === categoryId);
            previewCategory.textContent = category ? category.name : 'Non sélectionnée';
            previewCategory.className = 'badge bg-primary';
        } else {
            previewCategory.textContent = 'Non sélectionnée';
            previewCategory.className = 'badge bg-light text-dark';
        }
        
        loadTagsForCategory(categoryId);
    });

    function loadTagsForCategory(categoryId) {
        if (!categoryId) {
            tagsListDiv.classList.add('d-none');
            noMessageDiv.style.display = 'block';
            updateTagsPreview([]);
            return;
        }
        
        const category = categoriesData.find(c => c.id === categoryId);
        
        if (!category || category.tags.length === 0) {
            tagsListDiv.innerHTML = '<div class="text-center text-muted"><i class="fas fa-info-circle fa-2x mb-3"></i><h6>Aucun tag disponible</h6><p>Cette catégorie n\'a pas encore de tags</p></div>';
            tagsListDiv.classList.remove('d-none');
            noMessageDiv.style.display = 'none';
            updateTagsPreview([]);
            return;
        }
        
        // Group tags by type
        const tagsByType = category.tags.reduce((acc, tag) => {
            if (!acc[tag.type]) acc[tag.type] = [];
            acc[tag.type].push(tag);
            return acc;
        }, {});

        const typeLabels = {
            'genre': '📖 Genre',
            'theme': '🎭 Thème',
            'mood': '😊 Ambiance',
            'pace': '⚡ Rythme',
            'other': '🏷️ Autre'
        };

        let html = '';
        Object.keys(tagsByType).forEach(type => {
            html += `<div class="mb-3">
                <h6 class="text-muted mb-2">${typeLabels[type] || '🏷️ Autre'}</h6>
                <div class="d-flex flex-wrap gap-2">`;
            
            tagsByType[type].forEach(tag => {
                html += `
                    <div class="tag-item" data-tag-id="${tag.id}" onclick="toggleTag(this, ${tag.id}, '${tag.name}', '${tag.color}')">
                        <input type="checkbox" name="tags[]" value="${tag.id}" id="tag-${tag.id}" style="display: none;">
                        ${tag.icon ? `<i class="${tag.icon}"></i>` : ''}
                        <span>${tag.name}</span>
                    </div>
                `;
            });
            
            html += '</div></div>';
        });
        
        tagsListDiv.innerHTML = html;
        tagsListDiv.classList.remove('d-none');
        noMessageDiv.style.display = 'none';
    }

    // Global function for tag toggle
    window.toggleTag = function(element, tagId, tagName, tagColor) {
        const checkbox = element.querySelector('input[type="checkbox"]');
        const isSelected = element.classList.contains('selected');
        
        if (isSelected) {
            element.classList.remove('selected');
            element.style.background = '';
            element.style.color = '';
            checkbox.checked = false;
        } else {
            element.classList.add('selected');
            element.style.background = `linear-gradient(135deg, ${tagColor} 0%, ${tagColor}aa 100%)`;
            element.style.color = 'white';
            checkbox.checked = true;
        }
        
        updateTagsPreview();
    };

    function updateTagsPreview() {
        const selectedTags = document.querySelectorAll('input[name="tags[]"]:checked');
        
        if (selectedTags.length === 0) {
            previewTags.innerHTML = '<small class="text-muted">Aucun tag sélectionné</small>';
            return;
        }
        
        let tagsHtml = '';
        selectedTags.forEach(checkbox => {
            const tagElement = checkbox.closest('.tag-item');
            const tagName = tagElement.querySelector('span').textContent;
            const tagColor = getComputedStyle(tagElement).background;
            
            tagsHtml += `<span class="badge me-1 mb-1" style="background: ${tagColor}; color: white;">${tagName}</span>`;
        });
        
        previewTags.innerHTML = tagsHtml;
    }

    // Step navigation (visual feedback)
    const steps = document.querySelectorAll('.step');
    const sections = document.querySelectorAll('.form-section');
    
    // Intersection Observer for step highlighting
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const stepIndex = parseInt(entry.target.dataset.step) - 1;
                steps.forEach((step, index) => {
                    step.classList.toggle('active', index <= stepIndex);
                });
            }
        });
    }, { threshold: 0.5 });

    sections.forEach(section => observer.observe(section));

    // Form validation enhancements
    const form = document.getElementById('book-form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            // Scroll to first invalid field
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalid.focus();
            }
        }
    });

    // Character counter for description
    const descriptionField = document.querySelector('textarea[name="description"]');
    if (descriptionField) {
        const maxLength = 1000;
        const counter = document.createElement('div');
        counter.className = 'text-end text-muted small mt-1';
        descriptionField.parentNode.appendChild(counter);
        
        function updateCounter() {
            const remaining = maxLength - descriptionField.value.length;
            counter.textContent = `${remaining} caractères restants`;
            counter.className = `text-end small mt-1 ${remaining < 100 ? 'text-warning' : remaining < 0 ? 'text-danger' : 'text-muted'}`;
        }
        
        descriptionField.addEventListener('input', updateCounter);
        updateCounter();
    }
});
</script>
@endpush
@endsection