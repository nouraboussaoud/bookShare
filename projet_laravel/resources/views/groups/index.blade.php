@extends('layouts.app')

@section('title', 'Reading Groups')

@push('styles')
<style>
    :root{
        --primary: #6366f1;
        --secondary: #8b5cf6;
        --success: #10b981;
        --warning: #f59e0b;
        --border: #e2e8f0;
    }

    .page-hero {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #fff;
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .page-hero::before{
        content: "";
        position:absolute; inset:0;
        background: url("data:image/svg+xml,<svg width='60' height='60' xmlns='http://www.w3.org/2000/svg'><circle cx='30' cy='30' r='3' fill='%23fff' fill-opacity='0.12'/></svg>");
        opacity:.2;
        z-index: 1;
    }
    .page-hero > * {
        position: relative;
        z-index: 2;
    }

    .stat-card { background:#fff; border-left:4px solid var(--primary); border-radius:.75rem; padding:1rem; box-shadow:0 2px 6px rgba(0,0,0,.04); }
    .stat-number{ font-size:1.5rem; font-weight:700; color:var(--primary); }

    /* Group card improvements */
    .group-card { border:1px solid var(--border); border-radius:.75rem; overflow:hidden; transition:.18s; display:flex; flex-direction:column; height:100%; position:relative; background:#fff; }
    .group-card:hover { transform:translateY(-4px); box-shadow:0 8px 18px rgba(0,0,0,.06); border-color:var(--primary); }
    .group-badge { position:absolute; top:12px; right:12px; border-radius:999px; padding:.25rem .6rem; font-weight:600; font-size:.78rem; }
    .badge-public { background:#dcfce7; color:var(--success); }
    .badge-private { background:#fef3c7; color:var(--warning); }

    .group-content { padding:1rem 1rem 0 1rem; flex:0 0 auto; }
    .group-meta { padding:0 1rem 1rem 1rem; color:#6b7280; font-size:.9rem; }
    .group-owner { padding:0 1rem 1rem 1rem; display:flex; gap:.5rem; align-items:center; }
    .owner-avatar { width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; color:#fff; background:linear-gradient(135deg,var(--primary),var(--secondary)); }

    .group-actions { margin-top:auto; border-top:1px solid var(--border); padding:.75rem 1rem; background:#f8fafc; display:flex; gap:.5rem; flex-wrap:wrap; align-items:center; }
    .btn-modern { border-radius:.5rem; padding:.45rem .9rem; display:inline-flex; align-items:center; justify-content:center; gap:.5rem; font-weight:600; font-size:.9rem; text-decoration:none; border:none; cursor:pointer; transition:all .2s ease; }
    .btn-modern:hover { transform:translateY(-2px); box-shadow:0 4px 8px rgba(0,0,0,.1); }
    .btn-join { background:var(--success); color:#fff; }
    .btn-join:hover { background:#0da858; }
    .btn-request { background:var(--warning); color:#fff; }
    .btn-request:hover { background:#d97706; }
    .btn-manage { background:var(--primary); color:#fff; }
    .btn-manage:hover { background:#4f46e5; }
    .btn-view { background:#fff; border:1px solid var(--border); color:#444; }
    .btn-view:hover { background:#f3f4f6; border-color:var(--primary); color:var(--primary); }

    /* responsive tweaks */
    @media (max-width:576px){ .group-meta { font-size:.85rem; } .stat-number{ font-size:1.25rem; } }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4">

    {{-- Flash Messages --}}
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero">
        <div class="d-flex justify-content-between flex-wrap align-items-start gap-3">
            <div>
                <h1 class="h3 fw-bold mb-1"><i class="fas fa-book-reader me-2"></i> Groupes de Lecture</h1>
                <p class="mb-0 text-white-75">Découvrez des communautés de lecture incroyables et connectez-vous avec d'autres passionnés de livres</p>
            </div>
            <a href="{{ route('reading-groups.create') }}" class="btn btn-light btn-lg shadow-sm">
                <i class="fas fa-plus me-2"></i>Créer un Groupe
            </a>
        </div>
    </div>

    {{-- stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-number">{{ $groups->total() }}</div>
                <div class="text-muted small">Groupes Totaux</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                @php $publicCount = \App\Models\ReadingGroup::where('is_private', false)->where('status', 'active')->count(); @endphp
                <div class="stat-number">{{ $publicCount }}</div>
                <div class="text-muted small">Groupes Publics</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                @php $myGroupsCount = \App\Models\ReadingGroup::where('owner_id', auth()->id())->where('status', 'active')->count(); @endphp
                <div class="stat-number">{{ $myGroupsCount }}</div>
                <div class="text-muted small">Vos Groupes</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                @php $totalMembers = \App\Models\GroupMembership::where('status', 'approved')->distinct('user_id')->count('user_id'); @endphp
                <div class="stat-number">{{ $totalMembers }}</div>
                <div class="text-muted small">Membres Totaux</div>
            </div>
        </div>
    </div>

    {{-- groups list --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i> Tous les Groupes de Lecture</h5>
            <div class="d-flex gap-2 w-100 w-md-auto">
                <div class="position-relative flex-grow-1">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input class="form-control ps-5 rounded-pill" placeholder="Rechercher des groupes par nom ou description..." />
                </div>
                <select class="form-select rounded-pill w-auto">
                    <option>Tous les Groupes</option>
                    <option>Groupes Publics</option>
                    <option>Groupes Privés</option>
                    <option>Mes Groupes</option>
                </select>
            </div>
        </div>

        <div class="card-body">
            @if($groups->count())
                <div class="row g-4">
                    @foreach($groups as $group)
                        @php
                            // safe fallbacks
                            $membersCount = $group->members_count ?? ($group->members ? $group->members->count() : 0);
                            $ownerName = optional($group->owner)->name ?? 'Unknown';
                        @endphp

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="group-card h-100">
                                <span class="group-badge {{ $group->is_private ? 'badge-private' : 'badge-public' }}">
                                    <i class="fas fa-{{ $group->is_private ? 'lock' : 'globe' }} me-1"></i>
                                    {{ $group->is_private ? 'Privé' : 'Public' }}
                                </span>

                                <div class="group-content">
                                    <h5 class="mb-1 fw-bold">
                                        {{ $group->name }}
                                        @if($group->owner_id === auth()->id())
                                            <i class="fas fa-crown text-warning small ms-1" title="Vous êtes propriétaire de ce groupe"></i>
                                        @endif
                                    </h5>

                                    <p class="text-muted small mb-2" style="min-height:2.6rem;">
                                        {{ \Illuminate\Support\Str::limit($group->description ?? 'Aucune description disponible', 140) }}
                                    </p>

                                    <div class="group-meta d-flex gap-3 flex-wrap">
                                        <span><i class="fas fa-users me-1"></i> {{ $membersCount }} {{ \Illuminate\Support\Str::plural('membre', $membersCount) }}</span>
                                        <span><i class="fas fa-calendar me-1"></i> {{ optional($group->created_at)->format('M j, Y') }}</span>
                                    </div>
                                </div>

                                <div class="group-owner">
                                    <div class="owner-avatar">
                                        {{ strtoupper(substr($ownerName,0,2)) }}
                                    </div>
                                    <div class="small text-truncate">{{ $ownerName }}</div>
                                </div>

                                <div class="group-actions">
                                    @php
                                        $isOwner = $group->owner_id === auth()->id();
                                        // Check membership via the pivot table directly for reliability
                                        $isMember = \App\Models\GroupMembership::where('user_id', auth()->id())
                                            ->where('reading_group_id', $group->id)
                                            ->where('status', 'approved')
                                            ->exists();
                                    @endphp

                                    @if($isOwner)
                                        <a href="{{ route('reading-groups.edit', $group) }}" class="btn-modern btn-manage">
                                            <i class="fas fa-cog"></i> Gérer
                                        </a>
                                    @elseif($isMember)
                                        <form action="{{ route('reading-groups.leave', $group) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-modern btn-request" onclick="return confirm('Quitter ce groupe ?')">
                                                <i class="fas fa-sign-out-alt"></i> Quitter
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('reading-groups.join', $group) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-modern {{ $group->is_private ? 'btn-request' : 'btn-join' }}">
                                                <i class="fas fa-{{ $group->is_private ? 'clock' : 'user-plus' }}"></i>
                                                {{ $group->is_private ? 'Demander' : 'Rejoindre' }}
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('reading-groups.show', $group) }}" class="btn-modern btn-view ms-auto">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $groups->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-book-open empty-icon"></i>
                    <h5>Aucun groupe de lecture pour le moment</h5>
                    <p class="small mb-3">Soyez le premier à créer une communauté de lecture !</p>
                    <a href="{{ route('reading-groups.create') }}" class="btn-modern btn-manage">
                        <i class="fas fa-plus me-1"></i>Créer le Premier Groupe
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection