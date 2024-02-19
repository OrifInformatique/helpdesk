<?php

/**
 * Controller for technicians presences
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

class Presences extends Home
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }


    /**
     * Default function, displays the presences of all technicians.
     * 
     * @return view
     * 
     */
    public function index()
    {
        $this->setSessionVariables();

        return redirect()->to('/helpdesk/presences/presences_list');
    }


    /** ********************************************************************************************************************************* */


    /**
     * Displays the presences of all technicians.
     * 
     * @return view
     * 
     */
    public function presences_list()
    {
        $this->setSessionVariables();

        // 0 stands for current week
        $periods = $this->choosePeriods(0);

        $data =
        [
            'messages'            => $this->getFlashdataMessages(),
            'all_users_presences' => $this->presences_model->getAllPresences(),
            'classes'             => $this->defineDaysOff($periods),
            'title'               => lang('Helpdesk.ttl_presences_list')
        ];

        return $this->display_view('Helpdesk\presences_list', $data);
    }

    /**
     * Adds the presences of a technician
     * 
     * @return view
     * 
     */
    public function add_technician_presences()
    {
        $this->isUserLogged();
        $this->setSessionVariables();

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $user_id = $_POST['technician'];

            if(!isset($user_id) || empty($user_id) || !is_numeric($user_id))
                $data['messages']['error'] = lang('Helpdesk.err_unvalid_technician_selected');

            else
                return redirect()->to('/helpdesk/presences/technician_presences/'.$user_id);
        }

        $data['users'] = $this->user_data_model->getUsersWithoutPresences();
        $data['title'] = lang('Helpdesk.ttl_add_technician_presences');

        return $this->display_view('Helpdesk\add_technician_presences', $data);
    }

    /**
     * Displays the page letting users modify their presences, and manages the post of the data.
     * 
     * @return view
     * 
     */
    public function technician_presences($user_id = NULL)
    {
        $this->isUserLogged();
        $this->setSessionVariables();

        if(!isset($user_id) || empty($user_id) || !is_numeric($user_id))
        {
            $this->session->setFlashdata('error', lang('Helpdesk.err_unvalid_technician_selected'));

            return redirect()->to('/helpdesk/presences/presences_list');
        }

        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $id_presence = $this->presences_model->getPresenceId($user_id);

            foreach ($_SESSION['helpdesk']['presences_periods'] as $field)
            {
                if (!isset($_POST[$field]) || empty($_POST[$field]) || !in_array($_POST[$field], [1, 2, 3]))
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

        if($user_id == $_SESSION['user_id'])
        {
            $data['title'] = lang('Helpdesk.ttl_my_presences');
            $data['user_id'] = $_SESSION['user_id'];
        }

        else
        {
            $technician_fullname = $this->user_data_model->getUserFullName($user_id);
            $technician_fullname = $technician_fullname['first_name_user_data'].' '.$technician_fullname['last_name_user_data'];
            $data['title'] = sprintf(lang('Helpdesk.ttl_technician_presences'), $technician_fullname);
            $data['user_id'] = $user_id;
        }

        return $this->display_view('Helpdesk\technician_presences', $data);
    }


    /**
     * Displays the presence delete confirm page, and does the suppression of the entry.
     * 
     * @param int $id_presence ID of the presence entry
     * 
     * @return view
     * 
     */
    public function delete_presences($id_presence)
    {
        $this->isUserLogged();

        // If the users confirms the deletion
        if(isset($_POST['delete_confirmation']) && $_POST['delete_confirmation'])
        {
            $this->presences_model->delete($id_presence);

            $this->session->setFlashdata('success', lang('Helpdesk.scs_presences_deleted'));

            return redirect()->to('/helpdesk/presences/presences_list');
        }

        // When the user clicks the delete button
        else
        {
            $user_id = $this->presences_model->getUserId($id_presence);
            $user_fullname = $this->user_data_model->getUserFullName($user_id);

            $presence_entry = lang('Helpdesk.technician_presences').' <strong>'.implode(' ', $user_fullname).'</strong>.';

            $data = 
            [
                'title'         => lang('Helpdesk.ttl_delete_confirmation'),
                'delete_url'    => base_url('/helpdesk/presences/delete_presences/'.$id_presence),
                'btn_back_url'  => base_url('/helpdesk/presences/presences_list'),
                'entry'         => $presence_entry
            ];

            return $this->display_view('Helpdesk\delete_entry', $data);
        }
    }
}