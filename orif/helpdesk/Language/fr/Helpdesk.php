<?php
/**
 * French translations
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (http://www.orif.ch)
 */

return[

    /*
    ** PAGE TITLES
    */

    'ttl_all_presences'         => 'Présences des techniciens',
    'ttl_my_presences'          => 'Mes présences',
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
    'ttl_welcome_to_helpdesk'   => 'Bienvenue au helpdesk de Pomy !',


    /*
    ** ERROR MESSAGES
    */

    'err_technician_must_be_assigned_to_schedule'   => 'Chaque technicien doit être assigné au minimum à un rôle pendant une période.',
    'err_invalid_technician'                        => 'Le technicien sélectionné est invalide.',
    'err_invalid_role'                              => 'Le rôle assigné est invalide.',
    'err_dates_are_incoherent'                      => 'Les dates entrées sont incohérentes.',
    'err_unvalid_planning_type'                     => 'Erreur de planning.',
    'err_no_technician_selected'                    => 'Aucun technicien n\'a été sélectionné.',
    'err_unvalid_technician_selected'               => 'Le technicien sélectionné est invalide.',

    'err_no_technician_available'                   => 'Aucun technicien n\'est disponible.',
    'err_no_holidays'                               => 'Aucune période fériée n\'existe.',
    'err_no_technician_assigned'                    => 'Aucun technicien n\'est assigné au planning.',
    'err_no_technician_presences'                   => 'Aucun technicien n\'a de présences.',

    /*
    ** CUSTOM VALIDATION RULES MESSAGES
    */
	'coherent_dates' => 'La date de fin doit être ultérieure à la date de début.',
	'not_in_planning' => 'Le technicien renseigné figure déjà dans le planning.',

    /*
    ** INFO MESSAGES
    */

    'info_presences_fields_empty'         => 'Les champs vides seront automatiquement remplis par "Absent".',


    /*
    ** SUCCESS MESSAGES
    */

    'scs_presences_updated'             => 'Les présences ont été modifiées.',
    'scs_presences_deleted'             => 'Les présences du technicien ont été supprimées.',
    'scs_technician_added_to_schedule'  => 'Le technicien a été ajouté au planning.',
    'scs_technician_deleted'            => 'Le technicien a été supprimé du planning.',
    'scs_planning_updated'              => 'La planning de la semaine a été modifié.',
    'scs_holiday_updated'               => 'La période de vacances a été modifiée.',
    'scs_holiday_deleted'               => 'La période de vacances a été supprimée.',

    /*
    ** FORM CUSTOM ERRORS
    */

    'required'    => 'Le champ est requis.',
    'alpha_space' => 'Le champ ne peut contenir que des caractères alphabétiques et des espaces.',
    'valid_date'  => 'Date entrée incorrecte.',
    'is_nautral_no_zero' => 'Technicien sélectionné invalide.',


    /*
    ** BUTTONS
    */

    'btn_all_presences'     => 'Présences des techniciens',
    'btn_my_presences'      => 'Modifier mes présences',
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
    'btn_terminal'          => 'Terminal',
    'btn_reset'             => 'Réinitialiser',

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

    'added_technician'          => 'Technicien à ajouter au planning',

    'holiday_name'              => 'Nom des vacances',
    'start_date'                => 'Date de début',
    'end_date'                  => 'Date de fin',

    'delete_confirmation'       => 'Voulez-vous vraiment supprimer cette entrée ?',

    'technician_menu'           => 'Que souhaitez-vous faire avec le technicien ',

    'alt_photo_technician'      => 'Photo de face du technicien',

    'unavailable'               => 'Indisponible',

    'updating_in'               => 'Actualisation dans',
    'seconds'                   => 'seconde·s',

    'planning_generation'       => 'Génération du planning'
];