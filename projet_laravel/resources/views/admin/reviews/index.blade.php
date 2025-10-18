@extends('layouts.admin-layout')

@section('title', 'Gestion des Avis - Admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📝 Gestion des Avis</h1>
    <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Créer un Avis
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tous les Avis ({{ $reviews->total() }})</h6>
    </div>
    <div class="card-body">
        @if($reviews->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Livre</th>
                            <th>Utilisateur</th>
                            <th>Note</th>
                            <th>Commentaire</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                <td>{{ $review->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($review->book->photo)
                                            <img src="{{ $review->book->photo_url }}" 
                                                 class="rounded mr-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded mr-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-book text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ Str::limit($review->book->title, 30) }}</strong><br>
                                            <small class="text-muted">par {{ $review->book->author }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $review->user ? $review->user->name : 'Utilisateur supprimé' }}</strong><br>
                                        <small class="text-muted">{{ $review->user ? $review->user->email : 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
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
                                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.reviews.show', $review) }}" 
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <a href="{{ route('admin.reviews.edit', $review) }}" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        @if($review->status == 'PENDING')
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    data-toggle="modal" data-target="#approveModal{{ $review->id }}" 
                                                    title="Approuver">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-toggle="modal" data-target="#rejectModal{{ $review->id }}" 
                                                    title="Rejeter">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                        
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?');" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            @if($review->status == 'PENDING')
                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal{{ $review->id }}" tabindex="-1" role="dialog">
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
                                <div class="modal fade" id="rejectModal{{ $review->id }}" tabindex="-1" role="dialog">
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
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($reviews->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $reviews->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-4">
                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Aucun avis trouvé</h5>
                <p class="text-muted">Les avis des utilisateurs apparaîtront ici.</p>
                <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer le premier avis
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
