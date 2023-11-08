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

<form method="POST" action="<?= base_url('helpdesk/home/deletePresences/'.$id_presence) ?>">
    <input type="hidden" name="delete_confirmation" value="true">

    <div>
        <p><?= lang('Helpdesk.delete_confirmation')?></p>
    </div>

    <div class="buttons-area">
        <a class="btn btn-primary mb-3" href="javascript:history.back()"><?= lang('Helpdesk.btn_cancel')?></a>
        <input class="btn btn-danger mb-3" type="submit" value="<?= lang('Helpdesk.btn_delete')?>">
    </div>
</form>