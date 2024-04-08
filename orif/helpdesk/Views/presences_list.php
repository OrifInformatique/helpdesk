<?php

/**
 * presences_list view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<?= view('Helpdesk\Common\body_start') ?>

<nav><a class="btn btn-back" href="<?= base_url('/helpdesk/planning/cw_planning') ?>"><span><?= lang('Buttons.back')?></span></a></nav>

<div class="planning-table">
	<div class="action-menu table-top">
		<a class="btn btn-add" href="<?= base_url('/helpdesk/presences/add_technician_presences') ?>"><span><?= lang('Buttons.add_technician_presences') ?></span></a>
		<?php if(isset($_SESSION['user_id'])): ?>
			<a class="btn btn-edit" href="<?= base_url('/helpdesk/presences/technician_presences/'.$_SESSION['user_id']) ?>"><span><?= lang('Buttons.my_presences') ?></span></a>
		<?php else: ?>
			<button disabled class="btn btn-edit"><span><?= lang('Buttons.my_presences') ?></span></button>
		<?php endif; ?>
	</div>
	<table class="table-responsive
	<?php if(isset($classes))
	{
		foreach($classes as $class)
		{
			echo $class;
		}
	}?>
	">
		<thead>
			<tr>
				<th></th>
				<th colspan="4"><?= lang('Time.monday')?></th>
				<th colspan="4"><?= lang('Time.tuesday')?></th>
				<th colspan="4"><?= lang('Time.wednesday')?></th>
				<th colspan="4"><?= lang('Time.thursday')?></th>
				<th colspan="4"><?= lang('Time.friday')?></th>
				<?php if (isset($all_users_presences) && !empty($all_users_presences)) echo '<th class="empty-cell"></th><th class="empty-cell"></th>' ?>
			</tr>
			<tr>
				<th><?= lang('MiscTexts.technician')?></th>

				<?php for($i = 0; $i < 5; $i++): ?>
					<th><?= lang('Time.08:00').' '.lang('Time.10:00') ?></th>
					<th><?= lang('Time.10:00').' '.lang('Time.12:00') ?></th>
					<th><?= lang('Time.12:45').' '.lang('Time.15:00') ?></th>
					<th><?= lang('Time.15:00').' '.lang('Time.16:57') ?></th>
				<?php endfor; ?>
				<?php if (isset($all_users_presences) && !empty($all_users_presences)) echo '<th class="empty-cell"></th><th class="empty-cell"></th>' ?>
			</tr>
		</thead>
		<tbody>
			<?php if (isset($all_users_presences) && !empty($all_users_presences)) : ?>
				<?php foreach ($all_users_presences as $user_presences) : ?>
					<tr>
						<th>
							<?= $user_presences['last_name_user_data'].'<br>'.$user_presences['first_name_user_data']; ?>
						</th>

						<?php foreach ($_SESSION['helpdesk']['presences_periods'] as $period)
						{
							switch($user_presences[$period])
							{
								case 1:
									echo '<td class="present">'.lang('HelpdeskLexicon/Presences.present_symbol').'</td>';
									break;

								case 2:
									echo '<td class="partly-absent">'.lang('HelpdeskLexicon/Presences.partly_absent_symbol').'</td>';
									break;

								case 3:
									echo '<td class="absent">'.lang('HelpdeskLexicon/Presences.absent_symbol').'</td>';
									break;
							}
						}?>

						<td>
							<a class="btn btn-edit" href="<?= base_url('/helpdesk/presences/technician_presences/'.$user_presences['fk_user_id'])?>"></a>
						</td>
						<td>
							<a class="btn btn-delete" href="<?= base_url('/helpdesk/presences/delete_presences/'.$user_presences['id_presence'])?>"></a>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr>
					<td colspan="21">
						<?= lang('Errors.no_technician_presences')?>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
	<?= view('Helpdesk\Common\planning_bottom') ?>

	<div class="roles roles-presences">
		<div class="present"><?= lang('HelpdeskLexicon/Presences.present')?></div>
		<div class="partly-absent"><?= lang('HelpdeskLexicon/Presences.partly_absent')?></div>
		<div class="absent"><?= lang('HelpdeskLexicon/Presences.absent')?></div>
	</div>
</div>