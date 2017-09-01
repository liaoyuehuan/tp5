<?php
namespace app\index\controller;

class Url
{
    public function index(){
        echo  \think\Url::build('index/Qq/call');
    }
}

