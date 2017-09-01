<?php
namespace app\index\controller;

use think\Url;
use think\Controller;

require_once '../extend/qq/API/qqConnectAPI.php';

class Qq extends Controller{
    private $id = '1106212979';
    private $key = 'YVwujjGIr0etbobr';
    
    public function index(){
       
        $qc = new \QC();
        $url = $qc -> qq_login();
        $this->redirect($url);
    }
    
    public function index2(){
        $this->assign('redirec_url',urlencode('http://localhost:8081'.Url::build('index/Qq/index3')));
        if(isset($_GET['access_token'])) {
            echo $_GET['access_token'];exit();
            $this->assign('access_token',$_GET['access_token']);
        } else {
            
            $this->assign('access_token','');
        }
       
        return  $this->fetch('index2');
    }
    
    public function index3(){
        return  $this->fetch('index3');
    }
    
    public function enter(){
        
        
    }
    
    public function call(){
        
    }
    
    public  function login(){
        
        $qc = new \QC();
        $qc -> qq_login();
        
    }
    
    public function get_info(){
        $qc = new \QC();
        $ret = $qc -> get_info();
        var_dump($ret);exit();
    }
    
    
}