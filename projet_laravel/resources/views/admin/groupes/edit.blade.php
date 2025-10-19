@extends('layouts.admin-layout')
@section('title', 'Modifier le Groupe')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Modifier le Groupe</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.groupes.update', $group) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $group->name) }}" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control">{{ old('description', $group->description) }}</textarea>
                </div>

                <button class="btn btn-primary">Enregistrer</button>
                <a href="{{ route('admin.groupes.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
@endsection
