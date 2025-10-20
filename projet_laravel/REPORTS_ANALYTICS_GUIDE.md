# 📊 Dashboard Analytique & Système de Priorité - Signalements

## 🎯 Vue d'ensemble

Ce système avancé de gestion des signalements comprend deux fonctionnalités principales :

### 1. **Dashboard Analytique**
Un tableau de bord complet avec des statistiques en temps réel, des graphiques interactifs et des analyses détaillées.

### 2. **Système de Priorité Automatique**
Un algorithme intelligent qui calcule automatiquement le niveau de priorité de chaque signalement.

---

## 🚀 Fonctionnalités Implémentées

### 📈 Dashboard Analytique

#### **Statistiques Générales**
- Total des signalements sur période personnalisable
- Signalements en attente de traitement
- Signalements urgents et haute priorité
- Taux de résolution et temps moyen

#### **Graphiques Interactifs (Chart.js)**
1. **Évolution temporelle** (7 derniers jours)
   - Ligne de tendance totale
   - Signalements en attente
   - Signalements traités

2. **Distribution par priorité**
   - Graphique circulaire (Doughnut)
   - Normale, Moyenne, Haute, Critique

3. **Distribution par type**
   - Conflits d'échange
   - Comportements inappropriés

4. **Distribution par statut**
   - En attente, Traités, Rejetés

#### **Top Rankings**
- Top 5 utilisateurs signalés
- Top 5 reporters actifs
- Identification des récidivistes

#### **Performance Modérateurs**
- Nombre total de dossiers traités
- Taux d'approbation vs rejet
- Temps moyen de résolution
- Statistiques individuelles par modérateur

#### **Alertes Urgentes**
- Tableau dédié aux signalements critiques nécessitant une attention immédiate
- Indicateurs visuels : 🔴 Critique, 🟠 Haute, 🟡 Moyenne, 🟢 Normale

#### **Export de Données**
- Export CSV complet avec tous les détails
- Filtrable par date, statut, priorité

---

### ⚡ Système de Priorité Automatique

#### **Algorithme de Calcul (Score sur 10 points)**

Le score de priorité est calculé selon 4 critères pondérés :

##### 1. **Gravité du Type** (40% - 4 points max)
- `COMPORTEMENT` : +4 points (comportement inapproprié)
- `CONFLIT_ECHANGE` : +2 points (conflit d'échange)

##### 2. **Signalements Similaires** (30% - 3 points max)
- ≥5 signalements similaires : +3 points
- 3-4 signalements : +2 points
- 1-2 signalements : +1 point
- 0 signalement : 0 point

##### 3. **Utilisateur Récidiviste** (20% - 2 points max)
- Récidiviste (≥3 signalements traités en 6 mois) : +2 points
- Non récidiviste : 0 point

##### 4. **Ancienneté du Signalement** (10% - 1 point max)
- Plus de 72 heures (3 jours) : +1 point
- Moins de 72 heures : 0 point

##### Bonus : **Émotion Forte Détectée**
- Score émotionnel ≥80% : +1 point bonus

#### **Niveaux de Priorité**

| Score | Niveau | Icône | Couleur | Action |
|-------|--------|-------|---------|--------|
| 8-10  | 🔴 **CRITIQUE** | 🔴 | Rouge | Traitement immédiat requis |
| 6-7   | 🟠 **HAUTE** | 🟠 | Orange | Traitement prioritaire |
| 4-5   | 🟡 **MOYENNE** | 🟡 | Jaune | Traitement dans les 48h |
| 1-3   | 🟢 **NORMALE** | 🟢 | Vert | Traitement standard |

#### **Calcul Automatique**

Le calcul se fait automatiquement :
- ✅ À la création du signalement (événement `creating`)
- ✅ Manuellement via `$report->updatePriorityLevel()`

```php
// Exemple : Recalculer la priorité manuellement
$report = Report::find(1);
$report->updatePriorityLevel();
```

---

## 📁 Structure des Fichiers

### **Contrôleur**
```
app/Http/Controllers/Admin/ReportsDashboardController.php
```
- `index()` : Affichage du dashboard avec toutes les statistiques
- `export()` : Export CSV des données
- `timelineData()` : API JSON pour les données de timeline

### **Modèle**
```
app/Models/Report.php
```
**Nouvelles méthodes :**
- `calculatePriorityScore()` : Calcul du score
- `updatePriorityLevel()` : Mise à jour du niveau
- `countSimilarReports()` : Compte les signalements similaires
- `checkIfRecurringOffender()` : Vérifie si récidiviste
- `getPriorityColorAttribute()` : Couleur Bootstrap
- `getPriorityIconAttribute()` : Icône emoji

**Nouveaux scopes :**
- `byPriority($priority)` : Filtrer par priorité
- `highPriority()` : Signalements haute priorité ou critiques
- `urgent()` : Signalements critiques uniquement

**Méthodes helper :**
- `isHighPriority()` : Booléen
- `isUrgent()` : Booléen

### **Vue**
```
resources/views/admin/reports/dashboard.blade.php
```
- Interface complète avec filtres
- 4 graphiques Chart.js interactifs
- Tableaux de données
- Design responsive Bootstrap

### **Routes**
```php
// routes/web.php - Section Admin
Route::get('reports/dashboard', [ReportsDashboardController::class, 'index'])
    ->name('admin.reports.dashboard');
Route::get('reports/dashboard/export', [ReportsDashboardController::class, 'export'])
    ->name('admin.reports.dashboard.export');
Route::get('reports/dashboard/timeline-data', [ReportsDashboardController::class, 'timelineData'])
    ->name('admin.reports.dashboard.timeline');
```

### **Migration**
```
database/migrations/2025_10_20_223128_add_priority_and_analytics_to_reports_table.php
```

### **Seeder**
```
database/seeders/UpdateReportsPrioritySeeder.php
```

---

## 🔧 Installation & Configuration

### 1. **Exécuter les Migrations**
```bash
php artisan migrate
```

### 2. **Mettre à Jour les Signalements Existants**
```bash
php artisan db:seed --class=UpdateReportsPrioritySeeder
```

Cette commande va :
- Compter les signalements similaires
- Détecter les récidivistes
- Calculer les scores de priorité
- Assigner les niveaux de priorité
- Afficher les statistiques

### 3. **Accéder au Dashboard**
```
URL : /admin/reports/dashboard
```

---

## 📊 Utilisation

### **Filtres Disponibles**

1. **Date début** : Date de début de la période
2. **Date fin** : Date de fin de la période
3. **Statut** : EN_ATTENTE, TRAITE, REJETE
4. **Priorité** : critique, haute, moyenne, normale

### **Actions Disponibles**

#### **Exporter les Données**
Bouton "Exporter CSV" en haut à droite du dashboard.
Format : `reports_YYYY-MM-DD_HHMMSS.csv`

Colonnes exportées :
- ID, Type, Description, Statut
- Priorité, Score, Reporter, Utilisateur signalé
- Modérateur, Dates, Temps de résolution, Action prise

#### **Voir les Signalements Urgents**
Section dédiée en haut de la page avec :
- Badge de priorité coloré
- Indicateur de récidiviste ⚠️
- Bouton "Voir" pour détails

---

## 🎨 Design & Interface

### **Cartes Statistiques**
4 cartes principales avec indicateurs clés :
- **Total** (Bleu) : Nombre total de signalements
- **En Attente** (Jaune) : Signalements non traités
- **Urgents** (Rouge) : Signalements critiques + haute priorité
- **Taux de Résolution** (Vert) : Pourcentage + temps moyen

### **Codes Couleur Bootstrap**
```php
'critique'  => 'danger'   // Rouge
'haute'     => 'warning'  // Orange
'moyenne'   => 'info'     // Bleu clair
'normale'   => 'secondary'// Gris
```

### **Graphiques Chart.js**
- **Timeline** : Graphique linéaire avec 3 courbes
- **Priorité** : Graphique circulaire (doughnut)
- **Type** : Graphique circulaire
- **Statut** : Graphique circulaire

Tous les graphiques sont :
- ✅ Responsive
- ✅ Interactifs (tooltips)
- ✅ Animés
- ✅ Personnalisables

---

## 🧪 Tests & Debugging

### **Tester l'Algorithme de Priorité**

```bash
php artisan tinker
```

```php
// Créer un signalement de test
$report = Report::create([
    'type' => 'COMPORTEMENT',
    'description' => 'Test de priorité',
    'status' => 'EN_ATTENTE',
    'reporter_id' => 1,
    'reported_user_id' => 2
]);

// Vérifier le calcul automatique
echo "Score: " . $report->priority_score . "\n";
echo "Niveau: " . $report->priority_level . "\n";
echo "Icône: " . $report->priority_icon . "\n";
echo "Couleur: " . $report->priority_color . "\n";

// Recalculer manuellement
$report->updatePriorityLevel();
```

### **Vérifier les Statistiques**

```php
// Compter par priorité
$critiques = Report::where('priority_level', Report::PRIORITY_CRITIQUE)->count();
$hautes = Report::where('priority_level', Report::PRIORITY_HAUTE)->count();
$moyennes = Report::where('priority_level', Report::PRIORITY_MOYENNE)->count();
$normales = Report::where('priority_level', Report::PRIORITY_NORMALE)->count();

echo "🔴 Critiques: $critiques\n";
echo "🟠 Hautes: $hautes\n";
echo "🟡 Moyennes: $moyennes\n";
echo "🟢 Normales: $normales\n";

// Récidivistes
$recidivistes = Report::where('is_recurring_offender', true)->count();
echo "⚠️  Récidivistes: $recidivistes\n";
```

---

## 🔐 Sécurité & Permissions

### **Middleware Requis**
- ✅ `auth` : Authentification obligatoire
- ✅ `admin` : Rôle administrateur uniquement

### **Accès**
Seuls les administrateurs peuvent :
- Accéder au dashboard analytique
- Exporter les données CSV
- Voir les statistiques détaillées

---

## 📝 Exemples de Code

### **Filtrer par Priorité**

```php
// Tous les signalements urgents
$urgents = Report::urgent()->get();

// Haute priorité ou critique
$highPriority = Report::highPriority()->get();

// Priorité spécifique
$moyennes = Report::byPriority(Report::PRIORITY_MOYENNE)->get();
```

### **Obtenir les Statistiques**

```php
use App\Http\Controllers\Admin\ReportsDashboardController;

$controller = new ReportsDashboardController();

// Timeline des 7 derniers jours
$timeline = $controller->timelineData(request()->merge(['days' => 7]));

// Export CSV
$csv = $controller->export(request()->merge([
    'date_from' => '2025-01-01',
    'date_to' => '2025-12-31'
]));
```

### **Vérifications Manuelles**

```php
$report = Report::find(1);

// Vérifications
if ($report->isUrgent()) {
    // Traitement immédiat
}

if ($report->isHighPriority()) {
    // Priorité haute ou critique
}

// Score détaillé
$score = $report->calculatePriorityScore();
echo "Score calculé: $score/10\n";
```

---

## 🎯 Cas d'Usage

### **Scénario 1 : Nouveau Signalement Comportement**

```
Type: COMPORTEMENT (+4 points)
Signalements similaires: 3 (+2 points)
Récidiviste: Non (0 point)
Ancienneté: < 72h (0 point)
Émotion: 85% (+1 point bonus)

Score total: 7/10
Niveau: 🟠 HAUTE PRIORITÉ
```

### **Scénario 2 : Conflit d'Échange Ancien**

```
Type: CONFLIT_ECHANGE (+2 points)
Signalements similaires: 0 (0 point)
Récidiviste: Non (0 point)
Ancienneté: > 72h (+1 point)
Émotion: 50% (0 bonus)

Score total: 3/10
Niveau: 🟢 NORMALE
```

### **Scénario 3 : Récidiviste Grave**

```
Type: COMPORTEMENT (+4 points)
Signalements similaires: 5+ (+3 points)
Récidiviste: Oui (+2 points)
Ancienneté: > 72h (+1 point)
Émotion: 90% (+1 bonus)

Score total: 11/10 → 10/10 (max)
Niveau: 🔴 CRITIQUE
```

---

## 🔄 Maintenance

### **Recalculer Toutes les Priorités**

```bash
php artisan db:seed --class=UpdateReportsPrioritySeeder
```

### **Nettoyer les Anciens Signalements**

```php
// Supprimer les signalements traités de plus d'1 an
Report::processed()
    ->where('resolved_at', '<', now()->subYear())
    ->delete();
```

### **Optimiser les Performances**

```php
// Index recommandés dans la migration
$table->index('priority_level');
$table->index('priority_score');
$table->index(['status', 'priority_level']);
$table->index('created_at');
```

---

## 📞 Support & Questions

Pour toute question ou amélioration :
1. Vérifier ce guide
2. Consulter le code source avec commentaires
3. Tester avec `php artisan tinker`
4. Contacter l'équipe technique

---

## ✅ Checklist de Validation

- [x] Migration créée et exécutée
- [x] Modèle Report mis à jour avec nouvelles méthodes
- [x] Contrôleur Dashboard créé
- [x] Vue dashboard avec graphiques
- [x] Routes ajoutées
- [x] Seeder de mise à jour créé
- [x] Algorithme de priorité testé
- [x] Calcul automatique à la création
- [x] Export CSV fonctionnel
- [x] Graphiques Chart.js intégrés
- [x] Design responsive
- [x] Documentation complète

---

🎉 **Système Opérationnel !**

Le Dashboard Analytique et le Système de Priorité sont maintenant pleinement fonctionnels et prêts à l'emploi.
