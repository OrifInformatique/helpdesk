<?php

/**
 * Script de test pour la génération de planning
 * 
 * Ce script teste l'algorithme de génération de planning avec les données existantes
 * 
 * Utilisation:
 * - Depuis la racine du projet: php orif/helpdesk/algo_implementation/test_planning_generation.php
 * - Ou via spark: php spark test:planning (si la commande est créée)
 */

// Définir le chemin de base
define('FCPATH', __DIR__ . '/../../../../public/');

// Charger l'environnement CodeIgniter
require_once FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();

// Location of the framework bootstrap file
require rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';

// Load environment settings from .env files
require_once SYSTEMPATH . 'Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv(ROOTPATH))->load();

// Initialize CodeIgniter
$app = Config\Services::codeigniter();
$app->initialize();

use Helpdesk\AlgoImplementation\PlanningGenerator;
use Helpdesk\Enums\TechnicianPresence;
use Helpdesk\Enums\TechnicianAssignment;

echo "========================================\n";
echo "TEST DE GÉNÉRATION DE PLANNING\n";
echo "========================================\n\n";

try {
    // Créer une instance du générateur
    $generator = new PlanningGenerator();
    
    echo "✓ Générateur de planning initialisé\n\n";
    
    // Test 1: Obtenir les périodes de la semaine prochaine
    echo "--- TEST 1: Récupération des périodes ---\n";
    $periods = $generator->getNextWeekPeriodsOn();
    echo "Nombre de périodes récupérées: " . count($periods) . "\n";
    
    if (count($periods) > 0) {
        echo "Exemple de période:\n";
        $first_period = array_key_first($periods);
        echo "  - " . $first_period . ": " . date('Y-m-d H:i', $periods[$first_period]['start']) . 
             " -> " . date('Y-m-d H:i', $periods[$first_period]['end']) . "\n";
    }
    echo "\n";
    
    // Test 2: Obtenir les utilisateurs avec leurs rôles
    echo "--- TEST 2: Récupération des utilisateurs ---\n";
    $users = $generator->getUsersPresentNextWeekWithThereRoles();
    echo "Nombre d'utilisateurs récupérés: " . count($users) . "\n";
    
    if (count($users) > 0) {
        echo "Exemples d'utilisateurs:\n";
        $count = 0;
        foreach ($users as $user_id => $user) {
            if ($count++ >= 3) break;
            echo "  - User ID: $user_id\n";
            echo "    Priorité de rôle: " . $user['role_priority'] . "\n";
            echo "    Tech 1 - Min: " . $user['technician_1_assignation']['min'] . 
                 ", Max: " . $user['technician_1_assignation']['max'] . "\n";
            echo "    Tech 2 - Min: " . $user['technician_2_assignation']['min'] . 
                 ", Max: " . $user['technician_2_assignation']['max'] . "\n";
            echo "    Tech 3 - Min: " . $user['technician_3_assignation']['min'] . 
                 ", Max: " . $user['technician_3_assignation']['max'] . "\n";
        }
    }
    echo "\n";
    
    // Test 3: Obtenir les présences par période
    echo "--- TEST 3: Récupération des présences ---\n";
    $presences = $generator->getUsersPresencesPerPeriods($periods, $users);
    echo "Nombre de présences traitées: " . count($presences) . "\n";
    
    if (count($presences) > 0) {
        echo "Exemples de présences:\n";
        $count = 0;
        foreach ($presences as $period_name => $presence) {
            if ($count++ >= 3) break;
            $available_count = count($presence['all_available_technicians']);
            echo "  - Période: $period_name\n";
            echo "    Techniciens disponibles: $available_count\n";
            echo "    Tech 1 disponibles: " . count($presence['available_first_technicians']) . "\n";
            echo "    Tech 2 disponibles: " . count($presence['available_second_technicians']) . "\n";
            echo "    Tech 3 disponibles: " . count($presence['available_third_technicians']) . "\n";
        }
    }
    echo "\n";
    
    // Test 4: Vérifier si le planning peut être copié
    echo "--- TEST 4: Vérification de copie du planning ---\n";
    $canCopy = $generator->canPlanningBeCopiedFromCurrentWeek();
    echo "Le planning peut être copié: " . ($canCopy ? 'Oui' : 'Non') . "\n\n";
    
    // Test 5: Génération complète du planning
    echo "--- TEST 5: Génération complète du planning ---\n";
    echo "Génération en cours...\n";
    
    $generated_periods = $generator->generateNextWeekPlanning();
    
    echo "✓ Planning généré avec succès!\n\n";
    
    // Afficher les résultats
    echo "--- RÉSULTATS DE LA GÉNÉRATION ---\n";
    echo "Nombre total de périodes: " . count($generated_periods) . "\n\n";
    
    $periods_with_assignments = 0;
    $total_assignments = 0;
    
    foreach ($generated_periods as $period_name => $period) {
        $has_assignment = false;
        $assignments = [];
        
        if (isset($period['first_technician'])) {
            $has_assignment = true;
            $assignments[] = "Tech1: " . $period['first_technician'];
            $total_assignments++;
        }
        if (isset($period['second_technician'])) {
            $has_assignment = true;
            $assignments[] = "Tech2: " . $period['second_technician'];
            $total_assignments++;
        }
        if (isset($period['third_technician'])) {
            $has_assignment = true;
            $assignments[] = "Tech3: " . $period['third_technician'];
            $total_assignments++;
        }
        
        if ($has_assignment) {
            $periods_with_assignments++;
            echo "Période: $period_name\n";
            echo "  " . date('Y-m-d H:i', $period['start']) . " -> " . date('Y-m-d H:i', $period['end']) . "\n";
            echo "  Assignations: " . implode(", ", $assignments) . "\n";
        }
    }
    
    echo "\n--- STATISTIQUES ---\n";
    echo "Périodes avec assignations: $periods_with_assignments / " . count($generated_periods) . "\n";
    echo "Total d'assignations: $total_assignments\n";
    
    // Statistiques par utilisateur
    echo "\n--- ASSIGNATIONS PAR UTILISATEUR ---\n";
    $user_stats = [];
    
    foreach ($generated_periods as $period) {
        if (isset($period['first_technician'])) {
            $user_id = $period['first_technician'];
            if (!isset($user_stats[$user_id])) {
                $user_stats[$user_id] = ['tech1' => 0, 'tech2' => 0, 'tech3' => 0];
            }
            $user_stats[$user_id]['tech1']++;
        }
        if (isset($period['second_technician'])) {
            $user_id = $period['second_technician'];
            if (!isset($user_stats[$user_id])) {
                $user_stats[$user_id] = ['tech1' => 0, 'tech2' => 0, 'tech3' => 0];
            }
            $user_stats[$user_id]['tech2']++;
        }
        if (isset($period['third_technician'])) {
            $user_id = $period['third_technician'];
            if (!isset($user_stats[$user_id])) {
                $user_stats[$user_id] = ['tech1' => 0, 'tech2' => 0, 'tech3' => 0];
            }
            $user_stats[$user_id]['tech3']++;
        }
    }
    
    foreach ($user_stats as $user_id => $stats) {
        $total = $stats['tech1'] + $stats['tech2'] + $stats['tech3'];
        echo "User ID $user_id: Tech1=" . $stats['tech1'] . 
             ", Tech2=" . $stats['tech2'] . 
             ", Tech3=" . $stats['tech3'] . 
             " (Total: $total)\n";
    }
    
    echo "\n========================================\n";
    echo "TEST TERMINÉ AVEC SUCCÈS!\n";
    echo "========================================\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERREUR LORS DU TEST\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
