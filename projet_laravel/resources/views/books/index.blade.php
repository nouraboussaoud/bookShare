@extends('layouts.layout')

@section('title', 'Mes Livres')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 text-gray-800 mb-2">
            @auth
                @php($isAdmin = auth()->user()->isAdmin())
                @if($isAdmin)
                    {{ ($scope ?? null) === 'others' ? 'Livres de la communauté' : 'Tous les livres' }}
                @else
                    {{ ($scope ?? null) === 'others' ? 'Livres de la communauté' : 'Mes livres' }}
                @endif
            @endauth
        </h1>
        @auth
            <div class="btn-group" role="group">
                @if(!$isAdmin)
                    <a href="{{ route('books.index') }}" class="btn btn-sm {{ ($scope ?? null) === 'others' ? 'btn-outline-secondary' : 'btn-secondary' }}">Mes livres</a>
                @endif
                <a href="{{ route('books.index', ['scope' => 'others']) }}" class="btn btn-sm {{ ($scope ?? null) === 'others' ? 'btn-secondary' : 'btn-outline-secondary' }}">Livres de la communauté</a>
                @if($isAdmin)
                    <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-secondary">Tous</a>
                @endif
            </div>
        @endauth
    </div>
    <a href="{{ route('books.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Ajouter un livre</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Status</th>
                        <th>Propriétaire</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr>
                            <td>{{ $book->id }}</td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            <td>
                                <span class="badge {{ $book->status === 'AVAILABLE' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $book->status }}</span>
                            </td>
                            <td>{{ $book->user?->name }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('books.toggle-status', $book) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-info" title="Changer statut">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                </form>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Supprimer ce livre ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucun livre trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>
            {{ $books->links() }}
        </div>
    </div>
</div>
@endsection
