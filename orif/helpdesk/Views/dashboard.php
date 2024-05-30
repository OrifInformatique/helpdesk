<?php

/**
 * dashboard view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<?= view('Helpdesk\Common\body_start') ?>

<div id="img-link-data" data-img-link="<?= htmlspecialchars(base_url('/images/helpdesk/default_technician_picture.jpg')) ?>"></div>
<div id="alt-text-data" data-alt-text="<?= lang('Technician.alt_default_picture') ?>"></div>

<script src="<?= base_url('Scripts/technician_photo/technician_photo_error_handling.js')?>"></script>

<nav>
    <a class="btn btn-back" href="javascript:history.back()"><span><?= lang('Buttons.back')?></span></a>
</nav>

<div class="dashboard-container">
    <div class="dashboard-card big" id="details-card">
        <div class="dashboard-card-header">
            <?= view('\Helpdesk\Common\technician_photo', $user) ?>
        </div>
        <div class="dashboard-card-content">
            <h3><?= $user['last_name_user_data'].' '.$user['first_name_user_data']?></h3>
            <em><?= $user['initials_user_data'] ?></em>
            <strong><?= $role ?></strong>

        </div>
    </div>
    
    <div class="dashboard-card big" id="planning-card">
        <div class="dashboard-card-header">
            <h3><?= lang('Technician.plannings') ?></h3>
        </div>
        <div class="dashboard-card-content">
            <a class="btn btn-add" href="<?= base_url('helpdesk/planning/add_technician/0') ?>">
                <span><?= lang('Buttons.add_technician_actual_planning') ?></span>
            </a>
            <a class="btn btn-add" href="<?= base_url('helpdesk/planning/add_technician/1') ?>">
                <span><?= lang('Buttons.add_technician_next_planning') ?></span>
            </a>
            <a class="btn btn-delete" href="<?= base_url('helpdesk/planning/delete_technician/'.$user['id'].'/0') ?>">
                <span><?= lang('Buttons.delete_technician_actual_planning')?></span>
            </a>
            <a class="btn btn-delete" href="<?= base_url('helpdesk/planning/delete_technician/'.$user['id'].'/1') ?>">
                <span><?= lang('Buttons.delete_technician_next_planning')?></span>
            </a>
        </div>
    </div>
    
    <div class="dashboard-card small" id="presences-card">
        <div class="dashboard-card-header">
            <h3><?= lang('Technician.presences') ?></h3>
        </div>
        <div class="dashboard-card-content">
            <a class="btn btn-edit" href="<?= base_url('helpdesk/presences/technician_presences/'.$user['id']) ?>">
                <span><?= lang('Buttons.edit') ?></span>
            </a>
            <?php if(isset($id_presence)): ?>
                <a class="btn btn-delete" href="<?= base_url('helpdesk/presences/delete_presences/'.$id_presence) ?>">
                    <span><?= lang('Buttons.delete') ?></span>
                </a>
            <?php else: ?>
                <button class="btn btn-delete" disabled>
                    <span><?= lang('Buttons.delete') ?></span>
                </button>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if($isUserLoggedAdmin): ?>
        <div class="dashboard-card small" id="user-card">
            <div class="dashboard-card-header">
                <h3><?= lang('Technician.user') ?></h3>
            </div>
            <div class="dashboard-card-content">
                <a class="btn btn-edit" href="<?= base_url('helpdesk/user/helpdesk_save_user/'.$user['id']) ?>">
                    <span><?= lang('Buttons.edit') ?></span>
                </a>
                <a class="btn btn-delete" href="<?= base_url('helpdesk/user/helpdesk_delete_user/'.$user['id']) ?>">
                    <span><?= lang('Buttons.delete') ?></span>
                </a>
            </div>
        </div>
    <?php endif ?>
</div>