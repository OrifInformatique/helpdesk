<?php

/**
 * terminal view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

?>

<div id="reload-page-data" data-reload-page="<?= htmlspecialchars(base_url('helpdesk/home/terminalDisplay')) ?>"></div>
<script src="<?= base_url('Scripts/terminal.js')?>" defer></script>

<style>

    #login-bar, hr, div[class='alert alert-warning text-center'], #toolbarContainer
    {
        display: none;
    }

    .terminal-display
    {
        display: flex;
        justify-content: center;
        flex-direction: row;
        position: absolute;
        top: 30%;
    }

    @media screen and (max-width: 1150px) and (max-height: 1080px)
    {
        .terminal-display
        {
            flex-direction: column;
            align-items: center;
            height: 100%;
        }

        .unavailable
        {
            transform: scale(0.5);
            transition-duration: 0.5s;
            filter: grayscale(0%);
            filter: opacity(40%);
        }

        .unavailable:hover
        {
            transform: scale(0.6);
            filter: opacity(60%);
        }

        .unavailable-text
        {
            margin-top: 0;
        }
    }

    @media screen and (max-height: 900px)
    {
        .terminal-display
        {
            top: 0;
        }    
    }

    .technician-sheet
    {
        align-items: center;
        position: relative;
        flex-direction: column;
        background-color: #efefef;
        min-width: 350px;
        max-width: fit-content;
        min-height: 400px;
        max-height: fit-content;
        margin: 15px;
        padding: 5px;
        transition-duration: 0.3s;
    }

    .technician-sheet:hover
    {
        transform: scale(1.05);
    }

    .role, .identity
    {
        font-size: 1.5em;
        margin: auto;
    }

    .role
    {
        position: absolute;
        top: 0;

    }

    .terminal-display img
    {
        margin-top: 100px;
        min-width: 325px;
        max-width: 325px;
        min-height: 325px;
        max-height: 325px;
    }

    .bg-green 
    {
        background-color: #c5deb5;
    }

    .bg-yellow 
    {
        background-color: #e5f874;
    }

    .bg-orange 
    {
        background-color: #ffd965;
    }    
    
    .no_technician
    {
        position: absolute;
        top: 50%;
        text-align: center;
        font-size: 2em;
        color: #fff;
        font-weight: bold;
        background-color: red;
        border-radius: 10px;
        padding: 3px 15px;
        z-index: 5;
    }

    .unavailable
    {
        transform: scale(0.5);
        transform: translateY(150px);
        transition-duration: 0.5s;
        filter: grayscale(0%);
        filter: opacity(40%);
    }

    .unavailable:hover
    {
        transform: scale(0.6);
        transform: translateY(125px);
        filter: opacity(60%);
    }

    .unavailable-text
    {
        margin-top: -60px;
        font-weight: bold;
        background-color: lightgray;
        padding: 6px;
        border-radius: 5px;
        font-size: 1.1em;
        filter: opacity(100%);
        
    }

    .hidden
    {
        display: none;
        filter: opacity(0%);
    }

    .auto-refresh-timer
    {
        width: 100%;
        display: flex;
        justify-content: center;
        position: fixed;
        bottom: 2%;
    }

    span
    {
        font-weight: bold;
        font-size: 1.15em;
    }

</style>

<!-- Title, if exists -->
<?php if (isset($title)) {
    echo ('<h2>' . $title . '</h2>');
} ?>

<div id="no-technician-available" class="d-flex justify-content-center hidden">
    <p class="no_technician"> <?= lang('Helpdesk.no_technician_available')?></p>
</div>

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