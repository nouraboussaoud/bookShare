@extends('layouts.layout')

@section('title', 'Modifier le Groupe de Lecture')

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
                    <h6 class="m-0 fw-bold text-primary">Modifier "{{ $readingGroup->name }}"</h6>
                    <a href="{{ route('reading-groups.show', $readingGroup) }}" class="btn btn-sm btn-outline-secondary">Retour</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('reading-groups.update', $readingGroup) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nom <span class="required">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $readingGroup->name) }}"
                                   required maxlength="255" placeholder="Nom du groupe">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="5"
                                      class="form-control @error('description') is-invalid @enderror"
                                      maxlength="1000" placeholder="Décrivez le groupe, ses thèmes, règles...">{{ old('description', $readingGroup->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Maximum 1000 caractères</div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="hidden" name="is_private" value="0">
                            <input type="checkbox" id="is_private" name="is_private" value="1"
                                   class="form-check-input @error('is_private') is-invalid @enderror"
                                   {{ old('is_private', $readingGroup->is_private) ? 'checked' : '' }}>
                            <label for="is_private" class="form-check-label">Groupe Privé</label>
                            @error('is_private') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Les groupes privés nécessitent une approbation ou une invitation.</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            <a href="{{ route('reading-groups.show', $readingGroup) }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
