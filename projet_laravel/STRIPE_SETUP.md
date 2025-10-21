# Configuration de Stripe pour BookShare

## 📋 Prérequis

1. Un compte Stripe (gratuit pour les tests)
2. Les clés API Stripe (Test Mode)

## 🚀 Installation

### Étape 1: Créer un compte Stripe

1. Allez sur [https://dashboard.stripe.com/register](https://dashboard.stripe.com/register)
2. Créez un compte gratuit
3. Activez le mode **Test** dans le dashboard (en haut à droite)

### Étape 2: Récupérer les clés API

1. Dans le dashboard Stripe, allez dans **Developers → API keys**
2. Copiez les clés suivantes:
   - **Publishable key** (commence par `pk_test_...`)
   - **Secret key** (commence par `sk_test_...`)

### Étape 3: Configurer le fichier .env

Ajoutez ces lignes dans votre fichier `.env`:

```env
# Stripe Payment Configuration
STRIPE_KEY=pk_test_votre_cle_publique
STRIPE_SECRET=sk_test_votre_cle_secrete
STRIPE_WEBHOOK_SECRET=whsec_votre_secret_webhook
```

⚠️ **Important**: 
- Pour les tests, utilisez les clés **test** (pk_test_, sk_test_)
- Pour la production, utilisez les clés **live** (pk_live_, sk_live_)
- Ne commitez JAMAIS vos clés dans Git!

### Étape 4: Configurer les Webhooks (Optionnel pour les tests)

Les webhooks permettent à Stripe de notifier votre application des événements de paiement.

1. Dans le dashboard Stripe, allez dans **Developers → Webhooks**
2. Cliquez sur **Add endpoint**
3. URL de l'endpoint: `https://votre-domaine.com/stripe/webhook`
4. Sélectionnez les événements:
   - `checkout.session.completed`
   - `payment_intent.payment_failed`
5. Copiez le **Signing secret** (commence par `whsec_...`)
6. Ajoutez-le dans votre `.env` comme `STRIPE_WEBHOOK_SECRET`

Pour les tests en local, utilisez [Stripe CLI](https://stripe.com/docs/stripe-cli):

```bash
stripe listen --forward-to localhost:8000/stripe/webhook
```

## 💳 Cartes de Test Stripe

Pour tester les paiements, utilisez ces numéros de carte fictifs:

### ✅ Paiement Réussi
```
Numéro: 4242 4242 4242 4242
Date: N'importe quelle date future (ex: 12/25)
CVC: N'importe quel code 3 chiffres (ex: 123)
```

### ❌ Paiement Échoué
```
Numéro: 4000 0000 0000 0002
Date: N'importe quelle date future
CVC: N'importe quel code 3 chiffres
```

### 🔐 Authentification 3D Secure Requise
```
Numéro: 4000 0025 0000 3155
Date: N'importe quelle date future
CVC: N'importe quel code 3 chiffres
```

Plus de cartes de test: [https://stripe.com/docs/testing](https://stripe.com/docs/testing)

## 🎯 Workflow de Paiement

1. **Utilisateur A** demande une location d'un livre
2. **Utilisateur B** (propriétaire) accepte la demande
   - → Un paiement est créé automatiquement
   - → Une notification est envoyée à l'Utilisateur A
3. **Utilisateur A** clique sur "Paiements" dans la navbar
4. **Utilisateur A** clique sur le bouton "Payer avec Stripe"
5. Redirection vers la page de paiement Stripe Checkout
6. **Utilisateur A** entre ses informations de carte
7. Après paiement réussi:
   - → Redirection vers la page de succès
   - → Le paiement est marqué comme "complété"
   - → Notifications envoyées aux deux parties
8. **Utilisateur B** peut démarrer la location

## 🔧 Dépannage

### Erreur: "No API key provided"
- Vérifiez que `STRIPE_SECRET` est bien défini dans `.env`
- Lancez `php artisan config:clear`

### Erreur: "Invalid API Key"
- Vérifiez que vous utilisez la bonne clé (test vs live)
- Assurez-vous qu'il n'y a pas d'espaces avant/après la clé

### Le webhook ne fonctionne pas
- Vérifiez que l'URL du webhook est accessible publiquement
- Pour les tests locaux, utilisez `ngrok` ou `Stripe CLI`

## 📚 Documentation

- [Documentation Stripe PHP](https://stripe.com/docs/api/php)
- [Stripe Checkout](https://stripe.com/docs/payments/checkout)
- [Webhooks Stripe](https://stripe.com/docs/webhooks)

## 💰 Tarification Stripe

- **Tests**: Gratuit et illimité
- **Production**: 
  - 1,4% + 0,25€ par transaction réussie (cartes européennes)
  - 2,9% + 0,25€ pour les autres cartes
  - Pas d'abonnement mensuel

## 🔐 Sécurité

- ✅ Les paiements sont entièrement gérés par Stripe (PCI-DSS compliant)
- ✅ Aucune information de carte bancaire ne transite par votre serveur
- ✅ Chiffrement SSL/TLS automatique
- ✅ Protection contre la fraude incluse
- ✅ Authentification 3D Secure (SCA) supportée

## 📝 Notes

- En mode test, aucune carte réelle n'est débitée
- Tous les paiements de test apparaissent dans le dashboard Stripe
- Vous pouvez simuler tous les scénarios (succès, échec, remboursement, etc.)
