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

class Holidays_model extends \CodeIgniter\Model
{
    protected $table = 'tbl_holidays';
    protected $primaryKey = 'id_holiday';
    protected $allowedFields = ['name_holiday','start_date_holiday','end_date_holiday'];
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
        $holidays_data = $this->orderBy('start_date_holiday', 'ASC')->findAll();

        return $holidays_data;
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
        $holiday_data = $this->where('id_holiday', $id_holiday)->first();

        return $holiday_data;
    }

    /*
    ** areWeInHolidays function
    **
    ** Get a specific holiday entry
    **
    */
    public function areWeInHolidays()
    {
        // Récupérer les données des vacances
        $holidays_data = $this->getHolidays();

        $current_time = time();

        $return = false;

        foreach ($holidays_data as $holiday) 
        {
            // If we are in a day off, return true
            if ($current_time >= strtotime($holiday['start_date_holiday']) && $current_time <= strtotime($holiday['end_date_holiday']))
            {
                $return = true;
                break;
            }
        }

        return $return;
    }
}