# 📋 Guide Complet : Gestion des Paiements de Réservation

## 🎯 Vue d'ensemble

Le système de paiements de réservation permet de gérer tous les aspects financiers liés à la location de livres entre utilisateurs.

---

## 📖 Scénario d'utilisation

### 1️⃣ **Création d'une Réservation** (Location)

1. Un utilisateur (Locataire) trouve un livre dans le Marketplace
2. Il crée une demande de réservation en spécifiant :
   - La durée de location
   - La localisation
   - Le prix convenu

**Statut initial** : `en_attente`

---

### 2️⃣ **Gestion des Paiements**

Une fois la réservation créée, plusieurs types de paiements peuvent être enregistrés :

#### 💰 **Paiement de Location**
- **Quand** : Au moment de la confirmation de la réservation
- **Montant** : Prix convenu pour la location
- **Payé par** : Locataire
- **Reçu par** : Propriétaire

#### 🔒 **Caution (Dépôt de garantie)**
- **Quand** : Avant la remise du livre
- **Montant** : Variable (généralement 20-50% du prix du livre)
- **Payé par** : Locataire
- **Reçu par** : Propriétaire
- **Note** : Remboursable si le livre est retourné en bon état

#### ⚠️ **Pénalité**
- **Quand** : En cas de retard ou de dommage
- **Montant** : Selon les conditions (ex: 2€/jour de retard)
- **Payé par** : Locataire
- **Reçu par** : Propriétaire

#### 🔄 **Remboursement**
- **Quand** : Après retour du livre en bon état
- **Montant** : Montant de la caution
- **Payé par** : Propriétaire
- **Reçu par** : Locataire

---

## 🔄 Flux Complet du Processus

### Étape 1 : Demande de Réservation
```
[Locataire] → Crée une demande → [Statut: en_attente]
```

### Étape 2 : Confirmation du Propriétaire
```
[Propriétaire] → Confirme la demande → [Statut: confirmee]
         ↓
  Paiement de location requis
         ↓
[Locataire] → Effectue le paiement → [Type: location, Statut: complete]
```

### Étape 3 : Remise du Livre
```
[Propriétaire] → Demande une caution (optionnel)
         ↓
[Locataire] → Paye la caution → [Type: caution, Statut: complete]
         ↓
[Propriétaire] → Démarre la location → [Statut: en_cours]
```

### Étape 4 : Retour du Livre
```
[Locataire] → Retourne le livre
         ↓
[Propriétaire] → Vérifie l'état du livre
         ↓
    ┌─────────┴─────────┐
    ↓                   ↓
Livre OK            Livre endommagé/En retard
    ↓                   ↓
Rembourse caution    Applique pénalité
    ↓                   ↓
[Type: remboursement]  [Type: penalite]
    ↓                   ↓
Termine location    Rembourse le reste (si applicable)
    ↓                   ↓
[Statut: terminee]  [Statut: terminee]
```

---

## 🎭 Rôles et Permissions

### 👤 **Locataire** (celui qui loue le livre)
- ✅ Voir ses paiements
- ✅ Créer un paiement de location
- ✅ Créer un paiement de caution
- ❌ Ne peut PAS supprimer un paiement
- ❌ Ne peut PAS rembourser

### 🏠 **Propriétaire** (celui qui prête le livre)
- ✅ Voir tous les paiements de ses réservations
- ✅ Créer tous types de paiements
- ✅ Marquer un paiement comme complet
- ✅ Rembourser une caution
- ✅ Créer une pénalité
- ✅ Supprimer un paiement

---

## 💡 Exemples Pratiques

### Exemple 1 : Location Simple (Sans Caution)

1. **Alice** veut louer le livre "Harry Potter" de **Bob** pour 7 jours à 5€
2. Bob accepte la demande → Réservation confirmée
3. Alice paie 5€ → Paiement enregistré (Type: location, Statut: complete)
4. Bob remet le livre → Location démarre
5. Après 7 jours, Alice rend le livre
6. Bob termine la location → Terminé

**Paiements totaux** : 1 paiement de 5€

---

### Exemple 2 : Location avec Caution

1. **Marie** veut louer "Le Seigneur des Anneaux" de **Paul** pour 14 jours à 10€
2. Paul accepte et demande une caution de 20€
3. Marie paie :
   - 10€ pour la location → (Type: location)
   - 20€ de caution → (Type: caution)
4. Paul remet le livre → Location démarre
5. Après 14 jours, Marie rend le livre en bon état
6. Paul vérifie et rembourse :
   - 20€ de caution → (Type: remboursement)
7. Paul termine la location

**Paiements totaux** :
- Location : 10€ (Marie → Paul)
- Caution : 20€ (Marie → Paul)
- Remboursement : 20€ (Paul → Marie)
- **Net pour Marie** : 10€
- **Net pour Paul** : 10€

---

### Exemple 3 : Location avec Retard

1. **Tom** loue "1984" à **Sophie** pour 10 jours à 8€
2. Sophie accepte et demande 15€ de caution
3. Tom paie 8€ + 15€ caution
4. Tom est en retard de 3 jours (pénalité : 2€/jour)
5. Sophie calcule :
   - Pénalité : 3 jours × 2€ = 6€
   - Remboursement : 15€ - 6€ = 9€
6. Sophie enregistre :
   - Pénalité de 6€ → (Type: penalite)
   - Remboursement de 9€ → (Type: remboursement)

**Paiements totaux** :
- Location : 8€
- Caution : 15€
- Pénalité : 6€
- Remboursement : 9€
- **Total payé par Tom** : 8€ + 6€ = 14€
- **Total reçu par Sophie** : 14€

---

## 📊 Statuts des Paiements

| Statut | Description | Utilisé pour |
|--------|-------------|--------------|
| ⏳ **En attente** | Paiement pas encore effectué | Paiement prévu mais non reçu |
| ✅ **Complété** | Paiement reçu et validé | Paiement effectif |
| ❌ **Échoué** | Transaction échouée | Tentative de paiement ratée |
| 💸 **Remboursé** | Fonds retournés | Caution remboursée |
| 🚫 **Annulé** | Transaction annulée | Paiement annulé avant traitement |

---

## 🔗 Navigation dans l'Application

### Accès aux Paiements

1. **Depuis la Navbar** : Cliquez sur "Paiements"
2. **Depuis une Réservation** : 
   - Allez dans "Réservations"
   - Cliquez sur une réservation
   - Cliquez sur "Voir les Paiements"
3. **Créer un Paiement** :
   - Menu "Paiements" → "Nouveau Paiement"
   - Ou depuis une réservation → "Voir les Paiements" → "Nouveau Paiement"

---

## 📈 Statistiques Disponibles

### Page d'Index des Paiements
- **Total Payé** : Somme de tous les paiements complétés
- **En Attente** : Somme des paiements en attente
- **Remboursé** : Somme des remboursements
- **Total Paiements** : Nombre total de transactions

### Page des Paiements par Réservation
- Total payé pour cette réservation
- Montant en attente
- Montant remboursé
- Nombre de transactions

---

## ⚠️ Points Importants

### ✅ À FAIRE
- Toujours enregistrer le paiement de location en premier
- Vérifier l'état du livre avant de rembourser la caution
- Calculer précisément les pénalités de retard
- Conserver une trace de toutes les transactions

### ❌ À ÉVITER
- Ne jamais supprimer un paiement complété (sauf erreur flagrante)
- Ne pas rembourser sans vérifier l'état du livre
- Ne pas oublier d'enregistrer les pénalités

---

## 🛠️ Dépannage

### Problème : Je ne vois pas mes réservations
**Solution** : Vérifiez que vous avez des réservations actives (statut : en_attente, confirmee, en_cours, ou terminee)

### Problème : Je ne peux pas créer de paiement
**Solution** : Assurez-vous d'être soit le propriétaire soit le locataire de la réservation

### Problème : Le bouton "Supprimer" n'apparaît pas
**Solution** : Seul le propriétaire peut supprimer un paiement

---

## 📞 Questions Fréquentes

### Q : Qui peut créer un paiement ?
**R** : Le propriétaire et le locataire de la réservation concernée

### Q : Quand enregistrer le paiement de location ?
**R** : Dès que la réservation est confirmée et le paiement reçu

### Q : La caution est-elle obligatoire ?
**R** : Non, c'est optionnel. Le propriétaire décide s'il en demande une

### Q : Comment calculer une pénalité de retard ?
**R** : Définissez un montant par jour (ex: 2€/jour) et multipliez par le nombre de jours de retard

### Q : Que faire si le livre est endommagé ?
**R** : 
1. Estimez le coût de réparation
2. Créez une pénalité pour ce montant
3. Déduisez de la caution
4. Remboursez le reste (si applicable)

---

## 🎓 Conseils pour une Bonne Gestion

1. **Communication** : Discutez des conditions (caution, pénalités) avant la confirmation
2. **Documentation** : Prenez des photos du livre avant la remise
3. **Clarté** : Utilisez les notes pour expliquer chaque paiement
4. **Rapidité** : Remboursez la caution rapidement après un retour conforme
5. **Équité** : Appliquez les pénalités de manière juste et proportionnée

---

## 📝 Bonnes Pratiques

### Pour les Propriétaires
- ✅ Définissez clairement vos conditions (caution, pénalités) dans la description
- ✅ Enregistrez immédiatement les paiements reçus
- ✅ Vérifiez l'état du livre dès son retour
- ✅ Remboursez rapidement la caution si tout est OK

### Pour les Locataires
- ✅ Payez rapidement pour confirmer votre sérieux
- ✅ Prenez soin du livre
- ✅ Respectez les délais de retour
- ✅ Signalez tout problème immédiatement

---

## 🔐 Sécurité et Traçabilité

Tous les paiements sont :
- ✅ Horodatés (date de création et modification)
- ✅ Liés à un utilisateur (via la réservation)
- ✅ Modifiables (historique des changements)
- ✅ Traçables (notes et références)

---

## 📱 Support

Pour toute question ou problème :
1. Consultez ce guide
2. Vérifiez la section "Aide & Informations" dans l'application
3. Contactez l'administrateur si nécessaire

---

**Dernière mise à jour** : 21 octobre 2025
**Version** : 1.0

---

*Bonne gestion de vos paiements ! 📚💰*
