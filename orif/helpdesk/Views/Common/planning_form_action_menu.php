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

<div class="action-menu ignore_presences_check_option">
    <?= form_label(lang('MiscTexts.ignore_presences_check'), 'ignore_presences_check') ?>
    <input type="checkbox" name="ignore_presences_check" id="ignore_presences_check">
</div>

<?php if(isset($update) && $update): ?>
    <div class="action-menu delete-planning-option">
        <a class="btn btn-delete" href="<?= base_url('/helpdesk/planning/delete_planning/'.$planning_type) ?>"><span><?= lang('Buttons.delete_planning') ?></span></a>
    </div>
<?php endif; ?>

<div class="action-menu">
    <button type="submit" class="btn btn-save"><span><?= lang('Buttons.save') ?></span></button>
    <button type="reset" class="btn btn-reset"><span><?= lang('Buttons.reset') ?></span></button>

    <?php switch($planning_type)
    {
        case 0:
            echo '<a class="btn btn-back" href="'.base_url('/helpdesk/planning/cw_planning').'"><span>'.lang('Buttons.back').'</span></a>';
            break;

        case 1:
            echo '<a class="btn btn-back" href="'.base_url('/helpdesk/planning/nw_planning').'"><span>'.lang('Buttons.back').'</span></a>';
            break;
    } ?>
</div>