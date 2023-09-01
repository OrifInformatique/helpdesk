<?php

/**
 * Main controller
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Helpdesk\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use DateTime;
use Psr\Log\LoggerInterface;
use Helpdesk\Models\Presences_model;
use Helpdesk\Models\Planning_model;
use Helpdesk\Models\User_Data_model;
use Helpdesk\Models\Holidays_model;
use Helpdesk\Models\Lw_planning_model;
use Helpdesk\Models\Nw_planning_model;

class Home extends BaseController
{

    protected $session;
    protected $presences_model;
    protected $planning_model;
    protected $lw_planning_model;
    protected $nw_planning_model;
    protected $user_data_model;
    protected $holidays_model;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
        $this->presences_model = new Presences_model();
        $this->planning_model = new Planning_model();
        $this->lw_planning_model = new Lw_planning_model();
        $this->nw_planning_model = new Nw_planning_model();
        $this->user_data_model = new User_Data_model();
        $this->holidays_model = new Holidays_model();

        helper('form');
    }


    /*
    ** index function
    **
    ** Default function, displays the planning page
    ** Duplicate from planning() function
    **
    */
    public function index()
    {
        // Page title
        $data['title'] = lang('Helpdesk.ttl_planning');

        // Retrieves users having a planning
        $planning_data = $this->planning_model->getPlanningDataByUser();

        $data['planning_data'] = $planning_data;

        // Presences table
        $data['periods'] = 
        [
            'planning_mon_m1', 'planning_mon_m2', 'planning_mon_a1', 'planning_mon_a2',
            'planning_tue_m1', 'planning_tue_m2', 'planning_tue_a1', 'planning_tue_a2',
            'planning_wed_m1', 'planning_wed_m2', 'planning_wed_a1', 'planning_wed_a2',
            'planning_thu_m1', 'planning_thu_m2', 'planning_thu_a1', 'planning_thu_a2',
            'planning_fri_m1', 'planning_fri_m2', 'planning_fri_a1', 'planning_fri_a2',
        ];

        // Displays current week planning page
        $this->display_view('Helpdesk\planning', $data);
    }


    /*
    ** isUserLogged function
    **
    ** Checks whether the user is logged in
    **
    */
    public function isUserLogged()
    {
        // If the user ID isn't set or is empty
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) 
        {
            // Rediriect to the login page
            // Here, native header() function is used because CI functions redirect() and display_view() don't work
            header("Location: " . base_url('user/auth/login'));
            exit();
        }

        // Otherwise, proceed with the rest of the code
    }


    /*
    ** isSetPlanningType function
    **
    ** Checks whether a planning type exists
    **
    */
    public function isSetPlanningType($planning_type)
    {
        // If there is no planning type, create an error and redirects to home page
        if(!isset($planning_type))
        {
            $planning_type = NULL;

            // Error message
            $data['error'] = lang('Helpdesk.err_unfound_planning_type');

            // Displays the planning page
            return $this->display_view('Helpdesk\home\planning', $data);
        }

        // Otherwise, proceed with the rest of the code
    }

    /*
    ** planning function
    **
    ** Displays the current week planning page
    **
    */
    public function planning()
    {
        // Page title
        $data['title'] = lang('Helpdesk.ttl_planning');

        // Retrieves users having a planning
        $planning_data = $this->planning_model->getPlanningDataByUser();

        $data['planning_data'] = $planning_data;

        // Presences table
        $data['periods'] = 
        [
            'planning_mon_m1', 'planning_mon_m2', 'planning_mon_a1', 'planning_mon_a2',
            'planning_tue_m1', 'planning_tue_m2', 'planning_tue_a1', 'planning_tue_a2',
            'planning_wed_m1', 'planning_wed_m2', 'planning_wed_a1', 'planning_wed_a2',
            'planning_thu_m1', 'planning_thu_m2', 'planning_thu_a1', 'planning_thu_a2',
            'planning_fri_m1', 'planning_fri_m2', 'planning_fri_a1', 'planning_fri_a2',
        ];

        // Displays current week planning page
        $this->display_view('Helpdesk\planning', $data);
    }


    /*
    ** lw_planning function
    **
    ** Displays the last week planning page
    **
    */
    public function lw_planning()
    {
        // Page title
        $data['title'] = lang('Helpdesk.ttl_lw_planning');

        // Retrieves users having a planning, from last week
        $lw_planning_data = $this->lw_planning_model->getPlanningDataByUser();

        $data['lw_planning_data'] = $lw_planning_data;

        // Presences table
        $data['lw_periods'] = 
        [
            'lw_planning_mon_m1', 'lw_planning_mon_m2', 'lw_planning_mon_a1', 'lw_planning_mon_a2',
            'lw_planning_tue_m1', 'lw_planning_tue_m2', 'lw_planning_tue_a1', 'lw_planning_tue_a2',
            'lw_planning_wed_m1', 'lw_planning_wed_m2', 'lw_planning_wed_a1', 'lw_planning_wed_a2',
            'lw_planning_thr_m1', 'lw_planning_thr_m2', 'lw_planning_thr_a1', 'lw_planning_thr_a2',
            'lw_planning_fri_m1', 'lw_planning_fri_m2', 'lw_planning_fri_a1', 'lw_planning_fri_a2',
        ];

        // Displays last week planning page
        $this->display_view('Helpdesk\lw_planning', $data);
    }


    /*
    ** nw_planning function
    **
    ** Displays the next week planning page
    **
    */
    public function nw_planning()
    {
        // Page title
        $data['title'] = lang('Helpdesk.ttl_nw_planning');

        // Retrieves users having a planning, from last week
        $nw_planning_data = $this->nw_planning_model->getNwPlanningDataByUser();

        $data['nw_planning_data'] = $nw_planning_data;

        // Presences table
        $data['nw_periods'] = 
        [
            'nw_planning_mon_m1', 'nw_planning_mon_m2', 'nw_planning_mon_a1', 'nw_planning_mon_a2',
            'nw_planning_tue_m1', 'nw_planning_tue_m2', 'nw_planning_tue_a1', 'nw_planning_tue_a2',
            'nw_planning_wed_m1', 'nw_planning_wed_m2', 'nw_planning_wed_a1', 'nw_planning_wed_a2',
            'nw_planning_thu_m1', 'nw_planning_thu_m2', 'nw_planning_thu_a1', 'nw_planning_thu_a2',
            'nw_planning_fri_m1', 'nw_planning_fri_m2', 'nw_planning_fri_a1', 'nw_planning_fri_a2',
        ];

        
        // Reference for next week table
        $next_monday = strtotime('next monday');
        
        // Weekdays table for dates
        $data['next_week'] =
        [
            'monday' => $next_monday,
            'tuesday' => strtotime('+1 day', $next_monday),
            'wednesday' => strtotime('+2 days', $next_monday),
            'thursday' => strtotime('+3 days', $next_monday),
            'friday' => strtotime('+4 days', $next_monday),
        ];

        // Displays next week planning page
        $this->display_view('Helpdesk\nw_planning', $data);
    }


    /*
    ** presence function
    **
    ** Displays the presence page
    **
    */
    public function presences()
    {
        // Checks whether the user is logged
        $this->isUserLogged();

        // Retrieves user ID
        $user_id = $_SESSION['user_id'];

        // Retrieves user presences
        $presences_data = $this->presences_model->getPresencesUser($user_id);

        // Add presences to $data
        $data['presences'] = $presences_data;

        $data['weekdays'] =
        [
            'monday'    => ['presence_mon_m1','presence_mon_m2','presence_mon_a1','presence_mon_a2'],
            'tuesday'   => ['presence_tue_m1','presence_tue_m2','presence_tue_a1','presence_tue_a2'],
            'wednesday' => ['presence_wed_m1','presence_wed_m2','presence_wed_a1','presence_wed_a2'],
            'thursday'  => ['presence_thu_m1','presence_thu_m2','presence_thu_a1','presence_thu_a2'],
            'friday'    => ['presence_fri_m1','presence_fri_m2','presence_fri_a1','presence_fri_a2'],
        ];

        // Page title
        $data['title'] = lang('Helpdesk.ttl_presences');

        // Displays presences form page
        $this->display_view('Helpdesk\presences', $data);

    }


    /*
    ** savePresence function
    **
    ** Save the presences entered on presences page 
    **
    */
    function savePresence()
    {
        // Checks whether user is logged in
        $this->isUserLogged();

        // Retrieve user ID form session
        $user_id = $_SESSION['user_id'];

        // Retrieve presence ID from database
        $id_presence = $this->presences_model->getPresenceId($user_id);

        // Form fields table
        $form_fields = 
        [
            'presence_mon_m1','presence_mon_m2','presence_mon_a1','presence_mon_a2',
            'presence_tue_m1','presence_tue_m2','presence_tue_a1','presence_tue_a2',
            'presence_wed_m1','presence_wed_m2','presence_wed_a1','presence_wed_a2',
            'presence_thu_m1','presence_thu_m2','presence_thu_a1','presence_thu_a2',
            'presence_fri_m1','presence_fri_m2','presence_fri_a1','presence_fri_a2',
        ];

        //
        // TODO : Take Absent state value from database
        //

        // Add default value if the field is empty
        foreach ($form_fields as $field)
        {
            // If the field is empty or doesn't exist
            if (!isset($_POST[$field]) || empty($_POST[$field]))
            {
                // Value is defined to "Absent"
                $_POST[$field] = 3;
            }
        }

        // Prepare presences to record
        $data = [

            'id_presence' => $id_presence,

            'fk_user_id' => $user_id,

            'presence_mon_m1' => $_POST['presence_mon_m1'],
            'presence_mon_m2' => $_POST['presence_mon_m2'],
            'presence_mon_a1' => $_POST['presence_mon_a1'],
            'presence_mon_a2' => $_POST['presence_mon_a2'],

            'presence_tue_m1' => $_POST['presence_tue_m1'],
            'presence_tue_m2' => $_POST['presence_tue_m2'],
            'presence_tue_a1' => $_POST['presence_tue_a1'],
            'presence_tue_a2' => $_POST['presence_tue_a2'],

            'presence_wed_m1' => $_POST['presence_wed_m1'],
            'presence_wed_m2' => $_POST['presence_wed_m2'],
            'presence_wed_a1' => $_POST['presence_wed_a1'],
            'presence_wed_a2' => $_POST['presence_wed_a2'],

            'presence_thu_m1' => $_POST['presence_thu_m1'],
            'presence_thu_m2' => $_POST['presence_thu_m2'],
            'presence_thu_a1' => $_POST['presence_thu_a1'],
            'presence_thu_a2' => $_POST['presence_thu_a2'],

            'presence_fri_m1' => $_POST['presence_fri_m1'],
            'presence_fri_m2' => $_POST['presence_fri_m2'],
            'presence_fri_a1' => $_POST['presence_fri_a1'],
            'presence_fri_a2' => $_POST['presence_fri_a2']
        ];

        // Do the inset/update on the database
        $this->presences_model->save($data);

        // Select user presences
        $presences_data = $this->presences_model->getPresencesUser($user_id);

        $data['presences'] = $presences_data;

        // Success message
        $data['success'] = lang('Helpdesk.scs_presences_updated');

        $data['weekdays'] =
        [
            'monday'    => ['presence_mon_m1','presence_mon_m2','presence_mon_a1','presence_mon_a2'],
            'tuesday'   => ['presence_tue_m1','presence_tue_m2','presence_tue_a1','presence_tue_a2'],
            'wednesday' => ['presence_wed_m1','presence_wed_m2','presence_wed_a1','presence_wed_a2'],
            'thursday'  => ['presence_thu_m1','presence_thu_m2','presence_thu_a1','presence_thu_a2'],
            'friday'    => ['presence_fri_m1','presence_fri_m2','presence_fri_a1','presence_fri_a2'],
        ];

        // Page title
        $data['title'] = lang('Helpdesk.ttl_presences');

        // Displays presences page
        $this->display_view('Helpdesk\presences', $data);
    }

    /*
    ** addTechnician function
    **
    ** Displays the add_technician page
    ** Save form inputs from add_technician
    **
    */
    function addTechnician($planning_type)
    {
        // Checks whether user is logged in
        $this->isUserLogged();

        // If there is no planning type, create an error and redirects to home page
        if(!isset($planning_type))
        {
            $planning_type = NULL;

            // Error message
            $data['error'] = lang('Helpdesk.err_unfound_planning_type');

            // Displays the planning page
            return $this->display_view('Helpdesk\home\planning', $data);
        }

        $data['planning_type'] = $planning_type;

        // Reference for next week table
        $next_monday = strtotime('next monday');
        
        // Weekdays table for dates
        $data['next_week'] =
        [
            'monday' => $next_monday,
            'tuesday' => strtotime('+1 day', $next_monday),
            'wednesday' => strtotime('+2 days', $next_monday),
            'thursday' => strtotime('+3 days', $next_monday),
            'friday' => strtotime('+4 days', $next_monday),
        ];

        // Page title
        $data['title'] = lang('Helpdesk.ttl_add_technician');

        // Retrieve all users data from database
        $data['users'] = $this->user_data_model->getUsersData();

        // Table to identify presences on next page
        $data['periods'] = 
        [
            'planning_mon_m1','planning_mon_m2','planning_mon_a1','planning_mon_a2',
            'planning_tue_m1','planning_tue_m2','planning_tue_a1','planning_tue_a2',
            'planning_wed_m1','planning_wed_m2','planning_wed_a1','planning_wed_a2',
            'planning_thu_m1','planning_thu_m2','planning_thu_a1','planning_thu_a2',
            'planning_fri_m1','planning_fri_m2','planning_fri_a1','planning_fri_a2',
        ];

        // If the "add technician" button from planning page is pressed
        if (empty($_POST))
        {
            // Displays add_technician page
            return $this->display_view('Helpdesk\add_technician', $data);
        }

        // Retrieve user ID from the "technicien" field
        $user_id = $_POST['technician'];
        
        // Finds which planning is used | 0 is current week, 1 is next week
        switch($planning_type)
        {
            case 0:
                // Checks whether the user already has a schedule
                $data['error'] = $this->planning_model->checkUserOwnsPlanning($user_id);
                break;

            case 1:
                // Checks whether the user already has a schedule
                $data['error'] = $this->nw_planning_model->checkUserOwnsNwPlanning($user_id);
                break;
        }

        // If $data['error'] isn't empty, the user already has a schedule
        if (!empty($data['error']))
        {
            // Displays the same page, with an error message
            return $this->display_view('Helpdesk\add_technician', $data);
        }

        // Form fields table
        $form_fields_data = $data['periods'];

        // Variable for empty fields count
        $emptyFields = 0;

        // Add default values if field is empty
        foreach ($form_fields_data as $field)
        {
            // If the field is empty or is not set
            if (!isset($_POST[$field]) || empty($_POST[$field]))
            {
                // Value is defined to NULL
                $_POST[$field] = NULL;

                // Increment empty fields count by 1
                $emptyFields++;
            }
        }

        // If 20 fields are empty, means all fields are empty. Cannot add a technician without any role
        if ($emptyFields === 20)
        {
            // Error message
            $data['error'] = lang('Helpdesk.err_technician_must_be_assigned_to_schedule');

            // Displays the same page, with an error message
            return $this->display_view('Helpdesk\add_technician', $data);     
        }

        // Success message
        $data['success'] = lang('Helpdesk.scs_technician_added_to_schedule');

        // Finds which planning is updated | 0 is current week, 1 is next week
        switch($planning_type)
        {
            case 0:
                // Prepare technician insert
                $data = 
                [
                    'fk_user_id' => $user_id,

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

                // Insert data into "tbl_planning" table
                $this->planning_model->insert($data);

                // Page title
                $data['title'] = lang('Helpdesk.ttl_planning');
                    
                // Retrieves users having a schedule
                $planning_data = $this->planning_model->getPlanningDataByUser();

                $data['planning_data'] = $planning_data;

                // Presences table
                $data['periods'] = 
                [
                    'planning_mon_m1', 'planning_mon_m2', 'planning_mon_a1', 'planning_mon_a2',
                    'planning_tue_m1', 'planning_tue_m2', 'planning_tue_a1', 'planning_tue_a2',
                    'planning_wed_m1', 'planning_wed_m2', 'planning_wed_a1', 'planning_wed_a2',
                    'planning_thu_m1', 'planning_thu_m2', 'planning_thu_a1', 'planning_thu_a2',
                    'planning_fri_m1', 'planning_fri_m2', 'planning_fri_a1', 'planning_fri_a2',
                ];

                // Displays schedule page
                $this->display_view('Helpdesk\planning', $data);
                
                break;

            case 1:
                // Prepare technician insert
                $data = 
                [
                    'fk_user_id' => $user_id,

                    'nw_planning_mon_m1' => $_POST['planning_mon_m1'],
                    'nw_planning_mon_m2' => $_POST['planning_mon_m2'],
                    'nw_planning_mon_a1' => $_POST['planning_mon_a1'],
                    'nw_planning_mon_a2' => $_POST['planning_mon_a2'],

                    'nw_planning_tue_m1' => $_POST['planning_tue_m1'],
                    'nw_planning_tue_m2' => $_POST['planning_tue_m2'],
                    'nw_planning_tue_a1' => $_POST['planning_tue_a1'],
                    'nw_planning_tue_a2' => $_POST['planning_tue_a2'],

                    'nw_planning_wed_m1' => $_POST['planning_wed_m1'],
                    'nw_planning_wed_m2' => $_POST['planning_wed_m2'],
                    'nw_planning_wed_a1' => $_POST['planning_wed_a1'],
                    'nw_planning_wed_a2' => $_POST['planning_wed_a2'],

                    'nw_planning_thu_m1' => $_POST['planning_thu_m1'],
                    'nw_planning_thu_m2' => $_POST['planning_thu_m2'],
                    'nw_planning_thu_a1' => $_POST['planning_thu_a1'],
                    'nw_planning_thu_a2' => $_POST['planning_thu_a2'],

                    'nw_planning_fri_m1' => $_POST['planning_fri_m1'],
                    'nw_planning_fri_m2' => $_POST['planning_fri_m2'],
                    'nw_planning_fri_a1' => $_POST['planning_fri_a1'],
                    'nw_planning_fri_a2' => $_POST['planning_fri_a2'],
                ];

                // Insert data into "tbl_nw_planning" table
                $this->nw_planning_model->insert($data);

                // Page title
                $data['title'] = lang('Helpdesk.ttl_nw_planning');

                // Retrieves users having a schedule
                $nw_planning_data = $this->nw_planning_model->getNwPlanningDataByUser();

                $data['nw_planning_data'] = $nw_planning_data;

                // Presences table
                $data['nw_periods'] = 
                [
                    'nw_planning_mon_m1', 'nw_planning_mon_m2', 'nw_planning_mon_a1', 'nw_planning_mon_a2',
                    'nw_planning_tue_m1', 'nw_planning_tue_m2', 'nw_planning_tue_a1', 'nw_planning_tue_a2',
                    'nw_planning_wed_m1', 'nw_planning_wed_m2', 'nw_planning_wed_a1', 'nw_planning_wed_a2',
                    'nw_planning_thu_m1', 'nw_planning_thu_m2', 'nw_planning_thu_a1', 'nw_planning_thu_a2',
                    'nw_planning_fri_m1', 'nw_planning_fri_m2', 'nw_planning_fri_a1', 'nw_planning_fri_a2',
                ];

                // Weekdays table for dates
                $data['next_week'] =
                [
                    'monday' => $next_monday,
                    'tuesday' => strtotime('+1 day', $next_monday),
                    'wednesday' => strtotime('+2 days', $next_monday),
                    'thursday' => strtotime('+3 days', $next_monday),
                    'friday' => strtotime('+4 days', $next_monday),
                ];

                // Displays schedule page
                $this->display_view('Helpdesk\nw_planning', $data);

                break;
        }
    }

    
    /*
    ** updatePlanning function
    **
    ** Displays the update_planning page
    ** Saves form data from the update_planning page
    **
    */
    function updatePlanning($planning_type)
    {
        // Checks whether user is logged in
        $this->isUserLogged();
        
        // If there is no planning type, create an error and redirects to home page
        $this->isSetPlanningType($planning_type);

        $data['planning_type'] = $planning_type;

        $form_fields_data = [];

        // Finds which planning is used for fields names | 0 is current week, 1 is next week
        switch($planning_type)
        {
            case 0:
                // Form fields table
                $form_fields_data = 
                [
                    'planning_mon_m1','planning_mon_m2','planning_mon_a1','planning_mon_a2',
                    'planning_tue_m1','planning_tue_m2','planning_tue_a1','planning_tue_a2',
                    'planning_wed_m1','planning_wed_m2','planning_wed_a1','planning_wed_a2',
                    'planning_thu_m1','planning_thu_m2','planning_thu_a1','planning_thu_a2',
                    'planning_fri_m1','planning_fri_m2','planning_fri_a1','planning_fri_a2',
                ];

                break;

            case 1:
                // Form fields table
                $form_fields_data = 
                [
                    'nw_planning_mon_m1','nw_planning_mon_m2','nw_planning_mon_a1','nw_planning_mon_a2',
                    'nw_planning_tue_m1','nw_planning_tue_m2','nw_planning_tue_a1','nw_planning_tue_a2',
                    'nw_planning_wed_m1','nw_planning_wed_m2','nw_planning_wed_a1','nw_planning_wed_a2',
                    'nw_planning_thu_m1','nw_planning_thu_m2','nw_planning_thu_a1','nw_planning_thu_a2',
                    'nw_planning_fri_m1','nw_planning_fri_m2','nw_planning_fri_a1','nw_planning_fri_a2',
                ];
                
                break;
        }

        if ($_POST)
        {
            // Finds which planning is updated | 0 is current week, 1 is next week
            switch($planning_type)
            {
                case 0:
                    // Get form data
                    $planning_data = $_POST['planning'];
                    break;
        
                case 1:
                    // Get from data
                    $planning_data = $_POST['nw_planning'];
                    break;
            }

            // Browse data for each technician
            foreach ($planning_data as $id_planning => $technician_planning) 
            {
                // Empty fields count
                $emptyFieldsCount = 0;

                // Finds which planning is updated | 0 is current week, 1 is next week
                switch($planning_type)
                {
                    case 0:
                        // Add the retrieved value to the array that will be used to update the data. Here, we begin with primary an foreign keys
                        $data_to_update = array(
                            'id_planning' => $technician_planning['id_planning'],
                            'fk_user_id' => $technician_planning['fk_user_id']
                        );
                        break;
            
                    case 1:
                        // Add the retrieved value to the array that will be used to update the data. Here, we begin with primary an foreign keys
                        $data_to_update = array(
                            'id_nw_planning' => $technician_planning['id_nw_planning'],
                            'fk_user_id' => $technician_planning['fk_user_id']
                        );
                        break;
                }
                
                // Browse the form fields for each technician
                foreach ($form_fields_data as $field) 
                {
                    $field_value = $technician_planning[$field];
                    

                    // Defines value to NULL to insert empty values
                    if(empty($field_value))
                    {
                        $field_value = NULL;

                        $emptyFieldsCount++;
                    }

                    // Add the retrieved value to the array that will be used to update the data. Here, roles at each period will be recorded
                    $data_to_update[$field] = $field_value;
                }

                // If all fields are empty, prevent having a technician without any role at any period
                if($emptyFieldsCount === 20)
                {
                    // Error message
                    $data['error'] = lang('Helpdesk.err_technician_must_be_assigned_to_schedule');

                    break;
                }

                else
                {
                    switch($planning_type)
                    {
                        case 0:
                            // Update planning for the user
                            $this->planning_model->update($id_planning, $data_to_update);
                            break;
                
                        case 1:
                            // Update planning for the user
                            $this->nw_planning_model->update($id_planning, $data_to_update);
                            break;
                    }
                    
                    // Success message
                    $data['success'] = lang('Helpdesk.scs_planning_updated');
                }
            }
        }

        // Determines from which planning data are retrieved | 0 is current week, 1 is next week
        switch($planning_type)
        {
            case 0:
                // Retrieve planning data
                $planning_data = $this->planning_model->getPlanningDataByUser();

                $data['planning_data'] = $planning_data;
                        
                // Page title
                $data['title'] = lang('Helpdesk.ttl_update_planning');

                break;

            case 1:
                // Retrieve planning data
                $nw_planning_data = $this->nw_planning_model->getNwPlanningDataByUser();

                $data['nw_planning_data'] = $nw_planning_data;      

                // Reference for next week table
                $next_monday = strtotime('next monday');
                
                // Weekdays table for dates
                $data['next_week'] =
                [
                    'monday' => $next_monday,
                    'tuesday' => strtotime('+1 day', $next_monday),
                    'wednesday' => strtotime('+2 days', $next_monday),
                    'thursday' => strtotime('+3 days', $next_monday),
                    'friday' => strtotime('+4 days', $next_monday),
                ];

                // Page title
                $data['title'] = lang('Helpdesk.ttl_update_nw_planning');

                break;
        }

        $data['form_fields_data'] = $form_fields_data;

        // Display update_planning view
        $this->display_view('Helpdesk\update_planning', $data);
    }


    /*
    ** technicianMenu function
    **
    ** Displays the menu of a technician
    **
    */
    public function technicianMenu($user_id, $planning_type)
    {
        // Checks whether user is logged in
        $this->isUserLogged();

        // If there is no planning type, create an error and redirects to home page
        $this->isSetPlanningType($planning_type);

        $data['planning_type'] = $planning_type;

        $data['user'] = $this->user_data_model->getUserData($user_id);

        $data['title'] = lang('Helpdesk.ttl_technician_menu');

        $this->display_view('Helpdesk\technician_menu', $data);
    }


    /*
    ** deleteTechnician function
    **
    ** Displays the delete confirm page
    ** Delete a technician form a planning
    **
    */
    public function deleteTechnician($user_id, $planning_type)
    {
        // Checks whether user is logged in
        $this->isUserLogged();

        // If there is no planning type, create an error and redirects to home page
        $this->isSetPlanningType($planning_type);

        // If the users confirms the deletion
        if(isset($_POST['delete_confirmation']) && $_POST['delete_confirmation'] == true)
        {
            // Success message
            $data['success'] = lang('Helpdesk.scs_technician_deleted');

            // Finds on which planning entry is deleted | 0 is current week, 1 is next week
            switch($planning_type)
            {
                case 0:
                    // Retrieves the planning id to delete the entry
                    $planning_data = $this->planning_model->getPlanning($user_id);

                    // Delete the entry
                    $this->planning_model->delete($planning_data['id_planning']);

                    /*
                    ** planning() function copy
                    ** (Repetion is needed)
                    */

                    // Page title
                    $data['title'] = lang('Helpdesk.ttl_planning');

                    // Retrieves users having a planning
                    $planning_data = $this->planning_model->getPlanningDataByUser();

                    $data['planning_data'] = $planning_data;

                    // Presences table
                    $data['periods'] = 
                    [
                        'planning_mon_m1', 'planning_mon_m2', 'planning_mon_a1', 'planning_mon_a2',
                        'planning_tue_m1', 'planning_tue_m2', 'planning_tue_a1', 'planning_tue_a2',
                        'planning_wed_m1', 'planning_wed_m2', 'planning_wed_a1', 'planning_wed_a2',
                        'planning_thu_m1', 'planning_thu_m2', 'planning_thu_a1', 'planning_thu_a2',
                        'planning_fri_m1', 'planning_fri_m2', 'planning_fri_a1', 'planning_fri_a2',
                    ];

                    // Displays current week planning page
                    $this->display_view('Helpdesk\planning', $data);

                    break;
                
                case 1:
                    // Retrieves the planning id to delete the entry
                    $id_planning = $this->nw_planning_model->getNwPlanning($user_id);

                    // Delete the entry
                    $this->nw_planning_model->delete($id_planning);
                    
                    /*
                    ** nw_planning() function copy
                    ** (Repetion is needed)
                    */

                    // Page title
                    $data['title'] = lang('Helpdesk.ttl_nw_planning');

                    // Retrieves users having a planning
                    $nw_planning_data = $this->nw_planning_model->getNwPlanningDataByUser();

                    $data['nw_planning_data'] = $nw_planning_data;

                    // Presences table
                    $data['nw_periods'] = 
                    [
                        'nw_planning_mon_m1', 'nw_planning_mon_m2', 'nw_planning_mon_a1', 'nw_planning_mon_a2',
                        'nw_planning_tue_m1', 'nw_planning_tue_m2', 'nw_planning_tue_a1', 'nw_planning_tue_a2',
                        'nw_planning_wed_m1', 'nw_planning_wed_m2', 'nw_planning_wed_a1', 'nw_planning_wed_a2',
                        'nw_planning_thu_m1', 'nw_planning_thu_m2', 'nw_planning_thu_a1', 'nw_planning_thu_a2',
                        'nw_planning_fri_m1', 'nw_planning_fri_m2', 'nw_planning_fri_a1', 'nw_planning_fri_a2',
                    ];

                    // Reference for next week table
                    $next_monday = strtotime('next monday');

                    // Weekdays table for dates
                    $data['next_week'] =
                    [
                        'monday' => $next_monday,
                        'tuesday' => strtotime('+1 day', $next_monday),
                        'wednesday' => strtotime('+2 days', $next_monday),
                        'thursday' => strtotime('+3 days', $next_monday),
                        'friday' => strtotime('+4 days', $next_monday),
                    ];

                    // Displays current week planning page
                    $this->display_view('Helpdesk\nw_planning', $data);

                    break;
            }
        }

        // When the user clicks the delete button
        else
        {
            $data['user_id'] = $user_id;

            $data['planning_type'] = $planning_type;

            // Page title
            $data['title'] = lang('Helpdesk.ttl_delete_confirmation');

            // Displays the delete confirmation page
            $this->display_view('Helpdesk\delete_technician', $data);

        }        
    }

    
    /*
    ** holiday function
    **
    ** Displays the holiday list page
    **
    */
    public function holidays()
    {
        // Page title
        $data['title'] = lang('Helpdesk.ttl_holiday');

        // Retrieve all holiday data
        $holidays_data = $this->holidays_model->getHolidays();

        $data['holidays_data'] = $holidays_data;

        // Displays holiday list view
        $this->display_view('Helpdesk\holidays', $data);
    }


    /*
    ** saveHoliday function
    **
    ** Display the add_holiday page
    ** Saves form data from add_holiday page
    **
    */
    public function saveHoliday($id_holiday = 0)
    {
        // Checks whether user is logged in
        $this->isUserLogged();

        // If data is sent
        if($_POST)
        {  
            // Convert String to DateTime for comparison
            $start_date = new DateTime(($_POST['start_date']));
            $end_date = new DateTime(($_POST['end_date']));
            
            // Checks if end date is before start date
            if($end_date < $start_date)
            {
                // Error message
                $data['error'] = lang('Helpdesk.err_dates_are_incoherent');

                // If we edit an existing entry
                if(isset($id_holiday) && $id_holiday != 0)
                {
                    $form_data =
                    [
                        'id_holiday' => $_POST['id_holiday'],
                        'name_holiday' => esc($_POST['holiday_name']),
                        'start_date_holiday' => $_POST['start_date'],
                        'end_date_holiday' => $_POST['end_date'],
                    ];

                    $data['holiday'] = $form_data;   
                    
                    // Page title
                    $data['title'] = lang('Helpdesk.ttl_update_holiday');
                }

                // Otherwise, we create a vacation period
                else
                {
                    // Page title
                    $data['title'] = lang('Helpdesk.ttl_add_holiday');     
                    
                    // If a error is created, keep form fields data
                    if(isset($data['error']))
                    {
                        $form_data =
                        [
                            'name_holiday' => esc($_POST['holiday_name']),
                            'start_date_holiday' => $_POST['start_date'],
                            'end_date_holiday' => $_POST['end_date'],
                        ];

                        $data['holiday'] = $form_data;                
                    }
                }

                // Displays the add_holiday view
                $this->display_view('Helpdesk\add_holiday', $data);
            }

            // Otherwise, no error is created, entry creation
            else
            {
                // Prepare data to record
                $data =
                [
                    'id_holiday' => $_POST['id_holiday'],
                    'name_holiday' => esc($_POST['holiday_name']),
                    'start_date_holiday' => $_POST['start_date'],
                    'end_date_holiday' => $_POST['end_date'],
                ];

                // Inserting data
                $this->holidays_model->save($data);

                // Success message
                $data['success'] = lang('Helpdesk.scs_holiday_updated');

                /*
                ** holiday() function copy
                ** (Repetion is needed)
                */

                // Page title
                $data['title'] = lang('Helpdesk.ttl_holiday');

                // Retrieve all holiday data
                $holidays_data = $this->holidays_model->getHolidays();

                $data['holidays_data'] = $holidays_data;

                // Displays the holidays list view, with a success message
                $this->display_view('Helpdesk\holidays', $data);                
            }
        }

        // Otherwise, the add_holiday page is displayed
        else
        {
            // If we edit an existing entry
            if(isset($id_holiday) && $id_holiday != 0)
            {
                // Retrieve the holiday data
                $holiday_data = $this->holidays_model->getHoliday($id_holiday);

                $data['holiday'] = $holiday_data;
                
                // Page title
                $data['title'] = lang('Helpdesk.ttl_update_holiday');
            }

            // Otherwise, we create a vacation period
            else
            {
                // Page title
                $data['title'] = lang('Helpdesk.ttl_add_holiday');     
                
                // If a error is created, keep form fields data
                if(isset($data['error']))
                {
                    $form_data =
                    [
                        'name_holiday' => $_POST['holiday_name'],
                        'start_date_holiday' => $_POST['start_date'],
                        'end_date_holiday' => $_POST['end_date'],
                    ];

                    $data['holiday'] = $form_data;                
                }
            }

            // Displays the add_holiday view
            $this->display_view('Helpdesk\add_holiday', $data);
        }
    }


    /*
    ** deleteHoliday function
    **
    ** Displays the delete confirm page
    ** Deletes the vacation entry
    **
    */
    public function deleteHoliday($id_holiday)
    {
        // Checks whether user is logged in
        $this->isUserLogged();

        // If the users confirms the deletion
        if(isset($_POST['delete_confirmation']) && $_POST['delete_confirmation'] == true)
        {
            // Delete the entry
            $this->holidays_model->delete($id_holiday);

            // Success message
            $data['success'] = lang('Helpdesk.scs_holiday_deleted');

            /*
            ** holiday() function copy
            ** (Repetion is needed)
            */

            // Page title
            $data['title'] = lang('Helpdesk.ttl_holiday');

            // Retrieve all holiday data
            $holidays_data = $this->holidays_model->getHolidays();

            $data['holidays_data'] = $holidays_data;

            // Displays the holidays list view, with a success message
            $this->display_view('Helpdesk\holidays', $data);
        }

        // When the user clicks the delete button
        else
        {
            $data['id_holiday'] = $id_holiday;

            // Page title
            $data['title'] = lang('Helpdesk.ttl_delete_confirmation');

            // Displays the delete confirmation page
            $this->display_view('Helpdesk\delete_holiday', $data);

        }
    }


    /*
    ** terminalDisplay function
    **
    ** Displays the assigned technicians of a certain period on a page
    ** The page is displayed live on a terminal (a screen)
    **
    */
    public function terminalDisplay()
    {
        $data = [];

        // Retrieves actual time
        $time = 
        [
            'day'       => substr(strtolower(date('l', time())), 0, 3), // Keeps only the 3 first chars of weekday
            'period'    => '', // Will be set later
            'hh:mm'     => strtotime(date('H:i', time())), // time, converted to time for comparisons
        ];

        // Determines on which period we actually are
        switch (true) {
            case ($time['hh:mm'] >= strtotime("08:00") && $time['hh:mm'] < strtotime("10:00")):
                $time['period'] = 'm1';
                break;
        
            case ($time['hh:mm'] >= strtotime("10:00") && $time['hh:mm'] < strtotime("12:00")):
                $time['period'] = 'm2';
                break;
        
            case ($time['hh:mm'] >= strtotime("12:45") && $time['hh:mm'] < strtotime("15:00")):
                $time['period'] = 'a1';
                break;
        
            case ($time['hh:mm'] >= strtotime("15:00") && $time['hh:mm'] < strtotime("16:57")):
                $time['period'] = 'a2';
                break;
        
            default:
                $time['period'] = NULL;
                break;
        }

        // If we are in work timetables
        if(isset($time['period']))
        {
            // Constructs the period name
            $sql_name_period = 'planning_'.$time['day'].'_'.$time['period'];

            // Retrieves the technicians that are assigned to this period
            $technicians_data = $this->planning_model->getTechniciansOnPeriod($sql_name_period);

            $data['technicians'] = $technicians_data;
            
            $data['period'] = $sql_name_period;
        }

        // Otherwise, will display the terminal page with an error

        //$data['title'] = lang('Helpdesk.ttl_welcome_to_helpdesk');

        // Displays the page of the terminal
        $this->display_view('Helpdesk\terminal', $data);
    }
}
