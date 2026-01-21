# Guide de Test - Génération de Planning

## Prérequis

Assurez-vous d'avoir :
- Les seeds exécutés (utilisateurs, rôles, présences)
- Une base de données configurée et accessible
- CodeIgniter correctement configuré

## Méthode 1 : Script PHP Direct (Simple)

Depuis la racine du projet :

```bash
php orif/helpdesk/algo_implementation/test_planning_generation.php
```

## Méthode 2 : Commande Spark (Recommandé)

### Étape 1 : Installer la commande

Copier le fichier de commande dans le dossier des commandes CodeIgniter :

```bash
# Windows (PowerShell)
Copy-Item orif/helpdesk/algo_implementation/TestPlanningCommand.php app/Commands/TestPlanningCommand.php

# Linux/Mac
cp orif/helpdesk/algo_implementation/TestPlanningCommand.php app/Commands/TestPlanningCommand.php
```

### Étape 2 : Exécuter le test

```bash
php spark test:planning
```

## Ce que vous devriez voir

Le script va :

1. ✅ Initialiser le générateur
2. 📅 Récupérer les périodes de la semaine prochaine
3. 👥 Récupérer les utilisateurs avec leurs rôles
4. 📊 Récupérer les présences par période
5. 🔄 Vérifier si le planning peut être copié
6. 🎯 Générer le planning complet
7. 📈 Afficher les statistiques

## Résultats attendus

- **Périodes** : 20 périodes (5 jours × 4 périodes/jour), moins les jours fériés
- **Utilisateurs** : Tous les utilisateurs avec un rôle (priority_role != 0) et au moins une présence
- **Présences** : Pour chaque période, liste des techniciens disponibles
- **Assignations** : Répartition équitable des techniciens selon leurs contraintes

## Dépannage

### Erreur : "Class not found"
- Vérifiez que l'autoloader CodeIgniter est correctement configuré
- Vérifiez que les namespaces sont corrects

### Erreur : "Database connection failed"
- Vérifiez votre configuration dans `app/Config/Database.php`
- Vérifiez que la base de données est accessible

### Aucun utilisateur récupéré
- Vérifiez que les seeds ont été exécutés
- Vérifiez que les utilisateurs ont des rôles avec `priority_role != 0`
- Vérifiez que les utilisateurs ont des présences (au moins une période PRESENT ou PARTIALLY_ABSENT)

### Aucune période récupérée
- Vérifiez que la date "next monday" est correcte
- Vérifiez s'il y a des jours fériés qui excluent toutes les périodes

## Exemple de sortie réussie

```
========================================
TEST DE GÉNÉRATION DE PLANNING
========================================

✓ Générateur de planning initialisé

--- TEST 1: Récupération des périodes ---
Nombre de périodes récupérées: 20

--- TEST 2: Récupération des utilisateurs ---
Nombre d'utilisateurs récupérés: 15

--- TEST 3: Récupération des présences ---
Nombre de présences traitées: 20

--- TEST 4: Vérification de copie du planning ---
Le planning peut être copié: Non

--- TEST 5: Génération complète du planning ---
Génération en cours...
✓ Planning généré avec succès!

--- RÉSULTATS DE LA GÉNÉRATION ---
Nombre total de périodes: 20

Période: mon-m1
  2024-01-15 08:00 -> 2024-01-15 10:00
  Assignations: Tech1: 7, Tech2: 8

--- STATISTIQUES ---
Périodes avec assignations: 18 / 20
Total d'assignations: 45

--- ASSIGNATIONS PAR UTILISATEUR ---
User ID 7: Tech1=3, Tech2=2, Tech3=1 (Total: 6)
User ID 8: Tech1=2, Tech2=3, Tech3=2 (Total: 7)
...

========================================
TEST TERMINÉ AVEC SUCCÈS!
========================================
```
