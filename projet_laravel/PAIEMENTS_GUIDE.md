# Guide Complet du Système de Paiement - BookShare

## 📋 Table des Matières
1. [Vue d'ensemble](#vue-densemble)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Fonctionnalités](#fonctionnalités)
5. [Workflow Complet](#workflow-complet)
6. [Notifications](#notifications)
7. [Test](#test)

---

## 🎯 Vue d'ensemble

Le système de paiement intégré dans BookShare permet aux locataires de payer leurs locations de livres via deux méthodes:
- **Stripe** (paiement instantané par carte bancaire)
- **Paiement Manuel** (PayPal, virement, espèces, autre)

Chaque action déclenche des **notifications automatiques** pour tenir informés le propriétaire et le locataire.

---

## 📦 Installation

### 1. Installer le package Stripe PHP

```bash
composer require stripe/stripe-php
```

### 2. Vérifier les migrations

Les migrations nécessaires sont déjà en place:
- `reservation_payments` - Table des paiements
- `notifications` - Table des notifications

Si nécessaire, exécutez:
```bash
php artisan migrate
```

---

## ⚙️ Configuration

### 1. Créer un compte Stripe

1. Allez sur [https://dashboard.stripe.com/register](https://dashboard.stripe.com/register)
2. Créez un compte
3. Activez le mode **Test** dans le dashboard

### 2. Récupérer les clés API

Dans le dashboard Stripe (mode Test):
1. Allez dans **Developers** → **API keys**
2. Copiez:
   - **Publishable key** (commence par `pk_test_`)
   - **Secret key** (commence par `sk_test_`)

### 3. Configurer le fichier .env

Ajoutez ces lignes dans votre fichier `.env`:

```env
# Stripe Configuration
STRIPE_KEY=pk_test_votre_cle_publique_ici
STRIPE_SECRET=sk_test_votre_cle_secrete_ici
STRIPE_WEBHOOK_SECRET=whsec_votre_secret_webhook_ici
```

### 4. Configuration Webhook (Optionnel mais recommandé)

Pour recevoir les confirmations de paiement:

1. Dans le dashboard Stripe: **Developers** → **Webhooks** → **Add endpoint**
2. URL du endpoint: `https://votresite.com/stripe/webhook`
3. Événements à écouter:
   - `checkout.session.completed`
   - `payment_intent.payment_failed`
4. Copiez le **Signing secret** et ajoutez-le dans `.env`

---

## 🚀 Fonctionnalités

### Routes disponibles

#### Routes Locataire (Paiement)
- `GET /payments` - Liste des paiements
- `GET /payments/{payment}` - Formulaire de paiement
- `POST /payments/{payment}/process` - Traiter paiement manuel
- `POST /payments/{payment}/cancel` - Annuler paiement
- `POST /payments/{payment}/stripe/checkout` - Créer session Stripe
- `GET /payments/{payment}/stripe/success` - Retour succès Stripe
- `GET /payments/{payment}/stripe/cancel` - Retour annulation Stripe

#### Routes Propriétaire/Admin
- `GET /reservation-payments` - Gestion des paiements
- Routes CRUD complètes disponibles

### Méthodes de Paiement

#### 1. Stripe (Recommandé)
- Paiement instantané
- Sécurisé SSL 256-bit
- Protection des acheteurs
- Toutes cartes acceptées (Visa, Mastercard, Amex...)
- 3D Secure intégré

#### 2. Paiement Manuel
- **PayPal**: Pour les transferts PayPal
- **Virement bancaire**: Pour les virements SEPA
- **Espèces**: Remise en personne
- **Autre**: Autres méthodes

---

## 🔄 Workflow Complet

### 1. Demande de Location
**Locataire** → Crée une demande de location

**Notifications:**
- ✉️ **Propriétaire** reçoit: "Nouvelle demande de location"

### 2. Acceptation par le Propriétaire
**Propriétaire** → Accepte la demande

**Actions automatiques:**
- ✅ Création automatique d'un paiement (statut: `en_attente`)
- ✉️ **Locataire** reçoit: "Location acceptée - Paiement requis"

### 3. Paiement par le Locataire
**Locataire** → Effectue le paiement (Stripe ou Manuel)

**Notifications:**
- ✉️ **Propriétaire** reçoit: "Paiement reçu" (avec méthode et montant)
- ✉️ **Locataire** reçoit: "Paiement confirmé" (avec référence)

### 4. Démarrage de la Location
**Propriétaire** → Démarre la location

**Vérification:** Le système vérifie que le paiement a été effectué

**Notifications:**
- ✉️ **Locataire** reçoit: "Location démarrée" (avec date de retour)
- ✉️ **Propriétaire** reçoit: "Location démarrée" (avec informations locataire)

### 5. Fin de la Location
**Propriétaire** → Marque la location comme terminée

**Notifications:**
- ✉️ **Locataire** reçoit: "Location terminée" (remerciements)
- ✉️ **Propriétaire** reçoit: "Location terminée" (confirmation retour)

### 6. Annulation (à tout moment avant paiement)
**Locataire** → Annule le paiement

**Actions automatiques:**
- ❌ Paiement marqué comme `annule`
- ❌ Location marquée comme `annulee`

**Notifications:**
- ✉️ **Propriétaire** reçoit: "Paiement annulé"

---

## 📬 Notifications

### Types de Notifications Implémentés

| Type | Destinataire | Déclencheur |
|------|-------------|-------------|
| `location_request` | Propriétaire | Nouvelle demande |
| `location_accepted_payment_required` | Locataire | Demande acceptée |
| `location_rejected` | Locataire | Demande refusée |
| `payment_received` | Propriétaire | Paiement effectué |
| `payment_confirmed` | Locataire | Paiement confirmé |
| `payment_cancelled` | Propriétaire | Paiement annulé |
| `location_started` | Locataire | Location démarre |
| `location_started_owner` | Propriétaire | Location démarre |
| `location_completed` | Locataire | Location terminée |
| `location_completed_owner` | Propriétaire | Location terminée |

### Service de Notifications

Toutes les notifications sont gérées par `NotificationService`:
- Création automatique des notifications
- Stockage en base de données
- Disponibles dans l'interface utilisateur
- Badge de compteur dans la navbar

---

## 🧪 Test

### Cartes de test Stripe

| Carte | Résultat |
|-------|----------|
| `4242 4242 4242 4242` | Succès |
| `4000 0000 0000 0002` | Décliné |
| `4000 0025 0000 3155` | Requiert 3D Secure |

- **Date d'expiration**: N'importe quelle date future (ex: 12/25)
- **CVC**: N'importe quel nombre à 3 chiffres (ex: 123)
- **Code postal**: N'importe quel code valide (ex: 75001)

### Test du Workflow Complet

1. **Créer une location:**
   ```
   Utilisateur A crée une demande de location → statut "en_attente"
   ```

2. **Vérifier les notifications:**
   ```
   Propriétaire (User B) reçoit notification "Nouvelle demande"
   ```

3. **Accepter la demande:**
   ```
   Propriétaire accepte → Paiement créé automatiquement
   ```

4. **Vérifier création paiement:**
   ```
   Locataire voit le paiement dans /payments
   Badge de notification affiché
   ```

5. **Effectuer un paiement Stripe:**
   ```
   Cliquer "Payer avec Stripe" → Remplir la carte test → Confirmer
   ```

6. **Vérifier les notifications:**
   ```
   Propriétaire reçoit "Paiement reçu"
   Locataire reçoit "Paiement confirmé"
   ```

7. **Démarrer la location:**
   ```
   Propriétaire démarre → Les deux parties sont notifiées
   ```

---

## 🔧 Dépannage

### Erreur: "Class 'Stripe\Stripe' not found"
**Solution:** Installez le package Stripe
```bash
composer require stripe/stripe-php
```

### Erreur: "Invalid API Key"
**Solution:** Vérifiez votre fichier `.env`:
- Clés bien copiées depuis le dashboard Stripe
- Mode Test activé
- Cache config nettoyé: `php artisan config:clear`

### Paiement non redirigé
**Solution:** Vérifiez les URLs de succès/annulation dans le code
- Doivent être des URLs absolues
- Doivent correspondre aux routes définies

### Notifications non reçues
**Solution:**
1. Vérifiez que `NotificationService` est bien injecté
2. Vérifiez la table `notifications` en base de données
3. Vérifiez les relations dans les modèles

---

## 📊 Tarification Stripe

- **Mode Test**: Gratuit illimité
- **Mode Production**:
  - 1,4% + 0,25€ par transaction (cartes européennes)
  - Pas de frais mensuels
  - Pas de frais d'installation

---

## 🔐 Sécurité

- ✅ Aucune information de carte ne transite par votre serveur
- ✅ PCI-DSS compliant (géré par Stripe)
- ✅ Vérification que l'utilisateur est bien le locataire
- ✅ Vérification du statut du paiement avant actions
- ✅ Protection CSRF sur tous les formulaires
- ✅ Validation des données côté serveur

---

## 📝 Notes Importantes

1. **Ne jamais commit les clés Stripe dans Git**
2. **Utiliser le mode Test** en développement
3. **Configurer les Webhooks** pour la production
4. **Tester tous les scénarios** avant mise en production
5. **Sauvegarder régulièrement** la base de données

---

## 🎉 Félicitations !

Votre système de paiement est maintenant opérationnel avec:
- ✅ Paiement Stripe sécurisé
- ✅ Paiement manuel flexible
- ✅ Notifications automatiques complètes
- ✅ Interface utilisateur intuitive
- ✅ Workflow complet de A à Z

**Bon partage de livres! 📚**
