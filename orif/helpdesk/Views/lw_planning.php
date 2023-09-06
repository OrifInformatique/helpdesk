<?php

/**
 * last week planning view
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
            <?php echo ('<p class="success">'.$success.'</p>'); ?>
        </div>
    <?php endif; ?>

    <!-- Error message, if exists -->
    <?php if(isset($error)): ?>
        <div class="d-flex justify-content-center">
            <?php echo ('<p class="error">'.$error.'</p>'); ?>
        </div>
    <?php endif; ?>

    <nav>
        <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home/presences') ?>"><?php echo lang('Helpdesk.btn_presences')?></a>
        <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home/holidays') ?>"><?php echo lang('Helpdesk.btn_holiday')?></a>
    </nav>

    <div class="planning">
        <div class="d-flex justify-content-center roles">
            <div class="bg-green  border-xs-1 p-2 rounded rounded-3 mx-3"><?php echo lang('Helpdesk.role_1')?></div>
            <div class="bg-yellow border-xs-1 p-2 rounded rounded-3 mx-3"><?php echo lang('Helpdesk.role_2')?></div>
            <div class="bg-orange border-xs-1 p-2 rounded rounded-3 mx-3"><?php echo lang('Helpdesk.role_3')?></div>
        </div>

        <div class="week">
            <button disabled class="btn btn-primary btn-last-week"><?php echo lang('Helpdesk.btn_last_week')?></button>

            <div>
                <?php echo lang('Helpdesk.planning_of_week')?>
                <span class="start-date">
                    <!-- Displays the last monday -->
                    <?php echo date('d/m/Y', strtotime('monday last week')); ?>
                </span>
                <?php echo lang('Helpdesk.to')?>
                <span class="end-date">
                    <!-- Displays the last friday -->
                    <?php echo date('d/m/Y', strtotime('friday last week')); ?>
                </span>
            </div>

            <a class="btn btn-primary btn-next-week" href="<?= base_url('helpdesk/home/planning') ?>"><?php echo lang('Helpdesk.btn_next_week')?></a>
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
                                <a href="<?= base_url('helpdesk/home/technicianMenu/'.$user['fk_user_id'].'/-1') ?>">
                                    <?php echo $user['last_name_user_data'].'<br>'.$user['first_name_user_data']; ?>
                                </a>
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
                            <?php echo lang('Helpdesk.no_technician_assigned')?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="action-menu d-flex justify-content-center">
            <button disabled class="btn btn-blue"><?php echo lang('Helpdesk.btn_edit_planning')?></button>
        </div>
    </div>
</div>