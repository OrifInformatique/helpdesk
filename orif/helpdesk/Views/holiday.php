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
    /* Success message */
	.success {
		position: absolute;
		top: 18%;
		background-color: greenyellow;
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

<!-- Success message, if exists -->
<?php if (isset($success)): ?>
    <div class="d-flex justify-content-center">
        <?php echo ('<p class="success">'.$success.'</p>'); ?>
    </div>
<?php endif; ?>

<div class="container-fluid">

    <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home') ?>"><?php echo lang('Helpdesk.btn_back')?></a><br>

    <a class="btn btn-blue mb-3" href="<?= base_url('helpdesk/home/addHoliday') ?>"><?php echo lang('Helpdesk.btn_add_holiday')?></a>

    <div class="d-flex justify-content-center">
        <table class="table-responsive position-relative">
            <thead>
                <tr>
                    <th><?php echo lang('Helpdesk.holiday_name')?></th>
                    <th><?php echo lang('Helpdesk.start_date')?></th>
                    <th><?php echo lang('Helpdesk.end_date')?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($vacances_data)) : ?>
                    <?php foreach ($vacances_data as $holiday) : ?>
                        <tr>
                            <td>
                                <?php echo $holiday['nom_vacances']; ?>
                            </td>
                            <td>
                                <?php echo $holiday['date_debut_vacances']; ?>
                            </td>
                            <td>
                                <?php echo $holiday['date_fin_vacances']; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">
                            <?php echo lang('Helpdesk.no_holidays')?><br>
                            <a class="btn btn-blue mb-3" href="<?= base_url('helpdesk/home/addHoliday') ?>"><?php echo lang('Helpdesk.btn_add_holiday')?></a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>