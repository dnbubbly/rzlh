<?php

namespace app\admin\controller\contract;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\facade\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Shared\Html;
use HTMLtoOpenXML\Parser;
use PhpOffice\PhpWord\Settings;


/**
 * @ControllerAnnotation(title="contract_info")
 */
class Info extends AdminController
{

    use \app\admin\traits\Curd;
    
    protected $relationSearch = true;
    
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\ContractInfo();
        
        $this->step = new \app\admin\model\SystemStep();
        
        $this->stepflow = new \app\admin\model\StepFlow();
        
        $this->quality = new \app\admin\model\ContractQuality();
        
        $this->detail= new \app\admin\model\ContractDetail();
        
        $this->qualitydetail= new \app\admin\model\ContractQualitydetail();
        
        $this->qualitylevel= new \app\admin\model\ContractQualitylevel();
        
        $this->assign('getDraftList', $this->model->getDraftList());

        $this->assign('getLeadList', $this->model->getLeadList());

        $this->assign('getCheckNumList', $this->model->getCheckNumList());
        
        $this->assign('getCheckTypeList', $this->model->getCheckTypeList());

        $this->assign('getTaxList', $this->model->getTaxList());

        $this->assign('getFreightList', $this->model->getFreightList());

        $this->assign('getMarkupList', $this->model->getMarkupList());

        $this->assign('getSettleList', $this->model->getSettleList());
        
        $this->assign('getContractType', $this->model->getContractType());
        
        $this->assign('getContractHighwaysiteList', $this->model->getContractHighwaysiteList());
        
        $this->assign('getContractRailwaysiteList', $this->model->getContractRailwaysiteList());

    }
    
    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $where[] = ['isfile','=',0];
            $count = $this->model
            ->withJoin(['systemAdmin','customerInfo','customerInfoBuyer','contractType'], 'LEFT')
            ->where($where)
            ->count();
            $list = $this->model
            ->withJoin(['systemAdmin','customerInfo','customerInfoBuyer','contractType'], 'LEFT')
            ->where($where)
            ->page($page, $limit)
            ->order($this->sort)
            ->select();
            foreach ($list as $li){
                if($li->type==1){
                    $li->hezuofang = $li->customerInfoBuyer->name;
                }elseif ($li->type==2){
                    $li->hezuofang = $li->customerInfo->name;
                }
                $li->zxq = date("Y-m-d",$li->startdate)."至".date("Y-m-d",$li->enddate);
                $li->date = date("Y-m-d",$li->date);
            }
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="g归档合同列表")
     */
    public function fileindex()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $where[] = ['isfile','=',1];
            $count = $this->model
            ->withJoin(['systemAdmin','customerInfo','customerInfoBuyer','contractType'], 'LEFT')
            ->where($where)
            ->count();
            $list = $this->model
            ->withJoin(['systemAdmin','customerInfo','customerInfoBuyer','contractType'], 'LEFT')
            ->where($where)
            ->page($page, $limit)
            ->order($this->sort)
            ->select();
            foreach ($list as $li){
                if($li->type==1){
                    $li->hezuofang = $li->customerInfoBuyer->name;
                }elseif ($li->type==2){
                    $li->hezuofang = $li->customerInfo->name;
                }
                $li->zxq = date("Y-m-d",$li->startdate)."至".date("Y-m-d",$li->enddate);
                $li->date = date("Y-m-d",$li->date);
            }
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="选择合同类型")
     */
    public function addone()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $this->success('保存成功',$post);
        }
        $this->assign('getTypeList', $this->model->getDraftList());
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="添加")
     */
    public function add()
    {
        $get = $this->request->get();
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $add['type'] = $post['type'];
                $add['draft'] = $post['draft'];
                $add['lead'] = $post['lead'];
                $add['seller'] = $post['seller'];
                $add['buyer'] = $post['buyer'];
                $add['number'] = $post['number'];
                $add['address'] = $post['address'];
                $add['date'] = strtotime($post['date']);
                $add['receiving'] = $post['receiving'];
                $add['settlement'] = $post['settlement'];
                $add['startdate'] = strtotime($post['startdate']);
                $add['enddate'] = strtotime($post['enddate']);
                $add['road'] = $post['road'];
                if($add['road']==1){//公路
                    $add['start_station'] = $post['rstart_station'];
                    $add['end_station'] = $post['rend_station'];
                }elseif ($add['road']==2){//铁路
                    $add['start_station'] = $post['hstart_station'];
                    $add['end_station'] = $post['hend_station'];
                }
                $add['delivery'] = $post['delivery'];
                $add['delivery_address'] = $post['delivery_address'];
                $add['check_num'] = $post['check_num'];
                $add['check_type'] = $post['check_type'];
                $add['tax'] = $post['tax'];
                $add['freight'] = $post['freight'];
                $add['markup'] = $post['markup'];
                $add['markupnum'] = $post['markupnum'];
                $add['advance'] = $post['advance'];
                $add['advance_remark'] = $post['advance_remark'];
                $add['settle'] = $post['settle'];
                $add['law'] = $post['law'];
                $add['remark'] = $post['remark'];
                $add['confile'] = $post['confile'];
                if ($post['lead']!=1){
                    $add['elefile'] = $post['elefile'];
                }
                $add['add_id'] = $post['add_id'];
                //保存
                $save = $this->model->save($add);
                $c_id = $this->model->id;
                $coal = $post['coal'];//煤种数量价格
                foreach ($coal as $a){
                    $in['c_id'] = $c_id;
                    $in['type'] = $a['type'];
                    $in['num'] = $a['num'];
                    $in['price'] = $a['price'];
                    if( $in['type']){
                        $this->detail->insert($in);
                        $cd_id = $this->detail->getLastInsID();
                        $qtype = $this->quality->where('status',1)->group('name')->order('id asc')->select();
                        $qua = json_decode(htmlspecialchars_decode($a['json']),true);
                        foreach ($qtype as $k=>$v){
                            $qd['cd_id'] = $cd_id;
                            if($qua[$v->name]['qz1']){
                                $qd['qname'] = $v->name;
                                $qd['name'] = $qua[$v->name]['qz1'];
                                $qd['standard'] = $qua[$v->name]['qz2'];
                                $this->qualitydetail->insert($qd);
                                $cqd_id = $this->qualitydetail->getLastInsID();
                                if($qua[$v->name]['qz']){
                                    foreach ($qua[$v->name]['qz'] as $qz){
                                        $cqd['cqd_id'] = $cqd_id;
                                        $cqd['qz3'] = $qz[0];
                                        $cqd['qz4'] = $qz[1];
                                        $cqd['qz5'] = $qz[2];
                                        $cqd['qz6'] = $qz[3];
                                        $cqd['qz7'] = $qz[4];
                                        $cqd['qz8'] = $qz[5];
                                        $cqd['qz9'] = $qz[6];
                                        $cqd['qz10'] = $qz[7];
                                        if($cqd['qz3']||$cqd['qz6']){
                                            $this->qualitylevel->insert($cqd);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                }
                if ($post['lead']==1){//我方主导合同需生成电子合同
                    
                }
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $cus_id = empty($get['cus_id'])?'':$get['cus_id'];
        $draft= empty($get['draft'])?'':$get['draft'];
        $lead= empty($get['lead'])?'':$get['lead'];
        //编号
        //查询最新的编号
        $number = $this->model->order("id desc")->find();
        if($number){
            if(substr($number->number,8,4)==date("Y")){
                $code = "RZLH-XS-".date("Y").(sprintf("%03d",substr($number->number,12,3)+1));
            }else{
                $code = "RZLH-XS-".date("Y")."001";
            }
        }else{
            $code = "RZLH-XS-".date("Y")."001";
        }
        $this->assign('code', $code);
        if($cus_id==1){
            $this->assign('seller', "日照兰花冶电能源有限公司");
        }elseif ($cus_id==2){
            $this->assign('buyer', "日照兰花冶电能源有限公司");
        }
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        $row = $this->model->withJoin(['systemAdmin','customerInfo','customerInfoBuyer','customerInfoReceiving','customerInfoSettlement','contractType'], 'LEFT')->find($id);
        empty($row) && $this->error('数据不存在');
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $add['id'] = $id;
                $add['type'] = $post['type'];
                $add['draft'] = $post['draft'];
                $add['lead'] = $post['lead'];
                $add['seller'] = $post['seller'];
                $add['buyer'] = $post['buyer'];
                $add['number'] = $post['number'];
                $add['address'] = $post['address'];
                $add['date'] = strtotime($post['date']);
                $add['receiving'] = $post['receiving'];
                $add['settlement'] = $post['settlement'];
                $add['startdate'] = strtotime($post['startdate']);
                $add['enddate'] = strtotime($post['enddate']);
                $add['road'] = $post['road'];
                if($add['road']==1){//公路
                    $add['start_station'] = $post['rstart_station'];
                    $add['end_station'] = $post['rend_station'];
                }elseif ($add['road']==2){//铁路
                    $add['start_station'] = $post['hstart_station'];
                    $add['end_station'] = $post['hend_station'];
                }
                $add['delivery'] = $post['delivery'];
                $add['delivery_address'] = $post['delivery_address'];
                $add['check_num'] = $post['check_num'];
                $add['check_type'] = $post['check_type'];
                $add['tax'] = $post['tax'];
                $add['freight'] = $post['freight'];
                $add['markup'] = $post['markup'];
                $add['markupnum'] = $post['markupnum'];
                $add['advance'] = $post['advance'];
                $add['advance_remark'] = $post['advance_remark'];
                $add['settle'] = $post['settle'];
                $add['law'] = $post['law'];
                $add['remark'] = $post['remark'];
                $add['confile'] = $post['confile'];
                if ($post['lead']!=1){
                    $add['elefile'] = $post['elefile'];
                }
                $add['add_id'] = $post['add_id'];
                //保存
                $save = $row->save($add);
                $coal = $post['coal'];//煤种数量价格
                //先删除再新加
                $detail = $this->detail->where('c_id',$id)->select();
                foreach ($detail as $de){
                    $qualitydetail = $this->qualitydetail->where('cd_id',$de->id)->select();
                    foreach ($qualitydetail as $qdd){
                        $qualitylevel = $this->qualitylevel->where('cqd_id',$qdd->id)->select();
                        $qualitylevel->delete();
                    }
                    $qualitydetail->delete();
                }
                $detail->delete();
                //新加
                foreach ($coal as $a){
                    $in['c_id'] = $id;
                    $in['type'] = $a['type'];
                    $in['num'] = $a['num'];
                    $in['price'] = $a['price'];
                    if( $in['type']){
                        $this->detail->insert($in);
                        $cd_id = $this->detail->getLastInsID();
                        $qtype = $this->quality->where('status',1)->group('name')->order('id asc')->select();
                        $qua = json_decode(htmlspecialchars_decode($a['json']),true);
                        foreach ($qtype as $k=>$v){
                            $qd['cd_id'] = $cd_id;
                            if($qua[$v->name]['qz1']){
                                $qd['qname'] = $v->name;
                                $qd['name'] = $qua[$v->name]['qz1'];
                                $qd['standard'] = $qua[$v->name]['qz2'];
                                $this->qualitydetail->insert($qd);
                                $cqd_id = $this->qualitydetail->getLastInsID();
                                if($qua[$v->name]['qz']){
                                    foreach ($qua[$v->name]['qz'] as $qz){
                                        $cqd['cqd_id'] = $cqd_id;
                                        $cqd['qz3'] = $qz[0];
                                        $cqd['qz4'] = $qz[1];
                                        $cqd['qz5'] = $qz[2];
                                        $cqd['qz6'] = $qz[3];
                                        $cqd['qz7'] = $qz[4];
                                        $cqd['qz8'] = $qz[5];
                                        $cqd['qz9'] = $qz[6];
                                        $cqd['qz10'] = $qz[7];
                                        if($cqd['qz3']||$cqd['qz6']){
                                            $this->qualitylevel->insert($cqd);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                }
            } catch (\Exception $e) {
                $this->error('保存失败'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        if($row->type==1){
            $this->assign('seller', "日照兰花冶电能源有限公司");
        }elseif ($row->type==2){
            $this->assign('buyer', "日照兰花冶电能源有限公司");
        }
        $row->date = date("Y-m-d",$row->date);
        $row->startdate = date("Y-m-d",$row->startdate);
        $row->enddate = date("Y-m-d",$row->enddate);
        $row->detail = $this->detail->where('c_id',$row->id)->select();
        foreach ($row->detail as $del){
            $qtype = $this->quality->where('status',1)->group('name')->order('id asc')->select();
            $json = array();
            foreach ($qtype as $k=>$v){
                $qualitydetail = $this->qualitydetail->where(['cd_id'=>$del->id,'qname'=>$v->name])->select();
                if(count($qualitydetail)>0){
                    foreach ($qualitydetail as $qd){
                        $json[$v->name]['qz1'] = $qd->name;
                        $json[$v->name]['qz2'] = $qd->standard;
                        $level = $this->qualitylevel->where('cqd_id',$qd->id)->select();
                        if(count($level)>0){
                            foreach ($level as $le){
                                $json[$v->name]['qz'][] = [$le->qz3,$le->qz4,$le->qz5,$le->qz6,$le->qz7,$le->qz8,$le->qz9,$le->qz10];
                            }
                        }else{
                            $json[$v->name]['qz'][] = ['','＜','≤','','每升高','','加价',''];
                        }
                    }
                }else{
                    $json[$v->name]['qz1'] = '';
                    $json[$v->name]['qz2'] = '';
                    $json[$v->name]['qz'][] = ['','＜','≤','','每升高','','加价',''];
                }
                
            }
            $del->json = json_encode($json);
        }
        $this->assign('row', $row);
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="查看")
     */
    public function detail($id)
    {
        $row = $this->model->withJoin(['systemAdmin','customerInfo','customerInfoBuyer','customerInfoReceiving','customerInfoSettlement','contractType'], 'LEFT')->find($id);
        empty($row) && $this->error('数据不存在');
        if($row->type==1){
            $this->assign('seller', "日照兰花冶电能源有限公司");
        }elseif ($row->type==2){
            $this->assign('buyer', "日照兰花冶电能源有限公司");
        }
        $row->date = date("Y-m-d",$row->date);
        $row->startdate = date("Y-m-d",$row->startdate);
        $row->enddate = date("Y-m-d",$row->enddate);
        $row->detail = $this->detail->where('c_id',$row->id)->select();
        foreach ($row->detail as $del){
            $qtype = $this->quality->where('status',1)->group('name')->order('id asc')->select();
            $json = array();
            foreach ($qtype as $k=>$v){
                $qualitydetail = $this->qualitydetail->where(['cd_id'=>$del->id,'qname'=>$v->name])->select();
                if(count($qualitydetail)>0){
                    foreach ($qualitydetail as $qd){
                        $json[$v->name]['qz1'] = $qd->name;
                        $json[$v->name]['qz2'] = $qd->standard;
                        $level = $this->qualitylevel->where('cqd_id',$qd->id)->select();
                        if(count($level)>0){
                            foreach ($level as $le){
                                $json[$v->name]['qz'][] = [$le->qz3,$le->qz4,$le->qz5,$le->qz6,$le->qz7,$le->qz8,$le->qz9,$le->qz10];
                            }
                        }else{
                            $json[$v->name]['qz'][] = ['','＜','≤','','每升高','','加价',''];
                        }
                    }
                }else{
                    $json[$v->name]['qz1'] = '';
                    $json[$v->name]['qz2'] = '';
                    $json[$v->name]['qz'][] = ['','＜','≤','','每升高','','加价',''];
                }
                
            }
            $del->json = json_encode($json);
        }
        $this->assign('row', $row);
        return $this->fetch();
    }
    
    public function quality()
    {   
        $get = $this->request->get();
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $qz = $post['qz'];
            $qzc = array();
            foreach ($qz as $k=>$v){
                $qzc[$k]['qz1'] = $v['qz1'];
                $qzc[$k]['qz2'] = $v['qz2'];
                foreach ($v['qz3'] as $kk=>$vv){
                    $qzc[$k]['qz'][$kk] = [$v['qz3'][$kk],$v['qz4'][$kk],$v['qz5'][$kk],$v['qz6'][$kk],$v['qz7'][$kk],$v['qz8'][$kk],$v['qz9'][$kk],$v['qz10'][$kk]];
                }
            }
            $arr['data'] = $qzc;
            $arr['index'] = $get['index'];
            $this->success('保存成功',$arr);
        }
        //质量标准
        $qtype = $this->quality->where('status',1)->group('name')->order('id asc')->select();
        foreach ($qtype as $k=>$v){
            $qtype[$k]['unit'] = $this->quality->where(['status'=>1,'name'=>$v->name])->select();
        }
        $this->assign('index',$get['index']);
        $this->assign('qtype',$qtype);
        return $this->fetch();
    }
    
    public function qualitydetail()
    {
        $get = $this->request->get();
        //质量标准
        $qtype = $this->quality->where('status',1)->group('name')->order('id asc')->select();
        foreach ($qtype as $k=>$v){
            $qtype[$k]['unit'] = $this->quality->where(['status'=>1,'name'=>$v->name])->select();
        }
        
        $this->assign('index',$get['index']);
        $this->assign('qtype',$qtype);
        return $this->fetch();
    }
    /**
     * @NodeAnotation(title="发起审批")
     */
    public function agree($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        $row['status'] !=1||$row['status'] !=2 && $this->error('审批中或已生效，不需要提交!');
        $row['isfile'] ==1 && $this->error('已归档，不需要提交!');
        //查询是否设置审批流程
        $request = new Request();
        $currentController = parseNodeStr(Request::controller());
        $step = $this->step->where("model",$currentController)->find();
        empty($step) && $this->error('请先去设置审批流程','',__url('admin/system.step/add'));
        //是否发起人
        $starpeople = json_decode($step->processConfig)->flowPermission;
        if(count($starpeople)>0){
            $starpeople = array_column($starpeople,null,'targetId');//将数组的键值作为数组索引
            !array_key_exists(session('admin.id'),$starpeople) && $this->error('您不是该流程的发起人！');
        }
        if ($this->request->isPost()) {
            try {
                //修改状态审批中
                $up = $this->model->where('id',$id)->update(['status'=>1]);//测试改为0，正式改为1，审批中
                //添加审批记录
                $stepflow = array();
                $stepflow['model'] = $currentController;
                $stepflow['title'] = session('admin.username')."发起的".$step->title;
                $stepflow['a_id'] = $id;
                $stepflow['u_id'] = session('admin.id');
                $stepflow['current_step'] = 1;
                $save = $this->stepflow->save($stepflow);
                //添加审批待办
                addflowDetail(json_decode($step->processConfig)->nodeConfig->childNode,$this->stepflow,$this->model);
                
            } catch (\Exception $e) {
                $this->error('发起审批失败'.$e->getMessage());
            }
            $save&&$up ? $this->success('发起审批成功') : $this->error('发起审批失败');
        }
        
    }
    
    /**
     * @NodeAnotation(title="签署")
     */
    public function sign($id)
    {
        $row = $this->model->withJoin(['systemAdmin','customerInfo','customerInfoBuyer','customerInfoReceiving','customerInfoSettlement','contractType'], 'LEFT')->find($id);
        empty($row) && $this->error('数据不存在');
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $post['isour'] = $post['isour']=='on'?1:0;
                $post['isother'] = $post['isother']=='on'?1:0;
                $post['oursigndate'] = strtotime($post['oursigndate']);
                $post['othersigndate'] = strtotime($post['othersigndate']);
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('签署失败'.$e->getMessage());
            }
            $save ? $this->success('签署成功') : $this->error('签署失败');
        }
        $row->oursigndate = empty($row->oursigndate)?'':date("Y-m-d",$row->oursigndate);
        $row->othersigndate= empty($row->othersigndate)?'':date("Y-m-d",$row->othersigndate);
        $this->assign('row', $row);
        return $this->fetch();
    }
    /**
     * @NodeAnotation(title="归档")
     */
    public function file($id)
    {
        $row = $this->model->withJoin(['systemAdmin','customerInfo','customerInfoBuyer','customerInfoReceiving','customerInfoSettlement','contractType'], 'LEFT')->find($id);
        empty($row) && $this->error('数据不存在');
        $row->status!=2||$row->isour!=1||$row->isother!=1 && $this->error('未生效或未签署，不能归档！');
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $post['isfile'] = 1;
                $post['filedate'] = strtotime($post['filedate']);
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('归档失败'.$e->getMessage());
            }
            $save ? $this->success('归档成功') : $this->error('归档失败');
        }
        $row->filedate = empty($row->filedate)?'':date("Y-m-d",$row->filedate);
        $this->assign('row', $row);
        return $this->fetch();
    }
    
    //审批通过回调
    public function useful($id){
        $up['status'] = 2;
        $this->model->where('id',$id)->update($up);
    }
    
    public function test($id){
        try {
            $row = $this->model->withJoin(['systemAdmin','customerInfo','customerInfoBuyer','customerInfoReceiving','customerInfoSettlement','contractType'], 'LEFT')->find($id);
            $row->startdate = date("Y-m-d",$row->startdate);
            $row->enddate = date("Y-m-d",$row->enddate);
            $row->detail = $this->detail->where('c_id',$row->id)->select();
            if ($row->road==1){
                $row->road = $this->model->getContractShipList()[$row->road]." 始发地：".$this->model->getContractHighwaysiteList()[$row->start_station]." 目的地：".$this->model->getContractHighwaysiteList()[$row->end_station];
            }elseif ($row->road==2){
                $row->road = $this->model->getContractShipList()[$row->road]." 发站：".$this->model->getContractRailwaysiteList()[$row->start_station]." 到站：".$this->model->getContractRailwaysiteList()[$row->end_station];
            }
            $row->delivery = $this->model->getContractDeliveryList()[$row->delivery];
            $row->check_num = $this->model->getCheckNumList()[$row->check_num];
            $row->check_type= $this->model->getCheckTypeList()[$row->check_type];
            
            $row->jiesuan = $this->model->getSettleList()[$row->settle]." ".$this->model->getFreightList()[$row->freight]." ".$this->model->getMarkupList()[$row->markup]."(".$row->markupnum.")"." ".$this->model->getTaxList()[$row->tax]." 预付款比例".$row->advance."(".$row->advance_remark.")";
            
            $row->detail = $this->detail->where('c_id',$row->id)->select();
            foreach ($row->detail as $del){
                $del->type = $this->model->getContractCoaltypeList()[$del->type];
                $qtype = $this->quality->where('status',1)->group('name')->order('id asc')->select();
                
                foreach ($qtype as $k=>$v){
                    
                    $qualitydetail = $this->qualitydetail->where(['cd_id'=>$del->id,'qname'=>$v->name])->select();
                    foreach ($qualitydetail as $qd){
                        $del->jc = $del->jc.($k+1).".".$v->name."：".$qd->name.$qd->standard."。";
                        $level = $this->qualitylevel->where('cqd_id',$qd->id)->select();
                        if(count($level)>0){
                            foreach ($level as $le){
                                $jc1 = "";
                                $jc2 = "";
                                $jc3 = "";
                                $jc4 = "";
                                if(!empty($le->qz4)){
                                    $jc1 = $le->qz3.$le->qz4;
                                }
                                if(!empty($le->qz6)){
                                    $jc2 = $le->qz5.$le->qz6;
                                }
                                if(empty($le->qz8)){
                                    $jc3 = "不加不减";
                                }else{
                                    $jc3 = $le->qz7.$le->qz8;
                                }
                                if(!empty($le->qz10)){
                                    $jc4 = $le->qz9.$le->qz10;
                                }
                                $del->jc = $del->jc.$jc1."实际值".$jc2.",，".$jc3.$jc4."；";
                            }
                        }
                    }
                }
            }
            
            if($row->type=='1'){
                $tmp = new TemplateProcessor('upload/contract/template/xsht.docx');
            }else{
                $tmp = new TemplateProcessor('upload/contract/template/cght.docx');
            }
            $set = new Settings();
            $set->setOutputEscapingEnabled(true);
            $arr = $row->detail;
            $length = count($arr);//总行数
            $tmp->cloneRow('type', $length);//复制行
            
            for ($i = 0; $i < $length; $i++) {
                $tmp->setValue("type#" . ($i + 1), $arr[$i]['type']);//替换变量
                $tmp->setValue("num#" . ($i + 1), $arr[$i]['num']);
                $tmp->setValue("price#" . ($i + 1), $arr[$i]['price']);
                $tmp->setValue("jc#" . ($i + 1), $arr[$i]['jc']);
            }
            $tmp->setValue('number', $row->number);
            $tmp->setValue('address', $this->model->getContractAddressList()[$row->address]);
            $tmp->setValue('buyer', $row->customerInfoBuyer->name);
            $tmp->setValue('date', date("Y-m-d",$row->date));
            $tmp->setValue('startdate', $row->startdate);
            $tmp->setValue('enddate', $row->enddate);
            $tmp->setValue('road', $row->road);
            $tmp->setValue('delivery', $row->delivery);
            $tmp->setValue('delivery_address', $this->model->getContractAddressList()[$row->delivery_address]);
            $tmp->setValue('receivingname', $row->customerInfoReceiving->name);
            $tmp->setValue('check_num', $row->check_num);
            $tmp->setValue('check_type', $row->check_type);
            $tmp->setValue('jiesuan', $row->jiesuan);
//             $parser = new Parser();
//             $toOpenXML = $parser->fromHTML("<p>dfdfs</p>");
//             var_dump($toOpenXML);
            $tmp->setValue('law', $row->law);
            $tmp->saveAs("upload/contract/bb.docx");//另存为
        } catch (\Exception $e) {
            $this->error('签署失败'.$e->getMessage());die;
        }
    }
}