<?php

/**
 * planning view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<nav>
    <a class="btn btn-primary mb-3" href="<?= base_url('/presences/all_presences') ?>"><?= lang('Helpdesk.btn_all_presences')?></a>
    <a class="btn btn-primary mb-3" href="<?= base_url('/holidays/holidays_list') ?>"><?= lang('Helpdesk.btn_holiday')?></a>
    <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home/generatePlanning') ?>">Generate planning</a>
</nav>

<div class="planning">
    <div class="d-flex justify-content-center roles">
        <div class="bg-green  border-xs-1 p-2 rounded rounded-3 mx-3"><?= lang('Helpdesk.role_1')?></div>
        <div class="bg-light-green border-xs-1 p-2 rounded rounded-3 mx-3"><?= lang('Helpdesk.role_2')?></div>
        <div class="bg-orange border-xs-1 p-2 rounded rounded-3 mx-3"><?= lang('Helpdesk.role_3')?></div>
    </div>

    <div class="week">
        <a class="btn btn-primary btn-last-week" href="<?= base_url('/planning/cw_planning') ?>"><?= lang('Helpdesk.btn_last_week')?></a>

        <div>
            <?= lang('Helpdesk.planning_of_week')?>
            <span class="start-date">
                <?= date('d/m/Y', $_SESSION['helpdesk']['next_week']['monday']); ?>
            </span>
            <?= lang('Helpdesk.to')?>
            <span class="end-date">
                <?= date('d/m/Y', $_SESSION['helpdesk']['next_week']['friday']); ?>
            </span>
        </div>

        <button disabled class="btn btn-primary btn-next-week"><?= lang('Helpdesk.btn_next_week')?></button>
    </div>

    <table class="table-responsive
    <?php if(isset($classes))
    {
        foreach($classes as $class)
        {
            echo $class;
        }
    }?>
    ">
        <thead>
            <tr>
                <th></th>
                <th colspan="4"><?= lang('Helpdesk.monday').' '.date('d', $_SESSION['helpdesk']['next_week']['monday']); ?></th>
                <th colspan="4"><?= lang('Helpdesk.tuesday').' '.date('d', $_SESSION['helpdesk']['next_week']['tuesday']); ?></th>
                <th colspan="4"><?= lang('Helpdesk.wednesday').' '.date('d', $_SESSION['helpdesk']['next_week']['wednesday']); ?></th>
                <th colspan="4"><?= lang('Helpdesk.thursday').' '.date('d', $_SESSION['helpdesk']['next_week']['thursday']); ?></th>
                <th colspan="4"><?= lang('Helpdesk.friday').' '.date('d', $_SESSION['helpdesk']['next_week']['friday']); ?></th>
            </tr>
            <tr>
                <th><?= lang('Helpdesk.technician')?></th>
                <?php for($i = 0; $i < 5; $i++): ?>
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
                            <a href="<?= base_url('helpdesk/home/technicianMenu/'.$user['fk_user_id']) ?>">
                                <?= $user['last_name_user_data'].'<br>'.$user['first_name_user_data']; ?>
                            </a>
                        </th>

                        <?php foreach ($_SESSION['helpdesk']['nw_periods'] as $period): ?>
                            <td class="<?= $user[$period] == 0 ? '' : ($user[$period] == 1 ? 'bg-green' : ($user[$period] == 2 ? 'bg-light-green' : 'bg-orange')); ?>">
                                <?= $user[$period]; ?>
                            </td>

                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="21">
                        <?= lang('Helpdesk.err_no_technician_assigned')?><br>
                        <a class="btn btn-blue" href="<?= base_url('/planning/add_technician/1') ?>"><?= lang('Helpdesk.btn_add_technician')?></a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="action-menu d-flex justify-content-center">
        <?php if(isset($nw_planning_data)): ?>
            <a class="btn btn-blue" href="<?= base_url('/planning/update_planning/1') ?>"><?= lang('Helpdesk.btn_edit_planning')?></a>
        <?php else: ?>
            <button disabled class="btn btn-blue"><?= lang('Helpdesk.btn_edit_planning')?></button>
        <?php endif; ?>
    </div>
</div>