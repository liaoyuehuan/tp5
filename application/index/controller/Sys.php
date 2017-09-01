<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/29 0029
 * Time: 11:26
 */

namespace app\index\controller;


class Sys
{
    public function index(){
        echo 'sys';
    }

    public function sys_get(){
        echo 'temp_dir : '.sys_get_temp_dir().'<br />';
    }
}