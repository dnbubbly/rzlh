<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class StepFlow extends TimeModel
{

    protected $name = "step_flow";

    protected $deleteTime = "delete_time";

    public function systemAdmin()
    {
        return $this->belongsTo('\app\admin\model\SystemAdmin', 'u_id', 'id');
    }
    

}