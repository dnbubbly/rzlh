<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class ContractType extends TimeModel
{

    protected $name = "contract_type";

    protected $deleteTime = "delete_time";

    
    
    public function getStatusList()
    {
        return ['0'=>'禁用','1'=>'启用',];
    }


}