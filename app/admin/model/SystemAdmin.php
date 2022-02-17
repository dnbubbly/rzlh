<?php

// +----------------------------------------------------------------------
// | EasyAdmin
// +----------------------------------------------------------------------
// | PHP交流群: 763822524
// +----------------------------------------------------------------------
// | 开源协议  https://mit-license.org 
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zhongshaofa/EasyAdmin
// +----------------------------------------------------------------------

namespace app\admin\model;


use app\common\model\TimeModel;

class SystemAdmin extends TimeModel
{

    protected $deleteTime = 'delete_time';

    public function getAuthList()
    {
        $list = (new SystemAuth())
            ->where('status', 1)
            ->column('title', 'id');
        return $list;
    }
    
    public function department()
    {
        return $this->belongsTo('\app\admin\model\SystemDepartment', 'dep_id', 'id');
    }
    
    
    public function getMemberInfoList()
    {
        return \app\admin\model\SystemDepartment::column('title', 'id');
    }
    public function getSexList()
    {
        return ['0'=>'男','1'=>'女'];
    }
    /**
     * 返回员工信息
     *   */
    public function getField($field,$id){
        $admin = $this->where("id",$id)->find();
        return $admin->$field;
    }

}