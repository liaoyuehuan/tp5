<?php
namespace app\index\controller;

use think\Request;

class Request2
{
    public function info(){
        
        $request = Request::instance();
        echo 'domain:'.$request->domain().'<br/>';
        echo 'url:'.$request->baseUrl().'<br/>';
        echo 'url with domain:'.$request->url().'<br/>';
        echo 'root:'.$request->root().'<br/>';
        echo 'pathinfo: '.$request->pathinfo().'<br>';
        echo 'ext: ' . $request->ext() . '<br/>';
    }
    
    public function injection(){
        Request::hook("inject",array($this,'injectMethod'));
        Request::instance()->inject(1);
    }
    
    public function injectMethod($id){
        echo $id;
    }
    
    public function param(){
        $request = Request::instance();
        $id =  $request->get("id","","htmlspecialchars");
        echo $id;
        var_dump($request->get());
    }
    
    public function name(){
        $request = Request::instance();
        echo 'action : '.$request->action().'<br>';
        echo 'module : '.$request->module().'<br>';
        echo 'controller : '.$request->controller().'<br>';
    }
    
    public function path(){
        echo $_SERVER['DOCUMENT_ROOT'];
    }
}

