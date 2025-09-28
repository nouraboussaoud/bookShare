@extends('layouts.layout')

@section('title', 'BookShare - Créer un signalement')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-flag text-primary mr-2"></i>
                Créer un signalement
            </h1>
            <p class="mb-0 text-gray-600">Signalez un problème ou un comportement inapproprié</p>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Retour à mes signalements
        </a>
    </div>

    <!-- Main Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Formulaire de signalement</h6>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reports.store') }}">
                        @csrf

                        <!-- Type de rapport -->
                        <div class="form-group">
                            <label for="type">Type de signalement <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                <option value="">Sélectionnez un type</option>
                                @foreach(\App\Models\Report::getTypes() as $value => $label)
                                    <option value="{{ $value }}" {{ old('type', request('type')) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description du problème <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="5" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Décrivez en détail le problème rencontré ou le comportement à signaler..." 
                                      required>{{ old('description') }}</textarea>
                            <small class="form-text text-muted">Minimum 10 caractères, maximum 1000 caractères</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Utilisateur signalé (si pas d'échange pré-sélectionné) -->
                        @if(!$exchange)
                            <div id="reported-user-section" class="form-group" style="{{ old('type', request('type')) === 'COMPORTEMENT' || $reportedUser ? '' : 'display: none;' }}">
                                <label for="reported_user_search">Utilisateur à signaler</label>
                                @if($reportedUser)
                                    <input type="hidden" name="reported_user_id" value="{{ $reportedUser->id }}">
                                    <div class="alert alert-info">
                                        <i class="fas fa-user mr-2"></i>
                                        <strong>{{ $reportedUser->name }}</strong> ({{ $reportedUser->email }})
                                    </div>
                                @else
                                    <select name="reported_user_id" id="reported_user_search" class="form-control @error('reported_user_id') is-invalid @enderror">
                                        <option value="">Rechercher un utilisateur...</option>
                                        @foreach(\App\Models\User::where('id', '!=', auth()->id())->orderBy('name')->get() as $user)
                                            <option value="{{ $user->id }}" {{ old('reported_user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('reported_user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @endif
                            </div>
                        @endif

                        <!-- Échange signalé -->
                        @if($exchange)
                            <input type="hidden" name="exchange_id" value="{{ $exchange->id }}">
                            <div class="form-group">
                                <label>Échange concerné</label>
                                <div class="alert alert-info">
                                    <i class="fas fa-exchange-alt mr-2"></i>
                                    <strong>Échange #{{ $exchange->id }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        Initiateur: {{ $exchange->initiateur->name ?? 'N/A' }} • 
                                        Récepteur: {{ $exchange->recepteur->name ?? 'N/A' }} • 
                                        Statut: {{ $exchange->status }}
                                    </small>
                                </div>
                            </div>
                        @else
                            <div id="exchange-section" class="form-group" style="{{ old('type', request('type')) === 'CONFLIT_ECHANGE' ? '' : 'display: none;' }}">
                                <label for="exchange_id">Échange concerné</label>
                                <select name="exchange_id" id="exchange_id" class="form-control @error('exchange_id') is-invalid @enderror">
                                    <option value="">Sélectionnez un échange...</option>
                                    @php
                                        $userExchanges = \App\Models\Exchange::where(function($query) {
                                            $query->where('userInitiateurId', auth()->id())
                                                  ->orWhere('userRecepteurId', auth()->id());
                                        })->with(['initiateur', 'recepteur'])->orderBy('created_at', 'desc')->get();
                                    @endphp
                                    @foreach($userExchanges as $userExchange)
                                        <option value="{{ $userExchange->id }}" {{ old('exchange_id', request('exchange_id')) == $userExchange->id ? 'selected' : '' }}>
                                            Échange #{{ $userExchange->id }} - {{ $userExchange->initiateur->name ?? 'N/A' }} ↔ {{ $userExchange->recepteur->name ?? 'N/A' }} ({{ $userExchange->status }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('exchange_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <!-- Informations importantes -->
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle mr-2"></i>Informations importantes</h6>
                            <ul class="mb-0">
                                <li>Vous ne pouvez pas vous signaler vous-même</li>
                                <li>Les signalements abusifs peuvent entraîner des sanctions</li>
                                <li>Seuls les administrateurs peuvent voir vos signalements</li>
                                <li>Vous recevrez une notification lorsque votre signalement sera traité</li>
                            </ul>
                        </div>

                        <!-- Boutons -->
                        <div class="form-group text-right">
                            <a href="{{ route('reports.index') }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-times mr-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-flag mr-1"></i> Créer le signalement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Show/hide sections based on report type
    $('#type').change(function() {
        const type = $(this).val();
        
        if (type === 'COMPORTEMENT') {
            $('#reported-user-section').show();
            $('#exchange-section').hide();
            $('#exchange_id').val('').prop('required', false);
            $('#reported_user_search').prop('required', true);
        } else if (type === 'CONFLIT_ECHANGE') {
            $('#reported-user-section').hide();
            $('#exchange-section').show();
            $('#reported_user_search').val('').prop('required', false);
            $('#exchange_id').prop('required', true);
        } else {
            $('#reported-user-section').hide();
            $('#exchange-section').hide();
            $('#reported_user_search').val('').prop('required', false);
            $('#exchange_id').val('').prop('required', false);
        }
    });

    // Trigger change event on page load
    $('#type').trigger('change');
});
</script>
@endpush