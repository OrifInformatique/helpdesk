<?php

/**
 * holiday view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

?>

<script src="<?= base_url('Scripts/planningGeneration/text_animation.js')?>" defer></script>

<?php
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

<div class="container-fluid">

    <!-- Title, if exists -->
    <?php if(isset($title)){
        echo ('<h2>' . $title . '</h2>');
    } ?>

    <!-- Success message, if exists -->
    <?php if(isset($success)): ?>
        <div class="d-flex justify-content-center">
            <?php echo ('<p class="success">'.$success.'</p>'); ?>
        </div>
    <?php endif; ?>

    <!-- Error message, if exists -->
    <?php if(isset($error)): ?>
        <div class="d-flex justify-content-center">
            <?php echo ('<p class="error">'.$error.'</p>'); ?>
        </div>
    <?php endif; ?>

    <div id="waiting-text" class="d-flex justify-content-center align-center">
        <?= lang('Helpdesk.planning_generation') ?>
    </div>
</div>