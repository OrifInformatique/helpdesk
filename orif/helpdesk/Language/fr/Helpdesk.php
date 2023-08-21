<?php
/**
 * French translations for planning page
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (http://www.orif.ch)
 */

return[

    /*
    ** PAGE TITLES
    */

    'ttl_presences'             => 'Présences de l\'apprenti',
    'ttl_add_technician'        => 'Ajouter un technicien',
    'ttl_technician_menu'       => 'Menu du technicien',
    'ttl_planning'              => 'Planning de la semaine',
    'ttl_lw_planning'           => 'Planning de la semaine dernière',
    'ttl_nw_planning'           => 'Planning de la semaine prochaine',
    'ttl_update_planning'       => 'Modifier le planning',
    'ttl_update_nw_planning'    => 'Modifier le planning de la semaine prochaine',
    'ttl_holiday'               => 'Vacances et périodes fériées',
    'ttl_add_holiday'           => 'Ajouter une préiode fériée',
    'ttl_update_holiday'        => 'Modifier une période fériée',
    'ttl_delete_confirmation'   => 'Confirmation de suppression',


    /*
    ** ERROR MESSAGES
    */

    'err_technician_already_has_schedule'           => 'Le technicien renseigné possède déjà un planning',
    'err_technician_must_be_assigned_to_schedule'   => 'Chaque technicien doit être assigné au minimum à un rôle pendant une période',
    'err_dates_are_incoherent'                      => 'Les dates entrées sont incohérentes',
    'err_unfound_planning_type'                     => 'Erreur de planning, veuillez réessayer',


    /*
    ** SUCCESS MESSAGES
    */

    'scs_presences_updated'             => 'Présences modifiées avec succès',
    'scs_technician_added_to_schedule'  => 'Technicien ajouté au planning avec succès',
    'scs_technician_deleted'            => 'Le technicien a été supprimé du planning avec succès',
    'scs_planning_updated'              => 'Planning de la semaine modifié avec succès',
    'scs_holiday_updated'               => 'La période de vacances a été modifiée avec succès',
    'scs_holiday_deleted'               => 'La période de vacances a été supprimée avec succès',

    
    /*
    ** BUTTONS
    */

    'btn_presences'         => 'Vos présences',
    'btn_add_technician'    => 'Ajouter un technicien',
    'btn_edit_planning'     => 'Modifier le planning',
    'btn_holiday'           => 'Liste des vacances',
    'btn_add_holiday'       => 'Ajouter des vacances',
    'btn_back'              => 'Retour',
    'btn_save'              => 'Enregistrer',
    'btn_delete'            => 'Supprimer',
    'btn_cancel'            => 'Annuler',
    'btn_last_week'         => '◄◄ Semaine dernière',
    'btn_next_week'         => 'Semaine suivante ►►',

    'btn_delete_from_planning' => 'Supprimer du planning',


    /*
    ** ROLES
    */

    'role_1' => '1 - Technicien d\'abstreinte',
    'role_2' => '2 - Technicien de backup',
    'role_3' => '3 - Technicien de réserve',


    /*
    ** PRESENCES
    */

    'present'       => 'Présent',
    'partly_absent' => 'Absent en partie',
    'absent'        => 'Absent',


    /*
    ** WEEKDAYS
    */

    'monday'    => 'Lundi',
    'tuesday'   => 'Mardi',
    'wednesday' => 'Mercredi',
    'thursday'  => 'Jeudi',
    'friday'    => 'Vendredi',


    /*
    ** OTHER TEXT
    */

    'planning_of_week'          => 'Planning de la semaine du',
    'to'                        => 'au',
    'technician'                => 'Technicien',
    'no_technician_assigned'    => 'Aucun technicien n\'est assigné au planning.',

    'empty_fields_info'         => 'Les champs vides seront automatiquement remplis par "Absent".',

    'added_technician'          => 'Technicien à ajouter au planning :',

    'holiday_name'              => 'Nom des vacances',
    'start_date'                => 'Date de début',
    'end_date'                  => 'Date de fin',
    'no_holidays'               => 'Aucune période fériée n\'existe.',

    'delete_confirmation'       => 'Voulez-vous vraiment supprimer cette entrée ?',

    'technician_menu'           => 'Que souhaitez-vous faire avec le technicien ',
];