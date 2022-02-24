<?php

namespace app\report\controller;


use app\common\controller\MobileController;
use think\App;
use think\facade\Db;

class Index extends MobileController
{
    
    protected $layout = FALSE;
    
    public function __construct(App $app)
    {
        parent::__construct($app);
        
        $this->contract = new \app\admin\model\Contract();
        
        $this->saleDepartment= new \app\admin\model\SaleDepartment();
        
        $this->salesOrder= new \app\admin\model\SalesOrder();
        
        $this->saleCoal= new \app\admin\model\SaleCoal();
        
        $this->ccoalType= new \app\admin\model\CcoalType();
        
        $this->coalStocksG= new \app\admin\model\CoalStocksG();
        
        $this->qrderBudget= new \app\admin\model\OrderBudget();
        
    }
    /**
     * 首页
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            try {
                //年发运吨数
                $dataAll = array();
                $year = date("Y");
                $month = date("m");
                $preyear = date("Y",strtotime("-1 year"));
                $dataAll[0]['year'] = $year;
                $dataAll[1]['year'] = $preyear;
                $where[] = ['iszc','<>',2];
                $where[] = ['ischeck','=',1];
                for ($i=1;$i<=12;$i++){
                    $list1 = $this->saleCoal
                    ->alias('s')
                    ->field('sum(ScdWeight2) as weight')
                    ->join('salecoaldetail sd','sd.ScID=s.ScID')
                    ->where($where)
                    ->where("Year(ScDate)=".$year)
                    ->where("Month(ScDate)=".$i)
                    ->find();
                    $dataAll[0]['data'][] = $list1->weight?floatval($list1->weight):0;
                }
                for ($i=1;$i<=12;$i++){
                    $list1 = $this->saleCoal
                    ->alias('s')
                    ->field('sum(ScdWeight2) as weight')
                    ->join('salecoaldetail sd','sd.ScID=s.ScID')
                    ->where($where)
                    ->where("Year(ScDate)=".$preyear)
                    ->where("Month(ScDate)=".$i)
                    ->find();
                    $dataAll[1]['data'][] = $list1->weight?floatval($list1->weight):0;
                }
                $option1['dataAll'] = $dataAll;
                $data['option1'] = $option1;
                //合作方年度发运数
                $list2 = $this->saleCoal
                ->alias('s')
                ->field('ScDepartment,sum(ScdWeight2) as weight')
                ->join('salecoaldetail sd','sd.ScID=s.ScID')
                ->where($where)
                ->group('ScDepartment')
                ->order('weight desc')
                ->limit(10)
                ->select();
                $option2 = array();
                foreach ($list2 as $v){
                    $option2['xAxis'][] = $v->ScDepartment;
                    $option2['series'][] = $v->weight?floatval($v->weight):0;
                }
                $data['option2'] = $option2;
                //运输方式对比
                $dataAll2 = array();
                for ($i=1;$i<=$month+1;$i++){
                    $list3 = $this->saleCoal
                    ->alias('s')
                    ->field('sum(ScdWeight2) as weight')
                    ->join('salecoaldetail sd','sd.ScID=s.ScID')
                    ->where($where)
                    ->where("Year(ScDate)=".$year)
                    ->where("Month(ScDate)=".$i)
                    ->where('YsType','铁路')
                    ->find();
                    $dataAll2['year'][0][] = $list3->weight?floatval($list3->weight):0;
                }
                for ($i=1;$i<=$month+1;$i++){
                    $list4 = $this->saleCoal
                    ->alias('s')
                    ->field('sum(ScdWeight2) as weight')
                    ->join('salecoaldetail sd','sd.ScID=s.ScID')
                    ->where($where)
                    ->where("Year(ScDate)=".$year)
                    ->where("Month(ScDate)=".$i)
                    ->where('YsType','公路')
                    ->find();
                    $dataAll2['year'][1][] = $list4->weight?floatval($list4->weight):0;
                }
                $option3['dataAll2'] = $dataAll2;
                $data['option3'] = $option3;
                //品种占比
                $list4 = $this->saleCoal
                ->alias('s')
                ->field('ScCoalType,sum(ScdWeight2) as weight')
                ->join('salecoaldetail sd','sd.ScID=s.ScID')
                ->where($where)
                ->group('ScCoalType')
                ->order('weight desc')
                ->limit(8)
                ->select();
                $count4 = $this->saleCoal
                ->alias('s')
                ->field('sum(ScdWeight2) as weight')
                ->join('salecoaldetail sd','sd.ScID=s.ScID')
                ->where($where)
                ->find();
                foreach ($list4 as $k=>$v){
                    $dataAll4[$k]['name'] = $v->ScCoalType;
                    $dataAll4[$k]['value'] = $v->weight;
                    $count4->weight= $count4->weight-$v->weight;
                }
                if($count4->weight>0){
                    $dataAll4[count($list4)]['name'] = "其他";
                    $dataAll4[count($list4)]['value'] = $count4->weight;
                }
                $ccoalType = $this->ccoalType->field('mname')->select();
                foreach ($ccoalType as $v){
                    $ccoal[] = $v->mname;
                }
                $ccoal[] = "其他";
                $option4['ccoalType'] = $ccoal;
                $option4['dataAll4'] = $dataAll4;
                $data['option4'] = $option4;
                //地图
                $option5 = $this->contract
                ->alias('a')
                ->field('SdName,sum(cPrice*cCount) as count')
                ->join('saledepartment sd','sd.SdName=a.cBuyer')
                ->where(['cType'=>1,'a.ischeck'=>1])
                ->group('SdName')
                ->order('count desc')
                ->limit(8)
                ->select();
                foreach ($option5 as $k=>$v){
                    if($v->SdName=="湖南华菱钢铁股份有限公司"){
                        $v->SdName = "湖南华菱";
                    }elseif ($v->SdName=="贵州乌江水电开发有限责任公司大龙分公司"){
                        $v->SdName = "贵州大龙";
                    }elseif ($v->SdName=="江苏盈硕能源科技有限公司"){
                        $v->SdName = "江苏盈硕";
                    }elseif ($v->SdName=="济源市万源工贸有限公司"){
                        $v->SdName = "济源万源";
                    }elseif ($v->SdName=="沛县华建电力燃料有限公司"){
                        $v->SdName = "沛县华建";
                    }elseif ($v->SdName=="江苏南钢鑫洋供应链有限公司"){
                        $v->SdName = "江苏南钢";
                    }
                    $option5[$k]['count'] = round($v->count/10000);
                }
                $data['option5'] = $option5;
                //库存明细
                $stockyear = date("ym");
                $stockpreyear = date("ym",strtotime('-1 month'));
                $stock = $this->coalStocksG->field("CsGangkou,CsKuangdian,CsTypeName,JCSL".$stockpreyear.",RKSL".$stockyear.",CKSL".$stockyear.",JCSL".$stockyear)->select();
                $kc['stockyear'] = $stockyear;
                $kc['stockpreyear'] = $stockpreyear;
                $kc['stock'] = $stock;
                $data['kc'] = $kc;
                //库存库点名称
                $list6 = $this->coalStocksG
                ->field('CsGangkou,sum(JCSL'.$stockyear.') as count')
                ->group('CsGangkou')
                ->order('count desc')
                ->limit(7)
                ->select();
                $count6 = $this->coalStocksG
                ->field('sum(JCSL'.$stockyear.') as count')
                ->order('count desc')
                ->find();
                foreach ($list6 as $k=>$v){
                    $option6['xAxis'][] = $v->CsGangkou;
                    $option6['series'][] = $v->count?floatval($v->count):0;
                    $count6->count= $count6->count-$v->count;
                }
                if($count6->count>0){
                    $option6['xAxis'][] = "其他";
                    $option6['series'][] = $v->count?floatval($v->count):0;
                }
                $data['option6'] = $option6;
                //库存品种
                $list7 = $this->coalStocksG
                ->field('CsTypeName,sum(JCSL'.$stockyear.') as count')
                ->group('CsTypeName')
                ->order('count desc')
                ->limit(7)
                ->select();
                $count7 = $this->coalStocksG
                ->field('sum(JCSL'.$stockyear.') as count')
                ->order('count desc')
                ->find();
                foreach ($list7 as $k=>$v){
                    $option7[$k]['name'] = $v->CsTypeName;
                    $option7[$k]['value'] = $v->count;
                    $count7->count= $count7->count-$v->count;
                }
                if($count7->count>0){
                    $option7[count($list7)]['name'] = "其他";
                    $option7[count($list7)]['value'] = $count7->count;
                }
                $data['option7'] = $option7;
                //合同履约率
                $lvyue = $this->contract->where(['ischeck'=>1,'cType'=>1])->select();
                foreach ($lvyue as $v){
                    $com = $this->qrderBudget->where('sCode',$v->cCode)->find();
                    $v->complete = $com?round($com->sType/10000):0;
                    $v->bi = round($v->complete/$v->cCount*100,2)."％";
                }
                $data['lvyue'] = $lvyue;
                //当月交易额 当年交易额 累计交易额
                $wherejiao[] = ['cType','=',1];
                $wherejiao[] = ['ischeck','=',1];
                $yue = $this->contract
                ->field('sum(cPrice*cCount) as money')
                ->where($wherejiao)
                ->where("Year(cDate)=".$year)
                ->where("Month(cDate)=".$month)
                ->find();
                $data['yue'] = $yue->money?round($yue->money/10000):0;//当月交易额
                $nian = $this->contract
                ->field('sum(cPrice*cCount) as money')
                ->where($wherejiao)
                ->where("Year(cDate)=".$year)
                ->find();
                $data['nian'] = $nian->money?round($nian->money/10000):0;//当年交易额
                $lei = $this->contract
                ->field('sum(cPrice*cCount) as money')
                ->where($wherejiao)
                ->find();
                $data['lei'] = $lei->money?round($lei->money/10000):0;//累计交易额
                $data = [
                    'code'  => 1,
                    'msg'   => '成功',
                    'data'  => $data,
                ];
            }catch (\Exception $e){
                $data = [
                    'code'  => 0,
                    'msg'   => $e->getMessage(),
                    'data'  => '',
                ];
            }
            return json($data);
        }
        $year = date("Y");
        $month = date("m");
        $preyear = date("Y",strtotime("-1 year"));
        $this->assign('year',$year);//当年
        $this->assign('preyear',$preyear);//上一年
        //当月交易额 当年交易额 累计交易额
        $where[] = ['cType','=',1];
        $where[] = ['ischeck','=',1];
        $yue = $this->contract
        ->field('sum(cPrice*cCount) as money')
        ->where($where)
        ->where("Year(cDate)=".$year)
        ->where("Month(cDate)=".$month)
        ->find();
        $this->assign('yue',$yue->money?round($yue->money/10000):0);//当月交易额
        $nian = $this->contract
        ->field('sum(cPrice*cCount) as money')
        ->where($where)
        ->where("Year(cDate)=".$year)
        ->find();
        $this->assign('nian',$nian->money?round($nian->money/10000):0);//当年交易额
        $lei = $this->contract
        ->field('sum(cPrice*cCount) as money')
        ->where($where)
        ->find();
        $this->assign('lei',$lei->money?round($lei->money/10000):0);//累计交易额
        return $this->fetch();
    }
    public function test(){
        return $this->fetch();
    }
}
