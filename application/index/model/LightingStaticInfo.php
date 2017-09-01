<?php
namespace app\index\model;

use think\Model;

class LightingStaticInfo extends Model
{
    public function lightingDynamicInfo(){
        return $this->hasOne('LightingDynamicInfo','pk_LightingDynamicInfo');
    }
    
    public function sensorLogs(){
        return $this->hasMany('SensorLog','fk_LightingStaticInfo_SensorLog');
    }
    
}

