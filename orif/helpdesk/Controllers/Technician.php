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
use Helpdesk\Models\Roles_model;

class Technician extends Home
{
    protected $roles_model;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Load required roles_model
        $this->roles_model = new Roles_model();
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
        
        // Get role from tbl_roles via Roles_model
        $role = '';
        $role_data = null;
        
        // Use Roles_model directly if fk_role_id is available
        if (isset($user['fk_role_id']) && !empty($user['fk_role_id'])) {
            $role_data = $this->roles_model->getRoleByID($user['fk_role_id']);
            if ($role_data !== null && !empty($role_data)) {
                $role = $role_data['name_role'] ?? '';
            }
        }
        
        // If no role is found via Roles_model, use getUserRole as fallback
        if (empty($role)) {
            $user_role = $this->user_data_model->getUserRole($user_id);
            if ($user_role !== null && !empty($user_role)) {
                $role = $user_role['name_role'] ?? '';
                $role_data = $user_role;
            }
        }
        
        // Fallback to old system if no role is defined
        if (empty($role)) {
            switch($user['fk_user_type'])
            {
                case 1:
                    $role = lang('Technician.role_admin');
                    break;

                case 2:
                    $role = lang('Technician.role_user');
                    break;

                case 3:
                    $role = lang('Technician.role_guest');
                    break;

                case 4:
                    $role = lang('Technician.role_mentor');
                    break;

                default:
                    $role = lang('MiscTexts.role_unknown');
            }
        }

        // Get presence ID securely
        $presence_record = $this->presences_model->getPresenceId($user_id);
        $id_presence = null;
        if ($presence_record !== null) {
            if (is_object($presence_record)) {
                $id_presence = $presence_record->id_presence ?? null;
            } elseif (is_array($presence_record)) {
                $id_presence = $presence_record['id_presence'] ?? null;
            }
        }

        $data =
        [
            'user'                  => $user,
            'role'                  => $role,
            'role_data'             => $role_data,
            'is_user_logged_admin'     => $this->isAdmin(),
            'has_cw_planning_entry' => $this->planning_model->getPlanning($user_id) ? true : false,
            'has_nw_planning_entry' => $this->nw_planning_model->getNwPlanning($user_id) ? true : false,
            'id_presence'           => $id_presence,
            'title'                 => lang('Titles.technician_menu')
        ];

        return $this->display_view('Helpdesk\dashboard', $data);
    }
}