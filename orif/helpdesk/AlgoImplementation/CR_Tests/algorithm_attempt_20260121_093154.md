# Test de Génération de Planning

**Date:** 2026-01-21 09:31:54
**Fichier de sortie:** `algorithm_attempt_20260121_093154.md`

---

✅ **Générateur de planning initialisé**

## TEST 1: Récupération des périodes

- **Nombre de périodes récupérées:** 20
- **Exemple de période:**
  - `mon-m1`: 2026-01-26 08:00 → 2026-01-26 10:00

## TEST 2: Récupération des utilisateurs

- **Nombre d'utilisateurs récupérés:** 19
- **Exemples d'utilisateurs:**
  - **User ID:** 2
    - Priorité de rôle: `1`
    - Tech 1 - Min: `0`, Max: `20`
  - **User ID:** 12
    - Priorité de rôle: `1`
    - Tech 1 - Min: `0`, Max: `20`
  - **User ID:** 21
    - Priorité de rôle: `1`
    - Tech 1 - Min: `0`, Max: `20`

## TEST 3: Récupération des présences

- **Nombre de présences traitées:** 20
- **Exemples de présences:**
  - **Période:** `mon-m1`
    - Techniciens disponibles: `3`
  - **Période:** `mon-m2`
    - Techniciens disponibles: `13`
  - **Période:** `mon-a1`
    - Techniciens disponibles: `13`

## TEST 4: Vérification de copie du planning

- **Le planning peut être copié:** ❌ Non

## TEST 5: Génération complète du planning

Génération en cours...
✅ **Planning généré avec succès!**

## RÉSULTATS DE LA GÉNÉRATION

- **Nombre total de périodes:** 20

### Planning de la semaine

| | Lundi | Mardi | Mercredi | Jeudi | Vendredi |
|---|---|---|---|---|---|
| **M1 : 8h-10h** | User B_Present<br>Alpha Realistic<br>User B_Partially_Absent | User B_Present<br>Alpha Realistic<br>User B_Partially_Absent | Charlie Realistic<br>User B_Present<br>Alpha Realistic | Charlie Realistic<br>Lima Realistic<br>Echo Realistic | User A_Operator<br>Lima Realistic<br>User A_Developer |
| **M2 : 10h-12h** | Golf Realistic<br>Kilo Realistic<br>User A_Operator | User A_Operator<br>Lima Realistic<br>User A_Developer | Delta Realistic<br>Golf Realistic<br>Lima Realistic | Lima Realistic<br>Delta Realistic<br>User B_Present | User A_Operator<br>Lima Realistic<br>User A_Infrastructure |
| **A1 : 12h45-14h45** | User A_Operator<br>User A_Infrastructure<br>Delta Realistic | User A_Operator<br>Kilo Realistic<br>Lima Realistic | Kilo Realistic<br>Juliet Realistic<br>Lima Realistic | Kilo Realistic<br>India Realistic<br>User B_Present | User A_Operator<br>User A_Developer<br>Kilo Realistic |
| **A2 : 15h-16h57** | User A_Operator<br>User A_Infrastructure<br>Delta Realistic | User A_Operator<br>User A_Developer<br>User A_Infrastructure | Echo Realistic<br>User B_Present<br>Golf Realistic | Echo Realistic<br>Foxtrot Realistic<br>Lima Realistic | User A_Operator<br>Delta Realistic<br>Kilo Realistic |

> **Note:** Les noms sont séparés par des retours à la ligne dans chaque cellule.

## STATISTIQUES

- **Périodes avec assignations:** 20 / 20
- **Total d'assignations:** 60

## ASSIGNATIONS PAR UTILISATEUR

| User ID | Nom | Rôle | Tech1 | Tech2 | Tech3 | Total |
|---------|-----|------|-------|-------|-------|-------|
| 2 | User A_Operator | Operator | 9 | 0 | 1 | **10** |
| 3 | User A_Infrastructure | Infrastructure | 0 | 2 | 2 | **4** |
| 4 | User A_Developer | Developer | 0 | 2 | 2 | **4** |
| 7 | User B_Present | Infrastructure | 2 | 2 | 2 | **6** |
| 8 | User B_Partially_Absent | Developer | 0 | 0 | 2 | **2** |
| 10 | Alpha Realistic | Observation | 0 | 2 | 1 | **3** |
| 12 | Charlie Realistic | Operator | 2 | 0 | 0 | **2** |
| 13 | Delta Realistic | Infrastructure | 1 | 2 | 2 | **5** |
| 14 | Echo Realistic | Developer | 2 | 0 | 1 | **3** |
| 15 | Foxtrot Realistic | Observation | 0 | 1 | 0 | **1** |
| 16 | Golf Realistic | Pré-apprentissage | 1 | 1 | 1 | **3** |
| 18 | India Realistic | Observation | 0 | 1 | 0 | **1** |
| 19 | Juliet Realistic | Developer | 0 | 1 | 0 | **1** |
| 20 | Kilo Realistic | Infrastructure | 2 | 2 | 2 | **6** |
| 21 | Lima Realistic | Operator | 1 | 4 | 4 | **9** |

---

## ✅ TEST TERMINÉ AVEC SUCCÈS!
