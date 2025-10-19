@extends('layouts.admin-layout')
@section('title', 'Détails de l\'Événement')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Détails de l'Événement</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $event->id }}</p>
            <p><strong>Titre:</strong> {{ $event->title }}</p>
            <p><strong>Description:</strong> {{ $event->description }}</p>
            <p><strong>Date:</strong>
                @if($event->event_date)
                    {{ $event->event_date->format('Y-m-d') }}
                    @if(!empty($event->event_time))
                        {{ ' ' . \Illuminate\Support\Carbon::parse($event->event_time)->format('H:i') }}
                    @endif
                @else
                    -
                @endif
            </p>

            <a href="{{ route('admin.evenements.edit', $event) }}" class="btn btn-info">Modifier</a>
            <a href="{{ route('admin.evenements.index') }}" class="btn btn-secondary">Retour</a>
        </div>
    </div>
@endsection
