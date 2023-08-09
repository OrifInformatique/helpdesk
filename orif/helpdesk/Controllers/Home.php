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
use Psr\Log\LoggerInterface;
use Helpdesk\Models\Presence_model;
use Helpdesk\Models\Planning_model;
use Helpdesk\Models\User_Data_model;

class Home extends BaseController
{

    protected $session;
    protected $presence_model;
    protected $planning_model;
    protected $user_data_model;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
        $this->presence_model = new Presence_model();
        $this->planning_model = new Planning_model();
        $this->user_data_model = new User_Data_model();

        helper('form');
    }


    /*
    ** index function
    **
    ** Default function, displays the planning page
    **
    */
    public function index()
    {
        // Page title
        $data['title'] = lang('Helpdesk.ttl_helpdesk');

        // Retrieves users having a planning
        $planning_data = $this->planning_model->getPlanningDataByUser();

        $data['planning_data'] = $planning_data;

        // Presences table
        $data['periodes'] = 
        [
            'planning_lundi_m1', 'planning_lundi_m2', 'planning_lundi_a1', 'planning_lundi_a2',
            'planning_mardi_m1', 'planning_mardi_m2', 'planning_mardi_a1', 'planning_mardi_a2',
            'planning_mercredi_m1', 'planning_mercredi_m2', 'planning_mercredi_a1', 'planning_mercredi_a2',
            'planning_jeudi_m1', 'planning_jeudi_m2', 'planning_jeudi_a1', 'planning_jeudi_a2',
            'planning_vendredi_m1', 'planning_vendredi_m2', 'planning_vendredi_a1', 'planning_vendredi_a2',
        ];

        // Displays schedule page
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
    ** presence function
    **
    ** Displays the presence page
    **
    */
    public function presence()
    {
        // Checks whether the user is logged
        $this->isUserLogged();

        // Page title
        $data['title'] = lang('Helpdesk.ttl_apprentice_presences');

        // Retrieves user ID
        $user_id = $_SESSION['user_id'];

        // Retrieves user presences
        $presences_data = $this->presence_model->getPresencesUser($user_id);

        // Add presences to $data
        $data = $presences_data;

        // Form fields table
        $form_fields_data = [
            'lundi_debut_matin','lundi_fin_matin','lundi_debut_apres_midi','lundi_fin_apres_midi',
            'mardi_debut_matin','mardi_fin_matin','mardi_debut_apres_midi','mardi_fin_apres_midi',
            'mercredi_debut_matin','mercredi_fin_matin','mercredi_debut_apres_midi','mercredi_fin_apres_midi',
            'jeudi_debut_matin','jeudi_fin_matin','jeudi_debut_apres_midi','jeudi_fin_apres_midi',
            'vendredi_debut_matin','vendredi_fin_matin','vendredi_debut_apres_midi','vendredi_fin_apres_midi',
        ];

        // Add form fields to $data
        $data = $form_fields_data;

        // Displays presences form page
        $this->display_view('Helpdesk\presence', $data);

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
        $id_presence = $this->presence_model->getPresenceId($user_id);

        // Form fields table
        $form_fields_data = [
            'lundi_debut_matin','lundi_fin_matin','lundi_debut_apres_midi','lundi_fin_apres_midi',
            'mardi_debut_matin','mardi_fin_matin','mardi_debut_apres_midi','mardi_fin_apres_midi',
            'mercredi_debut_matin','mercredi_fin_matin','mercredi_debut_apres_midi','mercredi_fin_apres_midi',
            'jeudi_debut_matin','jeudi_fin_matin','jeudi_debut_apres_midi','jeudi_fin_apres_midi',
            'vendredi_debut_matin','vendredi_fin_matin','vendredi_debut_apres_midi','vendredi_fin_apres_midi',
        ];

        // TODO : Take Absent state value from database
        // Add default value if the field is empty
        foreach ($form_fields_data as $field)
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

            'presences_lundi_m1' => $_POST['lundi_debut_matin'],
            'presences_lundi_m2' => $_POST['lundi_fin_matin'],
            'presences_lundi_a1' => $_POST['lundi_debut_apres_midi'],
            'presences_lundi_a2' => $_POST['lundi_fin_apres_midi'],

            'presences_mardi_m1' => $_POST['mardi_debut_matin'],
            'presences_mardi_m2' => $_POST['mardi_fin_matin'],
            'presences_mardi_a1' => $_POST['mardi_debut_apres_midi'],
            'presences_mardi_a2' => $_POST['mardi_fin_apres_midi'],

            'presences_mercredi_m1' => $_POST['mercredi_debut_matin'],
            'presences_mercredi_m2' => $_POST['mercredi_fin_matin'],
            'presences_mercredi_a1' => $_POST['mercredi_debut_apres_midi'],
            'presences_mercredi_a2' => $_POST['mercredi_fin_apres_midi'],

            'presences_jeudi_m1' => $_POST['jeudi_debut_matin'],
            'presences_jeudi_m2' => $_POST['jeudi_fin_matin'],
            'presences_jeudi_a1' => $_POST['jeudi_debut_apres_midi'],
            'presences_jeudi_a2' => $_POST['jeudi_fin_apres_midi'],

            'presences_vendredi_m1' => $_POST['vendredi_debut_matin'],
            'presences_vendredi_m2' => $_POST['vendredi_fin_matin'],
            'presences_vendredi_a1' => $_POST['vendredi_debut_apres_midi'],
            'presences_vendredi_a2' => $_POST['vendredi_fin_apres_midi']
        ];

        // Do the inset/update on the database
        $this->presence_model->save($data);

        // Select user presences
        $presences_data = $this->presence_model->getPresencesUser($user_id);

        $data = $presences_data;

        // Success message
        $data['success'] = lang('Helpdesk.scs_presences_updated');

        // Displays presences page
        $this->display_view('Helpdesk\presence', $data);
    }

    /*
    ** ajouterTechnicien function
    **
    ** Displays the ajouter_technicien page
    ** Save form inputs from ajouter_technicien
    **
    */
    function ajouterTechnicien()
    {
        // Checks whether user is logged in
        $this->isUserLogged();

        // Page title
        $data['title'] = lang('Helpdesk.ttl_add_technician');

        // Retrieve all users data from database
        $data['users'] = $this->user_data_model->getUsersData();

        // Table to identify presences on next page
        $data['presences'] = 
        [
            'lundi_debut_matin','lundi_fin_matin','lundi_debut_apres_midi','lundi_fin_apres_midi',
            'mardi_debut_matin','mardi_fin_matin','mardi_debut_apres_midi','mardi_fin_apres_midi',
            'mercredi_debut_matin','mercredi_fin_matin','mercredi_debut_apres_midi','mercredi_fin_apres_midi',
            'jeudi_debut_matin','jeudi_fin_matin','jeudi_debut_apres_midi','jeudi_fin_apres_midi',
            'vendredi_debut_matin','vendredi_fin_matin','vendredi_debut_apres_midi','vendredi_fin_apres_midi',
        ];

        // If the "add technician" button from planning page is pressed
        if (empty($_POST))
        {
            // Displays ajouter_technicien page
            return $this->display_view('Helpdesk\ajouter_technicien', $data);
        }

        // Retrieve user ID from the "technicien" field
        $user_id = $_POST['technicien'];
        
        // Checks whether the user already has a schedule
        $data['error'] = $this->planning_model->checkUserOwnsPlanning($user_id);
        
        // If $data['error'] isn't empty, the user already has a schedule
        if (!empty($data['error']))
        {
            // Displays the same page, with an error message
            return $this->display_view('Helpdesk\ajouter_technicien', $data);
        }

        // Form fields table
        $form_fields_data = [
            'lundi_debut_matin','lundi_fin_matin','lundi_debut_apres_midi','lundi_fin_apres_midi',
            'mardi_debut_matin','mardi_fin_matin','mardi_debut_apres_midi','mardi_fin_apres_midi',
            'mercredi_debut_matin','mercredi_fin_matin','mercredi_debut_apres_midi','mercredi_fin_apres_midi',
            'jeudi_debut_matin','jeudi_fin_matin','jeudi_debut_apres_midi','jeudi_fin_apres_midi',
            'vendredi_debut_matin','vendredi_fin_matin','vendredi_debut_apres_midi','vendredi_fin_apres_midi',
        ];

        // Variable for empty fields count
        $emptyFields = 0;

        // Add default values il field is empty
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

        // If 20 fields are empty, means all fields are empty. Cannot add a technician without role
        if ($emptyFields === 20)
        {
            // Error message
            $data['error'] = lang('Helpdesk.err_technician_must_be_assigned_to_schedule');

            // Displays the same page, with an error message
            return $this->display_view('Helpdesk\ajouter_technicien', $data);     
        }

        // Prepare planning to record
        $data = 
        [
            'fk_user_id' => $user_id,

            'planning_lundi_m1' => $_POST['lundi_debut_matin'],
            'planning_lundi_m2' => $_POST['lundi_fin_matin'],
            'planning_lundi_a1' => $_POST['lundi_debut_apres_midi'],
            'planning_lundi_a2' => $_POST['lundi_fin_apres_midi'],

            'planning_mardi_m1' => $_POST['mardi_debut_matin'],
            'planning_mardi_m2' => $_POST['mardi_fin_matin'],
            'planning_mardi_a1' => $_POST['mardi_debut_apres_midi'],
            'planning_mardi_a2' => $_POST['mardi_fin_apres_midi'],

            'planning_mercredi_m1' => $_POST['mercredi_debut_matin'],
            'planning_mercredi_m2' => $_POST['mercredi_fin_matin'],
            'planning_mercredi_a1' => $_POST['mercredi_debut_apres_midi'],
            'planning_mercredi_a2' => $_POST['mercredi_fin_apres_midi'],

            'planning_jeudi_m1' => $_POST['jeudi_debut_matin'],
            'planning_jeudi_m2' => $_POST['jeudi_fin_matin'],
            'planning_jeudi_a1' => $_POST['jeudi_debut_apres_midi'],
            'planning_jeudi_a2' => $_POST['jeudi_fin_apres_midi'],

            'planning_vendredi_m1' => $_POST['vendredi_debut_matin'],
            'planning_vendredi_m2' => $_POST['vendredi_fin_matin'],
            'planning_vendredi_a1' => $_POST['vendredi_debut_apres_midi'],
            'planning_vendredi_a2' => $_POST['vendredi_fin_apres_midi']
        ];

        // Insert data into "tbl_planning" table
        $this->planning_model->insert($data);

        // Success message
        $data['success'] = lang('Helpdesk.scs_technician_added_to_schedule');

        /*
        ** index() function copy
        ** (Repetion is needed)
        */
        
        // Page title
        $data['title'] = lang('Helpdesk.ttl_helpdesk');

        // Retrieves users having a schedule
        $planning_data = $this->planning_model->getPlanningDataByUser();

        $data['planning_data'] = $planning_data;

        // Presences table
        $data['periodes'] = 
        [
            'planning_lundi_m1', 'planning_lundi_m2', 'planning_lundi_a1', 'planning_lundi_a2',
            'planning_mardi_m1', 'planning_mardi_m2', 'planning_mardi_a1', 'planning_mardi_a2',
            'planning_mercredi_m1', 'planning_mercredi_m2', 'planning_mercredi_a1', 'planning_mercredi_a2',
            'planning_jeudi_m1', 'planning_jeudi_m2', 'planning_jeudi_a1', 'planning_jeudi_a2',
            'planning_vendredi_m1', 'planning_vendredi_m2', 'planning_vendredi_a1', 'planning_vendredi_a2',
        ];

        // Displays schedule page
        $this->display_view('Helpdesk\planning', $data);
    }

    
    /*
    ** modificationPlanning function
    **
    ** Displays the modification_planning page
    ** Saves form data from the modification_planning page
    **
    */
    function modificationPlanning()
    {
        // Checks whether user is logged in
        $this->isUserLogged();
        
        if ($_POST)
        {

            $updated_planning_data = 
            [

                'planning_lundi_m1' => $_POST['planning_lundi_m1'],
                'planning_lundi_m2' => $_POST['planning_lundi_m2'],
                'planning_lundi_a1' => $_POST['planning_lundi_a1'],
                'planning_lundi_a2' => $_POST['planning_lundi_a2'],

                'planning_mardi_m1' => $_POST['planning_mardi_m1'],
                'planning_mardi_m2' => $_POST['planning_mardi_m2'],
                'planning_mardi_a1' => $_POST['planning_mardi_a1'],
                'planning_mardi_a2' => $_POST['planning_mardi_a2'],

                'planning_mercredi_m1' => $_POST['planning_mercredi_m1'],
                'planning_mercredi_m2' => $_POST['planning_mercredi_m2'],
                'planning_mercredi_a1' => $_POST['planning_mercredi_a1'],
                'planning_mercredi_a2' => $_POST['planning_mercredi_a2'],

                'planning_jeudi_m1' => $_POST['planning_jeudi_m1'],
                'planning_jeudi_m2' => $_POST['planning_jeudi_m2'],
                'planning_jeudi_a1' => $_POST['planning_jeudi_a1'],
                'planning_jeudi_a2' => $_POST['planning_jeudi_a2'],

                'planning_vendredi_m1' => $_POST['planning_vendredi_m1'],
                'planning_vendredi_m2' => $_POST['planning_vendredi_m2'],
                'planning_vendredi_a1' => $_POST['planning_vendredi_a1'],
                'planning_vendredi_a2' => $_POST['planning_vendredi_a2']
            ];

            // Met à jour les enregistrements dans la base de données
            $this->planning_model->updatePlanningData($updated_planning_data);
        }        

        // Récupère les données du planning
        $planning_data = $this->planning_model->getPlanningData();

        $data['planning_data'] = $planning_data;

        $form_fields_data = 
        [
            'planning_lundi_m1','planning_lundi_m2','planning_lundi_a1','planning_lundi_a2',
            'planning_mardi_m1','planning_mardi_m2','planning_mardi_a1','planning_mardi_a2',
            'planning_mercredi_m1','planning_mercredi_m2','planning_mercredi_a1','planning_mercredi_a2',
            'planning_jeudi_m1','planning_jeudi_m2','planning_jeudi_a1','planning_jeudi_a2',
            'planning_vendredi_m1','planning_vendredi_m2','planning_vendredi_a1','planning_vendredi_a2',
        ];

        $data['form_fields_data'] = $form_fields_data;

        $this->display_view('Helpdesk\modification_planning', $data);
    }
}
