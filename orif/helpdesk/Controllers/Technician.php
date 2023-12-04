<?php

/**
 * Controller for technician (dashboard)
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

namespace Helpdesk\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use Helpdesk\Controllers\Home;

class Technician extends Home
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }


    /**
     * Default function, displays the terminal.
     * 
     * @return view
     * 
     */
    public function index()
    {
        $this->setSessionVariables();

        return redirect()->to('/helpdesk/home/index');
    }


    /** ********************************************************************************************************************************* */


    /**
     * [DOES NOT HAVE AN ACTUAL USE FOR NOW] Displays the dashboard of a specific technician.
     * 
     * @param int $user_id ID of a user
     * 
     * @return view
     * 
     */
    public function dashboard($user_id)
    {
        $this->isUserLogged();

        $data =
        [
            'user'  => $this->user_data_model->getUserData($user_id),
            'title' => lang('Helpdesk.ttl_technician_menu')
        ];

        return $this->display_view('Helpdesk\dashboard', $data);
    }
}