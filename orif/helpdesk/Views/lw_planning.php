<?php

/**
 * last week planning view
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

    tr th:first-child
    {
        width: 125px;
        height: 75px;
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

    .mentor
    {
        box-shadow: 12px 0 #9bc2e6 inset;
    }
</style>

<div class="container-fluid">

    <!-- Title, if exists -->
    <?php if(isset($title)){ echo ('<h2>'.$title.'</h2>');} ?>

    <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home/presences') ?>"><?php echo lang('Helpdesk.btn_presences')?></a>
    <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home/holidays') ?>"><?php echo lang('Helpdesk.btn_holiday')?></a>

    <div class="d-flex justify-content-center">
        <div class="bg-green border-xs-1 p-2 rounded rounded-3 mx-4"><?php echo lang('Helpdesk.role_1')?></div> <!-- c5deb5 -->
        <div class="bg-yellow border-xs-1 p-2 rounded rounded-3 mx-4"><?php echo lang('Helpdesk.role_2')?></div> <!-- e5f874 -->
        <div class="bg-orange border-xs-1 p-2 rounded rounded-3 mx-4"><?php echo lang('Helpdesk.role_3')?></div> <!-- ffd965 -->
    </div>

    <div class="week">
        <?php echo lang('Helpdesk.planning_of_week')?>
        <span class="start-date">
            <!-- Displays the current week monday -->
            <?php echo date('d/m/Y', strtotime('monday last week')); ?>
        </span>
        <?php echo lang('Helpdesk.to')?>
        <span class="end-date">
            <!-- Displays the current week friday -->
            <?php echo date('d/m/Y', strtotime('friday last week')); ?>
        </span>
        <a class="btn btn-primary" href="<?= base_url('helpdesk/home/planning') ?>"><?php echo lang('Helpdesk.btn_next_week')?></a>
    </div>


    <table class="table-responsive position-relative">
        <thead>
            <tr>
                <th></th>
                <th colspan="4"><?php echo lang('Helpdesk.monday').' '.date('d', strtotime('monday last week')); ?></th>
                <th colspan="4"><?php echo lang('Helpdesk.tuesday').' '.date('d', strtotime('tuesday last week')); ?></th>
                <th colspan="4"><?php echo lang('Helpdesk.wednesday').' '. date('d', strtotime('wednesday last week')); ?></th>
                <th colspan="4"><?php echo lang('Helpdesk.thursday').' '. date('d', strtotime('thursday last week')); ?></th>
                <th colspan="4"><?php echo lang('Helpdesk.friday').' '.date('d', strtotime('friday last week')); ?></th>

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
            <?php if (isset($lw_planning_data) && !empty($lw_planning_data)) : ?>
                <?php foreach ($lw_planning_data as $user) : ?>
                    <tr>
                        <th <?php if($user['fk_user_type'] == 4){echo 'class="mentor"';}?>>
                            <?php echo $user['last_name_user_data'].'<br>'.$user['first_name_user_data']; ?>
                        </th>

                        <?php foreach ($lw_periods as $period): ?>
                            <td class="<?php echo $user[$period] == 0 ? '' : ($user[$period] == 1 ? 'bg-green' : ($user[$period] == 2 ? 'bg-yellow' : 'bg-orange')); ?>">
                                <?php echo $user[$period]; ?>
                            </td>

                        <?php endforeach; ?>

                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="21">
                        <p> <?php echo lang('Helpdesk.no_technician_assigned')?> </p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>