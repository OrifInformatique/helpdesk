<?php

namespace Helpdesk\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Helpdesk\Models\Presence_model;
use Helpdesk\Models\Planning_model;

class Home extends BaseController
{

    protected $session;

    protected $presence_model;

    protected $planning_model;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
        $this->presence_model = new Presence_model();
        $this->planning_model = new Planning_model();

        helper('form');
    }

    public function index()
    {
        $data['title'] = "Helpdesk";

        // Récupère les données du planning
        $planning_data = $this->planning_model->getPlanningData();

        // Ajoute le planning à la variable $data
        $data['planning_data'] = $planning_data;

        // Affiche la page du planning
        $this->display_view('Helpdesk\planning', $data);
    }

    public function presence()
    {
        // Si l'utilisateur est connecté
        if (isset($_SESSION['user_id'])) {

            $data = [
                'title' => "Présences de l'apprenti"
            ];

            // Récupére les présences déjà enregistrées
            $user_id = $_SESSION['user_id'];

            // Récupère les présences de l'utilisateur
            $presences_data = $this->presence_model->getPresencesUser($user_id);

            // Ajouter les présences à la variable $data
            $data = $presences_data;

            // Affiche la page du formulaire des presences
            $this->display_view('Helpdesk\presence', $data);
        }

        // Sinon, redirige vers la page de connexion
        else {
            return redirect()->to('user/auth/login');
        }
    }

    function savePresence()
    {
        // Si au moins un champ est vide, réaffiche le formulaire en stockant les données déjà insérées
        if (count($_POST) != 20) {

            // Initialisation de variables
            $incomplete_form_data = [
                'error_message' => 'Toutes les présences doivent être renseignées.'
            ];

            // Boucle parcourant chaque entrée du formulaire
            foreach ($_POST as $key => $value) {
                // Ajoute l'entrée et sa valeur dans le tableau $incomplete_form_data
                $incomplete_form_data[$key] = $value;
            }

            // Réaffiche le formulaire avec les données des champs déjà renseignés
            $this->display_view('Helpdesk\presence', $incomplete_form_data);
        }

        // Si tous les champs du formulaire sont remplis
        else {

            // Récupére l'ID de l'utilisateur depuis la session
            $user_id = $_SESSION['user_id'];

            // Récupére l'ID de la présence depuis la table "presence"
            $id_presence = $this->presence_model->getPresenceId($user_id);

            // Prépare les données à enregistrer
            $data = [

                'id' => $id_presence,

                'fk_user_id' => $user_id,

                'fk_lundi_m1' => $_POST['lundi_debut_matin'],
                'fk_lundi_m2' => $_POST['lundi_fin_matin'],
                'fk_lundi_a1' => $_POST['lundi_debut_apres_midi'],
                'fk_lundi_a2' => $_POST['lundi_fin_apres_midi'],

                'fk_mardi_m1' => $_POST['mardi_debut_matin'],
                'fk_mardi_m2' => $_POST['mardi_fin_matin'],
                'fk_mardi_a1' => $_POST['mardi_debut_apres_midi'],
                'fk_mardi_a2' => $_POST['mardi_fin_apres_midi'],

                'fk_mercredI_m1' => $_POST['mercredi_debut_matin'],
                'fk_mercredI_m2' => $_POST['mercredi_fin_matin'],
                'fk_mercredI_a1' => $_POST['mercredi_debut_apres_midi'],
                'fk_mercredI_a2' => $_POST['mercredi_fin_apres_midi'],

                'fk_jeudi_m1' => $_POST['jeudi_debut_matin'],
                'fk_jeudi_m2' => $_POST['jeudi_fin_matin'],
                'fk_jeudi_a1' => $_POST['jeudi_debut_apres_midi'],
                'fk_jeudi_a2' => $_POST['jeudi_fin_apres_midi'],

                'fk_vendredi_m1' => $_POST['vendredi_debut_matin'],
                'fk_vendredi_m2' => $_POST['vendredi_fin_matin'],
                'fk_vendredi_a1' => $_POST['vendredi_debut_apres_midi'],
                'fk_vendredi_a2' => $_POST['vendredi_fin_apres_midi']
            ];

            // Effectue l'insertion ou la modification dans la base de données
            $this->presence_model->save($data);

            // Sélectionne les présences de l'utilisateur
            $presences_data = $this->presence_model->getPresencesUser($user_id);

            // Ajouter les présences à la variable $data
            $data = $presences_data;

            // Affiche la page du planning
            $this->display_view('Helpdesk\presence', $data);
        }
    }

    function ajouter_technicien()
    {
        // Si l'utilisateur est connecté
        if (isset($_SESSION['user_id'])) {

            $this->display_view('Helpdesk\ajouter_technicien');
        }

        // Sinon, redirige vers la page de connexion
        else {
            return redirect()->to('user/auth/login');
        }
    }

    function modification_planning()
    {
        // Si l'utilisateur est connecté
        if (isset($_SESSION['user_id'])) {

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

        // Sinon, redirige vers la page de connexion
        else {
            return redirect()->to('user/auth/login');
        }
    }
}
