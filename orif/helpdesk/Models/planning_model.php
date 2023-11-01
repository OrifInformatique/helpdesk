<?php

/**
 * Model for tbl_planning table
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

namespace Helpdesk\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class Planning_model extends \CodeIgniter\Model
{
    protected $table = 'tbl_planning';
    protected $primaryKey = 'id_planning';
    protected $allowedFields =
    [
        'fk_user_id',
        'planning_mon_m1', 'planning_mon_m2', 'planning_mon_a1', 'planning_mon_a2',
        'planning_tue_m1', 'planning_tue_m2', 'planning_tue_a1', 'planning_tue_a2',
        'planning_wed_m1', 'planning_wed_m2', 'planning_wed_a1', 'planning_wed_a2',
        'planning_thu_m1', 'planning_thu_m2', 'planning_thu_a1', 'planning_thu_a2',
        'planning_fri_m1', 'planning_fri_m2', 'planning_fri_a1', 'planning_fri_a2'
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
     * Get all planning data
     * 
     * @return array
     * 
     */
    public function getPlanningData()
    {
        $planning_data = $this->findAll();

        return $planning_data;
    }


    /**
     * Get all users having a role in current week planning
     * 
     * @return array
     * 
     */
    public function getPlanningDataByUser()
    {
        $planning_data_by_user = $this->join('tbl_user_data','tbl_planning.fk_user_id = tbl_user_data.fk_user_id')
                                      ->join('user','tbl_planning.fk_user_id = user.id')
                                      ->orderBy('last_name_user_data', 'ASC')
                                      ->findAll();

        return $planning_data_by_user;
    }


    /**
     * Check if a user has a role in the current week planning (if he has a planning entry)
     * 
     * @param int $user_id ID of a specific user
     * 
     * @return string|void
     * 
     */
    public function checkUserOwnsPlanning($user_id)
    {
        $planning_data = $this->where('fk_user_id', $user_id)->findAll();

        // If there is a result, it means the user already has a planning. Prevent duplicate creation
        if (!empty($planning_data))
        {
            $data['error'] = lang('Helpdesk.err_technician_already_has_schedule');

            return $data['error'];
        }
    }


    /**
     * Get the planning ID of a specific user planning entry
     * 
     * @param int $user_id ID of a specific user
     * 
     * @return array
     * 
     */
    public function getPlanning($user_id)
    {
        $planning_entry = $this->where('fk_user_id', $user_id)->first();

        return $planning_entry;
    }


    /**
     * Get all users assigned to a specific period
     * 
     * @param string $period Name of the period
     * 
     * @return array|NULL
     * 
     */
    public function getTechniciansOnPeriod($period)
    {
        $results = $this->join('tbl_user_data', 'tbl_planning.fk_user_id = tbl_user_data.fk_user_id')
                        ->where($period.' IS NOT NULL')
                        ->orderBy($period, 'ASC')
                        ->findAll();

        if(!empty($results))
        {
            foreach($results as $row)
            {
                $technicians[] = $row;
            }

            return $technicians;
        }

        else
        {
            return null;
        }
    }
}