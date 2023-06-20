<?php

/**
 * Model User_model this represents the user table
 *
 * @author      Orif (ViDi,HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Helpdesk\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class Planning_model extends \CodeIgniter\Model
{
    protected $table = 'planning';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fk_user_id', 'fk_username', 'planning_lundi_m1', 'planning_lundi_m2', 'planning_lundi_a1', 'planning_lundi_a2', 'planning_mardi_m1', 'planning_mardi_m2', 'planning_mardi_a1', 'planning_mardi_a2', 'planning_mercredi_m1', 'planning_mercredi_m2', 'planning_mercredi_a1', 'planning_mercredi_a2', 'planning_jeudi_m1', 'planning_jeudi_m2', 'planning_jeudi_a1', 'planning_jeudi_a2', 'planning_vendredi_m1', 'planning_vendredi_m2', 'planning_vendredi_a1', 'planning_vendredi_a2'];
    protected $validationRules;
    protected $validationMessages;


    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules = [];

        $this->validationMessages = [];

        parent::__construct($db, $validation);
    }

    public function getPlanningData()
    {
        // Requête SQL pour reprendre depuis la base de donnée la clé primaire des présences de l'utilisateur
        $planning_data = $this->findAll();

        return $planning_data;
    }
}