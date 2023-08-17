<?php

/**
 * technician menu view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

?>

<div class="container-fluid">

    <!-- Title, if exists -->
    <?php if(isset($title)){ echo ('<h2>'.$title.'</h2>');} ?>

    <?php foreach($user as $user): ?>
        <div>
            <?php echo lang('Helpdesk.technician_menu').'<strong>'.$user['last_name_user_data'].' '.$user['first_name_user_data'].'</strong> ?'?>
        </div>
        <div>
            <?php switch($planning_type){
                case 0: ?>
                    <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home/planning') ?>">
                        <?php echo lang('Helpdesk.btn_cancel')?>
                    </a>
                <?php break;
                case 1: ?>
                    <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home/nw_planning') ?>">
                        <?php echo lang('Helpdesk.btn_cancel')?>
                    </a>
                <?php break;
            } ?>
            <br>
            <a class="btn btn-danger mb-3" href="<?= base_url('helpdesk/home/deleteTechnician/'.$user['id'].'/'.$planning_type) ?>">
                <?php echo lang('Helpdesk.btn_delete_from_planning')?>
            </a>
        </div>
    <?php endforeach; ?>