<?php

/**
 * Next week planning view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<?= view('Helpdesk\Common\body_start') ?>
<?= view('Helpdesk\Common\planning_nav') ?>

<nav class="nw-planning-nav">
    <a class="btn btn-shift-weeks" href="<?= base_url('/helpdesk/home/confirm_action/shift_weeks') ?>"><span><?= lang('Buttons.shift_weeks') ?></span></a>
    <a class="btn btn-generate-planning" href="<?= base_url('/helpdesk/home/confirm_action/generate_planning') ?>"><span><?= lang('Buttons.generate_planning') ?></span></a>
</nav>

<div class="planning-table">

    <div class="week">
        <a class="btn btn-last-week" href="<?= base_url('/helpdesk/planning/cw_planning') ?>"><span><?= lang('Buttons.last_week')?></span></a>

        <?= view('Helpdesk\Common\planning_week', ['planning_type' => $planning_type]) ?>

        <button disabled class="btn btn-next-week"><span><?= lang('Buttons.next_week')?></span></button>
    </div>

    <table class="table-responsive<?= isset($classes) ? implode($classes) : ''?>">
        <thead>
            <tr>
                <th>
                    <?php if(!empty($nw_planning_data)): ?>
                        <a class="btn btn-edit" href="<?= base_url('/helpdesk/planning/update_planning/1') ?>" title="<?= lang('Buttons.edit_planning') ?>"></a>
                    <?php else: ?>
                        <button disabled class="btn btn-edit"></button>
                    <?php endif; ?>
                </th>
                <?php $planning_type = 1; echo view('Helpdesk\Common\planning_weekdays_row', ['planning_type' => $planning_type]) ?>
            </tr>
            <?= view('Helpdesk\Common\planning_schedules_row') ?>
        </thead>
        <tbody>
            <?php if (isset($nw_planning_data) && !empty($nw_planning_data)) : ?>
                <?php foreach ($nw_planning_data as $user) : ?>
                    <tr>
                        <?= view('Helpdesk\Common\planning_technician_name_column', $user) ?>

                        <?php foreach ($_SESSION['helpdesk']['nw_periods'] as $period): ?>
                            <?= view('Helpdesk\Common\planning_technician_roles_row', ['period' => $user[$period]]) ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="21">
                        <?= lang('Errors.no_technician_assigned')?><br>
                        <a class="btn btn-add" href="<?= base_url('/helpdesk/planning/add_technician/1') ?>"><span><?= lang('Buttons.add_technician')?></span></a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?= view('Helpdesk\Common\planning_bottom') ?>

    <?= view('Helpdesk\Common\planning_roles') ?>
</div>