@extends('layouts.layout')

@section('title', 'Mes Livres')

@section('content')
@php
    $isAdmin = auth()->check() ? auth()->user()->isAdmin() : false;
    $isOthers = isset($scope) && $scope == 'others';
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 text-gray-800 mb-2">
            @auth
                @if($isAdmin)
                    {{ $isOthers ? 'Livres de la communauté' : 'Tous les livres' }}
                @else
                    {{ $isOthers ? 'Livres de la communauté' : 'Mes livres' }}
                @endif
            @endauth
        </h1>
        @auth
            <div class="btn-group" role="group">
                @if(!$isAdmin)
                    <a href="{{ route('books.index') }}" class="btn btn-sm {{ $isOthers ? 'btn-outline-secondary' : 'btn-secondary' }}">Mes livres</a>
                @endif
                <a href="{{ route('books.index', ['scope' => 'others']) }}" class="btn btn-sm {{ $isOthers ? 'btn-secondary' : 'btn-outline-secondary' }}">Livres de la communauté</a>
                @if($isAdmin)
                    <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-secondary">Tous</a>
                @endif
            </div>
        @endauth
    </div>
    <a href="{{ route('books.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Ajouter un livre</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    @forelse($books as $book)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm book-card">
                @if($book->photo)
                    <img src="{{ $book->photo_url }}" class="card-img-top" alt="{{ $book->title }}" 
                         style="height: 250px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 250px;">
                        <i class="fas fa-book fa-4x text-muted"></i>
                    </div>
                @endif
                
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0">
                            <a href="{{ route('books.show', $book) }}" class="text-decoration-none">
                                {{ Str::limit($book->title, 20) }}
                            </a>
                        </h6>
                        @if($book->category)
                            <span class="badge" style="background-color: {{ $book->category->color }}; color: white; font-size: 0.7rem;">
                                {{ $book->category->name }}
                            </span>
                        @endif
                    </div>
                    
                    <p class="card-text text-muted mb-2 small">par {{ $book->author }}</p>
                    
                    <div class="mb-2">
                        <span class="badge badge-{{ $book->status == 'available' ? 'success' : 'warning' }}">
                            {{ $book->status == 'available' ? 'Disponible' : 'Réservé' }}
                        </span>
                        <span class="badge badge-info">{{ $book->age_display }}</span>
                    </div>
                    
                    <p class="card-text small text-info mb-2">
                        <i class="fas fa-user"></i> {{ $book->user?->name ?? 'N/A' }}
                    </p>
                    
                    @if($book->description)
                        <p class="card-text small text-muted mb-3">
                            {{ Str::limit($book->description, 60) }}
                        </p>
                    @endif
                    
                    <div class="mt-auto">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-info" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            @auth
                                @if(auth()->user()->isAdmin() || auth()->id() == $book->user_id)
                                    <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('books.toggleStatus', $book) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Changer le statut">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('books.destroy', $book) }}" method="POST" 
                                          onsubmit="return confirm('Supprimer ce livre ?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    @php
                                        $userReview = $book->reviews->where('user_id', Auth::id())->first();
                                    @endphp
                                    @if(!$userReview)
                                        <a href="{{ route('reviews.create', ['book_id' => $book->id]) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Donner un avis">
                                            <i class="fas fa-star"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('reviews.edit', $userReview) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Modifier mon avis">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun livre trouvé</h5>
                    <p class="text-muted">
                        @if($isOthers)
                            La communauté n'a pas encore partagé de livres.
                        @else
                            Vous n'avez pas encore ajouté de livres.
                        @endif
                    </p>
                    <a href="{{ route('books.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter votre premier livre
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($books->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $books->links() }}
    </div>
@endif

@endsection
