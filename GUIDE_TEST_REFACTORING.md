# Guide de test après refactoring des variables

## Variables modifiées

Les 5 variables suivantes ont été renommées en snake_case :

1. `$columnExists` → `$column_exists` (2 fichiers)
2. `$oldName` → `$old_name` (2 fichiers)
3. `$oldUsertype` → `$old_usertype` (2 fichiers)
4. `$userId` → `$user_id` (1 fichier)
5. `$emptyFieldsCount` → `$empty_fields_count` (1 fichier)

## Fichiers modifiés

- `orif/helpdesk/Database/Seeds/InsertUserData.php`
- `orif/helpdesk/Database/Migrations/2023-08-08-140000_AddFkRoleToUserData.php`
- `orif/user/Controllers/Admin.php`
- `orif/helpdesk/Controllers/User.php`
- `orif/helpdesk/Database/Seeds/InsertPresencesData.php`
- `orif/helpdesk/Controllers/Planning.php`

## Tests à effectuer

### 1. Tests de syntaxe PHP

**Commande :**
```bash
php -l orif/helpdesk/Database/Seeds/InsertUserData.php
php -l orif/helpdesk/Database/Migrations/2023-08-08-140000_AddFkRoleToUserData.php
php -l orif/user/Controllers/Admin.php
php -l orif/helpdesk/Controllers/User.php
php -l orif/helpdesk/Database/Seeds/InsertPresencesData.php
php -l orif/helpdesk/Controllers/Planning.php
```

**Résultat attendu :** Aucune erreur de syntaxe

### 2. Vérification des migrations

**Test :**
- Exécuter la migration `2023-08-08-140000_AddFkRoleToUserData.php` (méthode `up()`)
- Vérifier que la colonne `fk_role_id` est créée correctement
- Exécuter la méthode `down()` pour vérifier le rollback
- Vérifier que la colonne est supprimée correctement

**Commande :**
```bash
php spark migrate
php spark migrate:rollback
```

### 3. Tests des Seeders

**Test :**
- Exécuter le seeder `InsertUserData`
- Vérifier que les données sont insérées correctement
- Vérifier que la logique de vérification de colonne fonctionne

**Commande :**
```bash
php spark db:seed Helpdesk\Database\Seeds\InsertUserData
php spark db:seed Helpdesk\Database\Seeds\InsertPresencesData
```

### 4. Tests fonctionnels - Contrôleur Admin

**Scénarios à tester :**

1. **Création d'un utilisateur**
   - Accéder à `/user/admin/save_user/0`
   - Remplir le formulaire avec un nom d'utilisateur
   - Soumettre le formulaire
   - Vérifier que l'utilisateur est créé
   - Vérifier que les valeurs `$old_name` et `$old_usertype` sont bien utilisées en cas d'erreur

2. **Modification d'un utilisateur**
   - Accéder à `/user/admin/save_user/{id}`
   - Modifier le nom d'utilisateur
   - Soumettre le formulaire
   - Vérifier que les modifications sont sauvegardées
   - Vérifier que les anciennes valeurs sont bien conservées en cas d'erreur de validation

3. **Liste des utilisateurs**
   - Accéder à `/user/admin/list_user`
   - Vérifier que la liste s'affiche correctement

### 5. Tests fonctionnels - Contrôleur User (Helpdesk)

**Scénarios à tester :**

1. **Création d'un utilisateur avec données helpdesk**
   - Accéder à `/helpdesk/user/helpdesk_save_user/0`
   - Remplir le formulaire complet (nom, prénom, photo, etc.)
   - Soumettre le formulaire
   - Vérifier que l'utilisateur et ses données sont créés
   - Vérifier que les valeurs `$old_name` et `$old_usertype` sont bien utilisées en cas d'erreur

2. **Modification d'un utilisateur**
   - Accéder à `/helpdesk/user/helpdesk_save_user/{id}`
   - Modifier les informations
   - Soumettre le formulaire
   - Vérifier que les modifications sont sauvegardées

### 6. Tests fonctionnels - Contrôleur Planning

**Scénarios à tester :**

1. **Ajout d'un technicien au planning**
   - Accéder à `/helpdesk/planning/add_technician/0` (semaine courante)
   - Sélectionner un technicien
   - Remplir quelques périodes (pas toutes)
   - Soumettre le formulaire
   - Vérifier que la validation fonctionne (doit refuser si moins de 20 champs remplis)
   - Vérifier que `$empty_fields_count` compte correctement

2. **Modification du planning**
   - Accéder à `/helpdesk/planning/update_planning/0`
   - Modifier les rôles d'un technicien
   - Vider tous les champs d'un technicien
   - Soumettre le formulaire
   - Vérifier que la validation refuse (doit avoir au moins un champ rempli)
   - Vérifier que `$empty_fields_count` fonctionne correctement

3. **Génération automatique du planning**
   - Accéder à `/helpdesk/planning/planning_generation`
   - Vérifier que le planning est généré correctement

### 7. Tests des Seeders - InsertPresencesData

**Scénarios à tester :**

1. **Exécution du seeder**
   - Exécuter le seeder `InsertPresencesData`
   - Vérifier que les présences sont créées pour tous les utilisateurs (IDs 2 à 21)
   - Vérifier que la méthode `createPresences()` fonctionne avec `$user_id`

2. **Vérification des données**
   - Vérifier que les présences sont bien associées aux bons utilisateurs
   - Vérifier que les contraintes sont respectées (mon_m1, tue_m1, etc.)

## Checklist de vérification

- [ ] Aucune erreur de syntaxe PHP
- [ ] Les migrations s'exécutent sans erreur
- [ ] Les seeders s'exécutent sans erreur
- [ ] La création d'utilisateur fonctionne (Admin)
- [ ] La modification d'utilisateur fonctionne (Admin)
- [ ] La création d'utilisateur fonctionne (Helpdesk)
- [ ] La modification d'utilisateur fonctionne (Helpdesk)
- [ ] L'ajout de technicien au planning fonctionne
- [ ] La modification du planning fonctionne
- [ ] La validation des champs vides fonctionne
- [ ] Aucune erreur dans les logs PHP
- [ ] Aucune erreur dans la console du navigateur

## Commandes de test rapide

```bash
# Vérification syntaxe de tous les fichiers modifiés
for file in orif/helpdesk/Database/Seeds/InsertUserData.php orif/helpdesk/Database/Migrations/2023-08-08-140000_AddFkRoleToUserData.php orif/user/Controllers/Admin.php orif/helpdesk/Controllers/User.php orif/helpdesk/Database/Seeds/InsertPresencesData.php orif/helpdesk/Controllers/Planning.php; do
    php -l "$file"
done

# Vérifier qu'il ne reste aucune occurrence des anciennes variables
grep -r "\$columnExists\|\$oldName\|\$oldUsertype\|\$userId\|\$emptyFieldsCount" orif/

# Vérifier que les nouvelles variables sont bien utilisées
grep -r "\$column_exists\|\$old_name\|\$old_usertype\|\$user_id\|\$empty_fields_count" orif/
```

## Tests automatisés (si disponibles)

Si vous avez des tests unitaires ou fonctionnels :

```bash
php spark test
# ou
vendor/bin/phpunit
```

## Points d'attention

1. **Variables dans les paramètres de fonction** : Vérifier que les paramètres `$userId` dans `createPresences()` ont bien été renommés en `$user_id`

2. **Variables dans les boucles foreach** : Vérifier que `$userId` dans `foreach ($realisticUsers as $userId => $presences)` a bien été renommé

3. **Variables dans les conditions** : Vérifier que toutes les conditions utilisant ces variables fonctionnent toujours

4. **Logs d'erreur** : Surveiller les logs PHP pour détecter d'éventuelles erreurs non capturées

## En cas de problème

Si une erreur survient :

1. Vérifier les logs PHP : `writable/logs/`
2. Vérifier la console du navigateur (F12)
3. Activer le mode debug dans CodeIgniter
4. Vérifier que toutes les occurrences ont bien été remplacées :
   ```bash
   grep -r "ANCIENNE_VARIABLE" orif/
   ```

## Résultat attendu

Après tous ces tests, l'application doit fonctionner exactement comme avant le refactoring, mais avec des noms de variables conformes aux conventions snake_case.

