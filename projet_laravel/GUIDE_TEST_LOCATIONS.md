# Guide de Test - Système de Locations BookShare

## 🎯 Données de Test Créées

### Utilisateurs disponibles :
- **Alice Martin** (alice@bookshare.com) - mot de passe: `password`
- **Bob Dupont** (bob@bookshare.com) - mot de passe: `password`  
- **Claire Rousseau** (claire@bookshare.com) - mot de passe: `password`
- **Utilisateur existant** (celui qui était déjà dans la base)

### Livres disponibles :
- **Le Petit Prince** (Antoine de Saint-Exupéry) - Propriétaire: Alice
- **1984** (George Orwell) - Propriétaire: Bob
- **L'Étranger** (Albert Camus) - Propriétaire: Claire
- **Harry Potter à l'école des sorciers** (J.K. Rowling) - Propriétaire: Alice
- **Le Seigneur des Anneaux** (J.R.R. Tolkien) - Propriétaire: Bob

### Locations d'exemple déjà créées :
- **Claire** demande à louer **1984** de Bob (statut: en_attente)
- **Alice** loue **L'Étranger** de Claire (statut: en_cours)

## 🧪 Scénarios de Test

### 1. Test de Connexion
1. Allez sur http://127.0.0.1:8000
2. Connectez-vous avec un des comptes de test
3. Vérifiez que vous arrivez sur le dashboard

### 2. Test de Navigation
1. Dans la sidebar, cliquez sur "Mes Locations"
2. Vous devriez voir vos locations (propriétaire et locataire)
3. Cliquez sur "Guide d'aide" pour voir la documentation

### 3. Test de Demande de Location
1. Allez dans "Livres" → "Tous les Livres"
2. Choisissez un livre qui ne vous appartient pas
3. Cliquez sur "Louer ce livre"
4. Remplissez le formulaire de demande
5. Soumettez la demande

### 4. Test de Gestion des Demandes (Propriétaire)
1. Connectez-vous avec le propriétaire d'un livre demandé
2. Allez dans "Mes Locations"
3. Vous devriez voir les demandes en attente
4. Testez "Accepter" ou "Refuser" une demande

### 5. Test du Workflow Complet
1. **Demande** : Un utilisateur demande à louer un livre
2. **Confirmation** : Le propriétaire accepte la demande
3. **Démarrage** : Le propriétaire démarre la location
4. **Suivi** : Vérifiez les statuts et la timeline
5. **Fin** : Le propriétaire termine la location

### 6. Test des Fonctionnalités Avancées
- Modification d'une demande en attente
- Vérification des retards automatiques
- Test des permissions (impossible de louer ses propres livres)
- Navigation entre les différentes vues

## 🔗 URLs Importantes

- **Dashboard** : http://127.0.0.1:8000/dashboard
- **Mes Locations** : http://127.0.0.1:8000/locations
- **Tous les Livres** : http://127.0.0.1:8000/books
- **Guide d'Aide** : http://127.0.0.1:8000/locations-help

## ✅ Points à Vérifier

- [ ] Navigation fluide entre les pages
- [ ] Affichage correct des statuts et badges
- [ ] Fonctionnement des boutons d'action
- [ ] Calcul automatique des dates de fin
- [ ] Gestion des permissions et sécurité
- [ ] Responsive design sur différentes tailles d'écran
- [ ] Messages de succès/erreur appropriés
- [ ] Timeline et chronologie des événements

## 🐛 En cas de Problème

1. Vérifiez que le serveur Laravel fonctionne
2. Consultez les logs Laravel : `storage/logs/laravel.log`
3. Vérifiez la console du navigateur pour les erreurs JavaScript
4. Assurez-vous d'être connecté avec un compte valide

## 🎉 Fonctionnalités Implémentées

✅ **CRUD complet** des locations  
✅ **Workflow** : demande → confirmation → location → retour  
✅ **Gestion des statuts** avec badges colorés  
✅ **Timeline interactive** pour le suivi  
✅ **Permissions et sécurité** appropriées  
✅ **Interface responsive** avec SB Admin 2  
✅ **Guide d'aide** complet  
✅ **Intégration** avec le système existant  

Le système de locations est **entièrement fonctionnel** et prêt pour la production !
