<?php

/**
 * presence view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

?>

<style>
	table {
		border-collapse: collapse;
		margin: auto;
		width: auto;
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

	.container form {
		display: flex;
		flex-wrap: wrap;
		flex-direction: column;
	}

	.container label {
		display: flex;
		cursor: pointer;
		font-weight: 500;
		position: relative;
		overflow: hidden;
		margin-bottom: 0.375em;
	}

	.container label input {
		position: absolute;
		left: -9999px;
	}

	.container label input:checked+span.present {
		background-color: green;
		color: white;
	}

	.container label input:checked+span.present:before {
		box-shadow: inset 0 0 0 0.4375em darkgreen;
	}

	.container label input:checked+span.partly-absent {
		background-color: orange;
		color: white;
	}

	.container label input:checked+span.partly-absent:before {
		box-shadow: inset 0 0 0 0.4375em darkorange;
	}

	.container label input:checked+span.absent {
		background-color: red;
		color: white;
	}

	.container label input:checked+span.absent:before {
		box-shadow: inset 0 0 0 0.4375em darkred;
	}

	.container label span {
		display: flex;
		align-items: center;
		padding: 0.375em 0.75em 0.375em 0.375em;
		border-radius: 99em;
		transition: 0.25s ease;
		color: #086eb6;
	}

	.container label span:hover {
		background-color: #d6d6e5;
	}

	.container label span:before {
		display: flex;
		flex-shrink: 0;
		content: "";
		background-color: #fff;
		width: 1.5em;
		height: 1.5em;
		border-radius: 50%;
		margin-right: 0.375em;
		transition: 0.25s ease;
		box-shadow: inset 0 0 0 0.125em #004683;
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

	<a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home') ?>"><?php echo lang('Helpdesk.btn_back')?></a>

	<form method="POST" action="<?= base_url('helpdesk/home/savePresence') ?>">
		<input class="btn btn-blue" type="submit" value="<?php echo lang('Helpdesk.btn_save')?>">

		<div class="d-flex justify-content-center">
			<p> <?php echo lang('Helpdesk.empty_fields_info')?> </p>
		</div>

		<?php foreach($weekdays as $day => $periods): ?>
			<div class="d-flex justify-content-center">
				<div class="table-responsive">
					<table>
						<thead>
							<tr class="table">
								<th colspan="4"><?php echo lang('Helpdesk.'.$day)?></th>
							</tr>
							<tr>
								<th>8:00 - 10:00</th>
								<th>10:00 - 12:00</th>
								<th>12:45 - 15:00</th>
								<th>15:00 - 16:57</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php foreach($periods as $period): ?>
									<td>
										<div class="container">
											<label>
												<input class="input" type="radio" name=<?php echo $period ?> value="1" 
												<?php if ((isset($presences[$period])) && $presences[$period] == 1){echo "checked";} ?> 
												>
												<span class="button present"><?php echo lang('Helpdesk.present')?></span>
											</label>
											<label>
												<input class="input" type="radio" name=<?php echo $period ?> value="2"
												<?php if ((isset($presences[$period])) && $presences[$period] == 2){echo "checked";} ?>
												>
												<span class="button partly-absent"><?php echo lang('Helpdesk.partly_absent')?></span>
											</label>
											<label>
												<input class="input" type="radio" name=<?php echo $period ?> value="3"
												<?php if ((isset($presences[$period])) && $presences[$period] == 3){echo "checked";} ?>
												>
												<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
											</label>
										</div>
									</td>
								<?php endforeach ?>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		<?php endforeach ?>
	</form>
</div>