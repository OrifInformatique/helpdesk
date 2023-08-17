<?php

/**
 * planning view
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
    <?php if(isset($title)){ echo ('<h2>'.$title.'</h2>');} ?>

    <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home/presences') ?>"><?php echo lang('Helpdesk.btn_presences')?></a>
    <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home/holidays') ?>"><?php echo lang('Helpdesk.btn_holiday')?></a><br>

    <a class="btn btn-blue mb-3" href="<?= base_url('helpdesk/home/addTechnician/1') ?>"><?php echo lang('Helpdesk.btn_add_technician')?></a>
    <a class="btn btn-blue mb-3" href="<?= base_url('helpdesk/home/updatePlanning/1') ?>"><?php echo lang('Helpdesk.btn_edit_planning')?></a>
    
    <!-- Success message, if exists -->
    <?php if (isset($success)): ?>
        <div class="d-flex justify-content-center">
            <?php echo ('<p class="success">'.$success.'</p>'); ?>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-center">
        <div class="bg-green border-xs-1 p-2 rounded rounded-3 mx-4"><?php echo lang('Helpdesk.role_1')?></div> <!-- c5deb5 -->
        <div class="bg-yellow border-xs-1 p-2 rounded rounded-3 mx-4"><?php echo lang('Helpdesk.role_2')?></div> <!-- e5f874 -->
        <div class="bg-orange border-xs-1 p-2 rounded rounded-3 mx-4"><?php echo lang('Helpdesk.role_3')?></div> <!-- ffd965 -->
    </div>

    <div class="week">
        <a class="btn btn-primary" href="<?= base_url('helpdesk/home/planning') ?>"><?php echo lang('Helpdesk.btn_last_week')?></a>
        <?php echo lang('Helpdesk.planning_of_week')?>
        <span class="start-date">
            <!-- Displays the current week monday -->
            <?php echo date('d/m/Y', $next_week['monday']); ?>
        </span>
        <?php echo lang('Helpdesk.to')?>
        <span class="end-date">
            <!-- Displays the current week friday -->
            <?php echo date('d/m/Y', $next_week['friday']); ?>
        </span>
    </div>


    <table class="table-responsive position-relative">
        <thead>
            <tr>
                <th></th>
                <th colspan="4"><?php echo lang('Helpdesk.monday').' '.date('d', $next_week['monday']); ?></th>
                <th colspan="4"><?php echo lang('Helpdesk.tuesday').' '.date('d', $next_week['tuesday']); ?></th>
                <th colspan="4"><?php echo lang('Helpdesk.wednesday').' '.date('d', $next_week['wednesday']); ?></th>
                <th colspan="4"><?php echo lang('Helpdesk.thursday').' '.date('d', $next_week['thursday']); ?></th>
                <th colspan="4"><?php echo lang('Helpdesk.friday').' '.date('d', $next_week['friday']); ?></th>
            </tr>
            <tr>
                <th><?php echo lang('Helpdesk.technician')?></th>

                <?php 
                // Repeats timetables 5 times
                for($i = 0; $i < 5; $i++): ?>
                        
                    <th>8:00 10:00</th>
                    <th>10:00 12:00</th>
                    <th>12:45 15:00</th>
                    <th>15:00 16:57</th>

                <?php endfor; ?>
                    
            </tr>
        </thead>
        <tbody>
            <?php if (isset($nw_planning_data) && !empty($nw_planning_data)) : ?>
                <?php foreach ($nw_planning_data as $user) : ?>
                    <tr>
                        <th>
                            <a href="<?= base_url('helpdesk/home/technicianMenu/'.$user['fk_user_id'].'/1') ?>">
                                <?php echo $user['last_name_user_data'].'<br>'.$user['first_name_user_data']; ?>
                            </a>
                        </th>

                        <?php foreach ($nw_periods as $period): ?>
                            <td class="<?php echo $user[$period] == 0 ? '' : ($user[$period] == 1 ? 'bg-green' : ($user[$period] == 2 ? 'bg-yellow' : 'bg-orange')); ?>">
                                <?php echo $user[$period]; ?>
                            </td>

                        <?php endforeach; ?>

                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="21">
                        <?php echo lang('Helpdesk.no_technician_assigned')?><br>
                        <a class="btn btn-blue mb-3" href="<?= base_url('helpdesk/home/addTechnician/1') ?>"><?php echo lang('Helpdesk.btn_add_technician')?></a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>