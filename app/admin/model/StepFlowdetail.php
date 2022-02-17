<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class StepFlowdetail extends TimeModel
{

    protected $name = "step_flowdetail";

    protected $deleteTime = "delete_time";

    public function systemAdmin()
    {
        return $this->belongsTo('\app\admin\model\SystemAdmin', 'user_id', 'id');
    }
    
    public function stepFlow()
    {
        return $this->belongsTo('\app\admin\model\StepFlow', 'f_id', 'id');
    }

    

}