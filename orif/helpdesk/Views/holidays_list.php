<?php

/**
 * holidays view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<?= view('Helpdesk\Common\body_start') ?>

<nav><a class="btn btn-back" href="<?= base_url('/helpdesk/planning/cw_planning') ?>"><span><?= lang('Helpdesk.btn_back')?></span></a></nav>

<div class="holidays-table">
    <div class="add-holiday">
        <a class="btn btn-add" href="<?= base_url('/helpdesk/holidays/save_holiday') ?>"><span><?= lang('Helpdesk.btn_add_holiday')?></span></a>
    </div>
    <table class="table-responsive">
        <thead>
            <tr>
                <th><?= lang('Helpdesk.holiday_name')?></th>
                <th><?= lang('Helpdesk.start_date')?></th>
                <th><?= lang('Helpdesk.end_date')?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($holidays_data) && !empty($holidays_data)) : ?>
                <?php foreach ($holidays_data as $holiday) : ?>
                    <?php 
                        $start_date_holiday = new DateTime($holiday['start_date_holiday']); 
                        $start_date_holiday = $start_date_holiday->format('d/m/Y, H:i:s');

                        $end_date_holiday = new DateTime($holiday['end_date_holiday']); 
                        $end_date_holiday = $end_date_holiday->format('d/m/Y, H:i:s');
                    ?>
                    <tr>
                        <th>
                            <a href="<?= base_url('/helpdesk/holidays/save_holiday/'.$holiday['id_holiday']);?>"><?= htmlentities($holiday['name_holiday']); ?></a>
                        </th>
                        <td>
                            <?= $start_date_holiday; ?>
                        </td>
                        <td>
                            <?= $end_date_holiday; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">
                        <?= lang('Helpdesk.err_no_holidays')?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="table-bottom"></div>
</div>