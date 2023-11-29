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

<?= form_open(base_url('/helpdesk/planning/add_technician/'.$planning_type)) ?>
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
                        </tr>
                        <?php break;?>
                <?php } ?>

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
                <tr>
                    <td>
                        <?php 
                        $options = ['' => ''];

                        foreach($users as $user)
                        {
                            $options[$user['id']] = $user['last_name_user_data'].' '.$user['first_name_user_data'];
                        }
                        
                        echo form_dropdown('technician', $options, '')
                        ?>
                    </td>
                    <?php for($i = 0; $i < 20; $i++): ?>
                        <td>
                            <?php 
                            $options = ['' => '', '1' => '1', '2' => '2', '3' => '3'];

                            switch($planning_type)
                            {
                                case 0:
                                    echo form_dropdown($_SESSION['helpdesk']['cw_periods'][$i], $options, '');
                                    break;

                                case 1:
                                    echo form_dropdown($_SESSION['helpdesk']['nw_periods'][$i], $options, '');
                                    break;
                            }?>
                        </td>
                    <?php endfor; ?>
                </tr>
            </tbody>
        </table>
        <div class="action-menu d-flex justify-content-center">
            <?= form_submit('', lang('Helpdesk.btn_save'), ['class' => 'btn btn-success']) ?>
            <?= form_reset('', lang('Helpdesk.btn_reset'), ['class' => 'btn btn-warning']) ?>
            
            <?php switch($planning_type)
            {
                case 0:
                    echo '<a class="btn btn-primary" href="'.base_url('/helpdesk/planning/cw_planning').'">'.lang('Helpdesk.btn_back').'</a>';
                    break;

                case 1:
                    echo '<a class="btn btn-primary" href="'.base_url('/helpdesk/planning/nw_planning').'">'.lang('Helpdesk.btn_back').'</a>';
                    break;
            } ?>
        </div>
    </div>
<?= form_close() ?>