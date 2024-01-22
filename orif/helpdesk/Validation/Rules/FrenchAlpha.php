<?php

/**
 * Custom rule for user first and last name
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

namespace Helpdesk\Validation\Rules;

class FrenchAlpha
{
    /**
     * Check if the field matches a regex
     * 
     * @param string $field
     * 
     * @return bool
     * 
     */
    public function french_alpha($field)
    {
        /* Regex rule matches if the string contains Unicode chars (\p{L}) */
        if(preg_match("/^[\p{L}]+$/u", trim($field)))
            return true;

        return false;
    }
}
