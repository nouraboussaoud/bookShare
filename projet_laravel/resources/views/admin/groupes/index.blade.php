@extends('layouts.admin-layout')
@section('title', 'Gestion Groupes')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion Groupes</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between flex-wrap">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Groupes</h6>
            <div class="d-flex gap-2 mt-2 mt-md-0">
                <form method="GET" class="form-inline" style="gap:10px;">
                    <input type="text" name="q" class="form-control form-control-sm" placeholder="Recherche..." value="{{ $q ?? '' }}">
                    <select name="per_page" class="form-control form-control-sm">
                        @foreach([10,20,50,100] as $n)
                            <option value="{{ $n }}" {{ (isset($perPage) && $perPage == $n) ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-primary">Filtrer</button>
                </form>
                <a href="{{ route('admin.groupes.export') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-download me-1"></i> Télécharger CSV
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Groupes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalGroups ?? $groups->total() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Membres</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMembers ?? '-' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Événements</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEvents ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Activité Chat</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalChatActivity ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Membres</th>
                            <th>Événements</th>
                            <th>Messages Chat</th>
                            <th>Engagement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groups as $g)
                            <tr>
                                <td><small>#{{ $g->id }}</small></td>
                                <td><strong>{{ $g->name }}</strong></td>
                                <td><span class="badge bg-success">{{ $g->members_count ?? (method_exists($g, 'members') ? $g->members()->count() : '-') }}</span></td>
                                <td><span class="badge bg-info">{{ \DB::table('group_events')->where('reading_group_id', $g->id)->count() }}</span></td>
                                <td><span class="badge bg-warning text-dark">
                                    {{ \DB::table('event_chat_messages')
                                        ->whereIn('group_event_id', 
                                            \DB::table('group_events')
                                                ->where('reading_group_id', $g->id)
                                                ->pluck('id')
                                        )
                                        ->count() 
                                    }}
                                </span></td>
                                <td>
                                    @php
                                        $members = $g->members_count ?? $g->members()->count();
                                        $events = \DB::table('group_events')->where('reading_group_id', $g->id)->count();
                                        $messages = \DB::table('event_chat_messages')
                                            ->whereIn('group_event_id', 
                                                \DB::table('group_events')
                                                    ->where('reading_group_id', $g->id)
                                                    ->pluck('id')
                                            )
                                            ->count();
                                        $engagement = ($members > 0 && $events > 0) ? round($messages / ($members * $events), 2) : 0;
                                    @endphp
                                    <small>{{ $engagement }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.groupes.show', $g) }}" class="btn btn-info btn-sm">Voir</a>
                                        <a href="{{ route('admin.groupes.edit', $g) }}" class="btn btn-info btn-sm">Modifier</a>
                                        <form action="{{ route('admin.groupes.destroy', $g) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer ce groupe ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">{{ $groups->links() }}</div>
        </div>
    </div>
@endsection
