<?php

/**
 * terminal view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

?>

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

    @media screen and (max-width: 1440px) and (max-height: 1080px)
    {
        .terminal-display
        {
            flex-direction: column;
            align-items: center;
        }        

        .technician-sheet:first-child
        {
            margin: 0 0 80px 80px;
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
    
    .technician-sheet:first-child
    {
        scale: 1.3;
        margin-right: 80px;
    }

    .technician-sheet:hover
    {
        transform: scale(1.05);
        transition-duration: 0.3s;
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
    }
</style>

<!-- Title, if exists -->
<?php if (isset($title)) {
    echo ('<h2>' . $title . '</h2>');
} ?>

<div class="terminal-display container-fluid">
    <?php if(isset($technicians) && !empty($technicians)): ?>
        <?php foreach($technicians as $technician): ?>
            <div class="technician-sheet d-flex justify-content-center">

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
        <?php endforeach; ?>
    <?php else: ?>
        <div class="d-flex justify-content-center">
            <p class="no_technician"> <?= lang('Helpdesk.no_technician_available')?></p>
        </div>
    <?php endif; ?>

</div>