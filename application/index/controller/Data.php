<?php
namespace app\index\controller;

use think\Db;
use app\index\model\Notice;
use app\index\model\LightingStaticInfo;
use app\index\model\LightingDynamicInfo;

class Data{
    
    public function index(){
        $data = Db::connect()->query("select * from tbl_notice");
        var_dump($data);
    }
    
    public function qbc(){
            $notice = new Notice();
            $data = $notice->get(3,'',true);
            var_dump($data->toArray());
    }
    
    public function qbcQuery(){
        $notice = new Notice();
        $data = Notice::get(function($query){
            $query->where(['pk_notice' => '2'])->column('noti_Title');
        });
        var_dump($data->toArray());
    }
    
    public function all(){
        $notice = Notice::all();
        echo json($notice[0])->getContent();
       // var_dump($notice[0]->toJson());
    }
    
    public function limit(){
        $notice = Notice::all(function($query){
            $query->limit(0,2);
        });
        echo count($notice);
    }
    
    public function insert(){
        $notice = new Notice();
        Db::connect()->startTrans();
        $id = $notice->isUpdate(false)->save(['noti_Title' => 'asdas']);
        var_dump(Db::connect()->getLastInsID());
        Db::connect()->rollback();
    }
    
    public function update(){
        $notice = new Notice();
        echo $notice->get(1,'',true)->toJson().'<br>';
        echo $notice->isUpdate(true)->save(['noti_Title' => '测试'],['pk_Notice' =>1]).'<br>';
        echo $notice->get(1,'',true)->noti_Title.'<br>';
        echo $notice->get(1,'',true)->getData('noti_Title') ;
        //echo $notice->get(1,true)->toJson().'<br>';
    }
    
    public function relationOne(){
        $lightingStaticInfo = LightingStaticInfo::get(1);
        var_dump($lightingStaticInfo->lightingDynamicInfo());
    }
    
    public function one2many(){
        $lightingStaticInfo = LightingStaticInfo::get(1);
        var_dump($lightingStaticInfo->sensorLogs()->where(['selo_Temperature' => ['=',20]])->select()[0]->toJson());
    }
    
    public function oneBelong(){
        $dynamic = LightingDynamicInfo::get(1);
        var_dump($dynamic->lightingStaticInfo->lsin_Brand);
    }
    
}