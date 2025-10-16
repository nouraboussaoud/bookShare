@extends('layouts.layout')

@section('title', 'Mes Livres')

@section('content')
@php
    $isAdmin = auth()->check() ? auth()->user()->isAdmin() : false;
    $isOthers = isset($scope) && $scope == 'others';
    $myBooksCount = auth()->check() ? \App\Models\Book::where('user_id', auth()->id())->count() : 0;
    $totalBooks = \App\Models\Book::count();
    $availableBooks = \App\Models\Book::where('status', 'available')->count();
    $reservedBooks = \App\Models\Book::where('status', 'reserved')->count();
@endphp

<!-- Header "Welcome to ShareBooks" -->
<div class="mb-5">
    <h1 class="text-center mb-4" style="font-family: 'Poppins', sans-serif; font-size: 2.5rem; font-weight: 600; color: #1e293b;">
        Bienvenue sur BookShare
    </h1>
    
    <div class="card smooth" style="overflow: hidden; border-radius: 0px;">
        <div class="row g-0">
            <!-- Image à gauche -->
            <div class="col-md-5 d-none d-md-block" style="background: #f5f5f5; position: relative; min-height: 300px;">
                <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=600&h=400&fit=crop" 
                     alt="Open book" 
                     style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            
            <!-- Statistiques à droite -->
            <div class="col-md-7" style="background: #f8f9fa;">
                <div class="p-4 p-md-5">
                    <!-- Statistiques compactes -->
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="p-3 bg-white rounded shadow-sm text-center">
                                <div class="text-uppercase text-muted small mb-1" style="font-weight: 600; font-size: 0.7rem; letter-spacing: 0.5px;">Mes Livres</div>
                                <div class="h3 mb-0 fw-bold" style="color: #667eea;">
                                    <i class="fas fa-book me-2" style="font-size: 1.2rem;"></i>{{ $myBooksCount }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-white rounded shadow-sm text-center">
                                <div class="text-uppercase text-muted small mb-1" style="font-weight: 600; font-size: 0.7rem; letter-spacing: 0.5px;">Total Livres</div>
                                <div class="h3 mb-0 fw-bold" style="color: #667eea;">
                                    <i class="fas fa-books me-2" style="font-size: 1.2rem;"></i>{{ $totalBooks }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('books.create') }}" class="btn btn-purple btn-lg" style="border-radius: 0px; padding: 0.75rem 2rem; font-weight: 500;">
                            <i class="fas fa-plus me-2"></i>Ajouter un livre
                        </a>
                        @auth
                            @if(!$isAdmin && !$isOthers)
                            <a href="{{ route('books.index', ['scope' => 'others']) }}" class="btn btn-outline-purple btn-lg" style="border-radius: 0px; padding: 0.75rem 2rem; font-weight: 500;">
                                <i class="fas fa-users me-2"></i>Mes Livres
                            </a>
                            @elseif($isOthers)
                            <a href="{{ route('books.index') }}" class="btn btn-outline-purple btn-lg" style="border-radius: 0px; padding: 0.75rem 2rem; font-weight: 500;">
                                <i class="fas fa-book me-2"></i>Mes livres
                            </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                        <span class="badge badge-purple">{{ $book->age_display }}</span>
                    </div>
                    
                    <p class="card-text small text-purple mb-2">
                        <i class="fas fa-user"></i> {{ $book->user?->name ?? 'N/A' }}
                    </p>
                    
                    @if($book->description)
                        <p class="card-text small text-muted mb-3">
                            {{ Str::limit($book->description, 60) }}
                        </p>
                    @endif
                    
                    <div class="mt-auto">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-purple" title="Voir détails">
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
                                           class="btn btn-sm btn-outline-purple" title="Donner un avis">
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
                    <a href="{{ route('books.create') }}" class="btn btn-purple">
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

@push('styles')
<style>
    .book-card {
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
        border-radius: 0px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .book-card .card-img-top {
        transition: transform 0.3s ease;
    }
    
    .book-card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .btn-group .btn {
        border-radius: 0;
        font-size: 0.8rem;
    }
    
    .btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }
    
    .badge {
        font-size: 0.7rem;
        font-weight: 500;
    }
    
    .card-title a {
        color: #2c3e50;
        font-weight: 600;
    }
    
    .card-title a:hover {
        color: #667eea;
    }
</style>
@endpush