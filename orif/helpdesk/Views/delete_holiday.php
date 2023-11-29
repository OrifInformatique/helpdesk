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

<?= form_open(base_url('/holidays/delete_holiday/'.$id_holiday)) ?>
    <?= form_hidden('delete_confirmation', true) ?>

    <div>
        <p><?= lang('Helpdesk.delete_confirmation') ?></p>
    </div>

    <div class="buttons-area">
        <a class="btn btn-primary mb-3" href="<?= base_url('/holidays/save_holiday/'.$id_holiday) ?>"><?= lang('Helpdesk.btn_cancel')?></a>
        <?= form_submit('', lang('Helpdesk.btn_delete'), ['class' => 'btn btn-danger mb-3']) ?>
    </div>
<?= form_close() ?>