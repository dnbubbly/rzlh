<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class SystemStore extends TimeModel
{

    protected $name = "system_store";

    protected $deleteTime = "delete_time";

    public function getStatusList()
    {
        return ['0'=>'未生效','1'=>'审核中','2'=>'已生效','3'=>'已退回'];
    }
    
    public function systemAdmin()
    {
        return $this->belongsTo('\app\admin\model\SystemAdmin', 'add_id', 'id');
    }

    
    public function getSystemAdminList()
    {
        return \app\admin\model\SystemAdmin::column('username', 'id');
    }

}