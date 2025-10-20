# Guide de Gestion des Paiements de Réservation

## 📋 Vue d'ensemble

Ce document décrit les modifications apportées au système BookShare pour :
1. **Renommer "Location" en "Réservation"** dans le frontend
2. **Créer une nouvelle entité `ReservationPayment`** pour gérer les paiements
3. **Implémenter un CRUD complet** pour les paiements de réservation

---

## 🆕 Nouvelle Entité : ReservationPayment

### Structure de la Table

La table `reservation_payments` contient les champs suivants :

| Champ | Type | Description |
|-------|------|-------------|
| `id` | bigint | Identifiant unique |
| `location_id` | bigint | Clé étrangère vers `locations` |
| `montant` | decimal(10,2) | Montant du paiement |
| `type_paiement` | enum | Type : caution, location, penalite, remboursement |
| `statut_paiement` | enum | Statut : en_attente, complete, echoue, rembourse, annule |
| `methode_paiement` | string | Méthode utilisée (carte, espèces, virement, etc.) |
| `reference_transaction` | string | Référence externe (PayPal, Stripe, etc.) |
| `date_paiement` | date | Date du paiement |
| `date_remboursement` | date | Date du remboursement (si applicable) |
| `notes` | text | Notes supplémentaires |
| `timestamps` | - | created_at et updated_at |

### Fichiers Créés

#### 1. Migration
- **Fichier** : `database/migrations/2025_10_21_000001_create_reservation_payments_table.php`
- **Action** : Exécuter `php artisan migrate`

#### 2. Modèle
- **Fichier** : `app/Models/ReservationPayment.php`
- **Relations** : 
  - `belongsTo(Location::class)` - Un paiement appartient à une réservation
- **Méthodes utiles** :
  - `estComplete()` : Vérifie si le paiement est complet
  - `estEnAttente()` : Vérifie si le paiement est en attente
  - `marquerCommeComplete()` : Marque le paiement comme complet
  - `marquerCommeEchoue()` : Marque le paiement comme échoué
  - `rembourser()` : Rembourse le paiement
  - `getStatutBadgeClass()` : Retourne la classe CSS pour le badge
  - `getStatutLabel()` : Retourne le label du statut
  - `getTypeLabel()` : Retourne le label du type

#### 3. Contrôleur
- **Fichier** : `app/Http/Controllers/ReservationPaymentController.php`
- **Actions disponibles** :
  - `index()` : Liste tous les paiements
  - `create()` : Formulaire de création
  - `store()` : Enregistrer un nouveau paiement
  - `show()` : Afficher les détails d'un paiement
  - `edit()` : Formulaire d'édition
  - `update()` : Mettre à jour un paiement
  - `destroy()` : Supprimer un paiement
  - `marquerComplete()` : Marquer un paiement comme complet
  - `rembourser()` : Rembourser un paiement
  - `byLocation()` : Afficher tous les paiements d'une réservation

#### 4. Vues Blade
- `resources/views/reservation-payments/index.blade.php` - Liste des paiements
- `resources/views/reservation-payments/create.blade.php` - Créer un paiement
- `resources/views/reservation-payments/show.blade.php` - Détails d'un paiement
- `resources/views/reservation-payments/edit.blade.php` - Modifier un paiement
- `resources/views/reservation-payments/by-location.blade.php` - Paiements par réservation

---

## 🔄 Modifications du Modèle Location

Le modèle `Location` a été mis à jour pour inclure la relation avec les paiements :

```php
public function payments(): HasMany
{
    return $this->hasMany(ReservationPayment::class);
}
```

---

## 🛣️ Routes Ajoutées

Dans `routes/web.php`, les routes suivantes ont été ajoutées :

```php
// Routes pour les paiements de réservation
Route::resource('reservation-payments', ReservationPaymentController::class);
Route::patch('reservation-payments/{reservationPayment}/marquer-complete', [ReservationPaymentController::class, 'marquerComplete'])
    ->name('reservation-payments.marquer-complete');
Route::patch('reservation-payments/{reservationPayment}/rembourser', [ReservationPaymentController::class, 'rembourser'])
    ->name('reservation-payments.rembourser');
Route::get('locations/{location}/payments', [ReservationPaymentController::class, 'byLocation'])
    ->name('locations.payments');
```

---

## 🎨 Modifications du Frontend

### 1. Navbar (`resources/views/partials/navbar.blade.php`)
- ✅ "Locations" → "Réservations"

### 2. Sidebar Admin (`resources/views/partials/aside.blade.php`)
- ✅ Déjà nommé "Réservations"

### 3. Vue Détails de Réservation (`resources/views/locations/show.blade.php`)
- ✅ "Détails de la location" → "Détails de la réservation"
- ✅ "Retour aux locations" → "Retour aux réservations"
- ✅ Ajout d'un bouton "Voir les Paiements" dans la section Actions

---

## 📊 Utilisation

### Créer un Paiement

1. Aller sur la page d'une réservation
2. Cliquer sur "Voir les Paiements"
3. Cliquer sur "Nouveau Paiement"
4. Remplir le formulaire :
   - Montant
   - Type de paiement (Location, Caution, Pénalité, Remboursement)
   - Statut du paiement
   - Méthode de paiement (optionnel)
   - Référence de transaction (optionnel)
   - Date de paiement
   - Notes (optionnel)

### Gérer les Paiements

#### Pour le Propriétaire :
- Marquer un paiement comme complet
- Rembourser une caution
- Supprimer un paiement

#### Pour le Locataire :
- Voir ses paiements
- Consulter les détails

### Types de Paiements

1. **Location** : Paiement du prix de location du livre
2. **Caution** : Dépôt de garantie pour le livre
3. **Pénalité** : Frais de retard ou dommages
4. **Remboursement** : Retour de fonds au locataire

### Statuts des Paiements

1. **En attente** : Paiement non encore effectué
2. **Complété** : Paiement reçu
3. **Échoué** : Transaction échouée
4. **Remboursé** : Fonds retournés au locataire
5. **Annulé** : Transaction annulée

---

## 🔒 Permissions

- **Créer un paiement** : Propriétaire ou locataire de la réservation
- **Voir un paiement** : Propriétaire ou locataire
- **Modifier un paiement** : Propriétaire ou locataire
- **Supprimer un paiement** : Propriétaire uniquement
- **Marquer comme complet** : Propriétaire uniquement
- **Rembourser** : Propriétaire uniquement

---

## 🔗 Liens Rapides

- **Liste des paiements** : `/reservation-payments`
- **Créer un paiement** : `/reservation-payments/create`
- **Paiements d'une réservation** : `/locations/{id}/payments`

---

## 🚀 Prochaines Étapes Suggérées

1. **Intégration de passerelles de paiement** (Stripe, PayPal)
2. **Notifications automatiques** pour les paiements en attente
3. **Génération de factures** PDF
4. **Historique des transactions**
5. **Statistiques des paiements** dans le dashboard
6. **Rappels automatiques** pour les paiements en retard

---

## 📝 Notes Importantes

- La table `locations` n'a pas été renommée en base de données pour éviter de casser les relations existantes
- Seul le frontend affiche "Réservations" au lieu de "Locations"
- Les routes gardent le nom `locations.*` pour la cohérence
- La migration a créé une contrainte de clé étrangère avec `onDelete('cascade')`

---

## 🐛 Résolution de Problèmes

### Erreur "Table not found"
```bash
php artisan migrate
php artisan config:clear
php artisan cache:clear
# Redémarrer le serveur
```

### Problème de permissions
- Vérifier que l'utilisateur est authentifié
- Vérifier que l'utilisateur est soit le propriétaire soit le locataire

---

**Date de création** : 21 octobre 2025  
**Version** : 1.0.0
