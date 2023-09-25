<?php

/**
 * Model for tbl_users_data table
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Helpdesk\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class User_Data_model extends \CodeIgniter\Model
{
    protected $table = 'tbl_user_data';
    protected $primaryKey = 'id_user_data';
    protected $allowedFields = ['fk_user_id','last_name_user_data','first_name_user_data','initials_user_data','photo_user_data'];
    protected $validationRules;
    protected $validationMessages;


    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules = [];

        $this->validationMessages = [];

        parent::__construct($db, $validation);
    }

    /**
     * Get all data about all users
     * 
     * @return array $users_data
     * 
     */
    public function getUsersData()
    {
        // Join with "tbl_user_data" table, to retrieve all user data
        $users_data = $this->join('user', 'user.id = tbl_user_data.fk_user_id')->findAll();
        
        return $users_data;
    }

    /**
     * Get data from specified user
     * 
     * @param int $user_id ID of a specific user
     * 
     * @return array $user_data
     * 
     */
    public function getUserData($user_id)
    {
        // Join with "tbl_user_data" table, to retrieve all user data
        $user_data = $this->join('user', 'user.id = tbl_user_data.fk_user_id')->where('id', $user_id)->findAll();
        
        return $user_data;
    }
}