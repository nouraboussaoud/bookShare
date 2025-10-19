@extends('layouts.admin-layout')
@section('title', 'Modifier l\'Événement')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Modifier l'Événement</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.evenements.update', $event) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Titre</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control">{{ old('description', $event->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="event_date" class="form-control" value="{{ old('event_date', optional($event->event_date)->format('Y-m-d')) }}">
                </div>

                <div class="form-group">
                    <label>Heure</label>
                    <input type="time" name="event_time" class="form-control" value="{{ old('event_time', optional($event->event_time)->format('H:i')) }}">
                </div>

                <button class="btn btn-primary">Enregistrer</button>
                <a href="{{ route('admin.evenements.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection
