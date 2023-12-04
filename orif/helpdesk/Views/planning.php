<?php

/**
 * Current week planning view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<?= view('Helpdesk\Common\body_start') ?>
<?= view('Helpdesk\Common\planning_nav') ?>

<div class="planning">
    <?= view('Helpdesk\Common\planning_roles') ?>

    <div class="week">
        <a class="btn btn-primary btn-last-week" href="<?= base_url('/helpdesk/planning/lw_planning') ?>"><?= lang('Helpdesk.btn_last_week')?></a>

        <?= view('Helpdesk\Common\planning_week', ['planning_type' => $planning_type]) ?>

        <a class="btn btn-primary btn-next-week" href="<?= base_url('/helpdesk/planning/nw_planning') ?>"><?= lang('Helpdesk.btn_next_week')?></a>
    </div>

    <table class="table-responsive<?= isset($classes) ? implode($classes) : ''?>">
        <thead>
            <tr>
                <th>        
                    <?php if(isset($planning_data)): ?>
                        <a class="btn btn-blue" href="<?= base_url('/helpdesk/planning/update_planning/0') ?>" title="<?= lang('Helpdesk.btn_edit_planning') ?>"><i class="fa-solid fa-square-pen"></i></a>
                    <?php else: ?>
                        <button disabled class="btn btn-blue"><i class="fa-solid fa-square-pen"></i></button>
                    <?php endif; ?>
                </th>
                <?= view('Helpdesk\Common\planning_weekdays_row', ['planning_type' => $planning_type]) ?>
            </tr>
            <?= view('Helpdesk\Common\planning_schedules_row') ?>
        </thead>
        <tbody>
            <?php if(isset($planning_data) && !empty($planning_data)) : ?>
                <?php foreach ($planning_data as $user) : ?>
                    <tr>
                        <?= view('Helpdesk\Common\planning_technician_name_column', $user) ?>

                        <?php foreach ($_SESSION['helpdesk']['cw_periods'] as $period): ?>
                            <?= view('Helpdesk\Common\planning_technician_roles_row', ['period' => $user[$period]]) ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="21">
                        <?= lang('Helpdesk.err_no_technician_assigned')?><br>
                        <a class="btn btn-blue" href="<?= base_url('/helpdesk/planning/add_technician/0') ?>"><?= lang('Helpdesk.btn_add_technician')?></a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?= view('Helpdesk\Common\planning_bottom') ?>
</div>