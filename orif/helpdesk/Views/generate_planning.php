<?php

/**
 * holiday view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

if(isset($users))
{
    foreach($users as $user => $fields)
    {
        echo '<div id="'.$fields['id'].'-data">';

        foreach($fields as $field => $value)
        {
            echo '<div data-'.$field.'="'.$value.'"></div>';
        }

        echo '</div>';
    }
}

?>

<script src="<?= base_url('Scripts/planningGeneration/text_animation.js')?>" defer></script>

<div id="waiting-text" class="d-flex justify-content-center align-center">
    <?= lang('Helpdesk.planning_generation') ?>
</div>