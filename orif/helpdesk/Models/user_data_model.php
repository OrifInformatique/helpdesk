<?php

/**
 * welcome_message view
 *
 * @author      Orif (BlAl)
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
    protected $allowedFields = ['fk_user_id','nom_user_data','prenom_user_data','initiales_user_data','photo_user_data'];
    protected $validationRules;
    protected $validationMessages;


    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules = [];

        $this->validationMessages = [];

        parent::__construct($db, $validation);
    }

    public function getUsersData()
    {
        // Jointure avec la table "tbl_user_data", pour récupérer toutes les données de l'utilisateurs
        $this->join('user', 'user.id = tbl_user_data.fk_user_id');
        $result = $this->findAll();
        
        return $result;
    }
}