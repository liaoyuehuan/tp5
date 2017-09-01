<?php
namespace app\index\model;

use think\Model;

class LightingDynamicInfo extends Model
{
    public function lightingStaticInfo(){
        return $this->belongsTo('LightingStaticInfo','pk_LightingDynamicInfo');
    }
}

