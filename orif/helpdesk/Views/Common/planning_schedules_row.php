<?php

/**
 * Planning scheludes row in planning table component
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<tr>
    <th><?= lang('MiscTexts.technician')?></th>
    <?php for($i = 0; $i < 5; $i++): ?>
        <th><?= lang('Time.08:00').' '.lang('Time.10:00') ?></th>
        <th><?= lang('Time.10:00').' '.lang('Time.12:00') ?></th>
        <th><?= lang('Time.12:45').' '.lang('Time.15:00') ?></th>
        <th><?= lang('Time.15:00').' '.lang('Time.16:57') ?></th>
    <?php endfor; ?>
    <?= $update_extra_cell ?? '' ?>
</tr>