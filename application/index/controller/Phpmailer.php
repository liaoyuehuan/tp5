<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/15 0015
 * Time: 15:24
 */

namespace app\index\controller;

use think\Controller;

class Phpmailer extends Controller
{
    public function index(){
        $status = send_mail('1309893442@qq.com','test','测试');
        if($status){
            echo 'success';
        } else {
            echo 'error';
        }
    }
}