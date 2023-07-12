<?php

namespace Helpdesk\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Helpdesk\Models\Presence_model;
use Helpdesk\Models\Planning_model;
use Helpdesk\Models\User_Data_model;

class Home extends BaseController
{

    protected $session;
    protected $presence_model;
    protected $planning_model;
    protected $user_data_model;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
        $this->presence_model = new Presence_model();
        $this->planning_model = new Planning_model();
        $this->user_data_model = new User_Data_model();

        helper('form');
    }

    public function index()
    {
        $data['title'] = "Helpdesk";

        // Récupère les données des utilisateurs ayant un planning attribué
        $planning_data = $this->planning_model->getPlanningDataByUser();

        // Ajoute le planning à la variable $data
        $data['planning_data'] = $planning_data;

        // Tableau pour les présences
        $data['periodes'] = 
        [
            'planning_lundi_m1', 'planning_lundi_m2', 'planning_lundi_a1', 'planning_lundi_a2',
            'planning_mardi_m1', 'planning_mardi_m2', 'planning_mardi_a1', 'planning_mardi_a2',
            'planning_mercredi_m1', 'planning_mercredi_m2', 'planning_mercredi_a1', 'planning_mercredi_a2',
            'planning_jeudi_m1', 'planning_jeudi_m2', 'planning_jeudi_a1', 'planning_jeudi_a2',
            'planning_vendredi_m1', 'planning_vendredi_m2', 'planning_vendredi_a1', 'planning_vendredi_a2',
        ];

        // Affiche la page du planning
        $this->display_view('Helpdesk\planning', $data);
    }

    // Vérifie si l'utilisateur est connecté
    public function isUserLogged()
    {
        // Si l'ID de l'utilisateur n'existe pas ou est vide
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) 
        {
            // Redirige vers la page de connexion
            // Utilisation de fonctions PHP natives car redirect() ou display_view() ne fonctionnent pas
            header("Location: " . base_url('user/auth/login'));
            exit();
        }

        // Sinon, exécute la suite du code normalement
    }

    // Affiche la page presence
    public function presence()
    {
        // Vérifie si l'utilisateur est connecté
        $this->isUserLogged();

        // Titre de la page
        $data['title'] = "Présences de l'apprenti";

        // Récupére l'ID de l'utilisateur
        $user_id = $_SESSION['user_id'];

        // Récupère les présences de l'utilisateur
        $presences_data = $this->presence_model->getPresencesUser($user_id);

        // Ajouter les présences à la variable $data
        $data = $presences_data;

        // Affiche la page du formulaire des presences
        $this->display_view('Helpdesk\presence', $data);

    }

    // Sauvegarde les données envoyées par le formulaire de la page presence
    function savePresence()
    {
        // Vérifie si l'utilisateur est connecté
        $this->isUserLogged();

        // Récupére l'ID de l'utilisateur depuis la session
        $user_id = $_SESSION['user_id'];

        // Récupére l'ID de la présence depuis la table "presence"
        $id_presence = $this->presence_model->getPresenceId($user_id);

        // Tableau des champs du formulaire
        $form_fields_data = [
            'lundi_debut_matin','lundi_fin_matin','lundi_debut_apres_midi','lundi_fin_apres_midi',
            'mardi_debut_matin','mardi_fin_matin','mardi_debut_apres_midi','mardi_fin_apres_midi',
            'mercredi_debut_matin','mercredi_fin_matin','mercredi_debut_apres_midi','mercredi_fin_apres_midi',
            'jeudi_debut_matin','jeudi_fin_matin','jeudi_debut_apres_midi','jeudi_fin_apres_midi',
            'vendredi_debut_matin','vendredi_fin_matin','vendredi_debut_apres_midi','vendredi_fin_apres_midi',
        ];

        // TODO : Prendre la valeur de l'état Absent depuis la BD
        // Ajoute des valeurs par défaut si un champ n'est pas renseigné
        foreach ($form_fields_data as $field)
        {
            // Si le champ est vide ou indéfini
            if (!isset($_POST[$field]) || empty($_POST[$field]))
            {
                // Définit la valeur à "Absent"
                $_POST[$field] = 3;
            }
        }

        // Prépare les présences à enregistrer
        $data = [

            'id_presence' => $id_presence,

            'fk_user_id' => $user_id,

            'presences_lundi_m1' => $_POST['lundi_debut_matin'],
            'presences_lundi_m2' => $_POST['lundi_fin_matin'],
            'presences_lundi_a1' => $_POST['lundi_debut_apres_midi'],
            'presences_lundi_a2' => $_POST['lundi_fin_apres_midi'],

            'presences_mardi_m1' => $_POST['mardi_debut_matin'],
            'presences_mardi_m2' => $_POST['mardi_fin_matin'],
            'presences_mardi_a1' => $_POST['mardi_debut_apres_midi'],
            'presences_mardi_a2' => $_POST['mardi_fin_apres_midi'],

            'presences_mercredi_m1' => $_POST['mercredi_debut_matin'],
            'presences_mercredi_m2' => $_POST['mercredi_fin_matin'],
            'presences_mercredi_a1' => $_POST['mercredi_debut_apres_midi'],
            'presences_mercredi_a2' => $_POST['mercredi_fin_apres_midi'],

            'presences_jeudi_m1' => $_POST['jeudi_debut_matin'],
            'presences_jeudi_m2' => $_POST['jeudi_fin_matin'],
            'presences_jeudi_a1' => $_POST['jeudi_debut_apres_midi'],
            'presences_jeudi_a2' => $_POST['jeudi_fin_apres_midi'],

            'presences_vendredi_m1' => $_POST['vendredi_debut_matin'],
            'presences_vendredi_m2' => $_POST['vendredi_fin_matin'],
            'presences_vendredi_a1' => $_POST['vendredi_debut_apres_midi'],
            'presences_vendredi_a2' => $_POST['vendredi_fin_apres_midi']
        ];

        // Effectue l'insertion ou la modification des présences dans la base de données
        $this->presence_model->save($data);

        // Sélectionne les présences de l'utilisateur
        $presences_data = $this->presence_model->getPresencesUser($user_id);

        // Ajouter les présences à la variable $data
        $data = $presences_data;

        // Afficher un message de succès à l'utilisateur
        $data['success'] = "Présences modifiées avec succès";

        // Réffiche la page des présences
        $this->display_view('Helpdesk\presence', $data);
    }

    // Affiche la page ajouter_technicien | Sauvegarde les données du formulaire de la page ajout_technicien
    function ajouterTechnicien()
    {
        // Vérifie si l'utilisateur est connecté
        $this->isUserLogged();

        // Titre de la page
        $data['title'] = "Ajouter un technicien";

        // Récolte tous les utilisateurs de la base de données
        $data['users'] = $this->user_data_model->getUsersData();

        // Tableau pour identifier les présences dans la page suivante
        $data['presences'] = 
        [
            'lundi_debut_matin','lundi_fin_matin','lundi_debut_apres_midi','lundi_fin_apres_midi',
            'mardi_debut_matin','mardi_fin_matin','mardi_debut_apres_midi','mardi_fin_apres_midi',
            'mercredi_debut_matin','mercredi_fin_matin','mercredi_debut_apres_midi','mercredi_fin_apres_midi',
            'jeudi_debut_matin','jeudi_fin_matin','jeudi_debut_apres_midi','jeudi_fin_apres_midi',
            'vendredi_debut_matin','vendredi_fin_matin','vendredi_debut_apres_midi','vendredi_fin_apres_midi',
        ];

        // Si l'on clique sur le bouton "Ajouter un technicien" deupis le planning
        if (empty($_POST))
        {
            // Affiche la page d'ajout de technicien
            return $this->display_view('Helpdesk\ajouter_technicien', $data);
        }

        // Récupère l'ID de l'utilisateur rensigné dans le champ "technicien"
        $user_id = $_POST['technicien'];
        
        // Vérifie si l'utilisateur possède déjà un planning
        $data['error'] = $this->planning_model->checkUserOwnsPlanning($user_id);
        
        // Si $data['error'] n'est pas vide, cela veut dire que l'utilisateur possède déjà un planning
        if (!empty($data['error']))
        {
            // Réaffiche la page d'ajout de technicien, avec le message d'erreur
            return $this->display_view('Helpdesk\ajouter_technicien', $data);
        }

        // Tableau des champs du formulaire
        $form_fields_data = [
            'lundi_debut_matin','lundi_fin_matin','lundi_debut_apres_midi','lundi_fin_apres_midi',
            'mardi_debut_matin','mardi_fin_matin','mardi_debut_apres_midi','mardi_fin_apres_midi',
            'mercredi_debut_matin','mercredi_fin_matin','mercredi_debut_apres_midi','mercredi_fin_apres_midi',
            'jeudi_debut_matin','jeudi_fin_matin','jeudi_debut_apres_midi','jeudi_fin_apres_midi',
            'vendredi_debut_matin','vendredi_fin_matin','vendredi_debut_apres_midi','vendredi_fin_apres_midi',
        ];

        // Variable pour calculer le nombre de champs vides
        $emptyFields = 0;

        // Ajoute des valeurs par défaut si un champ n'est pas renseigné
        foreach ($form_fields_data as $field)
        {
            // Si le champ est vide ou indéfini
            if (!isset($_POST[$field]) || empty($_POST[$field]))
            {
                // Définit la valeur à NULL
                $_POST[$field] = NULL;

                // Incréemente la variable
                $emptyFields++;
            }
        }

        // Si il y a 20 champs vides, signifie que tous les champs sont vides. N'autoirise pas l'insertion d'un technicien sans présences
        if ($emptyFields === 20)
        {
            // Message d'erreur
            $data['error'] = 'Le technicien doit être assigné au minimum à un rôle pendant une période';

            // Réaffiche la page d'ajout de technicien, avec le message d'erreur
            return $this->display_view('Helpdesk\ajouter_technicien', $data);     
        }

        // Prépare le planning à enregistrer
        $data = 
        [
            'fk_user_id' => $user_id,

            'planning_lundi_m1' => $_POST['lundi_debut_matin'],
            'planning_lundi_m2' => $_POST['lundi_fin_matin'],
            'planning_lundi_a1' => $_POST['lundi_debut_apres_midi'],
            'planning_lundi_a2' => $_POST['lundi_fin_apres_midi'],

            'planning_mardi_m1' => $_POST['mardi_debut_matin'],
            'planning_mardi_m2' => $_POST['mardi_fin_matin'],
            'planning_mardi_a1' => $_POST['mardi_debut_apres_midi'],
            'planning_mardi_a2' => $_POST['mardi_fin_apres_midi'],

            'planning_mercredi_m1' => $_POST['mercredi_debut_matin'],
            'planning_mercredi_m2' => $_POST['mercredi_fin_matin'],
            'planning_mercredi_a1' => $_POST['mercredi_debut_apres_midi'],
            'planning_mercredi_a2' => $_POST['mercredi_fin_apres_midi'],

            'planning_jeudi_m1' => $_POST['jeudi_debut_matin'],
            'planning_jeudi_m2' => $_POST['jeudi_fin_matin'],
            'planning_jeudi_a1' => $_POST['jeudi_debut_apres_midi'],
            'planning_jeudi_a2' => $_POST['jeudi_fin_apres_midi'],

            'planning_vendredi_m1' => $_POST['vendredi_debut_matin'],
            'planning_vendredi_m2' => $_POST['vendredi_fin_matin'],
            'planning_vendredi_a1' => $_POST['vendredi_debut_apres_midi'],
            'planning_vendredi_a2' => $_POST['vendredi_fin_apres_midi']
        ];

        // Insère les données dans la table "tbl_planning"
        $this->planning_model->insert($data);

        // Afficher un message de succès à l'utilisateur
        $data['success'] = "Technicien ajouté au planning avec succès";

        /*
        ** Prise d'infos de la fonction index()
        ** (Répétition pour ajouter le message de succès à $data)
        */
        
        // Titre de la page
        $data['title'] = "Helpdesk";

        // Récupère les données des utilisateurs ayant un planning attribué
        $planning_data = $this->planning_model->getPlanningDataByUser();

        // Ajoute le planning à la variable $data
        $data['planning_data'] = $planning_data;

        // Tableau pour les présences
        $data['periodes'] = 
        [
            'planning_lundi_m1', 'planning_lundi_m2', 'planning_lundi_a1', 'planning_lundi_a2',
            'planning_mardi_m1', 'planning_mardi_m2', 'planning_mardi_a1', 'planning_mardi_a2',
            'planning_mercredi_m1', 'planning_mercredi_m2', 'planning_mercredi_a1', 'planning_mercredi_a2',
            'planning_jeudi_m1', 'planning_jeudi_m2', 'planning_jeudi_a1', 'planning_jeudi_a2',
            'planning_vendredi_m1', 'planning_vendredi_m2', 'planning_vendredi_a1', 'planning_vendredi_a2',
        ];

        // Affiche la page du planning
        $this->display_view('Helpdesk\planning', $data);
    }

    // Affiche la page modification_planning | Sauvegarde les données du formulaire de la page modification_planning
    function modificationPlanning()
    {
        // Vérifie si l'utilisateur est connecté
        $this->isUserLogged();

        // Récupère les données du planning
        $planning_data = $this->planning_model->getPlanningData();

        // Vérifie si des données ont été soumises via le formulaire
        if ($_POST) {

            // Récupère les données soumises
            $newData = [
                'planning_lundi_m1' => $_POST['planning_lundi_m1'],
                'planning_lundi_m2' => $_POST['planning_lundi_m2'],
                'planning_lundi_a1' => $_POST['planning_lundi_a1'],
                'planning_lundi_a2' => $_POST['planning_lundi_a2'],

                'planning_mardi_m1' => $_POST['planning_mardi_m1'],
                'planning_mardi_m2' => $_POST['planning_mardi_m2'],
                'planning_mardi_a1' => $_POST['planning_mardi_a1'],
                'planning_mardi_a2' => $_POST['planning_mardi_a2'],

                'planning_mercredi_m1' => $_POST['planning_mercredi_m1'],
                'planning_mercredi_m2' => $_POST['planning_mercredi_m2'],
                'planning_mercredi_a1' => $_POST['planning_mercredi_a1'],
                'planning_mercredi_a2' => $_POST['planning_mercredi_a2'],

                'planning_jeudi_m1' => $_POST['planning_jeudi_m1'],
                'planning_jeudi_m2' => $_POST['planning_jeudi_m2'],
                'planning_jeudi_a1' => $_POST['planning_jeudi_a1'],
                'planning_jeudi_a2' => $_POST['planning_jeudi_a2'],

                'planning_vendredi_m1' => $_POST['planning_vendredi_m1'],
                'planning_vendredi_m2' => $_POST['planning_vendredi_m2'],
                'planning_vendredi_a1' => $_POST['planning_vendredi_a1'],
                'planning_vendredi_a2' => $_POST['planning_vendredi_a2']
            ];


            // Met à jour les enregistrements dans la base de données
            $this->planning_model->updatePlanningData($newData);
        }

        // Ajoute le planning à la variable $data
        $data['planning_data'] = $planning_data;

        $this->display_view('Helpdesk\modification_planning', $data);
    }
}
