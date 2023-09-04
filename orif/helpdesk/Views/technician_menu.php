<?php

/**
 * technician menu view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

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

    <?php foreach($user as $user): ?>
        <div>
            <?php echo lang('Helpdesk.technician_menu').'<strong>'.$user['last_name_user_data'].' '.$user['first_name_user_data'].'</strong> ?'?>
        </div>
        <div>
            <a class="btn btn-primary mb-3" href="javascript:history.back()"><?php echo lang('Helpdesk.btn_cancel')?></a>
        </div>
    <?php endforeach; ?>