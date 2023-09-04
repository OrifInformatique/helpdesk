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

<div id="no-technician-available" class="d-flex justify-content-center hidden">
    <p class="no-technician"> <?= lang('Helpdesk.no_technician_available')?></p>
</div>    

<!-- Title, if exists -->
<?php if(isset($title)){
    echo ('<h2>' . $title . '</h2>');
} ?>

<!-- Success message, if exists -->
<?php if(isset($success)): ?>
    <div class="d-flex justify-content-center">
        <?php echo ('<p class="success">'.$success.'</p>'); ?>
    </div>
<?php endif; ?>

<!-- Error message, if exists -->
<?php if(isset($error)): ?>
    <div class="d-flex justify-content-center">
        <?php echo ('<p class="error">'.$error.'</p>'); ?>
    </div>
<?php endif; ?>

<?php if(isset($technicians) && !empty($technicians)): ?>
    <div class="terminal-display container-fluid">
        <?php $i = 1 ?>
        <?php foreach($technicians as $technician): ?>
            <div class="technician-sheet technician-<?= $i ?>-card d-flex justify-content-center">
                <div class="technician-<?= $i ?>-unavailable-text unavailable-text hidden"><?= lang('Helpdesk.unavailable')?></div>
                <div class="role">
                    <p>
                        <?php switch($technician[$period])
                        {
                            case 1:
                                echo '<div class="bg-green border-xs-1 p-2 rounded rounded-3 mx-4">'.lang('Helpdesk.role_1').'</div>';
                                break;
                            case 2:
                                echo '<div class="bg-yellow border-xs-1 p-2 rounded rounded-3 mx-4">'.lang('Helpdesk.role_2').'</div>';
                                break;
                            case 3:
                                echo '<div class="bg-orange border-xs-1 p-2 rounded rounded-3 mx-4">'.lang('Helpdesk.role_3').'</div>';
                                break;
                        }
                        ?>           
                    </p>
                </div>

                <div class="technician-picture">
                    <img src="<?= $technician['photo_user_data'] ?>" alt="<?= lang('Helpdesk.alt_photo_technician') ?>">
                </div>

                <div class="identity">
                    <p>
                        <?= $technician['last_name_user_data'].' '.$technician['first_name_user_data'] ?>
                    </p>
                </div>

            </div>
            <?php $i++ ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="auto-refresh-timer">
    <p><?= lang('Helpdesk.updating_in') ?> <span class="timer"></span> <?= lang('Helpdesk.seconds') ?>.</p>
</div>