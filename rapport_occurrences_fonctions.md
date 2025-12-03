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

### 4. `list_user()` → `listUser()`

**Nombre total d'occurrences : 26** (définition + appels + routes + vues + config)

#### Localisation

- **Définition** :
  - `orif/user/Controllers/Admin.php` (ligne 51)

- **Appels dans les contrôleurs** :
  - `orif/user/Controllers/Admin.php` (lignes 72, 114, 151, 166, 171, 173, 187, 204, 214)
  - `orif/helpdesk/Controllers/User.php` (lignes 215, 256, 275, 284, 286)

- **Routes/URLs** :
  - `orif/user/Views/admin/list_user.php` (ligne 62)
  - `orif/user/Views/admin/password_change_user.php` (ligne 61)
  - `orif/user/Views/admin/form_user.php` (ligne 138)
  - `orif/user/Views/admin/delete_user.php` (lignes 24, 42)
  - `orif/helpdesk/Views/form_user.php` (ligne 159)

- **Configuration** :
  - `orif/common/Config/AdminPanelConfig.php` (ligne 21)

- **Documentation** :
  - `GUIDE_TEST_REFACTORING.md` (ligne 84)
  - `conventions.md` (lignes 57, 73)

---

### 5. `save_user()` → `saveUser()`

**Nombre total d'occurrences : 16** (définition + appels + routes + vues)

#### Localisation

- **Définition** :
  - `orif/user/Controllers/Admin.php` (ligne 81)

- **Appels dans le contrôleur** :
  - `orif/user/Controllers/Admin.php` (ligne 190)

- **Routes/URLs** :
  - `orif/user/Views/admin/list_user.php` (ligne 19)
  - `orif/user/Views/admin/form_user.php` (ligne 33)
  - `orif/helpdesk/Views/dashboard.php` (ligne 98)
  - `orif/helpdesk/Views/form_user.php` (ligne 33)

- **Documentation** :
  - `GUIDE_TEST_REFACTORING.md` (lignes 70, 77)

- **Vues** :
  - `orif/user/Views/admin/form_user.php` (ligne 3)

---

### 6. `delete_user()` → `deleteUser()`

**Nombre total d'occurrences : 18** (définition + appels + routes + vues + lang)

#### Localisation

- **Définition** :
  - `orif/user/Controllers/Admin.php` (ligne 147)

- **Appels dans les contrôleurs** :
  - `orif/user/Controllers/Admin.php` (ligne 160)
  - `orif/helpdesk/Controllers/User.php` (ligne 269)

- **Routes/URLs** :
  - `orif/user/Views/admin/list_user.php` (ligne 50)
  - `orif/user/Views/admin/form_user.php` (lignes 121, 122, 127)
  - `orif/helpdesk/Views/dashboard.php` (ligne 101)
  - `orif/helpdesk/Views/form_user.php` (lignes 142, 143, 148)

- **Vues** :
  - `orif/user/Views/admin/delete_user.php` (ligne 3)

- **Fichiers de langue** :
  - `orif/user/Language/fr/user_lang.php` (ligne 31)
  - `orif/user/Language/en/user_lang.php` (ligne 31)

---

### 7. `reactivate_user()` → `reactivateUser()`

**Nombre total d'occurrences : 4** (définition + routes)

#### Localisation

- **Définition** :
  - `orif/user/Controllers/Admin.php` (ligne 183)

- **Routes/URLs** :
  - `orif/user/Views/admin/form_user.php` (ligne 116)
  - `orif/helpdesk/Views/form_user.php` (ligne 137)

---

### 8. `password_change_user()` → `passwordChangeUser()`

**Nombre total d'occurrences : 7** (définition + appels + routes + vues)

#### Localisation

- **Définition** :
  - `orif/user/Controllers/Admin.php` (ligne 200)

- **Appels dans le contrôleur** :
  - `orif/user/Controllers/Admin.php` (ligne 224)

- **Routes/URLs** :
  - `orif/user/Views/admin/password_change_user.php` (ligne 18)
  - `orif/user/Views/admin/form_user.php` (ligne 108)
  - `orif/helpdesk/Views/form_user.php` (ligne 129)

- **Vues** :
  - `orif/user/Views/admin/password_change_user.php` (ligne 3)

---

## Contrôleurs - Module Helpdesk

### 9. `helpdesk_save_user()` → `saveUser()`

**Nombre total d'occurrences : 9** (définition + appels + routes + vues)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/User.php` (ligne 46)

- **Appels dans le contrôleur** :
  - `orif/user/Controllers/Admin.php` (ligne 190)

- **Routes/URLs** :
  - `orif/user/Views/admin/list_user.php` (lignes 19, 46)
  - `orif/helpdesk/Views/dashboard.php` (ligne 98)
  - `orif/helpdesk/Views/form_user.php` (ligne 33)

- **Documentation** :
  - `GUIDE_TEST_REFACTORING.md` (lignes 92, 99)

- **Vues** :
  - `orif/helpdesk/Views/form_user.php` (ligne 3)

---

### 10. `helpdesk_delete_user()` → `deleteUser()`

**Nombre total d'occurrences : 7** (définition + appels + routes)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/User.php` (ligne 252)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/User.php` (ligne 269)

- **Routes/URLs** :
  - `orif/user/Views/admin/list_user.php` (ligne 50)
  - `orif/user/Views/admin/form_user.php` (ligne 127)
  - `orif/helpdesk/Views/dashboard.php` (ligne 101)
  - `orif/helpdesk/Views/form_user.php` (lignes 142, 148)

---

### 11. `update_technician_availability()` → `updateTechnicianAvailability()`

**Nombre total d'occurrences : 3** (définition + route + vue)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Terminal.php` (ligne 148)

- **Routes/URLs** :
  - `orif/helpdesk/Views/terminal.php` (ligne 45)

---

### 12. `lw_planning()` → `lastWeekPlanning()`

**Nombre total d'occurrences : 44** (définition + appels + routes + modèles + migrations + lang)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 51)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Planning.php` (lignes 58, 61, 64, 676, 683, 684)

- **Routes/URLs** :
  - `orif/helpdesk/Views/planning.php` (ligne 22)

- **Modèles** :
  - `orif/helpdesk/Models/Lw_planning_model.php` (lignes 4, 17, 19, 20, 24-28, 52, 54, 66, 67, 72)

- **Migrations** :
  - `orif/helpdesk/Database/Migrations/2023-08-15-150000_TblLwPlanning.php` (lignes 15-19, 24, 40, 60, 67)

- **Vues** :
  - `orif/helpdesk/Views/lw_planning.php` (lignes 38, 39)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/Titles.php` (ligne 19)
  - `orif/helpdesk/Language/en/Titles.php` (ligne 19)

- **Autres** :
  - `orif/helpdesk/Controllers/Home.php` (lignes 20, 34, 50, 111-115)

---

### 13. `cw_planning()` → `currentWeekPlanning()`

**Nombre total d'occurrences : 35** (définition + appels + routes + variables + lang)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 74)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Planning.php` (lignes 38, 297, 572, 632, 644, 679, 681, 683, 693, 694, 714, 817, 865, 868, 1150, 1151, 1153, 1157, 1159, 1160, 1163, 1166)

- **Routes/URLs** :
  - `orif/helpdesk/Views/holidays_list.php` (ligne 18)
  - `orif/helpdesk/Views/nw_planning.php` (ligne 27)
  - `orif/helpdesk/Views/presences_list.php` (ligne 18)
  - `orif/helpdesk/Views/lw_planning.php` (ligne 26)
  - `orif/helpdesk/Views/Common/planning_form_action_menu.php` (ligne 32)

- **Variables dans les vues** :
  - `orif/helpdesk/Views/dashboard.php` (ligne 50)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/MiscTexts.php` (ligne 40)
  - `orif/helpdesk/Language/en/MiscTexts.php` (ligne 40)

- **Autres** :
  - `orif/helpdesk/Controllers/Home.php` (lignes 74, 426)
  - `orif/helpdesk/Controllers/Technician.php` (ligne 87)

---

### 14. `nw_planning()` → `nextWeekPlanning()`

**Nombre total d'occurrences : 122** (définition + appels + routes + modèles + migrations + lang + variables)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 100)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Planning.php` (lignes 110, 113, 116, 198, 304-327, 330, 332, 383, 397, 485, 499, 518, 520, 521, 575, 577, 579, 635, 637, 644, 689, 691, 693, 695, 708, 751, 787, 796, 804, 828, 887, 889, 890, 903, 907, 913)

- **Routes/URLs** :
  - `orif/helpdesk/Views/planning.php` (ligne 26)
  - `orif/helpdesk/Views/Common/planning_form_action_menu.php` (ligne 36)

- **Modèles** :
  - `orif/helpdesk/Models/Nw_planning_model.php` (lignes 4, 17, 19, 20, 24-28, 52, 54, 66, 67, 72, 86, 87, 93, 107, 109)

- **Migrations** :
  - `orif/helpdesk/Database/Migrations/2023-08-15-150000_TblNwPlanning.php` (plusieurs occurrences)

- **Vues** :
  - `orif/helpdesk/Views/nw_planning.php` (lignes 38, 49, 50)
  - `orif/helpdesk/Views/update_planning.php` (lignes 62-65, 68, 75, 80)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/MiscTexts.php` (ligne 43)
  - `orif/helpdesk/Language/fr/Titles.php` (lignes 20, 24)
  - `orif/helpdesk/Language/en/MiscTexts.php` (ligne 41)
  - `orif/helpdesk/Language/en/Titles.php` (lignes 20, 24)

- **Autres** :
  - `orif/helpdesk/Controllers/Home.php` (lignes 22, 36, 52, 131-135, 384, 402, 407, 421)
  - `orif/helpdesk/Controllers/Technician.php` (ligne 88)
  - `orif/helpdesk/Validation/Rules/NotInPlanning.php` (lignes 15, 20, 25, 46)
  - `README.md` (ligne 203)

---

### 15. `add_technician()` → `addTechnician()`

**Nombre total d'occurrences : 42** (définition + appels + routes + vues + lang)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 131)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Planning.php` (lignes 145, 151, 168, 240, 248, 256)

- **Routes/URLs** :
  - `orif/helpdesk/Views/dashboard.php` (lignes 43, 44, 46, 47)
  - `orif/helpdesk/Views/nw_planning.php` (ligne 63)
  - `orif/helpdesk/Views/planning.php` (ligne 58)
  - `orif/helpdesk/Views/update_planning.php` (ligne 32)
  - `orif/helpdesk/Views/add_technician.php` (ligne 20)

- **Vues** :
  - `orif/helpdesk/Views/add_technician.php` (ligne 4)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/Buttons.php` (lignes 18, 46, 47)
  - `orif/helpdesk/Language/fr/Titles.php` (ligne 23)
  - `orif/helpdesk/Language/en/Buttons.php` (lignes 18, 46, 47)
  - `orif/helpdesk/Language/en/Titles.php` (ligne 23)

- **Documentation** :
  - `GUIDE_TEST_REFACTORING.md` (ligne 109)

- **Autres** :
  - `orif/helpdesk/Views/Common/planning_form_action_menu.php` (ligne 4)

---

### 16. `update_planning()` → `updatePlanning()`

**Nombre total d'occurrences : 19** (définition + appels + routes + vues + lang)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 345)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Planning.php` (lignes 458, 467, 475, 514, 537, 555, 595, 617, 650)

- **Routes/URLs** :
  - `orif/helpdesk/Views/nw_planning.php` (ligne 39)
  - `orif/helpdesk/Views/planning.php` (ligne 34)
  - `orif/helpdesk/Views/update_planning.php` (ligne 20)

- **Vues** :
  - `orif/helpdesk/Views/update_planning.php` (ligne 4)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/Titles.php` (ligne 25)
  - `orif/helpdesk/Language/en/Titles.php` (ligne 25)

- **Documentation** :
  - `GUIDE_TEST_REFACTORING.md` (ligne 117)

- **Autres** :
  - `orif/helpdesk/Views/Common/planning_form_action_menu.php` (ligne 4)

---

### 17. `delete_technician()` → `deleteTechnician()`

**Nombre total d'occurrences : 15** (définition + appels + routes + lang)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 550)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 594)

- **Routes/URLs** :
  - `orif/helpdesk/Views/dashboard.php` (lignes 51, 52, 56, 61, 62, 66)
  - `orif/helpdesk/Views/update_planning.php` (lignes 58, 80)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/Buttons.php` (lignes 48, 49)
  - `orif/helpdesk/Language/en/Buttons.php` (lignes 48, 49)

---

### 18. `delete_planning()` → `deletePlanning()`

**Nombre total d'occurrences : 6** (définition + appels + routes + lang)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 612)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 649)

- **Routes/URLs** :
  - `orif/helpdesk/Views/Common/planning_form_action_menu.php` (ligne 21)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/Buttons.php` (ligne 19)
  - `orif/helpdesk/Language/en/Buttons.php` (ligne 19)

---

### 19. `shift_weeks()` → `shiftWeeks()`

**Nombre total d'occurrences : 18** (définition + appels + routes + lang)

#### Localisation

- **Définition** :
  - `orif/helpdesk/Controllers/Planning.php` (ligne 669)

- **Appels dans le contrôleur** :
  - `orif/helpdesk/Controllers/Planning.php` (lignes 702, 706)

- **Routes/URLs** :
  - `orif/helpdesk/Views/nw_planning.php` (ligne 20)
  - `orif/helpdesk/Controllers/Home.php` (lignes 382, 388, 390, 396, 398)

- **Fichiers de langue** :
  - `orif/helpdesk/Language/fr/Success.php` (lignes 28, 29)
  - `orif/helpdesk/Language/fr/Buttons.php` (lignes 42, 43)
  - `orif/helpdesk/Language/en/Success.php` (lignes 28, 29)
  - `orif/helpdesk/Language/en/Buttons.php` (lignes 42, 43)

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
