<?php

/**
 * terminal view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

?>

<div id="reload-page-data" data-reload-page="<?= htmlspecialchars(base_url('helpdesk/home/terminal')) ?>"></div>
<script src="<?= base_url('Scripts/terminal/terminal.js')?>" defer></script>

<div id="no-technician-available" class="d-flex justify-content-center 
<?php 
    if($technicians_availability[0]['tech_available_terminal'] == true || 
    $technicians_availability[1]['tech_available_terminal'] == true || 
    $technicians_availability[2]['tech_available_terminal'] == true)
    {
        echo "hidden";
    };
?>">
    <p class="no-technician"> <?= lang('Helpdesk.no_technician_available')?></p>
</div>

<!-- Title, if exists -->
<?php if(isset($title)){
    echo ('<h2>' . $title . '</h2>');
} ?>

<!-- Success message, if exists -->
<?php if(isset($success)): ?>
    <div class="d-flex justify-content-center">
        <?= ('<p class="success">'.$success.'</p>'); ?>
    </div>
<?php endif; ?>

<!-- Error message, if exists -->
<?php if(isset($error)): ?>
    <div class="d-flex justify-content-center">
        <?= ('<p class="error">'.$error.'</p>'); ?>
    </div>
<?php endif; ?>

<?php if(isset($technicians) && !empty($technicians) && $day_off === false): ?>
    <div class="terminal-display container-fluid">
        <?php foreach($technicians as $technician): ?>
            <a class="technician-sheet d-flex justify-content-center <?= $technicians_availability[$technician[$period] -1]['tech_available_terminal'] == true ? '' : 'unavailable'; ?>" href="<?= base_url('helpdesk/home/updateTechnicianAvailability/'.$technician[$period]) ?>">
                <p class="technician-<?= $technician[$period] ?>-unavailable-text unavailable-text <?= $technicians_availability[$technician[$period] -1]['tech_available_terminal'] == true ? 'hidden' : ''; ?>"><?= lang('Helpdesk.unavailable')?></p>
                <div class="role">
                    <p>
                        <?php switch($technician[$period])
                        {
                            case 1:
                                echo '<div class="bg-green border-xs-1 p-2 rounded rounded-3 mx-4">'.lang('Helpdesk.role_1').'</div>';
                                break;
                            case 2:
                                echo '<div class="bg-light-green border-xs-1 p-2 rounded rounded-3 mx-4">'.lang('Helpdesk.role_2').'</div>';
                                break;
                            case 3:
                                echo '<div class="bg-orange border-xs-1 p-2 rounded rounded-3 mx-4">'.lang('Helpdesk.role_3').'</div>';
                                break;
                        }
                        ?>           
                    </p>
                </div>

                <div>
                    <img src="<?= $technician['photo_user_data'] ?>" alt="<?= lang('Helpdesk.alt_photo_technician') ?>">
                </div>

                <div class="identity">
                    <p>
                        <?= $technician['last_name_user_data'].' '.$technician['first_name_user_data'] ?>
                    </p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div id="no-technician-available" class="d-flex justify-content-center">
        <p class="no-technician"> <?= lang('Helpdesk.no_technician_available')?></p>
    </div>
<?php endif; ?>

<div class="auto-refresh-timer">
    <p><?= lang('Helpdesk.updating_in') ?> <span class="timer"></span> <?= lang('Helpdesk.seconds') ?>.</p>
</div>