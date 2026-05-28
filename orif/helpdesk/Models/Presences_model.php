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
        'presence_fri_m1', 'presence_fri_m2', 'presence_fri_a1', 'presence_fri_a2',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = null; // No created_at field
    protected $updatedField = 'updated_at';
    protected $validationRules;
    protected $validationMessages;
    
    /**
     * Simulated reference date for tests (format Y-m-d)
     * If set, will be used instead of current date in getPresencesUpdatedWithinDays
     */
    protected static $simulatedReferenceDate = null;


    public function __construct(?ConnectionInterface &$db = null, ?ValidationInterface $validation = null)
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
            ->join('user','tbl_presences.fk_user_id = user.id')
            ->orderBy('last_name_user_data', 'ASC')
            ->orderBy('first_name_user_data', 'ASC')
            ->findAll();

        return $all_presences_data;
    }


    /**
     * Get the primary key of the user presences entry
     * 
     * @param int $user_id ID of a specific user
     * 
     * @return array|object|null The presence record or null if not found
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

    /**
     * Get the user id of a presence entry
     * 
     * @param int $id_presnece ID of a presence entry
     * 
     * @return int
     * 
     */
    public function getUserId($id_presence)
    {
        $presence_data = $this->select('fk_user_id')->where('id_presence', $id_presence)->first();

        return $presence_data['fk_user_id'];
    }

    /**
     * Get a user presences on a specific period
     * 
     * @param int $user_id
     * @param string $planning_period
     * 
     * @return int
     * 
     */
    public function getTechnicianPresenceInSpecificPeriod($user_id, $planning_period)
    {
        $presence_period = 'presence_'.substr($planning_period, -6);

        $presence = $this->select($presence_period)->where('fk_user_id', $user_id)->get()->getFirstRow();

        return (int) $presence->$presence_period;
    }

    /**
     * Get IDs of all users that have presences
     * 
     * @return array
     * 
     */
    public function getUsersIdsInPresences()
    {
        $users_presences_ids = $this->select('fk_user_id')->get()->getResultArray();
        $users_presences_ids = array_column($users_presences_ids, 'fk_user_id');
        
        return $users_presences_ids;
    }

    /**
     * Check if a presence was updated within the last N days
     * 
     * @param int $user_id ID of the user
     * @param int $days Number of days to check (default: 7)
     * 
     * @return bool True if updated within the last N days, false otherwise
     * 
     */
    public function wasUpdatedWithinDays($user_id, $days = 7)
    {
        $presence = $this->where('fk_user_id', $user_id)->first();
        
        if (empty($presence)) {
            return false;
        }

        // Handle different data formats (object, array, string)
        $updated_at = null;
        if (is_object($presence)) {
            $updated_at = $presence->updated_at ?? null;
        } elseif (is_array($presence)) {
            $updated_at = $presence['updated_at'] ?? null;
        }

        if (empty($updated_at)) {
            return false;
        }

        // Convert to timestamp
        if (is_string($updated_at)) {
            $timestamp = strtotime($updated_at);
        } elseif (is_object($updated_at) && method_exists($updated_at, 'getTimestamp')) {
            $timestamp = $updated_at->getTimestamp();
        } else {
            return false;
        }
        
        $days_ago = strtotime("-{$days} days");
        
        return $timestamp >= $days_ago;
    }

    /**
     * Get all presences that were updated within the last N days
     * 
     * @param int $days Number of days to check (default: 7)
     * 
     * @return array Array of presence records
     * 
     */
    /**
     * Sets the simulated reference date for tests
     * 
     * @param string|null $date Date in Y-m-d format or null to use current date
     */
    public static function setSimulatedReferenceDate($date)
    {
        self::$simulatedReferenceDate = $date;
    }
    
    /**
     * Get presences updated within the last N days
     * 
     * @param int $days Number of days to check (default: 7)
     * 
     * @return array Array of presence records
     * 
     */
    public function getPresencesUpdatedWithinDays($days = 7)
    {
        if (self::$simulatedReferenceDate !== null) {
            // Use simulated date for tests
            $date_threshold = date('Y-m-d H:i:s', strtotime(self::$simulatedReferenceDate . " -{$days} days"));
        } else {
            // Use current date (normal behavior)
            $date_threshold = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        }
        
        return $this->where('updated_at >=', $date_threshold)
                    ->findAll();
    }
}