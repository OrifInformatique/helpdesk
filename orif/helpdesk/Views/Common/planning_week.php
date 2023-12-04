<?php

/**
 * Planning weekdays row in planning table component
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

$start_date = '';
$end_date = '';

switch($planning_type)
{
    case -1:
        $start_date = date('d/m/Y', strtotime('monday last week'));
        $end_date = date('d/m/Y', strtotime('friday last week'));
        break;
    case 0:
        $start_date = date('d/m/Y', strtotime('monday this week'));
        $end_date = date('d/m/Y', strtotime('friday this week'));
        break;
    case 1:
        $start_date = date('d/m/Y', $_SESSION['helpdesk']['next_week']['monday']);
        $end_date = date('d/m/Y', $_SESSION['helpdesk']['next_week']['friday']);
        break;
}?>

<div>
    <?= lang('Helpdesk.planning_of_week')?>
    <span class="start-date">
        <?= $start_date ?>
    </span>
    <?= lang('Helpdesk.to')?>
    <span class="end-date">
        <?= $end_date ?>
    </span>
</div>