<?php

/**
 * Custom rule for presences
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

namespace Helpdesk\Validation\Rules;

use Helpdesk\Models\Presences_model;

class HasPresences
{
    protected $presences_model;

    public function __construct()
    {
        $this->presences_model = new Presences_model();
    }

    /**
     * Check if a user has presences
     * 
     * @param string $user_id
     * 
     * @return bool
     * 
     */
    public function has_presences($user_id)
    {
        $user_presences = $this->presences_model->getPresencesUser($user_id);

        if (empty($user_presences))
        {
            return false;
        }

        return true;
    }
}
