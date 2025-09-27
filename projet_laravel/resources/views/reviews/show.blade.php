@extends('layouts.layout')
@section('title', 'Détails de l\'Avis')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Détails de l'Avis</h1>
            <p class="mb-0 text-gray-600">Avis pour "{{ $review->book->title }}"</p>
        </div>
        <a href="{{ route('reviews.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Review Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Détails de l'Avis</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Livre Évalué</h5>
                            <p><strong>{{ $review->book->title }}</strong></p>
                            <p class="text-muted">par {{ $review->book->author }}</p>
                            @if($review->book->category)
                                <span class="badge" style="background-color: {{ $review->book->category->color }}; color: white;">
                                    {{ $review->book->category->name }}
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h5>Propriétaire du Livre</h5>
                            <p>{{ $review->book->user->name }}</p>
                            <p class="text-muted">{{ $review->book->user->email }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Note</h5>
                            <div class="rating mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star text-warning" style="font-size: 1.5rem;"></i>
                                    @else
                                        <i class="far fa-star text-muted" style="font-size: 1.5rem;"></i>
                                    @endif
                                @endfor
                                <span class="ml-2 h5">{{ $review->rating }}/5</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Statut</h5>
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
                    
                    @if($review->comment)
                        <hr>
                        <h5>Commentaire</h5>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{{ $review->comment }}</p>
                        </div>
                    @endif
                    
                    @if($review->admin_reply)
                        <hr>
                        <h5>Réponse de l'Administrateur</h5>
                        <div class="bg-info text-white p-3 rounded">
                            <p class="mb-0">{{ $review->admin_reply }}</p>
                        </div>
                    @endif
                    
                    <hr>
                    <small class="text-muted">
                        Avis créé le {{ $review->created_at->format('d/m/Y à H:i') }}
                        @if($review->updated_at != $review->created_at)
                            • Modifié le {{ $review->updated_at->format('d/m/Y à H:i') }}
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    @if($review->status == 'PENDING')
                        <a href="{{ route('reviews.edit', $review) }}" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit"></i> Modifier l'Avis
                        </a>
                        
                        @if(Auth::user()->isAdmin())
                            <button type="button" class="btn btn-success btn-block mb-2" 
                                    data-toggle="modal" data-target="#approveModal">
                                <i class="fas fa-check"></i> Approuver
                            </button>
                            <button type="button" class="btn btn-danger btn-block mb-2" 
                                    data-toggle="modal" data-target="#rejectModal">
                                <i class="fas fa-times"></i> Rejeter
                            </button>
                        @endif
                        
                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-block">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            Cet avis a été traité et ne peut plus être modifié.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Book Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations du Livre</h6>
                </div>
                <div class="card-body">
                    <p><strong>Statut:</strong> 
                        <span class="badge {{ $review->book->status == 'AVAILABLE' ? 'badge-success' : 'badge-warning' }}">
                            {{ $review->book->status }}
                        </span>
                    </p>
                    @if($review->book->category)
                        <p><strong>Âge recommandé:</strong> 
                            @if($review->book->category->age_allowed == 0)
                                Tout âge
                            @else
                                {{ $review->book->category->age_allowed }}+
                            @endif
                        </p>
                    @endif
                    <p><strong>Ajouté le:</strong> {{ $review->book->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->isAdmin() && $review->status == 'PENDING')
        <!-- Approve Modal -->
        <div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title">Approuver l'avis</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Êtes-vous sûr de vouloir approuver cet avis ?</p>
                            <div class="form-group">
                                <label for="admin_reply">Réponse de l'administrateur (optionnel)</label>
                                <textarea class="form-control" name="admin_reply" rows="3" 
                                          placeholder="Merci pour votre avis constructif..."></textarea>
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
                    <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title">Rejeter l'avis</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Pourquoi rejetez-vous cet avis ?</p>
                            <div class="form-group">
                                <label for="admin_reply">Raison du rejet <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="admin_reply" rows="3" required
                                          placeholder="Veuillez expliquer pourquoi cet avis ne respecte pas nos conditions..."></textarea>
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
