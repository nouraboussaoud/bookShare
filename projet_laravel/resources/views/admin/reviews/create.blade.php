@extends('layouts.admin-layout')

@section('title', 'Créer un Avis - Admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📝 Créer un Nouvel Avis</h1>
    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>
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
    <!-- Create Form -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informations de l'Avis</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reviews.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="book_id">Livre <span class="text-danger">*</span></label>
                        <select name="book_id" id="book_id" class="form-control @error('book_id') is-invalid @enderror" required>
                            <option value="">Sélectionner un livre</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }} - par {{ $book->author }} ({{ $book->user->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('book_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="user_id">Utilisateur <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                            <option value="">Sélectionner un utilisateur</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="rating">Note <span class="text-danger">*</span></label>
                        <select name="rating" id="rating" class="form-control @error('rating') is-invalid @enderror" required>
                            <option value="">Sélectionner une note</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
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
                                  placeholder="Commentaire de l'utilisateur sur le livre...">{{ old('comment') }}</textarea>
                        @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Statut <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="PENDING" {{ old('status') == 'PENDING' ? 'selected' : '' }}>
                                En attente
                            </option>
                            <option value="APPROVED" {{ old('status') == 'APPROVED' ? 'selected' : '' }}>
                                Approuvé
                            </option>
                            <option value="REJECTED" {{ old('status') == 'REJECTED' ? 'selected' : '' }}>
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
                                  placeholder="Réponse ou commentaire de l'administrateur...">{{ old('admin_reply') }}</textarea>
                        @error('admin_reply')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Cette réponse sera visible par l'utilisateur sélectionné.
                        </small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Créer l'Avis
                        </button>
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Help Info -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-info-circle"></i> Aide
                </h6>
            </div>
            <div class="card-body">
                <h6>Création d'un avis</h6>
                <p class="text-muted small">
                    En tant qu'administrateur, vous pouvez créer des avis au nom des utilisateurs. 
                    Ceci peut être utile pour :
                </p>
                <ul class="text-muted small">
                    <li>Migrer des avis depuis un ancien système</li>
                    <li>Créer des avis de démonstration</li>
                    <li>Corriger des erreurs de saisie</li>
                </ul>
                
                <hr>
                
                <h6>Statuts disponibles</h6>
                <div class="mb-2">
                    <span class="badge badge-warning">En attente</span>
                    <small class="text-muted d-block">L'avis attend une validation</small>
                </div>
                <div class="mb-2">
                    <span class="badge badge-success">Approuvé</span>
                    <small class="text-muted d-block">L'avis est visible publiquement</small>
                </div>
                <div class="mb-2">
                    <span class="badge badge-danger">Rejeté</span>
                    <small class="text-muted d-block">L'avis a été refusé</small>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-exclamation-triangle"></i> Attention
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted small">
                    <strong>Règle importante :</strong> Un seul avis par livre est autorisé. 
                    Si le livre sélectionné a déjà un avis, la création échouera.
                </p>
                <p class="text-muted small">
                    Vérifiez d'abord si le livre n'a pas déjà été évalué avant de créer un nouvel avis.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-select status based on admin reply
    const statusSelect = document.getElementById('status');
    const adminReplyTextarea = document.getElementById('admin_reply');
    
    adminReplyTextarea.addEventListener('input', function() {
        if (this.value.trim() !== '' && statusSelect.value == 'PENDING') {
            statusSelect.value = 'APPROVED';
        }
    });
});
</script>
@endsection
