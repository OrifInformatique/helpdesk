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
     * Displays the dashboard of a specific technician.
     * 
     * @param int $user_id
     * 
     * @return view
     * 
     */
    public function dashboard($user_id)
    {
        $this->isUserLogged();

        $user = $this->user_data_model->getUserData($user_id)[0];
        $role = '';

        switch($user['fk_user_type'])
        {
            case 1:
                $role = lang('Technician.role_guest');
                break;

            case 2:
                $role = lang('Technician.role_user');
                break;

            case 3:
                $role = lang('Technician.role_admin');
                break;

            case 4:
                $role = lang('Technician.role_mentor');
                break;

            default:
                $role = lang('MiscTexts.role_unknown');
        }

        $data =
        [
            'user'              => $user,
            'role'              => $role,
            'isUserLoggedAdmin' => $this->isAdmin($_SESSION['user_access']),
            'id_presence'       => $this->presences_model->getPresenceId($user_id)['id_presence'] ?? null,
            'title'             => lang('Titles.technician_menu')
        ];

        return $this->display_view('Helpdesk\dashboard', $data);
    }
}