<!DOCTYPE html>
<html>
<head>
    <title>Test IA Classification</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial; margin: 20px; }
        .test-container { max-width: 600px; margin: 0 auto; }
        .test-input { width: 100%; height: 100px; margin: 10px 0; }
        .test-button { background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .result { margin: 10px 0; padding: 10px; border: 1px solid #ddd; }
        .success { background: #d4edda; border-color: #c3e6cb; }
        .error { background: #f8d7da; border-color: #f5c6cb; }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🤖 Test IA Classification - BookShare</h1>
        
        <h3>Testez votre IA de classification des signalements</h3>
        
        <textarea id="testText" class="test-input" placeholder="Tapez un message de signalement pour tester l'IA...">L'utilisateur ne m'a jamais rendu le livre que je lui ai prêté</textarea>
        
        <button class="test-button" onclick="testAI()">🧠 Tester l'IA</button>
        
        <div id="result" class="result" style="display: none;"></div>
        
        <h4>📝 Exemples à tester :</h4>
        <ul>
            <li><a href="#" onclick="setTestText('L\'utilisateur ne m\'a jamais rendu le livre que je lui ai prêté')">Livre non rendu</a></li>
            <li><a href="#" onclick="setTestText('Cette personne m\'a insulté dans ses messages')">Comportement inapproprié</a></li>
            <li><a href="#" onclick="setTestText('Il y a un bug sur le site, je n\'arrive pas à me connecter')">Problème technique</a></li>
            <li><a href="#" onclick="setTestText('L\'utilisateur n\'est pas venu au rendez-vous d\'échange')">Rendez-vous manqué</a></li>
        </ul>
    </div>

    <script>
    function setTestText(text) {
        document.getElementById('testText').value = text;
    }

    async function testAI() {
        const text = document.getElementById('testText').value;
        const resultDiv = document.getElementById('result');
        
        if (!text.trim()) {
            showResult('❌ Veuillez saisir un texte à analyser', 'error');
            return;
        }
        
        showResult('🔄 Analyse en cours...', '');
        
        try {
            const response = await fetch('/api/classify-report', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    description: text
                })
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                const confidence = data.confidence;
                const type = data.suggested_type;
                const explanation = data.explanation;
                
                let typeLabel = {
                    'CONFLIT_ECHANGE': 'Conflit d\'échange',
                    'COMPORTEMENT': 'Comportement inapproprié',
                    'AUTRE': 'Autre problème'
                }[type] || type;
                
                showResult(`
                    <h4>✅ Classification réussie !</h4>
                    <p><strong>Type suggéré :</strong> <span style="background: #007bff; color: white; padding: 2px 8px; border-radius: 3px;">${typeLabel}</span></p>
                    <p><strong>Confiance :</strong> ${confidence}%</p>
                    <p><strong>Explication :</strong> ${explanation}</p>
                    <p><strong>IA Provider :</strong> ${data.ai_provider}</p>
                `, 'success');
            } else {
                showResult(`❌ Erreur: ${data.error || 'Classification échouée'}`, 'error');
            }
            
        } catch (error) {
            console.error('Erreur:', error);
            showResult(`❌ Erreur de connexion: ${error.message}`, 'error');
        }
    }
    
    function showResult(html, className) {
        const resultDiv = document.getElementById('result');
        resultDiv.innerHTML = html;
        resultDiv.className = 'result ' + className;
        resultDiv.style.display = 'block';
    }
    </script>
</body>
</html>