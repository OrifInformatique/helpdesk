<?php
/**
 * French translations for form error messages
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (http://www.orif.ch)
 */

return[
    /* General form errors */
    'required' => 'Le champ est requis.',

    /* Planning form errors */
	'has_presences'         => 'Le technicien renseigné n\'a pas de présences.<br>Ajoutez-lui des présences via la liste des présences.',
    'is_natural_no_zero'    => 'Technicien sélectionné invalide.',
	'not_in_planning'       => 'Le technicien renseigné figure déjà dans le planning.',
    
    /* Holidays form errors */
    'coherent_dates'        => 'La date de fin doit être ultérieure à la date de début.',
    'french_alpha_space'    => 'Le champ ne peut contenir que des caractères alphabétiques et des espaces.',
    'valid_date'            => 'Date entrée incorrecte.',

    /* User create/update form error */
    'french_alpha' => 'Le champ {field} ne peut contenir que des caractères alphabétiques.'
];