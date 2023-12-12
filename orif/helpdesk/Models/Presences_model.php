<?php

/**
 * Model for tbl_presences table
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

namespace Helpdesk\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class Presences_model extends \CodeIgniter\Model
{
    protected $table = 'tbl_presences';
    protected $primaryKey = 'id_presence';
    protected $allowedFields =
    [
        'fk_user_id',
        'presence_mon_m1', 'presence_mon_m2', 'presence_mon_a1', 'presence_mon_a2',
        'presence_tue_m1', 'presence_tue_m2', 'presence_tue_a1', 'presence_tue_a2',
        'presence_wed_m1', 'presence_wed_m2', 'presence_wed_a1', 'presence_wed_a2',
        'presence_thu_m1', 'presence_thu_m2', 'presence_thu_a1', 'presence_thu_a2',
        'presence_fri_m1', 'presence_fri_m2', 'presence_fri_a1', 'presence_fri_a2'
    ];
    protected $validationRules;
    protected $validationMessages;


    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules = [];

        $this->validationMessages = [];

        parent::__construct($db, $validation);
    }


    /**
     * Get all presences data form all users
     * 
     * @return array
     * 
     */
    public function getAllPresences()
    {
        $all_presences_data = $this->join('tbl_user_data', 'tbl_presences.fk_user_id = tbl_user_data.fk_user_id')
                                    ->orderBy('last_name_user_data', 'ASC')
                                    ->findAll();

        return $all_presences_data;
    }


    /**
     * Get the primary key of the user presences entry
     * 
     * @param int $user_id ID of a specific user
     * 
     * @return int
     * 
     */
    public function getPresenceId($user_id)
    {
        $id_presence = $this->where('fk_user_id', $user_id)->first();

        return $id_presence;
    }


    /**
     * Get user presences
     * 
     * @param int $user_id ID of a specific user
     * 
     * @return array|NULL
     * 
     */
    public function getPresencesUser($user_id)
    {
        $query = $this->where('fk_user_id', $user_id)->get();

        if (!empty($query->getRow())) {

            $result = $query->getRow();

            $user_presences_data = 
            [
                'presence_mon_m1' => $result->presence_mon_m1,
                'presence_mon_m2' => $result->presence_mon_m2,
                'presence_mon_a1' => $result->presence_mon_a1,
                'presence_mon_a2' => $result->presence_mon_a2,

                'presence_tue_m1' => $result->presence_tue_m1,
                'presence_tue_m2' => $result->presence_tue_m2,
                'presence_tue_a1' => $result->presence_tue_a1,
                'presence_tue_a2' => $result->presence_tue_a2,

                'presence_wed_m1' => $result->presence_wed_m1,
                'presence_wed_m2' => $result->presence_wed_m2,
                'presence_wed_a1' => $result->presence_wed_a1,
                'presence_wed_a2' => $result->presence_wed_a2,

                'presence_thu_m1' => $result->presence_thu_m1,
                'presence_thu_m2' => $result->presence_thu_m2,
                'presence_thu_a1' => $result->presence_thu_a1,
                'presence_thu_a2' => $result->presence_thu_a2,

                'presence_fri_m1' => $result->presence_fri_m1,
                'presence_fri_m2' => $result->presence_fri_m2,
                'presence_fri_a1' => $result->presence_fri_a1,
                'presence_fri_a2' => $result->presence_fri_a2
            ];

            return $user_presences_data;
        }

        return null;
    }
}