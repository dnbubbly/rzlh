<?php

namespace app\mobile\controller;


use app\common\controller\AdminController;
use think\App;

class Contract extends AdminController
{
    protected $layout = FALSE;
    
    protected $sort = [
        'cdate' => 'desc',
    ];
    
    public function __construct(App $app)
    {
        parent::__construct($app);
        
        $this->model = new \app\admin\model\Contract();
        
        $this->saleDepartment= new \app\admin\model\SaleDepartment();
        
        $this->employ= new \app\admin\model\Employ();
        
        $this->department= new \app\admin\model\Department();
        
        $this->contractDetail = new \app\admin\model\ContractDetail();
        
        $this->processCls= new \app\admin\model\ProcessCls();
        
        $this->processCtrl= new \app\admin\model\ProcessCtrl();
        
    }
    
    /**
     * 首页
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $page = isset($post['page']) && !empty($post['page']) ? $post['page'] : 1;
            $limit = isset($post['limit']) && !empty($post['limit']) ? $post['limit'] : 15;
            $list= array();
            $where1[] = ['ischeck','=',0];
            $where1[] = ['ProcessCtrl_Authorizor','=',session('admin.userCode')];
            $where1[] = ['cType','=',1];
            $where1[] = ['ptype','=',2];
            $where2[] = ['ischeck','=',0];
            $where2[] = ['ProcessCtrl_Authorizor','=',session('admin.userCode')];
            $where2[] = ['cType','=',2];
            $where2[] = ['ptype','=',21];
            $count = $this->model
            ->withJoin(['processCtrl'], 'LEFT')
            ->join('Processcls processCls','processCtrl.ProcessCls_Code=processCls.ProcessCls_Id')
            ->where($where1)
            ->whereOr(function($query) use ($where2){
                $query->where($where2);
            })
            ->count();
            $list = $this->model
            ->withJoin(['processCtrl'], 'LEFT')
            ->join('Processcls processCls','processCtrl.ProcessCls_Code=processCls.ProcessCls_Id')
            ->where($where1)
            ->whereOr(function($query) use ($where2){
                $query->where($where2);
            })
            ->page($page, $limit)
            ->order($this->sort)
            ->select();
//             var_dump($this->model->getLastSql());die;
            foreach ($list as $li){
                $date=date_create($li->cDate);
                $li['cDate'] = date_format($date,"Y-m-d");
            }
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        $this->assign('menu',2);
        $this->assign('title',"合同审批");
        return $this->fetch();
    }
    
    public function detail($id)
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $contract = $this->model->where('cid',$id)->find();
            if($contract->cType==1){//销售合同
                $where1[] = ['ptype','=',2];
                $where1[] = ['processCtrl_Sequence','>',$contract->checkNo];
                $step = $this->processCtrl
                        ->withJoin(['processCls'], 'LEFT')
                        ->where($where1)
                        ->count();
                if($step){//有下一步
                    $up['checkPeo'.$contract->checkNo] = session('admin.userName');
                    $up['checkCon'.$contract->checkNo] = $post['yj'];
                    $up['checkDate'.$contract->checkNo] = date("Y-m-d H:i:s",time());
                    $up['checkNo'] = $contract->checkNo+1;
                    $save = $this->model->where('cid',$id)->update($up);
                    //发送消息下一步审核人
                    $message = [
                        'touser' => getnext(2,$contract->checkNo+1),
                        'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                        'url' => 'http://wap.rizhaolanhua.com/Contract/detail?id='.$id,
                        'data' => [
                            'first' => ['value' => getname($contract->cOperator).'发起的销售通知审批', 'color' => '#333'],
                            'keyword1' => ['value' => '销售通知审批', 'color' => '#333'],
                            'keyword2' => ['value' => '合同编号：'.$contract->cCode, 'color' => '#333'],
                            'remark' => ['value' => '请尽快审批！！', 'color' => '#333']
                        ],
                    ];
                    sendTemplateMessage($message);
                    $save?$this->success('审批成功,进入下一步骤'):$this->error('审批失败');
                }else{//没有下一步
                    $up['checkPeo'.$contract->checkNo] = session('admin.userName');
                    $up['checkCon'.$contract->checkNo] = $post['yj'];
                    $up['checkDate'.$contract->checkNo] = date("Y-m-d H:i:s",time());
                    $up['ischeck'] = 1;
                    $save = $this->model->where(['cid' => $id])->update($up);
                    //发送消息审核通过
                    $message = [
                        'touser' => getopenid($contract->cOperator),
                        'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                        'url' => 'http://wap.rizhaolanhua.com/Contract/detail?id='.$id,
                        'data' => [
                            'first' => ['value' => '您发起的销售合同审批已审核', 'color' => '#333'],
                            'keyword1' => ['value' => '销售合同审批', 'color' => '#333'],
                            'keyword2' => ['value' => '合同编号：'.$contract->cCode, 'color' => '#333'],
                            'remark' => ['value' => '高高兴兴上班去  平平安安回家来！', 'color' => '#333']
                        ],
                    ];
                    sendTemplateMessage($message);
                    $save?$this->success('审批成功'):$this->error('审批失败');
                }
            }elseif ($contract->cType==2){//采购合同
                $where1[] = ['ptype','=',21];
                $where1[] = ['processCtrl_Sequence','>',$contract->checkNo];
                $step = $this->processCtrl
                ->withJoin(['processCls'], 'LEFT')
                ->where($where1)
                ->count();
                if($step){//有下一步
                    $up['checkPeo'.$contract->checkNo] = session('admin.userName');
                    $up['checkCon'.$contract->checkNo] = $post['yj'];
                    $up['checkDate'.$contract->checkNo] = date("Y-m-d H:i:s",time());
                    $up['checkNo'] = $contract->checkNo+1;
                    $save = $this->model->where('cid',$id)->update($up);
                    //发送消息下一步审核人
                    $message = [
                        'touser' => getnext(21,$contract->checkNo+1),
                        'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                        'url' => 'http://wap.rizhaolanhua.com/Contract/detail?id='.$id,
                        'data' => [
                            'first' => ['value' => getname($contract->cOperator).'发起的采购通知审批', 'color' => '#333'],
                            'keyword1' => ['value' => '采购通知审批', 'color' => '#333'],
                            'keyword2' => ['value' => '合同编号：'.$contract->cCode, 'color' => '#333'],
                            'remark' => ['value' => '请尽快审批！！', 'color' => '#333']
                        ],
                    ];
                    sendTemplateMessage($message);
                    $save?$this->success('审批成功,进入下一步骤'):$this->error('审批失败');
                }else{//没有下一步
                    $up['checkPeo'.$contract->checkNo] = session('admin.userName');
                    $up['checkCon'.$contract->checkNo] = $post['yj'];
                    $up['checkDate'.$contract->checkNo] = date("Y-m-d H:i:s",time());
                    $up['ischeck'] = 1;
                    $save = $this->model->where(['cid' => $id])->update($up);
                    //发送消息审核通过
                    $message = [
                        'touser' => getopenid($contract->cOperator),
                        'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                        'url' => 'http://wap.rizhaolanhua.com/Contract/detail?id='.$id,
                        'data' => [
                            'first' => ['value' => '您发起的采购合同审批已审核', 'color' => '#333'],
                            'keyword1' => ['value' => '采购合同审批', 'color' => '#333'],
                            'keyword2' => ['value' => '合同编号：'.$contract->cCode, 'color' => '#333'],
                            'remark' => ['value' => '高高兴兴上班去  平平安安回家来！', 'color' => '#333']
                        ],
                    ];
                    sendTemplateMessage($message);
                    $save?$this->success('审批成功'):$this->error('审批失败');
                }
            }
        }
        $get = $this->request->get();
        $id = empty($get['id'])?'':$get['id'];
        $contract = $this->model->where('cid',$id)->find();
        $contract->processCtrl = $this->processCtrl
                                ->withJoin(['processCls'], 'LEFT')
                                ->where(['processCtrl_Sequence'=>$contract->checkNo,'ptype' => 2])
                                ->whereOr(function($query) use ($contract){
                                    $query->where(['processCtrl_Sequence'=>$contract->checkNo,'ptype' => 21]);
                                })
                                ->find();
//         $contract->cDate= date_format(date_create($contract->cDate),"Y-m-d");
//         $contract->cjhdate= date_format(date_create($contract->cjhdate),"Y-m-d");
//         $contract->cslcheck= $this->model->getCslcheck()[$contract->cslcheck];
//         $contract->cChegndTime= $this->model->getCChegndTime()[$contract->cChegndTime];
//         $contract->cbuchang= $this->model->getCbuchang()[$contract->cbuchang];
//         $contract->cYwType= $this->model->getCYwType()[$contract->cYwType];
        $contract->cStartDate= date_format(date_create($contract->cStartDate),"Y-m-d");
        $contract->cEndDate= date_format(date_create($contract->cEndDate),"Y-m-d");
        //文件类型
        $contract->filetype = getfiletype($contract->cFile);
        $contract->filetype2 = getfiletype($contract->cFile2);
        for ($i=1;$i<=$contract->checkNo;$i++){
            $contract->step .= "<p>".$contract['checkPeo'.$i]."：".$contract['checkCon'.$i]."</p>";
        }
        //质量标准
        $frl = $this->contractDetail->where(['cid'=>32,'csXm'=>1])->order("csid asc")->select();//发热量
        $this->assign('frl',$frl);
        $lf = $this->contractDetail->where(['cid'=>32,'csXm'=>2])->order("csid asc")->select();//硫分
        $this->assign('lf',$lf);
        $hf = $this->contractDetail->where(['cid'=>32,'csXm'=>3])->order("csid asc")->select();//灰分
        $this->assign('hf',$hf);
        $hff = $this->contractDetail->where(['cid'=>32,'csXm'=>4])->order("csid asc")->select();//挥发分
        $this->assign('hff',$hff);
        $kmzs = $this->contractDetail->where(['cid'=>32,'csXm'=>5])->order("csid asc")->select();//可磨指数
        $this->assign('kmzs',$kmzs);
        $sf = $this->contractDetail->where(['cid'=>32,'csXm'=>6])->order("csid asc")->select();//水分
        $this->assign('sf',$sf);
        $this->assign('contract',$contract);
        $this->assign('menu',2);
        $this->assign('title',"合同审批");
        return $this->fetch();
    }

    public function refund($id){
        $post = $this->request->post();
        $contract = $this->model->where('cid',$id)->find();
        $up = $this->model->where('cid',$id)->update(['ischeck'=>2,'checkPeo'.$contract->checkNo=>session('admin.userName'),'checkDate'.$contract=>date("Y-m-d h:i:s",time()),'checkCon'.$contract->checkNo=>$post['yj']]);
        if(!$up){
            $data = [
                'code'  => -1,
                'msg'   => '退回失败！请重新操作',
            ];
        }else{
            //发送消息审核通过
            $message = [
                'touser' => getopenid($contract->cOperator),
                'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                'url' => 'http://wap.rizhaolanhua.com/Contract/detail?id='.$id,
                'data' => [
                    'first' => ['value' => '您发起的合同审批已回退', 'color' => '#333'],
                    'keyword1' => ['value' => '合同审批', 'color' => '#333'],
                    'keyword2' => ['value' => '合同编号：'.$contract->cCode, 'color' => '#333'],
                    'remark' => ['value' => '请仔细检查重新上传！', 'color' => '#333']
                ],
            ];
            sendTemplateMessage($message);
            $data = [
                'code'  => 0,
                'msg'   => '退回成功',
            ];
        }
        return json($data);
    }
    
    public function content(){
        $get = $this->request->get();
        $get = $this->request->get();
        $id = empty($get['id'])?'':$get['id'];
        $contract = $this->model->where('cid',$id)->find();
        $contract->processCtrl = $this->processCtrl
        ->withJoin(['processCls'], 'LEFT')
        ->where(['processCtrl_Sequence'=>$contract->checkNo,'ptype' => 2])
        ->whereOr(function($query) use ($contract){
            $query->where(['processCtrl_Sequence'=>$contract->checkNo,'ptype' => 21]);
        })
        ->find();
//         $contract->cDate= date_format(date_create($contract->cDate),"Y-m-d");
//         $contract->cjhdate= date_format(date_create($contract->cjhdate),"Y-m-d");
//         $contract->cslcheck= $this->model->getCslcheck()[$contract->cslcheck];
//         $contract->cChegndTime= $this->model->getCChegndTime()[$contract->cChegndTime];
//         $contract->cbuchang= $this->model->getCbuchang()[$contract->cbuchang];
//         $contract->cYwType= $this->model->getCYwType()[$contract->cYwType];
        $contract->cStartDate= date_format(date_create($contract->cStartDate),"Y-m-d");
        $contract->cEndDate= date_format(date_create($contract->cEndDate),"Y-m-d");
        //文件类型
        $contract->filetype = getfiletype($contract->cFile);
        $contract->filetype2 = getfiletype($contract->cFile2);
        for ($i=1;$i<$contract->checkNo;$i++){
            $contract->step .= "<p>".$contract['checkPeo'.$i]."：".$contract['checkCon'.$i]."</p>";
        }
        if ($contract->cType==1){
            $department = $this->saleDepartment->where('SdName',$contract->cBuyer)->find();
        }elseif ($contract->cType==2){
            $department = $this->saleDepartment->where('SdName',$contract->cSeller)->find();
        }
        $employ = $this->employ->where('userCode',$contract->cOperator)->find();
        $edpart = $this->department->where('DepCode',$employ->depCode)->find();
        
        $this->assign('edpart',$edpart);
        $this->assign('department',$department);
        $this->assign('contract',$contract);
        $this->assign('menu',2);
        $this->assign('title',"合同会审");
        return $this->fetch();
    }
    public function test(){
        return $this->fetch();
    }
}
