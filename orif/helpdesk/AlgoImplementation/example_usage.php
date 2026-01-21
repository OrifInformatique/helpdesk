<?php

/**
 * Exemple d'utilisation de la classe PlanningGenerator
 * 
 * Ce fichier montre comment utiliser l'algorithme de génération de planning
 */

// Charger CodeIgniter (si nécessaire)
// require_once APPPATH . '../vendor/autoload.php';

use Helpdesk\AlgoImplementation\PlanningGenerator;

// Créer une instance du générateur
$generator = new PlanningGenerator();

try {
    // Générer le planning de la semaine prochaine
    $periods = $generator->generateNextWeekPlanning();

    // Afficher les résultats
    echo "=== Planning généré ===\n\n";
    
    foreach ($periods as $period_name => $period) {
        echo "Période: $period_name\n";
        echo "  Début: " . date('Y-m-d H:i:s', $period['start']) . "\n";
        echo "  Fin: " . date('Y-m-d H:i:s', $period['end']) . "\n";
        
        if (isset($period['first_technician'])) {
            echo "  Technicien 1: " . $period['first_technician'] . "\n";
        }
        if (isset($period['second_technician'])) {
            echo "  Technicien 2: " . $period['second_technician'] . "\n";
        }
        if (isset($period['third_technician'])) {
            echo "  Technicien 3: " . $period['third_technician'] . "\n";
        }
        echo "\n";
    }

    // Exemple d'utilisation des méthodes individuelles
    echo "\n=== Utilisation des méthodes individuelles ===\n\n";
    
    // Obtenir les périodes
    $periods_only = $generator->getNextWeekPeriodsOn();
    echo "Nombre de périodes: " . count($periods_only) . "\n\n";
    
    // Obtenir les utilisateurs
    $users = $generator->getUsersPresentNextWeekWithThereRoles();
    echo "Nombre d'utilisateurs: " . count($users) . "\n\n";
    
    // Obtenir les présences
    $presences = $generator->getUsersPresencesPerPeriods($periods_only, $users);
    echo "Nombre de présences traitées: " . count($presences) . "\n\n";
    
    // Vérifier si le planning peut être copié
    $canCopy = $generator->canPlanningBeCopiedFromCurrentWeek();
    echo "Le planning peut être copié: " . ($canCopy ? 'Oui' : 'Non') . "\n";

} catch (\Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
