<?php

/**
 * add_holiday view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<div id="add-holiday">
    <?php if(isset($holiday['id_holiday'])): ?>
        <?= form_open(base_url('/holidays/save_holiday/'.$holiday['id_holiday'])) ?>
        <?= form_hidden('id_holiday', esc($holiday['id_holiday'])) ?>
    <?php else: ?>
        <?= form_open(base_url('/holidays/save_holiday/')) ?>
        <?= form_hidden('id_holiday', 0) ?>
    <?php endif; ?>

        <div class="d-flex justify-content-center">

            <?= form_fieldset() ?>
                <?= form_label(lang('Helpdesk.holiday_name'), 'holiday_name')?>
                <?= form_input('holiday_name', set_value('holiday_name', isset($holiday['name_holiday']) ? $holiday['name_holiday'] : '', true)) ?>
                <p class="form-field-error"><?= isset($form_errors['holiday_name']) ? esc($form_errors['holiday_name']) : '' ?></p>
                <?= form_fieldset_close() ?>
                
                <?= form_fieldset() ?>
                <?= form_label(lang('Helpdesk.start_date'), 'start_date')?>
                <?= form_input('start_date', set_value('start_date', isset($holiday['start_date_holiday']) ? $holiday['start_date_holiday'] : '', true), '', 'datetime-local') ?>
                <p class="form-field-error"><?= isset($form_errors['start_date']) ? esc($form_errors['start_date']) : '' ?></p>
                <?= form_fieldset_close() ?>
                
                <?= form_fieldset() ?>
                <?= form_label(lang('Helpdesk.end_date'), 'end_date')?>
                <?= form_input('end_date', set_value('end_date', isset($holiday['end_date_holiday']) ? $holiday['end_date_holiday'] : '', true), '', 'datetime-local') ?>
                <p class="form-field-error"><?= isset($form_errors['end_date']) ? esc($form_errors['end_date']) : '' ?></p>
            <?= form_fieldset_close() ?>
        </div>

        <div class="buttons-area">
            <?= form_submit('', lang('Helpdesk.btn_save'), ['class' => 'btn btn-success']) ?>
            <?= form_reset('', lang('Helpdesk.btn_reset'), ['class' => 'btn btn-warning']) ?>
            <?php if(isset($holiday['id_holiday'])): ?>
                <a class="btn btn-danger" href="<?= base_url('/holidays/delete_holiday/'.$holiday['id_holiday']) ?>"><?= lang('Helpdesk.btn_delete')?></a>
                <?php endif; ?>
        </div>
        <div class="buttons-area">
            <a class="btn btn-primary mt-4" href="<?= base_url('/holidays/holidays_list') ?>"><?= lang('Helpdesk.btn_back')?></a>
        </div>
    <?= form_close('</div>') ?>