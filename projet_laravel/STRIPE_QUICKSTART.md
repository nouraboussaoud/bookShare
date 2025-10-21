# 🚀 Configuration Rapide Stripe (5 minutes)

## Étape 1: Créer un compte Stripe

1. Allez sur **https://dashboard.stripe.com/register**
2. Remplissez le formulaire d'inscription
3. **Activez le mode Test** (switch en haut à droite après connexion)

## Étape 2: Obtenir vos clés API

1. Dans le dashboard Stripe, cliquez sur **Developers** (menu de gauche)
2. Cliquez sur **API keys**
3. Vous verrez 2 clés:
   - **Publishable key** (commence par `pk_test_...`)
   - **Secret key** (commence par `sk_test_...`, cliquez sur "Reveal test key")

## Étape 3: Configurer BookShare

1. Ouvrez le fichier `.env` à la racine du projet
2. Ajoutez ces 3 lignes à la fin du fichier:

```env
STRIPE_KEY=pk_test_COPIEZ_VOTRE_CLE_PUBLIQUE_ICI
STRIPE_SECRET=sk_test_COPIEZ_VOTRE_CLE_SECRETE_ICI
STRIPE_WEBHOOK_SECRET=
```

3. **Remplacez** `pk_test_...` et `sk_test_...` par vos vraies clés

## Étape 4: Redémarrer Laravel

Dans votre terminal, exécutez:

```bash
php artisan config:clear
```

## Étape 5: Tester!

1. Rechargez la page de paiement
2. Vous devriez voir le bouton **"Payer avec Stripe"** en bleu/violet
3. Cliquez dessus pour être redirigé vers la page de paiement Stripe
4. Utilisez cette carte de test:

```
Numéro: 4242 4242 4242 4242
Date: 12/25 (n'importe quelle date future)
CVC: 123 (n'importe quel code 3 chiffres)
Email: test@example.com
```

5. Validez → Vous serez redirigé vers BookShare avec le paiement confirmé! ✅

## 🎯 Résultat Attendu

**AVANT la configuration:**
```
⚠️ Paiement Stripe temporairement indisponible
```

**APRÈS la configuration:**
```
[Bouton bleu Stripe] Payer X.XX€ avec Stripe
```

## ❓ Problèmes Courants

### Le bouton n'apparaît toujours pas
- Vérifiez que vous avez bien copié les clés complètes (elles sont longues!)
- Assurez-vous qu'il n'y a pas d'espaces avant/après les clés dans le .env
- Relancez `php artisan config:clear`

### Erreur "Invalid API Key"
- Vous utilisez peut-être les clés **live** au lieu des clés **test**
- Les clés test commencent par `pk_test_` et `sk_test_`
- Les clés live commencent par `pk_live_` et `sk_live_`

### Le paiement échoue
- C'est normal si vous utilisez une carte réelle en mode test!
- Utilisez uniquement la carte test: **4242 4242 4242 4242**

## 📚 Plus d'Infos

Consultez `STRIPE_SETUP.md` pour la documentation complète incluant:
- Configuration des webhooks
- Autres cartes de test
- Passage en production
- Tarification Stripe

## 💡 Note Importante

- **Mode Test**: Aucun argent réel n'est débité
- **Gratuit**: Les tests sont illimités et gratuits
- **Sécurisé**: Vos clés test ne peuvent pas traiter de vrais paiements
