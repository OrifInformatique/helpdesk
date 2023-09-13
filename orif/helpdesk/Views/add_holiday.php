<?php

/**
 * holiday view
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
            <?= ('<p class="success">'.$success.'</p>'); ?>
        </div>
    <?php endif; ?>

    <!-- Error message, if exists -->
    <?php if(isset($error)): ?>
        <div class="d-flex justify-content-center">
            <?= ('<p class="error">'.$error.'</p>'); ?>
        </div>
    <?php endif; ?>

    

    <?php if(isset($holiday['id_holiday'])): ?>
        <form method="POST" action="<?= base_url('helpdesk/home/saveHoliday/'.$holiday['id_holiday']) ?>">
    <?php else: ?>
        <form method="POST" action="<?= base_url('helpdesk/home/saveHoliday') ?>">
    <?php endif; ?>
    
        <div class="d-flex justify-content-center">
            <fieldset>
                <label for="holiday_name"><?= lang('Helpdesk.holiday_name')?></label>
                <input type="text" name="holiday_name" required
                value="<?php if(isset($holiday['name_holiday'])){echo $holiday['name_holiday'];}?>">
            </fieldset>
            <fieldset>
                <label for="start_date"><?= lang('Helpdesk.start_date')?></label>
                <input type="datetime-local" name="start_date" required
                value="<?php if(isset($holiday['start_date_holiday'])){echo $holiday['start_date_holiday'];}?>">
            </fieldset>
            <fieldset>
                <label for="end_date"><?= lang('Helpdesk.end_date')?></label>
                <input type="datetime-local" name="end_date" required
                value="<?php if(isset($holiday['end_date_holiday'])){echo $holiday['end_date_holiday'];}?>">
            </fieldset>
        </div>

        <div class="buttons-area">
            <input class="btn btn-success" type="submit" value="<?= lang('Helpdesk.btn_save')?>">        
            
            <?php if(isset($holiday['id_holiday'])): ?>
                <input type="hidden" name="id_holiday" value="<?= $holiday['id_holiday']; ?>">
                <a class="btn btn-danger" href="<?= base_url('helpdesk/home/deleteHoliday/'.$holiday['id_holiday']) ?>"><?= lang('Helpdesk.btn_delete')?></a>
            <?php else: ?>
                <input type="hidden" name="id_holiday" value="0">
            <?php endif; ?>
            <a class="btn btn-primary" href="<?= base_url('helpdesk/home/holidays') ?>"><?= lang('Helpdesk.btn_back')?></a>
        </div>
    </form>
</div>