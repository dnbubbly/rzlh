<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class SaleCoal extends TimeModel
{

    protected $name = "sale_coal";

    protected $deleteTime = "delete_time";

    public function getContractHighwaysiteList()
    {
        return \app\admin\model\ContractHighwaysite::column('name', 'id');
    }
    
    public function getContractCoaltypeList()
    {
        return \app\admin\model\ContractCoaltype::column('name', 'id');
    }

}