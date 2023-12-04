<?php

/**
 * Planning form action menus in planning table component (add_technician.php and update_planning.php)
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

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