<?php

/**
 * Model for tbl_planning table
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
    protected $allowedFields = [
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

    
    /*
    ** getPlanningData function
    **
    ** Get all planning data
    **
    */
    public function getPlanningData()
    {
        // Retrieve all planning data
        $nw_planning_data = $this->findAll();

        return $nw_planning_data;
    }


    /*
    ** getPlanningDataByUser function
    **
    ** Get all users having a planning
    **
    */
    public function getPlanningDataByUser()
    {
        // Join with "tbl_user_data" table to retrieve both planning and user data
        $nw_planning_data_by_user = $this->join('tbl_user_data','tbl_nw_planning.fk_user_id = tbl_user_data.fk_user_id')->findAll();
        
        return $nw_planning_data_by_user;
    }


    /*
    ** checkUserOwnsPlanning function
    **
    ** Check if a user owns a planning
    **
    */ 
    public function checkUserOwnsPlanning($user_id)
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


    /*
    ** updatePlanningData function
    **
    ** Update user planning records in the database
    **
    */
    public function updateUsersPlanning($id_planning, $data_to_update)
    {      
        $this->update($id_planning, $data_to_update);
    }
}