<?php


namespace Migration\Controllers;


use App\Controllers\BaseController;
use CodeIgniter\Config\Services;

class AuthenticateMigration extends BaseController
{
    public function authenticate(){
        $session=Services::session();
        $password=$this->request->getPost('migpassword');
        if (password_verify($password,config('\Migration\Config\MigrationConfig')->migrationpass)){
            $session->set('mig_authorized','true');
            setcookie('mig_authorized','true',strtotime('+1 hour'));
            return redirect()->to(base_url('migration'));
        }
        else{
            $session->set('mig_authorized','false');
            return redirect()->to(base_url('migration'));
        }
    }
}