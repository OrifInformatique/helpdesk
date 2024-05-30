<?php

/**
 * Technician photo component
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<img src="<?= base_url('helpdesk/home/showTechnicianPhoto/'.$photo_user_data) ?>" 
    alt="<?= lang('Technician.alt_technician_photo').' '.$last_name_user_data.' '.$first_name_user_data ?>"
    onerror="DisplayDefaultTechnicianPhoto(this)">