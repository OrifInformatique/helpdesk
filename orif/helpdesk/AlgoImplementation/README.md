# Implémentation de l'Algorithme de Génération de Planning

Ce dossier contient l'implémentation complète de l'algorithme de génération automatique du planning de la semaine prochaine, basé sur les diagrammes Mermaid de la documentation.

## Structure

- `PlanningGenerator.php` : Classe principale contenant toutes les méthodes de l'algorithme

## Fonctionnalités

### Fonction principale

- **`generateNextWeekPlanning()`** : Fonction principale qui orchestre toute la génération du planning

### Fonctions de récupération de données

- **`getNextWeekPeriodsOn()`** : Récupère les périodes de la semaine prochaine en excluant les jours fériés
- **`getUsersPresentNextWeekWithThereRoles()`** : Récupère les utilisateurs présents la semaine prochaine avec leurs rôles et contraintes d'assignation
- **`getUsersPresencesPerPeriods()`** : Récupère les présences des utilisateurs par période et identifie les techniciens disponibles

### Fonctions de logique métier

- **`canPlanningBeCopiedFromCurrentWeek()`** : Vérifie si le planning de la semaine actuelle peut être copié
- **`generateNextWeekPlanningAttribution()`** : Génère l'attribution des techniciens aux périodes
- **`assignCaseOneToThreeTechnicians()`** : Gère l'attribution pour les cas avec 1 à 3 techniciens disponibles
- **`assignCaseFourOrMoreTechnicians()`** : Gère l'attribution pour les cas avec 4+ techniciens disponibles

## Utilisation

```php
use Helpdesk\AlgoImplementation\PlanningGenerator;

// Créer une instance du générateur
$generator = new PlanningGenerator();

// Générer le planning
$periods = $generator->generateNextWeekPlanning();

// Les périodes contiennent maintenant les attributions :
// - first_technician
// - second_technician
// - third_technician
```

## Structure des données

### Tableau `$periods`
```php
[
    'mon-m1' => [
        'start' => timestamp,
        'end' => timestamp,
        'first_technician' => user_id,
        'second_technician' => user_id,
        'third_technician' => user_id
    ],
    // ... autres périodes
]
```

### Tableau `$users`
```php
[
    user_id => [
        'user_id' => int,
        'role_priority' => int,
        'technician_1_assignation' => [
            'min' => int,
            'max' => int,
            'assigned' => int
        ],
        'technician_2_assignation' => [...],
        'technician_3_assignation' => [...],
        'available_periods_counter' => int
    ],
    // ... autres utilisateurs
]
```

### Tableau `$presences`
```php
[
    'mon-m1' => [
        'start' => timestamp,
        'end' => timestamp,
        'available_first_technicians' => [user_id, ...],
        'available_second_technicians' => [user_id, ...],
        'available_third_technicians' => [user_id, ...],
        'all_available_technicians' => [user_id, ...]
    ],
    // ... autres périodes
]
```

## Notes d'implémentation

- L'algorithme respecte les contraintes de min/max d'assignation par rôle
- Les techniciens sont triés par nombre d'assignations (ascendant) puis par priorité de rôle (descendant)
- Les périodes sont triées par nombre de techniciens disponibles (ascendant)
- Les cas avec 1-3 techniciens et 4+ techniciens sont traités différemment pour optimiser l'attribution

## Tests

### Test avec les données existantes

Vous pouvez tester l'algorithme avec les données de test (seeds) existantes de deux façons :

#### Option 1: Via le script PHP direct

Depuis la racine du projet :
```bash
php orif/helpdesk/algo_implementation/test_planning_generation.php
```

#### Option 2: Via la commande Spark (recommandé)

1. Copier la commande dans le dossier des commandes :
```bash
cp orif/helpdesk/algo_implementation/TestPlanningCommand.php app/Commands/TestPlanningCommand.php
```

2. Exécuter la commande :
```bash
php spark test:planning
```

### Ce que le test vérifie

Le script de test exécute les étapes suivantes :

1. **Récupération des périodes** : Vérifie que les périodes de la semaine prochaine sont correctement récupérées (en excluant les jours fériés)

2. **Récupération des utilisateurs** : Vérifie que les utilisateurs présents avec leurs rôles sont correctement identifiés

3. **Récupération des présences** : Vérifie que les présences par période sont correctement calculées

4. **Vérification de copie** : Vérifie si le planning peut être copié depuis la semaine actuelle

5. **Génération complète** : Exécute l'algorithme complet et affiche :
   - Les périodes avec leurs assignations
   - Les statistiques globales
   - Les assignations par utilisateur

### Exemple de sortie

```
========================================
TEST DE GÉNÉRATION DE PLANNING
========================================

✓ Générateur de planning initialisé

--- TEST 1: Récupération des périodes ---
Nombre de périodes récupérées: 20
Exemple de période:
  - mon-m1: 2024-01-15 08:00 -> 2024-01-15 10:00

--- TEST 2: Récupération des utilisateurs ---
Nombre d'utilisateurs récupérés: 15
...

--- RÉSULTATS DE LA GÉNÉRATION ---
Période: mon-m1
  2024-01-15 08:00 -> 2024-01-15 10:00
  Assignations: Tech1: 7, Tech2: 8, Tech3: 10

--- STATISTIQUES ---
Périodes avec assignations: 18 / 20
Total d'assignations: 45

--- ASSIGNATIONS PAR UTILISATEUR ---
User ID 7: Tech1=3, Tech2=2, Tech3=1 (Total: 6)
...
```

## TODO

- Implémenter la logique complète de `canPlanningBeCopiedFromCurrentWeek()`
- Implémenter la copie du planning de la semaine actuelle si possible
- Ajouter la sauvegarde des résultats dans la base de données
- Ajouter des tests unitaires
