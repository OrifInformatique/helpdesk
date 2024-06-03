<?php

/**
 * Controller for holidays
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

class Holidays extends Home
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }


    /**
     * Default function, displays the holidays list.
     * 
     * @return view
     * 
     */
    public function index()
    {
        $this->setSessionVariables();

        return redirect()->to('/helpdesk/holidays/holidays_list');
    }


    /** ********************************************************************************************************************************* */


    /**
     * Displays the holidays list.
     * 
     * @return view
     * 
     */
    public function holidays_list()
    {
        $data = 
        [
            'messages'      => $this->getFlashdataMessages(),
            'holidays_data' => $this->holidays_model->getHolidays(),
            'title'         => lang('Titles.holiday')
        ];

        return $this->display_view('Helpdesk\holidays_list', $data);
    }


    /**
     * Displays the page to add/modifiy a holiday entry, and anages the post of the data.
     * 
     * @param int $id_holiday ID of the holiday, default value = 0
     * 
     * @return view
     * 
     */
    public function save_holiday($id_holiday = NULL)
    {
        $this->isUserLogged();

        if($_POST)
        {
            if(!$this->isTechnician())
                return redirect()->to(uri_string());

            $validation = \Config\Services::validation();
            $validation->setRules(
            [
                'holiday_name'  => 'required|french_alpha_space',
                'start_date'    => 'required|valid_date[Y-m-d\TH:i]',
                'end_date'      => 'required|valid_date[Y-m-d\TH:i]|coherent_dates['.$_POST['start_date'].']'
            ],
            [
                'holiday_name' => 
                [
                    'required'              => lang('Forms/Errors.required'),
                    'french_alpha_space'    => lang('Forms/Errors.french_alpha_space')
                ],
                'start_date' => 
                [
                    'required'      => lang('Forms/Errors.required'),
                    'valid_date'    => lang('Forms/Errors.valid_date')
                ],
                'end_date' => 
                [
                    'required'          => lang('Forms/Errors.required'),
                    'valid_date'        => lang('Forms/Errors.valid_date'),
                    'coherent_dates'    => lang('Forms/Errors.coherent_dates')
                ]
            ]);

            if(!$validation->run($_POST))
            {
                $data['form_errors'] = $validation->getErrors();
            }

            else
            {
                $data_to_save =
                [
                    'id_holiday'         => $_POST['id_holiday'],
                    'name_holiday'       => trim($_POST['holiday_name']),
                    'start_date_holiday' => $_POST['start_date'],
                    'end_date_holiday'   => $_POST['end_date'],
                ];

                $this->holidays_model->save($data_to_save);

                $this->session->setFlashdata('success', lang('Success.holiday_updated'));

                return redirect()->to('/helpdesk/holidays/holidays_list');
            }
        }

        if($id_holiday)
        {
            $data['holiday'] = $this->holidays_model->getHoliday($id_holiday);
            $data['title']   = lang('Titles.update_holiday');
        }

        else
        {
            $data['title'] = lang('Titles.add_holiday');
        }

        $data['messages'] = $this->getFlashdataMessages();

        return $this->display_view('Helpdesk\add_holiday', $data);
    }


    /**
     * Displays the page to deletes the vacation entry, and does the suppression of the entry.
     * 
     * @param int $id_holiday ID of the holiday
     * 
     * @return view
     * 
     */
    public function delete_holiday($id_holiday)
    {
        $this->isUserLogged();

        if(!$this->isTechnician())
            return redirect()->to('/helpdesk/holidays/save_holiday/'.$id_holiday);

        // If the users confirms the deletion
        if(isset($_POST['delete_confirmation']) && $_POST['delete_confirmation'] == true)
        {
            $this->holidays_model->delete($id_holiday);

            $this->session->setFlashdata('success', lang('Success.holiday_deleted'));

            return redirect()->to('/helpdesk/holidays/holidays_list');
        }

        // When the user clicks the delete button
        else
        {
            $holiday_data = $this->holidays_model->getHoliday($id_holiday);

            $holiday_entry = lang('MiscTexts.holiday_period').' <strong>'.$holiday_data['name_holiday'].'</strong>.';

            $data = 
            [
                'title'         => lang('Titles.delete_confirmation'),
                'delete_url'    => base_url('/helpdesk/holidays/delete_holiday/'.$id_holiday),
                'btn_back_url'  => base_url('/helpdesk/holidays/save_holiday/'.$id_holiday),
                'entry'         => $holiday_entry,
                'messages'      => $this->getFlashdataMessages()
            ];

            return $this->display_view('Helpdesk\delete_entry', $data);
        }
    }
}