<?php
namespace app\index\controller;

use think\Controller;

class Jd extends Controller{
    public function index(){        require_once(ROOT_PATH.'extend/simple_html_dom.php');        $url =  'https://item.jd.com/1231901.html';        $html = file_get_html($url);        echo $html->find('.detail-content-wrap')[0]->innertext;        exit();    }
}