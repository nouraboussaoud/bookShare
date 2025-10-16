@extends('layouts.admin-layout')@extends('layouts.admin-layout')



@section('title', 'BookShare - Détails du signalement #' . $report->id)@section('title', 'BookShare - Détails du signalement #' . $report->id)



@section('content')@section('content')

    <!-- Page Heading --><div class="container mx-auto px-4 py-8">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">    <div class="max-w-6xl mx-auto">

        <div>        <!-- Header -->

            <h1 class="h3 mb-0 text-gray-800">        <div class="bg-white rounded-lg shadow-md mb-6">

                <i class="fas fa-flag text-primary mr-2"></i>            <div class="bg-gradient-to-r from-red-500 to-pink-600 text-white p-6 rounded-t-lg">

                Signalement #{{ $report->id }}                <div class="flex justify-between items-start">

            </h1>                    <div>

            <p class="mb-0 text-gray-600">{{ \App\Models\Report::getTypes()[$report->type] }}</p>                        <h1 class="text-2xl font-bold">Signalement #{{ $report->id }}</h1>

        </div>                        <p class="text-red-100 mt-2">{{ \App\Models\Report::getTypes()[$report->type] }}</p>

        <div class="d-flex gap-2">                    </div>

            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary shadow-sm">                    <div class="flex items-center space-x-3">

                <i class="fas fa-arrow-left mr-1"></i>Retour à la liste                        <span class="px-3 py-1 rounded-full text-sm font-medium

            </a>                            @if($report->status === 'EN_ATTENTE') bg-yellow-200 text-yellow-800

            @if($report->isPending())                            @elseif($report->status === 'TRAITE') bg-green-200 text-green-800

                <button type="button" class="btn btn-success shadow-sm" onclick="updateReportStatus('TRAITE')">                            @else bg-red-200 text-red-800 @endif">

                    <i class="fas fa-check mr-1"></i>Traiter                            {{ \App\Models\Report::getStatuses()[$report->status] }}

                </button>                        </span>

                <button type="button" class="btn btn-danger shadow-sm" onclick="updateReportStatus('REJETE')">                        

                    <i class="fas fa-times mr-1"></i>Rejeter                        @if($report->isPending())

                </button>                            <div class="flex space-x-2">

            @endif                                <button onclick="openStatusModal('TRAITE')"

        </div>                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition duration-200">

    </div>                                    Traiter

                                </button>

    @if(session('success'))                                <button onclick="openStatusModal('REJETE')"

        <div class="alert alert-success alert-dismissible fade show" role="alert">                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition duration-200">

            {{ session('success') }}                                    Rejeter

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">                                </button>

                <span aria-hidden="true">&times;</span>                            </div>

            </button>                        @endif

        </div>                    </div>

    @endif                </div>

            </div>

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show" role="alert">            <!-- Main Content -->

            {{ session('error') }}            <div class="p-6">

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">                <div class="grid lg:grid-cols-3 gap-6">

                <span aria-hidden="true">&times;</span>                    <!-- Left Column - Report Details -->

            </button>                    <div class="lg:col-span-2 space-y-6">

        </div>                        <!-- Description -->

    @endif                        <div class="bg-gray-50 p-6 rounded-lg">

                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Description du problème</h3>

    <!-- Content Row -->                            <div class="prose prose-sm max-w-none">

    <div class="row">                                <p class="text-gray-700 whitespace-pre-wrap">{{ $report->description }}</p>

        <div class="col-lg-8">                            </div>

            <!-- Report Details Card -->                        </div>

            <div class="card shadow mb-4">

                <div class="card-header py-3">                        <!-- Exchange Details (if applicable) -->

                    <div class="d-flex justify-content-between align-items-center">                        @if($report->exchange)

                        <h6 class="m-0 font-weight-bold text-primary">                            <div class="bg-blue-50 p-6 rounded-lg">

                            <i class="fas fa-file-alt fa-sm text-primary"></i> Détails du signalement                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Échange concerné</h3>

                        </h6>                                <div class="grid md:grid-cols-2 gap-4">

                        @if($report->status === 'EN_ATTENTE')                                    <div>

                            <span class="badge badge-warning">En attente</span>                                        <h4 class="font-medium text-gray-900 mb-2">Informations de l'échange</h4>

                        @elseif($report->status === 'TRAITE')                                        <dl class="space-y-1 text-sm">

                            <span class="badge badge-success">Traité</span>                                            <div class="flex justify-between">

                        @else                                                <dt class="text-gray-600">ID:</dt>

                            <span class="badge badge-danger">Rejeté</span>                                                <dd class="text-gray-900">#{{ $report->exchange->id }}</dd>

                        @endif                                            </div>

                    </div>                                            <div class="flex justify-between">

                </div>                                                <dt class="text-gray-600">Livre:</dt>

                <div class="card-body">                                                <dd class="text-gray-900">{{ $report->exchange->bookDemande->title ?? 'N/A' }}</dd>

                    <div class="mb-4">                                            </div>

                        <h6 class="font-weight-bold text-gray-800">Description du problème:</h6>                                            <div class="flex justify-between">

                        <div class="bg-light p-3 rounded">                                                <dt class="text-gray-600">Statut:</dt>

                            <p class="mb-0 text-gray-700" style="white-space: pre-wrap;">{{ $report->description }}</p>                                                <dd class="text-gray-900">{{ $report->exchange->status }}</dd>

                        </div>                                            </div>

                    </div>                                            <div class="flex justify-between">

                                                <dt class="text-gray-600">Date début:</dt>

                    <div class="row">                                                <dd class="text-gray-900">

                        <div class="col-md-6">                                                    {{ $report->exchange->dateDebut ? \Carbon\Carbon::parse($report->exchange->dateDebut)->format('d/m/Y') : 'N/A' }}

                            <h6 class="font-weight-bold text-gray-800">Type de signalement:</h6>                                                </dd>

                            <p class="text-gray-600">                                            </div>

                                @if($report->type === 'CONFLIT_ECHANGE')                                        </dl>

                                    <span class="badge badge-info">Conflit d'échange</span>                                    </div>

                                @else                                    

                                    <span class="badge badge-warning">Comportement inapproprié</span>                                    <div>

                                @endif                                        <h4 class="font-medium text-gray-900 mb-2">Participants</h4>

                            </p>                                        <div class="space-y-2 text-sm">

                        </div>                                            <div class="flex items-center">

                        <div class="col-md-6">                                                <span class="w-20 text-gray-600">Demandeur:</span>

                            <h6 class="font-weight-bold text-gray-800">Date de création:</h6>                                                <span class="text-gray-900">{{ $report->exchange->initiateur->name ?? 'N/A' }}</span>

                            <p class="text-gray-600">{{ $report->created_at->format('d/m/Y à H:i') }}</p>                                            </div>

                        </div>                                            @if($report->exchange->recepteur)

                    </div>                                                <div class="flex items-center">

                </div>                                                    <span class="w-20 text-gray-600">Récepteur:</span>

            </div>                                                    <span class="text-gray-900">{{ $report->exchange->recepteur->name }}</span>

                                                </div>

            <!-- Exchange Details (if applicable) -->                                            @endif

            @if($report->exchange)                                        </div>

                <div class="card shadow mb-4">                                    </div>

                    <div class="card-header py-3">                                </div>

                        <h6 class="m-0 font-weight-bold text-primary">                                

                            <i class="fas fa-exchange-alt fa-sm text-primary"></i> Échange concerné                                <div class="mt-4 pt-4 border-t border-blue-200">

                        </h6>                                    <a href="{{ route('admin.exchanges.show', $report->exchange) }}" 

                    </div>                                       class="text-blue-600 hover:text-blue-800 text-sm underline">

                    <div class="card-body">                                        Voir tous les détails de l'échange →

                        <div class="row">                                    </a>

                            <div class="col-md-6">                                </div>

                                <h6 class="font-weight-bold text-gray-800">Informations de l'échange:</h6>                            </div>

                                <ul class="list-unstyled">                        @endif

                                    <li><strong>ID:</strong> #{{ $report->exchange->id }}</li>                    </div>

                                    <li><strong>Livre:</strong> {{ $report->exchange->bookDemande->title ?? 'N/A' }}</li>

                                    <li><strong>Statut:</strong>                     <!-- Right Column - Users and Actions -->

                                        @php                    <div class="space-y-6">

                                            $statusClass = 'secondary';                        <!-- Reporter Info -->

                                            switch($report->exchange->status) {                        <div class="bg-white border border-gray-200 p-4 rounded-lg">

                                                case 'EN_ATTENTE':                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Signalé par</h3>

                                                    $statusClass = 'warning';                            @if($report->reporter)

                                                    break;                                <div class="flex items-center">

                                                case 'EN_COURS':                                    <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center mr-3">

                                                    $statusClass = 'primary';                                        <span class="text-lg font-medium text-white">

                                                    break;                                            {{ substr($report->reporter->name, 0, 1) }}

                                                case 'TERMINE':                                        </span>

                                                    $statusClass = 'success';                                    </div>

                                                    break;                                    <div>

                                                case 'ANNULE':                                        <p class="font-medium text-gray-900">{{ $report->reporter->name }}</p>

                                                    $statusClass = 'danger';                                        <p class="text-sm text-gray-500">{{ $report->reporter->email }}</p>

                                                    break;                                        <p class="text-xs text-gray-400">

                                            }                                            Membre depuis {{ $report->reporter->created_at->format('M Y') }}

                                        @endphp                                        </p>

                                        <span class="badge badge-{{ $statusClass }}">{{ $report->exchange->status }}</span>                                    </div>

                                    </li>                                </div>

                                    <li><strong>Date début:</strong>                                 

                                        {{ $report->exchange->dateDebut ? \Carbon\Carbon::parse($report->exchange->dateDebut)->format('d/m/Y') : 'N/A' }}                                <div class="mt-3 pt-3 border-t border-gray-200">

                                    </li>                                    <div class="text-sm text-gray-600">

                                </ul>                                        <p>Signalements créés: {{ $report->reporter->reportsCreated->count() }}</p>

                            </div>                                        <p>Signalements reçus: {{ $report->reporter->reportsReceived->count() }}</p>

                            <div class="col-md-6">                                    </div>

                                <h6 class="font-weight-bold text-gray-800">Participants:</h6>                                </div>

                                <ul class="list-unstyled">                            @else

                                    <li><strong>Demandeur:</strong> {{ $report->exchange->initiateur->name ?? 'N/A' }}</li>                                <p class="text-gray-500">Utilisateur non disponible</p>

                                    @if($report->exchange->recepteur)                            @endif

                                        <li><strong>Récepteur:</strong> {{ $report->exchange->recepteur->name }}</li>                        </div>

                                    @endif

                                </ul>                        <!-- Reported User Info -->

                            </div>                        @if($report->reportedUser)

                        </div>                            <div class="bg-red-50 border border-red-200 p-4 rounded-lg">

                        <div class="mt-3">                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Utilisateur signalé</h3>

                            <a href="{{ route('admin.exchanges.show', $report->exchange) }}"                                 <div class="flex items-center">

                               class="btn btn-info btn-sm">                                    <div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center mr-3">

                                <i class="fas fa-eye mr-1"></i>Voir l'échange complet                                        <span class="text-lg font-medium text-white">

                            </a>                                            {{ substr($report->reportedUser->name, 0, 1) }}

                        </div>                                        </span>

                    </div>                                    </div>

                </div>                                    <div>

            @endif                                        <p class="font-medium text-gray-900">{{ $report->reportedUser->name }}</p>

        </div>                                        <p class="text-sm text-gray-500">{{ $report->reportedUser->email }}</p>

                                        <p class="text-xs text-gray-400">

        <div class="col-lg-4">                                            Membre depuis {{ $report->reportedUser->created_at->format('M Y') }}

            <!-- Reporter Info Card -->                                        </p>

            <div class="card shadow mb-4">                                    </div>

                <div class="card-header py-3">                                </div>

                    <h6 class="m-0 font-weight-bold text-primary">                                

                        <i class="fas fa-user fa-sm text-primary"></i> Signalé par                                <div class="mt-3 pt-3 border-t border-red-200">

                    </h6>                                    <div class="text-sm text-gray-600">

                </div>                                        <p>Signalements créés: {{ $report->reportedUser->reportsCreated->count() }}</p>

                <div class="card-body">                                        <p class="font-medium text-red-700">Signalements reçus: {{ $report->reportedUser->reportsReceived->count() }}</p>

                    @if($report->reporter)                                    </div>

                        <div class="text-center">                                </div>

                            <div class="icon-circle bg-primary mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">                            </div>

                                <i class="fas fa-user text-white"></i>                        @endif

                            </div>

                            <h6 class="font-weight-bold">{{ $report->reporter->name }}</h6>                        <!-- Report Metadata -->

                            <p class="text-gray-600 mb-2">{{ $report->reporter->email }}</p>                        <div class="bg-gray-50 p-4 rounded-lg">

                            <small class="text-gray-500">                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Informations du signalement</h3>

                                Membre depuis {{ $report->reporter->created_at->format('F Y') }}                            <dl class="space-y-2 text-sm">

                            </small>                                <div class="flex justify-between">

                        </div>                                    <dt class="text-gray-600">Créé le:</dt>

                        <hr>                                    <dd class="text-gray-900">{{ $report->created_at->format('d/m/Y à H:i') }}</dd>

                        <div class="text-center">                                </div>

                            <a href="{{ route('admin.users.edit', $report->reporter) }}"                                 <div class="flex justify-between">

                               class="btn btn-info btn-sm">                                    <dt class="text-gray-600">Modifié le:</dt>

                                <i class="fas fa-edit mr-1"></i>Modifier l'utilisateur                                    <dd class="text-gray-900">{{ $report->updated_at->format('d/m/Y à H:i') }}</dd>

                            </a>                                </div>

                        </div>                                <div class="flex justify-between">

                    @else                                    <dt class="text-gray-600">Type:</dt>

                        <p class="text-gray-500 text-center">Utilisateur non trouvé</p>                                    <dd class="text-gray-900">{{ \App\Models\Report::getTypes()[$report->type] }}</dd>

                    @endif                                </div>

                </div>                                <div class="flex justify-between">

            </div>                                    <dt class="text-gray-600">Statut:</dt>

                                    <dd>

            <!-- Reported User Info Card -->                                        <span class="px-2 py-1 text-xs font-medium rounded-full

            @if($report->reportedUser)                                            @if($report->status === 'EN_ATTENTE') bg-yellow-100 text-yellow-800

                <div class="card shadow mb-4">                                            @elseif($report->status === 'TRAITE') bg-green-100 text-green-800

                    <div class="card-header py-3">                                            @else bg-red-100 text-red-800 @endif">

                        <h6 class="m-0 font-weight-bold text-primary">                                            {{ \App\Models\Report::getStatuses()[$report->status] }}

                            <i class="fas fa-user-times fa-sm text-primary"></i> Utilisateur signalé                                        </span>

                        </h6>                                    </dd>

                    </div>                                </div>

                    <div class="card-body">                            </dl>

                        <div class="text-center">                        </div>

                            <div class="icon-circle bg-warning mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">

                                <i class="fas fa-user text-white"></i>                        <!-- Admin Actions -->

                            </div>                        <div class="bg-white border border-gray-200 p-4 rounded-lg">

                            <h6 class="font-weight-bold">{{ $report->reportedUser->name }}</h6>                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Actions administrateur</h3>

                            <p class="text-gray-600 mb-2">{{ $report->reportedUser->email }}</p>                            <div class="space-y-2">

                            <small class="text-gray-500">                                @if($report->isPending())

                                Membre depuis {{ $report->reportedUser->created_at->format('F Y') }}                                    <button onclick="openStatusModal('TRAITE')"

                            </small>                                            class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition duration-200">

                        </div>                                        Marquer comme traité

                        <hr>                                    </button>

                        <div class="text-center">                                    <button onclick="openStatusModal('REJETE')"

                            <a href="{{ route('admin.users.edit', $report->reportedUser) }}"                                             class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition duration-200">

                               class="btn btn-warning btn-sm">                                        Marquer comme rejeté

                                <i class="fas fa-edit mr-1"></i>Modifier l'utilisateur                                    </button>

                            </a>                                @else

                        </div>                                    <div class="text-sm text-gray-500 text-center py-2">

                    </div>                                        Ce signalement a déjà été traité

                </div>                                    </div>

            @endif                                @endif

                                

            <!-- Admin Actions Card -->                                <form method="POST" action="{{ route('admin.reports.destroy', $report) }}" 

            <div class="card shadow mb-4">                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce signalement ?')" 

                <div class="card-header py-3">                                      class="mt-4">

                    <h6 class="m-0 font-weight-bold text-primary">                                    @csrf

                        <i class="fas fa-cogs fa-sm text-primary"></i> Actions admin                                    @method('DELETE')

                    </h6>                                    <button type="submit" 

                </div>                                            class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition duration-200">

                <div class="card-body">                                        Supprimer le signalement

                    @if($report->isPending())                                    </button>

                        <div class="d-grid gap-2">                                </form>

                            <button type="button" class="btn btn-success" onclick="updateReportStatus('TRAITE')">                            </div>

                                <i class="fas fa-check mr-1"></i>Marquer comme traité                        </div>

                            </button>                    </div>

                            <button type="button" class="btn btn-danger" onclick="updateReportStatus('REJETE')">                </div>

                                <i class="fas fa-times mr-1"></i>Rejeter le signalement            </div>

                            </button>        </div>

                        </div>

                    @else        <!-- Navigation -->

                        <div class="text-center">        <div class="flex justify-between">

                            <p class="text-gray-600 mb-3">            <a href="{{ route('admin.reports.index') }}" 

                                Ce signalement a été                class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md transition duration-200">

                                <strong>{{ $report->status === 'TRAITE' ? 'traité' : 'rejeté' }}</strong>                ← Retour à la liste

                            </p>            </a>

                            @if($report->status === 'TRAITE')        </div>

                                <button type="button" class="btn btn-warning btn-sm" onclick="updateReportStatus('REJETE')">    </div>

                                    <i class="fas fa-times mr-1"></i>Rejeter</div>

                                </button>

                            @else<!-- Status Update Modal -->

                                <button type="button" class="btn btn-success btn-sm" onclick="updateReportStatus('TRAITE')"><div id="status-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">

                                    <i class="fas fa-check mr-1"></i>Traiter    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">

                                </button>        <div class="mt-3 text-center">

                            @endif            <h3 class="text-lg font-medium text-gray-900" id="modal-title">Changer le statut</h3>

                        </div>            <div class="mt-2 px-7 py-3">

                    @endif                <p class="text-sm text-gray-500" id="modal-message">

                    Êtes-vous sûr de vouloir changer le statut de ce signalement ?

                    <hr>                </p>

                                </div>

                    <div class="text-center">            <div class="items-center px-4 py-3">

                        <form method="POST" action="{{ route('admin.reports.destroy', $report) }}"                 <form method="POST" action="{{ route('admin.reports.updateStatus', $report) }}">

                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce signalement ?')"                     @csrf

                              class="d-inline">                    @method('PATCH')

                            @csrf                    <input type="hidden" name="status" id="modal-status">

                            @method('DELETE')                    <div class="flex justify-center space-x-3">

                            <button type="submit" class="btn btn-danger btn-sm">                        <button type="button" onclick="closeStatusModal()"

                                <i class="fas fa-trash mr-1"></i>Supprimer                                class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">

                            </button>                            Annuler

                        </form>                        </button>

                    </div>                        <button type="submit"

                </div>                                class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">

            </div>                            Confirmer

        </div>                        </button>

    </div>                    </div>

@endsection                </form>

            </div>

@push('scripts')        </div>

<script>    </div>

function updateReportStatus(status) {</div>

    const statusText = status === 'TRAITE' ? 'traité' : 'rejeté';

    <script>

    if (!confirm(`Êtes-vous sûr de vouloir marquer ce signalement comme ${statusText} ?`)) {function openStatusModal(status) {

        return;    const modal = document.getElementById('status-modal');

    }    const statusInput = document.getElementById('modal-status');

        const title = document.getElementById('modal-title');

    // Create and submit form    const message = document.getElementById('modal-message');

    const form = $('<form>', {    

        'method': 'POST',    statusInput.value = status;

        'action': `{{ route('admin.reports.updateStatus', $report) }}`    

    });    if (status === 'TRAITE') {

            title.textContent = 'Marquer comme traité';

    form.append($('<input>', {        message.textContent = 'Êtes-vous sûr de vouloir marquer ce signalement comme traité ? Le signaleur sera notifié.';

        'type': 'hidden',    } else {

        'name': '_token',        title.textContent = 'Marquer comme rejeté';

        'value': $('meta[name="csrf-token"]').attr('content')        message.textContent = 'Êtes-vous sûr de vouloir marquer ce signalement comme rejeté ? Le signaleur sera notifié.';

    }));    }

        

    form.append($('<input>', {    modal.classList.remove('hidden');

        'type': 'hidden',}

        'name': '_method',

        'value': 'PATCH'function closeStatusModal() {

    }));    const modal = document.getElementById('status-modal');

        modal.classList.add('hidden');

    form.append($('<input>', {}

        'type': 'hidden',

        'name': 'status',// Close modal when clicking outside

        'value': statusdocument.getElementById('status-modal').addEventListener('click', function(e) {

    }));    if (e.target === this) {

            closeStatusModal();

    form.appendTo('body').submit();    }

}});

</script></script>

@endpush@endsection
