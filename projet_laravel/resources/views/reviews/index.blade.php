@extends('layouts.layout')
@section('title', 'Mes Avis')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Mes Avis sur les Livres</h1>
        <p class="mb-0 text-gray-600">Gérez les avis et évaluations des livres</p>
    </div>
    <a href="{{ route('reviews.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Nouvel Avis
    </a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Reviews Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Avis</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Livre</th>
                            <th>Auteur du Livre</th>
                            <th>Catégorie</th>
                            <th>Note</th>
                            <th>Commentaire</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>
                                    <strong>{{ $review->book->title }}</strong>
                                    <br>
                                    <small class="text-muted">par {{ $review->book->user->name }}</small>
                                </td>
                                <td>{{ $review->book->author }}</td>
                                <td>
                                    @if($review->book->category)
                                        <span class="badge" style="background-color: {{ $review->book->category->color }}; color: white;">
                                            {{ $review->book->category->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">Aucune</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-muted"></i>
                                            @endif
                                        @endfor
                                        <span class="ml-1">({{ $review->rating }}/5)</span>
                                    </div>
                                </td>
                                <td>
                                    @if($review->comment)
                                        {{ Str::limit($review->comment, 50) }}
                                    @else
                                        <span class="text-muted">Aucun commentaire</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($review->status)
                                        @case('PENDING')
                                            <span class="badge badge-warning">En attente</span>
                                            @break
                                        @case('APPROVED')
                                            <span class="badge badge-success">Approuvé</span>
                                            @break
                                        @case('REJECTED')
                                            <span class="badge badge-danger">Rejeté</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $review->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('reviews.show', $review) }}" class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($review->user_id == Auth::id())
                                            <a href="{{ route('reviews.edit', $review) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre avis ?');" 
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            @if(Auth::user()->isAdmin() && $review->status == 'PENDING')
                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal{{ $review->id }}" tabindex="-1" role="dialog">
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
                                                        <textarea class="form-control" name="admin_reply" rows="3"></textarea>
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
                                <div class="modal fade" id="rejectModal{{ $review->id }}" tabindex="-1" role="dialog">
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
                                                        <textarea class="form-control" name="admin_reply" rows="3" required></textarea>
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
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    @if(Auth::user()->isAdmin())
                                        Aucun avis trouvé dans le système.
                                    @else
                                        <div>
                                            <i class="fas fa-star fa-3x mb-3 text-muted"></i><br>
                                            <strong>Vous n'avez pas encore créé d'avis.</strong><br>
                                            <small>Parcourez les livres disponibles et laissez votre premier avis !</small><br>
                                            <a href="{{ route('user.dashboard') }}" class="btn btn-primary btn-sm mt-2">
                                                <i class="fas fa-book"></i> Voir les livres
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($reviews->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
