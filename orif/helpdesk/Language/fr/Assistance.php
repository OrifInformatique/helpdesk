<?php
/**
 * French translations for assitance page
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (http://www.orif.ch)
 */

return[

    /* Roles explaination */
    'roles_summary' => 'Rôles',
    'roles_details' => 
    '<p>Les rôles définissent les responsabilités des techniciens lors de la gestion du helpdesk.</p>
    <p>Il y a 3 niveaux :</p>
    <ul>
        <li>Technicien d\'abstreinte</li>
        <li>Technicien de backup</li>
        <li>Technicien de réserve</li>
    </ul>
    <p>
        Être <span class="bg-green">technicien d\'abstreinte</span> consiste à répondre au client, que ce soit par téléphone ou en direct.<br>
        Être <span class="bg-light-green">technicien de backup</span> consiste à répondre au client pendant l\'absence du technicien d\'astreinte.<br>
        Être <span class="bg-orange">technicien de réserve</span> consiste à répondre au client pendant l\'absence du technicien d\'astreinte 
        <strong>et</strong> du technicien de backup.
    </p>
    <p>
        Si aucun technicien n\'est assigné ou disponible à une période, une autre personne doit gérer le helpdesk.
    </p>',

    /* Presences explaination */
    'presences_summary' => 'Présences',
    'presences_details' => '
    <p>Les présences des techniciens permettent d\'indiquer les disponibilités des techniciens sur les périodes de la semaine.</p>
    <p>Il y a 3 options possibles :</p>
    <ul>
        <li>Présent</li>
        <li>Absent en partie</li>
        <li>Absent</li>
    </ul>
    <p>
        Être <span class="present">présent</span> signifie que, durant la totalité de la période, le technicien est dans la zone de travail.<br>
        Être <span class="partly-absent">absent en partie</span> signifie que, durant la majorité du temps (pas totalement), 
        le technicien est dans la zone de travail.<br>
        Être <span class="absent">absent</span> signifie que, durant la totalité de la période, le technicien <strong>n\'est pas</strong> 
        dans la zone de travail.
    </p>
    <p>
        Les présences sont importantes à renseigner : il est impossible d\'ajouter au planning ou de générer un planning 
        pour un technicien n\'ayant pas de présences.<br>
        Lors de l\'altération du planning, l\'option "Ignorer la vérification des présences" permet de outrepasser cette restriction. 
        À utiliser qu\'en cas de nécessité.
    </p>',

    /* Mentor explaination */
    'mentor_summary' => 'Technicien parrain',
    'mentor_details' =>
    '<p>Le technicien parrain est un technicien aguerri, avec la mission de former les nouveaux techniciens au helpdesk.</p>
    <p>Ces derniers sont soutenus par le technicien parrain grâce à ses conseils avisés tout au long du processus de gestion de demandes/incidents :</p>
    <ul>
        <li>Gestion des appels</li>
        <li>Création des tickets</li>
        <li>Intervention sur site</li>
    </ul>
    <p>
        En principe, le technicien parrain ne fait pas le travail à la place de l\'apprenant (sauf s\'il a besoin d\'aide, évidemment).
    </p>
    <p>
        Sur ce site, les techniciens parrains sont distingués dans les plannings par une bande bleue à l\'intérieur de la case comportant leur nom et prénom.
    </p>'
];