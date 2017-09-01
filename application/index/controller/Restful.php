<?php
namespace app\index\controller;

use think\controller\Rest;

class Restful extends Rest
{
    public function index(){
        echo $this->method;
    }
    
    public function json(){
        $this->response(['name'=>1],'json')->getData();
    }
}

