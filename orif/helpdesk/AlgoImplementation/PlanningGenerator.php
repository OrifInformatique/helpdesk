<?php

/**
 * Classe principale pour la génération automatique du planning de la semaine prochaine
 * 
 * Implémentation de l'algorithme décrit dans les fichiers Mermaid
 * 
 * @author      Orif
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Helpdesk\AlgoImplementation;

use Helpdesk\Models\Presences_model;
use Helpdesk\Models\Roles_model;
use Helpdesk\Models\Planning_model;
use Helpdesk\Models\Nw_planning_model;
use Helpdesk\Models\Holidays_model;
use Helpdesk\Models\User_data_model;
use Helpdesk\Enums\TechnicianPresence;
use Helpdesk\Enums\TechnicianAssignment;
use Helpdesk\Enums\PlanningPeriod;

class PlanningGenerator
{
    protected $presences_model;
    protected $roles_model;
    protected $planning_model;
    protected $nw_planning_model;
    protected $holidays_model;
    protected $user_data_model;
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->presences_model = new Presences_model();
        $this->roles_model = new Roles_model();
        $this->planning_model = new Planning_model();
        $this->nw_planning_model = new Nw_planning_model();
        $this->holidays_model = new Holidays_model();
        $this->user_data_model = new User_data_model();
    }

    /**
     * Fonction principale : génère le planning de la semaine prochaine
     * 
     * @return array Tableau des périodes avec les attributions
     */
    public function generateNextWeekPlanning()
    {
        // Initialiser les tableaux
        $periods = [];
        $users = [];
        $presences = [];

        // Obtenir les informations de calendrier de la semaine prochaine
        $periods = $this->getNextWeekPeriodsOn();

        // Obtenir les informations des utilisateurs
        $users = $this->getUsersPresentNextWeekWithThereRoles();

        // Obtenir les informations de présence
        $presences = $this->getUsersPresencesPerPeriods($periods, $users);

        // Vérifier si le planning peut être copié
        $canCopy = $this->canPlanningBeCopiedFromCurrentWeek();

        if ($canCopy) {
            // Copier le planning de la semaine actuelle
            return $periods;
        } else {
            // Exécuter la logique d'attribution
            $periods = $this->generateNextWeekPlanningAttribution($periods, $users, $presences);
            return $periods;
        }
    }

    /**
     * Récupère les périodes de la semaine prochaine (en supprimant les périodes off)
     * 
     * @return array Tableau des périodes de la semaine prochaine
     */
    public function getNextWeekPeriodsOn()
    {
        // Initialiser un tableau periods[]
        $periods = [];

        // Obtenir les périodes de la semaine prochaine
        $next_monday = strtotime('next monday');
        $next_week = [
            'monday' => $next_monday,
            'tuesday' => strtotime('+1 day', $next_monday),
            'wednesday' => strtotime('+2 days', $next_monday),
            'thursday' => strtotime('+3 days', $next_monday),
            'friday' => strtotime('+4 days', $next_monday)
        ];
        
        foreach ($next_week as $key => $day) {
            $periods += [
                substr($key, 0, 3) . '-m1' => [
                    'start' => strtotime(date('Y-m-d', $day) . ' 08:00:00'),
                    'end' => strtotime(date('Y-m-d', $day) . ' 10:00:00')
                ],
                substr($key, 0, 3) . '-m2' => [
                    'start' => strtotime(date('Y-m-d', $day) . ' 10:00:00'),
                    'end' => strtotime(date('Y-m-d', $day) . ' 12:00:00')
                ],
                substr($key, 0, 3) . '-a1' => [
                    'start' => strtotime(date('Y-m-d', $day) . ' 12:45:00'),
                    'end' => strtotime(date('Y-m-d', $day) . ' 14:45:00')
                ],
                substr($key, 0, 3) . '-a2' => [
                    'start' => strtotime(date('Y-m-d', $day) . ' 15:00:00'),
                    'end' => strtotime(date('Y-m-d', $day) . ' 16:57:00')
                ]
            ];
        }

        // SQL - Supprimer les périodes off (jours fériés)
        $holidays_data = $this->holidays_model->getHolidays();

        foreach ($holidays_data as $holiday) {
            foreach ($periods as $period_name => $period) {
                // Si la période est dans une période de jour férié
                if ($period['start'] >= strtotime($holiday['start_date_holiday']) && 
                    $period['end'] <= strtotime($holiday['end_date_holiday'])) {
                    unset($periods[$period_name]);
                }
            }
        }

        return $periods;
    }

    /**
     * Récupère les utilisateurs présents la semaine prochaine avec leurs rôles
     * 
     * @return array Tableau des utilisateurs avec leurs informations
     */
    public function getUsersPresentNextWeekWithThereRoles()
    {
        // Initialiser un tableau users[]
        $users = [];

        // SQL - Obtenir tous les rôles où priority_role n'est pas 0
        $roles_data = $this->roles_model->where('priority_role !=', 0)->findAll();

        // Pour chaque rôle
        foreach ($roles_data as $role) {
            // SQL - Obtenir tous les user_ids avec au moins 1 PRESENT ou PARTIALLY_ABSENT qui ont ce rôle
            $presence_fields = [
                'presence_mon_m1', 'presence_mon_m2', 'presence_mon_a1', 'presence_mon_a2',
                'presence_tue_m1', 'presence_tue_m2', 'presence_tue_a1', 'presence_tue_a2',
                'presence_wed_m1', 'presence_wed_m2', 'presence_wed_a1', 'presence_wed_a2',
                'presence_thu_m1', 'presence_thu_m2', 'presence_thu_a1', 'presence_thu_a2',
                'presence_fri_m1', 'presence_fri_m2', 'presence_fri_a1', 'presence_fri_a2'
            ];

            // Construire la requête pour trouver les utilisateurs avec ce rôle et au moins une présence
            $builder = $this->db->table('tbl_presences')
                ->select('tbl_presences.fk_user_id')
                ->distinct()
                ->join('tbl_user_data', 'tbl_presences.fk_user_id = tbl_user_data.fk_user_id', 'inner')
                ->join('user', 'tbl_presences.fk_user_id = user.id', 'inner')
                ->where('tbl_user_data.fk_role_id', $role['id_role'])
                ->where('user.archive', null);

            // Construire la condition OR pour au moins une présence PRESENT ou PARTIALLY_ABSENT
            $builder->groupStart();
            $first = true;
            foreach ($presence_fields as $field) {
                if ($first) {
                    $builder->groupStart()
                        ->where($field, TechnicianPresence::PRESENT->value)
                        ->orWhere($field, TechnicianPresence::PARTLY_ABSENT->value)
                        ->groupEnd();
                    $first = false;
                } else {
                    $builder->orGroupStart()
                        ->where($field, TechnicianPresence::PRESENT->value)
                        ->orWhere($field, TechnicianPresence::PARTLY_ABSENT->value)
                        ->groupEnd();
                }
            }
            $builder->groupEnd();

            $users_with_role = $builder->get()->getResultArray();

            // Pour chaque utilisateur
            foreach ($users_with_role as $user_data) {
                $user_id = $user_data['fk_user_id'];

                // Ajouter à cet utilisateur les tableaux :
                // role_priority, technician_1_assignation[0,0,0], technician_2_assignation[0,0,0], 
                // technician_3_assignation[0,0,0], available_periods_counter
                if (!isset($users[$user_id])) {
                    $users[$user_id] = [
                        'user_id' => $user_id,
                        'role_priority' => $role['priority_role'],
                        'technician_1_assignation' => [
                            'min' => $role['min_assignation_first_technician_role'],
                            'max' => $role['max_assignation_first_technician_role'],
                            'assigned' => 0
                        ],
                        'technician_2_assignation' => [
                            'min' => $role['min_assignation_second_technician_role'],
                            'max' => $role['max_assignation_second_technician_role'],
                            'assigned' => 0
                        ],
                        'technician_3_assignation' => [
                            'min' => $role['min_assignation_third_technician_role'],
                            'max' => $role['max_assignation_third_technician_role'],
                            'assigned' => 0
                        ],
                        'available_periods_counter' => 0
                    ];
                }
            }
        }

        return $users;
    }

    /**
     * Récupère les présences des utilisateurs par période
     * 
     * @param array $periods Tableau des périodes
     * @param array $users Tableau des utilisateurs (passé par référence pour modifier available_periods_counter)
     * @return array Tableau des présences enrichi avec les techniciens disponibles
     */
    public function getUsersPresencesPerPeriods($periods, &$users)
    {
        // Initialiser un tableau de presences[] qui est une copie de periods[]
        $presences = $periods;

        // Initialiser les tableaux dans chaque presences[]
        // available_first_technicians[], available_second_technicians[], 
        // available_third_technicians[], all_available_technicians[]
        foreach ($presences as $period_name => $period) {
            $presences[$period_name]['available_first_technicians'] = [];
            $presences[$period_name]['available_second_technicians'] = [];
            $presences[$period_name]['available_third_technicians'] = [];
            $presences[$period_name]['all_available_technicians'] = [];
        }

        // Pour chaque utilisateur
        foreach ($users as $user_id => $user) {
            // SQL - Obtenir les User_presences pour cet utilisateur
            $user_presences = $this->presences_model->getPresencesUser($user_id);

            if ($user_presences === null) {
                continue;
            }

            // Pour chaque présence
            foreach ($user_presences as $presence_name => $presence_value) {
                // Convertir le nom de présence en nom de période
                $period_name = str_replace('presence_', '', $presence_name);
                $period_name = str_replace('_', '-', $period_name);

                if (!isset($presences[$period_name])) {
                    continue;
                }

                // Comparer User_presence pour cette présence
                // SWITCH(presence)
                switch ($presence_value) {
                    case TechnicianPresence::ABSENT->value:
                        // CASE Absent: CONTINUE
                        continue 2;

                    case TechnicianPresence::PRESENT->value:
                        // CASE Present:
                        // Ajouter cet utilisateur à
                        // available_first_technicians[], available_second_technicians[], 
                        // available_third_technicians[] pour cette présence
                        $presences[$period_name]['available_first_technicians'][] = $user_id;
                        $presences[$period_name]['available_second_technicians'][] = $user_id;
                        $presences[$period_name]['available_third_technicians'][] = $user_id;
                        break;

                    case TechnicianPresence::PARTLY_ABSENT->value:
                        // CASE Partially Absent:
                        // Ajouter cet utilisateur à available_third_technicians[] pour cette présence
                        $presences[$period_name]['available_third_technicians'][] = $user_id;
                        break;
                }

                // Ajouter cet utilisateur à all_available_technicians[] pour cette présence
                $presences[$period_name]['all_available_technicians'][] = $user_id;

                // Incrémenter pour cet utilisateur available_periods_counter
                $users[$user_id]['available_periods_counter']++;
            }
        }

        return $presences;
    }

    /**
     * Vérifie si le planning peut être copié depuis la semaine actuelle
     * 
     * @return bool True si le planning peut être copié, false sinon
     */
    public function canPlanningBeCopiedFromCurrentWeek()
    {
        // SQL - Obtenir les présences de la semaine actuelle
        $current_week_planning = $this->planning_model->getPlanningData();
        
        // TODO: Vérifier si possible de copier la semaine actuelle vers la semaine prochaine
        // Pour l'instant, retourner false pour forcer la génération
        return false;
    }

    /**
     * Génère l'attribution du planning de la semaine prochaine
     * 
     * @param array $periods Tableau des périodes
     * @param array $users Tableau des utilisateurs
     * @param array $presences Tableau des présences
     * @return array Tableau des périodes avec les attributions
     */
    public function generateNextWeekPlanningAttribution($periods, $users, $presences)
    {
        // Obtenir le planning de la semaine actuelle
        $current_week_planning = $this->planning_model->getPlanningData();
        $current_week_period_count = count($current_week_planning);
        $next_week_period_count = count($periods);

        // Le nombre de périodes de la semaine actuelle === le nombre de périodes de la semaine prochaine ?
        if ($current_week_period_count === $next_week_period_count) {
            // Copier le planning de la semaine actuelle
            // TODO: Implémenter la copie du planning
            return $periods;
        }

        // Trier users_ids[] avec le moins d'assignations possible, puis la priorité de rôle au début du tableau
        uasort($users, function($a, $b) {
            // D'abord par nombre d'assignations (ascendant)
            $a_assignations = $a['technician_1_assignation']['assigned'] + 
                            $a['technician_2_assignation']['assigned'] + 
                            $a['technician_3_assignation']['assigned'];
            $b_assignations = $b['technician_1_assignation']['assigned'] + 
                            $b['technician_2_assignation']['assigned'] + 
                            $b['technician_3_assignation']['assigned'];
            
            if ($a_assignations !== $b_assignations) {
                return $a_assignations <=> $b_assignations;
            }
            
            // Ensuite par priorité de rôle (descendant)
            return $b['role_priority'] <=> $a['role_priority'];
        });

        // Trier presences[] avec le moins de all_available_technician au début du tableau
        uasort($presences, function($a, $b) {
            return count($a['all_available_technicians']) <=> count($b['all_available_technicians']);
        });

        // Pour chaque présence
        foreach ($presences as $period_name => $presence) {
            // SWITCH(all_available_technicians) count
            $available_count = count($presence['all_available_technicians']);

            switch (true) {
                case $available_count === 0:
                    // CASE 0: CONTINUE
                    continue 2;

                case $available_count >= 1 && $available_count <= 3:
                    // CASE 1 to 3:
                    $this->assignCaseOneToThreeTechnicians($period_name, $presence, $users, $periods);
                    break;

                case $available_count >= 4:
                    // CASE 4 or more:
                    $this->assignCaseFourOrMoreTechnicians($period_name, $presence, $users, $periods);
                    break;
            }
        }

        return $periods;
    }

    /**
     * Attribution pour le cas de 1 à 3 techniciens disponibles
     * 
     * @param string $period_name Nom de la période
     * @param array $presence Données de présence pour cette période
     * @param array $users Tableau des utilisateurs (modifié par référence)
     * @param array $periods Tableau des périodes (modifié par référence)
     */
    protected function assignCaseOneToThreeTechnicians($period_name, $presence, &$users, &$periods)
    {
        $available_first = $presence['available_first_technicians'];
        $available_second = $presence['available_second_technicians'];
        $available_third = $presence['available_third_technicians'];

        // Pour chaque available_first_technician
        foreach ($available_first as $user_id) {
            // L'assignation max de user_id comme first_technician est-elle atteinte ?
            if ($users[$user_id]['technician_1_assignation']['assigned'] >= 
                $users[$user_id]['technician_1_assignation']['max']) {
                continue;
            }

            // Assigner ce user_id à cette période comme first_technician
            $periods[$period_name]['first_technician'] = $user_id;
            $users[$user_id]['technician_1_assignation']['assigned']++;

            // available_second_technician est-il >= 2 ?
            if (count($available_second) >= 2) {
                // Pour chaque available_second_technician
                foreach ($available_second as $second_user_id) {
                    // user_id est-il égal à first_technician dans cette période ?
                    if ($second_user_id == $user_id) {
                        continue;
                    }

                    // L'assignation max de user_id comme second_technician est-elle atteinte ?
                    if ($users[$second_user_id]['technician_2_assignation']['assigned'] >= 
                        $users[$second_user_id]['technician_2_assignation']['max']) {
                        continue;
                    }

                    // Assigner ce user_id à cette période comme second_technician
                    $periods[$period_name]['second_technician'] = $second_user_id;
                    $users[$second_user_id]['technician_2_assignation']['assigned']++;

                    // available_third_technician est-il >= 3 ?
                    if (count($available_third) >= 3) {
                        // Pour chaque available_third_technician
                        foreach ($available_third as $third_user_id) {
                            // user_id est-il égal à first_technician ou second_technician dans cette période ?
                            if ($third_user_id == $user_id || $third_user_id == $second_user_id) {
                                continue;
                            }

                            // L'assignation max de user_id comme third_technician est-elle atteinte ?
                            if ($users[$third_user_id]['technician_3_assignation']['assigned'] >= 
                                $users[$third_user_id]['technician_3_assignation']['max']) {
                                continue;
                            }

                            // Assigner ce user_id à cette période comme third_technician
                            $periods[$period_name]['third_technician'] = $third_user_id;
                            $users[$third_user_id]['technician_3_assignation']['assigned']++;
                            break 2; // Sortir des deux boucles foreach
                        }
                    }
                    break; // Sortir de la boucle second_technician
                }
            }
            break; // Sortir de la boucle first_technician
        }
    }

    /**
     * Attribution pour le cas de 4+ techniciens disponibles
     * 
     * @param string $period_name Nom de la période
     * @param array $presence Données de présence pour cette période
     * @param array $users Tableau des utilisateurs (modifié par référence)
     * @param array $periods Tableau des périodes (modifié par référence)
     */
    protected function assignCaseFourOrMoreTechnicians($period_name, $presence, &$users, &$periods)
    {
        // Pour chaque technician_X_assignation (où X = 1 à 3)
        for ($technician_num = 1; $technician_num <= 3; $technician_num++) {
            // Obtenir la liste des utilisateurs possibles pour cette assignation
            $available_list = [];
            
            switch ($technician_num) {
                case 1:
                    $available_list = $presence['available_first_technicians'];
                    break;
                case 2:
                    $available_list = $presence['available_second_technicians'];
                    break;
                case 3:
                    $available_list = $presence['available_third_technicians'];
                    break;
            }

            // Pour chaque user_id
            foreach ($available_list as $user_id) {
                // Vérifier si l'utilisateur n'est pas déjà assigné à cette période
                if (isset($periods[$period_name]['first_technician']) && 
                    $periods[$period_name]['first_technician'] == $user_id) {
                    continue;
                }
                if (isset($periods[$period_name]['second_technician']) && 
                    $periods[$period_name]['second_technician'] == $user_id) {
                    continue;
                }
                if (isset($periods[$period_name]['third_technician']) && 
                    $periods[$period_name]['third_technician'] == $user_id) {
                    continue;
                }

                // L'assignation max de user_id comme X_technician est-elle atteinte ?
                $assignation_key = 'technician_' . $technician_num . '_assignation';
                if ($users[$user_id][$assignation_key]['assigned'] >= 
                    $users[$user_id][$assignation_key]['max']) {
                    continue;
                }

                // Assigner ce user_id à cette période comme X_technician
                switch ($technician_num) {
                    case 1:
                        $periods[$period_name]['first_technician'] = $user_id;
                        break;
                    case 2:
                        $periods[$period_name]['second_technician'] = $user_id;
                        break;
                    case 3:
                        $periods[$period_name]['third_technician'] = $user_id;
                        break;
                }

                $users[$user_id][$assignation_key]['assigned']++;

                // Avons-nous terminé toutes les assignations technician_X_assignation ?
                break; // Passer au technicien suivant
            }
        }
    }
}
