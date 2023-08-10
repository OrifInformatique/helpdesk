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

class Planning_model extends \CodeIgniter\Model
{
    protected $table = 'tbl_planning';
    protected $primaryKey = 'id_planning';
    protected $allowedFields = [
        'fk_user_id',
        'planning_lundi_m1', 'planning_lundi_m2', 'planning_lundi_a1', 'planning_lundi_a2',
        'planning_mardi_m1', 'planning_mardi_m2', 'planning_mardi_a1', 'planning_mardi_a2',
        'planning_mercredi_m1', 'planning_mercredi_m2', 'planning_mercredi_a1', 'planning_mercredi_a2',
        'planning_jeudi_m1', 'planning_jeudi_m2', 'planning_jeudi_a1', 'planning_jeudi_a2',
        'planning_vendredi_m1', 'planning_vendredi_m2', 'planning_vendredi_a1', 'planning_vendredi_a2'
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
        $planning_data = $this->findAll();

        return $planning_data;
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
        $planning_data_by_user = $this->join('tbl_user_data','tbl_planning.fk_user_id = tbl_user_data.fk_user_id')->findAll();
        
        return $planning_data_by_user;
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
        $planning_data = $this->where('fk_user_id', $user_id)->findAll();
        
        // If there is a result, it means the user already has a planning. Prevent duplicate creation
        if (!empty($planning_data))
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