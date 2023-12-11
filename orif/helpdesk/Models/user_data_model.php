<?php

/**
 * Model for tbl_users_data table
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
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
     * @return array
     * 
     */
    public function getUsersData()
    {
        $users_data = $this->join('user', 'user.id = tbl_user_data.fk_user_id')->findAll();

        return $users_data;
    }


    /**
     * Get data from a specific user
     * 
     * @param int $user_id ID of the user
     * 
     * @return array
     * 
     */
    public function getUserData($user_id)
    {
        $user_data = $this->join('user', 'user.id = tbl_user_data.fk_user_id')->where('id', $user_id)->findAll();

        return $user_data;
    }


    /**
     * Get data from a specific user
     * 
     * @param int $user_id ID of the user
     * 
     * @return array
     * 
     */
    public function getUserDataId($user_id)
    {
        $id_user_data = $this->select('id_user_data')->where('fk_user_id', $user_id)->first();

        return $id_user_data;
    }
}