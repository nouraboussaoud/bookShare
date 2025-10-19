@extends('layouts.app')

@section('title', 'Edit Event - ' . $readingGroup->name)

@push('styles')
<style>
    .form-card { border-radius:.6rem; box-shadow:0 6px 18px rgba(0,0,0,.04); }
    .required { color: #dc3545; margin-left:.15rem; }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card form-card mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-edit me-2"></i> Modifier l'événement
                    </h6>
                    <a href="{{ route('reading-groups.events.show', [$readingGroup, $event]) }}" class="btn btn-sm btn-outline-secondary">
                        Retour
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('reading-groups.events.update', [$readingGroup, $event]) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Titre de l'événement <span class="required">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $event->title) }}" required maxlength="255">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      maxlength="1000">{{ old('description', $event->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Maximum 1000 caractères</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date <span class="required">*</span></label>
                                <input type="date" name="event_date" class="form-control @error('event_date') is-invalid @enderror"
                                       value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                                @error('event_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Heure</label>
                                <input type="time" name="event_time" class="form-control @error('event_time') is-invalid @enderror"
                                       value="{{ old('event_time', $event->event_time?->format('H:i')) }}">
                                @error('event_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lieu</label>
                                <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                       value="{{ old('location', $event->location) }}" maxlength="255">
                                @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre maximum de participants</label>
                                <input type="number" name="max_attendees" class="form-control @error('max_attendees') is-invalid @enderror"
                                       value="{{ old('max_attendees', $event->max_attendees) }}" min="1">
                                @error('max_attendees') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Durée de l'événement</label>
                            <select name="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror">
                                <option value="60" {{ old('duration_minutes', $event->duration_minutes ?? 120) == 60 ? 'selected' : '' }}>1 heure</option>
                                <option value="90" {{ old('duration_minutes', $event->duration_minutes ?? 120) == 90 ? 'selected' : '' }}>1 heure 30 minutes</option>
                                <option value="120" {{ old('duration_minutes', $event->duration_minutes ?? 120) == 120 ? 'selected' : '' }}>2 heures</option>
                                <option value="150" {{ old('duration_minutes', $event->duration_minutes ?? 120) == 150 ? 'selected' : '' }}>2 heures 30 minutes</option>
                                <option value="180" {{ old('duration_minutes', $event->duration_minutes ?? 120) == 180 ? 'selected' : '' }}>3 heures</option>
                                <option value="240" {{ old('duration_minutes', $event->duration_minutes ?? 120) == 240 ? 'selected' : '' }}>4 heures</option>
                            </select>
                            @error('duration_minutes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Durée estimée de l'événement</div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Sauvegarder les modifications
                            </button>
                            <a href="{{ route('reading-groups.events.show', [$readingGroup, $event]) }}" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
