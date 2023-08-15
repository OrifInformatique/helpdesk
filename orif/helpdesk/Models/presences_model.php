<?php

/**
 * Model for tbl_presences table
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
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


    /*
    ** getPresenceId function
    **
    ** Get the primary key of the user presences
    **
    */
    public function getPresenceId($user_id)
    {
        // Retrieve the primary key of the user presences
        $presence_data = $this->where('fk_user_id', $user_id)->first();

        return $presence_data;
    }


    /*
    ** getPresencesUser function
    **
    ** Get user presences
    **
    */
    public function getPresencesUser($user_id)
    {
        // Retrieve user presences
        $query = $this->where('fk_user_id', $user_id)->get();

        // If data exists
        if (!empty($query->getRow())) {

            // Get data
            $result = $query->getRow();

            // Presences table
            $presences_data = 
            [
                'lundi_debut_matin' => $result->presence_mon_m1,
                'lundi_fin_matin' => $result->presence_mon_m2,
                'lundi_debut_apres_midi' => $result->presence_mon_a1,
                'lundi_fin_apres_midi' => $result->presence_mon_a2,

                'mardi_debut_matin' => $result->presence_tue_m1,
                'mardi_fin_matin' => $result->presence_tue_m2,
                'mardi_debut_apres_midi' => $result->presence_tue_a1,
                'mardi_fin_apres_midi' => $result->presence_tue_a2,

                'mercredi_debut_matin' => $result->presence_wed_m1,
                'mercredi_fin_matin' => $result->presence_wed_m2,
                'mercredi_debut_apres_midi' => $result->presence_wed_a1,
                'mercredi_fin_apres_midi' => $result->presence_wed_a2,

                'jeudi_debut_matin' => $result->presence_thu_m1,
                'jeudi_fin_matin' => $result->presence_thu_m2,
                'jeudi_debut_apres_midi' => $result->presence_thu_a1,
                'jeudi_fin_apres_midi' => $result->presence_thu_a2,

                'vendredi_debut_matin' => $result->presence_fri_m1,
                'vendredi_fin_matin' => $result->presence_fri_m2,
                'vendredi_debut_apres_midi' => $result->presence_fri_a1,
                'vendredi_fin_apres_midi' => $result->presence_fri_a2
            ];

            return $presences_data;
        }

        // Otherwise, return null
        return null;
    }
}
