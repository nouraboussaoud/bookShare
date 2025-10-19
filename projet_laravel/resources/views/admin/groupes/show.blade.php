@extends('layouts.admin-layout')
@section('title', 'Détails du Groupe')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Détails du Groupe</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $group->id }}</p>
            <p><strong>Nom:</strong> {{ $group->name }}</p>
            <p><strong>Description:</strong> {{ $group->description }}</p>
            <a href="{{ route('admin.groupes.edit', $group) }}" class="btn btn-info">Modifier</a>
            <a href="{{ route('admin.groupes.index') }}" class="btn btn-secondary">Retour</a>
        </div>
    </div>
@endsection
