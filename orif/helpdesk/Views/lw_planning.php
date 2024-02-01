<?php

/**
 * Last week planning view
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
        <button disabled class="btn btn-last-week"><span><?= lang('Helpdesk.btn_last_week') ?></span></button>

        <?= view('Helpdesk\Common\planning_week', ['planning_type' => $planning_type]) ?>

        <a class="btn btn-next-week" href="<?= base_url('/helpdesk/planning/cw_planning') ?>"><span><?= lang('Helpdesk.btn_next_week') ?></span></a>
    </div>

    <table class="table-responsive<?= isset($classes) ? implode($classes) : ''?>">
        <thead>
            <tr>
                <th><button disabled class="btn btn-edit"></button></th>
                <?= view('Helpdesk\Common\planning_weekdays_row', ['planning_type' => $planning_type]) ?>
            </tr>
            <?= view('Helpdesk\Common\planning_schedules_row') ?>
        </thead>
        <tbody>
            <?php if (isset($lw_planning_data) && !empty($lw_planning_data)) : ?>
                <?php foreach ($lw_planning_data as $user) : ?>
                    <tr>
                        <?= view('Helpdesk\Common\planning_technician_name_column', $user) ?>

                        <?php foreach ($lw_periods as $period): ?>
                            <?= view('Helpdesk\Common\planning_technician_roles_row', ['period' => $user[$period]]) ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="21">
                        <?= lang('Helpdesk.err_no_technician_assigned')?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?= view('Helpdesk\Common\planning_bottom') ?>

    <?= view('Helpdesk\Common\planning_roles') ?>
</div>