<?php

namespace app\admin\controller\contract;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="contract_info")
 */
class Info extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\ContractInfo();
        
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
    
}