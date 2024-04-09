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

<div id="reload-page-data" data-reload-page="<?= htmlspecialchars(base_url('/helpdesk/terminal/display/'.$preview)) ?>"></div>
<div id="img-link-data" data-img-link="<?= htmlspecialchars(base_url('/images/helpdesk/default_technician_picture.jpg')) ?>"></div>
<script src="<?= base_url('Scripts/terminal/terminal.js')?>" defer></script>
<script src="<?= base_url('Scripts/terminal/image_error_handling.js')?>"></script>

<?php if($preview) :?>
    <div class="preview-filter">
        <p class="preview-text preview-text-left"><?= lang('MiscTexts.preview') ?></p>
        <p class="preview-text preview-text-right"><?= lang('MiscTexts.preview') ?></p>
    </div>
<?php endif; ?>

<?php if($day_off == true || !isset($technicians_availability) ||
        $technicians_availability[0]['tech_available_terminal'] == false &&
        $technicians_availability[1]['tech_available_terminal'] == false &&
        $technicians_availability[2]['tech_available_terminal'] == false) : 
?>
    <div id="no-technician-available" class="d-flex justify-content-center">
        <p class="no-technician"> <?= lang('Errors.no_technician_available')?></p>
    </div>
<?php endif; ?>

<?php if($day_off === false && isset($technicians) && !empty($technicians) ): ?>
    <div class="terminal-display">
        <?php foreach($technicians as $technician): ?>
            <a class="technician-sheet d-flex justify-content-center <?= $technicians_availability[$technician[$period] -1]['tech_available_terminal'] == true ? '' : 'unavailable'; ?> <?= $preview ? 'no-click' : '' ?>" href="<?= base_url('/helpdesk/terminal/update_technician_availability/'.$technician[$period]) ?>">
                <p class="technician-<?= $technician[$period] ?>-unavailable-text unavailable-text <?= $technicians_availability[$technician[$period] -1]['tech_available_terminal'] == true ? 'hidden' : ''; ?>"><?= lang('MiscTexts.unavailable')?></p>
                <div class="role">
                    <p>
                        <?php switch($technician[$period])
                        {
                            case 1:
                                echo '<div class="bg-green border-xs-1 p-2 rounded rounded-3 mx-4">'.lang('HelpdeskLexicon/Roles.first_technician_role_name').'</div>';
                                break;
                            case 2:
                                echo '<div class="bg-light-green border-xs-1 p-2 rounded rounded-3 mx-4">'.lang('HelpdeskLexicon/Roles.second_technician_role_name').'</div>';
                                break;
                            case 3:
                                echo '<div class="bg-orange border-xs-1 p-2 rounded rounded-3 mx-4">'.lang('HelpdeskLexicon/Roles.third_technician_role_name').'</div>';
                                break;
                        }?>
                    </p>
                </div>

                <div>
                    <img src="<?= $technician['photo_user_data'] ?>" alt="<?= lang('MiscTexts.alt_photo_technician') ?>" onerror="HideImage<?= $technician[$period] ?>(this)">
                </div>

                <div class="identity">
                    <p>
                        <?= $technician['last_name_user_data'].' '.$technician['first_name_user_data'] ?>
                    </p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php elseif(isset($technicians_availability)): ?>
    <div id="no-technician-available" class="d-flex justify-content-center">
        <p class="no-technician"> <?= lang('Errors.no_technician_available')?></p>
    </div>
<?php endif; ?>

<div class="auto-refresh-timer">
    <p><?= lang('MiscTexts.updating_in') ?> <span class="timer"></span> <?= lang('MiscTexts.seconds') ?>.</p>
</div>