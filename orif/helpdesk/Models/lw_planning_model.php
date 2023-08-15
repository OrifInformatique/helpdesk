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

class Lw_planning_model extends \CodeIgniter\Model
{
    protected $table = 'tbl_lw_planning';
    protected $primaryKey = 'id_lw_planning';
    protected $allowedFields = 
    [
        'fk_user_id',
        'lw_planning_mon_m1', 'lw_planning_mon_m2', 'lw_planning_mon_a1', 'lw_planning_mon_a2',
        'lw_planning_tue_m1', 'lw_planning_tue_m2', 'lw_planning_tue_a1', 'lw_planning_tue_a2',
        'lw_planning_wed_m1', 'lw_planning_wed_m2', 'lw_planning_wed_a1', 'lw_planning_wed_a2',
        'lw_planning_thr_m1', 'lw_planning_thr_m2', 'lw_planning_thr_a1', 'lw_planning_thr_a2',
        'lw_planning_fri_m1', 'lw_planning_fri_m2', 'lw_planning_fri_a1', 'lw_planning_fri_a2'
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
        $lw_planning_data = $this->findAll();

        return $lw_planning_data;
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
        $lw_planning_data_by_user = $this->join('tbl_user_data','tbl_lw_planning.fk_user_id = tbl_user_data.fk_user_id')->findAll();
        
        return $lw_planning_data_by_user;
    }
}