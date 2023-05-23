<?php



namespace Migration\Filters;


use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Services;
use Config\App;

class MigrationFilter implements \CodeIgniter\Filters\FilterInterface
{
    private $session;
    public function before(RequestInterface $request, $arguments = null)
    {
        //try to instantiate default session with default config
        try {
            $this->session=Services::session();
            $appstatusfile=fopen(config('\Migration\Config\MigrationConfig')->writablePath.'/appStatus.json','w+');
            fwrite($appstatusfile,json_encode(['initialized'=>'true']));
            fclose($appstatusfile);

        }
        //if cannot instanciate default session with database means app is not initialized
        catch (\Exception $e){

            $config=new App();
            $config->sessionSavePath=WRITEPATH.'session';
            $config->sessionDriver='CodeIgniter\Session\Handlers\FileHandler';
            $this->session=Services::session($config);
            $appstatusfile=fopen(config('\Migration\Config\MigrationConfig')->writablePath.'/appStatus.json','w+');
            fwrite($appstatusfile,json_encode(['initialized'=>'false']));
            fclose($appstatusfile);

        }
        //verify on migration index if user is authorized
        if ($this->session->get('mig_authorized')!='true'&&(base_url('migration')==$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])){
            if (isset($_COOKIE['mig_authorized'])&&$_COOKIE['mig_authorized']=='true'){
                $this->session->set('mig_authorized','true');
            }
            //else let him authenticate
            else {
                echo view('\Migration\migration\authentication');
                exit();
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}