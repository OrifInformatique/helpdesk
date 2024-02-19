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

<nav><a class="btn btn-back" href="<?= base_url('/helpdesk/planning/cw_planning') ?>"><span><?= lang('Helpdesk.btn_back')?></span></a></nav>

<div class="planning-table">
	<div class="action-menu table-top">
		<a class="btn btn-add" href="<?= base_url('/helpdesk/presences/add_technician_presences') ?>"><span><?= lang('Helpdesk.btn_add_technician_presences') ?></span></a>
		<a class="btn btn-edit" href="<?= base_url('/helpdesk/presences/technician_presences/'.$_SESSION['user_id']) ?>"><span><?= lang('Helpdesk.btn_my_presences') ?></span></a>
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
				<th colspan="4"><?= lang('Helpdesk.monday')?></th>
				<th colspan="4"><?= lang('Helpdesk.tuesday')?></th>
				<th colspan="4"><?= lang('Helpdesk.wednesday')?></th>
				<th colspan="4"><?= lang('Helpdesk.thursday')?></th>
				<th colspan="4"><?= lang('Helpdesk.friday')?></th>
				<?php if (isset($all_users_presences) && !empty($all_users_presences)) echo '<th class="empty-cell"></th><th class="empty-cell"></th>' ?>
			</tr>
			<tr>
				<th><?= lang('Helpdesk.technician')?></th>

				<?php for($i = 0; $i < 5; $i++): ?>
					<th>8:00 10:00</th>
					<th>10:00 12:00</th>
					<th>12:45 15:00</th>
					<th>15:00 16:57</th>
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
									echo '<td class="present">P</td>';
									break;

								case 2:
									echo '<td class="partly-absent">I</td>';
									break;

								case 3:
									echo '<td class="absent">A</td>';
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
						<?= lang('Helpdesk.err_no_technician_presences')?>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
	<?= view('Helpdesk\Common\planning_bottom') ?>

	<div class="roles roles-presences">
		<div class="present">P - <?= lang('Helpdesk.present')?></div>
		<div class="partly-absent">I - <?= lang('Helpdesk.partly_absent')?></div>
		<div class="absent">A - <?= lang('Helpdesk.absent')?></div>
	</div>
</div>