@extends('layouts.app')

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
document.addEventListener('DOMContentLoaded', function() {
    let aiAnalysisTimeout;
    let lastAnalyzedText = '';
    
    // Show/hide sections based on report type
    const typeSelect = document.getElementById('type');
    typeSelect.addEventListener('change', function() {
        const type = this.value;
        const reportedUserSection = document.getElementById('reported-user-section');
        const exchangeSection = document.getElementById('exchange-section');
        const exchangeField = document.getElementById('exchange_id');
        const reportedUserField = document.getElementById('reported_user_search');
        
        if (type === 'COMPORTEMENT') {
            if (reportedUserSection) {
                reportedUserSection.style.display = 'block';
                if (reportedUserField) {
                    reportedUserField.required = true;
                }
            }
            if (exchangeSection) {
                exchangeSection.style.display = 'none';
                if (exchangeField) {
                    exchangeField.value = '';
                    exchangeField.required = false;
                }
            }
        } else if (type === 'CONFLIT_ECHANGE') {
            if (reportedUserSection) {
                reportedUserSection.style.display = 'none';
                if (reportedUserField) {
                    reportedUserField.value = '';
                    reportedUserField.required = false;
                }
            }
            if (exchangeSection) {
                exchangeSection.style.display = 'block';
                if (exchangeField) {
                    exchangeField.required = true;
                }
            }
        } else {
            if (reportedUserSection) {
                reportedUserSection.style.display = 'none';
                if (reportedUserField) {
                    reportedUserField.value = '';
                    reportedUserField.required = false;
                }
            }
            if (exchangeSection) {
                exchangeSection.style.display = 'none';
                if (exchangeField) {
                    exchangeField.value = '';
                    exchangeField.required = false;
                }
            }
        }
    });

    // Compteur de caractères et analyse IA
    const descriptionField = document.getElementById('description');
    descriptionField.addEventListener('input', function() {
        const text = this.value;
        const charCount = text.length;
        const charCountElement = document.getElementById('char-count');
        
        if (charCountElement) {
            charCountElement.textContent = charCount;
            
            // Couleur selon la longueur
            charCountElement.className = 'text-muted';
            if (charCount > 900) {
                charCountElement.className = 'text-danger';
            } else if (charCount > 700) {
                charCountElement.className = 'text-warning';
            }
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
        
        // 1. Analyse de classification (existante)
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
            console.error('Erreur IA Classification:', error);
            showAIError('Erreur lors de l\'analyse IA: ' + error.message);
        });
    }

    // Afficher l'indicateur d'analyse
    function showAIAnalysis() {
        const indicator = document.getElementById('ai-analysis-indicator');
        const statusText = document.getElementById('ai-status-text');
        const magicIcon = document.getElementById('ai-magic-icon');
        
        if (indicator) indicator.style.display = 'block';
        if (statusText) statusText.textContent = 'Analyse en cours...';
        if (magicIcon) magicIcon.classList.add('fa-spin');
    }

    // Masquer l'indicateur d'analyse
    function hideAIAnalysis() {
        const indicator = document.getElementById('ai-analysis-indicator');
        const statusText = document.getElementById('ai-status-text');
        const magicIcon = document.getElementById('ai-magic-icon');
        
        if (indicator) indicator.style.display = 'none';
        if (statusText) statusText.textContent = 'Analyse terminée';
        if (magicIcon) magicIcon.classList.remove('fa-spin');
    }

    // Afficher suggestion IA forte
    function showAISuggestion(response) {
        const confidence = Math.round(response.confidence);
        const aiSuggestions = document.getElementById('ai-suggestions');
        const aiSuggestionContent = document.getElementById('ai-suggestion-content');
        
        if (aiSuggestions && aiSuggestionContent) {
            aiSuggestions.className = 'alert alert-success';
            aiSuggestionContent.innerHTML = `
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
            `;
            aiSuggestions.style.display = 'block';
        }
    }

    // Afficher suggestion IA faible
    function showWeakAISuggestion(response) {
        const confidence = Math.round(response.confidence);
        const aiSuggestions = document.getElementById('ai-suggestions');
        const aiSuggestionContent = document.getElementById('ai-suggestion-content');
        
        if (aiSuggestions && aiSuggestionContent) {
            aiSuggestions.className = 'alert alert-warning';
            aiSuggestionContent.innerHTML = `
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
            `;
            aiSuggestions.style.display = 'block';
        }
    }

    // Afficher erreur IA
    function showAIError(message) {
        const aiSuggestions = document.getElementById('ai-suggestions');
        const aiSuggestionContent = document.getElementById('ai-suggestion-content');
        
        if (aiSuggestions && aiSuggestionContent) {
            aiSuggestions.className = 'alert alert-danger';
            aiSuggestionContent.innerHTML = `
                <div>
                    <i class="fas fa-exclamation-triangle"></i> ${message}
                    <button type="button" class="btn btn-link btn-sm p-0 ml-2" onclick="dismissAISuggestion()">Masquer</button>
                </div>
            `;
            aiSuggestions.style.display = 'block';
        }
    }

    // Trigger change event on page load
    if (typeSelect) {
        const event = new Event('change');
        typeSelect.dispatchEvent(event);
    }
    
    // Test de connexion IA au chargement
    testAIConnection();
});

// Fonctions globales pour les boutons
function acceptAISuggestion(suggestedType) {
    const typeSelect = document.getElementById('type');
    if (typeSelect) {
        typeSelect.value = suggestedType;
        const event = new Event('change');
        typeSelect.dispatchEvent(event);
    }
    dismissAISuggestion();
    
    // Feedback visuel
    if (typeSelect) {
        typeSelect.classList.add('border-success');
        setTimeout(() => {
            typeSelect.classList.remove('border-success');
        }, 2000);
    }
}

function dismissAISuggestion() {
    const aiSuggestions = document.getElementById('ai-suggestions');
    if (aiSuggestions) {
        aiSuggestions.style.display = 'none';
    }
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
    fetch('{{ route("api.test-ai-connection") }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(response => {
        const statusText = document.getElementById('ai-status-text');
        if (statusText) {
            if (response.success) {
                statusText.textContent = 'IA connectée';
                statusText.className = 'text-success';
            } else {
                statusText.textContent = 'IA déconnectée';
                statusText.className = 'text-warning';
            }
        }
    })
    .catch(error => {
        const statusText = document.getElementById('ai-status-text');
        if (statusText) {
            statusText.textContent = 'IA indisponible';
            statusText.className = 'text-danger';
        }
    });
}
</script>
@endpush