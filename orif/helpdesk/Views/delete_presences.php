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

<?= form_open(base_url('/presences/delete_presences/'.$id_presence)) ?>
    <?= form_hidden('delete_confirmation', true) ?>

    <div>
        <p><?= lang('Helpdesk.delete_confirmation') ?></p>
    </div>

    <div class="buttons-area">
        <a class="btn btn-primary mb-3" href="<?= base_url('/presences/all_presences') ?>"><?= lang('Helpdesk.btn_cancel')?></a>
        <?= form_submit('', lang('Helpdesk.btn_delete'), ['class' => 'btn btn-danger mb-3']) ?>
    </div>
<?= form_close() ?>