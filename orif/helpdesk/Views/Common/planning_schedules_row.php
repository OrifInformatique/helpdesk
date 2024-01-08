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
    <th><?= lang('Helpdesk.technician')?></th>
    <?php for($i = 0; $i < 5; $i++): ?>
        <th>8:00 10:00</th>
        <th>10:00 12:00</th>
        <th>12:45 15:00</th>
        <th>15:00 16:57</th>
    <?php endfor; ?>
    <?= $update_extra_cell ?? '' ?>
</tr>