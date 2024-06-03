<?php
/**
 * English translations for errors
 * 
 * @author      Orif (KoYo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (http://www.orif.ch)
 * 
 */
return[
    /* User errors */
    'unauthorized' => 'You do not have the authorizations to perform this action.',

    /* Presences errors */
    'no_technician_presences'       => 'No technician have availability.',
    'invalid_technician_selected'   => 'The chosen technician is not valid.', // Also a terminal error
    
    /* Planning errors */
    'no_technician_assigned'                    => 'No technician is assigned on schedule.',
    'role_duplicates_on_periods'                => 'Multiple technicians are assigned with the same role during those periods :<br>',
    'technician_is_absent_on_periods'           => 'The technician %s is unavailable during those periods :<br>',
    'technician_must_be_assigned_to_schedule'   => 'Every technician must be assigned at minimum one role during a period.',
    'unvalid_planning_type'                     => 'Schedule error.',
    
    /* Holidays errors */
    'no_holidays' => 'No holiday period exists.',
    
    /* Terminal errors */
    'no_technician_available'   => 'No technician is available.',
    'no_technician_selected'    => 'No technician has been selected.',

    /* Planning generation errors */
    'planning_generation'                       => 'An arror occured while generating the schedule.',
    'planning_generation_absent_technicians'    => 'Schedule generation impossible : All technicians absent during the whole week.',
    'planning_generation_no_period'             => 'Schedule generation impossible : No work period during the week.',
    'planning_generation_no_technician'         => 'Schedule generation impossible : No technician available.',    
    'weeks_shift'                               => 'An error occured while shifting weeks.',
    
    /* Confirm action errors */
    'action_unvalid' => 'The action to execute is unknown.'
];