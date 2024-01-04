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
    <a class="btn btn-presences" href="<?= base_url('/helpdesk/presences/all_presences') ?>"><span><?= lang('Helpdesk.btn_all_presences')?></span></a>
    <a class="btn btn-holidays" href="<?= base_url('/helpdesk/holidays/holidays_list') ?>"><span><?= lang('Helpdesk.btn_holiday')?></span></a>
    <a class="btn btn-terminal" href="<?= base_url('/helpdesk/terminal/display') ?>" target="_blank"><span><?= lang('Helpdesk.btn_terminal')?></span></a>
</nav>