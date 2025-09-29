@extends('layouts.layout')

@section('title', $readingGroup->name)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">{{ $readingGroup->name }}</h6>
                <a href="{{ route('reading-groups.index') }}" class="btn btn-sm btn-outline-secondary">Retour</a>
            </div>
            <div class="card-body">
                <p><strong>Description:</strong> {{ $readingGroup->description ?? 'Aucune description' }}</p>
                <p><strong>Privé:</strong> {{ $readingGroup->is_private ? 'Oui' : 'Non' }}</p>
                <p><strong>Propriétaire:</strong> {{ $readingGroup->owner->name }}</p>
                <p><strong>Créé le:</strong> {{ $readingGroup->created_at->format('d/m/Y') }}</p>

                <h6 class="mt-4">Membres ({{ $members->count() }})</h6>
                @if ($members->count() > 0)
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Rôle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($members as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $readingGroup->user_id === $member->id ? 'Propriétaire' : 'Membre' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">Aucun membre pour le moment.</p>
                @endif

                @php
                    $isOwner = $readingGroup->user_id === auth()->id();
                    $isMember = $members->contains(auth()->id());
                @endphp

                <div class="mt-4">
                    @if ($isOwner)
                        <a href="{{ route('reading-groups.edit', $readingGroup) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                        <form action="{{ route('reading-groups.destroy', $readingGroup) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    @elseif ($isMember)
                        <form action="{{ route('reading-groups.leave', $readingGroup) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir quitter ce groupe ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-warning">Quitter</button>
                        </form>
                    @else
                        <form action="{{ route('reading-groups.join', $readingGroup) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success">Rejoindre</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection