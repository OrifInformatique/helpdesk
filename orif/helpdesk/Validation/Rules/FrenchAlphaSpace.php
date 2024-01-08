<?php

/**
 * Custom rule for holiday name
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

namespace Helpdesk\Validation\Rules;

use function PHPUnit\Framework\matchesRegularExpression;

class FrenchAlphaSpace
{
    /**
     * Check if the field matches a regex
     * 
     * @param string $field
     * 
     * @return bool
     * 
     */
    public function french_alpha_space($field)
    {
        /* Regex rule matches if the string contains Unicode chars (\p{L}) or/and spaces (\s) */
        if(preg_match("/^[\p{L}\s]+$/u", $field))
            return true;

        return false;
    }
}
