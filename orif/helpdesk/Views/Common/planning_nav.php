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
    <a class="btn btn-primary mb-3" href="<?= base_url('/helpdesk/presences/all_presences') ?>"><?= lang('Helpdesk.btn_all_presences')?></a>
    <a class="btn btn-primary mb-3" href="<?= base_url('/helpdesk/holidays/holidays_list') ?>"><?= lang('Helpdesk.btn_holiday')?></a>
    <a class="btn btn-primary mb-3" href="<?= base_url('/helpdesk/terminal/display') ?>"><?= lang('Helpdesk.btn_terminal')?></a>
</nav>