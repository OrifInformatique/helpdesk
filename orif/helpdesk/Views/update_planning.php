<?php

/**
 * update_planning view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

?>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        padding: 8px;
        text-align: center;
        border: 1px solid #ddd;
    }

    thead tr:nth-child(1) th {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
    }

    thead tr:nth-child(2) th {
        font-weight: bold;
    }

    tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .week {
        background-color: #eee;
        padding: 8px;
        font-size: 18px;
        text-align: center;
    }

    .bg-green {
        background-color: #c5deb5;
    }

    .bg-yellow {
        background-color: #e5f874;
    }

    .bg-orange {
        background-color: #ffd965;
    }

    .btn-blue {
        background-color: #b4c6e7;
    }

    .btn-blue:hover {
        color: #fff;
        background-color: #b4c6e7;
        border-color: #b4c6e7
    }
    
    /* Success message */
	.success {
		position: absolute;
		top: 18%;
		background-color: greenyellow;
		border-radius: 5px;
		padding: 7px 15px;
		font-size: 1.25em;

		animation: fadeOut 4s forwards;
	}

    /* Error message */
    .error {
		position: absolute;
		top: 18%;
		background-color: red;
        color: #fff;
		border-radius: 5px;
		padding: 7px 15px;
		font-size: 1.25em;

		animation: fadeOut 4s forwards;
	}

	/* Fade animation */
	@keyframes fadeOut 
	{
		0% { opacity: 1; }
		80% { opacity: 1; }
		100% { opacity: 0; display: none; }
	}

</style>

<div class="container-fluid">

    <!-- Title, if exists -->
    <?php if (isset($title)) {
        echo ('<h2>' . $title . '</h2>');
    } ?>

    <!-- Success message, if exists -->
    <?php if (isset($success)): ?>
        <div class="d-flex justify-content-center">
            <?php echo ('<p class="success">'.$success.'</p>'); ?>
        </div>
    <?php endif; ?>

    <!-- Error message, if exists -->
    <?php if (isset($error)): ?>
        <div class="d-flex justify-content-center">
            <?php echo ('<p class="error">'.$error.'</p>'); ?>
        </div>
    <?php endif; ?>

    <?php switch($planning_type)
    {
        case 0:
            echo('<a class="btn btn-primary mb-3" href="'.base_url('helpdesk/home').'">'.lang('Helpdesk.btn_back').'</a>');
            break;

        case 1:
            echo('<a class="btn btn-primary mb-3" href="'.base_url('helpdesk/home/nw_planning').'">'.lang('Helpdesk.btn_back').'</a>');
            break;
    } ?>
    
    <form method="POST" action="<?= base_url('helpdesk/home/updatePlanning/'.$planning_type) ?>">

        <input class="btn btn-blue mb-3" type="submit" value="<?php echo lang('Helpdesk.btn_save')?>">

        <div class="d-flex justify-content-center">
            <div class="bg-green border-xs-1 p-2 rounded rounded-3 mx-4"><?php echo lang('Helpdesk.role_1')?></div> <!-- c5deb5 -->
            <div class="bg-yellow border-xs-1 p-2 rounded rounded-3 mx-4"><?php echo lang('Helpdesk.role_2')?></div> <!-- e5f874 -->
            <div class="bg-orange border-xs-1 p-2 rounded rounded-3 mx-4"><?php echo lang('Helpdesk.role_3')?></div> <!-- ffd965 -->
        </div>

        <div class="week">
            <?php echo lang('Helpdesk.planning_of_week')?>
            <span class="start-date">
                <?php switch($planning_type)
                    {
                        case 0:
                            echo date('d/m/Y', strtotime('monday this week')); 
                            break;

                        case 1:
                            echo date('d/m/Y', strtotime('next monday'));
                            break;
                    }
                ?>
            </span>
            <?php echo lang('Helpdesk.to')?>
            <span class="end-date">
                <?php switch($planning_type)
                    {
                        case 0:
                            echo date('d/m/Y', strtotime('friday this week'));
                            break;

                        case 1:
                            echo date('d/m/Y', $next_week['friday']);
                            break;
                    }
                ?>
            </span>
        </div>


        <table class="table-responsive position-relative">
        <thead>
                <?php switch($planning_type)
                {
                    case 0: ?>
                        <tr>
                            <th></th>
                            <th colspan="4"><?php echo lang('Helpdesk.monday').' '.date('d', strtotime('monday this week')); ?></th>
                            <th colspan="4"><?php echo lang('Helpdesk.tuesday').' '.date('d', strtotime('tuesday this week')); ?></th>
                            <th colspan="4"><?php echo lang('Helpdesk.wednesday').' '.date('d', strtotime('wednesday this week')); ?></th>
                            <th colspan="4"><?php echo lang('Helpdesk.thursday').' '.date('d', strtotime('thursday this week')); ?></th>
                            <th colspan="4"><?php echo lang('Helpdesk.friday').' '.date('d', strtotime('friday this week')); ?></th>
                            <th></th>
                        </tr>
                    <?php break;?>
                    <?php case 1: ?>
                        <tr>
                            <th></th>
                            <th colspan="4"><?php echo lang('Helpdesk.monday').' '.date('d', $next_week['monday']); ?></th>
                            <th colspan="4"><?php echo lang('Helpdesk.tuesday').' '.date('d', $next_week['tuesday']); ?></th>
                            <th colspan="4"><?php echo lang('Helpdesk.wednesday').' '.date('d', $next_week['wednesday']); ?></th>
                            <th colspan="4"><?php echo lang('Helpdesk.thursday').' '.date('d', $next_week['thursday']); ?></th>
                            <th colspan="4"><?php echo lang('Helpdesk.friday').' '.date('d', $next_week['friday']); ?></th>
                            <th></th>
                        </tr>
                    <?php break;?>
                <?php } ?>
                <tr>
                    <th><?php echo lang('Helpdesk.technician') ?></th>
                    <?php 
                    // Repeats timetables 5 times
                    for($i = 0; $i < 5; $i++): ?>
                        <th>8:00 10:00</th>
                        <th>10:00 12:00</th>
                        <th>12:45 15:00</th>
                        <th>15:00 16:57</th>
                    <?php endfor; ?>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php if(isset($planning_data)) : ?>
                    <?php foreach($planning_data as $planning) : ?>      
                        <tr>
                            <th><?php echo $planning['last_name_user_data'].'<br>'.$planning['first_name_user_data']; ?></th>
                            <input type="hidden" name="planning[<?php echo $planning['id_planning']; ?>][id_planning]" value="<?php echo $planning['id_planning']; ?>">
                            <input type="hidden" name="planning[<?php echo $planning['id_planning']; ?>][fk_user_id]" value="<?php echo $planning['fk_user_id']; ?>">
                            <?php foreach ($form_fields_data as $field) : ?>
                                <td>
                                    <select name="planning[<?php echo $planning['id_planning']; ?>][<?php echo $field; ?>]">
                                        <?php
                                        $choices = array('', 1, 2, 3);

                                        foreach ($choices as $choice) 
                                        {
                                            $selected = ($planning[$field] == $choice) ? 'selected' : '';
                                            echo '<option value="' . $choice . '" ' . $selected . '>' . $choice . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            <?php endforeach; ?>
                            <td>
                                <a class="btn btn-danger" href="<?= base_url('helpdesk/home/deleteTechnician/'.$planning['fk_user_id'].'/0')?>">X</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php elseif(isset($nw_planning_data)): ?>
                    <?php foreach($nw_planning_data as $nw_planning) : ?>      
                        <tr>
                            <th><?php echo $nw_planning['last_name_user_data'].'<br>'.$nw_planning['first_name_user_data']; ?></th>
                            <input type="hidden" name="nw_planning[<?php echo $nw_planning['id_nw_planning']; ?>][id_nw_planning]" value="<?php echo $nw_planning['id_nw_planning']; ?>">
                            <input type="hidden" name="nw_planning[<?php echo $nw_planning['id_nw_planning']; ?>][fk_user_id]" value="<?php echo $nw_planning['fk_user_id']; ?>">
                            <?php foreach ($form_fields_data as $field) : ?>
                                <td>
                                    <select name="nw_planning[<?php echo $nw_planning['id_nw_planning']; ?>][<?php echo $field; ?>]">
                                        <?php
                                        $choices = array('', 1, 2, 3);

                                        foreach ($choices as $choice) 
                                        {
                                            $selected = ($nw_planning[$field] == $choice) ? 'selected' : '';
                                            echo '<option value="' . $choice . '" ' . $selected . '>' . $choice . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            <?php endforeach; ?>
                            <td>
                                <a class="btn btn-danger" href="<?= base_url('helpdesk/home/deleteTechnician/'.$nw_planning['fk_user_id'].'/1')?>">X</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr colspan="21">
                        <td><?php echo lang('Helpdesk.no_technician_assigned')?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>