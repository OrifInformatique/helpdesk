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

<?php if(!isset($presences)): ?>
	<div class="d-flex justify-content-center alert alert-info">
		<?= '<i class="fa-solid fa-circle-info"></i>'.lang('Helpdesk.info_presences_fields_empty')?>
	</div>
<?php endif; ?>

<form method="POST" action="<?= base_url('/presences/my_presences') ?>">
	<?php foreach($weekdays as $day => $periods): ?>
		<div class="d-flex justify-content-center">
			<div class="table-responsive">
				<table>
					<thead>
						<tr>
							<th colspan="4"><?= lang('Helpdesk.'.$day)?></th>
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
	<?php endforeach ?>

	<div class="action-menu d-flex justify-content-center">
		<input class="btn btn-success" type="submit" value="<?= lang('Helpdesk.btn_save')?>">
		<a class="btn btn-primary" href="<?= base_url('helpdesk/presences/all_presences') ?>"><?= lang('Helpdesk.btn_back')?></a>
	</div>
</form>