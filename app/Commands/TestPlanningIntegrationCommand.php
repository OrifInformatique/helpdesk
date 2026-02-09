<?php

/**
 * Spark command to test planning generation over multiple weeks
 * with realistic chronological simulation
 * 
 * Usage: php spark test:planning:integration
 * 
 * This command simulates multiple consecutive weeks of planning generation:
 * - Simulates time passage (week 1, week 2, etc.)
 * - Progressively modifies presences from week to week
 * - Saves generated planning for each week
 * - Simulates week shift (lw -> cw -> nw)
 * - Generates a report after each week
 * - Creates a final summary table
 */

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Helpdesk\AlgoImplementation\PlanningGenerator;
use Helpdesk\Models\User_data_model;
use Helpdesk\Models\Presences_model;
use Helpdesk\Models\Planning_model;
use Helpdesk\Models\Nw_planning_model;
use Helpdesk\Models\Lw_planning_model;
use Helpdesk\Enums\TechnicianAssignment;
use Helpdesk\Models\Holidays_model;
use Helpdesk\Models\Roles_model;

class TestPlanningIntegrationCommand extends BaseCommand
{
    protected $group       = 'Test';
    protected $name        = 'test:planning:integration';
    protected $description = 'Tests planning generation algorithm over multiple weeks with realistic chronological simulation';
    
    /**
     * Stores IDs of presences modified in current week
     * to avoid aging them during time simulation
     */
    private $modifiedPresenceIds = [];
    
    /**
     * Stores IDs of users whose planning has been modified
     * to update their presences as well
     */
    private $modifiedPlanningUserIds = [];
    
    /**
     * Simulated date of current week (format Y-m-d)
     */
    private $currentSimulatedDate = null;
    
    /**
     * Stores original holiday dates to avoid cumulative shifts
     * Format: [id_holiday => ['start' => 'Y-m-d H:i:s', 'end' => 'Y-m-d H:i:s']]
     */
    private $originalHolidayDates = [];
    
    /**
     * Stores IDs of holidays that should be adjusted (dynamically added during test)
     * Format: [id_holiday => true]
     */
    private $adjustableHolidayIds = [];

    /**
     * Test weeks configuration (20 weeks)
     * Each week can:
     * - Use a complete seed (replaces all presences)
     * - Partially modify existing presences
     * - Modify plannings (current week planning)
     * - Add users
     * - Modify user roles
     * - Add holidays
     * - Add vacations (presence modifications)
     * - Keep presences identical
     */
    private $weeksConfiguration = [
        [
            'week' => 1,
            'date' => '2025-08-18', // Week's Monday
            'description' => 'Initial week with MatrixOne seed + planning modification for user 3',
            'action' => 'seed_and_planning', // 'seed', 'modify', 'keep', 'seed_and_planning', 'add_user', 'change_role', 'add_holiday', 'add_vacation'
            'seed' => 'Tests\\Support\\Database\\Seeds\\TestInsertPresencesData', // Use test seed
            'planning_modifications' => [
                [
                    'user_id' => 3,
                    'old_planning' => [1, 1, 1, 2, 1, 1, 2, 1, 3, 3, 3, 3, 1, 1, 1, 2, 1, 1, 2, 2],
                    'new_planning' => [1, 1, 1, 2, 3, 3, 3, 3, 3, 3, 3, 3, 1, 1, 1, 2, 1, 1, 2, 2],
                ],
            ],
        ],
        [
            'week' => 2,
            'date' => '2025-08-25',
            'description' => 'Add new user id=12 with Observation role',
            'action' => 'add_user',
            'new_user' => [
                'user_id' => 12,
                'role_id' => 5, // Observation (check according to your roles)
                'planning' => [1, 1, 1, 1, 3, 1, 2, 1, 3, 1, 1, 1, 1, 1, 1, 2, 1, 1, 3, 3],
                'presences' => [1, 1, 1, 1, 3, 1, 2, 1, 3, 1, 1, 1, 1, 1, 1, 2, 1, 1, 3, 3],
            ],
        ],
        [
            'week' => 3,
            'date' => '2025-09-01',
            'description' => 'No modifications',
            'action' => 'keep',
        ],
        [
            'week' => 4,
            'date' => '2025-09-08',
            'description' => 'No modifications',
            'action' => 'keep',
        ],
        [
            'week' => 5,
            'date' => '2025-09-15',
            'description' => 'No modifications',
            'action' => 'keep',
        ],
        [
            'week' => 6,
            'date' => '2025-09-22',
            'description' => 'Monday holiday',
            'action' => 'add_holiday',
            'holiday' => [
                'name' => 'Holiday - Monday',
                'start_date' => '2025-09-22 00:00:00',
                'end_date' => '2025-09-22 23:59:59',
            ],
        ],
        [
            'week' => 7,
            'date' => '2025-09-29',
            'description' => 'No modifications',
            'action' => 'keep',
        ],
        [
            'week' => 8,
            'date' => '2025-10-06',
            'description' => 'Change role user id=12 → Operator',
            'action' => 'change_role',
            'role_change' => [
                'user_id' => 12,
                'new_role_id' => 2, // Operator (check according to your roles)
            ],
        ],
        [
            'week' => 9,
            'date' => '2025-10-13',
            'description' => 'Modifications planning users 4, 5 et 6',
            'action' => 'planning_modify',
            'planning_modifications' => [
                [
                    'user_id' => 4,
                    'old_planning' => [1, 2, 1, 1, 2, 1, 1, 2, 3, 3, 3, 3, 1, 2, 1, 1, 2, 1, 1, 2],
                    'new_planning' => [3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1, 2, 1, 1, 2, 1, 1, 2],
                ],
                [
                    'user_id' => 5,
                    'old_planning' => [1, 1, 2, 1, 1, 1, 1, 2, 3, 3, 3, 3, 3, 3, 3, 3, 1, 1, 2, 2],
                    'new_planning' => [3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1, 1, 2, 2],
                ],
                [
                    'user_id' => 6,
                    'old_planning' => [2, 1, 1, 1, 1, 2, 1, 1, 3, 3, 3, 3, 3, 3, 3, 3, 1, 2, 1, 2],
                    'new_planning' => [3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1, 2, 1, 2],
                ],
            ],
        ],
        [
            'week' => 10,
            'date' => '2025-10-20',
            'description' => 'No modifications',
            'action' => 'keep',
        ],
        [
            'week' => 11,
            'date' => '2025-10-27',
            'description' => 'Modifications planning users 4, 5 et 6',
            'action' => 'planning_modify',
            'planning_modifications' => [
                [
                    'user_id' => 4,
                    'old_planning' => [3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1, 2, 1, 1, 2, 1, 1, 2],
                    'new_planning' => [1, 2, 1, 1, 3, 3, 3, 3, 3, 3, 3, 3, 1, 2, 1, 1, 2, 1, 1, 2],
                ],
                [
                    'user_id' => 5,
                    'old_planning' => [3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1, 1, 2, 2],
                    'new_planning' => [1, 1, 2, 1, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1, 1, 2, 2],
                ],
                [
                    'user_id' => 6,
                    'old_planning' => [3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1, 2, 1, 2],
                    'new_planning' => [2, 1, 1, 1, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1, 2, 1, 2],
                ],
            ],
        ],
        [
            'week' => 12,
            'date' => '2025-11-03',
            'description' => 'Modifications planning users 4, 5 et 6',
            'action' => 'planning_modify',
            'planning_modifications' => [
                [
                    'user_id' => 4,
                    'old_planning' => [1, 2, 1, 1, 3, 3, 3, 3, 3, 3, 3, 3, 1, 2, 1, 1, 2, 1, 1, 2],
                    'new_planning' => [1, 2, 1, 1, 2, 1, 1, 2, 3, 3, 3, 3, 1, 2, 1, 1, 2, 1, 1, 2],
                ],
                [
                    'user_id' => 5,
                    'old_planning' => [1, 1, 2, 1, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1, 1, 2, 2],
                    'new_planning' => [1, 1, 2, 1, 1, 1, 1, 2, 3, 3, 3, 3, 3, 3, 3, 3, 1, 1, 2, 2],
                ],
                [
                    'user_id' => 6,
                    'old_planning' => [2, 1, 1, 1, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1, 2, 1, 2],
                    'new_planning' => [2, 1, 1, 1, 1, 2, 1, 1, 3, 3, 3, 3, 3, 3, 3, 3, 1, 2, 1, 2],
                ],
            ],
        ],
        [
            'week' => 13,
            'date' => '2025-11-10',
            'description' => 'No modifications',
            'action' => 'keep',
        ],
        [
            'week' => 14,
            'date' => '2025-11-17',
            'description' => 'No modifications',
            'action' => 'keep',
        ],
        [
            'week' => 15,
            'date' => '2025-11-24',
            'description' => 'No modifications',
            'action' => 'keep',
        ],
        [
            'week' => 16,
            'date' => '2025-12-01',
            'description' => 'No modifications',
            'action' => 'keep',
        ],
        [
            'week' => 17,
            'date' => '2025-12-08',
            'description' => 'No modifications',
            'action' => 'keep',
        ],
        [
            'week' => 18,
            'date' => '2025-12-15',
            'description' => 'No modifications',
            'action' => 'keep',
        ],
        [
            'week' => 19,
            'date' => '2025-12-22',
            'description' => 'Vacation starting from tue_a1',
            'action' => 'add_vacation',
            'vacation' => [
                'user_id' => null, // null = all users or specify a user_id
                'start_period' => 'tue-a1', // Start period (Tuesday afternoon 1)
                'end_period' => 'fri-a2', // End period (Friday afternoon 2)
                'presence_value' => 3, // 3 = Absent
            ],
        ],
        [
            'week' => 20,
            'date' => '2025-12-29',
            'description' => 'Vacances 5 jours',
            'action' => 'add_vacation',
            'vacation' => [
                'user_id' => null,
                'start_period' => 'mon-m1',
                'end_period' => 'fri-a2',
                'presence_value' => 3,
            ],
        ],
    ];

    public function run(array $params)
    {
        // Create filename with timestamp
        $timestamp = date('Ymd_His');
        $filename = WRITEPATH . 'integration_test_' . $timestamp . '.md';
        
        // Array to store results for each week
        $allWeeksResults = [];
        
        CLI::write('========================================', 'white');
        CLI::write('INTEGRATION TEST - CHRONOLOGICAL SIMULATION', 'white');
        CLI::write('Date: ' . date('Y-m-d H:i:s'), 'white');
        CLI::write('Number of weeks: ' . count($this->weeksConfiguration), 'white');
        CLI::write('Output file: ' . basename($filename), 'white');
        CLI::write('========================================', 'white');
        CLI::newLine();
        
        $db = \Config\Database::connect();
        $presences_model = new Presences_model();
        $planning_model = new Planning_model();
        $nw_planning_model = new Nw_planning_model();
        $lw_planning_model = new Lw_planning_model();
        
        // Initialize: clear all planning tables
        CLI::write('→ Initialization: cleaning planning tables...', 'cyan');
        $db->table('tbl_planning')->truncate();
        $db->table('tbl_nw_planning')->truncate();
        $db->table('tbl_lw_planning')->truncate();
        $db->table('tbl_presences')->truncate();
        
        // Initialize base holidays from seeder (e.g., Christmas holidays)
        CLI::write('→ Initialization: seeding base holidays...', 'cyan');
        $seeder = \Config\Database::seeder();
        $seeder->call('Tests\\Support\\Database\\Seeds\\TestInsertHolidaysData');
        
        // Store original holiday dates to avoid cumulative shifts
        // This must be called AFTER seeding holidays so seed holidays are not marked as adjustable
        $this->storeOriginalHolidayDates($db);
        
        // Execute each week
        foreach ($this->weeksConfiguration as $weekConfig) {
            $weekNum = $weekConfig['week'];
            $weekDescription = $weekConfig['description'];
            
            CLI::write("", 'white');
            CLI::write("════════════════════════════════════════", 'yellow');
            CLI::write("WEEK $weekNum : $weekDescription", 'yellow');
            CLI::write("════════════════════════════════════════", 'yellow');
            
            try {
                // Reset lists for this week
                $this->modifiedPresenceIds = [];
                $this->modifiedPlanningUserIds = [];
                
                // 1. Store simulated date for this week
                $this->currentSimulatedDate = $weekConfig['date'] ?? date('Y-m-d');
                
                // 2. Simulate week date (for holidays and generation)
                $this->simulateWeekDate($this->currentSimulatedDate);
                
                // 3. Handle actions according to type
                $this->handleWeekActions($weekConfig, $presences_model, $planning_model, $db);
                
                // 3.5. Store original dates for newly added holidays (after addHoliday action)
                // This ensures we capture the original dates before any adjustment
                $this->storeOriginalHolidayDates($db);
                
                // 4. Simulate time passage for presences (except for week 1)
                if ($weekNum > 1) {
                    $this->simulateTimePassage($presences_model, $weekNum);
                }
                
                // 4. Simulate week shift (except for week 1)
                if ($weekNum > 1) {
                    $this->simulateWeekShift($planning_model, $nw_planning_model, $lw_planning_model);
                }
                
                // 5. Adjust holidays to match simulated date
                $this->adjustHolidaysForSimulatedDate($this->currentSimulatedDate, $db);
                
                // 6. Set simulated reference date for models
                Presences_model::setSimulatedReferenceDate($this->currentSimulatedDate);
                
                // 7. Create generator instance
                $generator = new PlanningGenerator();
                
                // 7. Execute tests and generate planning
                $weekResult = $this->executeWeekTests($generator, $weekNum, $weekDescription, $db);
                
                // 8. Save generated planning in nw_planning
                if ($weekResult['test5_success']) {
                    $this->saveGeneratedPlanning($weekResult['generated_periods'], $nw_planning_model);
                    $weekResult['planning_saved'] = true;
                } else {
                    $weekResult['planning_saved'] = false;
                }
                
                // 9. Reset simulated date after use
                Presences_model::setSimulatedReferenceDate(null);
                
                // 10. Restore original holidays if necessary
                // (Holidays are already adjusted for each week, so no need to restore)
                
                // 10. Generate week report
                $weekReportFilename = WRITEPATH . 'week_' . $weekNum . '_report_' . $timestamp . '.md';
                $this->generateWeekReport($weekReportFilename, $weekResult, $weekConfig, $db);
                
                $allWeeksResults[] = $weekResult;
                
                CLI::write("✓ Week $weekNum completed successfully", 'green');
                CLI::write("  Report: " . basename($weekReportFilename), 'cyan');
                
            } catch (\Exception $e) {
                CLI::write("✗ ERROR during week $weekNum: " . $e->getMessage(), 'red');
                CLI::write("  File: " . $e->getFile() . " Line: " . $e->getLine(), 'red');
                
                $allWeeksResults[] = [
                    'week' => $weekNum,
                    'description' => $weekDescription,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        // Generate final summary report
        CLI::write("", 'white');
        CLI::write("════════════════════════════════════════", 'green');
        CLI::write("FINAL SUMMARY REPORT GENERATION", 'green');
        CLI::write("════════════════════════════════════════", 'green');
        $this->generateFinalReport($filename, $allWeeksResults);
        
        CLI::write('========================================', 'white');
        CLI::write('TEST COMPLETED!', 'green');
        CLI::write('Main report: ' . basename($filename), 'cyan');
        CLI::write('Weekly reports in: ' . WRITEPATH, 'cyan');
        CLI::write('========================================', 'white');
        
        return EXIT_SUCCESS;
    }
    
    /**
     * Simulates week date for holiday calculations
     */
    private function simulateWeekDate($date)
    {
        if ($date) {
            // Store simulated date in a static variable or session
            // For now, we use a simple approach with strtotime
            // Note: PlanningGenerator uses 'next monday', so we need to adjust
            // This functionality would require a modification of PlanningGenerator
            // For now, we leave it as is and handle holidays directly
        }
    }
    
    /**
     * Handles all week actions according to type
     */
    private function handleWeekActions($weekConfig, $presences_model, $planning_model, $db)
    {
        $action = $weekConfig['action'];
        
        switch ($action) {
            case 'seed':
                // Replace all presences with a seed
                CLI::write("  → Replacing presences with seed...", 'cyan');
                $db->table('tbl_presences')->truncate();
                $seeder = \Config\Database::seeder();
                $seeder->call($weekConfig['seed']);
                
                // All presences created by seed are considered modified this week
                $allPresences = $presences_model->findAll();
                foreach ($allPresences as $presence) {
                    $presence_array = is_object($presence) ? (array)$presence : $presence;
                    if (isset($presence_array['id_presence'])) {
                        $this->modifiedPresenceIds[] = $presence_array['id_presence'];
                    }
                }
                break;
                
            case 'seed_and_planning':
                // Seed + planning modifications
                CLI::write("  → Replacing presences with seed...", 'cyan');
                $db->table('tbl_presences')->truncate();
                $seeder = \Config\Database::seeder();
                $seeder->call($weekConfig['seed']);
                
                // All presences created by seed are considered modified this week
                $allPresences = $presences_model->findAll();
                foreach ($allPresences as $presence) {
                    $presence_array = is_object($presence) ? (array)$presence : $presence;
                    if (isset($presence_array['id_presence'])) {
                        $this->modifiedPresenceIds[] = $presence_array['id_presence'];
                    }
                }
                
                // Modify plannings
                if (isset($weekConfig['planning_modifications'])) {
                    $this->modifyPlannings($weekConfig['planning_modifications'], $planning_model, $presences_model);
                }
                break;
                
            case 'planning_modify':
                // Modify current week plannings
                CLI::write("  → Modifying plannings...", 'cyan');
                if (isset($weekConfig['planning_modifications'])) {
                    $this->modifyPlannings($weekConfig['planning_modifications'], $planning_model, $presences_model);
                }
                break;
                
            case 'modify':
                // Partially modify existing presences
                CLI::write("  → Partially modifying presences...", 'cyan');
                $modifications = $weekConfig['modifications'];
                
                foreach ($modifications['users_to_modify'] as $index => $user_id) {
                    $presence_record = $presences_model->where('fk_user_id', $user_id)->first();
                    
                    if ($presence_record) {
                        $presence_array = is_object($presence_record) ? (array)$presence_record : $presence_record;
                        $id_presence = $presence_array['id_presence'] ?? null;
                        
                        if ($id_presence) {
                            $update_data = [];
                            foreach ($modifications['periods_to_modify'] as $period_index => $period_field) {
                                if (isset($modifications['new_values'][$period_index])) {
                                    $update_data[$period_field] = $modifications['new_values'][$period_index];
                                }
                            }
                            
                            if (!empty($update_data)) {
                                $presences_model->update($id_presence, $update_data);
                                // Record this presence as modified this week
                                $this->modifiedPresenceIds[] = $id_presence;
                            }
                        }
                    }
                }
                break;
                
            case 'planning_modify':
                // Modify current week plannings
                CLI::write("  → Modifying plannings...", 'cyan');
                if (isset($weekConfig['planning_modifications'])) {
                    $this->modifyPlannings($weekConfig['planning_modifications'], $planning_model);
                }
                break;
                
            case 'add_user':
                // Add a new user with presences and planning
                CLI::write("  → Adding a new user...", 'cyan');
                $this->addUserWithPresencesAndPlanning($weekConfig['new_user'], $presences_model, $planning_model, $db);
                break;
                
            case 'change_role':
                // Change a user's role
                CLI::write("  → Changing role...", 'cyan');
                $this->changeUserRole($weekConfig['role_change'], $db);
                break;
                
            case 'add_holiday':
                // Add a holiday
                CLI::write("  → Adding a holiday...", 'cyan');
                $this->addHoliday($weekConfig['holiday'], $db);
                break;
                
            case 'add_vacation':
                // Add vacations (presence modifications)
                CLI::write("  → Adding vacations...", 'cyan');
                $this->addVacation($weekConfig['vacation'], $presences_model);
                break;
                
            case 'keep':
                // Keep presences identical
                CLI::write("  → Keeping existing presences...", 'cyan');
                // Do nothing, presences remain identical
                break;
        }
    }
    
    /**
     * Modifies current week plannings
     * Also updates presences of concerned users
     */
    private function modifyPlannings($modifications, $planning_model, $presences_model = null)
    {
        // Mapping array indices to database fields
        $period_fields = [
            'planning_mon_m1', 'planning_mon_m2', 'planning_mon_a1', 'planning_mon_a2',
            'planning_tue_m1', 'planning_tue_m2', 'planning_tue_a1', 'planning_tue_a2',
            'planning_wed_m1', 'planning_wed_m2', 'planning_wed_a1', 'planning_wed_a2',
            'planning_thu_m1', 'planning_thu_m2', 'planning_thu_a1', 'planning_thu_a2',
            'planning_fri_m1', 'planning_fri_m2', 'planning_fri_a1', 'planning_fri_a2',
        ];
        
        foreach ($modifications as $mod) {
            $user_id = $mod['user_id'];
            $new_planning = $mod['new_planning'];
            
            // Record this user as having modified planning
            $this->modifiedPlanningUserIds[] = $user_id;
            
            // Get existing planning
            $existing_planning = $planning_model->where('fk_user_id', $user_id)->first();
            
            if ($existing_planning) {
                $planning_array = is_object($existing_planning) ? (array)$existing_planning : $existing_planning;
                $id_planning = $planning_array['id_planning'] ?? null;
                
                if ($id_planning) {
                    $update_data = [];
                    foreach ($new_planning as $index => $value) {
                        if (isset($period_fields[$index])) {
                            $update_data[$period_fields[$index]] = $value;
                        }
                    }
                    
                    if (!empty($update_data)) {
                        $planning_model->update($id_planning, $update_data);
                    }
                }
            } else {
                // Create new planning if it doesn't exist
                $new_planning_data = ['fk_user_id' => $user_id];
                foreach ($new_planning as $index => $value) {
                    if (isset($period_fields[$index])) {
                        $new_planning_data[$period_fields[$index]] = $value;
                    }
                }
                $planning_model->insert($new_planning_data);
            }
            
            // If a presences model is provided, update this user's presences
            // because a planning change means presences should be considered modified
            if ($presences_model) {
                $presence_record = $presences_model->where('fk_user_id', $user_id)->first();
                if ($presence_record) {
                    $presence_array = is_object($presence_record) ? (array)$presence_record : $presence_record;
                    $id_presence = $presence_array['id_presence'] ?? null;
                    if ($id_presence && !in_array($id_presence, $this->modifiedPresenceIds)) {
                        $this->modifiedPresenceIds[] = $id_presence;
                    }
                }
            }
        }
    }
    
    /**
     * Adds a user with presences and planning
     */
    private function addUserWithPresencesAndPlanning($user_data, $presences_model, $planning_model, $db)
    {
        $user_id = $user_data['user_id'];
        $role_id = $user_data['role_id'];
        $planning = $user_data['planning'] ?? null;
        $presences = $user_data['presences'] ?? null;
        
        // Add user to tbl_user_data if necessary
        $user_data_model = new User_data_model();
        $existing_user_data = $user_data_model->where('fk_user_id', $user_id)->first();
        
        if (!$existing_user_data) {
            // Create user_data entry (you will need to adapt according to your structure)
            $db->table('tbl_user_data')->insert([
                'fk_user_id' => $user_id,
                'fk_role_id' => $role_id,
                'last_name_user_data' => 'Test',
                'first_name_user_data' => 'User' . $user_id,
                'initials_user_data' => 'TU' . $user_id,
            ]);
        } else {
            // Update role if necessary
            $user_data_array = is_object($existing_user_data) ? (array)$existing_user_data : $existing_user_data;
            $id_user_data = $user_data_array['id_user_data'] ?? null;
            if ($id_user_data) {
                $user_data_model->update($id_user_data, ['fk_role_id' => $role_id]);
            }
        }
        
        // Add presences
        if ($presences) {
            $presence_fields = [
                'presence_mon_m1', 'presence_mon_m2', 'presence_mon_a1', 'presence_mon_a2',
                'presence_tue_m1', 'presence_tue_m2', 'presence_tue_a1', 'presence_tue_a2',
                'presence_wed_m1', 'presence_wed_m2', 'presence_wed_a1', 'presence_wed_a2',
                'presence_thu_m1', 'presence_thu_m2', 'presence_thu_a1', 'presence_thu_a2',
                'presence_fri_m1', 'presence_fri_m2', 'presence_fri_a1', 'presence_fri_a2',
            ];
            
            $presence_data = ['fk_user_id' => $user_id];
            foreach ($presences as $index => $value) {
                if (isset($presence_fields[$index])) {
                    $presence_data[$presence_fields[$index]] = $value;
                }
            }
            
            $existing_presence = $presences_model->where('fk_user_id', $user_id)->first();
            if ($existing_presence) {
                $presence_array = is_object($existing_presence) ? (array)$existing_presence : $existing_presence;
                $id_presence = $presence_array['id_presence'] ?? null;
                if ($id_presence) {
                    $presences_model->update($id_presence, $presence_data);
                }
            } else {
                $inserted_id = $presences_model->insert($presence_data);
                // Record this presence as modified this week
                if ($inserted_id) {
                    $this->modifiedPresenceIds[] = $inserted_id;
                }
            }
        }
        
        // Add planning
        if ($planning) {
            $this->modifyPlannings([['user_id' => $user_id, 'new_planning' => $planning]], $planning_model);
        }
    }
    
    /**
     * Changes a user's role
     */
    private function changeUserRole($role_change, $db)
    {
        $user_id = $role_change['user_id'];
        $new_role_id = $role_change['new_role_id'];
        
        $user_data_model = new User_data_model();
        $user_data = $user_data_model->where('fk_user_id', $user_id)->first();
        
        if ($user_data) {
            $user_data_array = is_object($user_data) ? (array)$user_data : $user_data;
            $id_user_data = $user_data_array['id_user_data'] ?? null;
            if ($id_user_data) {
                $user_data_model->update($id_user_data, ['fk_role_id' => $new_role_id]);
            }
        }
    }
    
    /**
     * Adds a holiday dynamically during the test
     * This holiday will be adjusted to match the simulated date
     * 
     * IMPORTANT: Checks for duplicates before inserting to avoid multiple entries
     */
    private function addHoliday($holiday, $db)
    {
        $holidays_model = new Holidays_model();
        
        // Check if this holiday already exists (idempotent)
        $existing = $holidays_model
            ->where('name_holiday', $holiday['name'])
            ->where('start_date_holiday', $holiday['start_date'])
            ->where('end_date_holiday', $holiday['end_date'])
            ->first();
        
        if ($existing) {
            // Holiday already exists, use existing ID
            $existing_array = is_object($existing) ? (array)$existing : $existing;
            $id_holiday = $existing_array['id_holiday'] ?? null;
            
            if ($id_holiday) {
                // Store original dates if not already stored
                if (!isset($this->originalHolidayDates[$id_holiday])) {
                    $this->originalHolidayDates[$id_holiday] = [
                        'start' => $holiday['start_date'],
                        'end' => $holiday['end_date']
                    ];
                }
                // Mark as adjustable
                $this->adjustableHolidayIds[$id_holiday] = true;
            }
            return;
        }
        
        // Insert new holiday
        $inserted_id = $holidays_model->insert([
            'name_holiday' => $holiday['name'],
            'start_date_holiday' => $holiday['start_date'],
            'end_date_holiday' => $holiday['end_date'],
        ]);
        
        // Store original dates for this newly added holiday
        if ($inserted_id) {
            $this->originalHolidayDates[$inserted_id] = [
                'start' => $holiday['start_date'],
                'end' => $holiday['end_date']
            ];
            // Mark this holiday as adjustable (it was added dynamically during the test)
            $this->adjustableHolidayIds[$inserted_id] = true;
        }
    }
    
    /**
     * Adds vacation (modifies presences)
     */
    private function addVacation($vacation, $presences_model)
    {
        $start_period = $vacation['start_period'];
        $end_period = $vacation['end_period'];
        $presence_value = $vacation['presence_value'];
        $user_id = $vacation['user_id'] ?? null;
        
        // Period mapping
        $period_mapping = [
            'mon-m1' => 'presence_mon_m1', 'mon-m2' => 'presence_mon_m2',
            'mon-a1' => 'presence_mon_a1', 'mon-a2' => 'presence_mon_a2',
            'tue-m1' => 'presence_tue_m1', 'tue-m2' => 'presence_tue_m2',
            'tue-a1' => 'presence_tue_a1', 'tue-a2' => 'presence_tue_a2',
            'wed-m1' => 'presence_wed_m1', 'wed-m2' => 'presence_wed_m2',
            'wed-a1' => 'presence_wed_a1', 'wed-a2' => 'presence_wed_a2',
            'thu-m1' => 'presence_thu_m1', 'thu-m2' => 'presence_thu_m2',
            'thu-a1' => 'presence_thu_a1', 'thu-a2' => 'presence_thu_a2',
            'fri-m1' => 'presence_fri_m1', 'fri-m2' => 'presence_fri_m2',
            'fri-a1' => 'presence_fri_a1', 'fri-a2' => 'presence_fri_a2',
        ];
        
        $all_periods = array_keys($period_mapping);
        $start_index = array_search($start_period, $all_periods);
        $end_index = array_search($end_period, $all_periods);
        
        if ($start_index === false || $end_index === false) {
            CLI::write("  ✗ Error: invalid periods for vacation", 'red');
            return;
        }
        
        // Get users to modify
        $users_to_modify = $user_id ? [$user_id] : $presences_model->findAll();
        
        foreach ($users_to_modify as $user) {
            $user_array = is_object($user) ? (array)$user : $user;
            $current_user_id = $user_array['fk_user_id'] ?? ($user_id ?? null);
            
            if (!$current_user_id) continue;
            
            $presence_record = $presences_model->where('fk_user_id', $current_user_id)->first();
            if (!$presence_record) continue;
            
            $presence_array = is_object($presence_record) ? (array)$presence_record : $presence_record;
            $id_presence = $presence_array['id_presence'] ?? null;
            
            if ($id_presence) {
                $update_data = [];
                for ($i = $start_index; $i <= $end_index; $i++) {
                    $period_field = $period_mapping[$all_periods[$i]] ?? null;
                    if ($period_field) {
                        $update_data[$period_field] = $presence_value;
                    }
                }
                
                if (!empty($update_data)) {
                    $presences_model->update($id_presence, $update_data);
                    // Record this presence as modified this week
                    $this->modifiedPresenceIds[] = $id_presence;
                }
            }
        }
    }
    
    /**
     * Stores original holiday dates to avoid cumulative shifts
     * Only stores dates that haven't been stored yet (to preserve original dates)
     * 
     * IMPORTANT: Holidays from seeders (like TestInsertHolidaysData) should NOT be adjusted.
     * Only holidays added dynamically during the test (via add_holiday action) should be adjusted.
     * 
     * @param object $db Database instance
     */
    private function storeOriginalHolidayDates($db)
    {
        $holidays_model = new Holidays_model();
        $all_holidays = $holidays_model->findAll();
        
        foreach ($all_holidays as $holiday) {
            $holiday_array = is_object($holiday) ? (array)$holiday : $holiday;
            $id_holiday = $holiday_array['id_holiday'] ?? null;
            
            // Only store if not already stored (to preserve original dates)
            if ($id_holiday && !isset($this->originalHolidayDates[$id_holiday])) {
                $this->originalHolidayDates[$id_holiday] = [
                    'start' => $holiday_array['start_date_holiday'],
                    'end' => $holiday_array['end_date_holiday']
                ];
                // By default, holidays are NOT adjustable (they come from seeders)
                // They will be marked as adjustable when added dynamically via addHoliday()
            }
        }
    }
    
    /**
     * Adjusts holidays in the database to match the simulated date
     * 
     * This function modifies holiday dates so they correspond
     * to the real date of this week, allowing PlanningGenerator to detect them
     * correctly without code modification.
     * 
     * IMPORTANT: Only adjusts holidays that were added dynamically during the test
     * (marked in $adjustableHolidayIds). Holidays from seeders (like Noël) are NOT adjusted
     * and remain at their original dates.
     * 
     * IMPORTANT: Uses original holiday dates to avoid cumulative shifts.
     * Each week, holidays are recalculated from their original dates, not from
     * previously modified dates.
     * 
     * The offset is calculated based on the ORIGINAL holiday date, not the simulated week date.
     * This ensures that a holiday added on 2025-09-22 will always be adjusted to the real Monday
     * that corresponds to 2025-09-22, regardless of which simulated week we're processing.
     * 
     * Example : If a holiday was originally added on 2025-09-22 (Monday),
     * we calculate the offset from 2025-09-22 to the current real Monday,
     * and apply that offset to the holiday's original date.
     * 
     * @param string $simulatedDate Simulated date in Y-m-d format (not used for offset calculation)
     * @param object $db Database instance
     */
    private function adjustHolidaysForSimulatedDate($simulatedDate, $db)
    {
        // Use DateTime objects with UTC timezone to avoid timezone issues
        $timezone = new \DateTimeZone('UTC');
        
        // Calculate current real week's Monday (at midnight UTC)
        $real_datetime = new \DateTime('now', $timezone);
        $real_monday = clone $real_datetime;
        $real_monday->modify('monday this week');
        
        // Get all holidays (including newly added ones)
        $holidays_model = new Holidays_model();
        $all_holidays = $holidays_model->findAll();
        
        // Modify only adjustable holidays (those added dynamically during the test)
        foreach ($all_holidays as $holiday) {
            $holiday_array = is_object($holiday) ? (array)$holiday : $holiday;
            $id_holiday = $holiday_array['id_holiday'] ?? null;
            
            if (!$id_holiday) continue;
            
            // Skip holidays that are NOT adjustable (they come from seeders and should keep their original dates)
            if (!isset($this->adjustableHolidayIds[$id_holiday])) {
                continue;
            }
            
            // Use original date if available, otherwise use current date (for newly added holidays)
            if (isset($this->originalHolidayDates[$id_holiday])) {
                // Use original date to avoid cumulative shifts
                $original_start_str = $this->originalHolidayDates[$id_holiday]['start'];
                $original_end_str = $this->originalHolidayDates[$id_holiday]['end'];
            } else {
                // Newly added holiday - store its current date as original
                $original_start_str = $holiday_array['start_date_holiday'];
                $original_end_str = $holiday_array['end_date_holiday'];
                $this->originalHolidayDates[$id_holiday] = [
                    'start' => $original_start_str,
                    'end' => $original_end_str
                ];
                // Mark as adjustable
                $this->adjustableHolidayIds[$id_holiday] = true;
            }
            
            // Parse original dates using DateTime (preserves time component)
            $original_start = new \DateTime($original_start_str, $timezone);
            $original_end = new \DateTime($original_end_str, $timezone);
            
            // Calculate the Monday of the week containing the original holiday date
            $original_monday = clone $original_start;
            $original_monday->modify('monday this week');
            
            // Calculate day offset from original holiday's Monday to current real Monday
            // Positive = advance in time (original is in the past)
            $offset_interval = $real_monday->diff($original_monday);
            $offset_days = (int)$offset_interval->format('%r%a'); // %r gives sign
            
            // If no offset (within same week), no need to modify
            if (abs($offset_days) < 1) {
                continue;
            }
            
            // Calculate new dates by adding offset in days (preserves time component)
            $new_start = clone $original_start;
            $new_start->modify("$offset_days days");
            
            $new_end = clone $original_end;
            $new_end->modify("$offset_days days");
            
            // Format new dates preserving the time component (UTC)
            $new_start_str = $new_start->format('Y-m-d H:i:s');
            $new_end_str = $new_end->format('Y-m-d H:i:s');
            
            // Update in database
            $db->table('tbl_holidays')
               ->where('id_holiday', $id_holiday)
               ->update([
                   'start_date_holiday' => $new_start_str,
                   'end_date_holiday' => $new_end_str
               ]);
        }
    }
    
    /**
     * Simulates time passage by aging unmodified presences
     * 
     * This function updates presence updated_at timestamps to simulate
     * time passage over 20 weeks according to the following logic :
     * - Presences modified in the current week have a recent updated_at (week date)
     * - Unmodified presences have an old updated_at (week date - 8 days)
     * 
     * @param Presences_model $presences_model
     * @param int $weekNum Current week number
     */
    private function simulateTimePassage($presences_model, $weekNum)
    {
        CLI::write("  → Simulating time passage (simulated date: {$this->currentSimulatedDate})...", 'cyan');
        
        $db = \Config\Database::connect();
        
        // Reference date for modified presences: current week date (recent)
        $recentDate = $this->currentSimulatedDate . ' 12:00:00';
        
        // Reference date for unmodified presences: week date - 8 days (old)
        // This ensures they will be outside the 7-day window
        $oldDate = date('Y-m-d H:i:s', strtotime($this->currentSimulatedDate . ' -8 days'));
        
        // Update modified presences with recent date
        $recentCount = 0;
        if (!empty($this->modifiedPresenceIds)) {
            $recentCount = $db->table('tbl_presences')
               ->whereIn('id_presence', $this->modifiedPresenceIds)
               ->set('updated_at', $recentDate)
               ->update();
        }
        
        // Update unmodified presences with old date
        $updatedCount = 0;
        if (empty($this->modifiedPresenceIds)) {
            // All presences are old
            $updatedCount = $db->table('tbl_presences')
               ->set('updated_at', $oldDate)
               ->update();
        } else {
            // Only those that have not been modified
            $updatedCount = $db->table('tbl_presences')
                ->whereNotIn('id_presence', $this->modifiedPresenceIds)
                ->set('updated_at', $oldDate)
                ->update();
        }
        
        CLI::write("    ✓ {$updatedCount} presences aged (updated_at = {$oldDate}), {$recentCount} presences kept recent (updated_at = {$recentDate})", 'green');
    }
    
    /**
     * Simulates week shift (lw -> cw -> nw)
     */
    private function simulateWeekShift($planning_model, $nw_planning_model, $lw_planning_model)
    {
        CLI::write("  → Simulating week shift...", 'cyan');
        
        $db = \Config\Database::connect();
        
        // 1. Delete last week
        $db->table('tbl_lw_planning')->truncate();
        
        // 2. Current week -> Last week
        $cw_planning = $planning_model->findAll();
        if (!empty($cw_planning)) {
            $lw_data = [];
            foreach ($cw_planning as $planning) {
                $planning_array = is_object($planning) ? (array)$planning : $planning;
                $lw_data[] = [
                    'fk_user_id' => $planning_array['fk_user_id'],
                    'lw_planning_mon_m1' => $planning_array['planning_mon_m1'] ?? null,
                    'lw_planning_mon_m2' => $planning_array['planning_mon_m2'] ?? null,
                    'lw_planning_mon_a1' => $planning_array['planning_mon_a1'] ?? null,
                    'lw_planning_mon_a2' => $planning_array['planning_mon_a2'] ?? null,
                    'lw_planning_tue_m1' => $planning_array['planning_tue_m1'] ?? null,
                    'lw_planning_tue_m2' => $planning_array['planning_tue_m2'] ?? null,
                    'lw_planning_tue_a1' => $planning_array['planning_tue_a1'] ?? null,
                    'lw_planning_tue_a2' => $planning_array['planning_tue_a2'] ?? null,
                    'lw_planning_wed_m1' => $planning_array['planning_wed_m1'] ?? null,
                    'lw_planning_wed_m2' => $planning_array['planning_wed_m2'] ?? null,
                    'lw_planning_wed_a1' => $planning_array['planning_wed_a1'] ?? null,
                    'lw_planning_wed_a2' => $planning_array['planning_wed_a2'] ?? null,
                    'lw_planning_thu_m1' => $planning_array['planning_thu_m1'] ?? null,
                    'lw_planning_thu_m2' => $planning_array['planning_thu_m2'] ?? null,
                    'lw_planning_thu_a1' => $planning_array['planning_thu_a1'] ?? null,
                    'lw_planning_thu_a2' => $planning_array['planning_thu_a2'] ?? null,
                    'lw_planning_fri_m1' => $planning_array['planning_fri_m1'] ?? null,
                    'lw_planning_fri_m2' => $planning_array['planning_fri_m2'] ?? null,
                    'lw_planning_fri_a1' => $planning_array['planning_fri_a1'] ?? null,
                    'lw_planning_fri_a2' => $planning_array['planning_fri_a2'] ?? null,
                ];
            }
            if (!empty($lw_data)) {
                $lw_planning_model->insertBatch($lw_data);
            }
            $db->table('tbl_planning')->truncate();
        }
        
        // 3. Next week -> Current week
        $nw_planning = $nw_planning_model->findAll();
        if (!empty($nw_planning)) {
            $cw_data = [];
            foreach ($nw_planning as $planning) {
                $planning_array = is_object($planning) ? (array)$planning : $planning;
                $cw_data[] = [
                    'fk_user_id' => $planning_array['fk_user_id'],
                    'planning_mon_m1' => $planning_array['nw_planning_mon_m1'] ?? null,
                    'planning_mon_m2' => $planning_array['nw_planning_mon_m2'] ?? null,
                    'planning_mon_a1' => $planning_array['nw_planning_mon_a1'] ?? null,
                    'planning_mon_a2' => $planning_array['nw_planning_mon_a2'] ?? null,
                    'planning_tue_m1' => $planning_array['nw_planning_tue_m1'] ?? null,
                    'planning_tue_m2' => $planning_array['nw_planning_tue_m2'] ?? null,
                    'planning_tue_a1' => $planning_array['nw_planning_tue_a1'] ?? null,
                    'planning_tue_a2' => $planning_array['nw_planning_tue_a2'] ?? null,
                    'planning_wed_m1' => $planning_array['nw_planning_wed_m1'] ?? null,
                    'planning_wed_m2' => $planning_array['nw_planning_wed_m2'] ?? null,
                    'planning_wed_a1' => $planning_array['nw_planning_wed_a1'] ?? null,
                    'planning_wed_a2' => $planning_array['nw_planning_wed_a2'] ?? null,
                    'planning_thu_m1' => $planning_array['nw_planning_thu_m1'] ?? null,
                    'planning_thu_m2' => $planning_array['nw_planning_thu_m2'] ?? null,
                    'planning_thu_a1' => $planning_array['nw_planning_thu_a1'] ?? null,
                    'planning_thu_a2' => $planning_array['nw_planning_thu_a2'] ?? null,
                    'planning_fri_m1' => $planning_array['nw_planning_fri_m1'] ?? null,
                    'planning_fri_m2' => $planning_array['nw_planning_fri_m2'] ?? null,
                    'planning_fri_a1' => $planning_array['nw_planning_fri_a1'] ?? null,
                    'planning_fri_a2' => $planning_array['nw_planning_fri_a2'] ?? null,
                ];
            }
            if (!empty($cw_data)) {
                $planning_model->insertBatch($cw_data);
            }
            $db->table('tbl_nw_planning')->truncate();
        }
    }
    
    /**
     * Exécute tous les tests pour une semaine
     */
    private function executeWeekTests($generator, $weekNum, $weekDescription, $db)
    {
        $weekResult = [
            'week' => $weekNum,
            'description' => $weekDescription,
            'test1_periods_count' => 0,
            'test2_users_count' => 0,
            'test2_users_by_role' => [],
            'test3_presences_by_period' => [],
            'test4_can_copy' => false,
            'test4_presences_updated_recently' => false,
            'test5_success' => false,
            'test5_error' => null,
            'generated_periods' => null,
            'results' => [
                'periods_count' => 0,
                'periods_with_assignments' => 0,
                'total_assignments' => 0,
                'user_stats' => []
            ]
        ];
        
        // TEST 1: Get periods
        CLI::write("  → TEST 1: Getting periods...", 'cyan');
        $periods = $generator->getNextWeekPeriodsOn();
        $weekResult['test1_periods_count'] = count($periods);
        
        // TEST 2: Get users
        CLI::write("  → TEST 2: Getting users...", 'cyan');
        $users = $generator->getUsersPresentNextWeekWithThereRoles();
        $weekResult['test2_users_count'] = count($users);
        
        // Count users by role
        $user_data_model = new User_data_model();
        foreach ($users as $user_id => $user) {
            $user_data_array = $user_data_model->getUserData($user_id);
            if (!empty($user_data_array)) {
                $user_data = is_array($user_data_array[0]) ? $user_data_array[0] : (array)$user_data_array[0];
                $role_id = $user_data['fk_role_id'] ?? null;
                if ($role_id) {
                    $role_data = $db->table('tbl_roles')
                        ->where('id_role', $role_id)
                        ->get()
                        ->getRowArray();
                    $role_name = $role_data['name_role'] ?? 'Inconnu';
                    
                    if (!isset($weekResult['test2_users_by_role'][$role_name])) {
                        $weekResult['test2_users_by_role'][$role_name] = 0;
                    }
                    $weekResult['test2_users_by_role'][$role_name]++;
                }
            }
        }
        
        // TEST 3: Get presences
        CLI::write("  → TEST 3: Getting presences...", 'cyan');
        $presences = $generator->getUsersPresencesPerPeriods($periods, $users);
        
        foreach ($presences as $period_name => $presence) {
            $available_count = count($presence['all_available_technicians']);
            $weekResult['test3_presences_by_period'][$period_name] = $available_count;
        }
        
        // TEST 4: Check planning copy
        CLI::write("  → TEST 4: Checking planning copy...", 'cyan');
        $canCopy = $generator->canPlanningBeCopiedFromCurrentWeek();
        $weekResult['test4_can_copy'] = $canCopy;
        $weekResult['test4_presences_updated_recently'] = $generator->hasPresencesBeenUpdatedRecently(7);
        
        // TEST 5: Complete planning generation
        CLI::write("  → TEST 5: Complete planning generation...", 'cyan');
        try {
            // For testing, we always force generation (don't copy)
            // We directly get periods, users and presences
            $periods = $generator->getNextWeekPeriodsOn();
            $users = $generator->getUsersPresentNextWeekWithThereRoles();
            $presences = $generator->getUsersPresencesPerPeriods($periods, $users);
            
            // Force generation instead of copying
            $generated_periods = $generator->generateNextWeekPlanningAttribution($periods, $users, $presences);
            
            $weekResult['test5_success'] = true;
            $weekResult['generated_periods'] = $generated_periods;
            
            // Collect generation results
            $weekResult['results']['periods_count'] = count($generated_periods);
            
            $periods_with_assignments = 0;
            $total_assignments = 0;
            $user_stats = [];
            
            foreach ($generated_periods as $period_name => $period) {
                $has_assignment = false;
                
                // Check if it's an array or object
                $period_array = is_object($period) ? (array)$period : $period;
                
                if (isset($period_array['first_technician']) && $period_array['first_technician'] !== null) {
                    $user_id = $period_array['first_technician'];
                    if (!isset($user_stats[$user_id])) {
                        $user_stats[$user_id] = ['tech1' => 0, 'tech2' => 0, 'tech3' => 0];
                    }
                    $user_stats[$user_id]['tech1']++;
                    $has_assignment = true;
                    $total_assignments++;
                }
                
                if (isset($period_array['second_technician']) && $period_array['second_technician'] !== null) {
                    $user_id = $period_array['second_technician'];
                    if (!isset($user_stats[$user_id])) {
                        $user_stats[$user_id] = ['tech1' => 0, 'tech2' => 0, 'tech3' => 0];
                    }
                    $user_stats[$user_id]['tech2']++;
                    $has_assignment = true;
                    $total_assignments++;
                }
                
                if (isset($period_array['third_technician']) && $period_array['third_technician'] !== null) {
                    $user_id = $period_array['third_technician'];
                    if (!isset($user_stats[$user_id])) {
                        $user_stats[$user_id] = ['tech1' => 0, 'tech2' => 0, 'tech3' => 0];
                    }
                    $user_stats[$user_id]['tech3']++;
                    $has_assignment = true;
                    $total_assignments++;
                }
                
                if ($has_assignment) {
                    $periods_with_assignments++;
                }
            }
            
            $weekResult['results']['periods_with_assignments'] = $periods_with_assignments;
            $weekResult['results']['total_assignments'] = $total_assignments;
            $weekResult['results']['user_stats'] = $user_stats;
            
        } catch (\Exception $e) {
            $weekResult['test5_success'] = false;
            $weekResult['test5_error'] = $e->getMessage();
            CLI::write("  ✗ Error during generation: " . $e->getMessage(), 'red');
        }
        
        return $weekResult;
    }
    
    /**
     * Saves generated planning in nw_planning table
     */
    private function saveGeneratedPlanning($generated_periods, $nw_planning_model)
    {
        CLI::write("  → Saving generated planning...", 'cyan');
        
        // Map generated periods to database format
        $period_mapping = [
            'mon-m1' => 'nw_planning_mon_m1',
            'mon-m2' => 'nw_planning_mon_m2',
            'mon-a1' => 'nw_planning_mon_a1',
            'mon-a2' => 'nw_planning_mon_a2',
            'tue-m1' => 'nw_planning_tue_m1',
            'tue-m2' => 'nw_planning_tue_m2',
            'tue-a1' => 'nw_planning_tue_a1',
            'tue-a2' => 'nw_planning_tue_a2',
            'wed-m1' => 'nw_planning_wed_m1',
            'wed-m2' => 'nw_planning_wed_m2',
            'wed-a1' => 'nw_planning_wed_a1',
            'wed-a2' => 'nw_planning_wed_a2',
            'thu-m1' => 'nw_planning_thu_m1',
            'thu-m2' => 'nw_planning_thu_m2',
            'thu-a1' => 'nw_planning_thu_a1',
            'thu-a2' => 'nw_planning_thu_a2',
            'fri-m1' => 'nw_planning_fri_m1',
            'fri-m2' => 'nw_planning_fri_m2',
            'fri-a1' => 'nw_planning_fri_a1',
            'fri-a2' => 'nw_planning_fri_a2',
        ];
        
        // Group by user
        $planning_by_user = [];
        
        foreach ($generated_periods as $period_name => $period) {
            // Convert period name (e.g. "mon-m1") to DB field name
            $db_field = $period_mapping[$period_name] ?? null;
            if (!$db_field) continue;
            
            // Process each assigned technician
            foreach (['first_technician', 'second_technician', 'third_technician'] as $tech_key) {
                if (isset($period[$tech_key]) && $period[$tech_key] !== null) {
                    $user_id = $period[$tech_key];
                    
                    if (!isset($planning_by_user[$user_id])) {
                        $planning_by_user[$user_id] = [
                            'fk_user_id' => $user_id,
                            'nw_planning_mon_m1' => null,
                            'nw_planning_mon_m2' => null,
                            'nw_planning_mon_a1' => null,
                            'nw_planning_mon_a2' => null,
                            'nw_planning_tue_m1' => null,
                            'nw_planning_tue_m2' => null,
                            'nw_planning_tue_a1' => null,
                            'nw_planning_tue_a2' => null,
                            'nw_planning_wed_m1' => null,
                            'nw_planning_wed_m2' => null,
                            'nw_planning_wed_a1' => null,
                            'nw_planning_wed_a2' => null,
                            'nw_planning_thu_m1' => null,
                            'nw_planning_thu_m2' => null,
                            'nw_planning_thu_a1' => null,
                            'nw_planning_thu_a2' => null,
                            'nw_planning_fri_m1' => null,
                            'nw_planning_fri_m2' => null,
                            'nw_planning_fri_a1' => null,
                            'nw_planning_fri_a2' => null,
                        ];
                    }
                    
                    // Determine value according to technician type
                    $assignment_value = null;
                    if ($tech_key === 'first_technician') {
                        $assignment_value = TechnicianAssignment::FIRST_TECHNICIAN->value;
                    } elseif ($tech_key === 'second_technician') {
                        $assignment_value = TechnicianAssignment::SECOND_TECHNICIAN->value;
                    } elseif ($tech_key === 'third_technician') {
                        $assignment_value = TechnicianAssignment::THIRD_TECHNICIAN->value;
                    }
                    
                    // If user doesn't have an assignment for this period yet, or if it's a higher priority assignment
                    if ($planning_by_user[$user_id][$db_field] === null || 
                        ($assignment_value === TechnicianAssignment::FIRST_TECHNICIAN->value)) {
                        $planning_by_user[$user_id][$db_field] = $assignment_value;
                    }
                }
            }
        }
        
        // Insert data
        if (!empty($planning_by_user)) {
            $nw_planning_model->insertBatch(array_values($planning_by_user));
        }
    }
    
    /**
     * Generates report for a single week
     */
    private function generateWeekReport($filename, $weekResult, $weekConfig, $db = null)
    {
        $markdown = [];
        
        $markdown[] = '# Week ' . $weekResult['week'] . ' Report - ' . $weekResult['description'];
        $markdown[] = '';
        $markdown[] = '**Week date:** ' . ($weekConfig['date'] ?? 'N/A');
        $markdown[] = '**Report date:** ' . date('Y-m-d H:i:s');
        $markdown[] = '**Action:** ' . $weekConfig['action'];
        $markdown[] = '';
        
        // Action details
        if (isset($weekConfig['planning_modifications'])) {
            $markdown[] = '### Planning modifications';
            $markdown[] = '';
            foreach ($weekConfig['planning_modifications'] as $mod) {
                $markdown[] = '- **User ID ' . $mod['user_id'] . ':** Planning modified';
            }
            $markdown[] = '';
        }
        
        if (isset($weekConfig['new_user'])) {
            $markdown[] = '### New user';
            $markdown[] = '';
            $markdown[] = '- **User ID:** ' . $weekConfig['new_user']['user_id'];
            $markdown[] = '- **Role ID:** ' . $weekConfig['new_user']['role_id'];
            $markdown[] = '';
        }
        
        if (isset($weekConfig['role_change'])) {
            $markdown[] = '### Role change';
            $markdown[] = '';
            $markdown[] = '- **User ID:** ' . $weekConfig['role_change']['user_id'];
            $markdown[] = '- **New role ID:** ' . $weekConfig['role_change']['new_role_id'];
            $markdown[] = '';
        }
        
        if (isset($weekConfig['holiday'])) {
            $markdown[] = '### Holiday';
            $markdown[] = '';
            $markdown[] = '- **Name:** ' . $weekConfig['holiday']['name'];
            $markdown[] = '- **Start date:** ' . $weekConfig['holiday']['start_date'];
            $markdown[] = '- **End date:** ' . $weekConfig['holiday']['end_date'];
            $markdown[] = '';
        }
        
        if (isset($weekConfig['vacation'])) {
            $markdown[] = '### Vacation';
            $markdown[] = '';
            $markdown[] = '- **Start period:** ' . $weekConfig['vacation']['start_period'];
            $markdown[] = '- **End period:** ' . $weekConfig['vacation']['end_period'];
            $markdown[] = '- **User ID:** ' . ($weekConfig['vacation']['user_id'] ?? 'All');
            $markdown[] = '';
        }
        
        $markdown[] = '';
        $markdown[] = '---';
        $markdown[] = '';
        
        if (isset($weekResult['error'])) {
            $markdown[] = '## ❌ ERROR';
            $markdown[] = '';
            $markdown[] = '**Message:** ' . $weekResult['error'];
            $markdown[] = '';
            file_put_contents($filename, implode("\n", $markdown));
            return;
        }
        
        // TEST 1
        $markdown[] = '## TEST 1: Period retrieval';
        $markdown[] = '';
        $markdown[] = '**Number of periods:** ' . $weekResult['test1_periods_count'];
        $markdown[] = '';
        
        // TEST 2
        $markdown[] = '## TEST 2: User retrieval';
        $markdown[] = '';
        $markdown[] = '**Total number of users:** ' . $weekResult['test2_users_count'];
        $markdown[] = '';
        
        if (!empty($weekResult['test2_users_by_role'])) {
            $markdown[] = '### Users by role';
            $markdown[] = '';
            $markdown[] = '| Role | Count |';
            $markdown[] = '| ---- | ------ |';
            foreach ($weekResult['test2_users_by_role'] as $role => $count) {
                $markdown[] = '| ' . $role . ' | ' . $count . ' |';
            }
            $markdown[] = '';
        }
        
        // TEST 3
        $markdown[] = '## TEST 3: Presence retrieval';
        $markdown[] = '';
        if (!empty($weekResult['test3_presences_by_period'])) {
            $markdown[] = '| Period | Available technicians |';
            $markdown[] = '| ------- | ----------------------- |';
            foreach ($weekResult['test3_presences_by_period'] as $period => $count) {
                $markdown[] = '| `' . $period . '` | ' . $count . ' |';
            }
            $markdown[] = '';
        }
        
        // TEST 4
        $markdown[] = '## TEST 4: Planning copy verification';
        $markdown[] = '';
        $markdown[] = '- **Planning can be copied:** ' . ($weekResult['test4_can_copy'] ? '✅ Yes' : '❌ No');
        $markdown[] = '- **Presences updated recently (7 days):** ' . ($weekResult['test4_presences_updated_recently'] ? '✅ Yes' : '❌ No');
        $markdown[] = '';
        
        // TEST 5
        $markdown[] = '## TEST 5: Complete planning generation';
        $markdown[] = '';
        if ($weekResult['test5_success']) {
            $markdown[] = '✅ **Generation successful**';
            $markdown[] = '';
            $markdown[] = '### Results';
            $markdown[] = '';
            $markdown[] = '- **Total number of periods:** ' . $weekResult['results']['periods_count'];
            $markdown[] = '- **Periods with assignments:** ' . $weekResult['results']['periods_with_assignments'] . ' / ' . $weekResult['results']['periods_count'];
            $markdown[] = '- **Total assignments:** ' . $weekResult['results']['total_assignments'];
            $markdown[] = '';
            
            if (!empty($weekResult['results']['user_stats'])) {
                $markdown[] = '### Assignments by user';
                $markdown[] = '';
                $markdown[] = '| User ID | Tech1 | Tech2 | Tech3 | Total |';
                $markdown[] = '| ------- | ----- | ----- | ----- | ----- |';
                
                foreach ($weekResult['results']['user_stats'] as $user_id => $stats) {
                    $total = $stats['tech1'] + $stats['tech2'] + $stats['tech3'];
                    $markdown[] = '| ' . $user_id . ' | ' . $stats['tech1'] . ' | ' . $stats['tech2'] . ' | ' . $stats['tech3'] . ' | **' . $total . '** |';
                }
                $markdown[] = '';
            }
            
            // Add visual planning table
            if (!empty($weekResult['generated_periods'])) {
                $markdown[] = '### 📅 Generated Planning - Overview';
                $markdown[] = '';
                if ($db === null) {
                    $db = \Config\Database::connect();
                }
                $planningTable = $this->generatePlanningVisualTable($weekResult['generated_periods'], $db);
                $markdown = array_merge($markdown, $planningTable);
                $markdown[] = '';
            }
            
            $markdown[] = '- **Planning saved:** ' . ($weekResult['planning_saved'] ? '✅ Yes' : '❌ No');
        } else {
            $markdown[] = '❌ **Generation failed**';
            $markdown[] = '';
            $markdown[] = '**Error:** ' . ($weekResult['test5_error'] ?? 'Unknown error');
        }
        
        $markdown[] = '';
        $markdown[] = '---';
        $markdown[] = '';
        $markdown[] = '## ✅ REPORT COMPLETED';
        
        file_put_contents($filename, implode("\n", $markdown));
    }
    
    /**
     * Generates a visual table of generated planning
     * 
     * @param array $generated_periods Generated periods with assignments
     * @param object $db Database instance
     * @return array Array of markdown lines for the table
     */
    private function generatePlanningVisualTable($generated_periods, $db)
    {
        $markdown = [];
        
        // Get user names
        $user_ids = [];
        foreach ($generated_periods as $period) {
            $period_array = is_object($period) ? (array)$period : $period;
            if (isset($period_array['first_technician']) && $period_array['first_technician'] !== null) {
                $user_ids[] = $period_array['first_technician'];
            }
            if (isset($period_array['second_technician']) && $period_array['second_technician'] !== null) {
                $user_ids[] = $period_array['second_technician'];
            }
            if (isset($period_array['third_technician']) && $period_array['third_technician'] !== null) {
                $user_ids[] = $period_array['third_technician'];
            }
        }
        $user_ids = array_unique($user_ids);
        
        $user_names = [];
        if (!empty($user_ids)) {
            $users_data = $db->table('tbl_user_data')
                ->select('tbl_user_data.fk_user_id, first_name_user_data, last_name_user_data')
                ->whereIn('tbl_user_data.fk_user_id', $user_ids)
                ->get()
                ->getResultArray();
            
            foreach ($users_data as $user_data) {
                $full_name = trim(($user_data['first_name_user_data'] ?? '') . ' ' . ($user_data['last_name_user_data'] ?? ''));
                $user_names[$user_data['fk_user_id']] = $full_name ?: 'User ' . $user_data['fk_user_id'];
            }
        }
        
        // Helper function to get a user's name
        $getUserName = function($user_id) use ($user_names) {
            return $user_names[$user_id] ?? 'User ' . $user_id;
        };
        
        // Organize periods by day and type
        $planning_grid = [
            'mon' => ['m1' => ['tech1' => null, 'tech2' => null, 'tech3' => null],
                      'm2' => ['tech1' => null, 'tech2' => null, 'tech3' => null],
                      'a1' => ['tech1' => null, 'tech2' => null, 'tech3' => null],
                      'a2' => ['tech1' => null, 'tech2' => null, 'tech3' => null]],
            'tue' => ['m1' => ['tech1' => null, 'tech2' => null, 'tech3' => null],
                      'm2' => ['tech1' => null, 'tech2' => null, 'tech3' => null],
                      'a1' => ['tech1' => null, 'tech2' => null, 'tech3' => null],
                      'a2' => ['tech1' => null, 'tech2' => null, 'tech3' => null]],
            'wed' => ['m1' => ['tech1' => null, 'tech2' => null, 'tech3' => null],
                      'm2' => ['tech1' => null, 'tech2' => null, 'tech3' => null],
                      'a1' => ['tech1' => null, 'tech2' => null, 'tech3' => null],
                      'a2' => ['tech1' => null, 'tech2' => null, 'tech3' => null]],
            'thu' => ['m1' => ['tech1' => null, 'tech2' => null, 'tech3' => null],
                      'm2' => ['tech1' => null, 'tech2' => null, 'tech3' => null],
                      'a1' => ['tech1' => null, 'tech2' => null, 'tech3' => null],
                      'a2' => ['tech1' => null, 'tech2' => null, 'tech3' => null]],
            'fri' => ['m1' => ['tech1' => null, 'tech2' => null, 'tech3' => null],
                      'm2' => ['tech1' => null, 'tech2' => null, 'tech3' => null],
                      'a1' => ['tech1' => null, 'tech2' => null, 'tech3' => null],
                      'a2' => ['tech1' => null, 'tech2' => null, 'tech3' => null]],
        ];
        
        // Fill grid with generated data
        foreach ($generated_periods as $period_name => $period) {
            $period_array = is_object($period) ? (array)$period : $period;
            
            // Parse period name (e.g. "mon-m1")
            if (preg_match('/^(\w{3})-(\w{2})$/', $period_name, $matches)) {
                $day = $matches[1];
                $period_type = $matches[2];
                
                if (isset($planning_grid[$day][$period_type])) {
                    if (isset($period_array['first_technician']) && $period_array['first_technician'] !== null) {
                        $planning_grid[$day][$period_type]['tech1'] = $period_array['first_technician'];
                    }
                    if (isset($period_array['second_technician']) && $period_array['second_technician'] !== null) {
                        $planning_grid[$day][$period_type]['tech2'] = $period_array['second_technician'];
                    }
                    if (isset($period_array['third_technician']) && $period_array['third_technician'] !== null) {
                        $planning_grid[$day][$period_type]['tech3'] = $period_array['third_technician'];
                    }
                }
            }
        }
        
        // Generate markdown table
        // Header
        $markdown[] = '| Period | Monday | Tuesday | Wednesday | Thursday | Friday |';
        $markdown[] = '|--------|--------|---------|-----------|----------|--------|';
        
        // Rows for each period (m1, m2, a1, a2)
        $period_labels = [
            'm1' => 'Morning 1<br>(08:00-10:00)',
            'm2' => 'Morning 2<br>(10:00-12:00)',
            'a1' => 'Afternoon 1<br>(12:45-14:45)',
            'a2' => 'Afternoon 2<br>(15:00-16:57)',
        ];
        
        foreach (['m1', 'm2', 'a1', 'a2'] as $period_type) {
            $row = ['| ' . $period_labels[$period_type] . ' |'];
            
            foreach (['mon', 'tue', 'wed', 'thu', 'fri'] as $day) {
                $cell_content = [];
                
                if ($planning_grid[$day][$period_type]['tech1'] !== null) {
                    $user_id = $planning_grid[$day][$period_type]['tech1'];
                    $cell_content[] = '**Tech1:** ' . $getUserName($user_id) . ' (ID:' . $user_id . ')';
                }
                
                if ($planning_grid[$day][$period_type]['tech2'] !== null) {
                    $user_id = $planning_grid[$day][$period_type]['tech2'];
                    $cell_content[] = '**Tech2:** ' . $getUserName($user_id) . ' (ID:' . $user_id . ')';
                }
                
                if ($planning_grid[$day][$period_type]['tech3'] !== null) {
                    $user_id = $planning_grid[$day][$period_type]['tech3'];
                    $cell_content[] = '**Tech3:** ' . $getUserName($user_id) . ' (ID:' . $user_id . ')';
                }
                
                if (empty($cell_content)) {
                    $row[] = ' *No assignment* |';
                } else {
                    $row[] = ' ' . implode('<br>', $cell_content) . ' |';
                }
            }
            
            $markdown[] = implode('', $row);
        }
        
        return $markdown;
    }
    
    /**
     * Generates final summary report
     */
    private function generateFinalReport($filename, $allWeeksResults)
    {
        $markdown = [];
        
        // Header
        $markdown[] = '# Integration Test - Chronological Simulation';
        $markdown[] = '';
        $markdown[] = '**Date:** ' . date('Y-m-d H:i:s');
        $markdown[] = '**Number of weeks tested:** ' . count($allWeeksResults);
        $markdown[] = '**Output file:** `' . basename($filename) . '`';
        $markdown[] = '';
        $markdown[] = '---';
        $markdown[] = '';
        
        // Main summary table
        $markdown[] = '## 📊 SUMMARY TABLE';
        $markdown[] = '';
        $markdown[] = '| Week | Description | Periods | Users | Presences modified | Copy possible | Generation | Assignments |';
        $markdown[] = '| ---- | ----------- | ------- | ----- | ------------------- | ------------- | ---------- | ----------- |';
        
        foreach ($allWeeksResults as $result) {
            if (isset($result['error'])) {
                $markdown[] = '| ' . $result['week'] . ' | ' . ($result['description'] ?? 'N/A') . ' | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |';
            } else {
                $presences_updated = $result['test4_presences_updated_recently'] ? '✅ Yes' : '❌ No';
                $can_copy = $result['test4_can_copy'] ? '✅ Yes' : '❌ No';
                $generation = $result['test5_success'] ? '✅ Success' : '❌ Failure';
                $assignations = $result['test5_success'] ? $result['results']['total_assignments'] : 'N/A';
                
                $markdown[] = '| ' . $result['week'] . ' | ' . $result['description'] . ' | ' . 
                             $result['test1_periods_count'] . ' | ' . 
                             $result['test2_users_count'] . ' | ' . 
                             $presences_updated . ' | ' . 
                             $can_copy . ' | ' . 
                             $generation . ' | ' . 
                             $assignations . ' |';
            }
        }
        
        $markdown[] = '';
        $markdown[] = '---';
        $markdown[] = '';
        
        // Details by week
        $markdown[] = '## 📅 DETAILS BY WEEK';
        $markdown[] = '';
        
        foreach ($allWeeksResults as $result) {
            if (isset($result['error'])) {
                $markdown[] = '### Week ' . $result['week'] . ' - ❌ ERROR';
                $markdown[] = '';
                $markdown[] = '**Error:** ' . $result['error'];
                $markdown[] = '';
                continue;
            }
            
            $markdown[] = '### Week ' . $result['week'] . ' - ' . $result['description'];
            $markdown[] = '';
            $markdown[] = '- **Periods:** ' . $result['test1_periods_count'];
            $markdown[] = '- **Users:** ' . $result['test2_users_count'];
            $markdown[] = '- **Presences modified recently:** ' . ($result['test4_presences_updated_recently'] ? 'Yes' : 'No');
            $markdown[] = '- **Copy possible:** ' . ($result['test4_can_copy'] ? 'Yes' : 'No');
            $markdown[] = '- **Generation:** ' . ($result['test5_success'] ? '✅ Success' : '❌ Failure');
            
            if ($result['test5_success']) {
                $markdown[] = '- **Periods with assignments:** ' . $result['results']['periods_with_assignments'] . ' / ' . $result['results']['periods_count'];
                $markdown[] = '- **Total assignments:** ' . $result['results']['total_assignments'];
            }
            
            $markdown[] = '';
        }
        
        $markdown[] = '---';
        $markdown[] = '';
        
        // Comparative statistics
        $markdown[] = '## 📈 COMPARATIVE STATISTICS';
        $markdown[] = '';
        
        $successful_weeks = array_filter($allWeeksResults, function($r) { return !isset($r['error']) && isset($r['test5_success']) && $r['test5_success']; });
        
        if (!empty($successful_weeks)) {
            $total_assignments = array_sum(array_column(array_column($successful_weeks, 'results'), 'total_assignments'));
            $avg_assignments = $total_assignments / count($successful_weeks);
            $total_periods = array_sum(array_column($successful_weeks, 'test1_periods_count'));
            $avg_periods = $total_periods / count($successful_weeks);
            
            $markdown[] = '- **Successful weeks:** ' . count($successful_weeks) . ' / ' . count($allWeeksResults);
            $markdown[] = '- **Total assignments (all weeks):** ' . $total_assignments;
            $markdown[] = '- **Average assignments per week:** ' . round($avg_assignments, 2);
            $markdown[] = '- **Average periods per week:** ' . round($avg_periods, 2);
            $markdown[] = '';
        }
        
        $markdown[] = '---';
        $markdown[] = '';
        $markdown[] = '## ✅ TEST COMPLETED';
        $markdown[] = '';
        $markdown[] = '> **Note:** Detailed reports for each week are available in the `writable/` folder';
        
        file_put_contents($filename, implode("\n", $markdown));
    }
}
