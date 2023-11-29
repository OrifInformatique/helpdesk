<?php

/**
 * Model for tbl_lw_planning table
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
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

    
    /**
     * Get all planning data from last week
     * 
     * @return array
     * 
     */
    public function getPlanningData()
    {
        $lw_planning_data = $this->findAll();

        return $lw_planning_data;
    }


    /**
     * Get all users having a role in last week planning
     * 
     * @return array
     * 
     */
    public function getPlanningDataByUser()
    {
        $lw_planning_data_by_user = $this->join('tbl_user_data','tbl_lw_planning.fk_user_id = tbl_user_data.fk_user_id')
                                         ->join('user','tbl_lw_planning.fk_user_id = user.id')
                                         ->orderBy('last_name_user_data', 'ASC')
                                         ->findAll();

        return $lw_planning_data_by_user;
    }
}