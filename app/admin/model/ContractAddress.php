<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class ContractAddress extends TimeModel
{

    protected $name = "contract_address";

    protected $deleteTime = "delete_time";

    
    
    public function getStatusList()
    {
        return ['0'=>'禁用','1'=>'启用',];
    }

    public function systemAdmin()
    {
        return $this->belongsTo('\app\admin\model\SystemAdmin', 'add_id', 'id');
    }
    
}