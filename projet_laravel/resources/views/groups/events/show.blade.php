@extends('layouts.app')

@section('title', $event->title . ' - ' . $readingGroup->name)

@push('styles')
<style>
    .event-header { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:#fff; border-radius:.6rem; padding:2rem; margin-bottom:2rem; }
    .event-detail-row { display:flex; align-items:center; gap:1rem; margin-bottom:1rem; }
    .event-detail-row i { width:20px; color:#6366f1; font-size:1.1rem; }
    .attendee-badge { display:inline-block; width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; margin-right:.5rem; }
    .member-item { display:flex; align-items:center; gap:1rem; padding:.75rem; border-bottom:1px solid #e5e7eb; }
    .member-item:last-child { border-bottom:none; }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Event Header -->
            <div class="event-header">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h3 class="mb-2">{{ $event->title }}</h3>
                        <p class="mb-0 text-white-75">{{ $readingGroup->name }}</p>
                    </div>
                    <a href="{{ route('reading-groups.events.index', $readingGroup) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>
            </div>

            <!-- Event Details -->
            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h6 class="m-0">Détails de l'événement</h6>
                        </div>
                        <div class="card-body">
                            <div class="event-detail-row">
                                <i class="fas fa-calendar"></i>
                                <div>
                                    <div class="small text-muted">Date</div>
                                    <strong>{{ $event->event_date->format('l, F j, Y') }}</strong>
                                </div>
                            </div>

                            @if($event->event_time)
                            <div class="event-detail-row">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <div class="small text-muted">Heure</div>
                                    <strong>{{ $event->event_time->format('H:i') }}</strong>
                                    @if($event->duration_minutes)
                                        <span class="text-muted">({{ $event->duration_minutes }} min)</span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($event->location)
                            <div class="event-detail-row">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <div class="small text-muted">Lieu</div>
                                    <strong>{{ $event->location }}</strong>
                                </div>
                            </div>
                            @endif

                            <div class="event-detail-row">
                                <i class="fas fa-user-circle"></i>
                                <div>
                                    <div class="small text-muted">Organisé par</div>
                                    <strong>{{ optional($event->creator)->name ?? 'Unknown' }}</strong>
                                </div>
                            </div>

                            @if($event->description)
                            <div class="mt-3">
                                <h6 class="mb-2">Description</h6>
                                <p class="text-muted mb-0">{{ $event->description }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            @if($event->isPast())
                                <div class="alert alert-secondary mb-0">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Cet événement est terminé</strong>
                                </div>
                            @else
                                @if($isAttending && $attendanceStatus === 'confirmed')
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <strong>Vous êtes inscrit à cet événement</strong>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Attendance Card -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h6 class="m-0">Participation</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Participants inscrits</span>
                                    <strong class="text-primary">{{ $event->confirmedAttendeesCount() }}
                                        @if($event->max_attendees)/ {{ $event->max_attendees }}@endif
                                    </strong>
                                </div>
                                @if($event->max_attendees)
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar" style="width:{{ min(100, ($event->confirmedAttendeesCount() / $event->max_attendees) * 100) }}%"></div>
                                </div>
                                @endif
                            </div>

                            @if(!$event->isPast())
                            @if($isAttending && $attendanceStatus === 'confirmed')
                                <form action="{{ route('reading-groups.events.leave', [$readingGroup, $event]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Se désinscrire de cet événement ?');">
                                        <i class="fas fa-times me-1"></i> Se désinscrire
                                    </button>
                                </form>
                            @else
                                @if($event->max_attendees && $event->confirmedAttendeesCount() >= $event->max_attendees)
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="fas fa-users me-1"></i> Événement complet
                                    </button>
                                @else
                                    <form action="{{ route('reading-groups.events.join', [$readingGroup, $event]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-calendar-check me-1"></i> S'inscrire
                                        </button>
                                    </form>
                                @endif
                            @endif

                            @if($event->isActive())
                                <div class="mt-2">
                                    <a href="{{ route('events.chat.show', $event) }}" class="btn btn-primary w-100">
                                        <i class="fas fa-comments me-1"></i> Rejoindre le Chat
                                    </a>
                                </div>
                            @elseif($isAttending && $attendanceStatus === 'confirmed')
                                <div class="mt-2">
                                    <a href="{{ route('events.chat.show', $event) }}" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-comments me-1"></i> Accéder au Chat
                                    </a>
                                </div>
                            @endif
                            @endif

                            @php $isOwner = $readingGroup->owner_id === auth()->id(); @endphp
                            @if($isOwner)
                                <div class="mt-2 d-flex gap-2">
                                    <a href="{{ route('reading-groups.events.edit', [$readingGroup, $event]) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                                        <i class="fas fa-edit me-1"></i> Modifier
                                    </a>
                                    <form action="{{ route('reading-groups.events.destroy', [$readingGroup, $event]) }}" method="POST" class="flex-grow-1"
                                          onsubmit="return confirm('Supprimer cet événement ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                            <i class="fas fa-trash me-1"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Attendees List -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="m-0">Participants</h6>
                            <span class="badge bg-primary">{{ $event->attendees()->count() }}</span>
                        </div>
                        <div class="card-body p-0" style="max-height:300px; overflow-y:auto;">
                            @forelse($event->attendees as $attendee)
                                <div class="member-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <div class="attendee-badge">{{ strtoupper(substr($attendee->name, 0, 2)) }}</div>
                                        <div class="flex-grow-1">
                                            <div class="small fw-semibold">{{ $attendee->name }}</div>
                                            <div class="small text-muted">{{ $attendee->email }}</div>
                                            <div class="small text-muted">
                                                Inscrit le {{ $attendee->pivot->joined_at ? \Carbon\Carbon::parse($attendee->pivot->joined_at)->format('d/m/Y à H:i') : 'Date inconnue' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($attendee->pivot->status === 'confirmed')
                                            <span class="badge bg-success">Confirmé</span>
                                        @elseif($attendee->pivot->status === 'pending')
                                            <span class="badge bg-warning">En attente</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($attendee->pivot->status) }}</span>
                                        @endif
                                        @if($isOwner && $attendee->id !== auth()->id())
                                            <form action="{{ route('reading-groups.events.leave', [$readingGroup, $event]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="user_id" value="{{ $attendee->id }}">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Retirer {{ $attendee->name }} de cet événement ?');"
                                                        title="Retirer ce participant">
                                                    <i class="fas fa-user-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="p-3 text-center text-muted">
                                    <i class="fas fa-user-slash me-2"></i> Aucun participant pour le moment
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
