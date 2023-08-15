<?php

/**
 * holiday view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

?>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
        margin: auto;
    }

    th,
    td {
        padding: 8px;
        text-align: center;
        border: 1px solid #ddd;
    }

    thead tr:nth-child(1) th {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
    }

    thead tr:nth-child(2) th {
        font-weight: bold;
    }

    tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .week {
        background-color: #eee;
        padding: 8px;
        font-size: 18px;
        text-align: center;
    }

    .bg-green {
        background-color: #c5deb5;
    }

    .bg-yellow {
        background-color: #e5f874;
    }

    .bg-orange {
        background-color: #ffd965;
    }

    .btn-blue {
        background-color: #b4c6e7;
    }

    .btn-blue:hover {
        color: #fff;
        background-color: #b4c6e7;
        border-color: #b4c6e7
    }

    /* Error message */
    .error {
		position: absolute;
		top: 18%;
		background-color: red;
        color: #fff;
		border-radius: 5px;
		padding: 7px 15px;
		font-size: 1.25em;

		animation: fadeOut 4s forwards;
	}

	/* Fade animation */
	@keyframes fadeOut 
	{
		0% { opacity: 1; }
		80% { opacity: 1; }
		100% { opacity: 0; display: none; }
	}
</style>

<!-- Title, if exists -->
<?php if(isset($title)){ echo ('<h2>'.$title.'</h2>');} ?>

<!-- Error message, if exists -->
<?php if (isset($error)): ?>
    <div class="d-flex justify-content-center">
        <?php echo ('<p class="error">'.$error.'</p>'); ?>
    </div>
<?php endif; ?>

<div class="container-fluid">

    <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home/holidays') ?>"><?php echo lang('Helpdesk.btn_back')?></a>

    <?php if(isset($holiday['id_holiday'])): ?>
        <form method="POST" action="<?= base_url('helpdesk/home/saveHoliday/'.$holiday['id_holiday']) ?>">
    <?php else: ?>
        <form method="POST" action="<?= base_url('helpdesk/home/saveHoliday') ?>">
    <?php endif; ?>

		<input class="btn btn-blue" type="submit" value="<?php echo lang('Helpdesk.btn_save')?>">
        
        <div class="d-flex justify-content-center">
            <fieldset>
                <label for="holiday_name"><?php echo lang('Helpdesk.holiday_name')?></label>
                <input type="text" name="holiday_name" required
                value="<?php if(isset($holiday['name_holiday'])){echo $holiday['name_holiday'];}?>">
            </fieldset>
            <fieldset>
                <label for="start_date"><?php echo lang('Helpdesk.start_date')?></label>
                <input type="datetime-local" name="start_date" required
                value="<?php if(isset($holiday['start_date_holiday'])){echo $holiday['start_date_holiday'];}?>">
            </fieldset>
            <fieldset>
                <label for="end_date"><?php echo lang('Helpdesk.end_date')?></label>
                <input type="datetime-local" name="end_date" required
                value="<?php if(isset($holiday['end_date_holiday'])){echo $holiday['end_date_holiday'];}?>">
            </fieldset>
        </div>

        <?php if(isset($holiday['id_holiday'])): ?>
            <input type="hidden" name="id_holiday" value="<?php echo $holiday['id_holiday']; ?>">

            <a class="btn btn-danger mb-3" href="<?= base_url('helpdesk/home/deleteHoliday/'.$holiday['id_holiday']) ?>"><?php echo lang('Helpdesk.btn_delete')?></a>
        <?php else: ?>
            <input type="hidden" name="id_holiday" value="0">
        <?php endif; ?>
    </form>
</div>