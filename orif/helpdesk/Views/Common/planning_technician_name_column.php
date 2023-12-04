<?php

/**
 * Planning technicians names column in planning table component
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<th <?php if($fk_user_type == 4){echo 'class="mentor"';}?>>
    <a href="<?= base_url('/helpdesk/technician/dashboard/'.$fk_user_id) ?>">
        <?= $last_name_user_data.'<br>'.$first_name_user_data; ?>
    </a>
</th>