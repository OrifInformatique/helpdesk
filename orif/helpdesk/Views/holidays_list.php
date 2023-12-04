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

<a class="btn btn-primary" href="<?= base_url('/helpdesk/planning/cw_planning') ?>"><?= lang('Helpdesk.btn_back')?></a><br>

<div class="d-flex flex-column align-items-center">
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
                    <tr>
                        <td>
                            <a href="<?= base_url('/helpdesk/holidays/save_holiday/'.$holiday['id_holiday']);?>"><?= htmlentities($holiday['name_holiday']); ?></a>
                        </td>
                        <td>
                            <?= $holiday['start_date_holiday']; ?>
                        </td>
                        <td>
                            <?= $holiday['end_date_holiday']; ?>
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
    <div class="action-menu d-flex justify-content-center">
        <a class="btn btn-blue" href="<?= base_url('/helpdesk/holidays/save_holiday') ?>"><?= lang('Helpdesk.btn_add_holiday')?></a>
    </div>
</div>