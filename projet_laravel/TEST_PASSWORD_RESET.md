# 🧪 Test de la Fonctionnalité "Mot de Passe Oublié"

## ✅ Checklist de Test

### 1️⃣ **Page "Mot de passe oublié"**
- [ ] Accéder à http://localhost:8000/login
- [ ] Cliquer sur "Mot de passe oublié ?"
- [ ] Vérifier le design de la page `/forgot-password`
- [ ] Vérifier les icônes et le style
- [ ] Lire la card "Conseils de sécurité"

### 2️⃣ **Envoi de l'Email**
- [ ] Entrer un email **qui existe** dans la base de données
- [ ] Cliquer sur "Envoyer le lien de réinitialisation"
- [ ] Vérifier le message de succès : "We have emailed your password reset link!"

### 3️⃣ **Vérification de l'Email (Logs)**
Puisque `MAIL_MAILER=log`, l'email est dans les logs :

```bash
# Ouvrir un terminal et exécuter :
tail -f storage/logs/laravel.log
```

Vous devriez voir quelque chose comme :
```
[timestamp] local.INFO: 
To: user@example.com
Subject: Reset Password Notification
Reset Password: http://localhost:8000/reset-password/TOKEN_ICI?email=user@example.com
```

### 4️⃣ **Copier le Lien de Réinitialisation**
- [ ] Dans les logs, copier l'URL complète `http://localhost:8000/reset-password/TOKEN...`
- [ ] Coller dans le navigateur

### 5️⃣ **Page "Réinitialiser le mot de passe"**
- [ ] Vérifier que l'email est pré-rempli et en lecture seule
- [ ] Vérifier le design de la page
- [ ] Tester le bouton "Afficher/Masquer" le mot de passe

### 6️⃣ **Test de Force du Mot de Passe**
Taper progressivement pour voir la barre de force changer :

1. **Taper : `abc`**
   - [ ] Barre rouge "Faible"
   - [ ] Aucun critère validé (✓)

2. **Taper : `Abc123`**
   - [ ] Barre jaune/orange "Moyen"
   - [ ] 3 critères validés

3. **Taper : `Abc123@`**
   - [ ] Barre bleue "Bon"
   - [ ] 4 critères validés

4. **Taper : `Abc123@!`**
   - [ ] Barre verte "Excellent"
   - [ ] 5 critères validés ✅

### 7️⃣ **Validation des Critères**
Pendant la saisie, vérifier que les icônes changent :
- [ ] ⚪ → ✅ pour "Au moins 8 caractères"
- [ ] ⚪ → ✅ pour "Lettre majuscule"
- [ ] ⚪ → ✅ pour "Lettre minuscule"
- [ ] ⚪ → ✅ pour "Chiffre"
- [ ] ⚪ → ✅ pour "Caractère spécial"

### 8️⃣ **Confirmer le Mot de Passe**
- [ ] Taper le même mot de passe dans "Confirmer le mot de passe"
- [ ] Tester le bouton "Afficher/Masquer" sur ce champ aussi

### 9️⃣ **Soumettre le Formulaire**
- [ ] Cliquer sur "Réinitialiser le mot de passe"
- [ ] Vérifier la redirection vers `/login`
- [ ] Vérifier le message de succès

### 🔟 **Test de Connexion**
- [ ] Se connecter avec l'email
- [ ] Utiliser le **nouveau mot de passe**
- [ ] Vérifier que la connexion fonctionne ✅

---

## 🚨 Tests d'Erreurs

### Test 1 : Email inexistant
- [ ] Entrer un email qui n'existe pas
- [ ] Message d'erreur : "We can't find a user with that email address."

### Test 2 : Mots de passe différents
- [ ] Entrer deux mots de passe différents
- [ ] Message d'erreur sur "password_confirmation"

### Test 3 : Mot de passe trop faible
- [ ] Entrer un mot de passe simple comme "123456"
- [ ] Vérifier le message d'erreur de validation

### Test 4 : Token expiré
- [ ] Attendre 60+ minutes (ou modifier le timeout)
- [ ] Utiliser un vieux lien
- [ ] Message : "This password reset token is invalid."

### Test 5 : Utiliser le même token deux fois
- [ ] Réinitialiser le mot de passe une fois
- [ ] Essayer de réutiliser le même lien
- [ ] Le token devrait être invalide

---

## 📊 Scénarios de Test Complets

### Scénario A : Utilisateur légitime
1. Oublie son mot de passe
2. Va sur /login → "Mot de passe oublié ?"
3. Entre son email
4. Reçoit l'email (vérifie les logs)
5. Clique sur le lien
6. Crée un nouveau mot de passe fort
7. Se connecte avec succès

### Scénario B : Utilisateur malveillant
1. Entre un email qui n'existe pas
2. Reçoit un message d'erreur
3. Ne peut pas avancer

### Scénario C : Utilisateur distrait
1. Entre un mot de passe trop faible
2. Voit la barre rouge "Faible"
3. Lit les exigences
4. Corrige son mot de passe
5. Voit la barre devenir verte
6. Soumet avec succès

---

## 🔍 Points à Vérifier

### Design et UX
- [ ] Tous les textes sont en français
- [ ] Les icônes FontAwesome s'affichent correctement
- [ ] Les couleurs sont cohérentes avec le thème
- [ ] Les animations sont fluides
- [ ] Le design est responsive (mobile, tablette, desktop)

### Fonctionnalité
- [ ] Les routes fonctionnent
- [ ] La validation côté serveur fonctionne
- [ ] La validation côté client fonctionne (JavaScript)
- [ ] Les messages flash s'affichent correctement
- [ ] La redirection après succès fonctionne

### Sécurité
- [ ] Le token CSRF est présent
- [ ] Le mot de passe est haché en base de données
- [ ] Le token expire après 60 minutes
- [ ] L'email est validé côté serveur
- [ ] Les anciennes sessions sont invalidées

---

## 📝 Commandes Utiles

### Voir les logs en temps réel
```bash
tail -f storage/logs/laravel.log
```

### Nettoyer les anciens tokens (>60 min)
```bash
php artisan tinker
>>> DB::table('password_reset_tokens')->where('created_at', '<', now()->subMinutes(60))->delete();
```

### Voir tous les tokens actifs
```bash
php artisan tinker
>>> DB::table('password_reset_tokens')->get();
```

### Créer un utilisateur de test
```bash
php artisan tinker
>>> $user = new App\Models\User();
>>> $user->name = 'Test User';
>>> $user->email = 'test@bookshare.com';
>>> $user->password = bcrypt('OldPassword123!');
>>> $user->save();
```

---

## ✅ Résultat Attendu

Après tous les tests, vous devriez avoir :
- ✅ Une page "Mot de passe oublié" fonctionnelle et stylée
- ✅ Une page "Réinitialiser le mot de passe" avec indicateur de force
- ✅ Des emails dans les logs (en dev) ou envoyés (en prod)
- ✅ Un système de validation robuste
- ✅ Une expérience utilisateur fluide et sécurisée

---

## 🎯 Pour Aller Plus Loin

### En Production :
1. Configurer un vrai service d'email (Gmail, SendGrid, etc.)
2. Personnaliser davantage le template d'email
3. Ajouter un système de rate limiting
4. Implémenter l'authentification à deux facteurs (2FA)
5. Ajouter des notifications par SMS

### Monitoring :
- Surveiller le nombre de demandes de réinitialisation
- Détecter les tentatives d'attaque
- Logger les changements de mot de passe
- Envoyer une notification à l'utilisateur après changement

---

**Date de test** : _____________

**Testeur** : _____________

**Résultat** : ⭐⭐⭐⭐⭐ (5/5)
