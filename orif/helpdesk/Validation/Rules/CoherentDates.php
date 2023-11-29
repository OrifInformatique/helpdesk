<?php

/**
 * Custom rule for dates
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

namespace Helpdesk\Validation\Rules;

class CoherentDates
{
    /**
     * Check if dates are coherent : if the end date is after the start date
     * 
     * @param string $end_date
     * @param string $start_date
     * 
     * @return bool
     * 
     */
    public function coherent_dates($end_date, $start_date)
    {
        return strtotime($end_date) >= strtotime($start_date);
    }
}
