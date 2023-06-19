<?php

namespace Helpdesk\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Helpdesk\Models\Presence_model;

class Home extends BaseController
{

    protected $session;

    protected $presence_model;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
        $this->presence_model = new Presence_model();

        helper('form');
    }

    public function index()
    {
        $data['title'] = "Helpdesk";

        $this->display_view('Helpdesk\helpdesk_message', $data);
    }

    public function presence()
    {
        if (isset($_SESSION['user_id'])) {
            $data['title'] = "Presence Apprentie";

            // Récupérer les présences déjà enregistrées
            $user_id = $_SESSION['user_id'];
            $savedData = $this->presence_model->getByUserId($user_id);

            // Ajouter les présences à la variable $data
            $data['savedData'] = $savedData;

            $this->display_view('Helpdesk\presence', $data);
        } else {
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

            // Vérifie si les informations existent déjà dans la base de données
            //$presenceExists = $this->presence_model->checkPresenceExistence($user_id);

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

            /*
            if ($presenceExists) {

                $this->presence_model->save($data);
            } else {
                $this->presence_model->save($data);
            }
            */

            // Effectue l'insertion ou la modification dans la base de données
            $this->presence_model->save($data);

            // Affiche la page du planning
            $this->display_view('Helpdesk\presence', $data);
        }
    }
}
