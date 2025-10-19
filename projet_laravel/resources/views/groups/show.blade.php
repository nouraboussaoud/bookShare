@extends('layouts.app')

@section('title', $readingGroup->name)

@push('styles')
<style>
    .detail-card { border-radius:.6rem; box-shadow:0 6px 18px rgba(0,0,0,.04); }
    .member-avatar { width:36px; height:36px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#fff; font-weight:700; background:linear-gradient(135deg,#6366f1,#8b5cf6); }
    .meta-row { gap:1rem; display:flex; flex-wrap:wrap; color:#6b7280; }
    .badge-role { display:inline-block; padding:.25rem .6rem; border-radius:.4rem; font-size:.8rem; font-weight:600; }
    .badge-owner { background:#fef3c7; color:#92400e; }
    .badge-moderator { background:#dbeafe; color:#1e40af; }
    .badge-member { background:#dcfce7; color:#166534; }
    .member-card { border:1px solid #e5e7eb; border-radius:.5rem; padding:1rem; margin-bottom:.75rem; display:flex; justify-content:space-between; align-items:center; }
    .pending-request { background:#fef3c7; border:1px solid #fcd34d; }
    .btn-sm-action { padding:.4rem .6rem; font-size:.8rem; }
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

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Group Header -->
            <div class="card detail-card mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h6 class="m-0 fw-bold text-primary">{{ $readingGroup->name }}</h6>
                        <small class="text-muted">
                            {{ $readingGroup->is_private ? '🔒 Groupe Privé' : '🌍 Groupe Public' }}
                        </small>
                    </div>
                    <a href="{{ route('reading-groups.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-1"><strong>Description:</strong></p>
                        <p class="text-muted">{{ $readingGroup->description ?? 'Aucune description disponible.' }}</p>
                    </div>

                    <div class="mb-4 meta-row">
                        <div><strong>Propriétaire:</strong> {{ optional($readingGroup->owner)->name ?? 'Unknown' }}</div>
                        <div><strong>Créé:</strong> {{ optional($readingGroup->created_at)->format('M j, Y') ?? '-' }}</div>
                        <div><strong>Membres:</strong> {{ $members->count() }}/{{ $readingGroup->max_members ?? '∞' }}</div>
                        <div><strong>Statut:</strong> <span class="badge bg-success">{{ ucfirst($readingGroup->status) }}</span></div>
                    </div>

                    <!-- Events Preview -->
                    @php $upcomingEvents = $readingGroup->upcomingEvents()->take(3)->get(); @endphp
                    @if($upcomingEvents->count() > 0)
                    <div class="mb-4">
                        <h6 class="mb-2">📅 Événements à venir</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($upcomingEvents as $event)
                                @if($event->isActive())
                                    <div class="badge bg-success d-flex align-items-center gap-1">
                                        <i class="fas fa-circle text-warning" style="font-size: 0.6rem;"></i>
                                        {{ $event->event_date->format('M j') }} - {{ $event->title }} (EN COURS)
                                        <a href="{{ route('events.chat.show', $event) }}" class="btn btn-sm btn-light ms-2 py-0 px-2" style="font-size: 0.7rem;">
                                            <i class="fas fa-comments"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="badge bg-info">
                                        {{ $event->event_date->format('M j') }} - {{ $event->title }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <a href="{{ route('reading-groups.events.index', $readingGroup) }}" class="btn btn-sm btn-outline-info mt-2">
                            <i class="fas fa-calendar me-1"></i> Voir tous les événements
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Members Section -->
            <div class="card detail-card mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Membres ({{ $members->count() }})</h6>
                    @php $isOwner = $readingGroup->owner_id === auth()->id(); @endphp
                    @if($isOwner)
                        <a href="{{ route('reading-groups.events.create', $readingGroup) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-calendar-plus me-1"></i> Créer un événement
                        </a>
                    @endif
                </div>

                <div class="card-body">
                    @if ($members->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Member</th>
                                        <th>Role</th>
                                        <th>Joined</th>
                                        @if($isOwner)<th>Actions</th>@endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($members as $member)
                                        @php
                                            $role = 'Member';
                                            if (isset($member->pivot) && !empty($member->pivot->role)) {
                                                $role = ucfirst($member->pivot->role);
                                            } elseif ($readingGroup->owner_id === $member->id) {
                                                $role = 'Owner';
                                            }
                                            $joined = optional($member->pivot->joined_at ?? $member->pivot->created_at)->format('M j, Y') ?? 'N/A';
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="member-avatar">{{ strtoupper(substr($member->name ?? 'U', 0, 2)) }}</div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $member->name ?? 'User' }}</div>
                                                        <div class="small text-muted">{{ $member->email ?? '' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge-role badge-{{ strtolower($role) }}">{{ $role }}</span>
                                            </td>
                                            <td class="small">{{ $joined }}</td>
                                            @if($isOwner && $member->id !== $readingGroup->owner_id)
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <form action="{{ route('reading-groups.members.remove', [$readingGroup, $member->id]) }}" method="POST" class="d-inline" 
                                                          onsubmit="return confirm('Remove this member?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove member">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No members yet.</p>
                    @endif
                </div>
            </div>

            <!-- Pending Requests (Owner Only) -->
            @php
                $pendingRequests = $readingGroup->memberships()
                    ->where('status', 'pending')
                    ->with('user')
                    ->get();
            @endphp
            @if($isOwner && $pendingRequests->count() > 0)
            <div class="card detail-card mb-4" style="border-left:4px solid #f59e0b;">
                <div class="card-header py-3 bg-light">
                    <h6 class="m-0">
                        <i class="fas fa-clock text-warning me-2"></i> Pending Join Requests ({{ $pendingRequests->count() }})
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($pendingRequests as $request)
                        <div class="member-card pending-request">
                            <div class="d-flex align-items-center gap-2 flex-grow-1">
                                <div class="member-avatar">{{ strtoupper(substr($request->user->name ?? 'U', 0, 2)) }}</div>
                                <div>
                                    <div class="fw-semibold">{{ $request->user->name }}</div>
                                    <div class="small text-muted">{{ $request->user->email }}</div>
                                </div>
                            </div>
                            <div class="btn-group btn-group-sm" role="group">
                                <form action="{{ route('reading-groups.memberships.approve', [$readingGroup, $request->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success btn-sm-action">
                                        <i class="fas fa-check me-1"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('reading-groups.memberships.reject', [$readingGroup, $request->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger btn-sm-action" onclick="return confirm('Reject this request?');">
                                        <i class="fas fa-times me-1"></i> Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="card detail-card">
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        @php
                            $isOwner = $readingGroup->owner_id === auth()->id();
                            // Check membership via database for accuracy
                            $isMember = \App\Models\GroupMembership::where('user_id', auth()->id())
                                ->where('reading_group_id', $readingGroup->id)
                                ->where('status', 'approved')
                                ->exists();
                        @endphp

                        @if ($isOwner)
                            <a href="{{ route('reading-groups.edit', $readingGroup) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit me-1"></i> Edit Group
                            </a>
                            <form action="{{ route('reading-groups.destroy', $readingGroup) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this group? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash-alt me-1"></i> Delete Group
                                </button>
                            </form>
                        @elseif ($isMember)
                            <form action="{{ route('reading-groups.leave', $readingGroup) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to leave this group?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-sign-out-alt me-1"></i> Leave Group
                                </button>
                            </form>
                        @else
                            <form action="{{ route('reading-groups.join', $readingGroup) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $readingGroup->is_private ? 'btn-outline-primary' : 'btn-success' }}">
                                    <i class="fas fa-{{ $readingGroup->is_private ? 'hourglass-start' : 'user-plus' }} me-1"></i>
                                    {{ $readingGroup->is_private ? 'Request to Join' : 'Join Group' }}
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('reading-groups.events.index', $readingGroup) }}" class="btn btn-sm btn-outline-info ms-auto">
                            <i class="fas fa-calendar me-1"></i> Événements
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
