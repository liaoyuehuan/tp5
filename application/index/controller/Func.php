<?php
namespace app\index\controller;

class Func 
{
    public function index(){
        $this->a($_GET);
    }
    
    public function index2(){
        call_user_func([$this,'func1'],$_GET);
    }
    
    public function index3(){
        call_user_func_array([$this,'func1'], array('2'=>8,'s'=>6));
    }
    
    public function a(){
        $num = func_num_args();
        $args = func_get_args();
        var_dump($num);
        var_dump($args);
    }
    
    public function func1($arg1,$arg2){
        var_dump($arg1);
        var_dump($arg2);
    }
    
    
}

