<?php

/**
 * modification_planning view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

?>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        padding: 8px;
        text-align: center;
        border: 1px solid #ddd;
    }

    thead tr:nth-child(1) th {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
    }

    thead tr:nth-child(2) th {
        font-weight: bold;
    }

    tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .week {
        background-color: #eee;
        padding: 8px;
        font-size: 18px;
        text-align: center;
    }

    .bg-green {
        background-color: #c5deb5;
    }

    .bg-yellow {
        background-color: #e5f874;
    }

    .bg-orange {
        background-color: #ffd965;
    }

    .btn-blue {
        background-color: #b4c6e7;
    }

    .btn-blue:hover {
        color: #fff;
        background-color: #b4c6e7;
        border-color: #b4c6e7
    }
</style>

<div class="container-fluid">

    <!-- Affiche le titre si existant -->
    <?php if (isset($title)) {
        echo ('<h2>' . $title . '</h2>');
    } ?>

    <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home') ?>"><?php echo lang('Helpdesk.btn_back')?></a>

    <form method="POST" action="<?= base_url('helpdesk/home/modificationPlanning') ?>">

        <input class="btn btn-blue mb-3" type="submit" value="<?php echo lang('Helpdesk.btn_save')?>">

        <div class="d-flex justify-content-center">
            <div class="bg-green border-xs-1 p-2 rounded rounded-3 mx-4"><?php echo lang('Helpdesk.role_1')?></div> <!-- c5deb5 -->
            <div class="bg-yellow border-xs-1 p-2 rounded rounded-3 mx-4"><?php echo lang('Helpdesk.role_2')?></div> <!-- e5f874 -->
            <div class="bg-orange border-xs-1 p-2 rounded rounded-3 mx-4"><?php echo lang('Helpdesk.role_3')?></div> <!-- ffd965 -->
        </div>

        <div class="week">
            <?php echo lang('Helpdesk.planning_of_week')?>
            <span class="start-date">
                <!-- Affiche le lundi de la semaine en cours -->
                <?php echo date('d/m/Y', strtotime('monday this week')); ?>
            </span>
            <?php echo lang('Helpdesk.to')?>
            <span class="end-date">
                <!-- Affiche le vendredi de la semaine en cours -->
                <?php echo date('d/m/Y', strtotime('friday this week')); ?>
            </span>
        </div>


        <table class="table-responsive position-relative">
            <thead>
                <tr>
                    <th></th>
                    <th colspan="4"><?php echo lang('Helpdesk.monday').' '.date('d', strtotime('monday this week')); ?></th>
                    <th colspan="4"><?php echo lang('Helpdesk.tuesday').' '.date('d', strtotime('tuesday this week')); ?></th>
                    <th colspan="4"><?php echo lang('Helpdesk.wednesday').' '.date('d', strtotime('wednesday this week')); ?></th>
                    <th colspan="4"><?php echo lang('Helpdesk.thursday').' '.date('d', strtotime('thursday this week')); ?></th>
                    <th colspan="4"><?php echo lang('Helpdesk.friday').' '.date('d', strtotime('friday this week')); ?></th>
                </tr>

                <tr>
                    <th>Technicien</th>

                    <?php
                    // Boucle répétant 5x les horaires
                    for ($i = 0; $i < 5; $i++) : ?>

                        <th>8:00 10:00</th>
                        <th>10:00 12:00</th>
                        <th>12:45 15:00</th>
                        <th>15:00 16:57</th>

                    <?php endfor; ?>

                </tr>
            </thead>

            <tbody>
                <?php if (isset($planning_data)) : ?>
                    <?php foreach ($planning_data as $planning) : ?>      
                        <tr>
                            <th><?php echo $planning['fk_user_id']; ?></th>
                            <input type="hidden" name="id_planning" value="<?php echo $planning['id_planning']; ?>">
                            <input type="hidden" name="fk_user_id" value="<?php echo $planning['fk_user_id']; ?>">
                            <?php foreach ($form_fields_data as $field) : ?>
                                <td>
                                    <select name="<?php echo $field?>">
                                        <?php
                                        $choices = array('', 1, 2, 3);

                                        foreach ($choices as $choice) 
                                        {
                                            $selected = ($planning[$field] == $choice) ? 'selected' : '';
                                            echo '<option value="' . $choice . '" ' . $selected . '>' . $choice . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td><?php echo lang('Helpdesk.no_technician_assigned')?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>