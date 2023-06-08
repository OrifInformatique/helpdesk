<?php

namespace Helpdesk\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Home extends BaseController
{
    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();

        helper('form');
    }

	public function index()
	{
		$data['title'] = "Helpdesk";

		$this->display_view('Helpdesk\helpdesk_message', $data);
	}

    public function presence()
    {
        if(isset($_SESSION['user_id'])) {
            $data['title'] = "Presence Apprentis";

            $this->display_view('Helpdesk\presence', $data);


        }

        else {
            return redirect()->to('user/auth/login');
        }
    }

    public function savePresence()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $fk_user_id = $_POST['fk_user_id'];
            $fk_week_day_id = $_POST['fk_week_day_id'];
            $fk_shift_type_id = $_POST['fk_shift_type_id'];
            $fk_presence_type_id = $_POST['fk_presence_type_id'];
        
            // Insertion des données dans la table "presence"
            $sql = "INSERT INTO presence (fk_user_id, fk_week_day_id, fk_shift_type_id, fk_presence_type_id)
                    VALUES ('$fk_user_id', '$fk_week_day_id', '$fk_shift_type_id', '$fk_presence_type_id')";
        
        }
    }


        
}
