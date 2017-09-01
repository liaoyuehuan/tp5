<?php
namespace app\index\controller;

use think\Controller;
use think\View;
use function think\alert;

load_trait('controller/Jump');

class Index 
{
    use \traits\controller\Jump;
    
    protected $beforeActionList = [
        'before' => [ 'except' => 'json'],
    ];
    
    public function before(){
        
        echo "before";
    }
    
    public function php_input(){
        
        $_get_post_data = file_get_contents('php://input') ? file_get_contents('php://input') : null;
        var_dump($_get_post_data);
    }
    
    public function alert(){
        //alert(1);
    }
    
    
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    
    public function index2(){
        return view('index');
        openssl_pkey_new();
    }
    
    public function index3(){
        return 'index3';
    }
    
    public function json(){
        return '{name:"jack"}';
    }
    
    public function np(){
        echo 'namespace:'.__NAMESPACE__.'<br>';
        echo 'method:'.__METHOD__.'<br>';
        echo 'class:'.__CLASS__.'<br>';
        echo 'function:'.__FUNCTION__.'<br>';
    }
    
    public function type(){
        var_dump(is_int(1));
        
    } 
    
    public function csrf(){
        $rand = rand();
        
        echo $rand.'<br/>';
        echo uniqid($rand,true).'<br/>';
        echo strlen(uniqid('aa',true)).'<br />';
        echo uuid_create().'<br />';
    }
    
    public function sess(){
        session_start();
        var_dump(session_id());exit();
    }
    
}
