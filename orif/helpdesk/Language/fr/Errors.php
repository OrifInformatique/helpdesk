<?php
/**
 * French translations for errors
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (http://www.orif.ch)
 * 
 */
return[
    /* User errors */
    'unauthorized' => 'Vous n\'avez pas les droits pour effectuer cette action.',

    /* Presences errors */
    'no_technician_presences'       => 'Aucun technicien n\'a de présences.',
    'invalid_technician_selected'   => 'Le technicien sélectionné est invalide.', // Also a terminal error
    
    /* Planning errors */
    'no_technician_assigned'                    => 'Aucun technicien n\'est assigné au planning.',
    'role_duplicates_on_periods'                => 'Plusieurs techniciens sont assignés aux mêmes rôles durant ces périodes :<br>',
    'technician_is_absent_on_periods'           => 'Le technicien %s est absent durant ces périodes :<br>',
    'technician_must_be_assigned_to_schedule'   => 'Chaque technicien doit être assigné au minimum à un rôle pendant une période.',
    'unvalid_planning_type'                     => 'Erreur de planning.',
    
    /* Holidays errors */
    'no_holidays' => 'Aucune période fériée n\'existe.',
    
    /* Terminal errors */
    'no_technician_available'   => 'Aucun technicien n\'est disponible.',
    'no_technician_selected'    => 'Aucun technicien n\'a été sélectionné.',

    /* Planning generation errors */
    'planning_generation'                       => 'Une erreur est survenue lors de la génération du planning.',
    'planning_generation_absent_technicians'    => 'Génération du planning impossible : Tous les techniciens sont absents durant toute la semaine.',
    'planning_generation_no_period'             => 'Génération du planning impossible : Aucune période de travail dans la semaine.',
    'planning_generation_no_technician'         => 'Génération du planning impossible : Aucun technicien n\'a de présences.',    
    'weeks_shift'                               => 'Une erreur est survenue lors du déplacement des semaines.',
    
    /* Confirm action errors */
    'action_unvalid' => 'L\'action à exécuter est inconnue.'
];