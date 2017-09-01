<?php
namespace app\index\controller;

use think\Controller;

class Exec2 extends Controller
{
    public function ffmpeg(){
        ini_set("display_errors", "On");
        ini_set("safe_mode", "off");
        ini_set("safe_mode_include_dir", 'F:/');
        $start = time();

        $exec = "D:/tools/ffmpeg-20170724-03a9e6f-win64-static/bin/ffmpeg -i F:/workspace/tp5/public/static/video/test.wmv -c:v libx264 -strict -2 F:/workspace/tp5/public/static/video/test5.mp4 > D:/null5.txt 2> D:/null5.log &";
        echo shell_exec($exec);
        echo time() - $start.'<br>';
    }
}

