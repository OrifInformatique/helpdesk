<?php

/**
 * Main controller
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
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
     * Default function, displays the planning page.
     * 
     * @return view 'Helpdesk\planning'
     * 
     */
    public function index()
    {
        $this->setSessionVariables();

        return $this->planning();
    }
    
    /** ********************************************************************************************************************************* */


    /**
     * Set often used variables in session for global access.
     * 
     * @return void
     * 
     */
    public function setSessionVariables()
    {
        if(!isset($_SESSION['helpdesk']['next_week']) ||
            !isset($_SESSION['helpdesk']['cw_periods']) ||
            !isset($_SESSION['helpdesk']['nw_periods']) ||
            !isset($_SESSION['helpdesk']['presence_periods']))
        {
            $next_monday = strtotime('next monday');

            $_SESSION['helpdesk'] =
            [
                'next_week' =>
                [
                    'monday' => $next_monday,
                    'tuesday' => strtotime('+1 day', $next_monday),
                    'wednesday' => strtotime('+2 days', $next_monday),
                    'thursday' => strtotime('+3 days', $next_monday),
                    'friday' => strtotime('+4 days', $next_monday)
                ],

                // SQL names of the current week's planning periods (cw)
                'cw_periods' =>
                [
                    'planning_mon_m1', 'planning_mon_m2', 'planning_mon_a1', 'planning_mon_a2',
                    'planning_tue_m1', 'planning_tue_m2', 'planning_tue_a1', 'planning_tue_a2',
                    'planning_wed_m1', 'planning_wed_m2', 'planning_wed_a1', 'planning_wed_a2',
                    'planning_thu_m1', 'planning_thu_m2', 'planning_thu_a1', 'planning_thu_a2',
                    'planning_fri_m1', 'planning_fri_m2', 'planning_fri_a1', 'planning_fri_a2',
                ],

                // SQL names of the next week's planning periods (nw)
                'nw_periods' =>
                [
                    'nw_planning_mon_m1', 'nw_planning_mon_m2', 'nw_planning_mon_a1', 'nw_planning_mon_a2',
                    'nw_planning_tue_m1', 'nw_planning_tue_m2', 'nw_planning_tue_a1', 'nw_planning_tue_a2',
                    'nw_planning_wed_m1', 'nw_planning_wed_m2', 'nw_planning_wed_a1', 'nw_planning_wed_a2',
                    'nw_planning_thu_m1', 'nw_planning_thu_m2', 'nw_planning_thu_a1', 'nw_planning_thu_a2',
                    'nw_planning_fri_m1', 'nw_planning_fri_m2', 'nw_planning_fri_a1', 'nw_planning_fri_a2',
                ],

                // SQL names of presences on each period of the week
                'presences_periods' =>
                [
                    'presence_mon_m1','presence_mon_m2','presence_mon_a1','presence_mon_a2',
                    'presence_tue_m1','presence_tue_m2','presence_tue_a1','presence_tue_a2',
                    'presence_wed_m1','presence_wed_m2','presence_wed_a1','presence_wed_a2',
                    'presence_thu_m1','presence_thu_m2','presence_thu_a1','presence_thu_a2',
                    'presence_fri_m1','presence_fri_m2','presence_fri_a1','presence_fri_a2',
                ],
            ];
        }
    }


    /**
     * Checks whether the user is logged.
     * 
     * @return view|void 
     * 
     */
    public function isUserLogged()
    {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']))
        {
            // Rediriect to the login page
            // NB : PHP header() native function is used because CI functions redirect() and display_view() don't work here for some reason
            header("Location: " . base_url('user/auth/login'));
            exit();
        }
    }


    /**
     * Checks if the planning edited is correct.
     * 
     * @param int $planning_type Specifies which planning is being edited
     * 
     * @return view|void
     * 
     */
    public function isSetPlanningType($planning_type)
    {
        if(!in_array($planning_type, [-1,0,1]))
        {
            $this->session->setFlashdata('error', lang('Helpdesk.err_unvalid_planning_type'));

            return exit($this->planning(NULL));
        }
    }


    /**
     * Create CSS classes for leaving blank days off in plannings.
     * 
     * @param array $periods Names, start and end datetimes of periods
     * 
     * @return array
     * 
     */
    public function defineDaysOff($periods)
    {
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
     * Get names, stard and end dates for each period of a week.
     * 
     * @param int $planning_type ID of the edited planning
     * 
     * @return array
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
     * Get error and succes messages
     * 
     * @return array|null
     * 
     */
    public function getFlashdataMessages()
    {
        $messages['success'] = $this->session->getFlashdata('success');
        $messages['error'] = $this->session->getFlashdata('error');

        return $messages;
    }


    /** ********************************************************************************************************************************* */


    /**
     * Displays the current week planning.
     * 
     * @return view
     * 
     */
    public function planning()
    {
        $data['messages'] = $this->getFlashdataMessages();

        $data['planning_data'] = $this->planning_model->getPlanningDataByUser();
        
        // 0 stands for current week
        $periods = $this->choosePeriods(0);
        
        $data['classes'] = $this->defineDaysOff($periods);

        $data['title'] = lang('Helpdesk.ttl_planning');

        return $this->display_view('Helpdesk\planning', $data);
    }


    /**
     * Displays the last week planning.
     * 
     * @return view
     * 
     */
    public function lw_planning()
    {
        $data['lw_planning_data'] = $this->lw_planning_model->getPlanningDataByUser();

        // SQL names of last week'y planning periods
        $data['lw_periods'] =
        [
            'lw_planning_mon_m1', 'lw_planning_mon_m2', 'lw_planning_mon_a1', 'lw_planning_mon_a2',
            'lw_planning_tue_m1', 'lw_planning_tue_m2', 'lw_planning_tue_a1', 'lw_planning_tue_a2',
            'lw_planning_wed_m1', 'lw_planning_wed_m2', 'lw_planning_wed_a1', 'lw_planning_wed_a2',
            'lw_planning_thr_m1', 'lw_planning_thr_m2', 'lw_planning_thr_a1', 'lw_planning_thr_a2',
            'lw_planning_fri_m1', 'lw_planning_fri_m2', 'lw_planning_fri_a1', 'lw_planning_fri_a2',
        ];

        // -1 stands for last week
        $periods = $this->choosePeriods(-1);

        $data['classes'] = $this->defineDaysOff($periods);

        $data['title'] = lang('Helpdesk.ttl_lw_planning');

        return $this->display_view('Helpdesk\lw_planning', $data);
    }


    /**
     * Displays the next week planning
     * 
     * @return view
     * 
     */
    public function nw_planning()
    {
        $data['messages'] = $this->getFlashdataMessages();

        $data['nw_planning_data'] = $this->nw_planning_model->getNwPlanningDataByUser();
    
        // 1 stands for next week
        $periods = $this->choosePeriods(1);

        $data['classes'] = $this->defineDaysOff($periods);

        $data['title'] = lang('Helpdesk.ttl_nw_planning');

        return $this->display_view('Helpdesk\nw_planning', $data);
    }


    /**
     * Displays the presences of all technicians.
     * 
     * @return view
     * 
     */
    public function allPresences()
    {
        $data['messages'] = $this->getFlashdataMessages();

        $data['all_users_presences'] = $this->presences_model->getAllPresences();

        // 0 stands for current week
        $periods = $this->choosePeriods(0);
        
        $data['classes'] = $this->defineDaysOff($periods);

        $data['title'] = lang('Helpdesk.ttl_all_presences');

        return $this->display_view('Helpdesk\all_presences', $data);
    }


    /**
     * Displays the page letting users modify their presences, and manages the post of the data.
     * 
     * @return view
     * 
     */
    public function yourPresences()
    {
        $this->isUserLogged();

        $user_id = $_SESSION['user_id'];

        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $id_presence = $this->presences_model->getPresenceId($user_id);

            foreach ($_SESSION['helpdesk']['presences_periods'] as $field)
            {
                if (!isset($_POST[$field]) ||
                    empty($_POST[$field]) ||
                    !in_array($_POST[$field], [1, 2, 3]))
                {
                    // Default value is set to "Absent"
                    $_POST[$field] = 3;
                }
            }

            $data_to_save =
            [
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

            $this->presences_model->save($data_to_save);

            $data['messages']['success'] = lang('Helpdesk.scs_presences_updated');
        }

        $data['presences'] = $this->presences_model->getPresencesUser($user_id);

        $data['weekdays'] =
        [
            'monday'    => ['presence_mon_m1','presence_mon_m2','presence_mon_a1','presence_mon_a2'],
            'tuesday'   => ['presence_tue_m1','presence_tue_m2','presence_tue_a1','presence_tue_a2'],
            'wednesday' => ['presence_wed_m1','presence_wed_m2','presence_wed_a1','presence_wed_a2'],
            'thursday'  => ['presence_thu_m1','presence_thu_m2','presence_thu_a1','presence_thu_a2'],
            'friday'    => ['presence_fri_m1','presence_fri_m2','presence_fri_a1','presence_fri_a2'],
        ];

        $data['title'] = lang('Helpdesk.ttl_your_presences');

        return $this->display_view('Helpdesk\your_presences', $data);
    }


    /**
     * Displays the presence delete confirm page, and does the suppression of the entry.
     * 
     * @param int $id_presence ID of the presence entry
     * 
     * @return view
     * 
     */
    public function deletePresences($id_presence)
    {
        $this->isUserLogged();

        // If the users confirms the deletion
        if(isset($_POST['delete_confirmation']) && $_POST['delete_confirmation'])
        {
            $this->presences_model->delete($id_presence);

            $this->session->setFlashdata('success', lang('Helpdesk.scs_presences_deleted'));

            return redirect()->to('/helpdesk/home/allPresences');
        }

        // When the user clicks the delete button
        else
        {
            $data['id_presence'] = $id_presence;

            $data['title'] = lang('Helpdesk.ttl_delete_confirmation');

            return $this->display_view('Helpdesk\delete_presences', $data);
        }
    }


    /**
     * Displays the page for adding technicians in planning, and manages the post of the data.
     * 
     * @param int $planning_type ID of the edited planning
     * 
     * @return view
     * 
     */
    function addTechnician($planning_type)
    {
        $this->isUserLogged();

        $this->isSetPlanningType($planning_type);

        // Create variable for planning_type to use it in view
        $data['planning_type'] = $planning_type;

        $periods = $this->choosePeriods($planning_type);

        $data['classes'] = $this->defineDaysOff($periods);

        $data['title'] = lang('Helpdesk.ttl_add_technician');

        $data['users'] = $this->user_data_model->getUsersData();

        if (empty($_POST)) 
        {
            return $this->display_view('Helpdesk\add_technician', $data);
        }

        if (!is_numeric($_POST['technician']))
        {
            $data['messages']['error'] = lang('Helpdesk.err_invalid_technician');

            return $this->display_view('Helpdesk\add_technician', $data);
        }

        $user_id = $_POST['technician'];

        // 0 is current week, 1 is next week
        switch ($planning_type) {
            case 0:
                $data['messages']['error'] = $this->planning_model->checkUserOwnsPlanning($user_id);
                break;

            case 1:
                $data['messages']['error'] = $this->nw_planning_model->checkUserOwnsNwPlanning($user_id);
                break;

            default:
                $this->isSetPlanningType(NULL);
        }

        // If $data['error'] isn't empty, means that the user already has a schedule
        if (!empty($data['messages']['error'])) 
        {
            return $this->display_view('Helpdesk\add_technician', $data);
        }

        $form_fields = [];

        switch ($planning_type)
        {
            case 0:
                $form_fields = $_SESSION['helpdesk']['cw_periods'];
                break;

            case 1:
                $form_fields = $_SESSION['helpdesk']['nw_periods'];
                break;
            
            default:
                $this->isSetPlanningType(NULL);
        }

        $emptyFields = 0;

        foreach ($form_fields as $field)
        {
            if (!isset($_POST[$field]) || empty($_POST[$field]) || !in_array($_POST[$field], [1, 2, 3]))
            {
                $_POST[$field] = NULL;

                $emptyFields++;
            }
        }

        // If 20 fields are empty, means all fields are empty. Cannot add a technician without any role
        if ($emptyFields === 20) 
        {
            $data['messages']['error'] = lang('Helpdesk.err_technician_must_be_assigned_to_schedule');

            return $this->display_view('Helpdesk\add_technician', $data);
        }

        $this->session->setFlashdata('success', lang('Helpdesk.scs_technician_added_to_schedule'));

        // 0 is current week, 1 is next week
        switch ($planning_type) 
        {
            case 0:
                $data_to_insert =
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
                $this->planning_model->insert($data_to_insert);

                return redirect()->to('/helpdesk/home/planning');

            case 1:
                $data_to_insert =
                [
                    'fk_user_id' => $user_id,

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

                return redirect()->to('/helpdesk/home/nw_planning');

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
    function updatePlanning($planning_type)
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

                        return redirect()->to('/helpdesk/home/updatePlanning/'.$planning_type);
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

                    return redirect()->to('/helpdesk/home/updatePlanning/'.$planning_type);
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
     * [DOES NOT HAVE AN ACTUAL USE FOR NOW] Displays the menu of a technician.
     * 
     * @param int $user_id ID of the currently logged user
     * 
     * @return view
     * 
     */
    public function technicianMenu($user_id)
    {
        $this->isUserLogged();

        $data['user'] = $this->user_data_model->getUserData($user_id);

        $data['title'] = lang('Helpdesk.ttl_technician_menu');

        return $this->display_view('Helpdesk\technician_menu', $data);
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
    public function deleteTechnician($user_id, $planning_type)
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

                    return redirect()->to('helpdesk/home/planning');

                case 1:
                    $id_planning = $this->nw_planning_model->getNwPlanning($user_id);

                    $this->nw_planning_model->delete($id_planning);

                    return redirect()->to('helpdesk/home/nw_planning');

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


    /**
     * Displays the holidays list.
     * 
     * @return view
     * 
     */
    public function holidays()
    {
        $data['messages'] = $this->getFlashdataMessages();

        $data['holidays_data'] = $this->holidays_model->getHolidays();

        $data['title'] = lang('Helpdesk.ttl_holiday');

        return $this->display_view('Helpdesk\holidays', $data);
    }


    /**
     * Displays the page to add/modifiy a holiday entry, and anages the post of the data.
     * 
     * @param int $id_holiday ID of the holiday, default value = 0
     * 
     * @return view
     * 
     */
    public function saveHoliday($id_holiday = 0)
    {
        $this->isUserLogged();

        $datetime_error = false;

        if($_POST)
        {
            try
            {
                $start_date = new DateTime($_POST['start_date']);
                $end_date = new DateTime($_POST['end_date']);
            }

            catch(\Exception)
            {
                $datetime_error = true;
            }

            if($datetime_error == true || $end_date < $start_date)
            {
                $data['error'] = lang('Helpdesk.err_dates_are_incoherent');

                if(isset($id_holiday) && $id_holiday != 0)
                {
                    $form_data =
                    [
                        'id_holiday' => $_POST['id_holiday'],
                        'name_holiday' => esc($_POST['holiday_name']),
                        'start_date_holiday' => $_POST['start_date'],
                        'end_date_holiday' => $_POST['end_date'],
                    ];

                    $data['title'] = lang('Helpdesk.ttl_update_holiday');
                }

                else
                {
                    if(isset($data['error']))
                    {
                        $form_data =
                        [
                            'name_holiday' => trim(esc($_POST['holiday_name'])),
                            'start_date_holiday' => $_POST['start_date'],
                            'end_date_holiday' => $_POST['end_date'],
                        ];

                        $data['title'] = lang('Helpdesk.ttl_add_holiday');
                    }
                }

                $data['holiday'] = $form_data;

                return $this->display_view('Helpdesk\add_holiday', $data);
            }

            // No error occured, entry creation
            else
            {
                $data =
                [
                    'id_holiday' => $_POST['id_holiday'],
                    'name_holiday' => trim(esc($_POST['holiday_name'])),
                    'start_date_holiday' => $_POST['start_date'],
                    'end_date_holiday' => $_POST['end_date'],
                ];

                $this->holidays_model->save($data);

                $this->session->setFlashdata('success', lang('Helpdesk.scs_holiday_updated'));

                return redirect()->to('/helpdesk/home/holidays');
            }
        }

        // No data is sent
        else
        {
            if(isset($id_holiday) && $id_holiday != 0)
            {
                $data['holiday'] = $this->holidays_model->getHoliday($id_holiday);

                $data['title'] = lang('Helpdesk.ttl_update_holiday');
            }

            else
            {
                $data['title'] = lang('Helpdesk.ttl_add_holiday');

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

            return $this->display_view('Helpdesk\add_holiday', $data);
        }
    }


    /**
     * Displays the page to deletes the vacation entry, and does the suppression of the entry.
     * 
     * @param int $id_holiday ID of the holiday
     * 
     * @return view
     * 
     */
    public function deleteHoliday($id_holiday)
    {
        $this->isUserLogged();

        // If the users confirms the deletion
        if(isset($_POST['delete_confirmation']) && $_POST['delete_confirmation'] == true)
        {
            $this->holidays_model->delete($id_holiday);

            $this->session->setFlashdata('success', lang('Helpdesk.scs_holiday_deleted'));

            return redirect()->to('/helpdesk/home/holidays');
        }

        // When the user clicks the delete button
        else
        {
            $data['id_holiday'] = $id_holiday;

            $data['title'] = lang('Helpdesk.ttl_delete_confirmation');

            return $this->display_view('Helpdesk\delete_holiday', $data);
        }
    }


    /**
     * Displays the technicians assigned at the moment
     * 
     * @param string $error Error message, default value : NULL
     * 
     * @return view
     * 
     */
    public function terminal()
    {
        $data = $this->getFlashdataMessages();

        $isDayOff = $this->holidays_model->areWeInHolidays();

        if($isDayOff)
        {
            $data['day_off'] = true;
        }

        else
        {
            $data['day_off'] = false;

            $time =
            [
                'day'       => substr(strtolower(date('l', time())), 0, 3),
                'period'    => '', // Will be set later
                'hh:mm'     => strtotime(date('H:i', time())),
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
            }

            // If we are in work timetables
            if(isset($time['period']))
            {
                // Constructs the period name
                $sql_name_period = 'planning_'.$time['day'].'_'.$time['period'];

                $data['technicians'] = $this->planning_model->getTechniciansOnPeriod($sql_name_period);

                if(isset($data['technicians']) && !empty($data['technicians']))
                {
                    $data['period'] = $sql_name_period;
                    
                    // Resets the availabilities on the beginning of new periods
                    if ($time['hh:mm'] == strtotime("08:00") ||
                        $time['hh:mm'] == strtotime("10:00") ||
                        $time['hh:mm'] == strtotime("12:45") ||
                        $time['hh:mm'] == strtotime("15:00"))
                    {
                        $this->terminal_model->ResetAvailabilities();
                    }

                    $roles_assigned = [];

                    foreach($data['technicians'] as $technician)
                    {
                        array_push($roles_assigned, $technician[$sql_name_period]);
                    }

                    for($role = 1; $role <= 3; $role++)
                    {
                        if(!in_array($role, $roles_assigned))
                        {
                            $this->terminal_model->updateAvailability($role, false);
                        }
                    }
                }

                $data['technicians_availability'] = $this->terminal_model->getTerminalData();
            }
        }

        //$data['title'] = lang('Helpdesk.ttl_welcome_to_helpdesk');

        return $this->display_view('Helpdesk\terminal', $data);
    }


    /**
     * Changes technician availability on terminal
     * 
     * @param int $technician_type Role of the technician updated
     * 
     * @return view
     * 
     */
    public function updateTechnicianAvailability($technician_type)
    {
        $error = NULL;

        if(isset($technician_type) && !empty($technician_type))
        {
            $technicians_availability = $this->terminal_model->getTerminalData();

            switch($technician_type)
            {
                case 1:
                    $index = 0;
                    break;

                case 2:
                    $index = 1;
                    break;

                case 3:
                    $index = 2;
                    break;

                default:
                    $error = lang('Helpdesk.err_unvalid_technician_selected');
            }

            $role = $index+1;

            $this->terminal_model->updateAvailability($role, !$technicians_availability[$index]['tech_available_terminal']);
        }

        else
        {
            $error = lang('Helpdesk.err_no_technician_selected');
        }

        // Refreshes the terminal
        $this->terminal($error);
    }


    /**
     * Start the planning generation process
     * 
     * @return view
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
        return $this->display_view('Helpdesk\generate_planning', $data);
    }
}