<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/11 0011
 * Time: 10:36
 */

namespace app\index\controller;


use think\Controller;

class Mobile extends Controller
{
    public function index()
    {
        var_dump(is_mobile());
    }
}

function is_mobile(){
    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

    $mobile_agents = array(
        "240x320", "acer", "acoon", "acs-", "abacho", "ahong", "airness", "alcatel", "amoi",
        "android", "anywhereyougo.com", "applewebkit/525", "applewebkit/532", "asus", "audio",
        "au-mic", "avantogo", "becker", "benq", "bilbo", "bird", "blackberry", "blazer", "bleu",
        "cdm-", "compal", "coolpad", "danger", "dbtel", "dopod", "elaine", "eric", "etouch", "fly ",
        "fly_", "fly-", "go.web", "goodaccess", "gradiente", "grundig", "haier", "hedy", "hitachi",
        "htc", "huawei", "hutchison", "inno", "ipad", "ipaq", "iphone", "ipod", "jbrowser", "kddi",
        "kgt", "kwc", "lenovo", "lg ", "lg2", "lg3", "lg4", "lg5", "lg7", "lg8", "lg9", "lg-", "lge-", "lge9", "longcos", "maemo",
        "mercator", "meridian", "micromax", "midp", "mini", "mitsu", "mmm", "mmp", "mobi", "mot-",
        "moto", "nec-", "netfront", "newgen", "nexian", "nf-browser", "nintendo", "nitro", "nokia",
        "nook", "novarra", "obigo", "palm", "panasonic", "pantech", "philips", "phone", "pg-",
        "playstation", "pocket", "pt-", "qc-", "qtek", "rover", "sagem", "sama", "samu", "sanyo",
        "samsung", "sch-", "scooter", "sec-", "sendo", "sgh-", "sharp", "siemens", "sie-", "softbank",
        "sony", "spice", "sprint", "spv", "symbian", "tablet", "talkabout", "tcl-", "teleca", "telit",
        "tianyu", "tim-", "toshiba", "tsm", "up.browser", "utec", "utstar", "verykool", "virgin",
        "vk-", "voda", "voxtel", "vx", "wap", "wellco", "wig browser", "wii", "windows ce",
        "wireless", "xda", "xde", "zte"
    );

    foreach ($mobile_agents as $mobile_agent) {

        $mobile_agent = preg_replace('/\//','\/',quotemeta($mobile_agent));
        $pattern = '/'.$mobile_agent.'/';
        if(preg_match($pattern,$user_agent)){
           return true;
        };
    }
    return false;
}