# 🧪 Tests Rapides - Dashboard Analytique & Priorités

## Test 1 : Accès au Dashboard

1. Connectez-vous en tant qu'administrateur
2. Allez sur : `/admin/reports`
3. Cliquez sur le bouton "Dashboard Analytique" en haut à droite
4. ✅ Vous devriez voir le dashboard complet avec graphiques

## Test 2 : Vérifier les Statistiques

### Via l'Interface Web
- **Cartes en haut** : Total, En attente, Urgents, Taux de résolution
- **Graphique Timeline** : 7 derniers jours
- **Graphiques circulaires** : Priorité, Type, Statut
- **Tableaux** : Top utilisateurs signalés et reporters

### Via Tinker
```bash
php artisan tinker
```

```php
// Compter par priorité
$stats = [
    'critique' => Report::where('priority_level', 'critique')->count(),
    'haute' => Report::where('priority_level', 'haute')->count(),
    'moyenne' => Report::where('priority_level', 'moyenne')->count(),
    'normale' => Report::where('priority_level', 'normale')->count(),
];
print_r($stats);

// Résultats attendus :
// Array (
//     [critique] => 0
//     [haute] => 0
//     [moyenne] => 3
//     [normale] => 6
// )
```

## Test 3 : Algorithme de Priorité

### Créer un nouveau signalement et vérifier le calcul automatique

```php
php artisan tinker
```

```php
// Test 1 : Comportement simple (devrait être priorité moyenne)
$report1 = Report::create([
    'type' => 'COMPORTEMENT',
    'description' => 'Test comportement inapproprié',
    'status' => 'EN_ATTENTE',
    'reporter_id' => 1,
    'reported_user_id' => 2
]);

echo "Test 1 - Comportement simple:\n";
echo "Score: {$report1->priority_score}/10\n";
echo "Niveau: {$report1->priority_level}\n";
echo "Icône: {$report1->priority_icon}\n\n";
// Attendu: Score 4/10, Niveau: moyenne, Icône: 🟡

// Test 2 : Conflit d'échange simple (devrait être normale)
$report2 = Report::create([
    'type' => 'CONFLIT_ECHANGE',
    'description' => 'Test conflit',
    'status' => 'EN_ATTENTE',
    'reporter_id' => 1,
    'reported_user_id' => 3,
    'exchange_id' => 1
]);

echo "Test 2 - Conflit simple:\n";
echo "Score: {$report2->priority_score}/10\n";
echo "Niveau: {$report2->priority_level}\n";
echo "Icône: {$report2->priority_icon}\n\n";
// Attendu: Score 2/10, Niveau: normale, Icône: 🟢

// Test 3 : Signalement avec historique (créer plusieurs contre même utilisateur)
for ($i = 0; $i < 5; $i++) {
    Report::create([
        'type' => 'COMPORTEMENT',
        'description' => "Signalement répété #{$i}",
        'status' => 'EN_ATTENTE',
        'reporter_id' => 1,
        'reported_user_id' => 4
    ]);
}

// Le dernier devrait avoir un score élevé (signalements similaires)
$report3 = Report::where('reported_user_id', 4)->latest()->first();
echo "Test 3 - Utilisateur avec historique:\n";
echo "Score: {$report3->priority_score}/10\n";
echo "Niveau: {$report3->priority_level}\n";
echo "Icône: {$report3->priority_icon}\n";
echo "Signalements similaires: {$report3->similar_reports_count}\n\n";
// Attendu: Score 7-9/10, Niveau: haute, Icône: 🟠
```

## Test 4 : Filtres du Dashboard

1. Sur le dashboard, testez les filtres :
   - **Date début** : Il y a 1 mois
   - **Date fin** : Aujourd'hui
   - **Statut** : EN_ATTENTE
   - **Priorité** : moyenne
2. Cliquez sur "Filtrer"
3. ✅ Les statistiques doivent se mettre à jour

## Test 5 : Export CSV

1. Sur le dashboard
2. Cliquez sur "Exporter CSV"
3. ✅ Un fichier `reports_YYYY-MM-DD_HHMMSS.csv` doit être téléchargé
4. Ouvrez-le dans Excel/LibreOffice
5. ✅ Vérifiez que toutes les colonnes sont présentes :
   - ID, Type, Description, Statut
   - Priorité, Score, Reporter, Utilisateur signalé
   - Modérateur, Dates, Temps résolution, Action

## Test 6 : Recalcul Manuel des Priorités

```bash
php artisan db:seed --class=UpdateReportsPrioritySeeder
```

**Résultat attendu :**
```
📊 Mise à jour des priorités des signalements...
✅ Signalement #1: 🟡 moyenne (Score: 4/10)
✅ Signalement #2: 🟢 normale (Score: 2/10)
...

✅ X signalements mis à jour avec succès !

📈 Statistiques des priorités :
+----------+--------+-------+
| Priorité | Nombre | Icône |
+----------+--------+-------+
| Critique | 0      | 🔴    |
| Haute    | 0      | 🟠    |
| Moyenne  | 3      | 🟡    |
| Normale  | 6      | 🟢    |
+----------+--------+-------+
```

## Test 7 : Méthodes Helper du Modèle

```php
php artisan tinker
```

```php
$report = Report::first();

// Tester les méthodes booléennes
var_dump($report->isUrgent());        // false (si pas critique)
var_dump($report->isHighPriority());  // false (si pas haute ou critique)
var_dump($report->isPending());       // true (si EN_ATTENTE)

// Tester les attributs
echo $report->priority_color;   // 'secondary', 'info', 'warning', 'danger'
echo $report->priority_icon;    // 🟢, 🟡, 🟠, 🔴

// Tester les scopes
$urgents = Report::urgent()->count();
$highPrio = Report::highPriority()->count();
$pending = Report::pending()->count();

echo "Urgents: $urgents\n";
echo "Haute priorité: $highPrio\n";
echo "En attente: $pending\n";
```

## Test 8 : Graphiques Chart.js

1. Sur le dashboard, vérifiez que les 4 graphiques s'affichent :
   - ✅ **Timeline** (ligne) : Évolution sur 7 jours
   - ✅ **Priorité** (doughnut) : Distribution par priorité
   - ✅ **Type** (doughnut) : Conflits vs Comportements
   - ✅ **Statut** (doughnut) : En attente, Traités, Rejetés

2. Survolez les graphiques avec la souris
   - ✅ Les tooltips doivent s'afficher avec les valeurs

## Test 9 : Performance & Optimisation

### Vérifier les index de la base de données

```bash
php artisan tinker
```

```php
// Vérifier la structure de la table
DB::select('SHOW INDEX FROM reports');

// Temps de requête pour les statistiques
$start = microtime(true);
$stats = Report::select('priority_level', DB::raw('COUNT(*) as count'))
    ->groupBy('priority_level')
    ->get();
$time = (microtime(true) - $start) * 1000;

echo "Temps de requête: " . round($time, 2) . " ms\n";
// Devrait être < 50ms pour de bonnes performances
```

## Test 10 : Signalements Urgents

1. Créer un signalement critique :

```php
php artisan tinker
```

```php
// Simuler un cas critique
$critical = Report::create([
    'type' => 'COMPORTEMENT',
    'description' => 'Harcèlement grave et répété',
    'status' => 'EN_ATTENTE',
    'reporter_id' => 1,
    'reported_user_id' => 5,
    'emotion_type' => 'anger',
    'emotion_score' => 95.5
]);

// Ajouter manuellement des signalements similaires
$critical->similar_reports_count = 6;
$critical->is_recurring_offender = true;
$critical->created_at = now()->subDays(4); // Ancien
$critical->save();

// Recalculer
$critical->updatePriorityLevel();

echo "Score: {$critical->priority_score}/10\n";
echo "Niveau: {$critical->priority_level}\n";
// Attendu: Score 10/10, Niveau: critique
```

2. Retourner sur le dashboard
3. ✅ La section "Signalements Urgents" devrait afficher ce nouveau signalement en rouge

## Test 11 : Routes et Navigation

Vérifiez que toutes les routes fonctionnent :

```bash
php artisan route:list | grep "reports"
```

**Routes attendues :**
- ✅ `GET admin/reports` → Liste
- ✅ `GET admin/reports/dashboard` → Dashboard
- ✅ `GET admin/reports/dashboard/export` → Export CSV
- ✅ `GET admin/reports/dashboard/timeline-data` → API JSON
- ✅ `GET admin/reports/{report}` → Détails
- ✅ `PATCH admin/reports/{report}/status` → Maj statut

## Test 12 : Responsive Design

1. Ouvrez le dashboard sur mobile/tablette (F12 → Mode responsive)
2. ✅ Les cartes doivent s'empiler verticalement
3. ✅ Les graphiques doivent s'adapter à la largeur
4. ✅ Le menu doit rester accessible

## ✅ Checklist Finale

- [ ] Dashboard accessible depuis `/admin/reports/dashboard`
- [ ] Statistiques correctes (total, en attente, urgents, taux)
- [ ] 4 graphiques Chart.js affichés et interactifs
- [ ] Filtres fonctionnels (date, statut, priorité)
- [ ] Export CSV génère un fichier valide
- [ ] Algorithme de priorité calcule correctement (4 critères + bonus)
- [ ] Niveaux de priorité assignés : 🟢🟡🟠🔴
- [ ] Calcul automatique à la création d'un signalement
- [ ] Méthodes helper fonctionnelles (isUrgent, isHighPriority, etc.)
- [ ] Scopes de requête opérationnels (urgent, highPriority, byPriority)
- [ ] Top utilisateurs signalés et reporters affichés
- [ ] Section signalements urgents visible si applicable
- [ ] Performance modérateurs (si données disponibles)
- [ ] Navigation entre liste et dashboard fluide
- [ ] Responsive design sur mobile/tablette
- [ ] Seeder met à jour tous les signalements existants

## 🎉 Résultat Final Attendu

Après tous ces tests, vous devriez avoir :

1. ✅ Un dashboard complet avec 4 graphiques interactifs
2. ✅ Un système de priorité automatique basé sur 4 critères
3. ✅ 9 signalements existants classés (6 normaux, 3 moyens)
4. ✅ Des filtres fonctionnels par date, statut, priorité
5. ✅ Un export CSV complet
6. ✅ Une interface responsive et moderne
7. ✅ Des statistiques en temps réel
8. ✅ Une identification des récidivistes
9. ✅ Des alertes pour signalements urgents

**Le système est opérationnel ! 🚀**
