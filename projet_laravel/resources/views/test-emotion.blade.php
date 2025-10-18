<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test - Analyse d'Émotions IA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-heart mr-2"></i>
                            Test - Analyse d'Émotions IA
                        </h4>
                        <small>Testez l'analyse d'émotions en temps réel</small>
                    </div>
                    <div class="card-body">
                        
                        <!-- Zone de test -->
                        <div class="mb-4">
                            <label for="test-text" class="form-label">
                                <strong>Texte à analyser</strong>
                            </label>
                            <textarea id="test-text" class="form-control" rows="4" 
                                      placeholder="Écrivez votre message ici pour tester l'analyse d'émotions..."></textarea>
                            <small class="form-text text-muted">
                                Exemples : "Je suis très en colère contre ce comportement !", 
                                "J'ai peur que cela ne fonctionne pas", 
                                "Je suis triste de cette situation"
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <button id="analyze-btn" class="btn btn-primary">
                                <i class="fas fa-brain mr-1"></i>
                                Analyser les émotions
                            </button>
                        </div>

                        <!-- Zone de chargement -->
                        <div id="loading" class="text-center mt-4" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2 text-muted">Analyse en cours...</p>
                        </div>

                        <!-- Zone de résultats -->
                        <div id="results" class="mt-4" style="display: none;">
                            <!-- Les résultats seront affichés ici -->
                        </div>

                        <!-- Exemples prédéfinis -->
                        <div class="mt-5">
                            <h5>
                                <i class="fas fa-lightbulb text-warning mr-2"></i>
                                Exemples de test
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <button class="btn btn-outline-danger btn-sm w-100" onclick="testExample('Je suis vraiment en colère ! Cette personne est insupportable et m\\'a manqué de respect. C\\'est inacceptable !')">
                                        😠 Test Colère
                                    </button>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <button class="btn btn-outline-warning btn-sm w-100" onclick="testExample('J\\'ai très peur de cette situation. Je ne sais pas quoi faire et j\\'ai besoin d\\'aide immédiatement.')">
                                        😨 Test Peur
                                    </button>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <button class="btn btn-outline-primary btn-sm w-100" onclick="testExample('Je suis très triste de ce qui s\\'est passé. Cette situation me fait vraiment de la peine.')">
                                        😢 Test Tristesse
                                    </button>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <button class="btn btn-outline-success btn-sm w-100" onclick="testExample('Je suis content de ce service. Tout s\\'est bien passé et je recommande.')">
                                        😊 Test Joie
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Lien retour -->
                        <div class="text-center mt-4">
                            <a href="{{ route('reports.create') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Retour au formulaire de signalement
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('analyze-btn').addEventListener('click', function() {
            const text = document.getElementById('test-text').value.trim();
            
            if (!text) {
                alert('Veuillez saisir un texte à analyser.');
                return;
            }

            analyzeEmotion(text);
        });

        function testExample(exampleText) {
            document.getElementById('test-text').value = exampleText;
            analyzeEmotion(exampleText);
        }

        function analyzeEmotion(text) {
            // Afficher le chargement
            document.getElementById('loading').style.display = 'block';
            document.getElementById('results').style.display = 'none';
            document.getElementById('analyze-btn').disabled = true;

            // Appel à l'API
            fetch('{{ route("api.analyze-emotion") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    text: text
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                hideLoading();
                
                if (data.success) {
                    displayResults(data.emotion_analysis);
                } else {
                    displayError(data.message || 'Erreur lors de l\'analyse');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Erreur:', error);
                displayError('Erreur lors de l\'analyse: ' + error.message);
            });
        }

        function hideLoading() {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('analyze-btn').disabled = false;
        }

        function displayResults(emotionData) {
            const emotion = emotionData.emotion;
            const confidence = emotionData.confidence;
            const intensity = emotionData.intensity;
            const priority = emotionData.priority_level;
            const empathicMessage = emotionData.empathic_message;
            const allEmotions = emotionData.all_emotions || {};
            const recommendations = emotionData.recommendations || [];

            // Émojis par émotion
            const emotionEmojis = {
                'colère': '😠',
                'peur': '😨',
                'tristesse': '😢',
                'dégoût': '🤢',
                'joie': '😊',
                'surprise': '😲',
                'neutre': '😐'
            };

            const emoji = emotionEmojis[emotion] || '🤔';

            // Couleur selon la priorité
            let alertClass = 'alert-info';
            let priorityClass = 'badge-secondary';
            
            if (priority === 'critique') {
                alertClass = 'alert-danger';
                priorityClass = 'badge-danger';
            } else if (priority === 'haute') {
                alertClass = 'alert-warning';
                priorityClass = 'badge-warning';
            } else if (priority === 'moyenne') {
                alertClass = 'alert-primary';
                priorityClass = 'badge-primary';
            }

            const resultsHtml = `
                <div class="alert ${alertClass} border-0 shadow-sm">
                    <h5 class="alert-heading">
                        <span style="font-size: 1.5em;">${emoji}</span>
                        Résultats de l'analyse
                    </h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Émotion principale:</strong><br>
                            <span class="fs-5">${emotion.charAt(0).toUpperCase() + emotion.slice(1)}</span>
                            <br>
                            <small class="text-muted">Confiance: ${confidence}% | Intensité: ${intensity}</small>
                        </div>
                        <div class="col-md-6">
                            <strong>Priorité de traitement:</strong><br>
                            <span class="badge ${priorityClass} fs-6">
                                ${priority.charAt(0).toUpperCase() + priority.slice(1)}
                            </span>
                        </div>
                    </div>

                    <div class="alert alert-light border-primary">
                        <strong>💝 Message empathique automatique:</strong><br>
                        <em>"${empathicMessage}"</em>
                    </div>

                    ${recommendations.length > 0 ? `
                    <div class="mt-3">
                        <strong>📋 Recommandations pour le traitement:</strong>
                        <ul class="mt-2">
                            ${recommendations.map(rec => `<li>${rec}</li>`).join('')}
                        </ul>
                    </div>
                    ` : ''}

                    <div class="mt-3">
                        <strong>📊 Détail de toutes les émotions détectées:</strong>
                        <div class="row mt-2">
                            ${Object.entries(allEmotions).map(([em, score]) => `
                                <div class="col-6 col-md-4 mb-1">
                                    <small>
                                        ${emotionEmojis[em] || '🤔'} ${em}: 
                                        <strong>${score}%</strong>
                                    </small>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('results').innerHTML = resultsHtml;
            document.getElementById('results').style.display = 'block';
        }

        function displayError(message) {
            const errorHtml = `
                <div class="alert alert-danger">
                    <h5 class="alert-heading">
                        <i class="fas fa-exclamation-triangle"></i>
                        Erreur
                    </h5>
                    <p class="mb-0">${message}</p>
                </div>
            `;

            document.getElementById('results').innerHTML = errorHtml;
            document.getElementById('results').style.display = 'block';
        }
    </script>
</body>
</html>