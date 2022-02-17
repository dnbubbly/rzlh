<?php

namespace app\admin\model;

use app\common\model\TimeModel;
use app\common\constants\DepartmentConstant;

class SystemDepartment extends TimeModel
{

    protected $name = "system_department";

    protected $deleteTime = "delete_time";
    
    public function getStatusList()
    {
        return ['0'=>'禁用','1'=>'启用',];
    }
    
    public function getIsbalanceList()
    {
        return ['0'=>'禁用','1'=>'启用',];
    }

    public function getPidDepartmentList()
    {
        $list        = $this->field('id,pid,title')
        ->where([
            ['status', '=', 1],
        ])
        ->select()
        ->toArray();
        $pidMenuList = $this->buildPidDepartment(0, $list);
        return $pidMenuList;
    }
    
    protected function buildPidDepartment($pid, $list, $level = 0)
    {
        $newList = [];
        foreach ($list as $vo) {
            if ($vo['pid'] == $pid) {
                $level++;
                foreach ($newList as $v) {
                    if ($vo['pid'] == $v['pid'] && isset($v['level'])) {
                        $level = $v['level'];
                        break;
                    }
                }
                $vo['level'] = $level;
                if ($level > 1) {
                    $repeatString = "&nbsp;&nbsp;";
                    $markString   = str_repeat("├{$repeatString}", $level - 1);
                    $vo['title']  = $markString . $vo['title'];
                }
                $newList[] = $vo;
                $childList = $this->buildPidDepartment($vo['id'], $list, $level);
                !empty($childList) && $newList = array_merge($newList, $childList);
            }
            
        }
        return $newList;
    }
}