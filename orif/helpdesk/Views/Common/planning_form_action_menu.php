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

<div class="action-menu">
    <button type="submit" class="btn btn-save"><span><?= lang('Helpdesk.btn_save') ?></span></button>
    <button type="reset" class="btn btn-reset"><span><?= lang('Helpdesk.btn_reset') ?></span></button>

    <?php switch($planning_type)
    {
        case 0:
            echo '<a class="btn btn-back" href="'.base_url('/helpdesk/planning/cw_planning').'"><span>'.lang('Helpdesk.btn_back').'</span></a>';
            break;

        case 1:
            echo '<a class="btn btn-back" href="'.base_url('/helpdesk/planning/nw_planning').'"><span>'.lang('Helpdesk.btn_back').'</span></a>';
            break;
    } ?>
</div>