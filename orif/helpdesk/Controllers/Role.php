<?php

/**
 * Controller for role management
 * 
 * @author      Orif (WaAd)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

namespace Helpdesk\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Helpdesk\Models\Roles_model;
use CodeIgniter\HTTP\Response;

class Role extends BaseController
{
    protected $roles_model;
    protected $validation;

    /**
     * Constructor
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Set Access level before calling parent constructor
        // Accessibility reserved to admin users
        $this->access_level = config('Config\UserConfig')->access_lvl_admin;
        parent::initController($request, $response, $logger);

        // Load required helpers
        helper('form');

        // Load required services
        $this->validation = \Config\Services::validation();

        // Load required models
        $this->roles_model = new Roles_model();
    }

    /**
     * Default function, displays the list of roles
     * 
     * @return view
     * 
     */
    public function index()
    {
        return $this->listRole();
    }

    /**
     * Displays the list of roles
     *
     * @return string
     */
    public function listRole(): string
    {
        $roles = $this->roles_model->getRoles();

        $data = array(
            'title' => lang('role_lang.title_list_role') ?? 'Liste des rôles',
            'roles' => $roles
        );

        return $this->display_view('\Helpdesk\list_role', $data);
    }

    /**
     * Adds or modify a role
     *
     * @param integer $role_id The id of the role to modify, leave blank to create a new one
     * @return string|Response
     */
    public function saveRole(?int $role_id = 0): string|Response
    {
        $errors = [];
        $old_data = [];

        if (count($_POST) > 0) {
            $role_id = $this->request->getPost('id_role') ?: $role_id;
            
            // Store old data for form re-display
            $old_data = [
                'name_role' => $this->request->getPost('name_role'),
                'priority_role' => $this->request->getPost('priority_role'),
                'min_assignation_first_technician_role' => $this->request->getPost('min_assignation_first_technician_role'),
                'max_assignation_first_technician_role' => $this->request->getPost('max_assignation_first_technician_role'),
                'min_assignation_second_technician_role' => $this->request->getPost('min_assignation_second_technician_role'),
                'max_assignation_second_technician_role' => $this->request->getPost('max_assignation_second_technician_role'),
                'min_assignation_third_technician_role' => $this->request->getPost('min_assignation_third_technician_role'),
                'max_assignation_third_technician_role' => $this->request->getPost('max_assignation_third_technician_role')
            ];

            // Prepare role data
            $role_data = array(
                'id_role' => $role_id ?: null,
                'name_role' => $this->request->getPost('name_role'),
                'priority_role' => intval($this->request->getPost('priority_role')),
                'min_assignation_first_technician_role' => intval($this->request->getPost('min_assignation_first_technician_role')),
                'max_assignation_first_technician_role' => intval($this->request->getPost('max_assignation_first_technician_role')),
                'min_assignation_second_technician_role' => intval($this->request->getPost('min_assignation_second_technician_role')),
                'max_assignation_second_technician_role' => intval($this->request->getPost('max_assignation_second_technician_role')),
                'min_assignation_third_technician_role' => intval($this->request->getPost('min_assignation_third_technician_role')),
                'max_assignation_third_technician_role' => intval($this->request->getPost('max_assignation_third_technician_role'))
            );

            // Validation rules
            $validation_rules = [
                'name_role' => [
                    'label' => lang('role_lang.field_name_role') ?? 'Nom du rôle',
                    'rules' => 'required|min_length[1]|max_length[255]'
                ],
                'priority_role' => [
                    'label' => lang('role_lang.field_priority_role') ?? 'Priorité',
                    'rules' => 'required|integer|greater_than[0]'
                ],
                'min_assignation_first_technician_role' => [
                    'label' => lang('role_lang.field_min_assignation_first_technician_role') ?? 'Min assignation 1er technicien',
                    'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
                ],
                'max_assignation_first_technician_role' => [
                    'label' => lang('role_lang.field_max_assignation_first_technician_role') ?? 'Max assignation 1er technicien',
                    'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
                ],
                'min_assignation_second_technician_role' => [
                    'label' => lang('role_lang.field_min_assignation_second_technician_role') ?? 'Min assignation 2ème technicien',
                    'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
                ],
                'max_assignation_second_technician_role' => [
                    'label' => lang('role_lang.field_max_assignation_second_technician_role') ?? 'Max assignation 2ème technicien',
                    'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
                ],
                'min_assignation_third_technician_role' => [
                    'label' => lang('role_lang.field_min_assignation_third_technician_role') ?? 'Min assignation 3ème technicien',
                    'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
                ],
                'max_assignation_third_technician_role' => [
                    'label' => lang('role_lang.field_max_assignation_third_technician_role') ?? 'Max assignation 3ème technicien',
                    'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
                ]
            ];

            $this->validation->setRules($validation_rules);

            if (!$this->validation->run($role_data)) {
                $errors = $this->validation->getErrors();
            } else {
                // Save role
                if ($role_id == 0) {
                    // Create new role
                    $this->roles_model->insert($role_data);
                    $role_id = $this->roles_model->insertID();
                } else {
                    // Update existing role
                    $this->roles_model->update($role_id, $role_data);
                }

                // Check for errors
                if ($this->roles_model->errors() == null) {
                    return redirect()->to('/helpdesk/role/listRole');
                } else {
                    $errors = $this->roles_model->errors();
                }
            }
        }

        // Get role data for form
        $role = null;
        if ($role_id > 0) {
            $role = $this->roles_model->getRoleByID($role_id);
            if (is_null($role)) {
                return redirect()->to('/helpdesk/role/listRole');
            }
        }

        // Prepare data for view
        $data = array(
            'title' => lang('role_lang.title_role_' . (($role_id > 0) ? 'update' : 'new')) ?? (($role_id > 0) ? 'Modifier le rôle' : 'Nouveau rôle'),
            'role' => $role,
            'old_data' => $old_data,
            'errors' => $errors
        );

        return $this->display_view('\Helpdesk\form_role', $data);
    }

    /**
     * Delete or deactivate a role depending on $action
     *
     * @param integer $role_id ID of the role to affect
     * @param integer $action Action to apply on the role:
     *  - 0 for displaying the confirmation
     *  - 1 for deleting (hard delete)
     * @return string|Response
     */
    public function deleteRole(int $role_id, ?int $action = 0): string|Response
    {
        $role = $this->roles_model->getRoleByID($role_id);
        if (is_null($role)) {
            return redirect()->to('/helpdesk/role/listRole');
        }

        switch($action) {
            case 0: // Display confirmation
                $data = array(
                    'role' => $role,
                    'title' => lang('role_lang.title_role_delete') ?? 'Supprimer le rôle'
                );
                return $this->display_view('\Helpdesk\delete_role', $data);
                break;
            case 1: // Delete role
                $this->roles_model->delete($role_id, TRUE);
                return redirect()->to('/helpdesk/role/listRole');
            default: // Do nothing
                return redirect()->to('/helpdesk/role/listRole');
        }
    }
}

