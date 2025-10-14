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

                    <!-- Alert IA pour suggestions -->
                    <div id="ai-suggestions" class="alert alert-info" style="display: none;">
                        <h6 class="mb-2">
                            <i class="fas fa-robot text-primary"></i> 
                            Suggestion IA - Classification Automatique
                        </h6>
                        <div id="ai-suggestion-content"></div>
                    </div>

                    <!-- Status IA -->
                    <div id="ai-status" class="text-right mb-3">
                        <small class="text-muted">
                            <i class="fas fa-brain text-success"></i> 
                            Assistant IA: <span id="ai-status-text">Prêt</span>
                        </small>
                    </div>

                    <form method="POST" action="{{ route('reports.store') }}">
                        @csrf

                        <!-- Type de rapport avec IA -->
                        <div class="form-group">
                            <label for="type">Type de signalement <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                    <option value="">Sélectionnez un type</option>
                                    @foreach(\App\Models\Report::getTypes() as $value => $label)
                                        <option value="{{ $value }}" {{ old('type', request('type')) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text" title="Classification automatique par IA">
                                        <i class="fas fa-magic text-primary" id="ai-magic-icon"></i>
                                    </span>
                                </div>
                            </div>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description avec IA en temps réel -->
                        <div class="form-group">
                            <label for="description">Description du problème <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <textarea name="description" id="description" rows="5" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          placeholder="Décrivez en détail le problème rencontré ou le comportement à signaler..." 
                                          required>{{ old('description') }}</textarea>
                                
                                <!-- Indicateur d'analyse IA -->
                                <div id="ai-analysis-indicator" class="position-absolute" style="top: 10px; right: 10px; display: none;">
                                    <small class="badge badge-info">
                                        <i class="fas fa-brain fa-pulse"></i> IA analyse...
                                    </small>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-1">
                                <small class="form-text text-muted">
                                    Minimum 10 caractères, maximum 1000 caractères
                                    <br>
                                    <i class="fas fa-lightbulb text-warning"></i> 
                                    L'IA analysera automatiquement votre description pour suggérer le type approprié
                                </small>
                                <small class="text-muted">
                                    <span id="char-count">0</span>/1000
                                </small>
                            </div>
                            
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
    let aiAnalysisTimeout;
    let lastAnalyzedText = '';
    
    // Ajouter le token CSRF pour les requêtes AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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

    // Compteur de caractères
    $('#description').on('input', function() {
        const text = $(this).val();
        const charCount = text.length;
        $('#char-count').text(charCount);
        
        // Couleur selon la longueur
        if (charCount > 900) {
            $('#char-count').removeClass('text-muted text-warning').addClass('text-danger');
        } else if (charCount > 700) {
            $('#char-count').removeClass('text-muted text-danger').addClass('text-warning');
        } else {
            $('#char-count').removeClass('text-warning text-danger').addClass('text-muted');
        }
        
        // Analyse IA avec délai pour éviter trop de requêtes
        clearTimeout(aiAnalysisTimeout);
        
        if (text.length >= 20 && text !== lastAnalyzedText) {
            aiAnalysisTimeout = setTimeout(() => {
                analyzeWithAI(text);
            }, 1500); // Attendre 1.5 secondes après arrêt de frappe
        }
    });

    // Fonction d'analyse IA avec fetch
    function analyzeWithAI(text) {
        // Éviter les analyses répétées du même texte
        if (text === lastAnalyzedText) return;
        
        lastAnalyzedText = text;
        showAIAnalysis();
        
        // Utiliser fetch au lieu de jQuery pour gérer CSRF
        fetch('{{ route("api.classify-report") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                description: text
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(response => {
            hideAIAnalysis();
            
            if (response.success && response.is_confident) {
                showAISuggestion(response);
            } else if (response.success) {
                showWeakAISuggestion(response);
            } else {
                showAIError('Analyse non concluante');
            }
        })
        .catch(error => {
            hideAIAnalysis();
            console.error('Erreur IA:', error);
            showAIError('Erreur lors de l\'analyse IA: ' + error.message);
        });
    }

    // Afficher l'indicateur d'analyse
    function showAIAnalysis() {
        $('#ai-analysis-indicator').show();
        $('#ai-status-text').text('Analyse en cours...');
        $('#ai-magic-icon').addClass('fa-spin');
    }

    // Masquer l'indicateur d'analyse
    function hideAIAnalysis() {
        $('#ai-analysis-indicator').hide();
        $('#ai-status-text').text('Analyse terminée');
        $('#ai-magic-icon').removeClass('fa-spin');
    }

    // Afficher suggestion IA forte
    function showAISuggestion(response) {
        const confidence = Math.round(response.confidence);
        
        $('#ai-suggestions').removeClass('alert-warning alert-danger').addClass('alert-success');
        $('#ai-suggestion-content').html(`
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>Type suggéré:</strong> 
                    <span class="badge badge-primary">${getTypeLabel(response.suggested_type)}</span>
                    <br>
                    <small class="text-muted">
                        ${response.explanation} 
                        <br>
                        <i class="fas fa-chart-line"></i> Confiance: ${confidence}%
                    </small>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-success" onclick="acceptAISuggestion('${response.suggested_type}')">
                        <i class="fas fa-check"></i> Accepter
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary ml-1" onclick="dismissAISuggestion()">
                        <i class="fas fa-times"></i> Ignorer
                    </button>
                </div>
            </div>
        `);
        $('#ai-suggestions').show();
    }

    // Afficher suggestion IA faible
    function showWeakAISuggestion(response) {
        const confidence = Math.round(response.confidence);
        
        $('#ai-suggestions').removeClass('alert-success alert-danger').addClass('alert-warning');
        $('#ai-suggestion-content').html(`
            <div>
                <strong>Suggestion incertaine:</strong> 
                <span class="badge badge-warning">${getTypeLabel(response.suggested_type)}</span>
                <small class="text-muted">(${confidence}% confiance)</small>
                <br>
                <small class="text-muted">
                    L'IA n'est pas sûre. Veuillez vérifier le type manuellement.
                    <button type="button" class="btn btn-link btn-sm p-0 ml-2" onclick="dismissAISuggestion()">Masquer</button>
                </small>
            </div>
        `);
        $('#ai-suggestions').show();
    }

    // Afficher erreur IA
    function showAIError(message) {
        $('#ai-suggestions').removeClass('alert-success alert-warning').addClass('alert-danger');
        $('#ai-suggestion-content').html(`
            <div>
                <i class="fas fa-exclamation-triangle"></i> ${message}
                <button type="button" class="btn btn-link btn-sm p-0 ml-2" onclick="dismissAISuggestion()">Masquer</button>
            </div>
        `);
        $('#ai-suggestions').show();
    }

    // Trigger change event on page load
    $('#type').trigger('change');
    
    // Test de connexion IA au chargement
    testAIConnection();
});

// Fonctions globales pour les boutons
function acceptAISuggestion(suggestedType) {
    $('#type').val(suggestedType).trigger('change');
    dismissAISuggestion();
    
    // Feedback visuel
    $('#type').addClass('border-success');
    setTimeout(() => {
        $('#type').removeClass('border-success');
    }, 2000);
}

function dismissAISuggestion() {
    $('#ai-suggestions').hide();
}

function getTypeLabel(type) {
    const labels = {
        'CONFLIT_ECHANGE': 'Conflit d\'échange',
        'COMPORTEMENT': 'Comportement inapproprié', 
        'AUTRE': 'Autre'
    };
    return labels[type] || type;
}

// Test de connexion IA
function testAIConnection() {
    $.ajax({
        url: '{{ route("api.test-ai-connection") }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#ai-status-text').text('IA connectée').addClass('text-success');
            } else {
                $('#ai-status-text').text('IA déconnectée').addClass('text-warning');
            }
        },
        error: function() {
            $('#ai-status-text').text('IA indisponible').addClass('text-danger');
        }
    });
}
</script>
@endpush