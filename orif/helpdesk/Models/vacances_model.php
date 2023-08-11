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

class Vacances_model extends \CodeIgniter\Model
{
    protected $table = 'tbl_vacances';
    protected $primaryKey = 'id_vacances';
    protected $allowedFields = ['nom_vacances','date_debut_vacances','date_fin_vacances'];
    protected $validationRules;
    protected $validationMessages;


    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules = [];

        $this->validationMessages = [];

        parent::__construct($db, $validation);
    }

    
    /*
    ** getHolidays function
    **
    ** Get all holidays data
    **
    */
    public function getHolidays()
    {
        // Retrieve all holidays data, ordered by date
        $vacances_data = $this->orderBy('date_debut_vacances', 'ASC')->findAll();

        return $vacances_data;
    }


    /*
    ** getHolidays function
    **
    ** Get a specific holiday entry
    **
    */
    public function getHoliday($id_holiday)
    {
        // Retrieve holiday entry
        $vacances_data = $this->where('id_vacances', $id_holiday)->first();

        return $vacances_data;
    }
}