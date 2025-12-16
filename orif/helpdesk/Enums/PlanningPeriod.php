<?php

/**
 * Enum for Planning Periods
 * 
 * @author      Orif (WaAd)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * @uses        Controller: <Controllers\Planning.php>
 */

namespace Helpdesk\Enums;

enum PlanningPeriod : int {
    case LAST_WEEK = -1;
    case CURRENT_WEEK = 0;
    case NEXT_WEEK = 1;
}

