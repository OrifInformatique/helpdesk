# Rapport des variables non conformes au snake_case ou non intelligibles

## Variables en camelCase (non snake_case)

### Fichiers: Seeds

- **InsertUserData.php** (ligne 13, 222, 224)
  - `$columnExists` → devrait être `$column_exists`

- **InsertPresencesData.php** (lignes 16, 30, 41, 52, 69, 111, 120, 133)
  - `$samePresences` → devrait être `$same_presences`
  - `$PresentPresences` → devrait être `$present_presences`
  - `$partlyAbsentPresences` → devrait être `$partly_absent_presences`
  - `$allAbsentPresences` → devrait être `$all_absent_presences`
  - `$realisticUsers` → devrait être `$realistic_users`
  - `$userId` → devrait être `$user_id`
  - `$presenceData` → devrait être `$presence_data`

### Fichiers: Controllers

- **Planning.php** (lignes 403, 419, 462)
  - `$emptyFieldsCount` → devrait être `$empty_fields_count`

- **Auth.php** (lignes 158, 166, 223, 232, 321, 331, 333, 334, 336, 341)
  - `$emailConfig` → devrait être `$email_config`
  - `$graphUserScopes` → devrait être `$graph_user_scopes`
  - `$nameAndLastname` → devrait être `$name_and_lastname`
  - `$correspondingUser` → devrait être `$corresponding_user`
  - `$correspondingEmail` → devrait être `$corresponding_email`
  - `$password_max_lenght` → devrait être `$password_max_length` (typo + camelCase)

- **Admin.php** (lignes 84, 85, 90, 92, 128, 129)
  - `$oldName` → devrait être `$old_name`
  - `$oldUsertype` → devrait être `$old_usertype`

- **User.php** (lignes 49, 50, 52, 55, 57, 231, 232)
  - `$oldName` → devrait être `$old_name`
  - `$oldUsertype` → devrait être `$old_usertype`
  - `$user` → acceptable mais pourrait être `$user_data` ou `$current_user` selon le contexte
  - `$post_data` → devrait être `$post_data` (déjà snake_case, OK)

- **Terminal.php** (ligne 61, 63)
  - `$isDayOff` → devrait être `$is_day_off`

### Fichiers: Views

- **dashboard.php** (ligne 92)
  - `$isUserLoggedAdmin` → devrait être `$is_user_logged_admin`

- **mail_form.php** (ligne 31)
  - `$correspondingEmail` → devrait être `$corresponding_email`
  - `$user_email` → acceptable (snake_case)

- **verification_code_form.php** (lignes 14, 16, 17, 19)
  - `$errorMsg` → devrait être `$error_msg`
  - `$attemptsLeft` → devrait être `$attempts_left`
  - `$msg_attemptsLeft` → devrait être `$msg_attempts_left` (déjà snake_case mais pourrait être mieux)

### Fichiers: Migrations

- **2023-08-08-140000_AddFkRoleToUserData.php** (lignes 15, 18, 48, 50)
  - `$columnExists` → devrait être `$column_exists`

## Variables non intelligibles (noms trop courts ou abréviations obscures)

### Variables à une seule lettre

- **`$i`** - Utilisée dans plusieurs fichiers comme variable de boucle :
  - `InsertPresencesData.php` (lignes 24, 25, 99, 101)
  - `Planning.php` (lignes 747, 756, 757)
  - `Auth.php` (lignes 151, 197)
  - `presences_list.php` (ligne 43)
  - `add_technician.php` (lignes 49, 57, 61)
  - `planning_schedules_row.php` (ligne 16)
  - **Recommandation**: Utiliser des noms plus descriptifs comme `$index`, `$counter`, `$user_index`, etc.

- **`$e`** - Utilisée pour les exceptions :
  - `Planning.php` (ligne 910)
  - `Auth.php` (lignes 267, 269)
  - **Recommandation**: Utiliser `$exception` ou `$ex`

### Variables génériques peu descriptives

- **`$row`** - Utilisée dans de nombreux fichiers :
  - `InsertUserData.php` (lignes 208, 212, 222, 224, 226, 229)
  - `Planning.php` (lignes 130, 218, 744)
  - Et bien d'autres...
  - **Recommandation**: Utiliser des noms plus spécifiques selon le contexte (`$user_row`, `$planning_row`, etc.)

- **`$key`** - Utilisée dans plusieurs fichiers :
  - `Planning.php` (lignes 239, 321, 749, 1122, 1183, 1188)
  - **Recommandation**: Utiliser des noms plus spécifiques (`$array_key`, `$period_key`, etc.)

- **`$value`** - Utilisée dans plusieurs fichiers :
  - `Planning.php` (lignes 749, 1192)
  - **Recommandation**: Utiliser des noms plus spécifiques selon le contexte

- **`$data`** - Utilisée très fréquemment dans tous les fichiers :
  - Trop générique, devrait être remplacée par des noms plus spécifiques selon le contexte
  - **Recommandation**: `$user_data`, `$planning_data`, `$form_data`, etc.

## Variables avec typos ou problèmes de nommage

- **Presences_model.php** (ligne 131)
  - `$id_presnece` → devrait être `$id_presence` (typo dans le commentaire DocBlock)

- **Auth.php** (ligne 197)
  - `$password_max_lenght` → devrait être `$password_max_length` (typo)

## Résumé

### Total des variables non conformes identifiées

- **Variables en camelCase**: ~30 occurrences
- **Variables non intelligibles (une lettre)**: ~17 occurrences de `$i`, ~3 occurrences de `$e`
- **Variables génériques peu descriptives**: Nombreuses occurrences de `$row`, `$key`, `$value`, `$data`
- **Variables avec typos**: 2 occurrences

### Recommandations générales

1. Convertir toutes les variables camelCase en snake_case
2. Remplacer les variables à une lettre (`$i`, `$e`) par des noms plus descriptifs
3. Remplacer les variables génériques (`$row`, `$key`, `$value`, `$data`) par des noms contextuels plus spécifiques
4. Corriger les typos identifiées
