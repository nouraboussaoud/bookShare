@extends('layouts.layout')

@section('title', 'Modifier un livre')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Modifier Livre #{{ $book->id }}</h6>
                <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-secondary">Retour</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('books.update', $book) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $book->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Auteur</label>
                        <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author', $book->author) }}" required>
                        @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="AVAILABLE" {{ old('status', $book->status)==='AVAILABLE' ? 'selected' : '' }}>AVAILABLE</option>
                            <option value="RESERVED" {{ old('status', $book->status)==='RESERVED' ? 'selected' : '' }}>RESERVED</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
