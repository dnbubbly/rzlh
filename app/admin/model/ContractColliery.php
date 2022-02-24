<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class ContractColliery extends TimeModel
{

    protected $name = "contract_colliery";

    protected $deleteTime = "delete_time";

    
    
    public function getStatusList()
    {
        return ['0'=>'禁用','1'=>'启用',];
    }


}