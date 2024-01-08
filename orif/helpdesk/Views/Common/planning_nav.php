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
    <a class="btn btn-presences mb-1" href="<?= base_url('/helpdesk/presences/all_presences') ?>"><span><?= lang('Helpdesk.btn_all_presences')?></span></a>
    <a class="btn btn-holidays mb-1" href="<?= base_url('/helpdesk/holidays/holidays_list') ?>"><span><?= lang('Helpdesk.btn_holiday')?></span></a>
    <a class="btn btn-terminal mb-1" href="<?= base_url('/helpdesk/terminal/display/preview') ?>" target="_blank"><span><?= lang('Helpdesk.btn_terminal')?></span></a>
</nav>