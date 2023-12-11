<?php

/**
 * terminal view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<?= view('Helpdesk\Common\body_start') ?>

<div id="reload-page-data" data-reload-page="<?= htmlspecialchars(base_url('/helpdesk/terminal/display')) ?>"></div>
<div id="img-link-data" data-img-link="<?= htmlspecialchars(base_url('/images/helpdesk/default_technician_picture.jpg')) ?>"></div>
<script src="<?= base_url('Scripts/terminal/terminal.js')?>" defer></script>
<script src="<?= base_url('Scripts/terminal/image_error_handling.js')?>"></script>

<div id="no-technician-available" class="d-flex justify-content-center 
<?php if($technicians_availability[0]['tech_available_terminal'] == true || 
        $technicians_availability[1]['tech_available_terminal'] == true || 
        $technicians_availability[2]['tech_available_terminal'] == true)
    {
        echo "hidden";
    };?>
">
    <p class="no-technician"> <?= lang('Helpdesk.err_no_technician_available')?></p>
</div>

<?php if(isset($technicians) && !empty($technicians) && $day_off === false): ?>
    <div class="terminal-display container-fluid">
        <?php foreach($technicians as $technician): ?>
            <a class="technician-sheet d-flex justify-content-center <?= $technicians_availability[$technician[$period] -1]['tech_available_terminal'] == true ? '' : 'unavailable'; ?>" href="<?= base_url('/helpdesk/terminal/update_technician_availability/'.$technician[$period]) ?>">
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
                        }?>
                    </p>
                </div>

                <div>
                    <img src="<?= $technician['photo_user_data'] ?>" alt="<?= lang('Helpdesk.alt_photo_technician') ?>" onerror="HideImage<?= $technician[$period] ?>(this)">
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
        <p class="no-technician"> <?= lang('Helpdesk.err_no_technician_available')?></p>
    </div>
<?php endif; ?>

<div class="auto-refresh-timer">
    <p><?= lang('Helpdesk.updating_in') ?> <span class="timer"></span> <?= lang('Helpdesk.seconds') ?>.</p>
</div>