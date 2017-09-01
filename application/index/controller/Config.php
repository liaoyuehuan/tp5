<?php
namespace app\index\controller;


class Config{
    
    public function index(){
        echo ROOT_PATH.'<br>';
        echo APP_PATH;
        $filename  = ROOT_PATH;
        
        $config = include APP_PATH.'config.php';
        $data = json_encode($config);
        $data = '[
 {"value": "in","key": "入库"},
 {"value": "out","key": "出库"}
]';
        var_dump(json_decode($data,JSON_OBJECT_AS_ARRAY));
    }
}