<?php

/**
 * delete_entry view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<?= view('Helpdesk\Common\body_start') ?>

<?= form_open($delete_url, ['id' => 'delete-entry']) ?>
    <?= form_hidden('delete_confirmation', true) ?>

    <div>
        <p><?= lang('MiscTexts.delete_confirmation') ?></p>
        <p><?= $entry ?></p>
    </div>

    <div class="buttons-area">
        <a class="btn btn-back mb-3" href="<?= $btn_back_url ?>"><span><?= lang('Buttons.cancel')?></span></a>
        <button type="submit" class="btn btn-delete mb-3"><span><?= lang('Buttons.delete') ?></span></button>
    </div>
<?= form_close() ?>