<?php

/**
 * update_planning view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<?= view('Helpdesk\Common\body_start') ?>

<?= form_open(base_url('/helpdesk/planning/update_planning/'.$planning_type)) ?>
    <div class="planning">
        <?= view('Helpdesk\Common\planning_roles') ?>

        <div class="week">
            <div></div>
            <?= view('Helpdesk\Common\planning_week', ['planning_type' => $planning_type]) ?>
            <div></div>
        </div>

        <table class="table-responsive<?= isset($classes) ? implode($classes) : ''?>">
        <thead>
            <tr>
                <th><a class="btn btn-add" href="<?= base_url('/helpdesk/planning/add_technician/'.$planning_type) ?>" title="<?= lang('Helpdesk.btn_add_technician') ?>"></a></th>
                <?= view('Helpdesk\Common\planning_weekdays_row', ['planning_type' => $planning_type]) ?>
                <th></th>
            </tr>
            <?= view('Helpdesk\Common\planning_schedules_row', ['update_extra_cell' => '<th></th>']) ?>
        </thead>
            <tbody>
                <?php if(isset($planning_data)) : ?>
                    <?php foreach($planning_data as $planning) : ?>
                        <?= form_hidden("planning[".$planning['id_planning']."][id_planning]", $planning['id_planning']) ?>
                        <?= form_hidden("planning[".$planning['id_planning']."][fk_user_id]", $planning['fk_user_id']) ?>
                        <tr>
                            <th>
                                <?= $planning['last_name_user_data'].'<br>'.$planning['first_name_user_data']; ?>
                            </th>

                            <?php foreach ($form_fields_data as $field): ?>
                                <td>
                                <?php
                                    $options = ['' => '', '1' => '1', '2' => '2', '3' => '3'];

                                    echo form_dropdown('planning['.$planning['id_planning'].']['.$field.']', $options, isset($old_edit_plan_form['planning'][$planning['id_planning']][$field]) ? $old_edit_plan_form['planning'][$planning['id_planning']][$field] : $planning[$field]);
                                    ?>
                                </td>
                            <?php endforeach; ?>
                            <td>
                                <a class="btn btn-delete" href="<?= base_url('/helpdesk/planning/delete_technician/'.$planning['fk_user_id'].'/0')?>" title="<?= lang('Helpdesk.btn_delete_tech_from_planning')?>"></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php elseif(isset($nw_planning_data)): ?>
                    <?php foreach($nw_planning_data as $nw_planning) : ?>
                        <?= form_hidden("nw_planning[".$nw_planning['id_nw_planning']."][id_nw_planning]", $nw_planning['id_nw_planning']) ?>
                        <?= form_hidden("nw_planning[".$nw_planning['id_nw_planning']."][fk_user_id]", $nw_planning['fk_user_id']) ?>
                        <tr>
                            <th>
                                <?= $nw_planning['last_name_user_data'].'<br>'.$nw_planning['first_name_user_data']; ?>
                            </th>
                            <?php foreach ($form_fields_data as $field) : ?>
                                <td>
                                    <?php
                                    $options = ['' => '', '1' => '1', '2' => '2', '3' => '3'];

                                    echo form_dropdown('nw_planning['.$nw_planning['id_nw_planning'].']['.$field.']', $options, isset($old_edit_plan_form['nw_planning'][$nw_planning['id_nw_planning']][$field]) ? $old_edit_plan_form['nw_planning'][$nw_planning['id_nw_planning']][$field] : $nw_planning[$field]);
                                    ?>
                                </td>
                            <?php endforeach; ?>
                            <td>
                                <a class="btn btn-delete" href="<?= base_url('/helpdesk/planning/delete_technician/'.$nw_planning['fk_user_id'].'/1')?>" title="<?= lang('Helpdesk.btn_delete_tech_from_planning')?>"></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="21">
                            <?= lang('Helpdesk.no_technician_assigned')?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?= view('Helpdesk\Common\planning_form_action_menu', ['planning_type' => $planning_type]) ?>
    </div>
<?= form_close() ?>