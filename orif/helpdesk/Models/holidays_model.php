<?php

/**
 * Model for tbl_holidays table
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

    
    /**
     * Get all holidays data
     *
     * @return array $holidays_data
     * 
     */
    public function getHolidays()
    {
        // Retrieve all holidays data, ordered by date
        $holidays_data = $this->orderBy('start_date_holiday', 'ASC')->findAll();

        return $holidays_data;
    }


    /**
     * Get a specific holiday entry
     * 
     * @param int $id_holiday ID of a specific holiday entry
     * 
     * @return array $holiday_data
     * 
     */
    public function getHoliday($id_holiday)
    {
        // Retrieve holiday entry
        $holiday_data = $this->where('id_holiday', $id_holiday)->first();

        return $holiday_data;
    }

    /**
     * Checks if we are in a holiday period
     * 
     * @return bool $return
     * 
     */
    public function areWeInHolidays()
    {
        // Récupérer les données des vacances
        $holidays_data = $this->getHolidays();

        $current_time = time();

        foreach ($holidays_data as $holiday) 
        {
            // If we are in a day off, return true
            if ($current_time >= strtotime($holiday['start_date_holiday']) && $current_time <= strtotime($holiday['end_date_holiday']))
            {
                return true;
            }
        }

        return false;
    }
}