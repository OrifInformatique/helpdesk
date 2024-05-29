<?php

/**
 * assistance view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<?= view('Helpdesk\Common\body_start') ?>

<nav><a class="btn btn-back" href="javascript:history.back()"><span><?= lang('Buttons.back')?></span></a></nav>

<div id="assistance-container">
    <?php foreach($assistances as $assistance): ?>
        <?= view('Helpdesk\Common\assistance_element', $assistance) ?>
    <?php endforeach; ?>
</div>