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
use Helpdesk\Models\Roles_model;

class User_Data_model extends \CodeIgniter\Model
{
    protected $table = 'tbl_user_data';
    protected $primaryKey = 'id_user_data';
    protected $allowedFields = [
        'fk_user_id',
        'fk_role_id',
        'last_name_user_data',
        'first_name_user_data',
        'initials_user_data',
        'photo_user_data'
    ];
    protected $validationRules;
    protected $validationMessages;

    protected $presences_model;
    protected $roles_model;


    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules = [];

        $this->validationMessages = [];

        $this->presences_model = new presences_model();
        $this->roles_model = new Roles_model();

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
        $users_data = $this->join('user', 'user.id = tbl_user_data.fk_user_id')->where('user.archive', NULL)->orderBy('last_name_user_data', 'ASC')->orderBy('first_name_user_data', 'ASC')->findAll();

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
            ->orderBy('first_name_user_data', 'ASC')
            ->get()
            ->getResultArray();

        if(empty($result))
            return NULL;

        foreach($result as $row)
            $users_without_presences[] = $row;
    
        return $users_without_presences;
    }


    /**
     * Get the role of a specific user
     * 
     * @param int $user_id ID of the user
     * 
     * @return array|null Role data or null if user has no role or doesn't exist
     * 
     */
    public function getUserRole($user_id)
    {
        $user_data = $this->where('fk_user_id', $user_id)->first();

        if(empty($user_data) || empty($user_data->fk_role_id))
            return null;

        $role = $this->roles_model->getRoleByID($user_data->fk_role_id);

        return $role;
    }


    /**
     * Associate or update a role for a specific user
     * 
     * @param int $user_id ID of the user
     * @param int $role_id ID of the role to associate
     * 
     * @return bool True on success, false otherwise
     * 
     */
    public function setUserRole($user_id, $role_id)
    {
        // Vérifier que le rôle existe
        $role = $this->roles_model->getRoleByID($role_id);
        if(empty($role))
            return false;

        // Vérifier que l'utilisateur existe dans tbl_user_data
        $user_data = $this->where('fk_user_id', $user_id)->first();

        if(empty($user_data))
            return false;

        // Mettre à jour le rôle de l'utilisateur
        $data = ['fk_role_id' => $role_id];
        $result = $this->update($user_data->id_user_data, $data);

        return $result;
    }
}