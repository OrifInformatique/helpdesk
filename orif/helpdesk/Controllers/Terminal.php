<?php

/**
 * Controller for terminal
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

class Terminal extends Home
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
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

        return redirect()->to('/helpdesk/terminal/display');
    }


    /** ********************************************************************************************************************************* */


    /**
     * Displays the terminal.
     * 
     * @return view
     * 
     */
    public function display()
    {
        $data = $this->getFlashdataMessages();

        $isDayOff = $this->holidays_model->areWeInHolidays();

        if($isDayOff)
            $data['day_off'] = true;

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
                        array_push($roles_assigned, $technician[$sql_name_period]);

                    for($role = 1; $role <= 3; $role++)
                        if(!in_array($role, $roles_assigned))
                            $this->terminal_model->updateAvailability($role, false);
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
    public function update_technician_availability($technician_type)
    {
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
                    $this->session->setFlashdata('error', lang('Helpdesk.err_unvalid_technician_selected'));
            }

            $role = $index+1;
            $this->terminal_model->updateAvailability($role, !$technicians_availability[$index]['tech_available_terminal']);
        }

        else
            $this->session->setFlashdata('error', lang('Helpdesk.err_no_technician_selected'));

        // Refreshes the terminal
        return redirect()->to('/helpdesk/terminal/display');
    }
}