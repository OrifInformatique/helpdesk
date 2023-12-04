<?php

/**
 * Custom rule for planning
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

namespace Helpdesk\Validation\Rules;

use Helpdesk\Models\Planning_model;
use Helpdesk\Models\Nw_planning_model;

class NotInPlanning
{
    protected $planning_model;
    protected $nw_planning_model;

    public function __construct()
    {
        $this->planning_model = new Planning_model();
        $this->nw_planning_model = new Nw_planning_model();
    }

    /**
     * Check if a user isn't in planning
     * 
     * @param string $user_id
     * @param string $planning_type ID of the edited planning
     * 
     * @return bool
     * 
     */
    public function not_in_planning($user_id, $planning_type)
    {
        switch($planning_type)
        {
            case 0:
                $planning_data = $this->planning_model->where('fk_user_id', $user_id)->findAll();
                break;
                
            case 1:
                $planning_data = $this->nw_planning_model->where('fk_user_id', $user_id)->findAll();
                break;
        }

        // If there is a result (array not empty), it means the user already is in the planning.
        if (!empty($planning_data))
        {
            return false; // The user is in planning (NOT inexistent in planning)
        }

        return true; // The user isn't in planning (inexistent in planning)
    }
}
