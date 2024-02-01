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
        $start_date = date('d.m', strtotime('monday last week'));
        $end_date = date('d.m', strtotime('friday last week'));
        break;
    case 0:
        $start_date = date('d.m', strtotime('monday this week'));
        $end_date = date('d.m', strtotime('friday this week'));
        break;
    case 1:
        $start_date = date('d.m', $_SESSION['helpdesk']['next_week']['monday']);
        $end_date = date('d.m', $_SESSION['helpdesk']['next_week']['friday']);
        break;
}?>

<div>
    <?= $start_date ?>
    -
    <?= $end_date ?>
</div>