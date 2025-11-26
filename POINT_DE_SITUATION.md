# POINT DE SITUATION - PROJET HELPDESK

**Date** : 2025-01-26  
**Branche** : `feat/add-roles-and-user-seeders`  
**Statut** : Modifications en cours, prêt pour commit

---

## OBJECTIF DU COMMIT

Ce commit a pour objectif d'améliorer la structure de la base de données et d'enrichir les données de test (seeders) pour faciliter le développement et les tests de l'application Helpdesk.

**Objectifs spécifiques :**

1. **Amélioration de la structure des rôles** : Ajout de contraintes d'assignation pour gérer les limites minimales et maximales d'assignation par rôle de technicien
2. **Amélioration de la relation utilisateur-rôle** : Ajout de la clé étrangère `fk_role_id` dans la table `tbl_user_data` pour une meilleure intégrité référentielle
3. **Enrichissement des données de test** : Création de seeders complets pour les assignations, présences et utilisateurs avec des données réalistes
4. **Réorganisation des migrations** : Déplacement de la migration `TblPresences` pour respecter l'ordre chronologique et amélioration de la gestion des dépendances entre migrations

---

## MODIFICATIONS DU COMMIT À VENIR

### 1. Migrations de Base de Données

#### 1.1 Modification : `2023-08-08-133000_TblRoles.php`

**Changements :**

- Ajout de 6 nouveaux champs pour gérer les contraintes d'assignation :

  - `min_assignation_first_technician_role` (INT, nullable)
  - `max_assignation_first_technician_role` (INT, nullable)
  - `min_assignation_second_technician_role` (INT, nullable)
  - `max_assignation_second_technician_role` (INT, nullable)
  - `min_assignation_third_technician_role` (INT, nullable)
  - `max_assignation_third_technician_role` (INT, nullable)

**Impact :** +48 lignes

#### 1.2 Modification : `2023-08-08-133000_TblUserData.php`

**Changements :**

- Ajout du champ `fk_role_id` directement dans la migration principale
- Ajout de la clé étrangère vers `tbl_roles` avec `CASCADE` sur update et delete
- Suppression de l'appel au seeder `InsertUserData` (déplacé dans une migration séparée)

**Impact :** +13 lignes, -3 lignes

#### 1.3 Suppression et Recréation : `TblPresences.php`

**Ancien fichier supprimé :**

- `orif/helpdesk/Database/Migrations/2023-08-08-133000_TblPresences.php` (69 lignes)

**Nouveau fichier créé :**

- `orif/helpdesk/Database/Migrations/2023-08-08-135000_TblPresences.php`
  - Même structure mais avec un timestamp différent (135000 au lieu de 133000)
  - Ajout de l'appel au seeder `InsertPresencesData`

**Justification :** Réorganisation de l'ordre d'exécution des migrations pour respecter les dépendances (Presences doit être créée après Statuses).

#### 1.4 Nouvelle Migration : `2023-08-08-140000_AddFkRoleToUserData.php`

**Fonctionnalité :**

- Migration de compatibilité pour les bases de données existantes
- Vérifie si la colonne `fk_role_id` existe avant de l'ajouter
- Appelle le seeder `InsertUserData` après création de toutes les tables
- Gère le rollback proprement

**Impact :** +59 lignes (nouveau fichier)

#### 1.5 Modification : `2023-08-08-133000_TblAssignation.php`

**Changements :**

- Ajustements mineurs dans la structure

**Impact :** +2 lignes, -2 lignes

### 2. Seeders (Données de Test)

#### 2.1 Modification : `InsertRolesData.php`

**Changements :**

- Enrichissement des données de rôles avec les nouvelles contraintes d'assignation
- Ajout de valeurs pour les champs `min_assignation_*` et `max_assignation_*`

**Impact :** +80 lignes

#### 2.2 Modification : `InsertUserData.php`

**Changements majeurs :**

- **Ajout de 19 nouveaux utilisateurs de test** (de 2 à 21 utilisateurs au total)
- **Ajout du champ `fk_role_id`** pour chaque utilisateur
- **Vérification de l'existence de la colonne** `fk_role_id` avant insertion
- **Vérification de l'existence des enregistrements** pour éviter les doublons
- **Gestion conditionnelle** : insertion de `fk_role_id` seulement si la colonne existe

**Répartition des rôles :**

- Rôle 1 (Admin) : Utilisateurs 1, 12, 16
- Rôle 2 (First Technician) : Utilisateurs 2, 7, 8, 13, 19
- Rôle 3 (Second Technician) : Utilisateurs 3, 18
- Rôle 4 (Third Technician) : Utilisateurs 4, 20
- Rôle 5 (Fourth Technician) : Utilisateurs 5, 6, 14, 15, 17
- Rôle 6 (Reserve) : Utilisateurs 9, 10, 11, 21

**Impact :** +200 lignes

#### 2.3 Nouveau Seeder : `InsertAssignationsData.php`

**Fonctionnalité :**

- Création de 3 types d'assignations :
  1. Technicien d'astreinte
  2. Technicien de backup
  3. Technicien de réserve

**Impact :** +32 lignes (nouveau fichier)

#### 2.4 Nouveau Seeder : `InsertPresencesData.php`

**Fonctionnalité :**

- Création de présences réalistes pour 20 utilisateurs (IDs 2 à 21)
- **5 utilisateurs** avec les mêmes présences (présents toute la semaine)
- **3 utilisateurs** avec des présences uniformes :
  - 1 utilisateur présent partout
  - 1 utilisateur absent en partie partout
  - 1 utilisateur absent partout
- **12 utilisateurs** avec des présences réalistes et variées :
  - Configuration pour tester les contraintes d'assignation
  - 1 période avec seulement 1 utilisateur disponible
  - 1 période avec seulement 2 utilisateurs disponibles
  - 1 période avec seulement 3 utilisateurs disponibles
  - 1 période avec tous les utilisateurs disponibles
- Vérification de l'existence avant insertion pour éviter les doublons

**Impact :** +127 lignes (nouveau fichier)

### 3. Modèles

#### 3.1 Modification : `User_data_model.php`

**Changements :**

- Ajustements mineurs pour prendre en compte le nouveau champ `fk_role_id`

**Impact :** +2 lignes, -2 lignes

### 4. Autres Modifications

#### 4.1 Modification : `README.md`

**Changements :**

- Correction de la commande de migration Docker : `php spark migrate` → `php spark migrate --all`

**Impact :** +1 ligne, -1 ligne

#### 4.2 Modification : `orif/user/Database/Seeds/AddUserDatas.php`

**Changements :**

- Ajustements pour la compatibilité avec les nouvelles structures

**Impact :** +31 lignes

---

## POINTS CRITIQUES / POINTS À VÉRIFIER

> **⚠️ SECTION À COMPLÉTER AVANT LA COMMUNICATION**

### 🔴 Points Critiques (À Vérifier Absolument)

1. **[lignes 105-112]** Répartition des rôles
   - **Description :** S'assurer que la répartition des assignations est bien respectées
   - **Impact :** Garantir les bonnes bases sur le test futur de l'algorithme
   - **Action requise :** soit contrôler ligne par ligne soit avec des requêtes SQL (non créés actuellement).

### 🟡 Points d'Attention (À Surveiller)

1. **[lignes 127-143]** Contrôle du respect des présences dans la table tbl_presences
   - **Description :** 2.4 Nouveau Seeder : InsertPresencesData.php
   - **Impact :** Contrôler que l'attribution des rôles et des présences répond bien à la documentation
   - **Action requise :** soit requêtes SQL avec contrôle manuel du résultat soit tests unitaires

### 📋 Checklist de Vérification

- [ ] Toutes les migrations ont été testées sur une base de données vierge
- [ ] Les seeders fonctionnent correctement et n'insèrent pas de doublons
- [ ] Les contraintes de clés étrangères sont correctement appliquées
- [ ] La documentation a été mise à jour si nécessaire
- [ ] La compatibilité avec les données existantes est garantie
- [ ] Le rollback des migrations fonctionne correctement
