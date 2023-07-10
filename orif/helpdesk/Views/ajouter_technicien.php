<?php


/**
 * ajouter_technicien view
 *
 * @author      Orif (BlAl)
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
    td,
    input {
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

    .error {
		position: absolute;
		top: 18%;
		background-color: red;
        color: #fff;
		border-radius: 5px;
		padding: 7px 15px;
		font-size: 1.25em;

		animation: fadeOut 4s forwards;
	}

	/* Animation de disparition */
	@keyframes fadeOut 
	{
		0% { opacity: 1; }
		80% { opacity: 1; }
		100% { opacity: 0; display: none; }
	}
</style>

<div class="container-fluid">

    <!-- Affiche le titre si existant -->
    <?php if(isset($title)){ echo ('<h2>'.$title.'</h2>');} ?>

    <a class="btn btn-primary mb-3" href="<?= base_url('helpdesk/home') ?>">Retour</a>

    <form action="<?= base_url('helpdesk/home/ajouterTechnicien') ?>" method="post">

        <input class="btn btn-blue mb-3" type="submit" value="Enregistrer"/>

        <!-- Message d'erreur, si existant -->
		<?php if (isset($error)): ?>
			<div class="d-flex justify-content-center">
				<?php echo ('<p class="error">'.$error.'</p>'); ?>
			</div>
		<?php endif; ?>

        <div class="d-flex justify-content-center">
            <div class="bg-green border-xs-1 p-2 rounded rounded-3 mx-4">1 - Technicien d'astreinte</div> <!-- c5deb5 -->
            <div class="bg-yellow border-xs-1 p-2 rounded rounded-3 mx-4">2 - Technicien de backup</div> <!-- e5f874 -->
            <div class="bg-orange border-xs-1 p-2 rounded rounded-3 mx-4">3 - Technicien de réserve</div> <!-- ffd965 -->
        </div>

        <div class="week">
            Planning de la semaine du
            <span class="start-date">
                <!-- Affiche le lundi de la semaine en cours -->
                <?php echo date('d/m/Y', strtotime('monday this week')); ?>
            </span>
            au
            <span class="end-date">
                <!-- Affiche le vendredi de la semaine en cours -->
                <?php echo date('d/m/Y', strtotime('friday this week')); ?>
            </span>
        </div>

        <div class="">
            <p> Technicien à ajouter au planning </p>
            <select name="technicien" required>
                <option disabled selected></option>
                <?php 
                foreach($users as $user)
                {
                    echo('<option value="'.$user['id'].'">'.$user['nom_user_data'].' '.$user['prenom_user_data'].'</option>');
                } 
                ?>
            </select>
        </div>

        <table class="table-responsive position-relative">
            <thead>
                <tr>
                    <th colspan="4">Lundi <?php echo date('d', strtotime('monday this week')); ?></th>
                    <th colspan="4">Mardi <?php echo date('d', strtotime('tuesday this week')); ?></th>
                    <th colspan="4">Mercredi <?php echo date('d', strtotime('wednesday this week')); ?></th>
                    <th colspan="4">Jeudi <?php echo date('d', strtotime('thursday this week')); ?></th>
                    <th colspan="4">Vendredi <?php echo date('d', strtotime('friday this week')); ?></th>
                </tr>
                <tr>
                    <?php 
                    // Boucle répétant 5x les horaires
                    for($i = 0; $i < 5; $i++): ?>
                        <th>8:00 10:00</th>
                        <th>10:00 12:00</th>
                        <th>12:45 15:00</th>
                        <th>15:00 16:57</th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php 
                    // Boucle répétant 20x les choix des rôles des rôles
                    for($i = 0; $i < 20; $i++): ?>   
                        <td>
                            <select name="<?php echo($presences[$i]);?>">
                                <option selected></option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </td>
                    <?php endfor; ?>
                </tr>
            </tbody>
        </table>
    </form>
</div>