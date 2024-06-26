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

use Helpdesk\Models\Presences_model;

class User_Data_model extends \CodeIgniter\Model
{
    protected $table = 'tbl_user_data';
    protected $primaryKey = 'id_user_data';
    protected $allowedFields = ['fk_user_id','last_name_user_data','first_name_user_data','initials_user_data','photo_user_data'];
    protected $validationRules;
    protected $validationMessages;

    protected $presences_model;


    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules = [];

        $this->validationMessages = [];

        $this->presences_model = new presences_model();

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
        $users_data = $this->join('user', 'user.id = tbl_user_data.fk_user_id')->where('user.archive', NULL)->orderBy('last_name_user_data', 'ASC')->findAll();

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
     * Get the full name of a specific user
     * 
     * @param int $user_id ID of the user
     * 
     * @return array
     * 
     */
    public function getUserFullName($user_id)
    {
        $user_full_name = $this->select('first_name_user_data, last_name_user_data')
                          ->join('user', 'user.id = tbl_user_data.fk_user_id')
                          ->where('id', $user_id)->first();

        return $user_full_name;
    }


    /**
     * Get the photo of a specific user
     * 
     * @param int $user_id ID of the user
     * 
     * @return array
     * 
     */
    public function getUserPhoto($user_id)
    {
        $user_photo = $this->select('photo_user_data')->join('user', 'user.id = tbl_user_data.fk_user_id')->where('id', $user_id)->first();

        return $user_photo['photo_user_data'];
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


    /**
     * Get all users that not have presences
     * 
     * @return array
     * 
     */
    public function getUsersWithoutPresences()
    {
        $users_presences_ids = $this->presences_model->getUsersIdsInPresences();

        if(empty($users_presences_ids))
            $users_presences_ids = [''];

        $result = $this
            ->whereNotIn('tbl_user_data.fk_user_id', $users_presences_ids)
            ->orderBy('last_name_user_data', 'ASC')
            ->get()
            ->getResultArray();

        if(empty($result))
            return NULL;

        foreach($result as $row)
            $users_without_presences[] = $row;
    
        return $users_without_presences;
    }
}