@extends('layouts.app')

@section('title', 'Créer un sondage - ' . $event->title)

@push('styles')
<style>
    .poll-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 0.6rem; padding: 2rem; margin-bottom: 2rem; }
    .form-group-wrapper { background: #fff; border-radius: 0.6rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .form-group-title { font-size: 1rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
    .form-group-title i { color: #667eea; font-size: 1.1rem; }
    .poll-option-item { display: flex; gap: 0.75rem; margin-bottom: 0.75rem; align-items: center; }
    .poll-option-item input { flex: 1; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.9rem; transition: all 0.2s; }
    .poll-option-item input:focus { outline: none; border-color: #667eea; ring: 2px rgba(102, 126, 234, 0.1); box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
    .btn-remove { padding: 0.5rem 1rem; background: #fee2e2; color: #dc2626; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 0.9rem; transition: all 0.2s; }
    .btn-remove:hover { background: #fecaca; }
    .btn-add-option { padding: 0.75rem 1.5rem; background: #dbeafe; color: #1d4ed8; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 0.9rem; font-weight: 500; transition: all 0.2s; margin-top: 0.75rem; }
    .btn-add-option:hover { background: #bfdbfe; }
    .btn-submit { padding: 0.75rem 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 0.95rem; font-weight: 600; transition: all 0.2s; }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); }
    .btn-cancel { padding: 0.75rem 2rem; background: #f3f4f6; color: #6b7280; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer; font-size: 0.95rem; font-weight: 600; transition: all 0.2s; text-decoration: none; display: inline-block; }
    .btn-cancel:hover { background: #e5e7eb; }
    .form-error { color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem; display: flex; align-items: center; gap: 0.25rem; }
    .form-error i { font-size: 0.75rem; }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Poll Header -->
            <div class="poll-header">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h3 class="mb-2">📊 Créer un sondage</h3>
                        <p class="mb-0 text-white-75">Événement: <strong>{{ $event->title }}</strong></p>
                    </div>
                    <a href="{{ route('reading-groups.events.show', [$event->readingGroup, $event]) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('polls.store', [$event->readingGroup, $event]) }}" method="POST">
                @csrf

                <!-- Title Section -->
                <div class="form-group-wrapper">
                    <div class="form-group-title">
                        <i class="fas fa-heading"></i>
                        Titre du sondage
                    </div>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        class="form-control @error('title') is-invalid @enderror"
                        placeholder="Ex: Quel livre lire ensuite?"
                        value="{{ old('title') }}"
                        required
                    >
                    @error('title')
                        <div class="form-error mt-2">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Description Section -->
                <div class="form-group-wrapper">
                    <div class="form-group-title">
                        <i class="fas fa-align-left"></i>
                        Description
                    </div>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="form-control"
                        placeholder="Décrivez votre sondage (optionnel)"
                    >{{ old('description') }}</textarea>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle"></i> Une description aide les participants à comprendre le contexte
                    </small>
                </div>

                <!-- Poll Type Section -->
                <div class="form-group-wrapper">
                    <div class="form-group-title">
                        <i class="fas fa-list"></i>
                        Type de sondage
                    </div>
                    <select
                        id="type"
                        name="type"
                        class="form-control @error('type') is-invalid @enderror"
                        onchange="updatePollTypeUI()"
                        required
                    >
                        <option value="">-- Sélectionner un type --</option>
                        <option value="yes_no" {{ old('type') === 'yes_no' ? 'selected' : '' }}>
                            👍 Oui/Non
                        </option>
                        <option value="multiple_choice" {{ old('type') === 'multiple_choice' ? 'selected' : '' }}>
                            🎯 Choix multiples
                        </option>
                        <option value="rating" {{ old('type') === 'rating' ? 'selected' : '' }}>
                            ⭐ Évaluation (1-5)
                        </option>
                    </select>
                    @error('type')
                        <div class="form-error mt-2">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Poll Options (for multiple_choice only) -->
                <div id="optionsContainer" class="form-group-wrapper" style="display: none;">
                    <div class="form-group-title">
                        <i class="fas fa-check-square"></i>
                        Options du sondage
                    </div>
                    <div id="optionsWrapper"></div>
                    <button type="button" class="btn-add-option" onclick="addOption()">
                        <i class="fas fa-plus me-1"></i> Ajouter une option
                    </button>
                    @error('options')
                        <div class="form-error mt-2">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Close Time Section -->
                <div class="form-group-wrapper">
                    <div class="form-group-title">
                        <i class="fas fa-clock"></i>
                        Fermeture du sondage
                    </div>
                    <input
                        type="datetime-local"
                        id="closes_at"
                        name="closes_at"
                        class="form-control"
                        value="{{ old('closes_at', $event->getEventEndTime()?->format('Y-m-d\TH:i')) }}"
                    >
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle"></i> 
                        Défaut: Fin de l'événement 
                        <strong>({{ $event->getEventEndTime()?->format('d/m/Y H:i') ?? 'Non défini' }})</strong>
                    </small>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check me-2"></i>Créer le sondage
                    </button>
                    <a href="{{ route('reading-groups.events.show', [$event->readingGroup, $event]) }}" class="btn-cancel">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Initialize options container with default options
    function initializeOptions() {
        const optionsWrapper = document.getElementById('optionsWrapper');
        optionsWrapper.innerHTML = '';
        
        const oldOptions = @json(old('options', []));
        const optionCount = oldOptions.length > 0 ? oldOptions.length : 2;
        
        for (let i = 0; i < optionCount; i++) {
            addOption(i + 1, oldOptions[i] || '');
        }
    }

    function updatePollTypeUI() {
        const type = document.getElementById('type').value;
        const optionsContainer = document.getElementById('optionsContainer');

        if (type === 'multiple_choice') {
            optionsContainer.style.display = 'block';
            if (document.getElementById('optionsWrapper').children.length === 0) {
                initializeOptions();
            }
        } else {
            optionsContainer.style.display = 'none';
        }
    }

    function addOption(number = null, value = '') {
        const wrapper = document.getElementById('optionsWrapper');
        const optionCount = wrapper.children.length + 1;
        const optionNumber = number || optionCount;
        
        const optionDiv = document.createElement('div');
        optionDiv.className = 'poll-option-item';
        optionDiv.innerHTML = `
            <input
                type="text"
                name="options[]"
                placeholder="Option ${optionNumber}"
                value="${value}"
                required
            >
            <button type="button" class="btn-remove" onclick="removeOption(this)">
                <i class="fas fa-trash me-1"></i>Supprimer
            </button>
        `;
        wrapper.appendChild(optionDiv);
    }

    function removeOption(button) {
        const wrapper = document.getElementById('optionsWrapper');
        if (wrapper.children.length > 2) {
            button.parentElement.remove();
        } else {
            alert('Vous devez avoir au moins 2 options');
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const type = document.getElementById('type').value;
        if (type === 'multiple_choice') {
            initializeOptions();
            document.getElementById('optionsContainer').style.display = 'block';
        }
    });
</script>

    function addOption() {
        const wrapper = document.getElementById('optionsWrapper');
        const index = wrapper.children.length;

        const div = document.createElement('div');
        div.className = 'flex gap-2 mb-2';
        div.innerHTML = `
            <input
                type="text"
                name="options[]"
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Option ${index + 1}"
            >
            <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600" onclick="removeOption(this)">
                Supprimer
            </button>
        `;
        wrapper.appendChild(div);
    }

    function removeOption(button) {
        const wrapper = document.getElementById('optionsWrapper');
        if (wrapper.children.length > 2) {
            button.parentElement.remove();
        } else {
            alert('Vous devez avoir au moins 2 options');
        }
    }

    // Initialize UI based on stored value
    document.addEventListener('DOMContentLoaded', updatePollTypeUI);
</script>
@endsection
