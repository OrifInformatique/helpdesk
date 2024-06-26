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

use Helpdesk\Models\Lw_planning_model;
use Helpdesk\Models\Planning_model;
use Helpdesk\Models\Nw_planning_model;

use Helpdesk\Models\Holidays_model;
use Helpdesk\Models\Presences_model;
use Helpdesk\Models\Terminal_model;

use Helpdesk\Models\User_data_model;

class Home extends BaseController
{
    protected $session;

    protected $lw_planning_model;
    protected $planning_model;
    protected $nw_planning_model;

    protected $holidays_model;
    protected $presences_model;
    protected $terminal_model;

    protected $user_data_model;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session = \Config\Services::session();

        $this->lw_planning_model = new lw_planning_model();
        $this->planning_model = new planning_model();
        $this->nw_planning_model = new nw_planning_model();

        $this->holidays_model = new holidays_model();
        $this->presences_model = new presences_model();
        $this->terminal_model = new terminal_model();

        $this->user_data_model = new user_data_model();

        helper('form');
    }


    /**
     * Default function, displays the planning page.
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
     * Set often used variables in session for global access.
     * 
     * @return void
     * 
     */
    protected function setSessionVariables()
    {
        if(!isset($_SESSION['helpdesk']['next_week']) ||
            !isset($_SESSION['helpdesk']['lw_periods']) ||
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
                
                // SQL names of last week's planning periods (lw)
                'lw_periods' => 
                [
                    'lw_planning_mon_m1', 'lw_planning_mon_m2', 'lw_planning_mon_a1', 'lw_planning_mon_a2',
                    'lw_planning_tue_m1', 'lw_planning_tue_m2', 'lw_planning_tue_a1', 'lw_planning_tue_a2',
                    'lw_planning_wed_m1', 'lw_planning_wed_m2', 'lw_planning_wed_a1', 'lw_planning_wed_a2',
                    'lw_planning_thu_m1', 'lw_planning_thu_m2', 'lw_planning_thu_a1', 'lw_planning_thu_a2',
                    'lw_planning_fri_m1', 'lw_planning_fri_m2', 'lw_planning_fri_a1', 'lw_planning_fri_a2',
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
    protected function isUserLogged()
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
     * Evalautes if the user logged is a technician, in order to prevent guests from altering the data.
     * 
     * @return bool
     * 
     */
    public function isTechnician()
    {
        $access_level = $_SESSION['user_access'];

        // 1 corresponds to the guest user type access level
        if($access_level == 1)
        {
            $this->session->setFlashdata('error', lang('Errors.unauthorized'));
            return false;
        }

        return true;
    }
    
    
    /**
     * Evalautes if the user logged has admin rights.
     * 
     * @return bool
     * 
     */
    public function isAdmin()
    {
        $access_level = $_SESSION['user_access'];

        return $access_level == 4;
    }


    /**
     * Checks if the planning edited is correct.
     * 
     * @param int $planning_type Specifies which planning is being edited
     * 
     * @return view|void
     * 
     */
    protected function isSetPlanningType($planning_type)
    {
        if(!in_array($planning_type, [-1,0,1]))
        {
            $this->session->setFlashdata('error', lang('Errors.unvalid_planning_type'));

            return $this->index();
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
    protected function defineDaysOff($periods)
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
     * @param int $planning_type
     *  
     * @return array
     * 
     */
    protected function choosePeriods($planning_type)
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
                foreach($_SESSION['helpdesk']['next_week'] as $key => $day)
                {
                    $periods +=
                    [
                        substr($key, 0, 3).'-m1' => [
                            'start' => strtotime(date('Y-m-d', $day).' 08:00:00'),
                            'end'   => strtotime(date('Y-m-d', $day).' 10:00:00')
                        ],
                        substr($key, 0, 3).'-m2' => [
                            'start' => strtotime(date('Y-m-d', $day).' 10:00:00'),
                            'end'   => strtotime(date('Y-m-d', $day).' 12:00:00')
                        ],
                        substr($key, 0, 3).'-a1' => [
                            'start' => strtotime(date('Y-m-d', $day).' 12:45:00'),
                            'end'   => strtotime(date('Y-m-d', $day).' 14:45:00')
                        ],
                        substr($key, 0, 3).'-a2' => [
                            'start' => strtotime(date('Y-m-d', $day).' 15:00:00'),
                            'end'   => strtotime(date('Y-m-d', $day).' 16:57:00')
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
    protected function getFlashdataMessages()
    {
        $messages['success'] = $this->session->getFlashdata('success');
        $messages['error'] = $this->session->getFlashdata('error');
        
        return $messages;
    }


    /**
     * Display a confirmation page for a specified action (no entry deletion)
     * 
     * @param string $action Action that we want to do
     * 
     * @return view
     * 
     */
    public function confirm_action($action = null)
    {
        $this->isUserLogged();

        switch($action)
        {
            case 'shift_weeks':
                if(!$this->isTechnician())
                    return redirect()->to('/helpdesk/planning/nw_planning');
                
                $action =
                [
                    'name' => 'shift_weeks_with_planning_generation',
                    'css' => 'shift-weeks-with-planning-generation',
                    'url' => base_url('helpdesk/planning/shift_weeks/true'),
                    'desc' => lang('MiscTexts.generated_planning_overwrite_old_one')
                ];

                $alt_action = 
                [
                    'name' => 'shift_weeks',
                    'css' => 'shift-weeks',
                    'url' => base_url('helpdesk/planning/shift_weeks')
                ];

                $irreversible_action = true;
                $back_btn_url = base_url('helpdesk/planning/nw_planning');
                break;

            case 'generate_planning':
                if(!$this->isTechnician())
                    return redirect()->to('/helpdesk/planning/nw_planning');

                $action = 
                [
                    'name' => 'generate_planning',
                    'css' => 'generate-planning',
                    'url' => base_url('helpdesk/planning/planning_generation'),
                    'desc' => lang('MiscTexts.generated_planning_overwrite_old_one')
                ];
                
                $alt_action = null;

                $irreversible_action = true;

                $back_btn_url = base_url('helpdesk/planning/nw_planning');
                break;

            default:
                $this->session->setFlashData('error', lang('Errors.action_unvalid'));
                return redirect()->to('helpdesk/planning/cw_planning');
        }

        $data = 
        [
            'action' => $action,
            'alt_action' => $alt_action,
            'back_btn_url' => $back_btn_url,
            'irreversible_action' => $irreversible_action ?? false,
            'title' => lang('Titles.confirm_action')
        ];

        return $this->display_view('Helpdesk\confirm_action', $data);
    }


    /**
     * Displays the assistance page
     * 
     * @return view
     * 
     */
    public function assistance()
    {
        $data['assistances'] =
        [
            'roles' => 
            [
                'summary' => lang('Assistance.roles_summary'),
                'details' => lang('Assistance.roles_details'),
            ],

            'presences' => 
            [
                'summary' => lang('Assistance.presences_summary'),
                'details' => lang('Assistance.presences_details'),
            ],

            'mentor' => 
            [
                'summary' => lang('Assistance.mentor_summary'),
                'details' => lang('Assistance.mentor_details'),
            ]
        ];

        $data['title'] = lang('Titles.assistance');

        return $this->display_view('Helpdesk\assistance', $data);
    }


    /**
     * Get a specified image and returns it.
     *
     * @param string $file_name
     * 
     * @return ResponseInterface Technician photo
     * 
     */
    public function showTechnicianPhoto($file_name)
    {
        $file_path = WRITEPATH.'uploads/images/'.$file_name;

        $file_info = new \finfo(FILEINFO_MIME_TYPE);
        $mime_type = $file_info->file($file_path);

        return $this->response->setHeader('Content-Type', $mime_type)
                              ->setBody(file_get_contents($file_path))
                              ->send();
    }
}