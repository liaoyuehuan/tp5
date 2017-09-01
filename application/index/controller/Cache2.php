<?php
namespace app\index\controller;

use think\Cache;

class Cache2 {
    public function index(){
        Cache::init(['type'=>'file']);
        Cache::set('1','ok');
        echo Cache::get('1');
    }
}