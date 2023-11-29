<?php

/**
 * all_presences view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<a class="btn btn-primary" href="<?= base_url('/planning/cw_planning') ?>"><?= lang('Helpdesk.btn_back')?></a>

<div class="presences">
	<div class="d-flex justify-content-center roles">
		<div class="present border-xs-1 p-2 rounded rounded-3 mx-3">P - <?= lang('Helpdesk.present')?></div>
		<div class="partly-absent border-xs-1 p-2 rounded rounded-3 mx-3">I - <?= lang('Helpdesk.partly_absent')?></div>
		<div class="absent border-xs-1 p-2 rounded rounded-3 mx-3">A - <?= lang('Helpdesk.absent')?></div>
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
				<?php if (isset($all_users_presences) && !empty($all_users_presences)) echo '<th></th>' ?>
			</tr>
			<tr>
				<th><?= lang('Helpdesk.technician')?></th>

				<?php for($i = 0; $i < 5; $i++): ?>
					<th>8:00 10:00</th>
					<th>10:00 12:00</th>
					<th>12:45 15:00</th>
					<th>15:00 16:57</th>
				<?php endfor; ?>
				<?php if (isset($all_users_presences) && !empty($all_users_presences)) echo '<th></th>' ?>
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
							<a class="btn btn-danger" href="<?= base_url('/presences/delete_presences/'.$user_presences['id_presence'])?>">✕</a> <!-- ✕ => U+2715 | &#10005; -->
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

	<div class="action-menu d-flex justify-content-center">
			<a class="btn btn-blue" href="<?= base_url('/presences/my_presences') ?>"><?= lang('Helpdesk.btn_my_presences')?></a>
	</div>
</div>