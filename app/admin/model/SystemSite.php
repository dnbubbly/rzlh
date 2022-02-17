<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class SystemSite extends TimeModel
{

    protected $name = "system_site";

    protected $deleteTime = "delete_time";

    
    public function systemAdmin()
    {
        return $this->belongsTo('\app\admin\model\SystemAdmin', 'add_id', 'id');
    }

    
    public function getSystemAdminList()
    {
        return \app\admin\model\SystemAdmin::column('username', 'id');
    }

}