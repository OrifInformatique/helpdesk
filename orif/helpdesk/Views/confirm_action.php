<?php

/**
 * confirm_action view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<?= view('Helpdesk\Common\body_start') ?>

<div id="confirm-action">
    <div>
        <p>
            <?= lang('Helpdesk.confirm_action') ?> <br>
            <?php if(isset($irreversible_action) && $irreversible_action): ?>
                <strong><?= lang('Helpdesk.irreversible_action') ?></strong>
            <?php endif; ?>
        </p>

        <?php if(isset($action['desc']) && !empty($action['desc'])): ?>
            <p><em><?= $action['desc'] ?></em></p>
        <?php endif; ?>
    </div>

    <div class="buttons-area">
        <a class="btn btn-back mb-3" href="<?= $back_btn_url ?>"><span><?= lang('Helpdesk.btn_cancel')?></span></a>

        <?php if(isset($alt_action) && !empty($alt_action)): ?>
            <a class="btn btn-alt btn-<?= $alt_action['css'] ?> mb-3" href="<?= $alt_action['url'] ?>">
                <span><?= lang('Helpdesk.btn_'.$alt_action['name']) ?></span>
            </a>
        <?php endif; ?>

        <a class="btn btn-<?= $action['css'] ?> mb-3" href="<?= $action['url'] ?>">
            <span><?= lang('Helpdesk.btn_'.$action['name']) ?></span>
        </a>
    </div>
</div>