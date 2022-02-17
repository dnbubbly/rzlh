<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class CustomerCredit extends TimeModel
{

    protected $name = "customer_credit";

    protected $deleteTime = "delete_time";
    
    public function getStatusList()
    {
        return ['0'=>'启用','1'=>'停用'];
    }

    public function CustomerInfo()
    {
        return $this->belongsTo('\app\admin\model\CustomerInfo', 'cus_id', 'id');
    }
    
    public function systemAdmin()
    {
        return $this->belongsTo('\app\admin\model\SystemAdmin', 'add_id', 'id');
    }
    

}