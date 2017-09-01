<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/25 0025
 * Time: 16:11
 */

namespace app\index\controller;


class Ship
{
    public function index(){
        echo 'ship';
    }

    //物流及时查询api
    public function kdApiSearch(){
        include_once EXTEND_PATH.'kdship/KdApiSearchDemo.php';
        $logisticResult= getOrderTracesByJson();
        echo $logisticResult;
    }

}