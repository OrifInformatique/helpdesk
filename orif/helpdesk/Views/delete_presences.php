<?php

/**
 * delete_presences view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<?= view('Helpdesk\Common\body_start') ?>

<?= form_open(base_url('/helpdesk/presences/delete_presences/'.$id_presence)) ?>
    <?= form_hidden('delete_confirmation', true) ?>

    <div>
        <p><?= lang('Helpdesk.delete_confirmation') ?></p>
    </div>

    <div class="buttons-area">
        <a class="btn btn-back mb-3" href="<?= base_url('/helpdesk/presences/all_presences') ?>"><span><?= lang('Helpdesk.btn_cancel')?></span></a>
        <button type="submit" class="btn btn-delete mb-3"><span><?= lang('Helpdesk.btn_delete') ?></span></button>
    </div>
<?= form_close() ?>