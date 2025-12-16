<?php

/**
 * Enum for Technician Assignment Roles
 * 
 * @author      Orif (WaAd)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * @uses        Controller: <Controllers\Planning.php>
 *  
 */

namespace Helpdesk\Enums;

enum TechnicianAssignment : int {
    case UNASSIGNED = 0;
    case FIRST_TECHNICIAN = 1;
    case SECOND_TECHNICIAN = 2;
    case THIRD_TECHNICIAN = 3;
}

