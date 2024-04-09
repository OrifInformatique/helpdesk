<?php

/**
 * Planning weekdays row in planning table component
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

switch($planning_type)
{
    case -1:
        echo
        ('
            <th colspan="4">'.lang('Time.monday').' '.date('d', strtotime('monday last week')).'</th>
            <th colspan="4">'.lang('Time.tuesday').' '.date('d', strtotime('tuesday last week')).'</th>
            <th colspan="4">'.lang('Time.wednesday').' '.date('d', strtotime('wednesday last week')).'</th>
            <th colspan="4">'.lang('Time.thursday').' '.date('d', strtotime('thursday last week')).'</th>
            <th colspan="4">'.lang('Time.friday').' '.date('d', strtotime('friday last week')).'</th>
        ');
        break;
    case 0:
        echo
        ('
            <th colspan="4">'.lang('Time.monday').' '.date('d', strtotime('monday this week')).'</th>
            <th colspan="4">'.lang('Time.tuesday').' '.date('d', strtotime('tuesday this week')).'</th>
            <th colspan="4">'.lang('Time.wednesday').' '.date('d', strtotime('wednesday this week')).'</th>
            <th colspan="4">'.lang('Time.thursday').' '.date('d', strtotime('thursday this week')).'</th>
            <th colspan="4">'.lang('Time.friday').' '.date('d', strtotime('friday this week')).'</th>
        ');
        break;
    case 1:
        echo
        ('
            <th colspan="4">'.lang('Time.monday').' '.date('d', $_SESSION['helpdesk']['next_week']['monday']).'</th>
            <th colspan="4">'.lang('Time.tuesday').' '.date('d', $_SESSION['helpdesk']['next_week']['tuesday']).'</th>
            <th colspan="4">'.lang('Time.wednesday').' '.date('d', $_SESSION['helpdesk']['next_week']['wednesday']).'</th>
            <th colspan="4">'.lang('Time.thursday').' '.date('d', $_SESSION['helpdesk']['next_week']['thursday']).'</th>
            <th colspan="4">'.lang('Time.friday').' '.date('d', $_SESSION['helpdesk']['next_week']['friday']).'</th>
        ');
        break;
}