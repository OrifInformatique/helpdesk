# Test de Génération de Planning

**Date:** 2026-01-21 09:19:41
**Fichier de sortie:** `algorithm_attempt_20260121_091941.md`

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
  - **User ID:** 7
    - Priorité de rôle: `1`
    - Tech 1 - Min: `0`, Max: `20`
  - **User ID:** 12
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
| **M1 : 8h-10h** | User B_Present<br>Alpha Realistic<br>User B_Partially_Absent | User B_Present<br>Alpha Realistic<br>User B_Partially_Absent | User B_Present<br>Charlie Realistic<br>Alpha Realistic | User B_Present<br>Charlie Realistic<br>Lima Realistic | User A_Operator<br>User B_Present<br>Lima Realistic |
| **M2 : 10h-12h** | User B_Present<br>India Realistic<br>User A_Operator | User A_Operator<br>User B_Present<br>Lima Realistic | User B_Present<br>Delta Realistic<br>Lima Realistic | User B_Present<br>Lima Realistic<br>Delta Realistic | User A_Operator<br>User B_Present<br>Lima Realistic |
| **A1 : 12h45-14h45** | User A_Operator<br>User B_Present<br>User A_Infrastructure | User A_Operator<br>User B_Present<br>Lima Realistic | User B_Present<br>Kilo Realistic<br>Lima Realistic | User B_Present<br>Kilo Realistic<br>Juliet Realistic | User A_Operator<br>User B_Present<br>Kilo Realistic |
| **A2 : 15h-16h57** | User A_Operator<br>User B_Present<br>User A_Infrastructure | User A_Operator<br>User B_Present<br>Kilo Realistic | User B_Present<br>Echo Realistic<br>Golf Realistic | User B_Present<br>Echo Realistic<br>Lima Realistic | User A_Operator<br>User B_Present<br>Delta Realistic |

> **Note:** Les noms sont séparés par des retours à la ligne dans chaque cellule.

## STATISTIQUES

- **Périodes avec assignations:** 20 / 20
- **Total d'assignations:** 60

## ASSIGNATIONS PAR UTILISATEUR

| User ID | Nom | Rôle | Tech1 | Tech2 | Tech3 | Total |
|---------|-----|------|-------|-------|-------|-------|
| 2 | User A_Operator | Operator | 9 | 0 | 1 | **10** |
| 3 | User A_Infrastructure | Infrastructure | 0 | 0 | 2 | **2** |
| 7 | User B_Present | Operator | 11 | 9 | 0 | **20** |
| 8 | User B_Partially_Absent | Infrastructure | 0 | 0 | 2 | **2** |
| 10 | Alpha Realistic | Observation | 0 | 2 | 1 | **3** |
| 12 | Charlie Realistic | Operator | 0 | 2 | 0 | **2** |
| 13 | Delta Realistic | Infrastructure | 0 | 1 | 2 | **3** |
| 14 | Echo Realistic | Developer | 0 | 2 | 0 | **2** |
| 16 | Golf Realistic | Pré-apprentissage | 0 | 0 | 1 | **1** |
| 18 | India Realistic | Observation | 0 | 1 | 0 | **1** |
| 19 | Juliet Realistic | Developer | 0 | 0 | 1 | **1** |
| 20 | Kilo Realistic | Infrastructure | 0 | 2 | 2 | **4** |
| 21 | Lima Realistic | Operator | 0 | 1 | 8 | **9** |

---

## ✅ TEST TERMINÉ AVEC SUCCÈS!
