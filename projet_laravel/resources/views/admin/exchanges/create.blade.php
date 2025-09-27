@extends('layouts.layout')

@section('title', 'BookShare - Créer un Échange (Admin)')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus text-primary mr-2"></i>
                Créer un Nouvel Échange (Administration)
            </h1>
            <p class="mb-0 text-gray-600">Créer un échange entre deux utilisateurs de la plateforme</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.exchanges.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i>Retour à la liste
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Form Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus-circle fa-sm text-primary"></i> Nouvel Échange
                    </h6>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.exchanges.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="type" class="form-label font-weight-bold">Type d'échange</label>
                                    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="RESERVATION" {{ old('type') == 'RESERVATION' ? 'selected' : '' }}>Réservation</option>
                                        <option value="ECHANGE" {{ old('type') == 'ECHANGE' ? 'selected' : '' }}>Échange</option>
                                        <option value="PRET" {{ old('type') == 'PRET' ? 'selected' : '' }}>Prêt</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label font-weight-bold">Statut</label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="EN_ATTENTE" {{ old('status', 'EN_ATTENTE') == 'EN_ATTENTE' ? 'selected' : '' }}>En attente</option>
                                        <option value="EN_COURS" {{ old('status') == 'EN_COURS' ? 'selected' : '' }}>En cours</option>
                                        <option value="TERMINE" {{ old('status') == 'TERMINE' ? 'selected' : '' }}>Terminé</option>
                                        <option value="ANNULE" {{ old('status') == 'ANNULE' ? 'selected' : '' }}>Annulé</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="userInitiateurId" class="form-label font-weight-bold">Utilisateur Initiateur</label>
                                    <select name="userInitiateurId" id="userInitiateurId" class="form-control @error('userInitiateurId') is-invalid @enderror" required>
                                        <option value="">Sélectionner l'initiateur</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('userInitiateurId') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('userInitiateurId')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="userRecepteurId" class="form-label font-weight-bold">Utilisateur Récepteur</label>
                                    <select name="userRecepteurId" id="userRecepteurId" class="form-control @error('userRecepteurId') is-invalid @enderror" required>
                                        <option value="">Sélectionner le récepteur</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('userRecepteurId') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('userRecepteurId')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="bookDemandeId" class="form-label font-weight-bold">
                                        <span id="book-label">Livre</span>
                                    </label>
                                    <select name="bookDemandeId" id="bookDemandeId" class="form-control @error('bookDemandeId') is-invalid @enderror" required>
                                        <option value="">Sélectionner un livre</option>
                                        @foreach($books as $book)
                                            <option value="{{ $book->id }}" {{ old('bookDemandeId') == $book->id ? 'selected' : '' }}>
                                                {{ $book->title }} - Propriétaire: {{ $book->owner->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bookDemandeId')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Book Offered Field (Only for Exchange type) -->
                        <div class="row" id="book-offered-row" style="display: none;">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="bookOffertId" class="form-label font-weight-bold">Livre offert</label>
                                    <select name="bookOffertId" id="bookOffertId" class="form-control @error('bookOffertId') is-invalid @enderror">
                                        <option value="">Sélectionner un livre à offrir</option>
                                        @foreach($books as $book)
                                            <option value="{{ $book->id }}" {{ old('bookOffertId') == $book->id ? 'selected' : '' }}>
                                                {{ $book->title }} - Propriétaire: {{ $book->owner->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bookOffertId')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="dateDebut" class="form-label font-weight-bold">Date de début</label>
                                    <input type="date" name="dateDebut" id="dateDebut" class="form-control @error('dateDebut') is-invalid @enderror" value="{{ old('dateDebut') }}" required>
                                    @error('dateDebut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="dateFin" class="form-label font-weight-bold">Date de fin</label>
                                    <input type="date" name="dateFin" id="dateFin" class="form-control @error('dateFin') is-invalid @enderror" value="{{ old('dateFin') }}" required>
                                    @error('dateFin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save fa-sm text-white-50"></i> Créer l'Échange
                                </button>
                                <a href="{{ route('admin.exchanges.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times fa-sm text-white-50"></i> Annuler
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    function updateFormBasedOnType(selectedType) {
        const bookOfferedRow = $('#book-offered-row');
        const bookOfferedField = $('#bookOffertId');
        const bookLabel = $('#book-label');
        
        // Reset visibility and requirements
        bookOfferedRow.hide();
        bookOfferedField.removeAttr('required');
        
        // Update labels and show/hide fields based on type
        switch(selectedType) {
            case 'RESERVATION':
                bookLabel.text('Livre à réserver');
                break;
            case 'PRET':
                bookLabel.text('Livre à emprunter');
                break;
            case 'ECHANGE':
                bookLabel.text('Livre demandé');
                bookOfferedRow.show();
                bookOfferedField.attr('required', 'required');
                break;
            default:
                bookLabel.text('Livre');
                break;
        }
    }
    
    // Handle exchange type change
    $('#type').on('change', function() {
        updateFormBasedOnType($(this).val());
    });
    
    // Initialize form based on current/old value
    const currentType = $('#type').val();
    if (currentType) {
        updateFormBasedOnType(currentType);
    }
    
    // Admin-specific validation functions
    function showValidationError(fieldId, message) {
        const field = $('#' + fieldId);
        field.addClass('is-invalid');
        field.siblings('.invalid-feedback').remove();
        field.after('<div class="invalid-feedback" style="color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;">' + message + '</div>');
    }
    
    function clearValidationError(fieldId) {
        const field = $('#' + fieldId);
        field.removeClass('is-invalid');
        field.siblings('.invalid-feedback').remove();
    }
    
    function validateRequiredFields() {
        let isValid = true;
        
        // Validate Type
        const type = $('#type').val();
        if (!type) {
            showValidationError('type', 'Veuillez sélectionner un type d\'échange');
            isValid = false;
        } else {
            clearValidationError('type');
        }
        
        // Validate Status
        const status = $('#status').val();
        if (!status) {
            showValidationError('status', 'Veuillez sélectionner un statut');
            isValid = false;
        } else {
            clearValidationError('status');
        }
        
        // Validate Initiateur
        const initiateur = $('#userInitiateurId').val();
        if (!initiateur) {
            showValidationError('userInitiateurId', 'Veuillez sélectionner un utilisateur initiateur');
            isValid = false;
        } else {
            clearValidationError('userInitiateurId');
        }
        
        // Validate Recepteur
        const recepteur = $('#userRecepteurId').val();
        if (!recepteur) {
            showValidationError('userRecepteurId', 'Veuillez sélectionner un utilisateur récepteur');
            isValid = false;
        } else {
            clearValidationError('userRecepteurId');
        }
        
        // Validate Book
        const book = $('#bookDemandeId').val();
        if (!book) {
            showValidationError('bookDemandeId', 'Veuillez sélectionner un livre');
            isValid = false;
        } else {
            clearValidationError('bookDemandeId');
        }
        
        // Validate dates
        const dateDebut = $('#dateDebut').val();
        const dateFin = $('#dateFin').val();
        
        if (!dateDebut) {
            showValidationError('dateDebut', 'Veuillez sélectionner une date de début');
            isValid = false;
        } else {
            clearValidationError('dateDebut');
        }
        
        if (!dateFin) {
            showValidationError('dateFin', 'Veuillez sélectionner une date de fin');
            isValid = false;
        } else {
            clearValidationError('dateFin');
        }
        
        return isValid;
    }
    
    function validateAdminForm() {
        let isValid = validateRequiredFields();
        
        // Validate dates
        const dateDebut = $('#dateDebut').val();
        const dateFin = $('#dateFin').val();
        
        if (dateDebut && dateFin && dateFin <= dateDebut) {
            showValidationError('dateFin', 'La date de fin doit être postérieure à la date de début');
            isValid = false;
        } else {
            clearValidationError('dateFin');
        }
        
        // Validate that initiator and receiver are different
        const initiateur = $('#userInitiateurId').val();
        const recepteur = $('#userRecepteurId').val();
        
        if (initiateur && recepteur && initiateur === recepteur) {
            showValidationError('userRecepteurId', 'L\'utilisateur récepteur doit être différent de l\'initiateur');
            isValid = false;
        } else {
            clearValidationError('userRecepteurId');
        }
        
        // Validate exchange type specific requirements
        const exchangeType = $('#type').val();
        if (exchangeType === 'ECHANGE') {
            const bookOffered = $('#bookOffertId').val();
            const bookDemande = $('#bookDemandeId').val();
            
            if (bookOffered === bookDemande) {
                showValidationError('bookOffertId', 'Le livre offert ne peut pas être le même que le livre demandé');
                isValid = false;
            } else {
                clearValidationError('bookOffertId');
            }
        }
        
        return isValid;
    }
    
    // Real-time validation on all fields
    $('#type, #status, #userInitiateurId, #userRecepteurId, #bookDemandeId, #bookOffertId, #dateDebut, #dateFin').on('change blur', function() {
        validateAdminForm();
    });
    
    // Form submission validation
    $('form').on('submit', function(e) {
        if (!validateAdminForm()) {
            e.preventDefault();
            $('.is-invalid').first().focus();
        }
    });
    
    // Set minimum date for date fields
    const today = new Date().toISOString().split('T')[0];
    $('#dateDebut').attr('min', today);
    
    $('#dateDebut').on('change', function() {
        const startDate = $(this).val();
        if (startDate) {
            $('#dateFin').attr('min', startDate);
        }
    });
});
</script>
@endpush