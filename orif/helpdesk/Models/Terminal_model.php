<?php

/**
 * Model for tbl_terminal table
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

namespace Helpdesk\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class Terminal_model extends \CodeIgniter\Model
{
    protected $table = 'tbl_terminal';
    protected $primaryKey = 'id_terminal';
    protected $allowedFields = ['tech_available_terminal'];
    protected $validationRules;
    protected $validationMessages;


    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules = [];

        $this->validationMessages = [];

        parent::__construct($db, $validation);
    }


    /**
     * Get all technicans availabilities
     * 
     * @return array
     * 
     */
    public function getTerminalData()
    {
        $terminal_data = $this->findAll();
        
        return $terminal_data;
    }


    /**
     * Updates an availability
     * 
     * @param int $id Role updated
     * @param bool $value Determines whether the technician is available or not
     * 
     * @return void
     * 
     */
    public function updateAvailability($id, $value)
    {
        $data =
        [
            'tech_available_terminal' => $value
        ];

        $this->update($id, $data);
    }


    /**
     * Resets availabilities with default values
     * 
     * @return void
     * 
     */
    public function resetAvailabilities()
    {
        $data = 
        [
            'tech_available_terminal' => "true"
        ];

        $this->update([1,2,3], $data);
    }
}