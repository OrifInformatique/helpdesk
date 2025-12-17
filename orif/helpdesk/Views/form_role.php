<?php
/**
 * Form Role View
 *
 * @author      Orif (WaAd)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
$update = !is_null($role);
?>
<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('role_lang.title_role_'.($update ? 'update' : 'new')) ?? ($update ? 'Modifier le rôle' : 'Nouveau rôle'); ?></h1>
        </div>
    </div>
    
    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'role_form',
        'name' => 'role_form'
    );
    echo form_open('helpdesk/role/saveRole', $attributes);
    ?>
        <!-- ERROR MESSAGES -->
        <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger" role="alert">
            <ul>
                <?php foreach ($errors as $error): ?>
                <li><?= esc($error); ?></li>
                <?php endforeach ?>
            </ul>
        </div>
        <?php endif ?>

        <!-- HIDDEN FIELD FOR ROLE ID -->
        <?php if ($update): ?>
            <?= form_hidden('id_role', $role['id_role']); ?>
        <?php endif ?>

        <!-- ROLE FIELDS -->
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <?= form_label(lang('role_lang.field_name_role') ?? 'Nom du rôle', 'name_role', ['class' => 'form-label']); ?>
                    <?= form_input('name_role', $old_data['name_role'] ?? $role['name_role'] ?? '', [
                        'maxlength' => 255,
                        'class' => 'form-control',
                        'id' => 'name_role',
                        'required' => ''
                    ]); ?>
                </div>
                <div class="form-group">
                    <?= form_label(lang('role_lang.field_priority_role') ?? 'Priorité', 'priority_role', ['class' => 'form-label']); ?>
                    <?= form_input('priority_role', $old_data['priority_role'] ?? $role['priority_role'] ?? '', [
                        'type' => 'number',
                        'min' => 1,
                        'class' => 'form-control',
                        'id' => 'priority_role',
                        'required' => ''
                    ]); ?>
                </div>
            </div>
        </div>

        <!-- ASSIGNATION FIELDS -->
        <div class="row">
            <div class="col-sm-12">
                <h3><?= lang('role_lang.title_assignation_first_technician') ?? 'Assignation 1er technicien'; ?></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <?= form_label(lang('role_lang.field_min_assignation_first_technician_role') ?? 'Min assignation 1er technicien', 'min_assignation_first_technician_role', ['class' => 'form-label']); ?>
                    <?= form_input('min_assignation_first_technician_role', $old_data['min_assignation_first_technician_role'] ?? $role['min_assignation_first_technician_role'] ?? '', [
                        'type' => 'number',
                        'min' => 0,
                        'class' => 'form-control',
                        'id' => 'min_assignation_first_technician_role'
                    ]); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <?= form_label(lang('role_lang.field_max_assignation_first_technician_role') ?? 'Max assignation 1er technicien', 'max_assignation_first_technician_role', ['class' => 'form-label']); ?>
                    <?= form_input('max_assignation_first_technician_role', $old_data['max_assignation_first_technician_role'] ?? $role['max_assignation_first_technician_role'] ?? '', [
                        'type' => 'number',
                        'min' => 0,
                        'class' => 'form-control',
                        'id' => 'max_assignation_first_technician_role'
                    ]); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <h3><?= lang('role_lang.title_assignation_second_technician') ?? 'Assignation 2ème technicien'; ?></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <?= form_label(lang('role_lang.field_min_assignation_second_technician_role') ?? 'Min assignation 2ème technicien', 'min_assignation_second_technician_role', ['class' => 'form-label']); ?>
                    <?= form_input('min_assignation_second_technician_role', $old_data['min_assignation_second_technician_role'] ?? $role['min_assignation_second_technician_role'] ?? '', [
                        'type' => 'number',
                        'min' => 0,
                        'class' => 'form-control',
                        'id' => 'min_assignation_second_technician_role'
                    ]); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <?= form_label(lang('role_lang.field_max_assignation_second_technician_role') ?? 'Max assignation 2ème technicien', 'max_assignation_second_technician_role', ['class' => 'form-label']); ?>
                    <?= form_input('max_assignation_second_technician_role', $old_data['max_assignation_second_technician_role'] ?? $role['max_assignation_second_technician_role'] ?? '', [
                        'type' => 'number',
                        'min' => 0,
                        'class' => 'form-control',
                        'id' => 'max_assignation_second_technician_role'
                    ]); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <h3><?= lang('role_lang.title_assignation_third_technician') ?? 'Assignation 3ème technicien'; ?></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <?= form_label(lang('role_lang.field_min_assignation_third_technician_role') ?? 'Min assignation 3ème technicien', 'min_assignation_third_technician_role', ['class' => 'form-label']); ?>
                    <?= form_input('min_assignation_third_technician_role', $old_data['min_assignation_third_technician_role'] ?? $role['min_assignation_third_technician_role'] ?? '', [
                        'type' => 'number',
                        'min' => 0,
                        'class' => 'form-control',
                        'id' => 'min_assignation_third_technician_role'
                    ]); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <?= form_label(lang('role_lang.field_max_assignation_third_technician_role') ?? 'Max assignation 3ème technicien', 'max_assignation_third_technician_role', ['class' => 'form-label']); ?>
                    <?= form_input('max_assignation_third_technician_role', $old_data['max_assignation_third_technician_role'] ?? $role['max_assignation_third_technician_role'] ?? '', [
                        'type' => 'number',
                        'min' => 0,
                        'class' => 'form-control',
                        'id' => 'max_assignation_third_technician_role'
                    ]); ?>
                </div>
            </div>
        </div>
                    
        <!-- FORM BUTTONS -->
        <div class="row">
            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('helpdesk/role/listRole'); ?>"><?= lang('common_lang.btn_cancel') ?? 'Annuler'; ?></a>
                <?= form_submit('save', lang('common_lang.btn_save') ?? 'Enregistrer', ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    <?= form_close(); ?>
</div>

