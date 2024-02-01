<?php

/**
 * add_technician view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<?= view('Helpdesk\Common\body_start') ?>

<?= form_open(base_url('/helpdesk/planning/add_technician/'.$planning_type)) ?>
    <div class="planning-table">

        <div class="week">
            <div></div>
            <?= view('Helpdesk\Common\planning_week', ['planning_type' => $planning_type]) ?>
            <div></div>
        </div>

        <table class="table-responsive<?= isset($classes) ? implode($classes) : ''?>">
            <thead>
                <tr>
                    <th></th>
                    <?= view('Helpdesk\Common\planning_weekdays_row', ['planning_type' => $planning_type]) ?>
                </tr>
                <?= view('Helpdesk\Common\planning_schedules_row') ?>
            </thead>
            <tbody>
                <tr>
                    <th>
                        <?php 
                        $options = ['' => ''];

                        foreach($users as $user)
                        {
                            $options[$user['id']] = $user['last_name_user_data'].' '.$user['first_name_user_data'];
                        }
                        echo form_dropdown('technician', $options, isset($old_add_tech_form['technician']) ? $old_add_tech_form['technician'] : '')?>
                    </th>
                    <?php for($i = 0; $i < 20; $i++): ?>
                        <td>
                            <?php 
                            $options = ['' => '', '1' => '1', '2' => '2', '3' => '3'];

                            switch($planning_type)
                            {
                                case 0:
                                    echo form_dropdown($_SESSION['helpdesk']['cw_periods'][$i], $options, isset($old_add_tech_form[$_SESSION['helpdesk']['cw_periods'][$i]]) ? $old_add_tech_form[$_SESSION['helpdesk']['cw_periods'][$i]] : '');
                                    break;

                                case 1:
                                    echo form_dropdown($_SESSION['helpdesk']['nw_periods'][$i], $options, isset($old_add_tech_form[$_SESSION['helpdesk']['nw_periods'][$i]]) ? $old_add_tech_form[$_SESSION['helpdesk']['nw_periods'][$i]] : '');
                                    break;
                            }?>
                        </td>
                    <?php endfor; ?>
                </tr>
            </tbody>
        </table>
        <?= view('Helpdesk\Common\planning_form_action_menu', ['planning_type' => $planning_type]) ?>

        <?= view('Helpdesk\Common\planning_roles') ?>
    </div>
<?= form_close() ?>