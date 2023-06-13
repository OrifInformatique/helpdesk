<?php


/**
 * welcome_message view
 *
 * @author      Orif (BlAl)
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
</style>

<div class="container-fluid">

	<a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home') ?>">Retour</a>

	<form method="POST" action="<?= base_url('helpdesk/home/savePresence') ?>">
		<input class="btn btn-info" type="submit" value="Enregistrer">

		<!-- lundi -->
		<div class="d-flex justify-content-center">
			<div class="table-responsive">
				<table>
					<thead>
						<tr class="table">
							<th colspan="4">Lundi</th>
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
							<!-- lundi 8:00 10:00 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="lundi_debut_matin" value="1">
										<span class="button present">Présent</span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_debut_matin" value="2">
										<span class="button absent-partie">Absent en partie</span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_debut_matin" value="3">
										<span class="button absent">Absent</span>
									</label>
								</div>
							</td>
							<!-- lundi 10:00 12:00 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="lundi_fin_matin" value="1">
										<span class="button present">Présent</span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_fin_matin" value="2">
										<span class="button absent-partie">Absent en partie</span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_fin_matin" value="3">
										<span class="button absent">Absent</span>
									</label>
								</div>
							</td>
							<!-- lundi 12:45 15:00 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="lundi_debut_apres-midi" value="1">
										<span class="button present">Présent</span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_debut_apres-midi" value="2">
										<span class="button absent-partie">Absent en partie</span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_debut_apres-midi" value="3">
										<span class="button absent">Absent</span>
									</label>
								</div>
							</td>
							<!-- lundi 15:00 16:57 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="lundi_fin_apres-midi" value="1">
										<span class="button present">Présent</span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_fin_apres-midi" value="2">
										<span class="button absent-partie">Absent en partie</span>
									</label>
									<label>
										<input class="input" type="radio" name="lundi_fin_apres-midi" value="3">
										<span class="button absent">Absent</span>
									</label>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- mardi -->
		<div class="d-flex justify-content-center">
			<div class="table-responsive">
				<table>
					<thead>
						<tr>
							<th colspan="4">Mardi</th>
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
							<!-- mardi 8:00 10:00 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="mardi_debut_matin" value="1">
										<span class="button present">Présent</span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_debut_matin" value="2">
										<span class="button absent-partie">Absent en partie</span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_debut_matin" value="3">
										<span class="button absent">Absent</span>
									</label>
								</div>
							</td>
							<!-- mardi 10:00 12:00 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="mardi_fin_matin" value="1">
										<span class="button present">Présent</span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_fin_matin" value="2">
										<span class="button absent-partie">Absent en partie</span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_fin_matin" value="3">
										<span class="button absent">Absent</span>
									</label>
								</div>
							</td>
							<!-- mardi 12:45 15:00 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="mardi_debut_apres-midi" value="1">
										<span class="button present">Présent</span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_debut_apres-midi" value="2">
										<span class="button absent-partie">Absent en partie</span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_debut_apres-midi" value="3">
										<span class="button absent">Absent</span>
									</label>
								</div>
							</td>
							<!-- mardi 15:00 16:57 -->
							<td>
								<div class="container">
									<label>
										<input class="input" type="radio" name="mardi_fin_apres-midi" value="1">
										<span class="button present">Présent</span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_fin_apres-midi" value="2">
										<span class="button absent-partie">Absent en partie</span>
									</label>
									<label>
										<input class="input" type="radio" name="mardi_fin_apres-midi" value="3">
										<span class="button absent">Absent</span>
									</label>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- mercredi -->
		<div class="d-flex justify-content-center">
			<div class="table-responsive">
				<table>
					<thead>
						<tr>
							<th colspan="4">Mercredi</th>
						</tr>
						<tr>
							<th>8:00 - 10:00</th>
							<th>10:00 - 12:00</th>
							<th>12:45 - 15:00</th>
							<th>15:00 - 16:57</th>
						</tr>
					</thead>
					<tbody>
						<!-- mercredi 8:00 10:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="mercredi_debut_matin" value="1">
									<span class="button present">Présent</span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_debut_matin" value="2">
									<span class="button absent-partie">Absent en partie</span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_debut_matin" value="3">
									<span class="button absent">Absent</span>
								</label>
							</div>
						</td>
						<!-- mercredi 10:00 12:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="mercredi_fin_matin" value="1">
									<span class="button present">Présent</span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_fin_matin" value="2">
									<span class="button absent-partie">Absent en partie</span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_fin_matin" value="3">
									<span class="button absent">Absent</span>
								</label>
							</div>
						</td>
						<!-- mercredi 12:45 15:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="mercredi_debut_apres-midi" value="1">
									<span class="button present">Présent</span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_debut_apres-midi" value="2">
									<span class="button absent-partie">Absent en partie</span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_debut_apres-midi" value="3">
									<span class="button absent">Absent</span>
								</label>
							</div>
						</td>
						<!-- mercredi 15:00 16:57 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="mercredi_fin_apres-midi" value="1">
									<span class="button present">Présent</span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_fin_apres-midi" value="2">
									<span class="button absent-partie">Absent en partie</span>
								</label>
								<label>
									<input class="input" type="radio" name="mercredi_fin_apres-midi" value="3">
									<span class="button absent">Absent</span>
								</label>
							</div>
						</td>
					</tbody>
				</table>
			</div>
		</div>
		<!-- jeudi -->
		<div class="d-flex justify-content-center">
			<div class="table-responsive">
				<table>
					<thead>
						<tr>
							<th colspan="4">Jeudi</th>
						</tr>
						<tr>
							<th>8:00 - 10:00</th>
							<th>10:00 - 12:00</th>
							<th>12:45 - 15:00</th>
							<th>15:00 - 16:57</th>
						</tr>
					</thead>
					<tbody>
						<!-- jeudi 8:00 10:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="jeudi_debut_matin" value="1">
									<span class="button present">Présent</span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_debut_matin" value="2">
									<span class="button absent-partie">Absent en partie</span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_debut_matin" value="3">
									<span class="button absent">Absent</span>
								</label>
							</div>
						</td>
						<!-- jeudi 10:00 12:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="jeudi_fin_matin" value="1">
									<span class="button present">Présent</span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_fin_matin" value="2">
									<span class="button absent-partie">Absent en partie</span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_fin_matin" value="3">
									<span class="button absent">Absent</span>
								</label>
							</div>
						</td>
						<!-- jeudi 12:45 15:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="jeudi_debut_apres-midi" value="1">
									<span class="button present">Présent</span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_debut_apres-midi" value="2">
									<span class="button absent-partie">Absent en partie</span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_debut_apres-midi" value="3">
									<span class="button absent">Absent</span>
								</label>
							</div>
						</td>
						<!-- jeudi 15:00 16:57 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="jeudi_fin_apres-midi" value="1">
									<span class="button present">Présent</span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_fin_apres-midi" value="2">
									<span class="button absent-partie">Absent en partie</span>
								</label>
								<label>
									<input class="input" type="radio" name="jeudi_fin_apres-midi" value="3">
									<span class="button absent">Absent</span>
								</label>
							</div>
						</td>
					</tbody>
				</table>
			</div>
		</div>
		<!-- vendredi -->
		<div class="d-flex justify-content-center">
			<div class="table-responsive">
				<table>
					<thead>
						<tr>
							<th colspan="4">Vendredi</th>
						</tr>
						<tr>
							<th>8:00 - 10:00</th>
							<th>10:00 - 12:00</th>
							<th>12:45 - 15:00</th>
							<th>15:00 - 16:57</th>
						</tr>
					</thead>
					<tbody>
						<!-- vendredi 8:00 10:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="vendredi_debut_matin" value="1">
									<span class="button present">Présent</span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_debut_matin" value="2">
									<span class="button absent-partie">Absent en partie</span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_debut_matin" value="3">
									<span class="button absent">Absent</span>
								</label>
							</div>
						</td>
						<!-- vendredi 10:00 12:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="vendredi_fin_matin" value="1">
									<span class="button present">Présent</span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_fin_matin" value="2">
									<span class="button absent-partie">Absent en partie</span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_fin_matin" value="3">
									<span class="button absent">Absent</span>
								</label>
							</div>
						</td>
						<!-- vendredi 12:45 15:00 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="vendredi_debut_apres-midi" value="1">
									<span class="button present">Présent</span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_debut_apres-midi" value="2">
									<span class="button absent-partie">Absent en partie</span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_debut_apres-midi" value="3">
									<span class="button absent">Absent</span>
								</label>
							</div>
						</td>
						<!-- vendredi 15:00 16:57 -->
						<td>
							<div class="container">
								<label>
									<input class="input" type="radio" name="vendredi_fin_apres-midi" value="1">
									<span class="button present">Présent</span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_fin_apres-midi" value="2">
									<span class="button absent-partie">Absent en partie</span>
								</label>
								<label>
									<input class="input" type="radio" name="vendredi_fin_apres-midi" value="3">
									<span class="button absent">Absent</span>
								</label>
							</div>
						</td>
					</tbody>
				</table>
			</div>
		</div>
	</form>
</div>