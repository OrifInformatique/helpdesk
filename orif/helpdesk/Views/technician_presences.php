<?php

/**
 * your_presences view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<?= view('Helpdesk\Common\body_start') ?>


<?php if(!isset($presences)): ?>
	<div class="d-flex justify-content-center alert alert-info">
		<?= '<i class="fa-solid fa-circle-info"></i>'.lang('Helpdesk.info_presences_fields_empty')?>
	</div>
<?php endif; ?>

<?= form_open(base_url('/helpdesk/presences/technician_presences')) ?>
	<?php foreach($weekdays as $day => $periods): ?>
		<h3><?= lang('Helpdesk.'.$day)?></h3>
		<div>
			<div class="table-responsive">
				<table>
					<thead>
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
											<input class="input" type="radio" name=<?= $period ?> value="1"
											<?php if ((isset($presences[$period])) && $presences[$period] == 1){echo "checked";} ?>>
											<span class="button present"><?= lang('Helpdesk.present')?></span>
										</label>
										<label>
											<input class="input" type="radio" name=<?= $period ?> value="2"
											<?php if ((isset($presences[$period])) && $presences[$period] == 2){echo "checked";} ?>>
											<span class="button partly-absent"><?= lang('Helpdesk.partly_absent')?></span>
										</label>
										<label>
											<input class="input" type="radio" name=<?= $period ?> value="3"
											<?php if ((isset($presences[$period])) && $presences[$period] == 3){echo "checked";} ?>>
											<span class="button absent"><?= lang('Helpdesk.absent')?></span>
										</label>
									</div>
								</td>
							<?php endforeach ?>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="table-bottom"></div>
	<?php endforeach ?>

	<div class="action-menu">
		<button type="submit" class="btn btn-save"><span><?= lang('Helpdesk.btn_save') ?></span></button>
		<button type="reset" class="btn btn-reset"><span><?= lang('Helpdesk.btn_reset') ?></span></button>
		<a class="btn btn-back" href="<?= base_url('/helpdesk/presences/presences_list') ?>"><span><?= lang('Helpdesk.btn_back')?></span></a>
	</div>
<?= form_close() ?>