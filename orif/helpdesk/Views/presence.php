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
	h2 {
		text-align: center;
	}

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

	.container label input:checked+span.absent-partie {
		background-color: orange;
		color: white;
	}

	.container label input:checked+span.absent-partie:before {
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

<div class="container-fluid">

	<a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home') ?>"><?php echo lang('Helpdesk.btn_back')?></a>

	<form method="POST" action="<?= base_url('helpdesk/home/savePresence') ?>">
		<input class="btn btn-blue" type="submit" value="<?php echo lang('Helpdesk.btn_save')?>">
		
		<!-- Success message, if exists -->
		<?php if (isset($success)): ?>
			<div class="d-flex justify-content-center">
				<?php echo ('<p class="success">'.$success.'</p>'); ?>
			</div>
		<?php endif; ?>

		<div class="d-flex justify-content-center">
			<p> <?php echo lang('Helpdesk.empty_fields_info')?> </p>
		</div>

		<!-- TODO : Optimise this page using loops -->

		<!-- Monday -->
		<div class="d-flex justify-content-center">
			<div class="table-responsive">
				<table>
					<thead>
						<tr class="table">
							<th colspan="4"><?php echo lang('Helpdesk.monday')?></th>
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
							<!-- Monday 8:00 10:00 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="lundi_debut_matin" value="1" 
										<?php if ((isset($lundi_debut_matin)) && $lundi_debut_matin == 1){echo "checked";} ?> 
										>
										<span class="button present"><?php echo lang('Helpdesk.present')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_debut_matin" value="2"
										<?php if ((isset($lundi_debut_matin)) && $lundi_debut_matin == 2){echo "checked";} ?>
										>
										<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_debut_matin" value="3"
										<?php if ((isset($lundi_debut_matin)) && $lundi_debut_matin == 3){echo "checked";} ?>
										>
										<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
									</label>
								</div>
							</td>
							<!-- Monday 10:00 12:00 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="lundi_fin_matin" value="1"
										<?php if ((isset($lundi_fin_matin)) && $lundi_fin_matin == 1){echo "checked";} ?>
										>
										<span class="button present"><?php echo lang('Helpdesk.present')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_fin_matin" value="2"
										<?php if ((isset($lundi_fin_matin)) && $lundi_fin_matin == 2){echo "checked";} ?>
										>
										<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_fin_matin" value="3"
										<?php if ((isset($lundi_fin_matin)) && $lundi_fin_matin == 3){echo "checked";} ?>
										>
										<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
									</label>
								</div>
							</td>
							<!-- Monday 12:45 15:00 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="lundi_debut_apres_midi" value="1"
										<?php if ((isset($lundi_debut_apres_midi)) && $lundi_debut_apres_midi == 1){echo "checked";} ?>
										>
										<span class="button present"><?php echo lang('Helpdesk.present')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_debut_apres_midi" value="2"
										<?php if ((isset($lundi_debut_apres_midi)) && $lundi_debut_apres_midi == 2){echo "checked";} ?>
										>
										<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_debut_apres_midi" value="3"
										<?php if ((isset($lundi_debut_apres_midi)) && $lundi_debut_apres_midi == 3){echo "checked";} ?>
										>
										<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
									</label>
								</div>
							</td>
							<!-- Monday 15:00 16:57 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="lundi_fin_apres_midi" value="1"
										<?php if ((isset($lundi_fin_apres_midi)) && $lundi_fin_apres_midi == 1){echo "checked";} ?>
										>
										<span class="button present"><?php echo lang('Helpdesk.present')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_fin_apres_midi" value="2"
										<?php if ((isset($lundi_fin_apres_midi)) && $lundi_fin_apres_midi == 2){echo "checked";} ?>
										>
										<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_fin_apres_midi" value="3"
										<?php if ((isset($lundi_fin_apres_midi)) && $lundi_fin_apres_midi == 3){echo "checked";} ?>
										>
										<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
									</label>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- Tuesday -->
		<div class="d-flex justify-content-center">
			<div class="table-responsive">
				<table>
					<thead>
						<tr>
							<th colspan="4"><?php echo lang('Helpdesk.tuesday')?></th>
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
							<!-- Tuesday 8:00 10:00 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="mardi_debut_matin" value="1"
										<?php if ((isset($mardi_debut_matin)) && $mardi_debut_matin == 1){echo "checked";} ?>
										>
										<span class="button present"><?php echo lang('Helpdesk.present')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_debut_matin" value="2"
										<?php if ((isset($mardi_debut_matin)) && $mardi_debut_matin == 2){echo "checked";} ?>
										>
										<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_debut_matin" value="3"
										<?php if ((isset($mardi_debut_matin)) && $mardi_debut_matin == 3){echo "checked";} ?>
										>
										<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
									</label>
								</div>
							</td>
							<!-- Tuesday 10:00 12:00 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="mardi_fin_matin" value="1"
										<?php if ((isset($mardi_fin_matin)) && $mardi_fin_matin == 1){echo "checked";} ?>
										>
										<span class="button present"><?php echo lang('Helpdesk.present')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_fin_matin" value="2"
										<?php if ((isset($mardi_fin_matin)) && $mardi_fin_matin == 2){echo "checked";} ?>
										>
										<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_fin_matin" value="3"
										<?php if ((isset($mardi_fin_matin)) && $mardi_fin_matin == 3){echo "checked";} ?>
										>
										<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
									</label>
								</div>
							</td>
							<!-- Tuesday 12:45 15:00 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="mardi_debut_apres_midi" value="1"
										<?php if ((isset($mardi_debut_apres_midi)) && $mardi_debut_apres_midi == 1){echo "checked";} ?>
										>
										<span class="button present"><?php echo lang('Helpdesk.present')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_debut_apres_midi" value="2"
										<?php if ((isset($mardi_debut_apres_midi)) && $mardi_debut_apres_midi == 2){echo "checked";} ?>
										>
										<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_debut_apres_midi" value="3"
										<?php if ((isset($mardi_debut_apres_midi)) && $mardi_debut_apres_midi == 3){echo "checked";} ?>
										>
										<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
									</label>
								</div>
							</td>
							<!-- Tuesday 15:00 16:57 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="mardi_fin_apres_midi" value="1"
										<?php if ((isset($mardi_fin_apres_midi)) && $mardi_fin_apres_midi == 1){echo "checked";} ?>
										>
										<span class="button present"><?php echo lang('Helpdesk.present')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_fin_apres_midi" value="2"
										<?php if ((isset($mardi_fin_apres_midi)) && $mardi_fin_apres_midi == 2){echo "checked";} ?>
										>
										<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_fin_apres_midi" value="3"
										<?php if ((isset($mardi_fin_apres_midi)) && $mardi_fin_apres_midi == 3){echo "checked";} ?>
										>
										<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
									</label>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- Wednesday -->
		<div class="d-flex justify-content-center">
			<div class="table-responsive">
				<table>
					<thead>
						<tr>
							<th colspan="4"><?php echo lang('Helpdesk.wednesday')?></th>
						</tr>
						<tr>
							<th>8:00 - 10:00</th>
							<th>10:00 - 12:00</th>
							<th>12:45 - 15:00</th>
							<th>15:00 - 16:57</th>
						</tr>
					</thead>
					<tbody>
						<!-- Wednesday 8:00 10:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="mercredi_debut_matin" value="1"
									<?php if ((isset($mercredi_debut_matin)) && $mercredi_debut_matin == 1){echo "checked";} ?>
									>
									<span class="button present"><?php echo lang('Helpdesk.present')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_debut_matin" value="2"
									<?php if ((isset($mercredi_debut_matin)) && $mercredi_debut_matin == 2){echo "checked";} ?>
									>
									<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_debut_matin" value="3"
									<?php if ((isset($mercredi_debut_matin)) && $mercredi_debut_matin == 3){echo "checked";} ?>
									>
									<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
								</label>
							</div>
						</td>
						<!-- Wednesday 10:00 12:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="mercredi_fin_matin" value="1"
									<?php if ((isset($mercredi_fin_matin)) && $mercredi_fin_matin == 1){echo "checked";} ?>
									>
									<span class="button present"><?php echo lang('Helpdesk.present')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_fin_matin" value="2"
									<?php if ((isset($mercredi_fin_matin)) && $mercredi_fin_matin == 2){echo "checked";} ?>
									>
									<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_fin_matin" value="3"
									<?php if ((isset($mercredi_fin_matin)) && $mercredi_fin_matin == 3){echo "checked";} ?>
									>
									<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
								</label>
							</div>
						</td>
						<!-- Wednesday 12:45 15:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="mercredi_debut_apres_midi" value="1"
									<?php if ((isset($mercredi_debut_apres_midi)) && $mercredi_debut_apres_midi == 1){echo "checked";} ?>
									>
									<span class="button present"><?php echo lang('Helpdesk.present')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_debut_apres_midi" value="2"
									<?php if ((isset($mercredi_debut_apres_midi)) && $mercredi_debut_apres_midi == 2){echo "checked";} ?>
									>
									<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_debut_apres_midi" value="3"
									<?php if ((isset($mercredi_debut_apres_midi)) && $mercredi_debut_apres_midi == 3){echo "checked";} ?>
									>
									<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
								</label>
							</div>
						</td>
						<!-- Wednesday 15:00 16:57 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="mercredi_fin_apres_midi" value="1"
									<?php if ((isset($mercredi_fin_apres_midi)) && $mercredi_fin_apres_midi == 1){echo "checked";} ?>
									>
									<span class="button present"><?php echo lang('Helpdesk.present')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_fin_apres_midi" value="2"
									<?php if ((isset($mercredi_fin_apres_midi)) && $mercredi_fin_apres_midi == 2){echo "checked";} ?>
									>
									<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_fin_apres_midi" value="3"
									<?php if ((isset($mercredi_fin_apres_midi)) && $mercredi_fin_apres_midi == 3){echo "checked";} ?>
									>
									<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
								</label>
							</div>
						</td>
					</tbody>
				</table>
			</div>
		</div>
		<!-- Thrusday -->
		<div class="d-flex justify-content-center">
			<div class="table-responsive">
				<table>
					<thead>
						<tr>
							<th colspan="4"><?php echo lang('Helpdesk.thursday')?></th>
						</tr>
						<tr>
							<th>8:00 - 10:00</th>
							<th>10:00 - 12:00</th>
							<th>12:45 - 15:00</th>
							<th>15:00 - 16:57</th>
						</tr>
					</thead>
					<tbody>
						<!-- Thrusday 8:00 10:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="jeudi_debut_matin" value="1"
									<?php if ((isset($jeudi_debut_matin)) && $jeudi_debut_matin == 1){echo "checked";} ?>
									>
									<span class="button present"><?php echo lang('Helpdesk.present')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_debut_matin" value="2"
									<?php if ((isset($jeudi_debut_matin)) && $jeudi_debut_matin == 2){echo "checked";} ?>
									>
									<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_debut_matin" value="3"
									<?php if ((isset($jeudi_debut_matin)) && $jeudi_debut_matin == 3){echo "checked";} ?>
									>
									<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
								</label>
							</div>
						</td>
						<!-- Thrusday 10:00 12:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="jeudi_fin_matin" value="1"
									<?php if ((isset($jeudi_fin_matin)) && $jeudi_fin_matin == 1){echo "checked";} ?>
									>
									<span class="button present"><?php echo lang('Helpdesk.present')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_fin_matin" value="2"
									<?php if ((isset($jeudi_fin_matin)) && $jeudi_fin_matin == 2){echo "checked";} ?>
									>
									<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_fin_matin" value="3"
									<?php if ((isset($jeudi_fin_matin)) && $jeudi_fin_matin == 3){echo "checked";} ?>
									>
									<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
								</label>
							</div>
						</td>
						<!-- Thrusday 12:45 15:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="jeudi_debut_apres_midi" value="1"
									<?php if ((isset($jeudi_debut_apres_midi)) && $jeudi_debut_apres_midi == 1){echo "checked";} ?>
									>
									<span class="button present"><?php echo lang('Helpdesk.present')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_debut_apres_midi" value="2"
									<?php if ((isset($jeudi_debut_apres_midi)) && $jeudi_debut_apres_midi == 2){echo "checked";} ?>
									>
									<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_debut_apres_midi" value="3"
									<?php if ((isset($jeudi_debut_apres_midi)) && $jeudi_debut_apres_midi == 3){echo "checked";} ?>
									>
									<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
								</label>
							</div>
						</td>
						<!-- Thrusday 15:00 16:57 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="jeudi_fin_apres_midi" value="1"
									<?php if ((isset($jeudi_fin_apres_midi)) && $jeudi_fin_apres_midi == 1){echo "checked";} ?>
									>
									<span class="button present"><?php echo lang('Helpdesk.present')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_fin_apres_midi" value="2"
									<?php if ((isset($jeudi_fin_apres_midi)) && $jeudi_fin_apres_midi == 2){echo "checked";} ?>
									>
									<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_fin_apres_midi" value="3"
									<?php if ((isset($jeudi_fin_apres_midi)) && $jeudi_fin_apres_midi == 3){echo "checked";} ?>
									>
									<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
								</label>
							</div>
						</td>
					</tbody>
				</table>
			</div>
		</div>
		<!-- Friday -->
		<div class="d-flex justify-content-center">
			<div class="table-responsive">
				<table>
					<thead>
						<tr>
							<th colspan="4"><?php echo lang('Helpdesk.friday')?></th>
						</tr>
						<tr>
							<th>8:00 - 10:00</th>
							<th>10:00 - 12:00</th>
							<th>12:45 - 15:00</th>
							<th>15:00 - 16:57</th>
						</tr>
					</thead>
					<tbody>
						<!-- Friday 8:00 10:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="vendredi_debut_matin" value="1"
									<?php if ((isset($vendredi_debut_matin)) && $vendredi_debut_matin == 1){echo "checked";} ?>
									>
									<span class="button present"><?php echo lang('Helpdesk.present')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_debut_matin" value="2"
									<?php if ((isset($vendredi_debut_matin)) && $vendredi_debut_matin == 2){echo "checked";} ?>
									>
									<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_debut_matin" value="3"
									<?php if ((isset($vendredi_debut_matin)) && $vendredi_debut_matin == 3){echo "checked";} ?>
									>
									<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
								</label>
							</div>
						</td>
						<!-- Friday 10:00 12:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="vendredi_fin_matin" value="1"
									<?php if ((isset($vendredi_fin_matin)) && $vendredi_fin_matin == 1){echo "checked";} ?>
									>
									<span class="button present"><?php echo lang('Helpdesk.present')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_fin_matin" value="2"
									<?php if ((isset($vendredi_fin_matin)) && $vendredi_fin_matin == 2){echo "checked";} ?>
									>
									<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_fin_matin" value="3"
									<?php if ((isset($vendredi_fin_matin)) && $vendredi_fin_matin == 3){echo "checked";} ?>
									>
									<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
								</label>
							</div>
						</td>
						<!-- Friday 12:45 15:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="vendredi_debut_apres_midi" value="1"
									<?php if ((isset($vendredi_debut_apres_midi)) && $vendredi_debut_apres_midi == 1){echo "checked";} ?>
									>
									<span class="button present"><?php echo lang('Helpdesk.present')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_debut_apres_midi" value="2"
									<?php if ((isset($vendredi_debut_apres_midi)) && $vendredi_debut_apres_midi == 2){echo "checked";} ?>
									>
									<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_debut_apres_midi" value="3"
									<?php if ((isset($vendredi_debut_apres_midi)) && $vendredi_debut_apres_midi == 3){echo "checked";} ?>
									>
									<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
								</label>
							</div>
						</td>
						<!-- Friday 15:00 16:57 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="vendredi_fin_apres_midi" value="1"
									<?php if ((isset($vendredi_fin_apres_midi)) && $vendredi_fin_apres_midi == 1){echo "checked";} ?>
									>
									<span class="button present"><?php echo lang('Helpdesk.present')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_fin_apres_midi" value="2"
									<?php if ((isset($vendredi_fin_apres_midi)) && $vendredi_fin_apres_midi == 2){echo "checked";} ?>
									>
									<span class="button absent-partie"><?php echo lang('Helpdesk.partly_absent')?></span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_fin_apres_midi" value="3"
									<?php if ((isset($vendredi_fin_apres_midi)) && $vendredi_fin_apres_midi == 3){echo "checked";} ?>
									>
									<span class="button absent"><?php echo lang('Helpdesk.absent')?></span>
								</label>
							</div>
						</td>
					</tbody>
				</table>
			</div>
		</div>
	</form>
</div>