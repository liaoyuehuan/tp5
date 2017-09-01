<?php
namespace app\index\controller;

use think\Controller;

class Crawler extends Controller
{
    public function index(){
        $url = 'https://detail.tmall.com/item.htm?spm=a220m.1000858.1000725.31.d811797MtqPHK&id=538139634430&skuId=3214801168416&areaId=440100&user_id=2328465620&cat_id=2&is_b=1&rn=088c5716f376cb58b2f54cbb68cd9a70';
        require_once(ROOT_PATH.'extend/simple_html_dom.php');
        $html = file_get_html($url);
        $text = $html->find('#description')[0]->innertext;
        var_dump($this->encoding_str($text));exit();
    }
    
    public function index2(){
        $url = 'http://hws.m.taobao.com/cache/wdetail/5.0/?id=538139634430';
        var_dump(stripslashes(strip_tags(file_get_contents($url))));   
    }
    
    public function index4(){
        $url = 'https://detail.tmall.com/item.htm?spm=a220m.1000858.1000725.21.d811797Uodn3E&id=543940052584&areaId=440100&user_id=1665967495&cat_id=2&is_b=1&rn=766d05113cb812514ce58370fcc5a0e6';
        //$url = 'https://detail.tmall.com/item.htm?id=538139634430';
        $data = file_get_contents($url);
        preg_match('/{\"descUrl\".*?}/i', $data,$res);
        var_dump(json_decode($res[0],true)['descUrl']);
    }
    
    public function index5(){
        $url = 'http://dsc.taobaocdn.com/i2/531/131/538139634430/TB1OWeJNXXXXXX5XVXX8qtpFXlX.desc%7Cvar%5Edesc%3Bsign%5E4085f99477ad162917d1507a59bbf588%3Blang%5Egbk%3Bt%5E1495372045';
        $data = file_get_contents($url);
        preg_match('/desc=.*\;/i', $data,$res);
        $res = $res[0];
        $len = count($res);       
        var_dump(htmlentities(substr($res, 6,$len -2 - 6)));
    }
    
    public function index6(){
        $url = 'https://detail.tmall.com/item.htm?spm=a220m.1000858.1000725.73.d811797LgC7uK&id=541004293159&skuId=3409935519815&areaId=440100&user_id=784116016&cat_id=2&is_b=1&rn=834bedcb381309ca3ed1dcbfb24c8f09';
        //$url = 'https://detail.tmall.com/item.htm?id=538139634430';
        $data = file_get_contents($url);
        preg_match('/{\"descUrl\".*?}/i', $data,$res);
        $descUrl = json_decode($res[0],true)['descUrl'];
        //echo $descUrl;
        $data = file_get_contents('http:'.$descUrl);
        preg_match('/desc=.*\;/i', $data,$res2);
        $res2 = $res2[0];
        $len = strlen($res2);
        echo ($this->encoding_str(substr($res2, 6,strlen($res2) - 6 - 2)));exit();
    }
    
    public function index3(){
        $url = 'https://amos.alicdn.com/muliuserstatus.aw?_ksTS=1500445792110_1074&callback=jsonp1075&beginnum=0&charset=utf-8&uids=%E5%A5%87%E5%BA%A6%E5%AE%B6%E5%B1%85%E4%B8%93%E8%90%A5%E5%BA%97&site=cntaobao';
    }    
    
   
    
    
    public function encoding_str($str,$encoding = 'UTF-8'){
        $encode = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        $str = mb_convert_encoding($str, $encoding, $encode);
        return $str;
    }
    
    public function jd(){
        require_once(ROOT_PATH.'extend/simple_html_dom.php');
        $url = 'https://item.jd.com/12820454100.html';
        $html = file_get_html($url);
        echo $html->find('.summary-price')[0]->innertext;
    }
    
    public function keyword(){
        $url = 'https://detail.tmall.com/item.htm?spm=a230r.1.14.6.ebb2eb2hYU1W5&id=548109738389&cm_id=140105335569ed55e27b&abbucket=1';
        //$url = 'https://detail.tmall.com/item.htm?id=538139634430';
        $data = file_get_contents($url);
        $pattren = 'keywords" content="';
        preg_match('/keywords.*?\//i', $data,$res);
        $rs = $res[0];
        var_dump($this->encoding_str(substr($rs, strlen($pattren),count($rs) - count($pattren) - 2)));exit();
    }
    
    function pathinfo(){
        $src = 'https://img.alicdn.com/imgextra/i4/702546515/TB2EfR9aCvHfKJjSZFPXXbttpXa_!!702546515.jpg_60x60q90.jpg';
        var_dump(pathinfo($src));exit();
    }
}

