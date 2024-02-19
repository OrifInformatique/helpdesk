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
    <a class="btn btn-presences" href="<?= base_url('/helpdesk/presences/presences_list') ?>"><span><?= lang('Helpdesk.btn_presences_list')?></span></a>
    <a class="btn btn-holidays" href="<?= base_url('/helpdesk/holidays/holidays_list') ?>"><span><?= lang('Helpdesk.btn_holiday')?></span></a>
    <a class="btn btn-terminal btn-target-new-window" href="<?= base_url('/helpdesk/terminal/display/preview') ?>" target="_blank"><span><?= lang('Helpdesk.btn_terminal')?></span></a>
</nav>