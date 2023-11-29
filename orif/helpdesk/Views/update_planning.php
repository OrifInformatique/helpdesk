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

<?= form_open(base_url('/helpdesk/planning/update_planning/'.$planning_type)) ?>
    <div class="planning">
        <div class="d-flex justify-content-center roles">
            <div class="bg-green  border-xs-1 p-2 rounded rounded-3 mx-3"><?= lang('Helpdesk.role_1')?></div>
            <div class="bg-light-green border-xs-1 p-2 rounded rounded-3 mx-3"><?= lang('Helpdesk.role_2')?></div>
            <div class="bg-orange border-xs-1 p-2 rounded rounded-3 mx-3"><?= lang('Helpdesk.role_3')?></div>
        </div>

        <div class="week">
            <div></div>
            <div>
                <?= lang('Helpdesk.planning_of_week')?>
                <span class="start-date">
                    <?php switch($planning_type)
                        {
                            case 0:
                                echo date('d/m/Y', strtotime('monday this week')); 
                                break;

                            case 1:
                                echo date('d/m/Y', $_SESSION['helpdesk']['next_week']['monday']);
                                break;
                        }
                    ?>
                </span>
                <?= lang('Helpdesk.to')?>
                <span class="end-date">
                    <?php switch($planning_type)
                        {
                            case 0:
                                echo date('d/m/Y', strtotime('friday this week'));
                                break;

                            case 1:
                                echo date('d/m/Y', $_SESSION['helpdesk']['next_week']['friday']);
                                break;
                        }
                    ?>
                </span>
            </div>
            <div></div>
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
                <?php switch($planning_type)
                {
                    case 0: ?>
                        <tr>
                            <th></th>
                            <th colspan="4"><?= lang('Helpdesk.monday').' '.date('d', strtotime('monday this week')); ?></th>
                            <th colspan="4"><?= lang('Helpdesk.tuesday').' '.date('d', strtotime('tuesday this week')); ?></th>
                            <th colspan="4"><?= lang('Helpdesk.wednesday').' '.date('d', strtotime('wednesday this week')); ?></th>
                            <th colspan="4"><?= lang('Helpdesk.thursday').' '.date('d', strtotime('thursday this week')); ?></th>
                            <th colspan="4"><?= lang('Helpdesk.friday').' '.date('d', strtotime('friday this week')); ?></th>
                            <th></th>
                        </tr>
                    <?php break;?>
                    <?php case 1: ?>
                        <tr>
                            <th></th>
                            <th colspan="4"><?= lang('Helpdesk.monday').' '.date('d', $_SESSION['helpdesk']['next_week']['monday']); ?></th>
                            <th colspan="4"><?= lang('Helpdesk.tuesday').' '.date('d', $_SESSION['helpdesk']['next_week']['tuesday']); ?></th>
                            <th colspan="4"><?= lang('Helpdesk.wednesday').' '.date('d', $_SESSION['helpdesk']['next_week']['wednesday']); ?></th>
                            <th colspan="4"><?= lang('Helpdesk.thursday').' '.date('d', $_SESSION['helpdesk']['next_week']['thursday']); ?></th>
                            <th colspan="4"><?= lang('Helpdesk.friday').' '.date('d', $_SESSION['helpdesk']['next_week']['friday']); ?></th>
                            <th></th>
                        </tr>
                    <?php break;?>
                <?php } ?>
                <tr>
                    <th><?= lang('Helpdesk.technician') ?></th>
                    <?php for($i = 0; $i < 5; $i++): ?>
                        <th>8:00 10:00</th>
                        <th>10:00 12:00</th>
                        <th>12:45 15:00</th>
                        <th>15:00 16:57</th>
                    <?php endfor; ?>
                    <th></th>
                </tr>
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
                                    <select name="planning[<?= $planning['id_planning'] ?>][<?= $field ?>]">
                                        <?php
                                        $choices = array('', 1, 2, 3);
                                        foreach ($choices as $choice) 
                                        {
                                            $selected = ($planning[$field] == $choice) ? 'selected' : '';
                                            echo '<option value="' . $choice . '" ' . $selected . '>' . $choice . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            <?php endforeach; ?>
                            <td>
                                <a class="btn btn-danger" href="<?= base_url('/helpdesk/planning/delete_technician/'.$planning['fk_user_id'].'/0')?>">✕</a> <!-- ✕ => U+2715 | &#10005; -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="visible">
                        <td>
                            <a class="btn btn-success" href="<?= base_url('/helpdesk/planning/add_technician/0') ?>">✚</button> <!-- ✚ = U+271A | &#10010; -->
                        </td>
                        <td colspan="21"></td>
                    </tr>
                <?php elseif(isset($nw_planning_data)): ?>
                    <?php foreach($nw_planning_data as $nw_planning) : ?>
                        <tr>
                            <th>
                                <?= $nw_planning['last_name_user_data'].'<br>'.$nw_planning['first_name_user_data']; ?>
                                <input type="hidden" name="nw_planning[<?= $nw_planning['id_nw_planning'] ?>][id_nw_planning]" value="<?= $nw_planning['id_nw_planning'] ?>">
                                <input type="hidden" name="nw_planning[<?= $nw_planning['id_nw_planning'] ?>][fk_user_id]" value="<?= $nw_planning['fk_user_id'] ?>">
                            </th>
                            <?php foreach ($form_fields_data as $field) : ?>
                                <td>
                                    <select name="nw_planning[<?= $nw_planning['id_nw_planning'] ?>][<?= $field ?>]">
                                        <?php
                                        $choices = array('', 1, 2, 3);

                                        foreach ($choices as $choice) 
                                        {
                                            $selected = ($nw_planning[$field] == $choice) ? 'selected' : '';
                                            echo '<option value="' . $choice . '" ' . $selected . '>' . $choice . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            <?php endforeach; ?>
                            <td>
                                <a class="btn btn-danger" href="<?= base_url('/helpdesk/planning/delete_technician/'.$nw_planning['fk_user_id'].'/1')?>">✕</a> <!-- ✕ => U+2715 | &#10005; -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td>
                            <a class="btn btn-success" href="<?= base_url('/helpdesk/planning/add_technician/1') ?>">✚</button> <!-- ✚ = U+271A | &#10010; -->
                        </td>
                        <td colspan="21"></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="21">
                            <?= lang('Helpdesk.no_technician_assigned')?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="action-menu d-flex justify-content-center">
            <?php if(isset($planning_data) || isset($nw_planning_data)): ?>
                <input class="btn btn-success" type="submit" value="<?= lang('Helpdesk.btn_save')?>">
                <?= form_reset('', lang('Helpdesk.btn_reset'), ['class' => 'btn btn-warning']) ?>
            <?php endif; ?>
            <?php switch($planning_type)
            {
                case 0:
                    echo('<a class="btn btn-primary" href="'.base_url('/helpdesk/planning/cw_planning').'">'.lang('Helpdesk.btn_back').'</a>');
                    break;

                case 1:
                    echo('<a class="btn btn-primary" href="'.base_url('/helpdesk/planning/nw_planning').'">'.lang('Helpdesk.btn_back').'</a>');
                    break;
            }?>
        </div>
    </div>
</form>