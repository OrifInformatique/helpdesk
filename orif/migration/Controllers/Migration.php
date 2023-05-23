<?php
namespace Migration\Controllers;

use CodeIgniter\Config\Services;
use CodeIgniter\Database\MigrationRunner;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use Config\App;
use Config\Migrations;
use Migration\Models\MigrationModel;
use Psr\Log\LoggerInterface;

class Migration extends \App\Controllers\BaseController{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        //if user is not authenticated return to authentication view
        if (Services::session()->get('mig_authorized')!='true'){
            echo view('\Migration\migration\authentication');
            header('location:'.base_url('migration'));
            exit();
        }
        parent::initController($request, $response, $logger);
    }

    public function index(){
        $selected='migration';
        $tempHistory=null;
        $history=null;
        $migrationFiles=[];
        $migrationElements=[];

        $statusFile=fopen(config('\Migration\Config\MigrationConfig')->writablePath.'/appStatus.json','r');
        
        //verify if app is initialized
        $appstatus=(json_decode(fread($statusFile,filesize(config('\Migration\Config\MigrationConfig')->writablePath.'/appStatus.json')),true));
        foreach ($this->listDirectories(ROOTPATH.'orif') as $module){
            foreach ($module as $directoryLbl => $directory) {
                //when its Database Directory
                //if element index is not numeric then it's a path
                if (!is_numeric($directoryLbl)) {
                    //when the iterator has reached Database directory
                    if (strpos($directoryLbl,'Database')) {
                        //list all files in migration directory and add it to $migrationFiles
                        foreach ($directory as $databaseDirectoryLbl => $databaseDirectory) {

                            if (strpos($databaseDirectoryLbl,'Migration')) {
                                $migrationFiles[$databaseDirectoryLbl] = $databaseDirectory;
                            }
                        }
                    }
                }
            }
        }

        //form migration file get [creation_date, path, name, class, namespace, status with default value not migrated]
        foreach ($migrationFiles as $migrationModuleLbl => $migrationModuleDatas) {
            foreach ($migrationModuleDatas as $migrationInDriectoryLbl =>$migrationModuleFile) {
                if (is_array($migrationModuleFile)){
                    foreach ($migrationModuleFile as $migrationInFile){
                        $ifile=fopen($migrationInDriectoryLbl.'/'.$migrationInFile,'r');
                        $str=fread($ifile,filesize($migrationInDriectoryLbl.'/'.$migrationInFile)/2);
                        fseek($ifile,strpos($str,'class'));
                        $strC=fgets($ifile);
                        $migrationElement=['creation_date'=>explode('_',$migrationInFile,2)[0],'path'=>$migrationInDriectoryLbl.'/'.$migrationInFile,'name'=>explode('_',$migrationInFile,2)[1],'class'=>substr($strC,strpos($strC,' ')+1,strpos($strC,' extends')-strpos($strC,' ')-1),'namespace'=>ucfirst(str_replace(ROOTPATH.'orif/','',$migrationInDriectoryLbl)),'status'=>config('\Migration\Config\MigrationConfig')->migrate_status_not_migrated];
                        $migrationElements[explode('/',((explode(ROOTPATH.'orif',$migrationModuleLbl))[1]))[1]][]=$migrationElement;
                        fclose($ifile);
                    }
                    continue;
                }
                $file=fopen($migrationModuleLbl.'/'.$migrationModuleFile,'r');
                $str=fread($file,filesize($migrationModuleLbl.'/'.$migrationModuleFile)/2);
                fseek($file,strpos($str,'class'));
                $strC=fgets($file);
                $migrationElement=['creation_date'=>explode('_',$migrationModuleFile,2)[0],'path'=>$migrationModuleLbl.'/'.$migrationModuleFile,'name'=>explode('_',$migrationModuleFile,2)[1],'class'=>substr($strC,strpos($strC,' ')+1,strpos($strC,' extends')-strpos($strC,' ')-1),'namespace'=>ucfirst(str_replace(ROOTPATH.'orif/','',$migrationModuleLbl)),'status'=>config('\Migration\Config\MigrationConfig')->migrate_status_not_migrated];
                $migrationElements[explode('/',((explode(ROOTPATH.'orif',$migrationModuleLbl))[1]))[1]][]=$migrationElement;
                fclose($file);
            }

        }

        //order $migrationElements array
        asort($migrationElements);
        $error=null;
        //if error is in url store it in $error
        $this->request->getGet('error')==null?:$error=base64_decode($this->request->getGet('error'));
        //if the app is initialized verify in the database which file is migrated
            try {
                $migrationModel = new MigrationModel();
                foreach ($migrationElements as $migrationElementLbl => $migrationElement) {
                    foreach ($migrationElement as $migrationRowLbl => $migrationRow) {
                        //get class of migration element separated by \
                        $reformatedClass = (str_replace('/', '\\', $migrationRow['namespace'] . '\\' . $migrationRow['class']));
                        $migrationRowDb = $migrationModel->where('class', $reformatedClass)->first();
                        //if file is found on filesystem and database it means that is migrated
                        if ($migrationRowDb !== null && $migrationRowDb['version'] == $migrationRow['creation_date']) {
                            $selected = 'history';
                            $migrationRow['status'] = config('\Migration\Config\MigrationConfig')->migrate_status_migrated;
                            $migrationRow['batch'] = $migrationRowDb['batch'];
                            $migrationRow['migration_date'] = (new Time())->setTimestamp($migrationRowDb['time'])->toLocalizedString();
                            $migrationElement[$migrationRowLbl] = $migrationRow;
                        }
                        $tempHistory[] = $migrationRowDb;
                    }
                    $migrationElements[$migrationElementLbl] = $migrationElement;
                }
                //now the history contains all presents migration on filesystem

                foreach ($migrationModel->orderBy('time', 'desk')->findAll() as $migRow) {
                    if (isset($tempHistory)) {
                        foreach ($tempHistory as $presentsRow) {
                            if (isset($presentsRow))
                                if ($migRow['id'] == $presentsRow['id']) {
                                    $migRow['status'] = config('\Migration\Config\MigrationConfig')->migrate_status_migrated;
                                }

                        }
                    }
                    $history[] = $migRow;
                }
            }catch (\mysqli_sql_exception $e){}
        if (isset($history)) {
            foreach ($history as $historyIndex => $historyRow) {
                if (!isset($historyRow['status']))
                    $historyRow['status'] = config('\Migration\Config\MigrationConfig')->migrate_status_removed;
                $history[$historyIndex] = $historyRow;
            }
            krsort($history);

        }

        $data = ['migrations'   => $migrationElements,
                 'error'        => $error,
                 'history'      => $history,
                 'selected'     => isset($_COOKIE['selected']) ? $_COOKIE['selected'] : $selected ];
        
        return $this->display_view('\Migration\migration\index', $data);
    }

    /**
     * This function migrate application to specified migration file in param
     * @param $migrationElement /the migration element to migrate
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function migrate($migrationElement){
        $migrationElement=json_decode(base64_decode($migrationElement),true);
        $migrationRunner=new MigrationRunner(new Migrations());
        try {
            $migrationResult = $migrationRunner->force($migrationElement['path'], $migrationElement['namespace']);
        }catch(\Exception $e){
            $error=($e->getMessage());
            //if error occur send it to the view in base64 format
            return redirect()->to(base_url('migration').'?error='.base64_encode($error));
        }
        return redirect()->to(base_url('migration'));


    }

    /**
     * This function roolback application state to batch number
     * @param $batchNumber
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function rollback($batchNumber){
        $migrationRunner=new MigrationRunner(new Migrations());
        try {
            $migrationRunner->regress($batchNumber);
        }catch (\Exception $e){
            $error=($e->getMessage());
            return redirect()->to(base_url('migration').'?error='.base64_encode($error));
        }
        return redirect()->to(base_url('migration'));

    }

    /**
     * This fuction remove migration file from filesystem
     * @param $migrationElement
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function remove($migrationElement,$action=0){
        $migrationElement=json_decode(base64_decode($migrationElement),true);
        if ($action==0){

            return $this->display_view('\Migration\Views\migration\delete_migration',['migration'=>$migrationElement]);
        }
        else if ($action==2){
            $migrationDirectoryPathPart=explode('/',$migrationElement['path']);
            unset($migrationDirectoryPathPart[count($migrationDirectoryPathPart)-1]);
            $migrationDirectoryPath=join('/',$migrationDirectoryPathPart);
            unlink($migrationElement['path']);
            $directory=(array_diff(scandir($migrationDirectoryPath),['.','..']));
            if (count($directory)==0){
                rmdir($migrationDirectoryPath);
            }
        }



        return redirect()->to(base_url('migration'));

    }
    public function delete_module(int $action=0){
        if ($action!==2){
            return $this->display_view('\Migration\Views\migration\delete_module');
        }
        unlink(APPPATH.'../public/Scripts/migrationscripts.js');
        rmdir(APPPATH.'../public/Scripts');
        $this->remove_files(ROOTPATH.'orif/migration');
        $filterFile=fopen(APPPATH.'Config/Filters.php','c+');
        $filterContents=fread($filterFile,filesize(APPPATH.'Config/Filters.php'));
        while(($cursorPosition=strpos($filterContents,'migration'))!=false){
            //put the cursor to BOF
            fseek($filterFile,0);
            //read filter file
            $filterContents=fread($filterFile,filesize(APPPATH.'Config/Filters.php'));
            //reset the cursor to BOF
            fseek($filterFile,$cursorPosition-1);
            //remove migration first instance line and store to $datas
            $datas=str_replace(fgets($filterFile),'',$filterContents);
            //truncate the file
            ftruncate($filterFile,0);
            //reset the cursor to BOF
            fseek($filterFile,0);
            //write datas to file
            fwrite($filterFile,$datas);
            //reset the cursor to BOF
            fseek($filterFile,0);
            //read entire file
            $filterContents=fread($filterFile,filesize(APPPATH.'Config/Filters.php'));
        }
        return redirect()->to(base_url());
    }
    public function showpopup($moduleName,$class,$batchNumber){
        return view('\Migration\Views\popup\cancel_migration_popup',['moduleName'=>$moduleName,'className'=>$class,'batchNumber'=>$batchNumber]);
    }
    private function remove_files($path){
        $files=glob($path.'/*');
        foreach($files as $file){
            if (is_dir($file)){
                $this->remove_files($file);
            }
            else{
                unlink($file);
            }
        }
        rmdir($path);
        return;


    }
    /**
     * This function list all repository from a specified path
     * @return array
     */
    private function listDirectories(string $path){
        $directories=[];
        $directoryPtr=opendir($path);
        while ($file=readdir($directoryPtr)){
            if ($file=='.'||$file=='..'){
                continue;
            }
            if (is_dir($path.'/'.$file)){
                $directories[$path.'/'.$file]=$this->listDirectories($path.'/'.$file);
            }
            else{
                $directories[]=$file;
            }

        }
        arsort($directories);
        return $directories;

    }
}