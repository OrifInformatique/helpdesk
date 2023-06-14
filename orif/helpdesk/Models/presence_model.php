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

class Presence_model extends \CodeIgniter\Model
{
    protected $table = 'presence';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fk_user_id', 'fk_lundi_m1', 'fk_lundi_m2', 'fk_lundi_a1', 'fk_lundi_a2', 'fk_mardi_m1', 'fk_mardi_m2', 'fk_mardi_a1', 'fk_mardi_a2', 'fk_mercredI_m1', 'fk_mercredI_m2', 'fk_mercredI_a1', 'fk_mercredI_a2', 'fk_jeudi_m1', 'fk_jeudi_m2', 'fk_jeudi_a1', 'fk_jeudi_a2', 'fk_vendredi_m1', 'fk_vendredi_m2', 'fk_vendredi_a1', 'fk_vendredi_a2'];
    protected $validationRules;
    protected $validationMessages;


    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules = [];

        $this->validationMessages = [];

        parent::__construct($db, $validation);
    }

    public function checkPresenceExistence($user_id)
    {
        $query = $this->where('fk_user_id', $user_id)->get();
        $result = $query->getResult();

        $count = count($result);

        return ($count > 0);
    }


    
}
