@extends('layouts.layout')

@section('title', 'Modifier l\'Avis - Admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📝 Modifier l'Avis #{{ $review->id }}</h1>
    <div>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
        <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-info">
            <i class="fas fa-eye"></i> Voir détails
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <!-- Edit Form -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Modifier l'Avis</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reviews.update', $review) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="rating">Note <span class="text-danger">*</span></label>
                        <select name="rating" id="rating" class="form-control @error('rating') is-invalid @enderror" required>
                            <option value="">Sélectionner une note</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('rating', $review->rating) == $i ? 'selected' : '' }}>
                                    {{ $i }} étoile{{ $i > 1 ? 's' : '' }}
                                </option>
                            @endfor
                        </select>
                        @error('rating')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="comment">Commentaire</label>
                        <textarea name="comment" id="comment" rows="4" 
                                  class="form-control @error('comment') is-invalid @enderror"
                                  placeholder="Commentaire de l'utilisateur sur le livre...">{{ old('comment', $review->comment) }}</textarea>
                        @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Statut <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="PENDING" {{ old('status', $review->status) == 'PENDING' ? 'selected' : '' }}>
                                En attente
                            </option>
                            <option value="APPROVED" {{ old('status', $review->status) == 'APPROVED' ? 'selected' : '' }}>
                                Approuvé
                            </option>
                            <option value="REJECTED" {{ old('status', $review->status) == 'REJECTED' ? 'selected' : '' }}>
                                Rejeté
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="admin_reply">Réponse Administrateur</label>
                        <textarea name="admin_reply" id="admin_reply" rows="3" 
                                  class="form-control @error('admin_reply') is-invalid @enderror"
                                  placeholder="Réponse ou commentaire de l'administrateur...">{{ old('admin_reply', $review->admin_reply) }}</textarea>
                        @error('admin_reply')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Cette réponse sera visible par l'utilisateur qui a créé l'avis.
                        </small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-secondary">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Review Info -->
    <div class="col-lg-4">
        <!-- Book Info -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Livre Concerné</h6>
            </div>
            <div class="card-body">
                @if($review->book->photo)
                    <img src="{{ $review->book->photo_url }}" class="card-img-top mb-3" 
                         alt="{{ $review->book->title }}" style="height: 150px; object-fit: cover;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center mb-3" 
                         style="height: 150px;">
                        <i class="fas fa-book fa-2x text-muted"></i>
                    </div>
                @endif

                <h6 class="card-title">{{ $review->book->title }}</h6>
                <p class="card-text">
                    <small class="text-muted">par {{ $review->book->author }}</small><br>
                    <small class="text-muted">Propriétaire: {{ $review->book->user->name }}</small>
                </p>
            </div>
        </div>

        <!-- User Info -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Utilisateur</h6>
            </div>
            <div class="card-body">
                @if($review->user)
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $review->user->name }}</h6>
                            <small class="text-muted">{{ $review->user->email }}</small>
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-user-slash fa-2x mb-2"></i>
                        <p>Utilisateur supprimé</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Review History -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Historique</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted">Créé le:</small><br>
                    <strong>{{ $review->created_at->format('d/m/Y à H:i') }}</strong>
                </div>
                <div>
                    <small class="text-muted">Modifié le:</small><br>
                    <strong>{{ $review->updated_at->format('d/m/Y à H:i') }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
