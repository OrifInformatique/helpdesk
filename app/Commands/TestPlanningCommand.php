<?php

/**
 * Commande Spark pour tester la génération de planning
 * 
 * Utilisation: php spark test:planning
 */

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Helpdesk\AlgoImplementation\PlanningGenerator;
use Helpdesk\Models\User_data_model;

class TestPlanningCommand extends BaseCommand
{
    protected $group       = 'Test';
    protected $name        = 'test:planning';
    protected $description = 'Teste l\'algorithme de génération de planning avec les données existantes';

    public function run(array $params)
    {
        // Créer le nom du fichier avec timestamp
        $timestamp = date('Ymd_His');
        $filename = WRITEPATH . 'algorithm_attempt_' . $timestamp . '.md';
        
        // Tableau pour capturer les sorties Markdown
        $markdown = [];
        
        // Fonction pour écrire à la fois dans CLI et dans le tableau Markdown
        $writeBoth = function($text, $color = 'white', $markdown_format = null) use (&$markdown) {
            CLI::write($text, $color);
            // Si un format Markdown spécifique est fourni, l'utiliser, sinon utiliser le texte tel quel
            $markdown[] = $markdown_format !== null ? $markdown_format : $text;
        };
        
        // Fonction pour les nouvelles lignes
        $newLine = function() use (&$markdown) {
            CLI::newLine();
            $markdown[] = '';
        };
        
        // Fonction pour les erreurs
        $writeError = function($text) use (&$markdown) {
            CLI::error($text);
            $markdown[] = '**❌ ERREUR:** ' . $text;
        };

        // En-tête Markdown
        $markdown[] = '# Test de Génération de Planning';
        $markdown[] = '';
        $markdown[] = '**Date:** ' . date('Y-m-d H:i:s');
        $markdown[] = '**Fichier de sortie:** `' . basename($filename) . '`';
        $markdown[] = '';
        $markdown[] = '---';
        $markdown[] = '';
        
        // Afficher aussi dans CLI
        CLI::write('========================================', 'white');
        CLI::write('TEST DE GÉNÉRATION DE PLANNING', 'white');
        CLI::write('Date: ' . date('Y-m-d H:i:s'), 'white');
        CLI::write('Fichier de sortie: ' . basename($filename), 'white');
        CLI::write('========================================', 'white');
        CLI::newLine();

        try {
            // Créer une instance du générateur
            $generator = new PlanningGenerator();
            
            $writeBoth('✓ Générateur de planning initialisé', 'green', '✅ **Générateur de planning initialisé**');
            $newLine();
            
            // Test 1: Obtenir les périodes de la semaine prochaine
            $markdown[] = '## TEST 1: Récupération des périodes';
            $markdown[] = '';
            CLI::write('--- TEST 1: Récupération des périodes ---', 'yellow');
            $periods = $generator->getNextWeekPeriodsOn();
            $writeBoth('Nombre de périodes récupérées: ' . count($periods), 'white', '- **Nombre de périodes récupérées:** ' . count($periods));
            
            if (count($periods) > 0) {
                $first_period = array_key_first($periods);
                CLI::write('Exemple de période:', 'white');
                $markdown[] = '- **Exemple de période:**';
                $period_info = '  - ' . $first_period . ': ' . date('Y-m-d H:i', $periods[$first_period]['start']) . 
                         ' -> ' . date('Y-m-d H:i', $periods[$first_period]['end']);
                CLI::write($period_info, 'white');
                $markdown[] = '  - `' . $first_period . '`: ' . date('Y-m-d H:i', $periods[$first_period]['start']) . 
                         ' → ' . date('Y-m-d H:i', $periods[$first_period]['end']);
            }
            $newLine();
            
            // Test 2: Obtenir les utilisateurs avec leurs rôles
            $markdown[] = '## TEST 2: Récupération des utilisateurs';
            $markdown[] = '';
            CLI::write('--- TEST 2: Récupération des utilisateurs ---', 'yellow');
            $users = $generator->getUsersPresentNextWeekWithThereRoles();
            $writeBoth('Nombre d\'utilisateurs récupérés: ' . count($users), 'white', '- **Nombre d\'utilisateurs récupérés:** ' . count($users));
            
            if (count($users) > 0) {
                CLI::write('Exemples d\'utilisateurs:', 'white');
                $markdown[] = '- **Exemples d\'utilisateurs:**';
                $count = 0;
                foreach ($users as $user_id => $user) {
                    if ($count++ >= 3) break;
                    CLI::write('  - User ID: ' . $user_id, 'white');
                    CLI::write('    Priorité de rôle: ' . $user['role_priority'], 'white');
                    CLI::write('    Tech 1 - Min: ' . $user['technician_1_assignation']['min'] . 
                             ', Max: ' . $user['technician_1_assignation']['max'], 'white');
                    $markdown[] = '  - **User ID:** ' . $user_id;
                    $markdown[] = '    - Priorité de rôle: `' . $user['role_priority'] . '`';
                    $markdown[] = '    - Tech 1 - Min: `' . $user['technician_1_assignation']['min'] . 
                             '`, Max: `' . $user['technician_1_assignation']['max'] . '`';
                }
            }
            $newLine();
            
            // Test 3: Obtenir les présences par période
            $markdown[] = '## TEST 3: Récupération des présences';
            $markdown[] = '';
            CLI::write('--- TEST 3: Récupération des présences ---', 'yellow');
            $presences = $generator->getUsersPresencesPerPeriods($periods, $users);
            $writeBoth('Nombre de présences traitées: ' . count($presences), 'white', '- **Nombre de présences traitées:** ' . count($presences));
            
            if (count($presences) > 0) {
                CLI::write('Exemples de présences:', 'white');
                $markdown[] = '- **Exemples de présences:**';
                $count = 0;
                foreach ($presences as $period_name => $presence) {
                    if ($count++ >= 3) break;
                    $available_count = count($presence['all_available_technicians']);
                    CLI::write('  - Période: ' . $period_name, 'white');
                    CLI::write('    Techniciens disponibles: ' . $available_count, 'white');
                    $markdown[] = '  - **Période:** `' . $period_name . '`';
                    $markdown[] = '    - Techniciens disponibles: `' . $available_count . '`';
                }
            }
            $newLine();
            
            // Test 4: Vérifier si le planning peut être copié
            $markdown[] = '## TEST 4: Vérification de copie du planning';
            $markdown[] = '';
            CLI::write('--- TEST 4: Vérification de copie du planning ---', 'yellow');
            $canCopy = $generator->canPlanningBeCopiedFromCurrentWeek();
            $writeBoth('Le planning peut être copié: ' . ($canCopy ? 'Oui' : 'Non'), 'white', 
                      '- **Le planning peut être copié:** ' . ($canCopy ? '✅ Oui' : '❌ Non'));
            $newLine();
            
            // Test 5: Génération complète du planning
            $markdown[] = '## TEST 5: Génération complète du planning';
            $markdown[] = '';
            CLI::write('--- TEST 5: Génération complète du planning ---', 'yellow');
            CLI::write('Génération en cours...', 'white');
            $markdown[] = 'Génération en cours...';
            
            $generated_periods = $generator->generateNextWeekPlanning();
            
            $writeBoth('✓ Planning généré avec succès!', 'green', '✅ **Planning généré avec succès!**');
            $newLine();
            
            // Debug: Vérifier la structure des données (à retirer après debug)
            $debug_count = 0;
            foreach ($generated_periods as $period_name => $period) {
                if (isset($period['first_technician']) || isset($period['second_technician']) || isset($period['third_technician'])) {
                    $debug_count++;
                }
            }
            CLI::write('DEBUG: Périodes avec assignations détectées: ' . $debug_count, 'yellow');
            
            // Afficher les résultats
            $markdown[] = '## RÉSULTATS DE LA GÉNÉRATION';
            $markdown[] = '';
            CLI::write('--- RÉSULTATS DE LA GÉNÉRATION ---', 'yellow');
            $writeBoth('Nombre total de périodes: ' . count($generated_periods), 'white', '- **Nombre total de périodes:** ' . count($generated_periods));
            $newLine();
            
            $periods_with_assignments = 0;
            $total_assignments = 0;
            
            // Récupérer les noms et rôles des utilisateurs pour l'affichage
            $user_data_model = new User_data_model();
            $user_names = [];
            $user_roles = [];
            $user_ids_to_fetch = [];
            
            foreach ($generated_periods as $period_name => $period) {
                if (isset($period['first_technician'])) {
                    $user_ids_to_fetch[] = $period['first_technician'];
                }
                if (isset($period['second_technician'])) {
                    $user_ids_to_fetch[] = $period['second_technician'];
                }
                if (isset($period['third_technician'])) {
                    $user_ids_to_fetch[] = $period['third_technician'];
                }
            }
            
            $user_ids_to_fetch = array_unique($user_ids_to_fetch);
            
            if (!empty($user_ids_to_fetch)) {
                $db = \Config\Database::connect();
                $users_data = $db->table('tbl_user_data')
                    ->select('tbl_user_data.fk_user_id, first_name_user_data, last_name_user_data, tbl_roles.name_role')
                    ->join('tbl_roles', 'tbl_roles.id_role = tbl_user_data.fk_role_id', 'left')
                    ->whereIn('tbl_user_data.fk_user_id', $user_ids_to_fetch)
                    ->get()
                    ->getResultArray();
                
                foreach ($users_data as $user_data) {
                    $full_name = trim(($user_data['first_name_user_data'] ?? '') . ' ' . ($user_data['last_name_user_data'] ?? ''));
                    $user_names[$user_data['fk_user_id']] = $full_name ?: 'Nom inconnu';
                    $user_roles[$user_data['fk_user_id']] = $user_data['name_role'] ?? 'Aucun rôle';
                }
            }
            
            // Fonction helper pour formater l'affichage avec le nom
            $formatUser = function($user_id) use ($user_names) {
                $name = $user_names[$user_id] ?? 'Nom inconnu';
                return $user_id . ' (' . $name . ')';
            };
            
            // Fonction pour obtenir juste le nom (sans ID)
            $getUserName = function($user_id) use ($user_names) {
                return $user_names[$user_id] ?? 'Nom inconnu';
            };
            
            // Organiser les périodes par jour et type
            $periods_by_day = [
                'mon' => ['m1' => null, 'm2' => null, 'a1' => null, 'a2' => null],
                'tue' => ['m1' => null, 'm2' => null, 'a1' => null, 'a2' => null],
                'wed' => ['m1' => null, 'm2' => null, 'a1' => null, 'a2' => null],
                'thu' => ['m1' => null, 'm2' => null, 'a1' => null, 'a2' => null],
                'fri' => ['m1' => null, 'm2' => null, 'a1' => null, 'a2' => null],
            ];
            
            // Mapper les noms de périodes vers les heures
            $period_times = [
                'm1' => '8h-10h',
                'm2' => '10h-12h',
                'a1' => '12h45-14h45',
                'a2' => '15h-16h57',
            ];
            
            foreach ($generated_periods as $period_name => $period) {
                // Extraire le jour et le type de période (ex: "mon-m1" -> "mon" et "m1")
                $parts = explode('-', $period_name);
                if (count($parts) === 2) {
                    $day = $parts[0];
                    $period_type = $parts[1];
                    
                    // Vérifier que cette période existe dans notre structure
                    if (isset($periods_by_day[$day]) && array_key_exists($period_type, $periods_by_day[$day])) {
                        $technicians = [];
                        
                        // Vérifier et ajouter les techniciens assignés (vérifier avec isset ET !empty)
                        if (isset($period['first_technician']) && $period['first_technician'] !== null && $period['first_technician'] !== '') {
                            $technicians[] = $getUserName($period['first_technician']);
                            $total_assignments++;
                        }
                        if (isset($period['second_technician']) && $period['second_technician'] !== null && $period['second_technician'] !== '') {
                            $technicians[] = $getUserName($period['second_technician']);
                            $total_assignments++;
                        }
                        if (isset($period['third_technician']) && $period['third_technician'] !== null && $period['third_technician'] !== '') {
                            $technicians[] = $getUserName($period['third_technician']);
                            $total_assignments++;
                        }
                        
                        // Toujours stocker, même si vide (pour afficher "-")
                        $periods_by_day[$day][$period_type] = !empty($technicians) ? $technicians : null;
                        
                        if (!empty($technicians)) {
                            $periods_with_assignments++;
                            
                            // Afficher aussi dans le CLI
                            CLI::write('Période: ' . $period_name, 'cyan');
                            CLI::write('  ' . date('Y-m-d H:i', $period['start']) . ' -> ' . date('Y-m-d H:i', $period['end']), 'white');
                            CLI::write('  Assignations: ' . implode(', ', array_map($formatUser, array_filter([
                                $period['first_technician'] ?? null,
                                $period['second_technician'] ?? null,
                                $period['third_technician'] ?? null
                            ]))), 'white');
                        }
                    }
                }
            }
            
            // Créer le tableau Markdown
            $markdown[] = '### Planning de la semaine';
            $markdown[] = '';
            $markdown[] = '| | Lundi | Mardi | Mercredi | Jeudi | Vendredi |';
            $markdown[] = '|---|---|---|---|---|---|';
            
            // Pour chaque type de période
            foreach (['m1', 'm2', 'a1', 'a2'] as $period_type) {
                $time_label = ucfirst($period_type) . ' : ' . $period_times[$period_type];
                $row = ['**' . $time_label . '**'];
                
                foreach (['mon', 'tue', 'wed', 'thu', 'fri'] as $day) {
                    $technicians = $periods_by_day[$day][$period_type];
                    if ($technicians !== null && !empty($technicians)) {
                        // Utiliser <br> pour les retours à la ligne dans Markdown (format HTML dans tableaux)
                        $row[] = implode('<br>', $technicians);
                    } else {
                        $row[] = '-';
                    }
                }
                
                $markdown[] = '| ' . implode(' | ', $row) . ' |';
            }
            
            $markdown[] = '';
            $markdown[] = '> **Note:** Les noms sont séparés par des retours à la ligne dans chaque cellule.';
            $markdown[] = '';
            
            $newLine();
            $markdown[] = '## STATISTIQUES';
            $markdown[] = '';
            CLI::write('--- STATISTIQUES ---', 'yellow');
            $writeBoth('Périodes avec assignations: ' . $periods_with_assignments . ' / ' . count($generated_periods), 'white',
                      '- **Périodes avec assignations:** ' . $periods_with_assignments . ' / ' . count($generated_periods));
            $writeBoth('Total d\'assignations: ' . $total_assignments, 'white',
                      '- **Total d\'assignations:** ' . $total_assignments);
            
            // Statistiques par utilisateur
            $newLine();
            $markdown[] = '## ASSIGNATIONS PAR UTILISATEUR';
            $markdown[] = '';
            CLI::write('--- ASSIGNATIONS PAR UTILISATEUR ---', 'yellow');
            $user_stats = [];
            
            foreach ($generated_periods as $period_name => $period) {
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
            
            // Tableau Markdown pour les statistiques utilisateurs
            $markdown[] = '| User ID | Nom | Rôle | Tech1 | Tech2 | Tech3 | Total |';
            $markdown[] = '|---------|-----|------|-------|-------|-------|-------|';
            
            foreach ($user_stats as $user_id => $stats) {
                $total = $stats['tech1'] + $stats['tech2'] + $stats['tech3'];
                $user_name = $user_names[$user_id] ?? 'Nom inconnu';
                $user_role = $user_roles[$user_id] ?? 'Aucun rôle';
                CLI::write('User ID ' . $user_id . ' (' . $user_name . ') [' . $user_role . ']: Tech1=' . $stats['tech1'] . 
                         ', Tech2=' . $stats['tech2'] . 
                         ', Tech3=' . $stats['tech3'] . 
                         ' (Total: ' . $total . ')', 'white');
                $markdown[] = '| ' . $user_id . ' | ' . $user_name . ' | ' . $user_role . ' | ' . $stats['tech1'] . 
                            ' | ' . $stats['tech2'] . ' | ' . $stats['tech3'] . ' | **' . $total . '** |';
            }
            
            $newLine();
            $markdown[] = '';
            $markdown[] = '---';
            $markdown[] = '';
            $markdown[] = '## ✅ TEST TERMINÉ AVEC SUCCÈS!';
            $markdown[] = '';
            
            CLI::write('========================================', 'white');
            CLI::write('TEST TERMINÉ AVEC SUCCÈS!', 'green');
            CLI::write('========================================', 'white');
            
            // Écrire tout dans le fichier Markdown
            file_put_contents($filename, implode("\n", $markdown));
            CLI::newLine();
            CLI::write('Résultats sauvegardés dans: ' . $filename, 'cyan');
            
        } catch (\Exception $e) {
            $writeError('ERREUR LORS DU TEST');
            $writeError('Message: ' . $e->getMessage());
            $writeError('Fichier: ' . $e->getFile());
            $writeError('Ligne: ' . $e->getLine());
            $newLine();
            CLI::write('Trace:', 'red');
            $markdown[] = '**Trace:**';
            $markdown[] = '';
            $markdown[] = '```';
            $markdown[] = $e->getTraceAsString();
            $markdown[] = '```';
            CLI::write($e->getTraceAsString());
            
            // Écrire l'erreur dans le fichier aussi
            file_put_contents($filename, implode("\n", $markdown));
            
            return EXIT_ERROR;
        }
        
        return EXIT_SUCCESS;
    }
}
