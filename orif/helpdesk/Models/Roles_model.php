<?php

/**
 * Model for tbl_roles table
 * 
 * @author      Orif (ViDi,HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

namespace Helpdesk\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class Roles_model extends \CodeIgniter\Model
{
    protected $table = 'tbl_roles';
    protected $primaryKey = 'id_role';
    protected $allowedFields = [
        'name_role',
        'priority_role',
        'min_assignation_first_technician_role',
        'max_assignation_first_technician_role',
        'min_assignation_second_technician_role',
        'max_assignation_second_technician_role',
        'min_assignation_third_technician_role',
        'max_assignation_third_technician_role'
    ];
    protected $validationRules;
    protected $validationMessages;


    public function __construct(?ConnectionInterface &$db = null, ?ValidationInterface $validation = null)
    {
        $this->validationRules = [];

        $this->validationMessages = [];

        parent::__construct($db, $validation);
    }


    /**
     * Get all roles
     * 
     * @return array
     * 
     */
    public function getRoles()
    {
        return $this->orderBy('priority_role', 'ASC')->findAll();
    }


    /**
     * Get a specific role by ID
     * 
     * @param int $id ID of the role
     * 
     * @return array|null
     * 
     */
    public function getRoleByID($id)
    {
        $role = $this->where('id_role', $id)->first();

        return $role;
    }
}

