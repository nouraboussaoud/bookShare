@extends('layouts.layout')@extends('layouts.layout')



@section('title', 'BookShare - Gestion des signalements')@section('title', 'BookShare - Gestion des signalements')



@section('content')@section('content')

    <!-- Page Heading -->    <!-- Page Heading -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>        <div>

            <h1 class="h3 mb-0 text-gray-800">            <h1 class="h3 mb-0 text-gray-800">

                <i class="fas fa-flag text-primary mr-2"></i>                <i class="fas fa-flag text-primary mr-2"></i>

                Gestion des signalements                Gestion des signalements

            </h1>            </h1>

            <p class="mb-0 text-gray-600">Examinez et traitez les signalements des utilisateurs</p>            <p class="mb-0 text-gray-600">Examinez et traitez les signalements des utilisateurs</p>

        </div>        </div>

    </div>    </div>



    @if(session('success'))    <!-- Statistics Cards -->

        <div class="alert alert-success alert-dismissible fade show" role="alert">    <div class="row mb-4">

            {{ session('success') }}        <div class="col-xl-3 col-md-6 mb-4">

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">            <div class="card border-left-primary shadow h-100 py-2">

                <span aria-hidden="true">&times;</span>                <div class="card-body">

            </button>                    <div class="row no-gutters align-items-center">

        </div>                        <div class="col mr-2">

    @endif                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>

    @if(session('error'))                        </div>

        <div class="alert alert-danger alert-dismissible fade show" role="alert">                        <div class="col-auto">

            {{ session('error') }}                            <i class="fas fa-flag fa-2x text-gray-300"></i>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">                        </div>

                <span aria-hidden="true">&times;</span>                    </div>

            </button>                </div>

        </div>            </div>

    @endif        </div>

        

    <!-- Statistics Cards -->        <div class="col-xl-3 col-md-6 mb-4">

    <div class="row mb-4">            <div class="card border-left-warning shadow h-100 py-2">

        <div class="col-xl-3 col-md-6 mb-4">                <div class="card-body">

            <div class="card border-left-primary shadow h-100 py-2">                    <div class="row no-gutters align-items-center">

                <div class="card-body">                        <div class="col mr-2">

                    <div class="row no-gutters align-items-center">                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En attente</div>

                        <div class="col mr-2">                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>

                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>                        </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>                        <div class="col-auto">

                        </div>                            <i class="fas fa-clock fa-2x text-gray-300"></i>

                        <div class="col-auto">                        </div>

                            <i class="fas fa-flag fa-2x text-gray-300"></i>                    </div>

                        </div>                </div>

                    </div>            </div>

                </div>        </div>

            </div>        

        </div>        <div class="col-xl-3 col-md-6 mb-4">

                    <div class="card border-left-success shadow h-100 py-2">

        <div class="col-xl-3 col-md-6 mb-4">                <div class="card-body">

            <div class="card border-left-warning shadow h-100 py-2">                    <div class="row no-gutters align-items-center">

                <div class="card-body">                        <div class="col mr-2">

                    <div class="row no-gutters align-items-center">                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Traités</div>

                        <div class="col mr-2">                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['processed'] }}</div>

                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En attente</div>                        </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>                        <div class="col-auto">

                        </div>                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>

                        <div class="col-auto">                        </div>

                            <i class="fas fa-clock fa-2x text-gray-300"></i>                    </div>

                        </div>                </div>

                    </div>            </div>

                </div>        </div>

            </div>        

        </div>        <div class="col-xl-3 col-md-6 mb-4">

                    <div class="card border-left-danger shadow h-100 py-2">

        <div class="col-xl-3 col-md-6 mb-4">                <div class="card-body">

            <div class="card border-left-success shadow h-100 py-2">                    <div class="row no-gutters align-items-center">

                <div class="card-body">                        <div class="col mr-2">

                    <div class="row no-gutters align-items-center">                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejetés</div>

                        <div class="col mr-2">                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['rejected'] }}</div>

                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Traités</div>                        </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['processed'] }}</div>                        <div class="col-auto">

                        </div>                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>

                        <div class="col-auto">                        </div>

                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>                    </div>

                        </div>                </div>

                    </div>            </div>

                </div>        </div>

            </div>    </div>

        </div>

            <!-- Filters Card -->

        <div class="col-xl-3 col-md-6 mb-4">    <div class="card shadow mb-4">

            <div class="card border-left-danger shadow h-100 py-2">        <div class="card-header py-3">

                <div class="card-body">            <h6 class="m-0 font-weight-bold text-primary">Filtres</h6>

                    <div class="row no-gutters align-items-center">        </div>

                        <div class="col mr-2">        <div class="card-body">

                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejetés</div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['rejected'] }}</div>            <form method="GET" class="row">

                        </div>                <div class="col-md-3 mb-3">

                        <div class="col-auto">                    <label for="status" class="form-label">Statut</label>

                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>                    <select name="status" id="status" class="form-control">

                        </div>                        <option value="">Tous les statuts</option>

                    </div>                        @foreach(\App\Models\Report::getStatuses() as $value => $label)

                </div>                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>

            </div>                                {{ $label }}

        </div>                            </option>

    </div>                        @endforeach

                    </select>

    <!-- Filters Card -->                </div>

    <div class="card shadow mb-4">

        <div class="card-header py-3">                    <div>

            <h6 class="m-0 font-weight-bold text-primary">Filtres</h6>                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>

        </div>                        <select name="type" id="type" class="rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">

        <div class="card-body">                            <option value="">Tous les types</option>

            <form method="GET" class="row">                            @foreach(\App\Models\Report::getTypes() as $value => $label)

                <div class="col-md-3 mb-3">                                <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>

                    <label for="status" class="form-label">Statut</label>                                    {{ $label }}

                    <select name="status" id="status" class="form-control">                                </option>

                        <option value="">Tous les statuts</option>                            @endforeach

                        @foreach(\App\Models\Report::getStatuses() as $value => $label)                        </select>

                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>                    </div>

                                {{ $label }}

                            </option>                    <div>

                        @endforeach                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>

                    </select>                        <input type="text" name="search" id="search" value="{{ request('search') }}" 

                </div>                               placeholder="Nom utilisateur..." 

                               class="rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">

                <div class="col-md-3 mb-3">                    </div>

                    <label for="type" class="form-label">Type</label>

                    <select name="type" id="type" class="form-control">                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition duration-200">

                        <option value="">Tous les types</option>                        Filtrer

                        @foreach(\App\Models\Report::getTypes() as $value => $label)                    </button>

                            <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>

                                {{ $label }}                    @if(request()->hasAny(['status', 'type', 'search']))

                            </option>                        <a href="{{ route('admin.reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200">

                        @endforeach                            Réinitialiser

                    </select>                        </a>

                </div>                    @endif

                </form>

                <div class="col-md-4 mb-3">            </div>

                    <label for="search" class="form-label">Recherche</label>        </div>

                    <input type="text" name="search" id="search" value="{{ request('search') }}"     </div>

                           placeholder="Nom utilisateur..." class="form-control">

                </div>    <!-- Reports List -->

    @if($reports->count() > 0)

                <div class="col-md-2 mb-3">        <div class="bg-white rounded-lg shadow-md">

                    <label class="form-label">&nbsp;</label>            <!-- Bulk Actions -->

                    <div class="d-flex gap-2">            <div class="p-4 border-b border-gray-200">

                        <button type="submit" class="btn btn-primary">                <form id="bulk-action-form" method="POST" action="{{ route('admin.reports.bulkUpdateStatus') }}">

                            <i class="fas fa-search"></i> Filtrer                    @csrf

                        </button>                    <div class="flex items-center gap-4">

                        @if(request()->hasAny(['status', 'type', 'search']))                        <label for="select-all" class="flex items-center">

                            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-500 focus:ring-red-500">

                                <i class="fas fa-undo"></i>                            <span class="ml-2 text-sm text-gray-700">Tout sélectionner</span>

                            </a>                        </label>

                        @endif                        

                    </div>                        <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">

                </div>                            <option value="">Action en lot...</option>

            </form>                            <option value="TRAITE">Marquer comme traité</option>

        </div>                            <option value="REJETE">Marquer comme rejeté</option>

    </div>                        </select>

                        

    <!-- Reports List -->                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition duration-200 disabled:bg-gray-400" disabled>

    @if($reports->count() > 0)                            Appliquer

        <div class="card shadow mb-4">                        </button>

            <div class="card-header py-3">                    </div>

                <h6 class="m-0 font-weight-bold text-primary">Liste des signalements</h6>                </form>

            </div>            </div>

            <div class="card-body">

                <!-- Bulk Actions -->            <!-- Reports Table -->

                <div class="mb-3">            <div class="overflow-x-auto">

                    <form id="bulk-action-form" method="POST" action="{{ route('admin.reports.bulkUpdateStatus') }}">                <table class="min-w-full divide-y divide-gray-200">

                        @csrf                    <thead class="bg-gray-50">

                        <div class="row align-items-end">                        <tr>

                            <div class="col-md-3">                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">

                                <div class="form-check">                                <input type="checkbox" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-500 focus:ring-red-500">

                                    <input type="checkbox" id="select-all" class="form-check-input">                            </th>

                                    <label for="select-all" class="form-check-label">Tout sélectionner</label>                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>

                                </div>                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>

                            </div>                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Signalé par</th>

                            <div class="col-md-4">                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur signalé</th>

                                <select name="status" class="form-control">                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>

                                    <option value="">Action en lot...</option>                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>

                                    <option value="TRAITE">Marquer comme traité</option>                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>

                                    <option value="REJETE">Marquer comme rejeté</option>                        </tr>

                                </select>                    </thead>

                            </div>                    <tbody class="bg-white divide-y divide-gray-200">

                            <div class="col-md-2">                        @foreach($reports as $report)

                                <button type="submit" class="btn btn-warning" disabled>                            <tr class="hover:bg-gray-50">

                                    <i class="fas fa-tasks"></i> Appliquer                                <td class="px-4 py-4 whitespace-nowrap">

                                </button>                                    <input type="checkbox" name="reports[]" value="{{ $report->id }}" 

                            </div>                                           class="report-checkbox rounded border-gray-300 text-red-600 shadow-sm focus:border-red-500 focus:ring-red-500">

                        </div>                                </td>

                    </form>                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">

                </div>                                    #{{ $report->id }}

                                </td>

                <!-- Reports Table -->                                <td class="px-4 py-4 whitespace-nowrap">

                <div class="table-responsive">                                    <span class="px-2 py-1 text-xs font-medium rounded-full

                    <table class="table table-bordered" width="100%" cellspacing="0">                                        @if($report->type === 'CONFLIT_ECHANGE') bg-purple-100 text-purple-800

                        <thead>                                        @else bg-orange-100 text-orange-800 @endif">

                            <tr>                                        {{ \App\Models\Report::getTypes()[$report->type] }}

                                <th width="5%">                                    </span>

                                    <input type="checkbox" class="form-check-input">                                </td>

                                </th>                                <td class="px-4 py-4 whitespace-nowrap">

                                <th width="8%">ID</th>                                    <div class="text-sm text-gray-900">{{ $report->reporter->name ?? 'N/A' }}</div>

                                <th width="15%">Type</th>                                    <div class="text-sm text-gray-500">{{ $report->reporter->email ?? 'N/A' }}</div>

                                <th width="20%">Signalé par</th>                                </td>

                                <th width="20%">Utilisateur signalé</th>                                <td class="px-4 py-4 whitespace-nowrap">

                                <th width="12%">Statut</th>                                    @if($report->reportedUser)

                                <th width="12%">Date</th>                                        <div class="text-sm text-gray-900">{{ $report->reportedUser->name }}</div>

                                <th width="8%">Actions</th>                                        <div class="text-sm text-gray-500">{{ $report->reportedUser->email }}</div>

                            </tr>                                    @else

                        </thead>                                        <span class="text-sm text-gray-400">N/A</span>

                        <tbody>                                    @endif

                            @foreach($reports as $report)                                </td>

                                <tr>                                <td class="px-4 py-4 whitespace-nowrap">

                                    <td>                                    <span class="px-2 py-1 text-xs font-medium rounded-full

                                        <input type="checkbox" name="reports[]" value="{{ $report->id }}"                                         @if($report->status === 'EN_ATTENTE') bg-yellow-100 text-yellow-800

                                               class="report-checkbox form-check-input">                                        @elseif($report->status === 'TRAITE') bg-green-100 text-green-800

                                    </td>                                        @else bg-red-100 text-red-800 @endif">

                                    <td class="font-weight-bold">                                        {{ \App\Models\Report::getStatuses()[$report->status] }}

                                        #{{ $report->id }}                                    </span>

                                    </td>                                </td>

                                    <td>                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">

                                        @if($report->type === 'CONFLIT_ECHANGE')                                    {{ $report->created_at->format('d/m/Y H:i') }}

                                            <span class="badge badge-info">Conflit d'échange</span>                                </td>

                                        @else                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium space-x-2">

                                            <span class="badge badge-warning">Comportement</span>                                    <a href="{{ route('admin.reports.show', $report) }}" 

                                        @endif                                       class="text-blue-600 hover:text-blue-900">Voir</a>

                                    </td>                                    

                                    <td>                                    @if($report->isPending())

                                        <div class="font-weight-bold">{{ $report->reporter->name ?? 'N/A' }}</div>                                        <span class="text-gray-300">|</span>

                                        <small class="text-gray-600">{{ $report->reporter->email ?? 'N/A' }}</small>                                        <button onclick="openStatusModal({{ $report->id }}, 'TRAITE')"

                                    </td>                                                class="text-green-600 hover:text-green-900">Traiter</button>

                                    <td>                                        <span class="text-gray-300">|</span>

                                        @if($report->reportedUser)                                        <button onclick="openStatusModal({{ $report->id }}, 'REJETE')"

                                            <div class="font-weight-bold">{{ $report->reportedUser->name }}</div>                                                class="text-red-600 hover:text-red-900">Rejeter</button>

                                            <small class="text-gray-600">{{ $report->reportedUser->email }}</small>                                    @endif

                                        @else                                </td>

                                            <span class="text-gray-500">N/A</span>                            </tr>

                                        @endif                        @endforeach

                                    </td>                    </tbody>

                                    <td>                </table>

                                        @if($report->status === 'EN_ATTENTE')            </div>

                                            <span class="badge badge-warning">En attente</span>

                                        @elseif($report->status === 'TRAITE')            <!-- Pagination -->

                                            <span class="badge badge-success">Traité</span>            <div class="bg-white px-4 py-3 border-t border-gray-200">

                                        @else                {{ $reports->appends(request()->query())->links() }}

                                            <span class="badge badge-danger">Rejeté</span>            </div>

                                        @endif        </div>

                                    </td>    @else

                                    <td>        <div class="bg-white rounded-lg shadow-md p-8 text-center">

                                        {{ $report->created_at->format('d/m/Y') }}<br>            <div class="text-gray-400 text-6xl mb-4">📋</div>

                                        <small class="text-gray-600">{{ $report->created_at->format('H:i') }}</small>            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun signalement trouvé</h3>

                                    </td>            <p class="text-gray-600">

                                    <td>                @if(request()->hasAny(['status', 'type', 'search']))

                                        <div class="btn-group" role="group">                    Aucun signalement ne correspond à vos critères de recherche.

                                            <a href="{{ route('admin.reports.show', $report) }}"                 @else

                                               class="btn btn-info btn-sm" title="Voir détails">                    Aucun signalement n'a été créé pour le moment.

                                                <i class="fas fa-eye"></i>                @endif

                                            </a>            </p>

                                                    </div>

                                            @if($report->isPending())    @endif

                                                <button type="button" class="btn btn-success btn-sm" </div>

                                                        onclick="updateReportStatus({{ $report->id }}, 'TRAITE')" 

                                                        title="Marquer comme traité"><!-- Status Update Modal -->

                                                    <i class="fas fa-check"></i><div id="status-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">

                                                </button>    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">

                                                <button type="button" class="btn btn-danger btn-sm"         <div class="mt-3 text-center">

                                                        onclick="updateReportStatus({{ $report->id }}, 'REJETE')"             <h3 class="text-lg font-medium text-gray-900" id="modal-title">Changer le statut</h3>

                                                        title="Rejeter">            <div class="mt-2 px-7 py-3">

                                                    <i class="fas fa-times"></i>                <p class="text-sm text-gray-500" id="modal-message">

                                                </button>                    Êtes-vous sûr de vouloir changer le statut de ce signalement ?

                                            @endif                </p>

                                        </div>            </div>

                                    </td>            <div class="items-center px-4 py-3">

                                </tr>                <form id="status-form" method="POST">

                            @endforeach                    @csrf

                        </tbody>                    @method('PATCH')

                    </table>                    <input type="hidden" name="status" id="modal-status">

                </div>                    <div class="flex justify-center space-x-3">

                        <button type="button" onclick="closeStatusModal()"

                <!-- Pagination -->                                class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">

                <div class="d-flex justify-content-center">                            Annuler

                    {{ $reports->appends(request()->query())->links() }}                        </button>

                </div>                        <button type="submit"

            </div>                                class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">

        </div>                            Confirmer

    @else                        </button>

        <div class="card shadow mb-4">                    </div>

            <div class="card-body text-center py-5">                </form>

                <i class="fas fa-flag fa-3x text-gray-300 mb-3"></i>            </div>

                <h5 class="text-gray-600">Aucun signalement trouvé</h5>        </div>

                <p class="text-gray-500">    </div>

                    @if(request()->hasAny(['status', 'type', 'search']))</div>

                        Aucun signalement ne correspond à vos critères de recherche.

                    @else<script>

                        Il n'y a pas encore de signalements dans le système.// Bulk selection

                    @endifdocument.getElementById('select-all').addEventListener('change', function() {

                </p>    const checkboxes = document.querySelectorAll('.report-checkbox');

                @if(request()->hasAny(['status', 'type', 'search']))    const bulkButton = document.querySelector('#bulk-action-form button[type="submit"]');

                    <a href="{{ route('admin.reports.index') }}" class="btn btn-primary">    

                        <i class="fas fa-undo mr-1"></i>Voir tous les signalements    checkboxes.forEach(checkbox => {

                    </a>        checkbox.checked = this.checked;

                @endif    });

            </div>    

        </div>    bulkButton.disabled = !this.checked;

    @endif});

@endsection

// Individual checkbox handling

@push('scripts')document.querySelectorAll('.report-checkbox').forEach(checkbox => {

<script>    checkbox.addEventListener('change', function() {

// Bulk selection functionality        const checkedBoxes = document.querySelectorAll('.report-checkbox:checked');

$(document).ready(function() {        const bulkButton = document.querySelector('#bulk-action-form button[type="submit"]');

    const selectAllCheckbox = $('#select-all');        const selectAll = document.getElementById('select-all');

    const reportCheckboxes = $('.report-checkbox');        

    const bulkForm = $('#bulk-action-form');        bulkButton.disabled = checkedBoxes.length === 0;

    const applyButton = bulkForm.find('button[type="submit"]');        selectAll.checked = checkedBoxes.length === document.querySelectorAll('.report-checkbox').length;

    });

    // Handle select all checkbox});

    selectAllCheckbox.change(function() {

        const isChecked = $(this).is(':checked');// Status modal functions

        reportCheckboxes.prop('checked', isChecked);function openStatusModal(reportId, status) {

        updateApplyButton();    const modal = document.getElementById('status-modal');

    });    const form = document.getElementById('status-form');

    const statusInput = document.getElementById('modal-status');

    // Handle individual checkboxes    const title = document.getElementById('modal-title');

    reportCheckboxes.change(function() {    const message = document.getElementById('modal-message');

        const totalCheckboxes = reportCheckboxes.length;    

        const checkedCheckboxes = reportCheckboxes.filter(':checked').length;    form.action = `/admin/reports/${reportId}/status`;

            statusInput.value = status;

        selectAllCheckbox.prop('checked', totalCheckboxes === checkedCheckboxes);    

        updateApplyButton();    if (status === 'TRAITE') {

    });        title.textContent = 'Marquer comme traité';

        message.textContent = 'Êtes-vous sûr de vouloir marquer ce signalement comme traité ?';

    // Update apply button state    } else {

    function updateApplyButton() {        title.textContent = 'Marquer comme rejeté';

        const hasChecked = reportCheckboxes.filter(':checked').length > 0;        message.textContent = 'Êtes-vous sûr de vouloir marquer ce signalement comme rejeté ?';

        applyButton.prop('disabled', !hasChecked);    }

    }    

    modal.classList.remove('hidden');

    // Handle bulk form submission}

    bulkForm.submit(function(e) {

        const selectedStatus = $(this).find('select[name="status"]').val();function closeStatusModal() {

        const checkedCount = reportCheckboxes.filter(':checked').length;    const modal = document.getElementById('status-modal');

            modal.classList.add('hidden');

        if (!selectedStatus) {}

            e.preventDefault();

            alert('Veuillez sélectionner une action.');// Close modal when clicking outside

            return false;document.getElementById('status-modal').addEventListener('click', function(e) {

        }    if (e.target === this) {

                closeStatusModal();

        if (checkedCount === 0) {    }

            e.preventDefault();});

            alert('Veuillez sélectionner au moins un signalement.');</script>

            return false;@endsection
        }
        
        const statusText = selectedStatus === 'TRAITE' ? 'traités' : 'rejetés';
        if (!confirm(`Êtes-vous sûr de vouloir marquer ${checkedCount} signalement(s) comme ${statusText} ?`)) {
            e.preventDefault();
            return false;
        }
    });
});

// Individual report status update
function updateReportStatus(reportId, status) {
    const statusText = status === 'TRAITE' ? 'traité' : 'rejeté';
    
    if (!confirm(`Êtes-vous sûr de vouloir marquer ce signalement comme ${statusText} ?`)) {
        return;
    }
    
    // Create and submit form
    const form = $('<form>', {
        'method': 'POST',
        'action': `/admin/reports/${reportId}/status`
    });
    
    form.append($('<input>', {
        'type': 'hidden',
        'name': '_token',
        'value': $('meta[name="csrf-token"]').attr('content')
    }));
    
    form.append($('<input>', {
        'type': 'hidden',
        'name': '_method',
        'value': 'PATCH'
    }));
    
    form.append($('<input>', {
        'type': 'hidden',
        'name': 'status',
        'value': status
    }));
    
    form.appendTo('body').submit();
}
</script>
@endpush