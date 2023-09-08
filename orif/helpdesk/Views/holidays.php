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
            <?php echo ('<p class="success">'.$success.'</p>'); ?>
        </div>
    <?php endif; ?>

    <!-- Error message, if exists -->
    <?php if(isset($error)): ?>
        <div class="d-flex justify-content-center">
            <?php echo ('<p class="error">'.$error.'</p>'); ?>
        </div>
    <?php endif; ?>

    <a class="btn btn-primary" href="<?= base_url('helpdesk/home/planning') ?>"><?php echo lang('Helpdesk.btn_back')?></a><br>

    <div class="d-flex flex-column align-items-center">
        <table class="table-responsive">
            <thead>
                <tr>
                    <th><?php echo lang('Helpdesk.holiday_name')?></th>
                    <th><?php echo lang('Helpdesk.start_date')?></th>
                    <th><?php echo lang('Helpdesk.end_date')?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($holidays_data) && !empty($holidays_data)) : ?>
                    <?php foreach ($holidays_data as $holiday) : ?>
                        <tr>
                            <td>
                                <a href="<?php echo base_url('helpdesk/home/saveHoliday/'.$holiday['id_holiday']);?>"><?php echo $holiday['name_holiday']; ?></a>
                            </td>
                            <td>
                                <?php echo $holiday['start_date_holiday']; ?>
                            </td>
                            <td>
                                <?php echo $holiday['end_date_holiday']; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">
                            <?php echo lang('Helpdesk.no_holidays')?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="action-menu d-flex justify-content-center">
            <a class="btn btn-blue" href="<?= base_url('helpdesk/home/saveHoliday') ?>"><?php echo lang('Helpdesk.btn_add_holiday')?></a>
        </div>
    </div>
</div>