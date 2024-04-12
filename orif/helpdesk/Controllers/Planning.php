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
            'title'            => lang('Titles.lw_planning')
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
            'title'         => lang('Titles.planning')
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
            'title'            => lang('Titles.nw_planning')
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
            'title'         => lang('Titles.add_technician')
        ];

        if($_SERVER["REQUEST_METHOD"] != "POST")
        {
            return $this->display_view('Helpdesk\add_technician', $data);
        }

        $validation = \Config\Services::validation();
        $validation->setRule('technician', '', 'is_natural_no_zero|not_in_planning['.$planning_type.']|has_presences', 
        ['is_natural_no_zero' => lang('Forms/Errors.is_natural_no_zero'),
         'not_in_planning'    => lang('Forms/Errors.not_in_planning'),
         'has_presences'      => lang('Forms/Errors.has_presences')]);
        
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
                array_push($technician_absent_periods, lang('Time.'.substr($field, -6)));
            }

            foreach($planning_data as $planning_entry)
            {
                if($planning_entry['fk_user_id'] == $user_id)
                    continue;

                if($_POST[$field] != '' && $_POST[$field] == $planning_entry[$field])
                {
                    $role_duplicated = true;
                    array_push($roles_duplicated_periods, lang('Time.'.substr($field, -6)));
                }
            }
        }


        if($presences_check && $technician_absent)
        {
            $technician_fullname = $this->user_data_model->getUserFullName($user_id);
            $technician_fullname = $technician_fullname['first_name_user_data'].' '.$technician_fullname['last_name_user_data'];

            $data['messages']['error'] = sprintf(lang('Errors.technician_is_absent_on_periods'), $technician_fullname).implode(',<br>', $technician_absent_periods).'.';
            $data['old_add_tech_form'] = $_POST;
    
            return $this->display_view('Helpdesk\add_technician', $data);
        }

        if($empty_fields === 20) 
        {
            $data['messages']['error'] = lang('Errors.technician_must_be_assigned_to_schedule');
            $data['old_add_tech_form'] = $_POST;

            return $this->display_view('Helpdesk\add_technician', $data);
        }

        if($role_duplicated) 
        {
            $data['messages']['error'] = lang('Errors.role_duplicates_on_periods').implode(',<br>', $roles_duplicated_periods).'.';
            $data['old_add_tech_form'] = $_POST;

            return $this->display_view('Helpdesk\add_technician', $data);
        }

        $this->session->setFlashdata('success', lang('Success.technician_added_to_schedule'));

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
                        array_push($technician_absent_periods, lang('Time.'.substr($field, -6)));
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

                            if(!in_array(lang('Time.'.substr($field, -6)), $roles_duplicated_periods))
                                array_push($roles_duplicated_periods, lang('Time.'.substr($field, -6)));
                        }
                    }

                    $data_to_update[$field] = $field_value;
                }
                
                if($presences_check && $technician_absent)
                {
                    $technician_fullname = $this->user_data_model->getUserFullName($user_id);
                    $technician_fullname = $technician_fullname['first_name_user_data'].' '.$technician_fullname['last_name_user_data'];

                    $this->session->setFlashdata('error', sprintf(lang('Errors.technician_is_absent_on_periods'), $technician_fullname).implode(',<br>', $technician_absent_periods).'.');
                    $this->session->setFlashdata('old_edit_plan_form', $_POST);
                    
                    return redirect()->to('/helpdesk/planning/update_planning/'.$planning_type);
                }

                // If all fields are empty, prevent having a technician without any role at any period
                if($emptyFieldsCount === 20)
                {
                    $this->session->setFlashdata('error', lang('Errors.technician_must_be_assigned_to_schedule'));
                    $this->session->setFlashdata('old_edit_plan_form', $_POST);

                    return redirect()->to('/helpdesk/planning/update_planning/'.$planning_type);
                }

                if($role_duplicated) 
                {
                    $this->session->setFlashdata('error', lang('Errors.role_duplicates_on_periods').implode(',<br>', $roles_duplicated_periods).'.');
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

                $data['messages']['success'] = lang('Success.planning_updated');
            }
        }

        // 0 is current week, 1 is next week
        switch($planning_type)
        {
            case 0:
                $planning_data = $this->planning_model->getPlanningDataByUser();

                $data['planning_data'] = $planning_data;
                $data['title']         = lang('Titles.update_planning');
                break;

            case 1:
                $nw_planning_data = $this->nw_planning_model->getNwPlanningDataByUser();

                $data['nw_planning_data'] = $nw_planning_data;
                $data['title']            = lang('Titles.update_nw_planning');
                break;
        }

        if($this->session->getFlashdata('old_edit_plan_form'))
            $data['old_edit_plan_form'] = $this->session->getFlashdata('old_edit_plan_form');

        $periods = $this->choosePeriods($planning_type);

        if(empty($data['messages']['success']))
            $data['messages'] = $this->getFlashdataMessages();

        $data['form_fields']      = $form_fields;
        $data['classes']          = $this->defineDaysOff($periods);
        $data['update']           = true;

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
            $this->session->setFlashdata('success', lang('Success.technician_deleted'));

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
            $week = $planning_type == 0 ? lang('MiscTexts.actual') : lang('MiscTexts.next');

            $user_entry = lang('MiscTexts.technician').' <strong>'.implode(' ', $user_fullname).'</strong>, '.lang('MiscTexts.delete_from_planning_of_week').' <strong>'.$week.'</strong>.';

            $data =
            [
                'title'         => lang('Titles.delete_confirmation'),
                'delete_url'    => base_url('/helpdesk/planning/delete_technician/'.$user_id.'/'.$planning_type),
                'btn_back_url'  => base_url('/helpdesk/planning/update_planning/'.$planning_type),
                'entry'         => $user_entry
            ];

            return $this->display_view('Helpdesk\delete_entry', $data);
        }
    }


    /**
     * Displays the planning delete confirm page, and does the suppression of the entire planning.
     * 
     * @param int $planning_type ID of the edited planning
     * 
     * @return view
     * 
     */
    public function delete_planning($planning_type)
    {
        $this->isUserLogged();

        $this->isSetPlanningType($planning_type);

        // If the users confirms the deletion
        if(isset($_POST['delete_confirmation']) && $_POST['delete_confirmation'] == true)
        {
            $this->session->setFlashdata('success', lang('Success.planning_deleted'));

            // 0 is current week, 1 is next week
            switch($planning_type)
            {
                case 0:
                    $this->planning_model->emptyTable();

                    return redirect()->to('/helpdesk/planning/cw_planning');

                case 1:
                    $this->nw_planning_model->emptyTable();

                    return redirect()->to('/helpdesk/planning/nw_planning');
            }
        }

        // When the user clicks the delete button
        else
        {
            $week = $planning_type == 0 ? lang('MiscTexts.actual') : lang('MiscTexts.next');

            $planning_entry = lang('MiscTexts.planning').' <strong>'.$week.'</strong>.';

            $data =
            [
                'title'         => lang('Titles.delete_confirmation'),
                'delete_url'    => base_url('/helpdesk/planning/delete_planning/'.$planning_type),
                'btn_back_url'  => base_url('/helpdesk/planning/update_planning/'.$planning_type),
                'entry'         => $planning_entry
            ];

            return $this->display_view('Helpdesk\delete_entry', $data);
        }
    }

    
    /** ********************************************************************************************************************************* */


    /**
     * Shifts the weeks to the left (nw => cw, cw => lw, lw deleted).
     * This function is executed every Monday, at 00:00 (with Infomaniak Task Planner).
     * 
     * @return view|void
     * 
     */
    public function shift_weeks($generate_planning = false)
    {
        try
        {
            // PART 1 : Last week deletion
            $this->lw_planning_model->emptyTable();

            // PART 2 : Current week to last week transfer
            $cw_planning = $this->planning_model->getPlanningData();
            
            if($cw_planning)
            {
                $lw_planning = $this->duplicate_planning($cw_planning, -1);
                $this->lw_planning_model->insertBatch($lw_planning);
                $this->planning_model->emptyTable();
            }

            // PART 3 : Next week to current week transfer
            $nw_planning = $this->nw_planning_model->getNwPlanningData();

            if($nw_planning)
            {
                $cw_planning = $this->duplicate_planning($nw_planning, 0);
                $this->planning_model->insertBatch($cw_planning);
                $this->nw_planning_model->emptyTable();
            }

            // PART 4 : Next week generation
            if($generate_planning)
            {
                $this->planning_generation();
                $this->session->setFlashData('success', lang('Success.shift_weeks_with_planning_generation'));
            }

            else
                $this->session->setFlashData('success', lang('Success.shift_weeks'));

            return redirect()->to('helpdesk/planning/nw_planning');
        }

        catch(\Exception)
        {
            $this->session->setFlashdata('error', lang('Errors.weeks_shift'));
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
    private function duplicate_planning($planning, $planning_type)
    {
        $duplicated_planning = [];
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
                if(in_array($key, ['id_planning', 'id_nw_planning', 'fk_user_id']))
                    $duplicated_planning_entry[$key] = $value;

                else
                {
                    $duplicated_planning_entry[$periods[$i]] = $value;
                    $i++;
                }
            }

            $duplicated_planning[] = $duplicated_planning_entry;
        }

        return $duplicated_planning;
    }


    /**
     * Generates the next week planning.
     * 
     * @return view|void
     * 
     */
    public function planning_generation()
    {
        $this->setSessionVariables();

        try
        {
            // Get the periods
            $periods = $this->choosePeriods(1);
            $periods = $this->removePeriodsOff($periods);

            if(empty($periods))
            {
                $this->session->setFlashdata('error', lang('Errors.planning_generation_no_period'));
                return redirect()->to('helpdesk/planning/nw_planning');
            }

            // Get all users that have presences
            $users_ids = $this->presences_model->getUsersIdsInPresences();

            if(empty($users_ids))
            {
                $this->session->setFlashdata('error', lang('Errors.planning_generation_no_technician'));
                return redirect()->to('helpdesk/planning/nw_planning');
            }

            $technicians_data = $this->prepareTechniciansData($periods, $users_ids);

            if(is_null($technicians_data))
            {
                $this->session->setFlashdata('error', lang('Errors.planning_generation_absent_technicians'));
                return redirect()->to('helpdesk/planning/nw_planning');
            }

            $generated_planning = $technicians_data['generated_planning'];
            $technicians_presences = $technicians_data['technicians_presences'];
            $technicians_presences_count = $technicians_data['technicians_presences_count'];
            $technician_assignations_per_role = $technicians_data['technician_assignations_per_role'];
            $technician_max_assignations_per_role = $technicians_data['technician_max_assignations_per_role'];

            $periods_assignations_count = $this->orderPeriodsByAssignationsCount($periods, $technicians_presences);
            
            $periods_assignations_count = $this->shuffleRowsWithSameValueInArray($periods_assignations_count);
            
            $cw_planning = $this->getAndArrangeCwPlanning();

            // 1 => Present, 2 => Partly absent
            $presences = [1, 2];

            /*
             * Planning generation algorithm
             * 
             */
            foreach($periods_assignations_count as $period_name => $possible_assignations_count)
            {
                $sql_nw_period = 'nw_planning_'.str_replace('-', '_', $period_name);
                $sql_cw_period = 'planning_'.str_replace('-', '_', $period_name);
                $sql_presence = 'presence_'.str_replace('-', '_', $period_name);
                
                $roles_assigned_in_period = [];

                $roles_available_in_period = $this->determineRolesAvailableForSpecificPeriod($technicians_presences, $users_ids, $sql_presence);

                // If there are no roles available (means that every technician is absent on that period), loop to the next period.
                if(empty($roles_available_in_period))
                    continue;

                foreach($presences as $presence)
                {
                    foreach($technicians_presences_count as $user_id => $user_id)
                    {
                        $user_presence_on_period = $technicians_presences[$user_id][$sql_presence] ?? null;

                        if($user_presence_on_period != $presence)
                            continue;

                        // If a technician is available at a period which can only fit one technician,
                        // he will be assigned as first tech in that period, despite the max assignations limit exceeded.
                        if($possible_assignations_count == 1)
                        {
                            $generated_planning[$user_id][$sql_nw_period] = 1;
                            $technician_assignations_per_role[$user_id][1]++;
                            break;
                        }

                        foreach($roles_available_in_period as $role)
                        {
                            if($technician_assignations_per_role[$user_id][$role] >= $technician_max_assignations_per_role)
                                continue;

                            if(!in_array($role, $roles_assigned_in_period))
                            {
                                if(isset($cw_planning[$user_id]))
                                {
                                    // Prevents a technician to have the same role in the same period 2 weeks in a row.
                                    if($cw_planning[$user_id][$sql_cw_period] == $role)
                                    {
                                        $possible_assignations_count--;
                                        continue;
                                    }
                                }
                                    
                                $generated_planning[$user_id][$sql_nw_period] = $role;
                                array_push($roles_assigned_in_period, $role);
                                $technician_assignations_per_role[$user_id][$role]++;
                                break;
                            }
                        }
                    }                    
                }
            }

            asort($generated_planning);

            $nw_planning = $this->nw_planning_model->getNwPlanningData();

            if($nw_planning)
                $this->nw_planning_model->emptyTable();

            foreach($generated_planning as $user_id => $generated_planning_entry)
            {
                // Prevent inserting empty rows (technicinain with no assignations).
                if(!isset($technician_assignations_per_role[$user_id]) ||
                    $technician_assignations_per_role[$user_id][1] === 0 &&
                    $technician_assignations_per_role[$user_id][2] === 0 &&
                    $technician_assignations_per_role[$user_id][3] === 0)
                {
                    continue;
                }

                $this->nw_planning_model->insert($generated_planning_entry); 
            }

            $this->session->setFlashdata('success', lang('Success.planning_generation'));
            return redirect()->to('helpdesk/planning/nw_planning');
        }

        catch(\Exception $e)
        {
            $this->session->setFlashdata('error', lang('Errors.planning_generation'));
            return redirect()->to('helpdesk/planning/nw_planning');
        }
    }


    /**
     * Removes from the periods array periods that are off.
     * 
     * @param array $periods
     * 
     * @return array
     * 
     */
    private function removePeriodsOff($periods)
    {
        $holidays_data = $this->holidays_model->getHolidays();

        foreach($holidays_data as $holiday)
        {
            foreach($periods as $period_name => $period)
            {
                // If the period is in a holiday period
                if($period['start'] >= strtotime($holiday['start_date_holiday']) && $period['end'] <= strtotime($holiday['end_date_holiday']))
                {
                    unset($periods[$period_name]);
                }
            }
        }

        return $periods;
    }


    /**
     * Prepare technician data for the planning generation.
     * For code optimization, technicians presences, technicians presences count, 
     * technician assignations per role, and empty generated planning are set here 
     * (this way we loop only once in users).
     * 
     * @param array $periods
     * @param array $users_ids
     * 
     * @return array
     * 
     */
    private function prepareTechniciansData($periods, $users_ids)
    {
        $generated_planning = [];
        $technicians_presences_count = [];
        $technicians_presences = [];
        $technician_assignations_per_role = [];
        $technicians_absent_all_week_count = 0;

        foreach($users_ids as $user_id)
        {
            // Technician presences
            $user_presences = $this->presences_model->getPresencesUser($user_id);

            foreach($user_presences as $presence_name => $presence_value)
            {
                $period_name = str_replace('_', '-', substr($presence_name, -6));
                if(!isset($periods[$period_name]) || $presence_value == 3)
                    unset($user_presences[$presence_name]);
            }

            if(empty($user_presences))
                $technicians_absent_all_week_count++;

            else
            {
                $technicians_presences[$user_id] = $user_presences;

                // Number of times a technician is available
                $technicians_presences_count[$user_id] = count($user_presences);

                // Setting array to count the number of times a technician is assigned to each role
                $technician_assignations_per_role[$user_id] = 
                [
                    1 => 0,
                    2 => 0,
                    3 => 0
                ];
            }

            // If all technicians are absent all week
            if($technicians_absent_all_week_count >= count($users_ids))
            {
                // Will return an error in the parent function
                return null;
            }

            // Generated planning array preperation
            $generated_planning[$user_id]['fk_user_id'] = $user_id;

            foreach($_SESSION['helpdesk']['nw_periods'] as $sql_nw_period)
            {
                $generated_planning[$user_id][$sql_nw_period] = null;
            }
        }
        
        // Prioritze the technicians that are the less often available by putting them on top of the array
        // They will then get picked first when creating the planning, ensuring fair distribution of roles between technicians
        asort($technicians_presences_count);

        $technicians_presences_count = $this->shuffleRowsWithSameValueInArray($technicians_presences_count);

        // This operation makes a fair distribution of techncicians possible
        switch(count($users_ids))
        {
            case 1:
                $technician_max_assignations_per_role = 20;
                break;

            case 2:
                $technician_max_assignations_per_role = 10;
                break;

            case 3:
                $technician_max_assignations_per_role = 7;
                break;

            case 4:
                $technician_max_assignations_per_role = 5;
                break;

            case in_array(count($users_ids), [5,6]):
                $technician_max_assignations_per_role = 4;
                break;

            case in_array(count($users_ids), [7,8,9]):
                $technician_max_assignations_per_role = 3;
                break;

            case count($users_ids) >= 10 && count($users_ids) < 20:
                $technician_max_assignations_per_role = 2;
                break;

            default:
                $technician_max_assignations_per_role = 1;
        }

        $output = 
        [
            'generated_planning'                    => $generated_planning,
            'technicians_presences'                 => $technicians_presences,
            'technicians_presences_count'           => $technicians_presences_count,
            'technician_assignations_per_role'      => $technician_assignations_per_role,
            'technician_max_assignations_per_role'  => $technician_max_assignations_per_role
        ];

        return $output;
    }


    /**
     * Determines which roles are available depending on the number of technicians that can be assigned to the period.
     * Example : if there's only one technician on a period, we don't want to assign him any other role than 1.
     * 
     * @param array $technicians_presences
     * @param array $users_ids
     * @param string $sql_presence
     * 
     * @return array
     * 
     */
    private function determineRolesAvailableForSpecificPeriod($technicians_presences, $users_ids, $sql_presence)
    {
        $technicians_on_period = 0;
        $roles_available_in_period = [];

        foreach($users_ids as $user_id)
        {
            if(isset($technicians_presences[$user_id][$sql_presence]))
                $technicians_on_period++;
        }

        switch($technicians_on_period)
        {
            case 0:
                return [];
                break;

            case 1:
                $roles_available_in_period = [1];
                break;

            case 2:
                $roles_available_in_period = [1, 2];
                break;

            default: // 3 or more technicians
                $roles_available_in_period = [1, 2, 3];
        }

        return $roles_available_in_period;
    }


    /**
     * Orders the periods by the number of technicians that can be assigned on each period (ASC).
     * 
     * @param array $periods
     * @param array $technicians_presences
     * 
     * @return array
     * 
     */
    private function orderPeriodsByAssignationsCount($periods, $technicians_presences)
    {
        foreach($periods as $period_name => $period_name)
        {
            $periods_assignations_count[$period_name] = 0;
            $sql_presence = 'presence_'.str_replace('-', '_', $period_name);

            foreach($technicians_presences as $technician_presence)
            {
                if(isset($technician_presence[$sql_presence]))
                    $periods_assignations_count[$period_name]++;
            }

            if($periods_assignations_count[$period_name] == 0)
                unset($periods_assignations_count[$period_name]);
        }

        asort($periods_assignations_count);

        return $periods_assignations_count;
    }

    /**
     * Get and arrange the current week planning
     * 
     * @return array
     * 
     */
    private function getAndArrangeCwPlanning()
    {
        $cw_planning = $this->planning_model->getPlanningData();
        $arranged_cw_planning = [];

        foreach($cw_planning as $cw_planning_entry)
        {
            $cw_user_planning = [];

            foreach($cw_planning_entry as $cw_planning_cell_name => $cw_planning_cell_value)
            {
                if(!in_array($cw_planning_cell_name, ['id_planning','fk_user_id']))
                    $cw_user_planning[$cw_planning_cell_name] = $cw_planning_cell_value;
            }

            $arranged_cw_planning[$cw_planning_entry['fk_user_id']] = $cw_user_planning;
        }

        return $arranged_cw_planning;
    }


    /**
     * Shuffles the rows that have the same amount of columns (count()) in an array.
     * 
     * @param array
     * 
     * @return array $shuffled_array
     * 
     */
    private function shuffleRowsWithSameValueInArray($array)
    {
        $groups = [];
        $shuffled_array = [];

        foreach($array as $key => $row)
        {
            $groups[$row][] = $key;
        }

        foreach($groups as $key => $group)
        {
            shuffle($group);

            foreach($group as $value)
            {
                $shuffled_array[$value] = $key;
            }
        }

        return $shuffled_array;
    }
}