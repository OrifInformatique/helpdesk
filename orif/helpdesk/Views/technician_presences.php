<?php

/**
 * technician_presences view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<div id="technician-presences">

<?= view('Helpdesk\Common\body_start') ?>

<?php if(!isset($presences)): ?>
	<div class="d-flex justify-content-center alert alert-info">
		<?= '<i class="fa-solid fa-circle-info"></i>'.lang('Infos.presences_fields_empty')?>
	</div>
<?php endif; ?>

<div class="select-presence-for-all-fields">
	<div>
		<p><?= lang('MiscTexts.select_presence_for_all_fields') ?></p>
	</div>
	<div>
		<button class="btn btn-present" onclick="ChangeAllFormValues(1)"><span><?= lang('HelpdeskLexicon/Presences.present_name') ?></span></button>
		<button class="btn btn-partly-absent" onclick="ChangeAllFormValues(2)"><span><?= lang('HelpdeskLexicon/Presences.partly_absent_name') ?></span></button>
		<button class="btn btn-absent" onclick="ChangeAllFormValues(3)"><span><?= lang('HelpdeskLexicon/Presences.absent_name') ?></span></button>
	</div>

</div>

<?= form_open(base_url('/helpdesk/presences/technician_presences/'.$user_id)) ?>
	<?php foreach($weekdays as $day => $periods): ?>
		<h3><?= lang('Time.'.$day)?></h3>
		<div>
			<div class="table-responsive">
				<table>
					<thead>
						<tr>
							<th><?= lang('Time.08:00').' - '.lang('Time.10:00') ?></th>
							<th><?= lang('Time.10:00').' - '.lang('Time.12:00') ?></th>
							<th><?= lang('Time.12:45').' - '.lang('Time.15:00') ?></th>
							<th><?= lang('Time.15:00').' - '.lang('Time.16:57') ?></th>
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
											<span class="button present"><?= lang('HelpdeskLexicon/Presences.present_name')?></span>
										</label>
										<label>
											<input class="input" type="radio" name=<?= $period ?> value="2"
											<?php if ((isset($presences[$period])) && $presences[$period] == 2){echo "checked";} ?>>
											<span class="button partly-absent"><?= lang('HelpdeskLexicon/Presences.partly_absent_name')?></span>
										</label>
										<label>
											<input class="input" type="radio" name=<?= $period ?> value="3"
											<?php if ((isset($presences[$period])) && $presences[$period] == 3){echo "checked";} ?>>
											<span class="button absent"><?= lang('HelpdeskLexicon/Presences.absent_name')?></span>
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
		<button type="submit" class="btn btn-save"><span><?= lang('Buttons.save') ?></span></button>
		<button type="reset" class="btn btn-reset"><span><?= lang('Buttons.reset') ?></span></button>
		<a class="btn btn-back" href="<?= base_url('/helpdesk/presences/presences_list') ?>"><span><?= lang('Buttons.back')?></span></a>
	</div>
<?= form_close() ?>

</div>