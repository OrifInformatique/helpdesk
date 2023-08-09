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

class Planning_model extends \CodeIgniter\Model
{
    protected $table = 'tbl_planning';
    protected $primaryKey = 'id_planning';
    protected $allowedFields = [
        'fk_user_id',
        'planning_lundi_m1', 'planning_lundi_m2', 'planning_lundi_a1', 'planning_lundi_a2',
        'planning_mardi_m1', 'planning_mardi_m2', 'planning_mardi_a1', 'planning_mardi_a2',
        'planning_mercredi_m1', 'planning_mercredi_m2', 'planning_mercredi_a1', 'planning_mercredi_a2',
        'planning_jeudi_m1', 'planning_jeudi_m2', 'planning_jeudi_a1', 'planning_jeudi_a2',
        'planning_vendredi_m1', 'planning_vendredi_m2', 'planning_vendredi_a1', 'planning_vendredi_a2'
    ];
    protected $validationRules;
    protected $validationMessages;


    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules = [];

        $this->validationMessages = [];

        parent::__construct($db, $validation);
    }

    // Récupère toutes les données du planning
    public function getPlanningData()
    {
        // Récolte toutes les données sur le planning
        $planning_data = $this->findAll();

        // Retourne le tableau
        return $planning_data;
    }

    // Récupère tous les utilisateur qui ont un planning
    public function getPlanningDataByUser()
    {
        // Jointure avec la table "tbl_user_data", pour récupérer les données du planning et de l'utilisateur
        $this->join('tbl_user_data','tbl_planning.fk_user_id = tbl_user_data.fk_user_id');
        $result = $this->findAll();
        
        return $result;
    }

    // Vérifie si un utilisateur possède un planning
    public function checkUserOwnsPlanning($user_id)
    {
        // Récolte l'ID de l'utilisateur via le planning de l'utilisateur, si existants
        $planning_data = $this->where('fk_user_id', $user_id)->findAll();
        
        // Si le résultat retourne qqch, signifie que l'utilisateur possède déjà un planning. Empêche de créer un doublon
        if (!empty($planning_data))
        {
            // Message d'erreur
            $data['error'] = lang('Helpdesk.err_technician_already_has_schedule');

            return $data['error'];
        }

        // Sinon, exécute la suite du code normalement
    }
    
    public function updatePlanningData($updated_planning_data)
    {
        // Met à jour tous les enregistrements dans la base de données
        $this->update(null, $updated_planning_data);
    }
}
