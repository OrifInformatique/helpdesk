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
use Helpdesk\Models\presences_model;
use Helpdesk\Models\planning_model;
use Helpdesk\Models\user_data_model;
use Helpdesk\Models\holidays_model;
use Helpdesk\Models\lw_planning_model;
use Helpdesk\Models\nw_planning_model;
use Helpdesk\Models\terminal_model;

class Home extends BaseController
{

    protected $session;
    protected $presences_model;
    protected $planning_model;
    protected $lw_planning_model;
    protected $nw_planning_model;
    protected $user_data_model;
    protected $holidays_model;
    protected $terminal_model;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
        $this->presences_model = new presences_model();
        $this->planning_model = new planning_model();
        $this->lw_planning_model = new lw_planning_model();
        $this->nw_planning_model = new nw_planning_model();
        $this->user_data_model = new user_data_model();
        $this->holidays_model = new holidays_model();
        $this->terminal_model = new terminal_model();

        helper('form');
    }


    /**
     * Default function, displays the planning page
     * 
     * @return view 'Helpdesk\planning'
     * 
     */
    public function index()
    {
        $this->setSessionVariables();
        
        return $this->planning();
    }
    
    
    /**
     * Set often used variables in session for global access
     * 
     * @return void
     * 
     */
    public function setSessionVariables()
    {
        if(!isset($_SESSION['helpdesk']['next_week']) ||
            !isset($_SESSION['helpdesk']['cw_periods']) ||
            !isset($_SESSION['helpdesk']['nw_periods']))
        {
            // Reference for next week table
            $next_monday = strtotime('next monday');

            $_SESSION['helpdesk'] =
            [
                // Weekdays table for dates
                'next_week' =>
                [
                    'monday' => $next_monday,
                    'tuesday' => strtotime('+1 day', $next_monday),
                    'wednesday' => strtotime('+2 days', $next_monday),
                    'thursday' => strtotime('+3 days', $next_monday),
                    'friday' => strtotime('+4 days', $next_monday)
                ],

                // Current week (cw) planning periods SQL names
                'cw_periods' => 
                [
                    'planning_mon_m1', 'planning_mon_m2', 'planning_mon_a1', 'planning_mon_a2',
                    'planning_tue_m1', 'planning_tue_m2', 'planning_tue_a1', 'planning_tue_a2',
                    'planning_wed_m1', 'planning_wed_m2', 'planning_wed_a1', 'planning_wed_a2',
                    'planning_thu_m1', 'planning_thu_m2', 'planning_thu_a1', 'planning_thu_a2',
                    'planning_fri_m1', 'planning_fri_m2', 'planning_fri_a1', 'planning_fri_a2',
                ],

                /// Next week (nw) planning periods SQL names
                'nw_periods' =>
                [
                    'nw_planning_mon_m1', 'nw_planning_mon_m2', 'nw_planning_mon_a1', 'nw_planning_mon_a2',
                    'nw_planning_tue_m1', 'nw_planning_tue_m2', 'nw_planning_tue_a1', 'nw_planning_tue_a2',
                    'nw_planning_wed_m1', 'nw_planning_wed_m2', 'nw_planning_wed_a1', 'nw_planning_wed_a2',
                    'nw_planning_thu_m1', 'nw_planning_thu_m2', 'nw_planning_thu_a1', 'nw_planning_thu_a2',
                    'nw_planning_fri_m1', 'nw_planning_fri_m2', 'nw_planning_fri_a1', 'nw_planning_fri_a2',
                ],
            ];
        }

        // Else, don't do anything
    }


    /**
     * Redirects the user to the login page if he isn't logged in
     * 
     * @return view 'user\auth\login', if user isn't logged
     * 
     */
    public function isUserLogged()
    {
        // If the user ID isn't set or is empty
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) 
        {
            // Rediriect to the login page
            // NB : PHP header() native function is used because CI functions redirect() and display_view() don't work here for some reason
            header("Location: " . base_url('user/auth/login'));
            exit();
        }

        // Otherwise, proceed with the rest of the code
    }


    /**
     * Redirects to the home page with an error if the planning type isn't set
     * 
     * @param int $planning_type Specifies which planning is being edited
     * 
     * @return view 'Helpdesk\planning', if planning type isn't set
     * 
     */
    public function isSetPlanningType($planning_type)
    {
        if(!isset($planning_type))
        {
            unset($planning_type);

            // Error message
            $error = lang('Helpdesk.err_unfound_planning_type');

            return exit($this->planning(NULL, $error));
        }

        // Otherwise, proceed with the rest of the code
    }


    /**
     * Set classes for leaving blank days off in plannings
     *
     * @param array $periods Names, start and end datetimes of periods
     * 
     * @return array $classes
     * 
     */
    public function defineDaysOff($periods)
    {
        // Get holidays data
        $holidays_data = $this->holidays_model->getHolidays();

        $classes = [];

        foreach($holidays_data as $holiday)
        {
            foreach($periods as $period_name => $period)
            {
                // If the period is in a holiday period
                if($period['start'] >= strtotime($holiday['start_date_holiday']) && $period['end'] <= strtotime($holiday['end_date_holiday']))
                {
                    // Add the class with a custom syntax for better comprehension
                    $classes[] = ' '.$period_name.'-off';
                }
            }
        }

        return $classes;
    }

    /**
     * Prevent code duplication for days off handler
     * 
     * @param int $planning_type Specifies which planning is used
     * 
     * @return array $periods
     * 
     */
    public function choosePeriods($planning_type)
    {
        $periods = [];

        $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        switch($planning_type)
        {
            case -1:
                foreach($weekdays as $day)
                {
                    $periods += 
                    [
                        substr($day, 0, 3).'-m1' => [
                            'start' => strtotime($day.' last week 08:00:00'),
                            'end' => strtotime($day.' last week 10:00:00')
                        ],
                        substr($day, 0, 3).'-m2' => [
                            'start' => strtotime($day.' last week 10:00:00'),
                            'end' => strtotime($day.' last week 12:00:00')
                        ],
                        substr($day, 0, 3).'-a1' => [
                            'start' => strtotime($day.' last week 12:45:00'),
                            'end' => strtotime($day.' last week 15:00:00')
                        ],
                        substr($day, 0, 3).'-a2' => [
                            'start' => strtotime($day.' last week 15:00:00'),
                            'end' => strtotime($day.' last week 16:57:00')
                        ]
                    ];
                }

                break;

            case 0:
                foreach($weekdays as $day)
                {
                    $periods += 
                    [
                        substr($day, 0, 3).'-m1' => [
                            'start' => strtotime($day.' this week 08:00:00'),
                            'end' => strtotime($day.' this week 10:00:00')
                        ],
                        substr($day, 0, 3).'-m2' => [
                            'start' => strtotime($day.' this week 10:00:00'),
                            'end' => strtotime($day.' this week 12:00:00')
                        ],
                        substr($day, 0, 3).'-a1' => [
                            'start' => strtotime($day.' this week 12:45:00'),
                            'end' => strtotime($day.' this week 15:00:00')
                        ],
                        substr($day, 0, 3).'-a2' => [
                            'start' => strtotime($day.' this week 15:00:00'),
                            'end' => strtotime($day.' this week 16:57:00')
                        ]
                    ];
                }

                break;

            case 1:
                foreach($_SESSION['helpdesk']['next_week'] as $day)
                {
                    $periods += 
                    [
                        substr($day, 0, 3).'-m1' => [
                            'start' => strtotime(date('Y-m-d', $day).' 08:00:00'),
                            'end'   => strtotime(date('Y-m-d', $day).' 10:00:00')
                        ],
                        substr($day, 0, 3).'-m2' => [
                            'start' => strtotime(date('Y-m-d', $day).' 08:00:00'),
                            'end'   => strtotime(date('Y-m-d', $day).' 10:00:00')
                        ],
                        substr($day, 0, 3).'-a1' => [
                            'start' => strtotime(date('Y-m-d', $day).' 08:00:00'),
                            'end'   => strtotime(date('Y-m-d', $day).' 10:00:00')
                        ],
                        substr($day, 0, 3).'-a2' => [
                            'start' => strtotime(date('Y-m-d', $day).' 08:00:00'),
                            'end'   => strtotime(date('Y-m-d', $day).' 10:00:00')
                        ]
                    ];
                }

                break;

            default:
                $this->isSetPlanningType(NULL);   
        }

        return $periods;
    }


    /**
     * Displays the current week planning page
     *
     * @param string $success Contains a success message, default value : NULL
     * @param string $error Contains an error message, default value : NULL
     * 
     * @return view 'Helpdesk\planning'
     * 
     */
    public function planning($success = NULL, $error = NULL)
    {
        // If there is a success message, it is stored for being displayed on view
        if(isset($success) && !empty($success))
        {
            $data['success'] = $success;
        }

        // If there is a error message, it is stored for being displayed on view
        if(isset($error) && !empty($error))
        {
            $data['error'] = $error;
        }

        // Retrieves users having a planning
        $data['planning_data'] = $this->planning_model->getPlanningDataByUser();

        // Get names, stard and end dates for each period of current week
        $periods = $this->choosePeriods(0); // 0 stands for current week
        
        // Get CSS classes to hide days off in planning
        $data['classes'] = $this->defineDaysOff($periods);

        // Page title
        $data['title'] = lang('Helpdesk.ttl_planning');

        // Displays current week planning page
        $this->display_view('Helpdesk\planning', $data);
    }


    /**
     * Displays the last week planning page
     *
     * @return view 'Helpdesk\lw_planning'
     * 
     */
    public function lw_planning()
    {
        // Retrieves users having a planning, from last week
        $data['lw_planning_data'] = $this->lw_planning_model->getPlanningDataByUser();

        // Presences table
        $data['lw_periods'] = 
        [
            'lw_planning_mon_m1', 'lw_planning_mon_m2', 'lw_planning_mon_a1', 'lw_planning_mon_a2',
            'lw_planning_tue_m1', 'lw_planning_tue_m2', 'lw_planning_tue_a1', 'lw_planning_tue_a2',
            'lw_planning_wed_m1', 'lw_planning_wed_m2', 'lw_planning_wed_a1', 'lw_planning_wed_a2',
            'lw_planning_thr_m1', 'lw_planning_thr_m2', 'lw_planning_thr_a1', 'lw_planning_thr_a2',
            'lw_planning_fri_m1', 'lw_planning_fri_m2', 'lw_planning_fri_a1', 'lw_planning_fri_a2',
        ];

        // Get names, stard and end dates for each period of last week
        $periods = $this->choosePeriods(-1); // -1 stands for last week

        // Get CSS classes to hide days off in planning
        $data['classes'] = $this->defineDaysOff($periods);

        // Page title
        $data['title'] = lang('Helpdesk.ttl_lw_planning');     

        // Displays last week planning page
        $this->display_view('Helpdesk\lw_planning', $data);
    }


    /**
     * Displays the next week planning page
     *
     * @param string $success Contains a success message, default value : NULL
     * 
     * @return view 'Helpdesk\nw_planning'
     * 
     */
    public function nw_planning($success = NULL)
    {
        // If there is a success message, it is stored for being displayed on view
        if(isset($success) && !empty($success))
        {
            $data['success'] = $success;
        }

        // Retrieves users having a planning, from next week
        $data['nw_planning_data'] = $this->nw_planning_model->getNwPlanningDataByUser();
    
        // Get names, stard and end dates for each period of next week
        $periods = $this->choosePeriods(1); // 1 stands for next week

        // Get CSS classes to hide days off in planning
        $data['classes'] = $this->defineDaysOff($periods);

        // Page title
        $data['title'] = lang('Helpdesk.ttl_nw_planning');

        // Displays next week planning page
        $this->display_view('Helpdesk\nw_planning', $data);
    }


    /**
     * Displays the all_presences page
     *
     * @param string $success Contains a success message, default value : NULL
     * 
     * @return view 'Helpdesk\all_presences'
     * 
     */
    public function allPresences($success = NULL)
    {
        // If there is a success message, it is stored for being displayed on view
        if(isset($success) && !empty($success))
        {
            $data['success'] = $success;
        }

        // Get all presences data
        $data['all_users_presences'] = $this->presences_model->getAllPresences();

        $data['periods'] =
        [
            'presence_mon_m1','presence_mon_m2','presence_mon_a1','presence_mon_a2',
            'presence_tue_m1','presence_tue_m2','presence_tue_a1','presence_tue_a2',
            'presence_wed_m1','presence_wed_m2','presence_wed_a1','presence_wed_a2',
            'presence_thu_m1','presence_thu_m2','presence_thu_a1','presence_thu_a2',
            'presence_fri_m1','presence_fri_m2','presence_fri_a1','presence_fri_a2',
        ];

        // Get names, stard and end dates for each period of current week
        $periods = $this->choosePeriods(0); // 0 stands for current week
        
        // Get CSS classes to hide days off in planning
        $data['classes'] = $this->defineDaysOff($periods);

        // Page title
        $data['title'] = lang('Helpdesk.ttl_all_presences');

        // Displays the all_presences page
        $this->display_view('Helpdesk\all_presences', $data);
    }


    /**
     * Displays the your_presences page |
     * Modifies user presneces 
     * 
     * @return view 'Helpdesk\your_presences'
     *
     */
    public function yourPresences()
    {
        $this->isUserLogged();

        // Retrieves user ID
        $user_id = $_SESSION['user_id'];

        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            // Retrieve presence ID from database
            $id_presence = $this->presences_model->getPresenceId($user_id);

            $form_fields = 
            [
                'presence_mon_m1','presence_mon_m2','presence_mon_a1','presence_mon_a2',
                'presence_tue_m1','presence_tue_m2','presence_tue_a1','presence_tue_a2',
                'presence_wed_m1','presence_wed_m2','presence_wed_a1','presence_wed_a2',
                'presence_thu_m1','presence_thu_m2','presence_thu_a1','presence_thu_a2',
                'presence_fri_m1','presence_fri_m2','presence_fri_a1','presence_fri_a2',
            ];

            // Add default value if the field is empty
            foreach ($form_fields as $field)
            {
                // If the field is empty or doesn't exist or contains a unvalid value
                if (!isset($_POST[$field]) || empty($_POST[$field]) 
                    || !in_array($_POST[$field], [1, 2, 3]))
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

            $this->presences_model->save($data);

            // Success message
            $data['success'] = lang('Helpdesk.scs_presences_updated');
        }

        // Retrieves user presences
        $data['presences'] = $this->presences_model->getPresencesUser($user_id);

        $data['weekdays'] =
        [
            'monday'    => ['presence_mon_m1','presence_mon_m2','presence_mon_a1','presence_mon_a2'],
            'tuesday'   => ['presence_tue_m1','presence_tue_m2','presence_tue_a1','presence_tue_a2'],
            'wednesday' => ['presence_wed_m1','presence_wed_m2','presence_wed_a1','presence_wed_a2'],
            'thursday'  => ['presence_thu_m1','presence_thu_m2','presence_thu_a1','presence_thu_a2'],
            'friday'    => ['presence_fri_m1','presence_fri_m2','presence_fri_a1','presence_fri_a2'],
        ];

        // Page title
        $data['title'] = lang('Helpdesk.ttl_your_presences');

        // Displays presences form page
        $this->display_view('Helpdesk\your_presences', $data);
    }


    /**
     * Displays the delete confirm page |
     * Deletes the presence entry
     *
     * @param int $id_presnece ID of presence entry
     * 
     * @return view 'Helpdesk\all_presneces' if entry is deleted, 'Helpdesk\delete_presences' otherwise
     * 
     */
    public function deletePresences($id_presence)
    {
        $this->isUserLogged();

        // If the users confirms the deletion
        if(isset($_POST['delete_confirmation']) && $_POST['delete_confirmation'])
        {
            // Delete the entry
            $this->presences_model->delete($id_presence);

            // Success message
            $success = lang('Helpdesk.scs_presences_deleted');

            $this->allPresences($success);
        }

        // When the user clicks the delete button
        else
        {
            $data['id_presence'] = $id_presence;

            // Page title
            $data['title'] = lang('Helpdesk.ttl_delete_confirmation');

            // Displays the delete confirmation page
            $this->display_view('Helpdesk\delete_presences', $data);
        }
    }

    /**
     * Displays the add_technician page |
     * Add a technician into a planning
     *
     * @param int $planning_type Specifies which planning is being edited
     * 
     * @return view 'Helpdesk\planning'
     * 
     */
    function addTechnician($planning_type)
    {
        $this->isUserLogged();

        $this->isSetPlanningType($planning_type);

        // Create variable for planning_type to use it in view
        $data['planning_type'] = $planning_type;

        // Get names, stard and end dates for each period of week
        $periods = $this->choosePeriods($planning_type);

        // Get CSS classes to hide days off in planning
        $data['classes'] = $this->defineDaysOff($periods);

        // Page title
        $data['title'] = lang('Helpdesk.ttl_add_technician');

        // Retrieve all users data from database
        $data['users'] = $this->user_data_model->getUsersData();

        // If data is sent
        if (!empty($_POST)) 
        {
            if (is_numeric($_POST['technician'])) 
            {
                $user_id = $_POST['technician'];

                // Finds which planning is used | 0 is current week, 1 is next week
                switch ($planning_type) {
                    case 0:
                        // Checks whether the user already has a schedule
                        $data['error'] = $this->planning_model->checkUserOwnsPlanning($user_id);
                        break;

                    case 1:
                        // Checks whether the user already has a schedule
                        $data['error'] = $this->nw_planning_model->checkUserOwnsNwPlanning($user_id);
                        break;

                    default:
                        $this->isSetPlanningType(NULL);
                }

                // If $data['error'] is empty, means that the user don't have a schedule
                if (empty($data['error'])) 
                {
                    $form_fields_data = [];

                    switch ($planning_type)
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

                    // Variable for empty fields count
                    $emptyFields = 0;

                    // Add default values if field is empty
                    foreach ($form_fields_data as $field) 
                    {
                        // If the field is empty or not set or an unvalid value
                        if (!isset($_POST[$field]) || empty($_POST[$field]) || !in_array($_POST[$field], [1, 2, 3])) 
                        {
                            // Value is defined as NULL
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

                        // Displays the same page with an error message
                        return $this->display_view('Helpdesk\add_technician', $data);
                    }

                    // Success message
                    $success = lang('Helpdesk.scs_technician_added_to_schedule');

                    // Finds which planning is updated | 0 is current week, 1 is next week
                    switch ($planning_type) 
                    {
                        case 0:
                            // Prepare technician insert
                            $data = [
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

                            $this->planning($success);

                            break;

                        case 1:
                            // Prepare technician insert
                            $data = [
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
                                'nw_planning_thu_a2' => $_POST['planning_thu_a1'],

                                'nw_planning_fri_m1' => $_POST['planning_fri_m1'],
                                'nw_planning_fri_m2' => $_POST['planning_fri_m2'],
                                'nw_planning_fri_a1' => $_POST['planning_fri_a1'],
                                'nw_planning_fri_a2' => $_POST['planning_fri_a2'],
                            ];

                            // Insert data into "tbl_nw_planning" table
                            $this->nw_planning_model->insert($data);

                            $this->nw_planning($success);

                            break;

                        default:
                            $this->isSetPlanningType(NULL);
                    }
                } 
                
                // $data['error'] isn't empty, means that the user already has a schedule. Returns an error
                else 
                {
                    return $this->display_view('Helpdesk\add_technician', $data);
                }
            } 
            
            // The user id isn't a number, returns an error
            else 
            {
                $data['error'] = lang('Helpdesk.err_invalid_technician');

                $this->display_view('Helpdesk\add_technician', $data);
            }
        } 

        else 
        {
            return $this->display_view('Helpdesk\add_technician', $data);
        }
    }

    
    /**
     * Displays the update_planning page |
     * Modifies roles assigned to technicans on periods
     *
     * @param int $planning_type Specifies which planning is being edited
     * @param string $error Contains an error message, default value : NULL
     * 
     * @return view 'Helpdesk\update_planning'
     * 
     */
    function updatePlanning($planning_type, $error = NULL)
    {
        $this->isUserLogged();
        
        $this->isSetPlanningType($planning_type);

        // Create variable for planning_type to use it in view
        $data['planning_type'] = $planning_type;

        $form_fields_data = [];

        // Finds which planning is used for fields names | 0 is current week, 1 is next week
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
                
                default:
                    $this->isSetPlanningType(NULL);
            }

            foreach ($planning_data as $id_planning => $technician_planning) 
            {
                // Empty fields count
                $emptyFieldsCount = 0;

                // Finds which planning is updated | 0 is current week, 1 is next week
                switch($planning_type)
                {
                    case 0:
                        // Add the retrieved value to the array that will be used to update the data. Here, we begin with primary and foreign key
                        $data_to_update = array(
                            'id_planning' => $technician_planning['id_planning'],
                            'fk_user_id' => $technician_planning['fk_user_id']
                        );
                        break;
            
                    case 1:
                        // Add the retrieved value to the array that will be used to update the data. Here, we begin with primary and foreign key
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
                    
                    // In any role value is incorrect, returns an error
                    if(!in_array($field_value, ["", 1, 2, 3]))
                    {
                        // Error message
                        $error = lang('Helpdesk.err_invalid_role');

                        unset($_POST);

                        $this->updatePlanning($planning_type, $error);
                        exit(); // Neccessary to prevent displaying multiple views
                    }

                    // Defines value to NULL to insert empty values in database
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

                        default:
                            $this->isSetPlanningType(NULL);
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

                // Page title
                $data['title'] = lang('Helpdesk.ttl_update_nw_planning');

                break;
            
            default:
                $this->isSetPlanningType(NULL);                
        }

        // If there is a error message, it is stored for being displayed on view
        if(isset($error) && !empty($error))
        {
            $data['error'] = $error;
        }

        $data['form_fields_data'] = $form_fields_data;

        // Get names, stard and end dates for each period of current week
        $periods = $this->choosePeriods($planning_type);
        
        // Get CSS classes to hide days off in planning
        $data['classes'] = $this->defineDaysOff($periods);

        $this->display_view('Helpdesk\update_planning', $data);
    }


    /**
     * [DOES NOT HAVE AN ACTUAL USE FOR NOW] Displays the menu of a technician
     *
     * @param int $user_id ID of currently logged user
     * 
     * @return view 'Helpdesk\technician_menu'
     * 
     */
    public function technicianMenu($user_id)
    {
        $this->isUserLogged();

        // Get user data
        $data['user'] = $this->user_data_model->getUserData($user_id);

        // Page title
        $data['title'] = lang('Helpdesk.ttl_technician_menu');

        $this->display_view('Helpdesk\technician_menu', $data);
    }


    /**
     * Displays the delete confirm page |
     * Delete a technician form a planning
     *
     * @param int $user_id ID of deleted technician
     * @param int $planning_type Specifies which planning is being edited
     * 
     * @return view 'Helpdesk\planning' (or nw_planning, depending of $planning_type) if entry is deleted, 'Helpdesk\delete_technician' otherwise
     * 
     */
    public function deleteTechnician($user_id, $planning_type)
    {
        $this->isUserLogged();

        $this->isSetPlanningType($planning_type);

        // If the users confirms the deletion
        if(isset($_POST['delete_confirmation']) && $_POST['delete_confirmation'] == true)
        {
            // Success message
            $success = lang('Helpdesk.scs_technician_deleted');

            // Finds on which planning entry is deleted | 0 is current week, 1 is next week
            switch($planning_type)
            {
                case 0:
                    // Retrieves the planning id to delete the entry
                    $planning_data = $this->planning_model->getPlanning($user_id);

                    // Delete the entry
                    $this->planning_model->delete($planning_data['id_planning']);

                    $this->planning($success);

                    break;
                
                case 1:
                    // Retrieves the planning id to delete the entry
                    $id_planning = $this->nw_planning_model->getNwPlanning($user_id);

                    // Delete the entry
                    $this->nw_planning_model->delete($id_planning);
                    
                    $this->nw_planning($success);

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

    
    /**
     * Displays the holiday list page
     *
     * @param string $success Contains a success message, default value : NULL
     * 
     * @return view 'Heldesk\holidays'
     * 
     */
    public function holidays($success = NULL)
    {
        // If there is a success message, it is stored for being displayed on view
        if(isset($success) && !empty($success))
        {
            $data['success'] = $success;
        }

        // Retrieve all holiday data
        $data['holidays_data'] = $this->holidays_model->getHolidays();

        // Page title
        $data['title'] = lang('Helpdesk.ttl_holiday');

        // Displays holiday list view
        $this->display_view('Helpdesk\holidays', $data);
    }


    /**
     * Display the add_holiday page |
     * Adds or modifies a holiday entry
     * 
     * @param int $id_holiday ID of the holiday, default value = 0
     *
     * @return view 'Helpdesk\add_holidays' until holiday period has been edited. After edit, returns 'Helpdesk\holidays'
     */
    public function saveHoliday($id_holiday = 0)
    {
        $this->isUserLogged();

        $error_dates = false;

        // If data is sent
        if($_POST)
        {
            try
            {
                // Convert String to DateTime for comparison
                $start_date = new DateTime($_POST['start_date']);
                $end_date = new DateTime($_POST['end_date']);
            }

            catch(\Exception)
            {
                $error_dates = true;
            }

            // Checks if end date is before start date or if an datetime conversion error happened
            if($error_dates == true || $end_date < $start_date)
            {
                // Error message
                $data['error'] = lang('Helpdesk.err_dates_are_incoherent');

                // Checks if we are editing a new entry or adding a new one
                if(isset($id_holiday) && $id_holiday != 0)
                {
                    // Keeping existing entry fields data
                    $form_data =
                    [
                        'id_holiday' => $_POST['id_holiday'],
                        'name_holiday' => esc($_POST['holiday_name']),
                        'start_date_holiday' => $_POST['start_date'],
                        'end_date_holiday' => $_POST['end_date'],
                    ];

                    // Page title
                    $data['title'] = lang('Helpdesk.ttl_update_holiday');
                }

                else
                {
                    // If a error is created, keep form fields data
                    if(isset($data['error']))
                    {
                        // Keeping new entry fields data
                        $form_data =
                        [
                            'name_holiday' => trim(esc($_POST['holiday_name'])),
                            'start_date_holiday' => $_POST['start_date'],
                            'end_date_holiday' => $_POST['end_date'],
                        ]; 
                        
                        // Page title
                        $data['title'] = lang('Helpdesk.ttl_add_holiday');                
                    }
                }

                $data['holiday'] = $form_data;

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
                    'name_holiday' => trim(esc($_POST['holiday_name'])),
                    'start_date_holiday' => $_POST['start_date'],
                    'end_date_holiday' => $_POST['end_date'],
                ];

                // Inserting/Updating data
                $this->holidays_model->save($data);

                // Success message
                $success = lang('Helpdesk.scs_holiday_updated');

                $this->holidays($success);
            }
        }

        // No data is sent
        else
        {
            // Checks if we are editing a new entry or adding a new one
            if(isset($id_holiday) && $id_holiday != 0)
            {
                // Retrieve the specific holiday data
                $data['holiday'] = $this->holidays_model->getHoliday($id_holiday);
                
                // Page title
                $data['title'] = lang('Helpdesk.ttl_update_holiday');
            }

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


    /**
     * Displays the delete confirm page |
     * Deletes the vacation entry
     *
     * @param int $id_holiday ID of the holiday
     * 
     * @return view 'Helpdesk\holidays' if entry is deleted, 'Helpdesk\delete_holiday' otherwise
     * 
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
            $success = lang('Helpdesk.scs_holiday_deleted');

            $this->holidays($success);
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


    /**
     * Displays the assigned technicians of a certain period on a page
     * 
     * @param string $error Contains an error message, default value : NULL
     * @param string $data Contains data, default value : NULL
     * 
     * @return view 'Helpdesk\terminal'
     * 
     */
    public function terminal($error = NULL, $data = NULL)
    {        
        $data = [];

        $isDayOff = $this->holidays_model->areWeInHolidays();

        // If there is a error message, it is stored for being displayed on view
        if(isset($error) && !empty($error))
        {
            $data['error'] = $error;
        }

        // Checks whether we are in a holiday period or not | true => in day off ; false = not in day off
        if($isDayOff)
        {
            $data['day_off'] = true;
        }

        else
        {
            $data['day_off'] = false;

            // Retrieves actual time
            $time = 
            [
                'day'       => substr(strtolower(date('l', time())), 0, 3), // Keeps only the 3 first chars of weekday
                'period'    => '', // Will be set later
                'hh:mm'     => strtotime(date('H:i', time())), // time, converted to date, then in time for comparisons
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
                $data['technicians'] = $this->planning_model->getTechniciansOnPeriod($sql_name_period);
                
                $data['period'] = $sql_name_period;
            }

            /* *************************************************************************************************** */

            // Resets the availabilities on the beginning of new periods
            if($time['hh:mm'] == strtotime("08:00") ||
                $time['hh:mm'] == strtotime("10:00") ||
                $time['hh:mm'] == strtotime("12:45") ||
                $time['hh:mm'] == strtotime("15:00"))
            {
                $this->terminal_model->ResetAvailabilities();
            }

            $data['technicians_availability'] = $this->terminal_model->getTerminalData();
        }

        //$data['title'] = lang('Helpdesk.ttl_welcome_to_helpdesk');

        // Displays the page of the terminal
        $this->display_view('Helpdesk\terminal', $data);
    }


    /**
     * Changes technician availability on terminal
     * 
     * @param int $technician_type Technician updated
     * 
     * @return void
     * 
     */
    public function updateTechnicianAvailability($technician_type)
    {
        if(isset($technician_type) && !empty($technician_type))
        {
            $technicians_availability = $this->terminal_model->getTerminalData();

            if(!$technicians_availability)
            {
                $this->terminal_model->ResetAvailabilities();
            }

            switch($technician_type)
            {
                case 1:
                    $index = 1;
                    break;
                    
                case 2:
                    $index = 2;
                    break;
                        
                case 3:
                    $index = 3;
                    break;
    
                default:
                    $error = lang('Helpdesk.err_unvalid_technician_selected');
            }
    
            $technicians_availability[$index] = !$technicians_availability[$index];
        }

        else
        {
            $error = lang('Helpdesk.err_no_technician_selected');
        }

        $data['technicians_availability'] = $technicians_availability;

        // Refreshes the terminal
        $this->terminal($error, $data);
    }


    /**
     * Start the planning generation process
     *
     * @return view 'Helpdesk\generate_planning'
     * 
     */
    public function generatePlanning()
    {
        $this->isUserLogged();

        // Get all users data
        $users = $this->user_data_model->getUsersData();

        $data['users'] = [];

        // Data formatting for getting data easier with JS
        foreach($users as $user)
        {
            $presences_user = $this->presences_model->getPresencesUser($user['fk_user_id']);

            $data['user-'.$user['fk_user_id']] = 
            [
                'firstName' => $user['first_name_user_data'],
                'lastName' => $user['last_name_user_data'],
                'id' => $user['last_name_user_data'].substr($user['last_name_user_data'], 0, 1),
                'active' => true, // TODO : RETRIEVE AUTOMATICALLY THIS VALUE, PRESETTED FOR TESTS
            ];

            foreach($presences_user as $presence_name => $presence)
            {
                $data['user-'.$user['fk_user_id']][$presence_name] = $presence;
            }

            array_push($data['users'], $data['user-'.$user['fk_user_id']]);
        }

        // Displays the page of planning generation
        $this->display_view('Helpdesk\generate_planning', $data);        
    }
}
