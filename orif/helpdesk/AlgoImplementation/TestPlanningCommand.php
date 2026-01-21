<?php

/**
 * Commande Spark pour tester la génération de planning
 * 
 * Utilisation: php spark test:planning
 */

namespace Helpdesk\AlgoImplementation;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Helpdesk\AlgoImplementation\PlanningGenerator;

class TestPlanningCommand extends BaseCommand
{
    protected $group       = 'Test';
    protected $name        = 'test:planning';
    protected $description = 'Teste l\'algorithme de génération de planning avec les données existantes';

    public function run(array $params)
    {
        CLI::write('========================================', 'white');
        CLI::write('TEST DE GÉNÉRATION DE PLANNING', 'white');
        CLI::write('========================================', 'white');
        CLI::newLine();

        try {
            // Créer une instance du générateur
            $generator = new PlanningGenerator();
            
            CLI::write('✓ Générateur de planning initialisé', 'green');
            CLI::newLine();
            
            // Test 1: Obtenir les périodes de la semaine prochaine
            CLI::write('--- TEST 1: Récupération des périodes ---', 'yellow');
            $periods = $generator->getNextWeekPeriodsOn();
            CLI::write('Nombre de périodes récupérées: ' . count($periods), 'white');
            
            if (count($periods) > 0) {
                $first_period = array_key_first($periods);
                CLI::write('Exemple de période:', 'white');
                CLI::write('  - ' . $first_period . ': ' . date('Y-m-d H:i', $periods[$first_period]['start']) . 
                         ' -> ' . date('Y-m-d H:i', $periods[$first_period]['end']), 'white');
            }
            CLI::newLine();
            
            // Test 2: Obtenir les utilisateurs avec leurs rôles
            CLI::write('--- TEST 2: Récupération des utilisateurs ---', 'yellow');
            $users = $generator->getUsersPresentNextWeekWithThereRoles();
            CLI::write('Nombre d\'utilisateurs récupérés: ' . count($users), 'white');
            
            if (count($users) > 0) {
                CLI::write('Exemples d\'utilisateurs:', 'white');
                $count = 0;
                foreach ($users as $user_id => $user) {
                    if ($count++ >= 3) break;
                    CLI::write('  - User ID: ' . $user_id, 'white');
                    CLI::write('    Priorité de rôle: ' . $user['role_priority'], 'white');
                    CLI::write('    Tech 1 - Min: ' . $user['technician_1_assignation']['min'] . 
                             ', Max: ' . $user['technician_1_assignation']['max'], 'white');
                }
            }
            CLI::newLine();
            
            // Test 3: Obtenir les présences par période
            CLI::write('--- TEST 3: Récupération des présences ---', 'yellow');
            $presences = $generator->getUsersPresencesPerPeriods($periods, $users);
            CLI::write('Nombre de présences traitées: ' . count($presences), 'white');
            
            if (count($presences) > 0) {
                CLI::write('Exemples de présences:', 'white');
                $count = 0;
                foreach ($presences as $period_name => $presence) {
                    if ($count++ >= 3) break;
                    $available_count = count($presence['all_available_technicians']);
                    CLI::write('  - Période: ' . $period_name, 'white');
                    CLI::write('    Techniciens disponibles: ' . $available_count, 'white');
                }
            }
            CLI::newLine();
            
            // Test 4: Vérifier si le planning peut être copié
            CLI::write('--- TEST 4: Vérification de copie du planning ---', 'yellow');
            $canCopy = $generator->canPlanningBeCopiedFromCurrentWeek();
            CLI::write('Le planning peut être copié: ' . ($canCopy ? 'Oui' : 'Non'), 'white');
            CLI::newLine();
            
            // Test 5: Génération complète du planning
            CLI::write('--- TEST 5: Génération complète du planning ---', 'yellow');
            CLI::write('Génération en cours...', 'white');
            
            $generated_periods = $generator->generateNextWeekPlanning();
            
            CLI::write('✓ Planning généré avec succès!', 'green');
            CLI::newLine();
            
            // Afficher les résultats
            CLI::write('--- RÉSULTATS DE LA GÉNÉRATION ---', 'yellow');
            CLI::write('Nombre total de périodes: ' . count($generated_periods), 'white');
            CLI::newLine();
            
            $periods_with_assignments = 0;
            $total_assignments = 0;
            
            foreach ($generated_periods as $period_name => $period) {
                $has_assignment = false;
                $assignments = [];
                
                if (isset($period['first_technician'])) {
                    $has_assignment = true;
                    $assignments[] = 'Tech1: ' . $period['first_technician'];
                    $total_assignments++;
                }
                if (isset($period['second_technician'])) {
                    $has_assignment = true;
                    $assignments[] = 'Tech2: ' . $period['second_technician'];
                    $total_assignments++;
                }
                if (isset($period['third_technician'])) {
                    $has_assignment = true;
                    $assignments[] = 'Tech3: ' . $period['third_technician'];
                    $total_assignments++;
                }
                
                if ($has_assignment) {
                    $periods_with_assignments++;
                    CLI::write('Période: ' . $period_name, 'cyan');
                    CLI::write('  ' . date('Y-m-d H:i', $period['start']) . ' -> ' . date('Y-m-d H:i', $period['end']), 'white');
                    CLI::write('  Assignations: ' . implode(', ', $assignments), 'white');
                }
            }
            
            CLI::newLine();
            CLI::write('--- STATISTIQUES ---', 'yellow');
            CLI::write('Périodes avec assignations: ' . $periods_with_assignments . ' / ' . count($generated_periods), 'white');
            CLI::write('Total d\'assignations: ' . $total_assignments, 'white');
            
            // Statistiques par utilisateur
            CLI::newLine();
            CLI::write('--- ASSIGNATIONS PAR UTILISATEUR ---', 'yellow');
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
                CLI::write('User ID ' . $user_id . ': Tech1=' . $stats['tech1'] . 
                         ', Tech2=' . $stats['tech2'] . 
                         ', Tech3=' . $stats['tech3'] . 
                         ' (Total: ' . $total . ')', 'white');
            }
            
            CLI::newLine();
            CLI::write('========================================', 'white');
            CLI::write('TEST TERMINÉ AVEC SUCCÈS!', 'green');
            CLI::write('========================================', 'white');
            
        } catch (\Exception $e) {
            CLI::error('ERREUR LORS DU TEST');
            CLI::error('Message: ' . $e->getMessage());
            CLI::error('Fichier: ' . $e->getFile());
            CLI::error('Ligne: ' . $e->getLine());
            CLI::newLine();
            CLI::write('Trace:', 'red');
            CLI::write($e->getTraceAsString());
            return EXIT_ERROR;
        }
        
        return EXIT_SUCCESS;
    }
}
