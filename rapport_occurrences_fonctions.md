# Rapport détaillé des occurrences des fonctions à modifier

## Contrôleurs - Module User

### 1. `errorhandler()` → `errorHandler()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 7** (définition + appels) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/user/Controllers/Auth.php` (ligne 47) ✅

- **Appels** :
  - `orif/user/Controllers/Auth.php` (lignes 243, 277, 283, 299, 307, 359) ✅

**Statut** : Toutes les occurrences ont été mises à jour. Aucune erreur de linting détectée.

---

### 2. `azure_login()` → `azureLogin()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 2** (définition + appel) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/user/Controllers/Auth.php` (ligne 218) ✅

- **Appel** :
  - `orif/user/Controllers/Auth.php` (ligne 432) ✅

**Statut** : Toutes les occurrences ont été mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 3. `change_password()` → `changePassword()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 12** (définition + routes) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/user/Controllers/Auth.php` (ligne 463) ✅

- **Routes/URLs** :
  - `orif/user/Views/auth/change_password.php` (ligne 20) ✅
  - `orif/common/Views/login_bar.php` (ligne 35) ✅

- **Note** : Les occurrences dans les vues (lignes 3, 18, 19, 65) et password_change_user.php sont des attributs HTML (IDs, noms de formulaires) qui restent en snake_case selon les conventions HTML.

**Statut** : Fonction et routes mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 4. `list_user()` → `listUser()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 26** (définition + routes + config) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/user/Controllers/Admin.php` (ligne 51) ✅

- **Routes/URLs dans les contrôleurs** :
  - `orif/user/Controllers/Admin.php` (lignes 114, 151, 166, 171, 173, 187, 204, 214) ✅
  - `orif/helpdesk/Controllers/User.php` (lignes 215, 256, 275, 284, 286) ✅

- **Routes/URLs dans les vues** :
  - `orif/user/Views/admin/list_user.php` (ligne 62) ✅
  - `orif/user/Views/admin/password_change_user.php` (ligne 61) ✅
  - `orif/user/Views/admin/form_user.php` (ligne 138) ✅
  - `orif/user/Views/admin/delete_user.php` (lignes 24, 42) ✅
  - `orif/helpdesk/Views/form_user.php` (ligne 159) ✅

- **Configuration** :
  - `orif/common/Config/AdminPanelConfig.php` (ligne 21) ✅

- **Note** : La ligne 72 de Admin.php (`display_view('\User\admin\list_user', $output)`) est le nom de la vue qui reste en snake_case selon les conventions. La documentation (GUIDE_TEST_REFACTORING.md, conventions.md) sera mise à jour ultérieurement.

**Statut** : Fonction et toutes les routes mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 5. `save_user()` → `saveUser()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 16** (définition + routes) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/user/Controllers/Admin.php` (ligne 81) ✅

- **Routes/URLs** :
  - `orif/user/Views/admin/form_user.php` (ligne 33) ✅

- **Note** :
  - La ligne 190 de Admin.php redirige vers `helpdesk_save_user` (fonction 9, à modifier séparément)
  - Les lignes dans list_user.php, dashboard.php et form_user.php (helpdesk) concernent `helpdesk_save_user` (fonction 9)
  - Les commentaires dans les vues (ligne 3) restent inchangés
  - La documentation (GUIDE_TEST_REFACTORING.md) sera mise à jour ultérieurement

**Statut** : Fonction et route principale mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 6. `delete_user()` → `deleteUser()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 18** (définition + routes) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/user/Controllers/Admin.php` (ligne 147) ✅

- **Routes/URLs** :
  - `orif/user/Views/admin/form_user.php` (ligne 121) ✅

- **Note** :
  - La ligne 160 de Admin.php et ligne 269 de User.php utilisent `display_view('\User\admin\delete_user', ...)` - c'est le nom de la vue qui reste en snake_case
  - Les lignes dans list_user.php, dashboard.php et form_user.php (helpdesk) concernent `helpdesk_delete_user` (fonction 10, à modifier séparément)
  - Les fichiers de langue (`btn_hard_delete_user`) sont des clés de langue, pas des noms de fonctions
  - Les commentaires dans delete_user.php restent inchangés

**Statut** : Fonction et route principale mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 7. `reactivate_user()` → `reactivateUser()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 4** (définition + routes) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/user/Controllers/Admin.php` (ligne 183) ✅

- **Routes/URLs** :
  - `orif/user/Views/admin/form_user.php` (ligne 116) ✅
  - `orif/helpdesk/Views/form_user.php` (ligne 137) ✅

**Statut** : Fonction et toutes les routes mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 8. `password_change_user()` → `passwordChangeUser()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 7** (définition + routes) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/user/Controllers/Admin.php` (ligne 200) ✅

- **Routes/URLs** :
  - `orif/user/Views/admin/password_change_user.php` (ligne 18) ✅
  - `orif/user/Views/admin/form_user.php` (ligne 108) ✅
  - `orif/helpdesk/Views/form_user.php` (ligne 129) ✅

- **Note** : La ligne 224 de Admin.php utilise `display_view('\User\admin\password_change_user', ...)` - c'est le nom de la vue qui reste en snake_case. Les commentaires restent inchangés.

**Statut** : Fonction et toutes les routes mises à jour. Aucune nouvelle erreur de linting détectée.

---

## Contrôleurs - Module Helpdesk

### 9. `helpdesk_save_user()` → `saveUser()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 9** (définition + routes) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/User.php` (ligne 46) ✅

- **Routes/URLs dans les contrôleurs** :
  - `orif/user/Controllers/Admin.php` (ligne 190) ✅

- **Routes/URLs dans les vues** :
  - `orif/user/Views/admin/list_user.php` (lignes 19, 46) ✅
  - `orif/helpdesk/Views/dashboard.php` (ligne 98) ✅
  - `orif/helpdesk/Views/form_user.php` (ligne 33) ✅

- **Note** : Les commentaires dans les vues restent inchangés. La documentation (GUIDE_TEST_REFACTORING.md) sera mise à jour ultérieurement.

**Statut** : Fonction et toutes les routes mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 10. `helpdesk_delete_user()` → `deleteUser()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 7** (définition + routes) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/User.php` (ligne 252) ✅

- **Routes/URLs** :
  - `orif/user/Views/admin/list_user.php` (ligne 50) ✅
  - `orif/user/Views/admin/form_user.php` (ligne 127) ✅
  - `orif/helpdesk/Views/dashboard.php` (ligne 101) ✅
  - `orif/helpdesk/Views/form_user.php` (lignes 142, 148) ✅

- **Note** : La ligne 269 de User.php utilise `display_view('\User\admin\delete_user', ...)` - c'est le nom de la vue qui reste en snake_case.

**Statut** : Fonction et toutes les routes mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 11. `update_technician_availability()` → `updateTechnicianAvailability()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 3** (définition + route) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Terminal.php` (ligne 148) ✅

- **Routes/URLs** :
  - `orif/helpdesk/Views/terminal.php` (ligne 45) ✅

**Statut** : Fonction et route mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 12. `lw_planning()` → `lastWeekPlanning()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 44** (définition + routes) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 51) ✅

- **Routes/URLs** :
  - `orif/helpdesk/Views/planning.php` (ligne 22) ✅

- **Note** : Les occurrences restantes sont des variables (`lw_planning_data`, `$lw_planning`), des noms de modèles (`lw_planning_model`), des noms de vues (`lw_planning.php`), des clés de langue (`Titles.lw_planning`), et des migrations qui restent en snake_case selon les conventions.

**Statut** : Fonction et route mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 13. `cw_planning()` → `currentWeekPlanning()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 35** (définition + routes) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 74) ✅

- **Routes/URLs dans les contrôleurs** :
  - `orif/helpdesk/Controllers/Planning.php` (lignes 38, 297, 572, 632) ✅
  - `orif/helpdesk/Controllers/Home.php` (ligne 74) ✅

- **Routes/URLs dans les vues** :
  - `orif/helpdesk/Views/holidays_list.php` (ligne 18) ✅
  - `orif/helpdesk/Views/nw_planning.php` (ligne 27) ✅
  - `orif/helpdesk/Views/presences_list.php` (ligne 18) ✅
  - `orif/helpdesk/Views/lw_planning.php` (ligne 26) ✅
  - `orif/helpdesk/Views/Common/planning_form_action_menu.php` (ligne 32) ✅

- **Note** : Les occurrences restantes sont des variables (`$cw_planning`, `cw_planning_data`), des clés de langue (`MiscTexts.cw_planning`), et d'autres références qui restent en snake_case selon les conventions.

**Statut** : Fonction et toutes les routes mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 14. `nw_planning()` → `nextWeekPlanning()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 122** (définition + routes) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 100) ✅

- **Routes/URLs dans les contrôleurs** :
  - `orif/helpdesk/Controllers/Planning.php` (lignes 332, 579, 637) ✅
  - `orif/helpdesk/Controllers/Home.php` (lignes 384, 407) ✅

- **Routes/URLs dans les vues** :
  - `orif/helpdesk/Views/planning.php` (ligne 26) ✅
  - `orif/helpdesk/Views/Common/planning_form_action_menu.php` (ligne 36) ✅

- **Documentation** :
  - `README.md` (ligne 203) ✅

- **Note** : Les occurrences restantes sont des variables (`$nw_planning`, `nw_planning_data`), des noms de modèles (`nw_planning_model`), des noms de vues (`nw_planning.php`), des clés de langue (`Titles.nw_planning`), et des migrations qui restent en snake_case selon les conventions.

**Statut** : Fonction et toutes les routes mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 15. `add_technician()` → `addTechnician()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 42** (définition + routes) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 131) ✅

- **Routes/URLs dans les vues** :
  - `orif/helpdesk/Views/dashboard.php` (lignes 43, 44, 46, 47) ✅
  - `orif/helpdesk/Views/nw_planning.php` (ligne 63) ✅
  - `orif/helpdesk/Views/planning.php` (ligne 58) ✅
  - `orif/helpdesk/Views/update_planning.php` (ligne 32) ✅
  - `orif/helpdesk/Views/add_technician.php` (ligne 20) ✅

- **Note** : Les clés de langue (`Buttons.add_technician`, `Titles.add_technician`) restent inchangées. Les commentaires dans les vues restent inchangés. La documentation (GUIDE_TEST_REFACTORING.md) sera mise à jour ultérieurement.

**Statut** : Fonction et toutes les routes mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 16. `update_planning()` → `updatePlanning()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 19** (définition + routes) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 345) ✅

- **Routes/URLs dans les contrôleurs** :
  - `orif/helpdesk/Controllers/Planning.php` (lignes 458, 467, 475, 595, 650) ✅

- **Routes/URLs dans les vues** :
  - `orif/helpdesk/Views/nw_planning.php` (ligne 39) ✅
  - `orif/helpdesk/Views/planning.php` (ligne 34) ✅
  - `orif/helpdesk/Views/update_planning.php` (ligne 20) ✅

- **Note** : Les clés de langue (`Titles.update_planning`) restent inchangées. Les commentaires dans les vues restent inchangés. La documentation (GUIDE_TEST_REFACTORING.md) sera mise à jour ultérieurement.

**Statut** : Fonction et toutes les routes mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 17. `delete_technician()` → `deleteTechnician()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 15** (définition + routes) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 550) ✅

- **Routes/URLs dans les contrôleurs** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 594) ✅

- **Routes/URLs dans les vues** :
  - `orif/helpdesk/Views/dashboard.php` (lignes 51, 52, 56, 61, 62, 66) ✅
  - `orif/helpdesk/Views/update_planning.php` (lignes 58, 80) ✅

- **Note** : Les clés de langue (`Buttons.delete_technician_from_actual_planning`, etc.) restent inchangées.

**Statut** : Fonction et toutes les routes mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 18. `delete_planning()` → `deletePlanning()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 6** (définition + routes) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 612) ✅

- **Routes/URLs dans les contrôleurs** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 649) ✅

- **Routes/URLs dans les vues** :
  - `orif/helpdesk/Views/Common/planning_form_action_menu.php` (ligne 21) ✅

- **Note** : Les clés de langue (`Buttons.delete_planning`) restent inchangées.

**Statut** : Fonction et toutes les routes mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 19. `shift_weeks()` → `shiftWeeks()` ✅ **TERMINÉ**

**Nombre total d'occurrences : 18** (définition + routes) - **Toutes modifiées**

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 669) ✅

- **Routes/URLs dans les contrôleurs** :
  - `orif/helpdesk/Controllers/Home.php` (lignes 382, 390, 398) ✅

- **Routes/URLs dans les vues** :
  - `orif/helpdesk/Views/nw_planning.php` (ligne 20) ✅

- **Note** : Les clés de langue (`Success.shift_weeks`, `Buttons.shift_weeks`, `'name' => 'shift_weeks'`) restent inchangées car ce sont des identifiants de messages, pas des noms de fonctions.

**Statut** : Fonction et toutes les routes mises à jour. Aucune nouvelle erreur de linting détectée.

---

### 20. `duplicate_planning()` → `duplicatePlanning()`

**Nombre total d'occurrences : 4** (définition + appels)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 728)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Planning.php` (lignes 683, 693)

---

### 21. `planning_generation()` → `planningGeneration()`

**Nombre total d'occurrences : 28** (définition + appels + routes + lang)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 774)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Planning.php` (lignes 701, 702, 786, 795, 803, 906, 912)

- **Routes/URLs** :
  - `orif/helpdesk/Controllers/Home.php` (ligne 413)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/Success.php` (lignes 27, 29)
  - `orif/helpdesk/Language/fr/MiscTexts.php` (ligne 32)
  - `orif/helpdesk/Language/fr/Errors.php` (lignes 33-36)
  - `orif/helpdesk/Language/fr/Buttons.php` (ligne 43)
  - `orif/helpdesk/Language/en/Success.php` (lignes 27, 29)
  - `orif/helpdesk/Language/en/MiscTexts.php` (ligne 32)
  - `orif/helpdesk/Language/en/Errors.php` (lignes 33-36)
  - `orif/helpdesk/Language/en/Buttons.php` (ligne 43)

- **Documentation** :
  - `GUIDE_TEST_REFACTORING.md` (ligne 125)

---

### 22. `presences_list()` → `presencesList()`

**Nombre total d'occurrences : 21** (définition + appels + routes + vues + lang)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Presences.php` (ligne 51)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Presences.php` (lignes 38, 63, 66, 81, 116, 216, 225, 240)

- **Routes/URLs** :
  - `orif/helpdesk/Views/technician_presences.php` (ligne 84)
  - `orif/helpdesk/Views/add_technician_presences.php` (ligne 36)
  - `orif/helpdesk/Views/Common/planning_presences_quick_goto.php` (ligne 15)
  - `orif/helpdesk/Views/Common/planning_nav.php` (ligne 15)

- **Vues** :
  - `orif/helpdesk/Views/presences_list.php` (ligne 4)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/Buttons.php` (ligne 15)
  - `orif/helpdesk/Language/fr/Titles.php` (ligne 15)
  - `orif/helpdesk/Language/en/Buttons.php` (ligne 15)
  - `orif/helpdesk/Language/en/Titles.php` (ligne 15)

---

### 23. `add_technician_presences()` → `addTechnicianPresences()`

**Nombre total d'occurrences : 14** (définition + appels + routes + vues + lang)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Presences.php` (ligne 75)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Presences.php` (lignes 92, 96, 98)

- **Routes/URLs** :
  - `orif/helpdesk/Views/presences_list.php` (ligne 22)
  - `orif/helpdesk/Views/add_technician_presences.php` (ligne 24)

- **Vues** :
  - `orif/helpdesk/Views/add_technician_presences.php` (ligne 4)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/MiscTexts.php` (ligne 13)
  - `orif/helpdesk/Language/fr/Buttons.php` (ligne 13)
  - `orif/helpdesk/Language/fr/Titles.php` (ligne 13)
  - `orif/helpdesk/Language/en/MiscTexts.php` (ligne 13)
  - `orif/helpdesk/Language/en/Buttons.php` (ligne 13)
  - `orif/helpdesk/Language/en/Titles.php` (ligne 13)

---

### 24. `technician_presences()` → `technicianPresences()`

**Nombre total d'occurrences : 31** (définition + appels + routes + vues + lang + CSS/JS)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Presences.php` (ligne 107)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Presences.php` (ligne 192, 199)

- **Routes/URLs** :
  - `orif/helpdesk/Views/dashboard.php` (ligne 77)
  - `orif/helpdesk/Views/presences_list.php` (lignes 24, 77)
  - `orif/helpdesk/Views/technician_presences.php` (ligne 36)

- **Vues** :
  - `orif/helpdesk/Views/technician_presences.php` (ligne 4)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/Titles.php` (ligne 16)
  - `orif/helpdesk/Language/en/Titles.php` (ligne 16)
  - `orif/helpdesk/Language/fr/Errors.php` (ligne 15)
  - `orif/helpdesk/Language/en/Errors.php` (ligne 15)
  - `orif/helpdesk/Language/fr/MiscTexts.php` (ligne 13)
  - `orif/helpdesk/Language/en/MiscTexts.php` (ligne 13)

- **Fichiers statiques** :
  - `orif/common/Views/header.php` (lignes 41, 80)

---

### 25. `delete_presences()` → `deletePresences()`

**Nombre total d'occurrences : 5** (définition + appels + routes)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Presences.php` (ligne 211)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Presences.php` (ligne 239)

- **Routes/URLs** :
  - `orif/helpdesk/Views/dashboard.php` (ligne 81)
  - `orif/helpdesk/Views/presences_list.php` (ligne 80)

---

### 26. `holidays_list()` → `holidaysList()`

**Nombre total d'occurrences : 8** (définition + appels + routes)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Holidays.php` (ligne 51)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Holidays.php` (lignes 38, 60, 126, 169)

- **Routes/URLs** :
  - `orif/helpdesk/Views/add_holiday.php` (ligne 55)
  - `orif/helpdesk/Views/Common/planning_nav.php` (ligne 16)

---

### 27. `save_holiday()` → `saveHoliday()`

**Nombre total d'occurrences : 8** (définition + appels + routes + vues)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Holidays.php` (ligne 72)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Holidays.php` (lignes 160, 183)

- **Routes/URLs** :
  - `orif/helpdesk/Views/holidays_list.php` (lignes 22, 47)
  - `orif/helpdesk/Views/add_holiday.php` (lignes 20, 23)

---

### 28. `delete_holiday()` → `deleteHoliday()`

**Nombre total d'occurrences : 4** (définition + appels + routes)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Holidays.php` (ligne 155)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Holidays.php` (ligne 182)

- **Routes/URLs** :
  - `orif/helpdesk/Views/add_holiday.php` (ligne 51)

---

### 29. `confirm_action()` → `confirmAction()`

**Nombre total d'occurrences : 13** (définition + appels + routes + vues + lang + CSS)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Home.php` (ligne 376)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Home.php` (lignes 435, 438)

- **Routes/URLs** :
  - `orif/helpdesk/Views/nw_planning.php` (lignes 20, 21)

- **Vues** :
  - `orif/helpdesk/Views/confirm_action.php` (ligne 4)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/MiscTexts.php` (ligne 35)
  - `orif/helpdesk/Language/fr/Titles.php` (ligne 33)
  - `orif/helpdesk/Language/en/MiscTexts.php` (ligne 35)
  - `orif/helpdesk/Language/en/Titles.php` (ligne 33)

- **Fichiers statiques** :
  - `orif/common/Views/header.php` (ligne 72)

---

## Contrôleurs - Module Welcome

### 30. `display_items()` → `displayItems()`

**Nombre total d'occurrences : 5** (définition + appels + routes + documentation)

#### Localisation

- **Définition** :
  - `orif/welcome/Controllers/Home.php` (ligne 26)

- **Appels dans le contrôleur** :
  - `orif/welcome/Controllers/Home.php` (ligne 59)

- **Routes/URLs** :
  - `orif/welcome/Views/welcome_message.php` (ligne 234)

- **Documentation** :
  - `orif/common/Views/items_list.php` (ligne 47)

---

## Models - Module User

### 31. `check_password_name()` → `checkPasswordName()`

**Nombre total d'occurrences : 4** (définition + appels)

#### Localisation

- **Définition** :
  - `orif/user/Models/User_model.php` (ligne 87)

- **Appels** :
  - `orif/user/Controllers/Auth.php` (lignes 404, 479)

---

### 32. `check_password_email()` → `checkPasswordEmail()`

**Nombre total d'occurrences : 3** (définition + appel)

#### Localisation

- **Définition** :
  - `orif/user/Models/User_model.php` (ligne 104)

- **Appel** :
  - `orif/user/Controllers/Auth.php` (ligne 403)

---

### 33. `get_access_level()` → `getAccessLevel()`

**Nombre total d'occurrences : 5** (définition + appels)

#### Localisation

- **Définition** :
  - `orif/user/Models/User_model.php` (ligne 122)

- **Appels** :
  - `orif/user/Controllers/Auth.php` (lignes 104, 351, 417)

---

## Validation Rules - Module Helpdesk

### 34. `not_in_planning()` → `notInPlanning()`

**Nombre total d'occurrences : 6** (définition + utilisation + lang)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Validation/Rules/NotInPlanning.php` (ligne 37)

- **Utilisation dans les règles de validation** :
  - `orif/helpdesk/Controllers/Planning.php` (lignes 158, 160)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/Forms/Errors.php` (ligne 17)
  - `orif/helpdesk/Language/en/Forms/Errors.php` (ligne 17)

---

### 35. `has_presences()` → `hasPresences()`

**Nombre total d'occurrences : 8** (définition + utilisation + lang + variables)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Validation/Rules/HasPresences.php` (ligne 33)

- **Utilisation dans les règles de validation** :
  - `orif/helpdesk/Controllers/Planning.php` (lignes 158, 161)

- **Variables dans les contrôleurs** :
  - `orif/helpdesk/Controllers/User.php` (ligne 260)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/Forms/Errors.php` (ligne 15)
  - `orif/helpdesk/Language/en/Forms/Errors.php` (ligne 15)

---

### 36. `coherent_dates()` → `coherentDates()`

**Nombre total d'occurrences : 6** (définition + utilisation + lang)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Validation/Rules/CoherentDates.php` (ligne 25)

- **Utilisation dans les règles de validation** :
  - `orif/helpdesk/Controllers/Holidays.php` (lignes 86, 103)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/Forms/Errors.php` (ligne 20)
  - `orif/helpdesk/Language/en/Forms/Errors.php` (ligne 20)

---

### 37. `french_alpha()` → `frenchAlpha()`

**Nombre total d'occurrences : 18** (définition + utilisation + lang)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Validation/Rules/FrenchAlpha.php` (ligne 24)

- **Utilisation dans les règles de validation** :
  - `orif/helpdesk/Controllers/User.php` (lignes 94, 95, 99, 100, 138, 139, 143, 144)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/Forms/Errors.php` (ligne 25)
  - `orif/helpdesk/Language/en/Forms/Errors.php` (ligne 25)

---

### 38. `french_alpha_space()` → `frenchAlphaSpace()`

**Nombre total d'occurrences : 6** (définition + utilisation + lang)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Validation/Rules/FrenchAlphaSpace.php` (ligne 24)

- **Utilisation dans les règles de validation** :
  - `orif/helpdesk/Controllers/Holidays.php` (lignes 84, 92)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/Forms/Errors.php` (ligne 21)
  - `orif/helpdesk/Language/en/Forms/Errors.php` (ligne 21)

---

## Validation Rules - Module User

### 39. `cb_unique_username()` → `cbUniqueUsername()`

**Nombre total d'occurrences : 3** (définition + commentaires)

#### Localisation

- **Définition** :
  - `orif/user/Validation/CustomRules.php` (ligne 26)

- **Commentaires (code commenté)** :
  - `orif/user/Models/User_model.php` (ligne 56)

---

### 40. `cb_unique_useremail()` → `cbUniqueUseremail()`

**Nombre total d'occurrences : 3** (définition + commentaires)

#### Localisation

- **Définition** :
  - `orif/user/Validation/CustomRules.php` (ligne 39)

- **Commentaires (code commenté)** :
  - `orif/user/Models/User_model.php` (ligne 58)

---

### 41. `cb_not_null_user_type()` → `cbNotNullUserType()`

**Nombre total d'occurrences : 6** (définition + utilisation + commentaires)

#### Localisation

- **Définition** :
  - `orif/user/Validation/CustomRules.php` (ligne 51)

- **Utilisation dans les règles de validation** :
  - `orif/helpdesk/Controllers/User.php` (lignes 86, 130)

- **Commentaires (code commenté)** :
  - `orif/user/Models/User_model.php` (lignes 41, 60)

---

## Résumé global

### Total des occurrences à modifier : **~700 occurrences** réparties sur **41 fonctions**

### Répartition par type de fichier

- **Contrôleurs** : ~200 occurrences
- **Vues** : ~150 occurrences
- **Routes/URLs** : ~200 occurrences
- **Fichiers de langue** : ~100 occurrences
- **Modèles** : ~20 occurrences
- **Règles de validation** : ~30 occurrences

### Points d'attention critiques

1. **Routes CodeIgniter** : Toutes les URLs dans les vues et contrôleurs devront être mises à jour
2. **Fichiers de langue** : Les clés de langue qui correspondent aux noms de fonctions devront être vérifiées
3. **Règles de validation** : Les noms de règles dans `setRule()` devront être mis à jour
4. **Documentation** : Les fichiers de documentation et guides devront être mis à jour
5. **Fichiers statiques** : Les références dans les fichiers CSS/JS devront être vérifiées
