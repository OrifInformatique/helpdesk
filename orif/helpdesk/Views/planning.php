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

<div class="planning-table">

    <div class="week">
        <a class="btn btn-last-week" href="<?= base_url('/helpdesk/planning/lw_planning') ?>"><span><?= lang('Buttons.last_week')?></span></a>

        <?= view('Helpdesk\Common\planning_week', ['planning_type' => $planning_type]) ?>

        <a class="btn btn-next-week" href="<?= base_url('/helpdesk/planning/nw_planning') ?>"><span><?= lang('Buttons.next_week')?></span></a>
    </div>

    <table class="table-responsive<?= isset($classes) ? implode($classes) : ''?>">
        <thead>
            <tr>
                <th>        
                    <?php if(!empty($planning_data)): ?>
                        <a class="btn btn-edit" href="<?= base_url('/helpdesk/planning/update_planning/0') ?>" title="<?= lang('Buttons.edit_planning') ?>"></a>
                    <?php else: ?>
                        <button disabled class="btn btn-edit"></button>
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
                        <?= lang('Errors.no_technician_assigned')?><br>
                        <a class="btn btn-add" href="<?= base_url('/helpdesk/planning/add_technician/0') ?>"><span><?= lang('Buttons.add_technician')?></span></a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?= view('Helpdesk\Common\planning_bottom') ?>

    <?= view('Helpdesk\Common\planning_roles') ?>
</div>