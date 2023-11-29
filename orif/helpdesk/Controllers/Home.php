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
use Psr\Log\LoggerInterface;

use Helpdesk\Models\terminal_model;
use Helpdesk\Models\holidays_model;
use Helpdesk\Models\Planning_model;
use Helpdesk\Models\User_Data_model;
use Helpdesk\Models\Presences_model;

class Home extends BaseController
{
    protected $session;
    protected $user_data_model;
    protected $holidays_model;
    protected $terminal_model;
    protected $planning_model;
    protected $presences_model;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session = \Config\Services::session();

        $this->holidays_model = new holidays_model();
        $this->terminal_model = new terminal_model();
        $this->planning_model = new planning_model();
        $this->user_data_model = new user_data_model();
        $this->presences_model = new presences_model();
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

        return redirect()->to('/helpdesk/planning/cw_planning');
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
                ]
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
     * @return array
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
            // FIX : PREVOIR QUE CETTE FONCTION PEUT RETOURNER NULL
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
}