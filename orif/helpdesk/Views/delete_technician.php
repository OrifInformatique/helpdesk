<?php

/**
 * delete_technician view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

?>

<!-- Title, if exists -->
<?php if(isset($title)){ echo ('<h2>'.$title.'</h2>');} ?>

<div class="container-fluid">
    <form method="POST" action="<?= base_url('helpdesk/home/deleteTechnician/'.$user_id.'/'.$planning_type) ?>">

        <input type="hidden" name="delete_confirmation" value="true">

        <div>
            <p><?php echo lang('Helpdesk.delete_confirmation')?></p>
        </div>

        <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home/technicianMenu/'.$user_id.'/'.$planning_type) ?>"><?php echo lang('Helpdesk.btn_cancel')?></a>

		<input class="btn btn-danger mb-3" type="submit" value="<?php echo lang('Helpdesk.btn_delete')?>">
    </form>
</div>