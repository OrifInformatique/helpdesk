# Test de Génération de Planning

**Date:** 2026-05-28 15:40:07
**Fichier de sortie:** `algorithm_attempt_20260528_154007.md`

---

✅ **Générateur de planning initialisé**

## TEST 1: Récupération des périodes

- **Nombre de périodes récupérées:** 20
- **Exemple de période:**
  - `mon-m1`: 2026-06-01 08:00 → 2026-06-01 10:00

## TEST 2: Récupération des utilisateurs

- **Nombre d'utilisateurs récupérés:** 15
- **Exemples d'utilisateurs:**
  - **User ID:** 4
    - Priorité de rôle: `1`
    - Tech 1 - Min: `0`, Max: `20`
  - **User ID:** 5
    - Priorité de rôle: `1`
    - Tech 1 - Min: `0`, Max: `20`
  - **User ID:** 6
    - Priorité de rôle: `1`
    - Tech 1 - Min: `0`, Max: `20`

## TEST 3: Récupération des présences

- **Nombre de présences traitées:** 20
- **Exemples de présences:**
  - **Période:** `mon-m1`
    - Techniciens disponibles: `13`
  - **Période:** `mon-m2`
    - Techniciens disponibles: `12`
  - **Période:** `mon-a1`
    - Techniciens disponibles: `8`

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
| **M1 : 8h-10h** | Full Operator<br>Mornings Operator<br>Limited Operator | Full Operator<br>Mornings Operator<br>Flex Operator | Full Operator<br>Mornings Operator<br>Flex Operator | Full Operator<br>Mornings Operator<br>Full Developer | Full Operator<br>Mornings Operator<br>Limited Operator |
| **M2 : 10h-12h** | Full Operator<br>Mornings Operator<br>Flex Operator | Full Operator<br>Mornings Operator<br>Limited Operator | Full Operator<br>Mornings Operator<br>Partial Infrastructure | Full Operator<br>Mornings Operator<br>Flex Operator | Full Operator<br>Mornings Operator<br>Flex Operator |
| **A1 : 12h45-14h45** | Full Operator<br>Afternoons Operator<br>Flex Operator | Full Operator<br>Afternoons Operator<br>Partial Infrastructure | Full Operator<br>Afternoons Operator<br>Limited Operator | Full Operator<br>Afternoons Operator<br>Flex Operator | Full Operator<br>Afternoons Operator<br>Flex Operator |
| **A2 : 15h-16h57** | Full Operator<br>Afternoons Operator<br>Full Infrastructure | Full Operator<br>Afternoons Operator<br>Flex Operator | Full Operator<br>Afternoons Operator<br>Flex Operator | Full Operator<br>Afternoons Operator<br>Limited Operator | Full Operator<br>Afternoons Operator<br>Full Infrastructure |

> **Note:** Les noms sont séparés par des retours à la ligne dans chaque cellule.


## STATISTIQUES

- **Périodes avec assignations:** 20 / 20
- **Total d'assignations:** 60

## ASSIGNATIONS PAR UTILISATEUR

| User ID | Nom | Rôle | Tech1 | Tech2 | Tech3 | Total |
|---------|-----|------|-------|-------|-------|-------|
| 4 | Full Operator | Operator | 20 | 0 | 0 | **20** |
| 5 | Mornings Operator | Operator | 0 | 10 | 0 | **10** |
| 7 | Limited Operator | Operator | 0 | 0 | 5 | **5** |
| 18 | Flex Operator | Operator | 0 | 0 | 10 | **10** |
| 6 | Afternoons Operator | Operator | 0 | 10 | 0 | **10** |
| 8 | Full Infrastructure | Infrastructure | 0 | 0 | 2 | **2** |
| 9 | Partial Infrastructure | Infrastructure | 0 | 0 | 2 | **2** |
| 10 | Full Developer | Developer | 0 | 0 | 1 | **1** |


---

## ✅ TEST TERMINÉ AVEC SUCCÈS!
