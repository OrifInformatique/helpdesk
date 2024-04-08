<?php

/**
 * Nav in plannings pages component
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<nav>
    <a class="btn btn-presences" href="<?= base_url('/helpdesk/presences/presences_list') ?>"><span><?= lang('Buttons.presences_list')?></span></a>
    <a class="btn btn-holidays" href="<?= base_url('/helpdesk/holidays/holidays_list') ?>"><span><?= lang('Buttons.holiday')?></span></a>
    <a class="btn btn-terminal btn-target-new-window" href="<?= base_url('/helpdesk/terminal/display/preview') ?>" target="_blank"><span><?= lang('Buttons.terminal')?></span></a>
</nav>