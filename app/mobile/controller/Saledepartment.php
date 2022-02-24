<?php

namespace app\mobile\controller;


use app\common\controller\AdminController;
use think\App;

class Saledepartment extends AdminController
{
    protected $layout = FALSE;
    
    protected $sort = [
        'SdName' => 'desc',
    ];
    
    public function __construct(App $app)
    {
        parent::__construct($app);
        
        $this->model = new \app\admin\model\SaleDepartment();
        
        $this->employ = new \app\admin\model\Employ();
        
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
            $where1[] = ['lcType','=',1];
            $where1[] = ['ptype','=',1];
            $where2[] = ['ischeck','=',0];
            $where2[] = ['ProcessCtrl_Authorizor','=',session('admin.userCode')];
            $where2[] = ['lcType','=',2];
            $where2[] = ['ptype','=',20];
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
//                         var_dump($this->model->getLastSql());die;
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        $this->assign('menu',2);
        $this->assign('title',"客户准入审批");
        return $this->fetch();
    }
    
    public function detail($id)
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $saledpt= $this->model->where('SdID',$id)->find();
            if($saledpt->lcType==1){//销售合同
                $where1[] = ['ptype','=',1];
                $where1[] = ['processCtrl_Sequence','>',$saledpt->checkNo];
                $step = $this->processCtrl
                ->withJoin(['processCls'], 'LEFT')
                ->where($where1)
                ->count();
                if($step){//有下一步
                    $up['checkPeo'.$saledpt->checkNo] = session('admin.userName');
                    $up['checkCon'.$saledpt->checkNo] = $post['yj'];
                    $up['checkNo'] = $saledpt->checkNo+1;
                    $save = $this->model->where('SdID',$id)->update($up);
                    //发送消息下一步审核人
                    $message = [
                        'touser' => getnext(1,$saledpt->checkNo+1),
                        'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                        'url' => 'http://wap.rizhaolanhua.com/Saledepartment/detail?id='.$id,
                        'data' => [
                            'first' => ['value' => getname($saledpt->SdOperator).'发起的客户准入审批', 'color' => '#333'],
                            'keyword1' => ['value' => '客户准入审批', 'color' => '#333'],
                            'keyword2' => ['value' => $saledpt->SdName, 'color' => '#333'],
                            'remark' => ['value' => '请尽快审批！！', 'color' => '#333']
                        ],
                    ];
                    sendTemplateMessage($message);
                    $save?$this->success('审批成功,进入下一步骤'):$this->error('审批失败');
                }else{//没有下一步
                    $up['checkPeo'.$saledpt->checkNo] = session('admin.userName');
                    $up['checkCon'.$saledpt->checkNo] = $post['yj'];
                    $up['ischeck'] = 1;
                    $save = $this->model->where(['SdID' => $id])->update($up);
                    //发送消息审核通过
                    $message = [
                        'touser' => getopenid($saledpt->cOperator),
                        'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                        'url' => 'http://wap.rizhaolanhua.com/Saledepartment/detail?id='.$id,
                        'data' => [
                            'first' => ['value' => '您发起的客户准入审批已审核', 'color' => '#333'],
                            'keyword1' => ['value' => '客户准入审批', 'color' => '#333'],
                            'keyword2' => ['value' => $saledpt->SdName, 'color' => '#333'],
                            'remark' => ['value' => '高高兴兴上班去  平平安安回家来！', 'color' => '#333']
                        ],
                    ];
                    sendTemplateMessage($message);
                    $save?$this->success('审批成功'):$this->error('审批失败');
                }
            }elseif ($saledpt->lcType==2){//采购合同
                $where1[] = ['ptype','=',20];
                $where1[] = ['processCtrl_Sequence','>',$saledpt->checkNo];
                $step = $this->processCtrl
                ->withJoin(['processCls'], 'LEFT')
                ->where($where1)
                ->count();
                if($step){//有下一步
                    $up['checkPeo'.$saledpt->checkNo] = session('admin.userName');
                    $up['checkCon'.$saledpt->checkNo] = $post['yj'];
                    $up['checkNo'] = $saledpt->checkNo+1;
                    $save = $this->model->where('SdID',$id)->update($up);
                    //发送消息下一步审核人
                    $message = [
                        'touser' => getnext(20,$saledpt->checkNo+1),
                        'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                        'url' => 'http://wap.rizhaolanhua.com/Saledepartment/detail?id='.$id,
                        'data' => [
                            'first' => ['value' => getname($saledpt->SdOperator).'发起的客户准入审批', 'color' => '#333'],
                            'keyword1' => ['value' => '客户准入审批', 'color' => '#333'],
                            'keyword2' => ['value' => $saledpt->SdName, 'color' => '#333'],
                            'remark' => ['value' => '请尽快审批！！', 'color' => '#333']
                        ],
                    ];
                    sendTemplateMessage($message);
                    $save?$this->success('审批成功,进入下一步骤'):$this->error('审批失败');
                }else{//没有下一步
                    $up['checkPeo'.$saledpt->checkNo] = session('admin.userName');
                    $up['checkCon'.$saledpt->checkNo] = $post['yj'];
                    $up['ischeck'] = 1;
                    $save = $this->model->where(['SdID' => $id])->update($up);
                    //发送消息审核通过
                    $message = [
                        'touser' => getopenid($saledpt->cOperator),
                        'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                        'url' => 'http://wap.rizhaolanhua.com/Saledepartment/detail?id='.$id,
                        'data' => [
                            'first' => ['value' => '您发起的客户准入审批已审核', 'color' => '#333'],
                            'keyword1' => ['value' => '客户准入审批', 'color' => '#333'],
                            'keyword2' => ['value' => $saledpt->SdName, 'color' => '#333'],
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
        $saledpt= $this->model->where('SdID',$id)->find();
        $saledpt->processCtrl = $this->processCtrl
                                    ->withJoin(['processCls'], 'LEFT')
                                    ->where(['processCtrl_Sequence'=>$saledpt->checkNo,'ptype' => 1])
                                    ->whereOr(function($query) use ($saledpt){
                                        $query->where(['processCtrl_Sequence'=>$saledpt->checkNo,'ptype' => 20]);
                                    })
                                    ->find();
        if($saledpt->SdType){
            $saledpt->SdType= $this->model->getSdType()[$saledpt->SdType];
        }
        //文件类型
        $saledpt->filetype = getfiletype($saledpt->cFile);
        $saledpt->filetype2 = getfiletype($saledpt->cFile2);
        $saledpt->filetype3 = getfiletype($saledpt->cFile3);
        $saledpt->filetype4 = getfiletype($saledpt->cFile4);
        if($saledpt){
            for ($i=1;$i<=$saledpt->checkNo;$i++){
                $saledpt->step .= "<p>".$saledpt['checkPeo'.$i]."：".$saledpt['checkCon'.$i]."</p>";
            }
        }
        
        $this->assign('saledpt',$saledpt);
        $this->assign('menu',2);
        $this->assign('title',"客户准入审批");
        return $this->fetch();
    }
    public function content($id){
        $get = $this->request->get();
        $id = empty($get['id'])?'':$get['id'];
        $saledpt= $this->model->where('SdID',$id)->find();
        $saledpt->processCtrl = $this->processCtrl
        ->withJoin(['processCls'], 'LEFT')
        ->where(['processCtrl_Sequence'=>$saledpt->checkNo,'ptype' => 1])
        ->whereOr(function($query) use ($saledpt){
            $query->where(['processCtrl_Sequence'=>$saledpt->checkNo,'ptype' => 20]);
        })
        ->find();
        if($saledpt->SdType){
            $saledpt->SdType= $this->model->getSdType()[$saledpt->SdType];
        }
        for ($i=1;$i<=$saledpt->checkNo;$i++){
            $saledpt->step .= "<p>".$saledpt['checkPeo'.$i]."：".$saledpt['checkCon'.$i]."</p>";
        }
        $saledpt->SdOperator = $this->employ->where("userCode",$saledpt->SdOperator)->find()['userName'];
        
        $this->assign('saledpt',$saledpt);
        $this->assign('menu',2);
        $this->assign('title',"客户准入审批");
        return $this->fetch();
    }
    
    public function refund($id){
        $post = $this->request->post();
        $contract = $this->model->where('SdID',$id)->find();
        $up = $this->model->where('SdID',$id)->update(['ischeck'=>2,'checkPeo'.$contract->checkNo=>session('admin.userName'),'checkCon'.$contract->checkNo=>$post['yj']]);
        if(!$up){
            $data = [
                'code'  => -1,
                'msg'   => '退回失败！请重新操作',
            ];
        }else{
            //发送消息审核通过
            $message = [
                'touser' => getopenid($contract->SdOperator),
                'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                'url' => 'http://wap.rizhaolanhua.com/Contract/detail?id='.$id,
                'data' => [
                    'first' => ['value' => '您发起的客户准入审批已回退', 'color' => '#333'],
                    'keyword1' => ['value' => '客户准入审批', 'color' => '#333'],
                    'keyword2' => ['value' => $contract->SdName, 'color' => '#333'],
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
}