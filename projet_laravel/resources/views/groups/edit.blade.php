@extends('layouts.layout')

@section('title', 'Modifier le Groupe de Lecture')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Modifier "{{ $readingGroup->name }}"</h6>
                <a href="{{ route('reading-groups.show', $readingGroup) }}" class="btn btn-sm btn-outline-secondary">Retour</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('reading-groups.update', $readingGroup) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $readingGroup->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Décrivez le groupe, ses thèmes, règles...">{{ old('description', $readingGroup->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="form-text text-muted">Maximum 1000 caractères</small>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_private" value="1" class="form-check-input @error('is_private') is-invalid @enderror" {{ old('is_private', $readingGroup->is_private) ? 'checked' : '' }}>
                        <label class="form-check-label">Groupe Privé</label>
                        @error('is_private')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="form-text text-muted">Les groupes privés nécessitent une approbation ou une invitation.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer les Modifications</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection