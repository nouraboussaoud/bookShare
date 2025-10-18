@extends('layouts.app')

@section('title', 'BookShare - Modifier l\'Échange')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit text-primary mr-2"></i>
                Modifier l'Échange #{{ $exchange->id }}
            </h1>
            <p class="mb-0 text-gray-600">Modifiez les détails de votre échange de livre</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('exchanges.show', $exchange->id) }}" class="btn btn-outline-info shadow-sm">
                <i class="fas fa-eye mr-1"></i>Voir les détails
            </a>
            <a href="{{ route('exchanges.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i>Retour à la liste
            </a>
        </div>
    </div>

    <!-- Form Content -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Exchange Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>Informations sur l'Échange
                    </h6>
                    <div class="text-muted">
                        <small>
                            <i class="fas fa-calendar mr-1"></i>
                            Créé le {{ \Carbon\Carbon::parse($exchange->created_at)->format('d/m/Y à H:i') }}
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Book Information -->
                    @if($exchange->bookDemande)
                        <div class="alert alert-info" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-book fa-2x text-info mr-3"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Livre concerné</h6>
                                    <strong>{{ $exchange->bookDemande->title }}</strong>
                                    @if($exchange->bookDemande->user)
                                        <br><small class="text-muted">Propriétaire: {{ $exchange->bookDemande->user->name }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Edit Form -->
                    <form action="{{ route('exchanges.update', $exchange->id) }}" method="POST" id="editExchangeForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Type -->
                                <div class="form-group">
                                    <label for="type" class="font-weight-bold text-gray-700">
                                        <i class="fas fa-tag mr-1"></i>Type d'échange
                                    </label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Sélectionnez un type</option>
                                        <option value="PRET" {{ $exchange->type == 'PRET' ? 'selected' : '' }}>
                                            <i class="fas fa-hand-holding"></i> Prêt
                                        </option>
                                        <option value="ECHANGE" {{ $exchange->type == 'ECHANGE' ? 'selected' : '' }}>
                                            <i class="fas fa-sync-alt"></i> Échange
                                        </option>
                                        <option value="DON" {{ $exchange->type == 'DON' ? 'selected' : '' }}>
                                            <i class="fas fa-gift"></i> Don
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Choisissez le type d'échange souhaité
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Status -->
                                <div class="form-group">
                                    <label for="status" class="font-weight-bold text-gray-700">
                                        <i class="fas fa-traffic-light mr-1"></i>Statut
                                    </label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="">Sélectionnez un statut</option>
                                        <option value="EN_ATTENTE" {{ $exchange->status == 'EN_ATTENTE' ? 'selected' : '' }}>
                                            En Attente
                                        </option>
                                        <option value="EN_COURS" {{ $exchange->status == 'EN_COURS' ? 'selected' : '' }}>
                                            En Cours
                                        </option>
                                        <option value="TERMINE" {{ $exchange->status == 'TERMINE' ? 'selected' : '' }}>
                                            Terminé
                                        </option>
                                        <option value="ANNULE" {{ $exchange->status == 'ANNULE' ? 'selected' : '' }}>
                                            Annulé
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Statut actuel de l'échange
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Start Date -->
                                <div class="form-group">
                                    <label for="dateDebut" class="font-weight-bold text-gray-700">
                                        <i class="fas fa-calendar-alt mr-1"></i>Date de début
                                    </label>
                                    <input type="datetime-local" 
                                           class="form-control @error('dateDebut') is-invalid @enderror" 
                                           id="dateDebut" 
                                           name="dateDebut" 
                                           value="{{ \Carbon\Carbon::parse($exchange->dateDebut)->format('Y-m-d\TH:i') }}"
                                           required>
                                    @error('dateDebut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Date et heure de début de l'échange
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- End Date -->
                                <div class="form-group">
                                    <label for="dateFin" class="font-weight-bold text-gray-700">
                                        <i class="fas fa-calendar-check mr-1"></i>Date de fin
                                    </label>
                                    <input type="datetime-local" 
                                           class="form-control @error('dateFin') is-invalid @enderror" 
                                           id="dateFin" 
                                           name="dateFin" 
                                           value="{{ \Carbon\Carbon::parse($exchange->dateFin)->format('Y-m-d\TH:i') }}"
                                           required>
                                    @error('dateFin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Date et heure de fin prévue
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Tous les champs sont obligatoires
                                        </small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('exchanges.show', $exchange->id) }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times mr-1"></i>Annuler
                                        </a>
                                        <button type="submit" class="btn btn-primary shadow-sm">
                                            <i class="fas fa-save mr-1"></i>Enregistrer les modifications
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .form-group label {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    .btn-group .btn {
        margin-left: 0.5rem;
    }
    
    .alert-info {
        border-left: 0.25rem solid #36b9cc;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Form validation
        $('#editExchangeForm').on('submit', function(e) {
            const startDate = new Date($('#dateDebut').val());
            const endDate = new Date($('#dateFin').val());
            
            if (endDate <= startDate) {
                e.preventDefault();
                alert('La date de fin doit être postérieure à la date de début.');
                return false;
            }
        });
        
        // Date validation on change
        $('#dateDebut, #dateFin').on('change', function() {
            const startDate = new Date($('#dateDebut').val());
            const endDate = new Date($('#dateFin').val());
            
            if (startDate && endDate && endDate <= startDate) {
                $(this).addClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
                $(this).after('<div class="invalid-feedback">La date de fin doit être postérieure à la date de début.</div>');
            } else {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            }
        });
    });
</script>
@endpush