<?php
namespace app\index\controller;

use think\Lang;

class Lang2{
    public function index(){
        echo Lang::get('Parse error');
    }
    
}
