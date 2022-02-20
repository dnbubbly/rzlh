<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class ContractInfo extends TimeModel
{

    protected $name = "contract_info";

    protected $deleteTime = "delete_time";

    
    
    public function getDraftList()
    {
        return ['1'=>'新增合同','2'=>'变更合同',];
    }

    public function getLeadList()
    {
        return ['1'=>'我方主导','2'=>'对方主导',];
    }
    
    public function getCheckNumList()
    {
        return ['1'=>'以发货地（煤矿、煤场）数量为准','2'=>'以港口装船数为准','3'=>'以收货单位过磅计量数为准','4'=>'以收货地（港口、煤场）数量为准','5'=>'以收货人验收数为准','4'=>'以铁路大票起票数为准',];
    }
    
    public function getCheckTypeList()
    {
        return ['1'=>'以到厂后收货单位采、制、化验结果为准','2'=>'以第三方检测机构化验结果为准','3'=>'以购方化验结果为准','4'=>'以销方化验结果为准',];
    }

    public function getTaxList()
    {
        return ['1'=>'不含税','2'=>'含 13%增值税发票',];
    }

    public function getFreightList()
    {
        return ['1'=>'单价包含运费','2'=>'单价不包含运费，购方自提','3'=>'单价不包含运费，销方办理运输，费用由购方承担',];
    }

    public function getMarkupList()
    {
        return ['1'=>'不加价','2'=>'6月以内承兑加价','3'=>'6月以上承兑加价',];
    }

    public function getSettleList()
    {
        return ['1'=>'一票结算','2'=>'二票结算',];
    }


}