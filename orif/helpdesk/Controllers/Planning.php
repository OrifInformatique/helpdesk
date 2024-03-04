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

class Planning extends Home
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }


    /**
     * Default function, displays the current week planning.
     * 
     * @return view
     * 
     */
    public function index()
    {
        $this->setSessionVariables();

        return redirect()->to('/helpdesk/planning/cw_planning');
    }


    /** ********************************************************************************************************************************* */


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
            'planning_type'    => -1,
            'title'            => lang('Helpdesk.ttl_lw_planning')
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
        $this->setSessionVariables();

        // 0 stands for current week
        $periods = $this->choosePeriods(0);

        $data =
        [
            'messages'      => $this->getFlashdataMessages(),
            'planning_data' => $this->planning_model->getPlanningDataByUser(),
            'classes'       => $this->defineDaysOff($periods),
            'planning_type' => 0,
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
        $this->setSessionVariables();

        // 1 stands for next week
        $periods = $this->choosePeriods(1);

        $data = 
        [
            'messages'         => $this->getFlashdataMessages(),
            'nw_planning_data' => $this->nw_planning_model->getNwPlanningDataByUser(),
            'classes'          => $this->defineDaysOff($periods),
            'planning_type'    => 1,
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
    public function add_technician($planning_type)
    {
        $this->isUserLogged();
        $this->setSessionVariables();

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
        $validation->setRule('technician', '', 'is_natural_no_zero|not_in_planning['.$planning_type.']|has_presences', 
        ['is_natural_no_zero' => lang('Helpdesk.is_natural_no_zero'),
         'not_in_planning'    => lang('Helpdesk.not_in_planning'),
         'has_presences'      => lang('Helpdesk.has_presences')]);
        
        if(!$validation->run($_POST))
        {
            $data['messages']['error'] = $validation->getError('technician');
            $data['old_add_tech_form'] = $_POST;
            
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

        $user_id = $_POST['technician'];
        $technician_absent = false;
        $role_duplicated =  false;
        $technician_absent_periods = [];
        $roles_duplicated_periods = [];
        $presences_check = isset($_POST['ignore_presences_check']) && $_POST['ignore_presences_check'] === 'on' ? false : true;

        switch($planning_type)
        {
            case 0:
                $planning_data = $this->planning_model->getPlanningData();
                break;
            case 1:
                $planning_data = $this->nw_planning_model->getNwPlanningData();
                break;
        }

        foreach ($form_fields as $field)
        {
            $technician_presence = $this->presences_model->getTechnicianPresenceInSpecificPeriod($user_id, $field);

            if (!isset($_POST[$field]) || empty($_POST[$field]) || !in_array($_POST[$field], [1, 2, 3]))
            {
                $_POST[$field] = NULL;
                $empty_fields++;
            }
            
            if($presences_check && $technician_presence === 3 && in_array($_POST[$field], [1, 2, 3]))
            {
                $technician_absent = true;
                array_push($technician_absent_periods, lang('Helpdesk.'.substr($field, -6)));
            }

            foreach($planning_data as $planning_entry)
            {
                if($planning_entry['fk_user_id'] == $user_id)
                    continue;

                if($_POST[$field] != '' && $_POST[$field] == $planning_entry[$field])
                {
                    $role_duplicated = true;
                    array_push($roles_duplicated_periods, lang('Helpdesk.'.substr($field, -6)));
                }
            }
        }


        if($presences_check && $technician_absent)
        {
            $technician_fullname = $this->user_data_model->getUserFullName($user_id);
            $technician_fullname = $technician_fullname['first_name_user_data'].' '.$technician_fullname['last_name_user_data'];

            $data['messages']['error'] = sprintf(lang('Helpdesk.err_technician_is_absent_on_periods'), $technician_fullname).implode(',<br>', $technician_absent_periods).'.';
            $data['old_add_tech_form'] = $_POST;
    
            return $this->display_view('Helpdesk\add_technician', $data);
        }

        if($empty_fields === 20) 
        {
            $data['messages']['error'] = lang('Helpdesk.err_technician_must_be_assigned_to_schedule');
            $data['old_add_tech_form'] = $_POST;

            return $this->display_view('Helpdesk\add_technician', $data);
        }

        if($role_duplicated) 
        {
            $data['messages']['error'] = lang('Helpdesk.err_role_duplicates_on_periods').implode(',<br>', $roles_duplicated_periods).'.';
            $data['old_add_tech_form'] = $_POST;

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
    public function update_planning($planning_type)
    {
        $this->isUserLogged();
        $this->setSessionVariables();
        
        $this->isSetPlanningType($planning_type);

        $data['planning_type'] = $planning_type;

        $technicians_updated_planning = [];
        $form_fields = [];

        // 0 is current week, 1 is next week
        switch($planning_type)
        {
            case 0:
                $form_fields = $_SESSION['helpdesk']['cw_periods'];
                break;

            case 1:
                $form_fields = $_SESSION['helpdesk']['nw_periods'];
                break;
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
            }

            foreach($planning_data as $id_planning => $technician_planning_row) // Row => all periods in a technician row
            {
                // 0 is current week, 1 is next week
                switch($planning_type)
                {
                    case 0:
                        $data_to_update['id_planning'] = $technician_planning_row['id_planning'];
                        break;

                    case 1:
                        $data_to_update['id_nw_planning'] = $technician_planning_row['id_nw_planning'];
                        break;
                    }
                    
                $data_to_update['fk_user_id'] = $technician_planning_row['fk_user_id'];
                    
                $emptyFieldsCount = 0;
                $user_id = $technician_planning_row['fk_user_id'];
                $technician_absent = false;
                $role_duplicated =  false;
                $technician_absent_periods = [];
                $roles_duplicated_periods = [];
                $presences_check = isset($_POST['ignore_presences_check']) && $_POST['ignore_presences_check'] === 'on' ? false : true;

                foreach ($form_fields as $field)
                {
                    $technician_presence = $this->presences_model->getTechnicianPresenceInSpecificPeriod($user_id, $field);
                    $field_value = $technician_planning_row[$field];

                    if(!$technician_absent && (!in_array($field_value, ["", 1, 2, 3]) || empty($field_value)))
                    {
                        $field_value = NULL; // Required for database insertion
                        $emptyFieldsCount++;
                    }

                    if($presences_check && $technician_presence === 3 && in_array($field_value, [1, 2, 3]))
                    {
                        $technician_absent = true;
                        array_push($technician_absent_periods, lang('Helpdesk.'.substr($field, -6)));
                        continue;
                    }

                    $roles_in_period = [];

                    foreach($planning_data as $technician_planning_column) // Column => all technicians in a period column
                    {
                        if($technician_planning_column[$field] != '' && !in_array($technician_planning_column[$field], $roles_in_period))
                        {
                            array_push($roles_in_period, $technician_planning_column[$field]);
                        }
    
                        else if(in_array($technician_planning_column[$field], $roles_in_period))
                        {
                            $role_duplicated = true;

                            if(!in_array(lang('Helpdesk.'.substr($field, -6)), $roles_duplicated_periods))
                                array_push($roles_duplicated_periods, lang('Helpdesk.'.substr($field, -6)));
                        }
                    }

                    $data_to_update[$field] = $field_value;
                }
                
                if($presences_check && $technician_absent)
                {
                    $technician_fullname = $this->user_data_model->getUserFullName($user_id);
                    $technician_fullname = $technician_fullname['first_name_user_data'].' '.$technician_fullname['last_name_user_data'];

                    $this->session->setFlashdata('error', sprintf(lang('Helpdesk.err_technician_is_absent_on_periods'), $technician_fullname).implode(',<br>', $technician_absent_periods).'.');
                    $this->session->setFlashdata('old_edit_plan_form', $_POST);
                    
                    return redirect()->to('/helpdesk/planning/update_planning/'.$planning_type);
                }

                // If all fields are empty, prevent having a technician without any role at any period
                if($emptyFieldsCount === 20)
                {
                    $this->session->setFlashdata('error', lang('Helpdesk.err_technician_must_be_assigned_to_schedule'));
                    $this->session->setFlashdata('old_edit_plan_form', $_POST);

                    return redirect()->to('/helpdesk/planning/update_planning/'.$planning_type);
                }

                if($role_duplicated) 
                {
                    $this->session->setFlashdata('error', lang('Helpdesk.err_role_duplicates_on_periods').implode(',<br>', $roles_duplicated_periods).'.');
                    $this->session->setFlashdata('old_edit_plan_form', $_POST);
        
                    return redirect()->to('/helpdesk/planning/update_planning/'.$planning_type);
                }

                switch($planning_type)
                {
                    case 0:
                        $technicians_updated_planning[$data_to_update['id_planning']] = $data_to_update;
                        break;
                        
                    case 1:
                        $technicians_updated_planning[$data_to_update['id_nw_planning']] = $data_to_update;
                        break;
                }
            }

            foreach($technicians_updated_planning as $id_planning => $technician_updated_planning)
            {
                switch($planning_type)
                {
                    case 0:
                        $this->planning_model->update($id_planning, $technician_updated_planning);
                        break;
            
                    case 1:
                        $this->nw_planning_model->update($id_planning, $technician_updated_planning);
                        break;
                }

                $data['messages']['success'] = lang('Helpdesk.scs_planning_updated');
            }
        }

        // 0 is current week, 1 is next week
        switch($planning_type)
        {
            case 0:
                $planning_data = $this->planning_model->getPlanningDataByUser();

                $data['planning_data'] = $planning_data;
                $data['title']         = lang('Helpdesk.ttl_update_planning');
                break;

            case 1:
                $nw_planning_data = $this->nw_planning_model->getNwPlanningDataByUser();

                $data['nw_planning_data'] = $nw_planning_data;
                $data['title']            = lang('Helpdesk.ttl_update_nw_planning');
                break;
        }

        if($this->session->getFlashdata('old_edit_plan_form'))
            $data['old_edit_plan_form'] = $this->session->getFlashdata('old_edit_plan_form');

        $periods = $this->choosePeriods($planning_type);

        if(empty($data['messages']['success']))
            $data['messages']         = $this->getFlashdataMessages();
        $data['form_fields']      = $form_fields;
        $data['classes']          = $this->defineDaysOff($periods);

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
            }
        }

        // When the user clicks the delete button
        else
        {
            $user_fullname = $this->user_data_model->getUserFullName($user_id);
            $week = $planning_type == 0 ? 'actuelle' : 'prochaine';

            $user_entry = lang('Helpdesk.technician').' <strong>'.implode(' ', $user_fullname).'</strong>, '.lang('Helpdesk.delete_from_planning_of_week').' <strong>'.$week.'</strong>.';

            $data =
            [
                'title'         => lang('Helpdesk.ttl_delete_confirmation'),
                'delete_url'    => base_url('/helpdesk/planning/delete_technician/'.$user_id.'/'.$planning_type),
                'btn_back_url'  => base_url('/helpdesk/planning/update_planning/'.$planning_type),
                'entry'         => $user_entry
            ];

            return $this->display_view('Helpdesk\delete_entry', $data);
        }
    }

    /** ********************************************************************************************************************************* */




    /**
     * Shifts the weeks to the left (nw => cw, cw => lw, lw deleted).
     * This function is executed every Monday, at 00:00 (with Infomaniak Task Planner).
     * 
     * @return
     * 
     */
    public function shift_weeks()
    {
        try
        {
            // PART 1 : Last week deletion
            $this->lw_planning_model->emptyTable();

            // PART 2 : Current week to last week transfer
            $cw_planning = $this->planning_model->getPlanningData();
            
            if($cw_planning)
            {
                $lw_planning = $this->duplicate($cw_planning, -1);
                $this->lw_planning_model->insertBatch($lw_planning);
                $this->planning_model->emptyTable();
            }

            // PART 3 : Next week to current week transfer
            $nw_planning = $this->nw_planning_model->getNwPlanningData();

            if($nw_planning)
            {
                $cw_planning = $this->duplicate($nw_planning, 0);
                $this->planning_model->insertBatch($cw_planning);
                $this->nw_planning_model->emptyTable();
            }
            
            // PART 4 : Next week generation
            $this->planning_generation();

            $this->session->setFlashdata('success', lang('Helpdesk.scs_weeks_shift'));
            return redirect()->to('helpdesk/planning/cw_planning');
        }

        catch(\Exception)
        {
            $this->session->setFlashdata('error', lang('Helpdesk.err_weeks_shift'));
            return redirect()->to('helpdesk/planning/cw_planning');
        }
    }
    
    
    /**
     * Duplicates a specified planning.
     * 
     * @param array $planning Planning to be duplicated
     * @param int $planning_type Type of the planning duplicated
     * 
     * @return array
     * 
     */
    public function duplicate($planning, $planning_type)
    {
        $duplicated_planning_data = [];
        $periods = [];

        switch($planning_type)
        {
            case -1:
                $periods = $_SESSION['helpdesk']['lw_periods'];
                break;

            case 0:
                $periods = $_SESSION['helpdesk']['cw_periods'];
                break;
        }

        foreach($planning as $row)
        {
            $duplicated_planning_entry = [];
            $i = 0;

            foreach($row as $key => $value)
            {
                if($key === 'id_planning' || $key === 'id_nw_planning' || $key === 'fk_user_id')
                    $duplicated_planning_entry[$key] = $value;

                else
                {
                    $duplicated_planning_entry[$periods[$i]] = $value;
                    $i++;
                }
            }

            $duplicated_planning_data[] = $duplicated_planning_entry;
        }

        return $duplicated_planning_data;
    }


    /**
     * Generates the next week planning.
     * 
     * @return void
     * 
     */
    public function planning_generation()
    {

    }
}