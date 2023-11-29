<?php

/**
 * Controller for plannings
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
use Helpdesk\Models\lw_planning_model;
use Helpdesk\Models\planning_model;
use Helpdesk\Models\nw_planning_model;
use Helpdesk\Models\user_data_model;

class Planning extends Home
{
    protected $lw_planning_model;
    protected $planning_model;
    protected $nw_planning_model;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->lw_planning_model = new lw_planning_model();
        $this->planning_model = new planning_model();
        $this->nw_planning_model = new nw_planning_model();
        $this->user_data_model = new user_data_model();
    }

    
    /**
     * Displays the last week planning.
     * 
     * @return view
     * 
     */
    public function lw_planning()
    {
        // -1 stands for last week
        $periods = $this->choosePeriods(-1);

        $data = 
        [
            'lw_planning_data' => $this->lw_planning_model->getPlanningDataByUser(),
            'classes'          => $this->defineDaysOff($periods),
            'title'            => lang('Helpdesk.ttl_lw_planning'),
            'lw_periods'       => // SQL names of last week's planning periods
            [
                'lw_planning_mon_m1', 'lw_planning_mon_m2', 'lw_planning_mon_a1', 'lw_planning_mon_a2',
                'lw_planning_tue_m1', 'lw_planning_tue_m2', 'lw_planning_tue_a1', 'lw_planning_tue_a2',
                'lw_planning_wed_m1', 'lw_planning_wed_m2', 'lw_planning_wed_a1', 'lw_planning_wed_a2',
                'lw_planning_thr_m1', 'lw_planning_thr_m2', 'lw_planning_thr_a1', 'lw_planning_thr_a2',
                'lw_planning_fri_m1', 'lw_planning_fri_m2', 'lw_planning_fri_a1', 'lw_planning_fri_a2',
            ]
        ];

        return $this->display_view('Helpdesk\lw_planning', $data);
    }
    
    
    /**
     * Displays the current week planning.
     * 
     * @return view
     * 
     */
    public function cw_planning()
    {
        // 0 stands for current week
        $periods = $this->choosePeriods(0);

        $data =
        [
            'messages'      => $this->getFlashdataMessages(),
            'planning_data' => $this->planning_model->getPlanningDataByUser(),
            'classes'       => $this->defineDaysOff($periods),
            'title'         => lang('Helpdesk.ttl_planning')
        ];

        return $this->display_view('Helpdesk\planning', $data);
    }


    /**
     * Displays the next week planning.
     * 
     * @return view
     * 
     */
    public function nw_planning()
    {
        // 1 stands for next week
        $periods = $this->choosePeriods(1);

        $data = 
        [
            'messages'         => $this->getFlashdataMessages(),
            'nw_planning_data' => $this->nw_planning_model->getNwPlanningDataByUser(),
            'classes'          => $this->defineDaysOff($periods),
            'title'            => lang('Helpdesk.ttl_nw_planning')
        ];

        return $this->display_view('Helpdesk\nw_planning', $data);
    }


    /** ********************************************************************************************************************************* */


    /**
     * Displays the page for adding technicians in planning, and manages the post of the data.
     * 
     * @param int $planning_type ID of the edited planning
     * 
     * @return view
     * 
     */
    function add_technician($planning_type)
    {
        $this->isUserLogged();

        $this->isSetPlanningType($planning_type);

        $periods = $this->choosePeriods($planning_type);

        $data = 
        [
            'planning_type' => $planning_type,
            'classes'       => $this->defineDaysOff($periods),
            'users'         => $this->user_data_model->getUsersData(),
            'title'         => lang('Helpdesk.ttl_add_technician')
        ];

        if($_SERVER["REQUEST_METHOD"] != "POST")
        {
            return $this->display_view('Helpdesk\add_technician', $data);
        }

        $validation = \Config\Services::validation();
        $validation->setRule('technician', '', 'is_natural_no_zero|not_in_planning['.$planning_type.']', 
        ['is_natural_no_zero' => lang('helpdesk.is_nautral_no_zero')]);
        
        if(!$validation->run($_POST))
        {
            $data['messages']['error'] = $validation->getError('technician');
            
            return $this->display_view('Helpdesk\add_technician', $data);
        }

        $form_fields = [];
        $empty_fields = 0;

        switch ($planning_type)
        {
            case 0:
                $form_fields = $_SESSION['helpdesk']['cw_periods'];
                break;

            case 1:
                $form_fields = $_SESSION['helpdesk']['nw_periods'];
                break;
        }

        foreach ($form_fields as $field)
        {
            if (!isset($_POST[$field]) || empty($_POST[$field]) || !in_array($_POST[$field], [1, 2, 3]))
            {
                $_POST[$field] = NULL;
                $empty_fields++;
            }
        }

        if($empty_fields === 20) 
        {
            $data['messages']['error'] = lang('Helpdesk.err_technician_must_be_assigned_to_schedule');

            return $this->display_view('Helpdesk\add_technician', $data);
        }

        $this->session->setFlashdata('success', lang('Helpdesk.scs_technician_added_to_schedule'));

        // 0 is current week, 1 is next week
        switch($planning_type) 
        {
            case 0:
                $data_to_insert =
                [
                    'fk_user_id' => $_POST['technician'],

                    'planning_mon_m1' => $_POST['planning_mon_m1'],
                    'planning_mon_m2' => $_POST['planning_mon_m2'],
                    'planning_mon_a1' => $_POST['planning_mon_a1'],
                    'planning_mon_a2' => $_POST['planning_mon_a2'],

                    'planning_tue_m1' => $_POST['planning_tue_m1'],
                    'planning_tue_m2' => $_POST['planning_tue_m2'],
                    'planning_tue_a1' => $_POST['planning_tue_a1'],
                    'planning_tue_a2' => $_POST['planning_tue_a2'],

                    'planning_wed_m1' => $_POST['planning_wed_m1'],
                    'planning_wed_m2' => $_POST['planning_wed_m2'],
                    'planning_wed_a1' => $_POST['planning_wed_a1'],
                    'planning_wed_a2' => $_POST['planning_wed_a2'],

                    'planning_thu_m1' => $_POST['planning_thu_m1'],
                    'planning_thu_m2' => $_POST['planning_thu_m2'],
                    'planning_thu_a1' => $_POST['planning_thu_a1'],
                    'planning_thu_a2' => $_POST['planning_thu_a2'],

                    'planning_fri_m1' => $_POST['planning_fri_m1'],
                    'planning_fri_m2' => $_POST['planning_fri_m2'],
                    'planning_fri_a1' => $_POST['planning_fri_a1'],
                    'planning_fri_a2' => $_POST['planning_fri_a2'],
                ];

                $this->planning_model->insert($data_to_insert);

                return redirect()->to('/helpdesk/planning/cw_planning');

            case 1:
                $data_to_insert =
                [
                    'fk_user_id' => $_POST['technician'],

                    'nw_planning_mon_m1' => $_POST['nw_planning_mon_m1'],
                    'nw_planning_mon_m2' => $_POST['nw_planning_mon_m2'],
                    'nw_planning_mon_a1' => $_POST['nw_planning_mon_a1'],
                    'nw_planning_mon_a2' => $_POST['nw_planning_mon_a2'],

                    'nw_planning_tue_m1' => $_POST['nw_planning_tue_m1'],
                    'nw_planning_tue_m2' => $_POST['nw_planning_tue_m2'],
                    'nw_planning_tue_a1' => $_POST['nw_planning_tue_a1'],
                    'nw_planning_tue_a2' => $_POST['nw_planning_tue_a2'],

                    'nw_planning_wed_m1' => $_POST['nw_planning_wed_m1'],
                    'nw_planning_wed_m2' => $_POST['nw_planning_wed_m2'],
                    'nw_planning_wed_a1' => $_POST['nw_planning_wed_a1'],
                    'nw_planning_wed_a2' => $_POST['nw_planning_wed_a2'],

                    'nw_planning_thu_m1' => $_POST['nw_planning_thu_m1'],
                    'nw_planning_thu_m2' => $_POST['nw_planning_thu_m2'],
                    'nw_planning_thu_a1' => $_POST['nw_planning_thu_a1'],
                    'nw_planning_thu_a2' => $_POST['nw_planning_thu_a1'],

                    'nw_planning_fri_m1' => $_POST['nw_planning_fri_m1'],
                    'nw_planning_fri_m2' => $_POST['nw_planning_fri_m2'],
                    'nw_planning_fri_a1' => $_POST['nw_planning_fri_a1'],
                    'nw_planning_fri_a2' => $_POST['nw_planning_fri_a2'],
                ];

                $this->nw_planning_model->insert($data_to_insert);

                return redirect()->to('/helpdesk/planning/nw_planning');

            default:
                $this->isSetPlanningType(NULL);
        }
    }


    /**
     * Displays the page to modify roles assigned to technicans on periods and manages the post of the data.
     * 
     * @param int $planning_type ID of the edited planning
     * 
     * @return view
     * 
     */
    function update_planning($planning_type)
    {
        $this->isUserLogged();
        
        $this->isSetPlanningType($planning_type);

        $data['planning_type'] = $planning_type;

        $form_fields_data = [];

        // 0 is current week, 1 is next week
        switch($planning_type)
        {
            case 0:
                $form_fields_data = $_SESSION['helpdesk']['cw_periods'];
                break;

            case 1:
                $form_fields_data = $_SESSION['helpdesk']['nw_periods'];
                break;

            default:
                $this->isSetPlanningType(NULL);
        }

        if ($_POST)
        {
            // 0 is current week, 1 is next week
            switch($planning_type)
            {
                case 0:
                    $planning_data = $_POST['planning'];
                    break;

                case 1:
                    $planning_data = $_POST['nw_planning'];
                    break;

                default:
                    $this->isSetPlanningType(NULL);
            }

            foreach ($planning_data as $id_planning => $technician_planning)
            {
                $emptyFieldsCount = 0;

                // 0 is current week, 1 is next week
                switch($planning_type)
                {
                    case 0:
                        $data_to_update = array(
                            'id_planning' => $technician_planning['id_planning'],
                            'fk_user_id' => $technician_planning['fk_user_id']
                        );
                        break;

                    case 1:
                        $data_to_update = array(
                            'id_nw_planning' => $technician_planning['id_nw_planning'],
                            'fk_user_id' => $technician_planning['fk_user_id']
                        );
                        break;

                    default:
                        $this->isSetPlanningType(NULL);
                }

                foreach ($form_fields_data as $field)
                {
                    $field_value = $technician_planning[$field];

                    if(!in_array($field_value, ["", 1, 2, 3]))
                    {
                        $this->session->setFlashdata('error', lang('Helpdesk.err_invalid_role'));

                        return redirect()->to('/helpdesk/planning/update_planning/'.$planning_type);
                    }

                    if(empty($field_value))
                    {
                        // Required for database insertion
                        $field_value = NULL;

                        $emptyFieldsCount++;
                    }

                    $data_to_update[$field] = $field_value;
                }

                // If all fields are empty, prevent having a technician without any role at any period
                if($emptyFieldsCount === 20)
                {
                    $this->session->setFlashdata('error', lang('Helpdesk.err_technician_must_be_assigned_to_schedule'));

                    return redirect()->to('/helpdesk/planning/update_planning/'.$planning_type);
                }

                switch($planning_type)
                {
                    case 0:
                        $this->planning_model->update($id_planning, $data_to_update);
                        break;
            
                    case 1:
                        $this->nw_planning_model->update($id_planning, $data_to_update);
                        break;

                    default:
                        $this->isSetPlanningType(NULL);
                }
                
                $this->session->setFlashdata('success', lang('Helpdesk.scs_planning_updated'));
            }
        }

        // 0 is current week, 1 is next week
        switch($planning_type)
        {
            case 0:
                $planning_data = $this->planning_model->getPlanningDataByUser();

                $data['planning_data'] = $planning_data;

                $data['title'] = lang('Helpdesk.ttl_update_planning');

                break;

            case 1:
                $nw_planning_data = $this->nw_planning_model->getNwPlanningDataByUser();

                $data['nw_planning_data'] = $nw_planning_data;

                $data['title'] = lang('Helpdesk.ttl_update_nw_planning');

                break;

            default:
                $this->isSetPlanningType(NULL);
        }

        $data['messages'] = $this->getFlashdataMessages();

        $data['form_fields_data'] = $form_fields_data;

        $periods = $this->choosePeriods($planning_type);

        $data['classes'] = $this->defineDaysOff($periods);

        return $this->display_view('Helpdesk\update_planning', $data);
    }


    /**
     * Displays the technician delete confirm page, and does the suppression of the entry.
     * 
     * @param int $user_id ID of the deleted technician
     * @param int $planning_type ID of the edited planning
     * 
     * @return view
     * 
     */
    public function delete_technician($user_id, $planning_type)
    {
        $this->isUserLogged();

        $this->isSetPlanningType($planning_type);

        // If the users confirms the deletion
        if(isset($_POST['delete_confirmation']) && $_POST['delete_confirmation'] == true)
        {
            $this->session->setFlashdata('success', lang('Helpdesk.scs_technician_deleted'));

            // 0 is current week, 1 is next week
            switch($planning_type)
            {
                case 0:
                    $planning_data = $this->planning_model->getPlanning($user_id);

                    $this->planning_model->delete($planning_data['id_planning']);

                    return redirect()->to('/helpdesk/planning/cw_planning');

                case 1:
                    $id_planning = $this->nw_planning_model->getNwPlanning($user_id);

                    $this->nw_planning_model->delete($id_planning);

                    return redirect()->to('/helpdesk/planning/nw_planning');

                default:
                    $this->isSetPlanningType(NULL);
            }
        }

        // When the user clicks the delete button
        else
        {
            $data['user_id'] = $user_id;

            $data['planning_type'] = $planning_type;

            $data['title'] = lang('Helpdesk.ttl_delete_confirmation');

            return $this->display_view('Helpdesk\delete_technician', $data);
        }
    }
}