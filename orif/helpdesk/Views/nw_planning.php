<?php

/**
 * planning view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

?>

<div class="container-fluid">

    <!-- Title, if exists -->
    <?php if(isset($title)){
        echo ('<h2>' . $title . '</h2>');
    } ?>

    <!-- Success message, if exists -->
    <?php if(isset($success)): ?>
        <div class="d-flex justify-content-center">
            <?= ('<p class="success">'.$success.'</p>'); ?>
        </div>
    <?php endif; ?>

    <!-- Error message, if exists -->
    <?php if(isset($error)): ?>
        <div class="d-flex justify-content-center">
            <?= ('<p class="error">'.$error.'</p>'); ?>
        </div>
    <?php endif; ?>

    <nav>
        <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home/allPresences') ?>"><?= lang('Helpdesk.btn_all_presences')?></a>
        <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home/holidays') ?>"><?= lang('Helpdesk.btn_holiday')?></a>
        <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home/generatePlanning') ?>">Generate planning</a>
    </nav>

    <div class="planning">
        <div class="d-flex justify-content-center roles">
            <div class="bg-green  border-xs-1 p-2 rounded rounded-3 mx-3"><?= lang('Helpdesk.role_1')?></div>
            <div class="bg-light-green border-xs-1 p-2 rounded rounded-3 mx-3"><?= lang('Helpdesk.role_2')?></div>
            <div class="bg-orange border-xs-1 p-2 rounded rounded-3 mx-3"><?= lang('Helpdesk.role_3')?></div>
        </div>

        <div class="week">
            <a class="btn btn-primary btn-last-week" href="<?= base_url('helpdesk/home/planning') ?>"><?= lang('Helpdesk.btn_last_week')?></a>

            <div>
                <?= lang('Helpdesk.planning_of_week')?>
                <span class="start-date">
                    <!-- Displays the next monday -->
                    <?= date('d/m/Y', $next_week['monday']); ?>
                </span>
                <?= lang('Helpdesk.to')?>
                <span class="end-date">
                    <!-- Displays the next friday -->
                    <?= date('d/m/Y', $next_week['friday']); ?>
                </span>
            </div>

            <button disabled class="btn btn-primary btn-next-week"><?= lang('Helpdesk.btn_next_week')?></button>
        </div>


        <table class="table-responsive<?php
        if(isset($classes))
        {
            foreach($classes as $class)
            {
                echo $class;
            }
        }?>">
            <thead>
                <tr>
                    <th></th>
                    <th colspan="4"><?= lang('Helpdesk.monday').' '.date('d', $next_week['monday']); ?></th>
                    <th colspan="4"><?= lang('Helpdesk.tuesday').' '.date('d', $next_week['tuesday']); ?></th>
                    <th colspan="4"><?= lang('Helpdesk.wednesday').' '.date('d', $next_week['wednesday']); ?></th>
                    <th colspan="4"><?= lang('Helpdesk.thursday').' '.date('d', $next_week['thursday']); ?></th>
                    <th colspan="4"><?= lang('Helpdesk.friday').' '.date('d', $next_week['friday']); ?></th>
                </tr>
                <tr>
                    <th><?= lang('Helpdesk.technician')?></th>

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
                            <th <?php if($user['fk_user_type'] == 4){echo 'class="mentor"';}?>>
                                <a href="<?= base_url('helpdesk/home/technicianMenu/'.$user['fk_user_id'].'/1') ?>">
                                    <?= $user['last_name_user_data'].'<br>'.$user['first_name_user_data']; ?>
                                </a>
                            </th>

                            <?php foreach ($nw_periods as $period): ?>
                                <td class="<?= $user[$period] == 0 ? '' : ($user[$period] == 1 ? 'bg-green' : ($user[$period] == 2 ? 'bg-light-green' : 'bg-orange')); ?>">
                                    <?= $user[$period]; ?>
                                </td>

                            <?php endforeach; ?>

                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="21">
                            <?= lang('Helpdesk.no_technician_assigned')?><br>
                            <a class="btn btn-blue" href="<?= base_url('helpdesk/home/addTechnician/1') ?>"><?= lang('Helpdesk.btn_add_technician')?></a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="action-menu d-flex justify-content-center">
            <?php if(isset($nw_planning_data)): ?>
                <a class="btn btn-blue" href="<?= base_url('helpdesk/home/updatePlanning/1') ?>"><?= lang('Helpdesk.btn_edit_planning')?></a>
            <?php else: ?>
                <button disabled class="btn btn-blue"><?= lang('Helpdesk.btn_edit_planning')?></button>
            <?php endif; ?>
        </div>
    </div>
</div>