<?php

/**
 * Planning technicians roles row in planning table component
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

$class = '';
switch($period)
{
    case 1:
        $class = 'bg-green';
        break;
    case 2:
        $class = 'bg-light-green';
        break;
    case 3:
        $class = 'bg-orange';
        break;
}?>

<td class="<?= $class ?>">
    <?= $period; ?>
</td>