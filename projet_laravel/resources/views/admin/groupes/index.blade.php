@extends('layouts.admin-layout')
@section('title', 'Gestion Groupes')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion Groupes</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Groupes</h6>
            <form method="GET" class="form-inline" style="gap:10px;">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Recherche..." value="{{ $q ?? '' }}">
                <select name="per_page" class="form-control form-control-sm">
                    @foreach([10,20,50,100] as $n)
                        <option value="{{ $n }}" {{ (isset($perPage) && $perPage == $n) ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-primary">Filtrer</button>
            </form>
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
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Membres</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groups as $g)
                            <tr>
                                <td>{{ $g->id }}</td>
                                <td>{{ $g->name }}</td>
                                <td>{{ $g->members_count ?? (method_exists($g, 'members') ? $g->members()->count() : '-') }}</td>
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
