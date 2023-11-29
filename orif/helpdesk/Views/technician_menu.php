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

<?php foreach($user as $user): ?>
    <div>
        <?= lang('Helpdesk.technician_menu').'<strong>'.$user['last_name_user_data'].' '.$user['first_name_user_data'].'</strong> ?'?>
    </div>
    <div>
        <a class="btn btn-primary mb-3" href="javascript:history.back()"><?= lang('Helpdesk.btn_cancel')?></a>
    </div>
<?php endforeach; ?>