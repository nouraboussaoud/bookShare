@extends('layouts.app')

@section('title', $readingGroup->name)

@push('styles')
<style>
    .detail-card { border-radius:.6rem; box-shadow:0 6px 18px rgba(0,0,0,.04); }
    .member-avatar { width:36px; height:36px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#fff; font-weight:700; background:linear-gradient(135deg,#6366f1,#8b5cf6); }
    .meta-row { gap:1rem; display:flex; flex-wrap:wrap; color:#6b7280; }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card detail-card mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">{{ $readingGroup->name }}</h6>
                    <a href="{{ route('reading-groups.index') }}" class="btn btn-sm btn-outline-secondary">Retour</a>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-1"><strong>Description :</strong></p>
                        <p class="text-muted">{{ $readingGroup->description ?? 'Aucune description' }}</p>
                    </div>

                    <div class="mb-3 meta-row">
                        <div><strong>Privé :</strong> {{ $readingGroup->is_private ? 'Oui' : 'Non' }}</div>
                        <div><strong>Propriétaire :</strong> {{ optional($readingGroup->owner)->name ?? 'Inconnu' }}</div>
                        <div><strong>Créé le :</strong> {{ optional($readingGroup->created_at)->format('d/m/Y') ?? '-' }}</div>
                    </div>

                    <h6 class="mt-4">Membres ({{ $members->count() }})</h6>

                    @if ($members->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Rôle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($members as $member)
                                        @php
                                            // Prefer pivot role if available, else determine owner by owner_id
                                            $role = 'Membre';
                                            if (isset($member->pivot) && !empty($member->pivot->role)) {
                                                $role = ucfirst($member->pivot->role);
                                            } elseif ($readingGroup->owner_id === $member->id) {
                                                $role = 'Propriétaire';
                                            }
                                        @endphp
                                        <tr>
                                            <td class="d-flex align-items-center gap-3">
                                                <div class="member-avatar">{{ strtoupper(substr($member->name ?? 'U', 0, 2)) }}</div>
                                                <div>
                                                    <div class="fw-semibold">{{ $member->name ?? 'Utilisateur' }}</div>
                                                    <div class="small text-muted">{{ $member->email ?? '' }}</div>
                                                </div>
                                            </td>
                                            <td>{{ $role }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucun membre pour le moment.</p>
                    @endif

                    @php
                        // Use owner_id consistently (controller uses owner_id)
                        $isOwner = $readingGroup->owner_id === auth()->id();
                        $isMember = $members->contains(auth()->id());
                    @endphp

                    <div class="mt-4 d-flex gap-2">
                        @if ($isOwner)
                            <a href="{{ route('reading-groups.edit', $readingGroup) }}" class="btn btn-sm btn-outline-primary">Modifier</a>

                            <form action="{{ route('reading-groups.destroy', $readingGroup) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                            </form>
                        @elseif ($isMember)
                            <form action="{{ route('reading-groups.leave', $readingGroup) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir quitter ce groupe ?');">
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
</div>
@endsection
