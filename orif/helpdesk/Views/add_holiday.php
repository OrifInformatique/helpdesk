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

<div id="holiday">

<?= view('Helpdesk\Common\body_start') ?>

<div>
    <?php if(isset($holiday['id_holiday'])): ?>
        <?= form_open(base_url('/helpdesk/holidays/save_holiday/'.$holiday['id_holiday'])) ?>
        <?= form_hidden('id_holiday', esc($holiday['id_holiday'])) ?>
    <?php else: ?>
        <?= form_open(base_url('/helpdesk/holidays/save_holiday/0')) ?>
        <?= form_hidden('id_holiday', 0) ?>
    <?php endif; ?>

    <div class="form-fields">
        <?= form_fieldset() ?>
            <?= form_label(lang('MiscTexts.holiday_name'), 'holiday_name')?>
            <?= form_input('holiday_name', set_value('holiday_name', isset($holiday['name_holiday']) ? $holiday['name_holiday'] : '', true)) ?>
            <p class="form-field-error"><?= isset($form_errors['holiday_name']) ? esc($form_errors['holiday_name']) : '' ?></p>
        <?= form_fieldset_close() ?>
            
        <?= form_fieldset() ?>
            <?= form_label(lang('MiscTexts.start_date'), 'start_date')?>
            <?= form_input('start_date', set_value('start_date', isset($holiday['start_date_holiday']) ? $holiday['start_date_holiday'] : '', true), '', 'datetime-local') ?>
            <p class="form-field-error"><?= isset($form_errors['start_date']) ? esc($form_errors['start_date']) : '' ?></p>
        <?= form_fieldset_close() ?>
            
        <?= form_fieldset() ?>
            <?= form_label(lang('MiscTexts.end_date'), 'end_date')?>
            <?= form_input('end_date', set_value('end_date', isset($holiday['end_date_holiday']) ? $holiday['end_date_holiday'] : '', true), '', 'datetime-local') ?>
            <p class="form-field-error"><?= isset($form_errors['end_date']) ? esc($form_errors['end_date']) : '' ?></p>
        <?= form_fieldset_close() ?>
    </div>

    <div class="buttons-area">
        <button type="submit" class="btn btn-save"><span><?= lang('Buttons.save') ?></span></button>
        <button type="reset" class="btn btn-reset"><span><?= lang('Buttons.reset') ?></span></button>
        <?php if(isset($holiday['id_holiday'])): ?>
            <a class="btn btn-delete" href="<?= base_url('/helpdesk/holidays/delete_holiday/'.$holiday['id_holiday']) ?>"><span><?= lang('Buttons.delete')?></span></a>
            <?php endif; ?>
    </div>
    <div class="buttons-area">
        <a class="btn btn-back mt-2" href="<?= base_url('/helpdesk/holidays/holidays_list') ?>"><span><?= lang('Buttons.back')?></span></a>
    </div>
<?= form_close('</div>') ?>

</div>