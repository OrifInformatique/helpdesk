<?php
/**
 * English translations for form error messages
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (http://www.orif.ch)
 */

return[
    /* General form errors */
    'required' => 'The field is required.',

    /* Planning form errors */
	'has_presences'         => 'The chosen technician has no availabilities.<br>Add him availabilites in the availabilities list.',
    'is_natural_no_zero'    => 'Chosen technician is invalid.',
	'not_in_planning'       => 'The chosen technician is already in the schedule.',
    
    /* Holidays form errors */
    'coherent_dates'        => 'The end date must be after the start date.',
    'french_alpha_space'    => 'The field can only contain alphabetical characters and spaces.',
    'valid_date'            => 'The entered date is invalid.',

    /* User create/update form error */
    'french_alpha' => 'The field {field} can only contain alphabetical characters.'
];