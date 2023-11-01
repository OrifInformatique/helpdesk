<?php

/**
 * delete_holiday view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<div class="container-fluid">
    <?php if(isset($title)){echo '<h2>'.$title.'</h2>';} ?>

    <?php if(isset($success)): ?>
        <div class="d-flex justify-content-center">
            <?= ('<p class="success">'.$success.'</p>'); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($error)): ?>
        <div class="d-flex justify-content-center">
            <?= ('<p class="error">'.$error.'</p>'); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= base_url('helpdesk/home/deleteHoliday/'.$id_holiday) ?>">

        <input type="hidden" name="id_holiday" value="<?= $id_holiday; ?>">
        <input type="hidden" name="delete_confirmation" value="true">

        <div>
            <p><?= lang('Helpdesk.delete_confirmation')?></p>
        </div>

        <div class="buttons-area">
            <a class="btn btn-primary mb-3" href="javascript:history.back()"><?= lang('Helpdesk.btn_cancel')?></a>
            <input class="btn btn-danger mb-3" type="submit" value="<?= lang('Helpdesk.btn_delete')?>">
        </div>
    </form>
</div>