<?php

/**
 * technician menu view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<?= view('Helpdesk\Common\body_start') ?>

<?php foreach($user as $user): ?>
    <h3><?= $user['last_name_user_data'].' '.$user['first_name_user_data']?></h3>
    <div>
        <a class="btn btn-back" href="<?= base_url('/helpdesk/planning/cw_planning') ?>"><span><?= lang('Buttons.back')?></span></a>
    </div>
<?php endforeach; ?>