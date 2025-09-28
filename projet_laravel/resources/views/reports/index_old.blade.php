@extends('layouts.layout')

@section('title', 'Mes signalements')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-flag text-primary mr-2"></i>
                Mes signalements
            </h1>
            <p class="mb-0 text-gray-600">Consultez l'état de vos signalements</p>
        </div>
        <a href="{{ route('reports.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouveau signalement
        </a>
    </div>

    <!-- Main Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste de mes signalements</h6>
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
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-12">
                    <form method="GET" class="form-inline">
                        <div class="form-group mr-3">
                            <label for="status" class="mr-2">Statut:</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Tous les statuts</option>
                                @foreach(\App\Models\Report::getStatuses() as $value => $label)
                                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mr-3">
                            <label for="type" class="mr-2">Type:</label>
                            <select name="type" id="type" class="form-control">
                                <option value="">Tous les types</option>
                                @foreach(\App\Models\Report::getTypes() as $value => $label)
                                    <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>

                        @if(request()->hasAny(['status', 'type']))
                            <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Réinitialiser
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            @if($reports->count() > 0)
                <div class="space-y-4">
                    @foreach($reports as $report)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-sm font-medium text-gray-600">
                                            {{ \App\Models\Report::getTypes()[$report->type] }}
                                        </span>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            @if($report->status === 'EN_ATTENTE') bg-yellow-100 text-yellow-800
                                            @elseif($report->status === 'TRAITE') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ \App\Models\Report::getStatuses()[$report->status] }}
                                        </span>
                                    </div>

                                    <p class="text-gray-700 mb-2">{{ Str::limit($report->description, 100) }}</p>

                                    <div class="text-sm text-gray-500">
                                        @if($report->reportedUser)
                                            <span>Utilisateur signalé: {{ $report->reportedUser->name }}</span>
                                        @endif
                                        @if($report->exchange)
                                            <span>{{ $report->reportedUser ? ' • ' : '' }}Échange: {{ $report->exchange->bookDemande->title ?? 'Livre supprimé' }}</span>
                                        @endif
                                    </div>

                                    <div class="text-xs text-gray-400 mt-2">
                                        Créé le {{ $report->created_at->format('d/m/Y à H:i') }}
                                    </div>
                                </div>

                                <div class="ml-4">
                                    <a href="{{ route('reports.show', $report) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition duration-200">
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $reports->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 text-6xl mb-4">📝</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun signalement trouvé</h3>
                    <p class="text-gray-600">
                        @if(request()->hasAny(['status', 'type']))
                            Aucun signalement ne correspond à vos critères de recherche.
                        @else
                            Vous n'avez créé aucun signalement pour le moment.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection