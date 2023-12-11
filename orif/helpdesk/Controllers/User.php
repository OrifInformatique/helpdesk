<?php

/**
 * Controller for new user creation
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

use User\Controllers\Admin;
use User\Models\User_model;
use Helpdesk\Models\User_Data_model;

class User extends Admin
{
    protected $user_data_model;
    protected $user_model;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->user_data_model = new user_data_model();
        $this->user_model = new user_model();
    }

    /**
     * Adds or modify a user
     *
     * @param integer $user_id = The id of the user to modify, leave blank to create a new one
     * @return void
     */
    public function helpdesk_save_user(?int $user_id = 0)
    {
        //store the user name and user type to display them again in the form
        $oldName = NULL;
        $oldUsertype = NULL;
        //added user in current scope to manage its datas
        $user=null;
        if (count($_POST) > 0) {
            $user_id = $this->request->getPost('id');
            $oldName = $this->request->getPost('user_name');
            if($_SESSION['user_id'] != $user_id) {
                $oldUsertype = $this->request->getPost('user_usertype');
            }
            $post_data = array(
                'id'                    => $user_id ?: null,
                'fk_user_type'          => intval($this->request->getPost('user_usertype')),
                'username'              => $this->request->getPost('user_name'),
                'email'                 => $this->request->getPost('user_email') ?? NULL,
                'user_password'         => $this->request->getPost('user_password'),
                'user_password_again'   => $this->request->getPost('user_password_again'),

                'id_user_data'          => $user_id == 0 ? 0 : ($this->user_data_model->getUserDataId($user_id)),
                'fk_user_id'            => $user_id != 0 ?: null,
                'first_name_user_data'  => $this->request->getPost('first_name_user_data'),
                'last_name_user_data'   => $this->request->getPost('last_name_user_data'),
                'photo_user_data'       => $this->request->getFile('photo_user_data')
            );

            $validation = \Config\Services::validation();
            $validation->setRules([
                'username' => [
                    'label' => lang('user_lang.field_username'),
                    'rules' => 'required|trim|'.
                            'min_length['.config('\User\Config\UserConfig')->username_min_length.']|'.
                            'max_length['.config('\User\Config\UserConfig')->username_max_length.']'
                ],
                'fk_user_type' => [
                    'label' => lang('user_lang.field_usertype'),
                    'rules' => 'required|cb_not_null_user_type'
                ],
                'email' => [
                    'label' => lang('Helpdesk.mail'),
                    'rules' => 'required|valid_email'
                ],
                'first_name_user_data' => [
                    'label' => lang('Helpdesk.first_name'),
                    'rules' => 'required|min_length[3]|max_length[50]|alpha'
                ],
                'last_name_user_data' => [
                    'label' => lang('Helpdesk.last_name'),
                    'rules' => 'required|min_length[3]|max_length[50]|alpha',
                ],
                'photo_user_data' => [
                    'label' => lang('Helpdesk.photo'),
                    'rules' => 'uploaded[photo_user_data]|is_image[photo_user_data]|ext_in[photo_user_data,png,jpg,jpeg]'
                ],
                'user_password' => [
                    'label' => lang('Helpdesk.password'),
                    'rules' => 'required|trim|'.
                            'min_length['.config("\User\Config\UserConfig")->password_min_length.']|'.
                            'max_length['.config("\User\Config\UserConfig")->password_max_length.']|'.
                            'matches[user_password_again]'
                ],
                'user_password_again' => [
                    'label' => lang('Helpdesk.password_confirm'),
                    'rules' => 'if_exist|required'
                ]]);
            d($post_data);
            if(!$validation->run($post_data))
                $errors = $validation->getErrors();

            else {
                $user = [
                    'id'            => $post_data['id'],
                    'fk_user_type'  => $post_data['fk_user_type'],
                    'username'      => $post_data['username'],
                    'password'      => $post_data['user_password'],
                    'email'         => $post_data['email'],
                ];
                $user_data = [
                    'id_user_data'          => $post_data['id_user_data'] ?? null,
                    'fk_user_id'            => $post_data['fk_user_id'],
                    'first_name_user_data'  => $post_data['first_name_user_data'],
                    'last_name_user_data'   => $post_data['last_name_user_data'],
                    'initials_user_data'    => $user['username'],
                    'photo_user_data'       => $post_data['photo_user_data']
                ];
                d($user,$user_data);
                $file_name = $user_data['photo_user_data']->getRandomName();
                $file_path = WRITEPATH.'uploads/images/'.$file_name;
                $user_data['photo_user_data']->move($file_path);
                $user_data['photo_user_data'] = $file_path;

                
                if ($user_id == 0)
                {
                    $this->user_model->insert($user);
                    $user_data['fk_user_id'] = $this->user_model->insertID();
                    $this->user_data_model->insert($user_data);
                } 
                
                else 
                {
                    $this->user_model->update($user);
                    $this->user_data_model->update($user_data);
                }

                //In the case of errors
                if ($this->user_model->errors()==null){
                    return redirect()->to('/user/admin/list_user');
                }
            }
        }

        //usertiarray is an array contained all usertype name and id
        $usertiarray=$this->db->table('user_type')->select(['id','name'],)->get()->getResultArray();
        $usertypes=[];
        foreach ($usertiarray as $row){
            $usertypes[$row['id']]=$row['name'];
        }
        $data = array(
            'title'         => lang('user_lang.title_user_'.((bool)$user_id ? 'update' : 'new')),
            'user'          => $this->user_model->withDeleted()->find($user_id),
            'user_types'    => $usertypes,
            'user_name'     => $oldName,
            'user_usertype' => $oldUsertype,
            'email'         => $post_data['email']??null,
            'first_name_user_data' => $post_data['first_name_user_data']??null,
            'last_name_user_data' => $post_data['last_name_user_data']??null,
            'photo_user_data' => $post_data['photo_user_data']??null,
            'errors'        => $errors??null,
        );
        d($data);
        return $this->display_view('\Helpdesk\form_user', $data);
    }

    /**
     * Delete or deactivate a user depending on $action
     *
     * @param integer $user_id = ID of the user to affect
     * @param integer $action = Action to apply on the user:
     *  - 0 for displaying the confirmation
     *  - 1 for deactivating (soft delete)
     *  - 2 for deleting (hard delete)
     * @return void
     */
    public function helpdesk_delete_user(int $user_id, ?int $action = 0)
    {
        $user = $this->user_model->withDeleted()->find($user_id);
        if (is_null($user)) {
            return redirect()->to('/user/admin/list_user');
        }
        $user_data_id =  $this->user_data_model->withDeleted()->getUserDataId($user_id);

        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'user' => $user,
                    'title' => lang('user_lang.title_user_delete')
                );
                return $this->display_view('\User\admin\delete_user', $output);
                break;
            case 1: // Deactivate (soft delete) user
                if ($_SESSION['user_id'] != $user['id']) {
                    $this->user_data_model->delete($user_data_id, FALSE);
                    $this->user_model->delete($user_id, FALSE);
                }
                return redirect()->to('/user/admin/list_user');
            case 2: // Delete user
                if ($_SESSION['user_id'] != $user['id']) {
                    $this->user_data_model->delete($user_data_id, TRUE);
                    $this->user_model->delete($user_id, TRUE);
                }
                return redirect()->to('/user/admin/list_user');
            default: // Do nothing
                return redirect()->to('/user/admin/list_user');
        }
    }
}
