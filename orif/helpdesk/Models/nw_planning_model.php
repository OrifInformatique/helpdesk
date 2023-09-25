<?php

/**
 * Model for tbl_nw_planning table
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Helpdesk\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class Nw_planning_model extends \CodeIgniter\Model
{
    protected $table = 'tbl_nw_planning';
    protected $primaryKey = 'id_nw_planning';
    protected $allowedFields = 
    [
        'fk_user_id',
        'nw_planning_mon_m1', 'nw_planning_mon_m2', 'nw_planning_mon_a1', 'nw_planning_mon_a2',
        'nw_planning_tue_m1', 'nw_planning_tue_m2', 'nw_planning_tue_a1', 'nw_planning_tue_a2',
        'nw_planning_wed_m1', 'nw_planning_wed_m2', 'nw_planning_wed_a1', 'nw_planning_wed_a2',
        'nw_planning_thr_m1', 'nw_planning_thr_m2', 'nw_planning_thr_a1', 'nw_planning_thr_a2',
        'nw_planning_fri_m1', 'nw_planning_fri_m2', 'nw_planning_fri_a1', 'nw_planning_fri_a2'
    ];
    protected $validationRules;
    protected $validationMessages;


    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules = [];

        $this->validationMessages = [];

        parent::__construct($db, $validation);
    }

    
    /**
     * Get all planning data from next week
     * 
     * @return array $nw_planning_data
     * 
     */
    public function getNwPlanningData()
    {
        // Retrieve all planning data
        $nw_planning_data = $this->findAll();

        return $nw_planning_data;
    }


    /**
     * Get all users having a role in next week planning
     * 
     * @return array $nw_planning_data_by_user
     * 
     */
    public function getNwPlanningDataByUser()
    {
        // Join with "tbl_user_data" and "user" tables to retrieve both planning and user data
        $nw_planning_data_by_user = $this->join('tbl_user_data','tbl_nw_planning.fk_user_id = tbl_user_data.fk_user_id')
                                         ->join('user','tbl_nw_planning.fk_user_id = user.id')
                                         ->orderBy('last_name_user_data', 'ASC')
                                         ->findAll();       
                                          
        return $nw_planning_data_by_user;
    }


    /**
     * Check if a user has a role in the next week planning (if he has a nw_planning entry)
     * 
     * @param int $user_id ID of a specific user
     * 
     * @return array $data[error], if user already have a planning entry
     * 
     */ 
    public function checkUserOwnsNwPlanning($user_id)
    {
        // Retrieve user's ID from the user's planning data, if it exists
        $nw_planning_data = $this->where('fk_user_id', $user_id)->findAll();
        
        // If there is a result, it means the user already has a planning. Prevent duplicate creation
        if (!empty($nw_planning_data))
        {
            // Error message
            $data['error'] = lang('Helpdesk.err_technician_already_has_schedule');

            return $data['error'];
        }

        // Otherwise, proceed with the rest of the code
    }


    /**
     * Get the planning ID of a specific user planning entry
     * 
     * @param int $user_id ID of a specific user
     * 
     * @return array $nw_planning_data
     * 
     */
    public function getNwPlanning($user_id)
    {
        $nw_planning_data = $this->where('fk_user_id', $user_id)->first();

        return $nw_planning_data;
    }
}