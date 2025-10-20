# 🤖 Chatbot IA Assistant - BookShare

## 📋 Vue d'Ensemble

Un assistant virtuel intelligent intégré à BookShare qui aide les utilisateurs à trouver des livres, répond à leurs questions et donne des recommandations personnalisées.

---

## ✨ Fonctionnalités

### Pour les Utilisateurs
- 💬 **Chat en temps réel** avec l'IA
- 📚 **Recommandations de livres** personnalisées
- 🔍 **Recherche intelligente** par description
- ❓ **Réponses aux questions** sur la plateforme
- 💡 **Suggestions automatiques** de questions
- 📝 **Historique de conversation** sauvegardé
- 🗑️ **Effacement de l'historique** à tout moment

### Capacités de l'IA
- Comprend le contexte de votre bibliothèque
- Recommande des livres selon vos goûts
- Explique comment utiliser la plateforme
- Répond en français de manière naturelle
- S'adapte à vos besoins

---

## 🛠️ Installation et Configuration

### 1. Package OpenAI Installé ✅
```bash
composer require openai-php/laravel
```

### 2. Configuration de la Clé API

**Obtenir une clé API OpenAI:**
1. Allez sur https://platform.openai.com/
2. Créez un compte ou connectez-vous
3. Allez dans "API Keys"
4. Créez une nouvelle clé API
5. Copiez la clé

**Ajouter dans votre fichier `.env`:**
```env
OPENAI_API_KEY=sk-votre-cle-api-ici
OPENAI_ORGANIZATION=
```

⚠️ **Important:** Ne partagez JAMAIS votre clé API publiquement!

### 3. Migration Exécutée ✅
```bash
php artisan migrate
```

Cela crée la table `chat_messages` pour stocker l'historique.

---

## 📊 Structure de la Base de Données

### Table: `chat_messages`

| Colonne | Type | Description |
|---------|------|-------------|
| id | bigint | Identifiant unique |
| user_id | bigint | ID de l'utilisateur (nullable) |
| session_id | string | ID de session pour tracking |
| role | enum | 'user' ou 'assistant' |
| message | text | Contenu du message |
| context | json | Contexte supplémentaire |
| created_at | timestamp | Date de création |

---

## 🎯 Utilisation

### Interface Utilisateur

**Le chatbot apparaît automatiquement** pour les utilisateurs connectés:

1. **Bouton flottant** en bas à droite de l'écran
2. **Cliquez** pour ouvrir la fenêtre de chat
3. **Tapez** votre question ou cliquez sur une suggestion
4. **Recevez** une réponse instantanée de l'IA

### Exemples de Questions

```
✅ "Recommande-moi un bon livre"
✅ "Quels sont les livres de science-fiction disponibles?"
✅ "Je cherche un roman d'aventure"
✅ "Comment emprunter un livre?"
✅ "Quels sont les livres les plus populaires?"
✅ "Je veux lire quelque chose de léger"
```

---

## 🔧 Architecture Technique

### Fichiers Créés

```
app/
├── Services/
│   └── ChatbotService.php          # Logique métier du chatbot
├── Http/Controllers/
│   └── ChatbotController.php       # Endpoints API
└── Models/
    └── ChatMessage.php             # Model Eloquent

database/
└── migrations/
    └── 2025_10_18_182714_create_chat_messages_table.php

resources/
└── views/
    └── components/
        └── chatbot-widget.blade.php # Widget frontend
```

### Routes API

```php
POST   /api/chatbot/message      // Envoyer un message
GET    /api/chatbot/history      // Récupérer l'historique
DELETE /api/chatbot/history      // Effacer l'historique
GET    /api/chatbot/suggestions  // Obtenir les suggestions
```

---

## 💡 Comment ça Marche

### 1. **Contexte Intelligent**

Le chatbot a accès à:
- 📚 Tous les livres de votre bibliothèque
- 🏷️ Toutes les catégories
- 🔥 Les livres récents
- 👤 L'historique de conversation de l'utilisateur

### 2. **Génération de Réponse**

```
User Message → ChatbotService → OpenAI GPT-3.5 → Response → User
                    ↓
              Save to Database
```

### 3. **Prompt System**

Le chatbot utilise un prompt système qui:
- Définit son rôle d'assistant BookShare
- Lui donne le contexte des livres disponibles
- Lui impose des règles (français, concis, amical)
- Lui fournit des exemples de questions

---

## 🎨 Personnalisation

### Modifier le Modèle IA

Dans `ChatbotService.php`:

```php
$response = OpenAI::chat()->create([
    'model' => 'gpt-3.5-turbo',  // Changez pour gpt-4 si besoin
    'max_tokens' => 500,          // Longueur de réponse
    'temperature' => 0.7,         // Créativité (0-1)
]);
```

### Modifier l'Apparence

Dans `chatbot-widget.blade.php`, modifiez les styles CSS:

```css
.chatbot-toggle {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    /* Changez les couleurs ici */
}
```

### Ajouter des Suggestions

Dans `ChatbotService.php`, méthode `getSuggestedQuestions()`:

```php
return [
    "Votre nouvelle suggestion",
    "Une autre question",
    // ...
];
```

---

## 📈 Statistiques et Monitoring

### Voir les Messages

```php
// Dans tinker
php artisan tinker

// Tous les messages
ChatMessage::all();

// Messages d'un utilisateur
ChatMessage::where('user_id', 1)->get();

// Messages récents
ChatMessage::orderBy('created_at', 'desc')->take(10)->get();
```

### Nettoyer les Vieux Messages

```php
// Supprimer les messages de plus de 30 jours
ChatMessage::where('created_at', '<', now()->subDays(30))->delete();
```

---

## 💰 Coûts OpenAI

### Tarification GPT-3.5-Turbo

- **Input:** ~$0.0005 / 1K tokens
- **Output:** ~$0.0015 / 1K tokens

### Estimation

**Pour 100 conversations/jour:**
- Moyenne: 200 tokens par conversation
- Coût journalier: ~$0.30
- Coût mensuel: ~$9

**Pour 1000 conversations/jour:**
- Coût mensuel: ~$90

💡 **Astuce:** Utilisez GPT-3.5-Turbo pour commencer (moins cher), passez à GPT-4 si besoin de meilleure qualité.

---

## 🔒 Sécurité

### Bonnes Pratiques

1. ✅ **Clé API dans .env** (jamais dans le code)
2. ✅ **Middleware auth** sur les routes
3. ✅ **Validation des inputs** (max 1000 caractères)
4. ✅ **Rate limiting** recommandé
5. ✅ **Logs des erreurs** activés

### Ajouter un Rate Limiter

Dans `routes/web.php`:

```php
Route::middleware(['auth', 'throttle:60,1'])->prefix('api')->group(function () {
    // 60 requêtes par minute max
    Route::post('/chatbot/message', ...);
});
```

---

## 🐛 Dépannage

### Erreur: "API key not found"

**Solution:**
```bash
# Vérifiez votre .env
cat .env | grep OPENAI

# Nettoyez le cache
php artisan config:clear
php artisan cache:clear
```

### Erreur: "Rate limit exceeded"

**Solution:** Vous avez dépassé le quota OpenAI. Attendez ou augmentez votre limite.

### Le chatbot ne s'affiche pas

**Solution:**
```bash
# Vérifiez que vous êtes connecté
# Le chatbot n'apparaît que pour les utilisateurs authentifiés

# Nettoyez les vues
php artisan view:clear
```

### Réponses lentes

**Solutions:**
1. Réduire `max_tokens` dans ChatbotService
2. Utiliser un cache pour les questions fréquentes
3. Optimiser le contexte des livres

---

## 🚀 Améliorations Futures

### Fonctionnalités Possibles

1. **Recherche Vocale** 🎤
   - Intégrer Whisper pour speech-to-text
   - Permettre des questions vocales

2. **Recommandations ML** 🧠
   - Analyser l'historique de lecture
   - Prédire les préférences

3. **Multi-langue** 🌍
   - Détecter la langue de l'utilisateur
   - Répondre dans sa langue

4. **Intégration Avancée** 🔗
   - Réserver un livre directement depuis le chat
   - Créer des avis via le chatbot
   - Rejoindre des groupes de lecture

5. **Analytics** 📊
   - Dashboard des questions fréquentes
   - Taux de satisfaction
   - Sujets populaires

---

## 📝 Exemples de Code

### Utiliser le Service Manuellement

```php
use App\Services\ChatbotService;

$chatbot = new ChatbotService();

// Envoyer un message
$response = $chatbot->generateResponse(
    "Recommande-moi un livre de science-fiction",
    "session-123"
);

// Récupérer l'historique
$history = $chatbot->getChatHistory("session-123");

// Effacer l'historique
$chatbot->clearChatHistory("session-123");
```

### Tester l'API avec cURL

```bash
# Envoyer un message
curl -X POST http://localhost:8000/api/chatbot/message \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-token" \
  -d '{"message": "Bonjour", "session_id": "test-123"}'

# Récupérer l'historique
curl http://localhost:8000/api/chatbot/history?session_id=test-123
```

---

## 🎓 Ressources

### Documentation Officielle
- [OpenAI API Docs](https://platform.openai.com/docs)
- [Laravel OpenAI Package](https://github.com/openai-php/laravel)

### Tutoriels
- [OpenAI Best Practices](https://platform.openai.com/docs/guides/best-practices)
- [Prompt Engineering Guide](https://platform.openai.com/docs/guides/prompt-engineering)

---

## ✅ Checklist de Test

- [ ] Le widget s'affiche en bas à droite
- [ ] Le bouton ouvre/ferme la fenêtre de chat
- [ ] Les suggestions de questions s'affichent
- [ ] Cliquer sur une suggestion envoie le message
- [ ] L'IA répond en français
- [ ] L'IA recommande des livres de votre bibliothèque
- [ ] L'historique est sauvegardé
- [ ] Le bouton "Effacer" fonctionne
- [ ] Les messages sont horodatés
- [ ] Le scroll automatique fonctionne
- [ ] Responsive sur mobile

---

## 🎉 Félicitations!

Votre chatbot IA est maintenant opérationnel! 🚀

**Prochaines étapes:**
1. Ajoutez votre clé API OpenAI dans `.env`
2. Testez le chatbot
3. Personnalisez selon vos besoins
4. Collectez les retours utilisateurs
5. Améliorez continuellement

**Besoin d'aide?** Consultez la documentation ou les logs d'erreur.

---

**Développé avec ❤️ pour BookShare**
