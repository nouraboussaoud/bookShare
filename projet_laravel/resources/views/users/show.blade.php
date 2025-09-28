@extends('layouts.layout')

@section('title', 'Profil de ' . $user->name)

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user text-primary mr-2"></i>
                Profil de {{ $user->name }}
            </h1>
            <p class="mb-0 text-gray-600">Informations et activité de l'utilisateur</p>
        </div>
        <div class="d-flex gap-2">
            @if(Auth::check() && Auth::id() !== $user->id)
                <button type="button" onclick="openReportModal('user', {{ $user->id }})" 
                        class="btn btn-warning shadow-sm" 
                        title="Signaler cet utilisateur">
                    <i class="fas fa-flag mr-1"></i>Signaler
                </button>
            @endif
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-4">
            <!-- User Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user fa-sm text-primary"></i> Informations utilisateur
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="icon-circle bg-primary mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="font-weight-bold">{{ $user->name }}</h5>
                        <p class="text-gray-600">{{ $user->email }}</p>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mb-2">
                                <h6 class="font-weight-bold text-primary">{{ $user->books()->count() }}</h6>
                                <small class="text-gray-600">Livres</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-2">
                                <h6 class="font-weight-bold text-success">{{ $user->exchangesAsInitiator()->where('status', 'TERMINE')->count() + $user->exchangesAsRecepteur()->where('status', 'TERMINE')->count() }}</h6>
                                <small class="text-gray-600">Échanges</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-gray-500">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Membre depuis {{ $user->created_at->format('F Y') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- User Books -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book fa-sm text-primary"></i> Livres de {{ $user->name }}
                    </h6>
                </div>
                <div class="card-body">
                    @if($user->books()->count() > 0)
                        <div class="row">
                            @foreach($user->books()->latest()->limit(6)->get() as $book)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100">
                                        @if($book->image)
                                            <img src="{{ asset('storage/' . $book->image) }}" class="card-img-top" alt="{{ $book->title }}" style="height: 150px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                                <i class="fas fa-book fa-3x text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div class="card-body p-2">
                                            <h6 class="card-title font-weight-bold" style="font-size: 0.9rem;">{{ Str::limit($book->title, 30) }}</h6>
                                            <p class="card-text text-gray-600" style="font-size: 0.8rem;">{{ Str::limit($book->author, 25) }}</p>
                                            <span class="badge badge-{{ $book->status === 'DISPONIBLE' ? 'success' : 'secondary' }} badge-sm">
                                                {{ $book->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($user->books()->count() > 6)
                            <div class="text-center mt-3">
                                <a href="{{ route('books.index', ['user' => $user->id]) }}" class="btn btn-outline-primary">
                                    Voir tous les livres ({{ $user->books()->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-3x text-gray-300 mb-3"></i>
                            <p class="text-gray-600">Cet utilisateur n'a pas encore ajouté de livres.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-star fa-sm text-primary"></i> Avis récents
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $reviews = $user->reviews()->with('book')->latest()->limit(3)->get();
                    @endphp
                    
                    @if($reviews->count() > 0)
                        @foreach($reviews as $review)
                            <div class="media mb-3">
                                <div class="media-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mt-0 font-weight-bold">{{ $review->book->title ?? 'Livre supprimé' }}</h6>
                                            <div class="mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-gray-300' }}"></i>
                                                @endfor
                                                <span class="ml-2 text-gray-600">{{ $review->rating }}/5</span>
                                            </div>
                                            <p class="mb-1">{{ Str::limit($review->comment, 100) }}</p>
                                        </div>
                                        <small class="text-gray-500">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-star fa-3x text-gray-300 mb-3"></i>
                            <p class="text-gray-600">Cet utilisateur n'a pas encore laissé d'avis.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add any profile-specific JavaScript here
});
</script>
@endpush