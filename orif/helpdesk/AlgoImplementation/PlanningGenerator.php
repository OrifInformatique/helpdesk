<?php

/**
 * Main class for automatic generation of next week's planning
 * 
 * Implementation of the algorithm described in Mermaid files
 * 
 * @author      Orif
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Helpdesk\AlgoImplementation;

use Helpdesk\Models\Presences_model;
use Helpdesk\Models\Roles_model;
use Helpdesk\Models\Planning_model;
use Helpdesk\Models\Nw_planning_model;
use Helpdesk\Models\Holidays_model;
use Helpdesk\Models\User_data_model;
use Helpdesk\Enums\TechnicianPresence;
use Helpdesk\Enums\TechnicianAssignment;
use Helpdesk\Enums\PlanningPeriod;

class PlanningGenerator
{
    protected $presences_model;
    protected $roles_model;
    protected $planning_model;
    protected $nw_planning_model;
    protected $holidays_model;
    protected $user_data_model;
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->presences_model = new Presences_model();
        $this->roles_model = new Roles_model();
        $this->planning_model = new Planning_model();
        $this->nw_planning_model = new Nw_planning_model();
        $this->holidays_model = new Holidays_model();
        $this->user_data_model = new User_data_model();
    }

    /**
     * Main function: generates next week's planning
     * 
     * @return array Array of periods with assignments
     */
    public function generateNextWeekPlanning()
    {
        // Initialize arrays
        $periods = [];
        $users = [];
        $presences = [];

        // Get next week's calendar information
        $periods = $this->getNextWeekPeriodsOn();

        // Get user information
        $users = $this->getUsersPresentNextWeekWithThereRoles();

        // Get presence information
        $presences = $this->getUsersPresencesPerPeriods($periods, $users);

        // Check if planning can be copied
        $canCopy = $this->canPlanningBeCopiedFromCurrentWeek();

        if ($canCopy) {
            // Copy current week's planning to next week
            $periods = $this->copyCurrentWeekPlanningToNextWeek();
            return $periods;
        } else {
            // Execute assignment logic
            $periods = $this->generateNextWeekPlanningAttribution($periods, $users, $presences);
            return $periods;
        }
    }

    /**
     * Gets next week's periods (removing off periods)
     * 
     * @return array Array of next week's periods
     */
    public function getNextWeekPeriodsOn()
    {
        // Initialize periods[] array
        $periods = [];

        // Get next week's periods
        $next_monday = strtotime('next monday');
        
        $next_week = [
            'monday' => $next_monday,
            'tuesday' => strtotime('+1 day', $next_monday),
            'wednesday' => strtotime('+2 days', $next_monday),
            'thursday' => strtotime('+3 days', $next_monday),
            'friday' => strtotime('+4 days', $next_monday)
        ];
        
        foreach ($next_week as $key => $day) {
            $periods += [
                substr($key, 0, 3) . '-m1' => [
                    'start' => strtotime(date('Y-m-d', $day) . ' 08:00:00'),
                    'end' => strtotime(date('Y-m-d', $day) . ' 10:00:00')
                ],
                substr($key, 0, 3) . '-m2' => [
                    'start' => strtotime(date('Y-m-d', $day) . ' 10:00:00'),
                    'end' => strtotime(date('Y-m-d', $day) . ' 12:00:00')
                ],
                substr($key, 0, 3) . '-a1' => [
                    'start' => strtotime(date('Y-m-d', $day) . ' 12:45:00'),
                    'end' => strtotime(date('Y-m-d', $day) . ' 14:45:00')
                ],
                substr($key, 0, 3) . '-a2' => [
                    'start' => strtotime(date('Y-m-d', $day) . ' 15:00:00'),
                    'end' => strtotime(date('Y-m-d', $day) . ' 16:57:00')
                ]
            ];
        }

        // SQL - Remove off periods (holidays)
        $holidays_data = $this->holidays_model->getHolidays();

        foreach ($holidays_data as $holiday) {
            foreach ($periods as $period_name => $period) {
                // If the period is within a holiday period
                if ($period['start'] >= strtotime($holiday['start_date_holiday']) && 
                    $period['end'] <= strtotime($holiday['end_date_holiday'])) {
                    unset($periods[$period_name]);
                }
            }
        }

        return $periods;
    }

    /**
     * Gets users present next week with their roles
     * 
     * @return array Array of users with their information
     */
    public function getUsersPresentNextWeekWithThereRoles()
    {
        // Initialize users[] array
        $users = [];

        // SQL - Get all roles where priority_role is not 0
        $roles_data = $this->roles_model->where('priority_role !=', 0)->findAll();

        // For each role
        foreach ($roles_data as $role) {
            // SQL - Get all user_ids with at least 1 PRESENT or PARTIALLY_ABSENT who have this role
            $presence_fields = [
                'presence_mon_m1', 'presence_mon_m2', 'presence_mon_a1', 'presence_mon_a2',
                'presence_tue_m1', 'presence_tue_m2', 'presence_tue_a1', 'presence_tue_a2',
                'presence_wed_m1', 'presence_wed_m2', 'presence_wed_a1', 'presence_wed_a2',
                'presence_thu_m1', 'presence_thu_m2', 'presence_thu_a1', 'presence_thu_a2',
                'presence_fri_m1', 'presence_fri_m2', 'presence_fri_a1', 'presence_fri_a2'
            ];

            // Build query to find users with this role and at least one presence
            $builder = $this->db->table('tbl_presences')
                ->select('tbl_presences.fk_user_id')
                ->distinct()
                ->join('tbl_user_data', 'tbl_presences.fk_user_id = tbl_user_data.fk_user_id', 'inner')
                ->join('user', 'tbl_presences.fk_user_id = user.id', 'inner')
                ->where('tbl_user_data.fk_role_id', $role['id_role'])
                ->where('user.archive', null);

            // Build OR condition for at least one PRESENT or PARTIALLY_ABSENT presence
            $builder->groupStart();
            $first = true;
            foreach ($presence_fields as $field) {
                if ($first) {
                    $builder->groupStart()
                        ->where($field, TechnicianPresence::PRESENT->value)
                        ->orWhere($field, TechnicianPresence::PARTLY_ABSENT->value)
                        ->groupEnd();
                    $first = false;
                } else {
                    $builder->orGroupStart()
                        ->where($field, TechnicianPresence::PRESENT->value)
                        ->orWhere($field, TechnicianPresence::PARTLY_ABSENT->value)
                        ->groupEnd();
                }
            }
            $builder->groupEnd();

            $users_with_role = $builder->get()->getResultArray();

            // For each user
            foreach ($users_with_role as $user_data) {
                $user_id = $user_data['fk_user_id'];

                // Add to this user the arrays:
                // role_priority, technician_1_assignation[0,0,0], technician_2_assignation[0,0,0], 
                // technician_3_assignation[0,0,0], available_periods_counter
                if (!isset($users[$user_id])) {
                    $users[$user_id] = [
                        'user_id' => $user_id,
                        'role_priority' => $role['priority_role'],
                        'technician_1_assignation' => [
                            'min' => $role['min_assignation_first_technician_role'],
                            'max' => $role['max_assignation_first_technician_role'],
                            'assigned' => 0
                        ],
                        'technician_2_assignation' => [
                            'min' => $role['min_assignation_second_technician_role'],
                            'max' => $role['max_assignation_second_technician_role'],
                            'assigned' => 0
                        ],
                        'technician_3_assignation' => [
                            'min' => $role['min_assignation_third_technician_role'],
                            'max' => $role['max_assignation_third_technician_role'],
                            'assigned' => 0
                        ],
                        'available_periods_counter' => 0
                    ];
                }
            }
        }

        return $users;
    }

    /**
     * Gets user presences by period
     * 
     * @param array $periods Array of periods
     * @param array $users Array of users (passed by reference to modify available_periods_counter)
     * @return array Array of presences enriched with available technicians
     */
    public function getUsersPresencesPerPeriods($periods, &$users)
    {
        // Initialize presences[] array which is a copy of periods[]
        $presences = $periods;

        // Initialize arrays in each presences[]
        // available_first_technicians[], available_second_technicians[], 
        // available_third_technicians[], all_available_technicians[]
        foreach ($presences as $period_name => $period) {
            $presences[$period_name]['available_first_technicians'] = [];
            $presences[$period_name]['available_second_technicians'] = [];
            $presences[$period_name]['available_third_technicians'] = [];
            $presences[$period_name]['all_available_technicians'] = [];
        }

        // For each user
        foreach ($users as $user_id => $user) {
            // SQL - Get User_presences for this user
            $user_presences = $this->presences_model->getPresencesUser($user_id);

            if ($user_presences === null) {
                continue;
            }

            // For each presence
            foreach ($user_presences as $presence_name => $presence_value) {
                // Convert presence name to period name
                $period_name = str_replace('presence_', '', $presence_name);
                $period_name = str_replace('_', '-', $period_name);

                if (!isset($presences[$period_name])) {
                    continue;
                }

                // Compare User_presence for this presence
                // SWITCH(presence)
                switch ($presence_value) {
                    case TechnicianPresence::ABSENT->value:
                        // CASE Absent: CONTINUE
                        continue 2;

                    case TechnicianPresence::PRESENT->value:
                        // CASE Present:
                        // Add this user to
                        // available_first_technicians[], available_second_technicians[], 
                        // available_third_technicians[] for this presence
                        $presences[$period_name]['available_first_technicians'][] = $user_id;
                        $presences[$period_name]['available_second_technicians'][] = $user_id;
                        $presences[$period_name]['available_third_technicians'][] = $user_id;
                        break;

                    case TechnicianPresence::PARTLY_ABSENT->value:
                        // CASE Partially Absent:
                        // Add this user to available_third_technicians[] for this presence
                        $presences[$period_name]['available_third_technicians'][] = $user_id;
                        break;
                }

                // Add this user to all_available_technicians[] for this presence
                $presences[$period_name]['all_available_technicians'][] = $user_id;

                // Increment available_periods_counter for this user
                $users[$user_id]['available_periods_counter']++;
            }
        }

        return $presences;
    }

    /**
     * Checks if presences have been updated recently (within the last 7 days)
     * 
     * This method determines if planning should be regenerated
     * due to recent presence modifications.
     * 
     * @param int $days Number of days to check (default: 7)
     * @return bool True if at least one presence has been updated, false otherwise
     */
    public function hasPresencesBeenUpdatedRecently($days = 7)
    {
        $recently_updated_presences = $this->presences_model->getPresencesUpdatedWithinDays($days);
        return !empty($recently_updated_presences);
    }

    /**
     * Gets current week's periods (removing off periods)
     * 
     * @return array Array of current week's periods
     */
    public function getCurrentWeekPeriodsOn()
    {
        // Initialize periods[] array
        $periods = [];

        // Get current week's periods
        $current_monday = strtotime('monday this week');
        
        $current_week = [
            'monday' => $current_monday,
            'tuesday' => strtotime('+1 day', $current_monday),
            'wednesday' => strtotime('+2 days', $current_monday),
            'thursday' => strtotime('+3 days', $current_monday),
            'friday' => strtotime('+4 days', $current_monday)
        ];
        
        foreach ($current_week as $key => $day) {
            $periods += [
                substr($key, 0, 3) . '-m1' => [
                    'start' => strtotime(date('Y-m-d', $day) . ' 08:00:00'),
                    'end' => strtotime(date('Y-m-d', $day) . ' 10:00:00')
                ],
                substr($key, 0, 3) . '-m2' => [
                    'start' => strtotime(date('Y-m-d', $day) . ' 10:00:00'),
                    'end' => strtotime(date('Y-m-d', $day) . ' 12:00:00')
                ],
                substr($key, 0, 3) . '-a1' => [
                    'start' => strtotime(date('Y-m-d', $day) . ' 12:45:00'),
                    'end' => strtotime(date('Y-m-d', $day) . ' 14:45:00')
                ],
                substr($key, 0, 3) . '-a2' => [
                    'start' => strtotime(date('Y-m-d', $day) . ' 15:00:00'),
                    'end' => strtotime(date('Y-m-d', $day) . ' 16:57:00')
                ]
            ];
        }

        // SQL - Remove off periods (holidays)
        $holidays_data = $this->holidays_model->getHolidays();

        foreach ($holidays_data as $holiday) {
            foreach ($periods as $period_name => $period) {
                // If period is within a holiday period
                if ($period['start'] >= strtotime($holiday['start_date_holiday']) && 
                    $period['end'] <= strtotime($holiday['end_date_holiday'])) {
                    unset($periods[$period_name]);
                }
            }
        }

        return $periods;
    }

    /**
     * Checks if planning can be copied from current week
     * 
     * Planning can only be copied if:
     * - Current planning exists and is not empty
     * - No presences have been updated in the last 7 days
     * - Number of periods in current week matches next week
     * 
     * @return bool True if planning can be copied, false otherwise
     */
    public function canPlanningBeCopiedFromCurrentWeek()
    {
        // SQL - Get current week's planning
        $current_week_planning = $this->planning_model->getPlanningData();
        
        // If current planning is empty, cannot copy (must generate)
        if (empty($current_week_planning)) {
            return false;
        }
        
        // Check if presences have been updated in the last 7 days
        if ($this->hasPresencesBeenUpdatedRecently(7)) {
            return false;
        }
        
        // Check if number of periods matches
        $current_week_periods = $this->getCurrentWeekPeriodsOn();
        $next_week_periods = $this->getNextWeekPeriodsOn();
        
        if (count($current_week_periods) !== count($next_week_periods)) {
            return false;
        }
        
        // If all conditions are met, can copy
        return true;
    }

    /**
     * Copies current week's planning to next week
     * 
     * This method maps current week's periods to next week's periods
     * taking into account holidays and off periods.
     * 
     * @return array Array of periods with copied assignments
     */
    public function copyCurrentWeekPlanningToNextWeek()
    {
        // Get periods from both weeks
        $current_week_periods = $this->getCurrentWeekPeriodsOn();
        $next_week_periods = $this->getNextWeekPeriodsOn();
        
        // Get current week's planning
        $current_week_planning = $this->planning_model->getPlanningData();
        
        // Mapping period names: current week -> next week
        $period_mapping = [];
        $current_period_names = array_keys($current_week_periods);
        $next_period_names = array_keys($next_week_periods);
        
        // Create mapping based on period order (same order = same period)
        for ($i = 0; $i < count($current_period_names) && $i < count($next_period_names); $i++) {
            $period_mapping[$current_period_names[$i]] = $next_period_names[$i];
        }
        
        // Create period mapping: current week period -> next week period
        // Based on period order (same position = same logical period)
        $current_period_list = array_values($current_period_names);
        $next_period_list = array_values($next_period_names);
        
        // Mapping des noms de colonnes SQL : planning_xxx -> nw_planning_xxx
        $sql_column_mapping = [
            'planning_mon_m1' => 'nw_planning_mon_m1',
            'planning_mon_m2' => 'nw_planning_mon_m2',
            'planning_mon_a1' => 'nw_planning_mon_a1',
            'planning_mon_a2' => 'nw_planning_mon_a2',
            'planning_tue_m1' => 'nw_planning_tue_m1',
            'planning_tue_m2' => 'nw_planning_tue_m2',
            'planning_tue_a1' => 'nw_planning_tue_a1',
            'planning_tue_a2' => 'nw_planning_tue_a2',
            'planning_wed_m1' => 'nw_planning_wed_m1',
            'planning_wed_m2' => 'nw_planning_wed_m2',
            'planning_wed_a1' => 'nw_planning_wed_a1',
            'planning_wed_a2' => 'nw_planning_wed_a2',
            'planning_thu_m1' => 'nw_planning_thu_m1',
            'planning_thu_m2' => 'nw_planning_thu_m2',
            'planning_thu_a1' => 'nw_planning_thu_a1',
            'planning_thu_a2' => 'nw_planning_thu_a2',
            'planning_fri_m1' => 'nw_planning_fri_m1',
            'planning_fri_m2' => 'nw_planning_fri_m2',
            'planning_fri_a1' => 'nw_planning_fri_a1',
            'planning_fri_a2' => 'nw_planning_fri_a2',
        ];
        
        // Create reverse mapping: logical period (mon-m1) -> SQL column
        $period_to_current_col = [];
        $period_to_next_col = [];
        
        foreach ($sql_column_mapping as $current_col => $next_col) {
            // Convert planning_mon_m1 -> mon-m1
            $period_key = str_replace(['planning_', 'nw_planning_'], '', $current_col);
            $period_key = str_replace('_', '-', $period_key);
            $period_to_current_col[$period_key] = $current_col;
            $period_to_next_col[$period_key] = $next_col;
        }
        
        // Prepare data for insertion into tbl_nw_planning
        $nw_planning_data = [];
        
        foreach ($current_week_planning as $planning_entry) {
            $nw_entry = [
                'fk_user_id' => $planning_entry['fk_user_id']
            ];
            
            // Initialize all next week columns to null
            foreach ($sql_column_mapping as $current_col => $next_col) {
                $nw_entry[$next_col] = null;
            }
            
            // Copy values for periods that exist in both weeks
            for ($i = 0; $i < count($current_period_list) && $i < count($next_period_list); $i++) {
                $current_period = $current_period_list[$i];
                $next_period = $next_period_list[$i];
                
                // Find corresponding SQL columns
                if (isset($period_to_current_col[$current_period]) && isset($period_to_next_col[$next_period])) {
                    $current_col = $period_to_current_col[$current_period];
                    $next_col = $period_to_next_col[$next_period];
                    
                    // Copy value if it exists
                    if (isset($planning_entry[$current_col])) {
                        $nw_entry[$next_col] = $planning_entry[$current_col];
                    }
                }
            }
            
            $nw_planning_data[] = $nw_entry;
        }
        
        // Clear nw_planning table before inserting new data
        $this->db->table('tbl_nw_planning')->truncate();
        
        // Insert copied data
        if (!empty($nw_planning_data)) {
            $this->nw_planning_model->insertBatch($nw_planning_data);
        }
        
        // Return next week's periods with assignments
        // Build return array in expected format (same format as generateNextWeekPlanningAttribution)
        $result_periods = [];
        
        // Get next week's planning from database
        $nw_planning_from_db = $this->nw_planning_model->getNwPlanningData();
        
        // Build period -> SQL columns mapping
        $period_to_col = [];
        foreach ($sql_column_mapping as $current_col => $next_col) {
            $period_key = str_replace(['planning_', 'nw_planning_'], '', $current_col);
            $period_key = str_replace('_', '-', $period_key);
            $period_to_col[$period_key] = $next_col;
        }
        
        // Initialize all periods with start and end
        foreach ($next_week_periods as $period_name => $period_info) {
            $result_periods[$period_name] = [
                'start' => $period_info['start'],
                'end' => $period_info['end']
            ];
            
            // Extract assignments for this period from database
            if (isset($period_to_col[$period_name])) {
                $period_col = $period_to_col[$period_name];
                
                foreach ($nw_planning_from_db as $planning_row) {
                    $assignment_value = is_array($planning_row) ? ($planning_row[$period_col] ?? null) : ($planning_row->$period_col ?? null);
                    
                    if (!empty($assignment_value)) {
                        // Values in database are 1, 2, 3 for first, second, third technician
                        if ($assignment_value == 1) {
                            $user_id = is_array($planning_row) ? $planning_row['fk_user_id'] : $planning_row->fk_user_id;
                            $result_periods[$period_name]['first_technician'] = $user_id;
                        } elseif ($assignment_value == 2) {
                            $user_id = is_array($planning_row) ? $planning_row['fk_user_id'] : $planning_row->fk_user_id;
                            $result_periods[$period_name]['second_technician'] = $user_id;
                        } elseif ($assignment_value == 3) {
                            $user_id = is_array($planning_row) ? $planning_row['fk_user_id'] : $planning_row->fk_user_id;
                            $result_periods[$period_name]['third_technician'] = $user_id;
                        }
                    }
                }
            }
        }
        
        return $result_periods;
    }

    /**
     * Generates next week's planning assignment
     * 
     * @param array $periods Array of periods
     * @param array $users Array of users
     * @param array $presences Array of presences
     * @return array Array of periods with assignments
     */
    public function generateNextWeekPlanningAttribution($periods, $users, $presences)
    {
        // Note: Period count verification is done in generateNextWeekPlanning()
        // via canPlanningBeCopiedFromCurrentWeek(). This function is only called if planning
        // cannot be copied, so we proceed directly with assignment.

        // Sort users_ids[] with least assignments possible, then role priority at the beginning of array
        uasort($users, function($a, $b) {
            // First by number of assignments (ascending)
            $a_assignations = $a['technician_1_assignation']['assigned'] + 
                            $a['technician_2_assignation']['assigned'] + 
                            $a['technician_3_assignation']['assigned'];
            $b_assignations = $b['technician_1_assignation']['assigned'] + 
                            $b['technician_2_assignation']['assigned'] + 
                            $b['technician_3_assignation']['assigned'];
            
            if ($a_assignations !== $b_assignations) {
                return $a_assignations <=> $b_assignations;
            }
            
            // Then by role priority (descending)
            return $b['role_priority'] <=> $a['role_priority'];
        });

        // Sort presences[] with least all_available_technician at the beginning of array
        uasort($presences, function($a, $b) {
            return count($a['all_available_technicians']) <=> count($b['all_available_technicians']);
        });

        // For each presence
        foreach ($presences as $period_name => $presence) {
            // SWITCH(all_available_technicians) count
            $available_count = count($presence['all_available_technicians']);

            switch (true) {
                case $available_count === 0:
                    // CASE 0: CONTINUE
                    continue 2;

                case $available_count >= 1 && $available_count <= 3:
                    // CASE 1 to 3:
                    $this->assignCaseOneToThreeTechnicians($period_name, $presence, $users, $periods);
                    break;

                case $available_count >= 4:
                    // CASE 4 or more:
                    $this->assignCaseFourOrMoreTechnicians($period_name, $presence, $users, $periods);
                    break;
            }
        }

        return $periods;
    }

    /**
     * Assignment for case of 1 to 3 available technicians
     * 
     * @param string $period_name Period name
     * @param array $presence Presence data for this period
     * @param array $users Array of users (modified by reference)
     * @param array $periods Array of periods (modified by reference)
     */
    protected function assignCaseOneToThreeTechnicians($period_name, $presence, &$users, &$periods)
    {
        $available_first = $presence['available_first_technicians'];
        $available_second = $presence['available_second_technicians'];
        $available_third = $presence['available_third_technicians'];

        // For each available_first_technician
        foreach ($available_first as $user_id) {
            // Is max assignment of user_id as first_technician reached?
            if ($users[$user_id]['technician_1_assignation']['assigned'] >= 
                $users[$user_id]['technician_1_assignation']['max']) {
                continue;
            }

            // Assign this user_id to this period as first_technician
            $periods[$period_name]['first_technician'] = $user_id;
            $users[$user_id]['technician_1_assignation']['assigned']++;

            // Is available_second_technician >= 2?
            if (count($available_second) >= 2) {
                // For each available_second_technician
                foreach ($available_second as $second_user_id) {
                    // Is user_id equal to first_technician in this period?
                    if ($second_user_id == $user_id) {
                        continue;
                    }

                    // Is max assignment of user_id as second_technician reached?
                    if ($users[$second_user_id]['technician_2_assignation']['assigned'] >= 
                        $users[$second_user_id]['technician_2_assignation']['max']) {
                        continue;
                    }

                    // Assign this user_id to this period as second_technician
                    $periods[$period_name]['second_technician'] = $second_user_id;
                    $users[$second_user_id]['technician_2_assignation']['assigned']++;

                    // Is available_third_technician >= 3?
                    if (count($available_third) >= 3) {
                        // For each available_third_technician
                        foreach ($available_third as $third_user_id) {
                            // Is user_id equal to first_technician or second_technician in this period?
                            if ($third_user_id == $user_id || $third_user_id == $second_user_id) {
                                continue;
                            }

                            // Is max assignment of user_id as third_technician reached?
                            if ($users[$third_user_id]['technician_3_assignation']['assigned'] >= 
                                $users[$third_user_id]['technician_3_assignation']['max']) {
                                continue;
                            }

                            // Assign this user_id to this period as third_technician
                            $periods[$period_name]['third_technician'] = $third_user_id;
                            $users[$third_user_id]['technician_3_assignation']['assigned']++;
                            break 2; // Exit both foreach loops
                        }
                    }
                    break; // Exit second_technician loop
                }
            }
            break; // Exit first_technician loop
        }
    }

    /**
     * Assignment for case of 4+ available technicians
     * 
     * @param string $period_name Period name
     * @param array $presence Presence data for this period
     * @param array $users Array of users (modified by reference)
     * @param array $periods Array of periods (modified by reference)
     */
    protected function assignCaseFourOrMoreTechnicians($period_name, $presence, &$users, &$periods)
    {
        // For each technician_X_assignation (where X = 1 to 3)
        for ($technician_num = 1; $technician_num <= 3; $technician_num++) {
            // Get list of possible users for this assignment
            $available_list = [];
            
            switch ($technician_num) {
                case 1:
                    $available_list = $presence['available_first_technicians'];
                    break;
                case 2:
                    $available_list = $presence['available_second_technicians'];
                    break;
                case 3:
                    $available_list = $presence['available_third_technicians'];
                    break;
            }

            // For each user_id
            foreach ($available_list as $user_id) {
                // Check if user is not already assigned to this period
                if (isset($periods[$period_name]['first_technician']) && 
                    $periods[$period_name]['first_technician'] == $user_id) {
                    continue;
                }
                if (isset($periods[$period_name]['second_technician']) && 
                    $periods[$period_name]['second_technician'] == $user_id) {
                    continue;
                }
                if (isset($periods[$period_name]['third_technician']) && 
                    $periods[$period_name]['third_technician'] == $user_id) {
                    continue;
                }

                // Is max assignment of user_id as X_technician reached?
                $assignation_key = 'technician_' . $technician_num . '_assignation';
                if ($users[$user_id][$assignation_key]['assigned'] >= 
                    $users[$user_id][$assignation_key]['max']) {
                    continue;
                }

                // Assign this user_id to this period as X_technician
                switch ($technician_num) {
                    case 1:
                        $periods[$period_name]['first_technician'] = $user_id;
                        break;
                    case 2:
                        $periods[$period_name]['second_technician'] = $user_id;
                        break;
                    case 3:
                        $periods[$period_name]['third_technician'] = $user_id;
                        break;
                }

                $users[$user_id][$assignation_key]['assigned']++;

                // Have we completed all technician_X_assignation assignments?
                break; // Move to next technician
            }
        }
    }
}
