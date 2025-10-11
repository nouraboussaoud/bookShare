@extends('layouts.layout')

@section('title', 'Détails de l\'Avis - Admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📝 Détails de l'Avis #{{ $review->id }}</h1>
    <div>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
        <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row">
    <!-- Review Details -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Détails de l'Avis</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>ID:</strong></div>
                    <div class="col-sm-9">{{ $review->id }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Utilisateur:</strong></div>
                    <div class="col-sm-9">
                        @if($review->user)
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <strong>{{ $review->user->name }}</strong><br>
                                    <small class="text-muted">{{ $review->user->email }}</small>
                                </div>
                            </div>
                        @else
                            <span class="text-muted">Utilisateur supprimé</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Note:</strong></div>
                    <div class="col-sm-9">
                        <div class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                            @endfor
                            <span class="ml-2 font-weight-bold">{{ $review->rating }}/5</span>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Commentaire:</strong></div>
                    <div class="col-sm-9">
                        @if($review->comment)
                            <div class="bg-light p-3 rounded">
                                {{ $review->comment }}
                            </div>
                        @else
                            <span class="text-muted">Aucun commentaire</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Statut:</strong></div>
                    <div class="col-sm-9">
                        @switch($review->status)
                            @case('PENDING')
                                <span class="badge badge-warning badge-lg">En attente</span>
                                @break
                            @case('APPROVED')
                                <span class="badge badge-success badge-lg">Approuvé</span>
                                @break
                            @case('REJECTED')
                                <span class="badge badge-danger badge-lg">Rejeté</span>
                                @break
                        @endswitch
                    </div>
                </div>

                @if($review->admin_reply)
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Réponse Admin:</strong></div>
                    <div class="col-sm-9">
                        <div class="bg-info text-white p-3 rounded">
                            <i class="fas fa-user-shield"></i> {{ $review->admin_reply }}
                        </div>
                    </div>
                </div>
                @endif

                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Date de création:</strong></div>
                    <div class="col-sm-9">{{ $review->created_at->format('d/m/Y à H:i') }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Dernière modification:</strong></div>
                    <div class="col-sm-9">{{ $review->updated_at->format('d/m/Y à H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Admin Actions -->
        @if($review->status == 'PENDING')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actions Administrateur</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-success btn-block" 
                                data-toggle="modal" data-target="#approveModal">
                            <i class="fas fa-check"></i> Approuver l'avis
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-danger btn-block" 
                                data-toggle="modal" data-target="#rejectModal">
                            <i class="fas fa-times"></i> Rejeter l'avis
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Book Details -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Livre Concerné</h6>
            </div>
            <div class="card-body">
                @if($review->book->photo)
                    <img src="{{ $review->book->photo_url }}" class="card-img-top mb-3" 
                         alt="{{ $review->book->title }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center mb-3" 
                         style="height: 200px;">
                        <i class="fas fa-book fa-3x text-muted"></i>
                    </div>
                @endif

                <h5 class="card-title">{{ $review->book->title }}</h5>
                <p class="card-text">
                    <strong>Auteur:</strong> {{ $review->book->author }}<br>
                    <strong>Propriétaire:</strong> {{ $review->book->user->name }}<br>
                    @if($review->book->category)
                        <strong>Catégorie:</strong> 
                        <span class="badge" style="background-color: {{ $review->book->category->color }}; color: white;">
                            {{ $review->book->category->name }}
                        </span><br>
                    @endif
                    <strong>Statut:</strong> 
                    <span class="badge badge-{{ $review->book->status == 'available' ? 'success' : 'warning' }}">
                        {{ $review->book->status == 'available' ? 'Disponible' : 'Réservé' }}
                    </span>
                </p>

                <a href="{{ route('books.show', $review->book) }}" class="btn btn-primary btn-block">
                    <i class="fas fa-eye"></i> Voir le livre
                </a>
            </div>
        </div>
    </div>
</div>

@if($review->status == 'PENDING')
<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approuver l'avis</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir approuver cet avis ?</p>
                    <div class="form-group">
                        <label>Réponse admin (optionnelle)</label>
                        <textarea name="admin_reply" class="form-control" rows="3" 
                                  placeholder="Réponse ou commentaire de l'administrateur..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Approuver</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rejeter l'avis</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir rejeter cet avis ?</p>
                    <div class="form-group">
                        <label>Raison du rejet <span class="text-danger">*</span></label>
                        <textarea name="admin_reply" class="form-control" rows="3" 
                                  placeholder="Expliquez pourquoi cet avis est rejeté..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection