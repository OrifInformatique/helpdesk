<?php

/**
 * Planning roles component
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<div class="roles">
    <div>
        <div class="bg-green"><?= lang('HelpdeskLexicon/Roles.first_technician_role')?></div>
        <div class="bg-light-green"><?= lang('HelpdeskLexicon/Roles.second_technician_role')?></div>
        <div class="bg-orange"><?= lang('HelpdeskLexicon/Roles.third_technician_role')?></div>
    </div>
    <a href="<?= base_url('/helpdesk/home/assistance') ?>"><?= lang('MiscTexts.what_does_it_mean') ?></a>
</div>