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

class Presence_model extends \CodeIgniter\Model
{
    protected $table = 'presence';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fk_user_id', 'fk_lundi_m1', 'fk_lundi_m2', 'fk_lundi_a1', 'fk_lundi_a2', 'fk_mardi_m1', 'fk_mardi_m2', 'fk_mardi_a1', 'fk_mardi_a2', 'fk_mercredi_m1', 'fk_mercredi_m2', 'fk_mercredi_a1', 'fk_mercredi_a2', 'fk_jeudi_m1', 'fk_jeudi_m2', 'fk_jeudi_a1', 'fk_jeudi_a2', 'fk_vendredi_m1', 'fk_vendredi_m2', 'fk_vendredi_a1', 'fk_vendredi_a2'];
    protected $validationRules;
    protected $validationMessages;


    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules = [];

        $this->validationMessages = [];

        parent::__construct($db, $validation);
    }

    public function getPresenceId($user_id)
    {
        // Requête SQL pour reprendre depuis la base de donnée la clé primaire des présences de l'utilisateur
        $presence_data = $this->where('fk_user_id', $user_id)->first();

        return $presence_data;
    }

    public function getPresencesUser($user_id)
    {
        // Requête SQL pour reprendre depuis la base de donnée les présences de l'utilisateur
        $query = $this->db->table('presence')
            ->select('*')
            ->where('fk_user_id', $user_id)
            ->get();

        // S'il y a des données 
        if (!empty($query->getRow())) {

            // Retourne les données
            $result = $query->getRow();

            // Tableau des présences pour envoyer sur la page du formulaire
            $presences_data = [

                'lundi_debut_matin' => $result->fk_lundi_m1,
                'lundi_fin_matin' => $result->fk_lundi_m2,
                'lundi_debut_apres_midi' => $result->fk_lundi_a1,
                'lundi_fin_apres_midi' => $result->fk_lundi_a2,

                'mardi_debut_matin' => $result->fk_mardi_m1,
                'mardi_fin_matin' => $result->fk_mardi_m2,
                'mardi_debut_apres_midi' => $result->fk_mardi_a1,
                'mardi_fin_apres_midi' => $result->fk_mardi_a2,

                'mercredi_debut_matin' => $result->fk_mercredi_m1,
                'mercredi_fin_matin' => $result->fk_mercredi_m2,
                'mercredi_debut_apres_midi' => $result->fk_mercredi_a1,
                'mercredi_fin_apres_midi' => $result->fk_mercredi_a2,

                'jeudi_debut_matin' => $result->fk_jeudi_m1,
                'jeudi_fin_matin' => $result->fk_jeudi_m2,
                'jeudi_debut_apres_midi' => $result->fk_jeudi_a1,
                'jeudi_fin_apres_midi' => $result->fk_jeudi_a2,

                'vendredi_debut_matin' => $result->fk_vendredi_m1,
                'vendredi_fin_matin' => $result->fk_vendredi_m2,
                'vendredi_debut_apres_midi' => $result->fk_vendredi_a1,
                'vendredi_fin_apres_midi' => $result->fk_vendredi_a2

            ];

            return $presences_data;
        }

        return null;
    }
}
