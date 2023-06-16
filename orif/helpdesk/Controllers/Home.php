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
        if (
            isset($_POST['lundi_debut_matin'], $_POST['lundi_fin_matin'], $_POST['lundi_debut_apres-midi'], $_POST['lundi_fin_apres-midi']) &&
            isset($_POST['mardi_debut_matin'], $_POST['mardi_fin_matin'], $_POST['mardi_debut_apres-midi'], $_POST['mardi_fin_apres-midi']) &&
            isset($_POST['mercredi_debut_matin'], $_POST['mercredi_fin_matin'], $_POST['mercredi_debut_apres-midi'], $_POST['mercredi_fin_apres-midi']) &&
            isset($_POST['jeudi_debut_matin'], $_POST['jeudi_fin_matin'], $_POST['jeudi_debut_apres-midi'], $_POST['jeudi_fin_apres-midi']) &&
            isset($_POST['vendredi_debut_matin'], $_POST['vendredi_fin_matin'], $_POST['vendredi_debut_apres-midi'], $_POST['vendredi_fin_apres-midi'])
        ) {
            $user_id = $_SESSION['user_id'];

            // Vérifier si les informations existent déjà dans la base de données
            $presenceExists = $this->presence_model->checkPresenceExistence($user_id);

            $data = [
                'fk_user_id' => $user_id,

                'fk_lundi_m1' => $_POST['lundi_debut_matin'],
                'fk_lundi_m2' => $_POST['lundi_fin_matin'],
                'fk_lundi_a1' => $_POST['lundi_debut_apres-midi'],
                'fk_lundi_a2' => $_POST['lundi_fin_apres-midi'],

                'fk_mardi_m1' => $_POST['mardi_debut_matin'],
                'fk_mardi_m2' => $_POST['mardi_fin_matin'],
                'fk_mardi_a1' => $_POST['mardi_debut_apres-midi'],
                'fk_mardi_a2' => $_POST['mardi_fin_apres-midi'],

                'fk_mercredI_m1' => $_POST['mercredi_debut_matin'],
                'fk_mercredI_m2' => $_POST['mercredi_fin_matin'],
                'fk_mercredI_a1' => $_POST['mercredi_debut_apres-midi'],
                'fk_mercredI_a2' => $_POST['mercredi_fin_apres-midi'],

                'fk_jeudi_m1' => $_POST['jeudi_debut_matin'],
                'fk_jeudi_m2' => $_POST['jeudi_fin_matin'],
                'fk_jeudi_a1' => $_POST['jeudi_debut_apres-midi'],
                'fk_jeudi_a2' => $_POST['jeudi_fin_apres-midi'],

                'fk_vendredi_m1' => $_POST['vendredi_debut_matin'],
                'fk_vendredi_m2' => $_POST['vendredi_fin_matin'],
                'fk_vendredi_a1' => $_POST['vendredi_debut_apres-midi'],
                'fk_vendredi_a2' => $_POST['vendredi_fin_apres-midi']
            ];

            if ($presenceExists) {
                $this->presence_model->update(
                    [
                        'fk_lundi_m1' => $data['fk_lundi_m1'],
                        'fk_lundi_m2' => $data['fk_lundi_m2'],
                        'fk_lundi_a1' => $data['fk_lundi_a1'],
                        'fk_lundi_a2' => $data['fk_lundi_a2'],

                        'fk_mardi_m1' => $data['fk_mardi_m1'],
                        'fk_mardi_m2' => $data['fk_mardi_m2'],
                        'fk_mardi_a1' => $data['fk_mardi_a1'],
                        'fk_mardi_a2' => $data['fk_mardi_a2'],

                        'fk_mercredI_m1' => $data['fk_mercredI_m1'],
                        'fk_mercredI_m2' => $data['fk_mercredI_m2'],
                        'fk_mercredI_a1' => $data['fk_mercredI_a1'],
                        'fk_mercredI_a2' => $data['fk_mercredI_a2'],

                        'fk_jeudi_m1' => $data['fk_jeudi_m1'],
                        'fk_jeudi_m2' => $data['fk_jeudi_m2'],
                        'fk_jeudi_a1' => $data['fk_jeudi_a1'],
                        'fk_jeudi_a2' => $data['fk_jeudi_a2'],
                        
                        'fk_vendredi_m1' => $data['fk_vendredi_m1'],
                        'fk_vendredi_m2' => $data['fk_vendredi_m2'],
                        'fk_vendredi_a1' => $data['fk_vendredi_a1'],
                        'fk_vendredi_a2' => $data['fk_vendredi_a2']
                    ],
                    $user_id
                );
            } else {
                $this->presence_model->save($data);
            }

            $this->display_view('Helpdesk\presence', $data);
        } else {
            dd('dgvdvd');
        }
    }
}
