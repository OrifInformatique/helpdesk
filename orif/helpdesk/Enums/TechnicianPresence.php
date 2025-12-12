<?php

/**
 * Enum for Technician Presence Status
 * 
 * @author      Orif (WaAd)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * @uses        Controller: <Controllers\Planning.php>
 */

namespace Helpdesk\Enums;

enum TechnicianPresence : int {
    case PRESENT = 1;
    case PARTLY_ABSENT = 2;
    case ABSENT = 3;
}

