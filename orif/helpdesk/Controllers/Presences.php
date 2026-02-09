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
use Helpdesk\Enums\TechnicianPresence;

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

        return redirect()->to('/helpdesk/presences/presencesList');
    }


    /** ********************************************************************************************************************************* */


    /**
     * Displays the presences of all technicians.
     * 
     * @return view
     * 
     */
    public function presencesList()
    {
        $this->setSessionVariables();

        // 0 stands for current week
        $periods = $this->choosePeriods(0);

        $data =
        [
            'messages'            => $this->getFlashdataMessages(),
            'all_users_presences' => $this->presences_model->getAllPresences(),
            'classes'             => $this->defineDaysOff($periods),
            'title'               => lang('Titles.presences_list')
        ];

        return $this->display_view('Helpdesk\presences_list', $data);
    }

    /**
     * Adds the presences of a technician
     * 
     * @return view
     * 
     */
    public function addTechnicianPresences()
    {
        $this->setSessionVariables();
        $this->isUserLogged();

        if(!$this->isTechnician())
            return redirect()->to('/helpdesk/presences/presencesList');

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            
            $user_id = $_POST['technician'];

            if(!isset($user_id) || empty($user_id) || !is_numeric($user_id))
                $data['messages']['error'] = lang('Errors.invalid_technician_selected');

            else
                return redirect()->to('/helpdesk/presences/technicianPresences/'.$user_id);
        }

        $data['users'] = $this->user_data_model->getUsersWithoutPresences();
        $data['title'] = lang('Titles.add_technician_presences');

        return $this->display_view('Helpdesk\add_technician_presences', $data);
    }

    /**
     * Displays the page letting users modify their presences, and manages the post of the data.
     * 
     * @return view
     * 
     */
    public function technicianPresences($user_id = NULL)
    {
        $this->isUserLogged();
        $this->setSessionVariables();

        if(!isset($user_id) || empty($user_id) || !is_numeric($user_id))
        {
            $this->session->setFlashdata('error', lang('Errors.invalid_technician_selected'));

            return redirect()->to('/helpdesk/presences/presencesList');
        }

        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if(!$this->isTechnician())
                return redirect()->to(uri_string());

            // Get existing presence record
            $presence_record = $this->presences_model->getPresenceId($user_id);
            
            // Extract presence ID if record exists
            $id_presence = null;
            if ($presence_record !== null) {
                // If it's an object, extract the ID
                if (is_object($presence_record)) {
                    $id_presence = $presence_record->id_presence ?? null;
                } 
                // If it's an array, extract the ID
                elseif (is_array($presence_record)) {
                    $id_presence = $presence_record['id_presence'] ?? null;
                }
            }

            // Delete existing duplicates for this user before update
            if ($id_presence !== null) {
                // Delete all other entries for this user (keep only the one with the retrieved ID)
                $this->presences_model->where('fk_user_id', $user_id)
                                      ->where('id_presence !=', $id_presence)
                                      ->delete();
            } else {
                // If there's no record, delete all potential duplicates
                $existing_presences = $this->presences_model->where('fk_user_id', $user_id)->findAll();
                if (count($existing_presences) > 0) {
                    // Keep only the first one and delete the others
                    $first_id = is_object($existing_presences[0]) ? $existing_presences[0]->id_presence : $existing_presences[0]['id_presence'];
                    $this->presences_model->where('fk_user_id', $user_id)
                                          ->where('id_presence !=', $first_id)
                                          ->delete();
                    $id_presence = $first_id;
                }
            }

            foreach ($_SESSION['helpdesk']['presences_periods'] as $field)
            {
                if (!isset($_POST[$field]) || empty($_POST[$field]) || !in_array($_POST[$field], [TechnicianPresence::PRESENT->value, TechnicianPresence::PARTLY_ABSENT->value, TechnicianPresence::ABSENT->value]))
                {
                    // Default value is set to "Absent"
                    $_POST[$field] = TechnicianPresence::ABSENT->value;
                }
            }

            $data_to_save =
            [
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

            // Add ID only if updating an existing record
            if ($id_presence !== null) {
                $data_to_save['id_presence'] = $id_presence;
            }

            $this->presences_model->save($data_to_save);

            $data['messages']['success'] = lang('Success.presences_updated');
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
            $data['title'] = lang('Titles.my_presences');
            $data['user_id'] = $_SESSION['user_id'];
        }

        else
        {
            $technician_fullname = $this->user_data_model->getUserFullName($user_id);
            $technician_fullname = $technician_fullname['first_name_user_data'].' '.$technician_fullname['last_name_user_data'];
            $data['title'] = sprintf(lang('Titles.technician_presences'), $technician_fullname);
            $data['user_id'] = $user_id;
        }

        if(!isset($data['messages']))
            $data['messages'] = $this->getFlashdataMessages();

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
    public function deletePresences($id_presence)
    {
        $this->isUserLogged();

        if(!$this->isTechnician())
            return redirect()->to('/helpdesk/presences/presencesList');

        // If the users confirms the deletion
        if(isset($_POST['delete_confirmation']) && $_POST['delete_confirmation'])
        {
            $this->presences_model->delete($id_presence);

            $this->session->setFlashdata('success', lang('Success.presences_deleted'));

            return redirect()->to('/helpdesk/presences/presencesList');
        }

        // When the user clicks the delete button
        else
        {
            $user_id = $this->presences_model->getUserId($id_presence);
            $user_fullname = $this->user_data_model->getUserFullName($user_id);

            $presence_entry = lang('MiscTexts.presences_of').' <strong>'.implode(' ', $user_fullname).'</strong>.';

            $data = 
            [
                'title'         => lang('Titles.delete_confirmation'),
                'delete_url'    => base_url('/helpdesk/presences/deletePresences/'.$id_presence),
                'btn_back_url'  => base_url('/helpdesk/presences/presencesList'),
                'entry'         => $presence_entry
            ];

            return $this->display_view('Helpdesk\delete_entry', $data);
        }
    }
}