<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class CustomerScore extends TimeModel
{

    protected $name = "customer_score";

    protected $deleteTime = "delete_time";
    
    public function CustomerInfo()
    {
        return $this->belongsTo('\app\admin\model\CustomerInfo', 'cus_id', 'id');
    }
    
    public function systemAdmin()
    {
        return $this->belongsTo('\app\admin\model\SystemAdmin', 'add_id', 'id');
    }

}