@extends('layouts.layout')

@section('title', 'Groupes de Lecture')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Groupes de Lecture</h6>
                <a href="{{ route('reading-groups.create') }}" class="btn btn-sm btn-primary">Créer un Groupe</a>
            </div>
            <div class="card-body">
                @if ($groups->count() === 0)
                    <div class="text-center">
                        <p class="text-muted mb-4">Aucun groupe pour le moment. Créez-en un !</p>
                        <a href="{{ route('reading-groups.create') }}" class="btn btn-primary">Nouveau Groupe</a>
                    </div>
                @else
                    <div class="row">
                        @foreach ($groups as $group)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $group->name }}</h5>
                                        <p class="card-text text-truncate">{{ $group->description ?? 'Aucune description' }}</p>
                                        <p class="card-text"><small class="text-muted">Membres: {{ $group->members_count }}</small></p>
                                        <p class="card-text"><small class="text-muted">Privé: {{ $group->is_private ? 'Oui' : 'Non' }}</small></p>
                                    </div>
                                    <div class="card-footer">
                                        <a href="{{ route('reading-groups.show', $group) }}" class="btn btn-sm btn-outline-primary">Voir Détails</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{ $groups->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection