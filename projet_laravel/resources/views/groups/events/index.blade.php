@extends('layouts.app')

@section('title', 'Group Events - ' . $readingGroup->name)

@push('styles')
<style>
    .event-card { border:1px solid #e5e7eb; border-radius:.6rem; overflow:hidden; transition:.2s; }
    .event-card:hover { transform:translateY(-2px); box-shadow:0 8px 16px rgba(0,0,0,.08); }
    .event-badge { display:inline-block; padding:.35rem .7rem; border-radius:.4rem; font-size:.85rem; font-weight:600; }
    .badge-upcoming { background:#dcfce7; color:#166534; }
    .badge-past { background:#f3f4f6; color:#4b5563; }
    .empty-state { text-align:center; padding:3rem 1rem; }
    .empty-state i { font-size:3rem; color:#d1d5db; margin-bottom:1rem; }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h4 class="mb-1">
                        <i class="fas fa-calendar-alt text-primary me-2"></i> Events - {{ $readingGroup->name }}
                    </h4>
                    <p class="text-muted mb-0">Gérer et participer aux événements du groupe</p>
                </div>
                @php $isOwner = $readingGroup->owner_id === auth()->id(); @endphp
                <div>
                    @if($isOwner)
                        <a href="{{ route('reading-groups.events.create', $readingGroup) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Créer un événement
                        </a>
                    @endif
                    <a href="{{ route('reading-groups.show', $readingGroup) }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>

            <!-- Upcoming Events Section -->
            <div class="mb-5">
                <h5 class="mb-3">
                    <i class="fas fa-calendar-check text-success me-2"></i> Événements à venir
                </h5>

                @if($upcomingEvents->count() > 0)
                    <div class="row g-3">
                        @foreach($upcomingEvents as $event)
                            <div class="col-md-6">
                                <div class="event-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0">{{ $event->title }}</h6>
                                            <span class="event-badge badge-upcoming">
                                                <i class="fas fa-calendar-day me-1"></i>
                                                {{ $event->event_date->format('M j') }}
                                            </span>
                                        </div>

                                        @if($event->event_time)
                                            <p class="small text-muted mb-2">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $event->event_time->format('H:i') }}
                                            </p>
                                        @endif

                                        @if($event->location)
                                            <p class="small text-muted mb-2">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $event->location }}
                                            </p>
                                        @endif

                                        <p class="small mb-3 text-muted">
                                            {{ \Illuminate\Support\Str::limit($event->description, 100) }}
                                        </p>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-users me-1"></i>
                                                {{ $event->confirmedAttendeesCount() }}
                                                @if($event->max_attendees)/ {{ $event->max_attendees }}@endif participant{{ $event->confirmedAttendeesCount() !== 1 ? 's' : '' }}
                                            </small>
                                            @if($event->isActive())
                                                <a href="{{ route('events.chat.show', $event) }}" class="btn btn-sm btn-success">
                                                    <i class="fas fa-comments me-1"></i>Rejoindre le Chat
                                                </a>
                                            @else
                                                <a href="{{ route('reading-groups.events.show', [$readingGroup, $event]) }}" class="btn btn-sm btn-outline-primary">
                                                    Voir
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucun événement à venir programmé.
                        @if($isOwner)
                            <a href="{{ route('reading-groups.events.create', $readingGroup) }}" class="alert-link">En créer un maintenant !</a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Past Events Section -->
            @if($pastEvents->count() > 0)
            <div>
                <h5 class="mb-3">
                    <i class="fas fa-history text-secondary me-2"></i> Événements passés
                </h5>

                <div class="row g-3">
                    @foreach($pastEvents as $event)
                        <div class="col-md-6">
                            <div class="event-card" style="opacity: 0.7;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">{{ $event->title }}</h6>
                                        <span class="event-badge badge-past">
                                            <i class="fas fa-calendar-day me-1"></i>
                                            {{ $event->event_date->format('M j, Y') }}
                                        </span>
                                    </div>

                                    <p class="small mb-2 text-muted">
                                        {{ \Illuminate\Support\Str::limit($event->description, 100) }}
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            {{ $event->confirmedAttendeesCount() }} participant{{ $event->confirmedAttendeesCount() !== 1 ? 's' : '' }}
                                        </small>
                                        <a href="{{ route('reading-groups.events.show', [$readingGroup, $event]) }}" class="btn btn-sm btn-outline-secondary">
                                            Voir les détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($upcomingEvents->count() === 0 && $pastEvents->count() === 0)
                <div class="card border-0 bg-light">
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h5>Aucun événement pour le moment</h5>
                        <p class="text-muted mb-0">Ce groupe n'a pas encore programmé d'événements.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
