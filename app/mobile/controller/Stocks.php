<?php

namespace app\mobile\controller;


use app\common\controller\AdminController;
use think\App;

class Stocks extends AdminController
{
    protected $layout = FALSE;
    
    protected $sort = [
        'CskID' => 'desc',
    ];
    
    public function __construct(App $app)
    {
        parent::__construct($app);
        
        $this->model = new \app\admin\model\CoalStocks();
        
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
            $where[] = ['ischeck','=',0];
            $where[] = ['ProcessCtrl_Authorizor','=',session('admin.userCode')];
            $where[] = ['ptype','=',3];
            $count = $this->model
            ->withJoin(['processCtrl'], 'LEFT')
            ->join('Processcls processCls','processCtrl.ProcessCls_Code=processCls.ProcessCls_Id')
            ->where($where)
            ->count();
            $list = $this->model
            ->withJoin(['processCtrl'], 'LEFT')
            ->join('Processcls processCls','processCtrl.ProcessCls_Code=processCls.ProcessCls_Id')
            ->where($where)
            ->page($page, $limit)
            ->order($this->sort)
            ->select();
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        $this->assign('menu',2);
        $this->assign('title',"仓储建库审批");
        return $this->fetch();
    }
    
    public function detail($id)
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $saledpt= $this->model->where('CskID',$id)->find();
            $where1[] = ['ptype','=',3];
            $where1[] = ['processCtrl_Sequence','>',$saledpt->checkNo];
            $step = $this->processCtrl
            ->withJoin(['processCls'], 'LEFT')
            ->where($where1)
            ->count();
            if($step){//有下一步
                $up['checkPeo'.$saledpt->checkNo] = session('admin.userName');
                $up['checkCon'.$saledpt->checkNo] = $post['yj'];
                $up['checkNo'] = $saledpt->checkNo+1;
                $save = $this->model->where('CskID',$id)->update($up);
                //发送消息下一步审核人
                $message = [
                    'touser' => getnext(4,$saledpt->checkNo+1),
                    'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                    'url' => 'http://wap.rizhaolanhua.com/Saledepartment/detail?id='.$id,
                    'data' => [
                        'first' => ['value' => getname($saledpt->SdOperator).'发起的仓储建库审批', 'color' => '#333'],
                        'keyword1' => ['value' => '仓储建库审批', 'color' => '#333'],
                        'keyword2' => ['value' => $saledpt->CskTypeName, 'color' => '#333'],
                        'remark' => ['value' => '请尽快审批！！', 'color' => '#333']
                    ],
                ];
                sendTemplateMessage($message);
                $save?$this->success('审批成功,进入下一步骤'):$this->error('审批失败');
            }else{//没有下一步
                $up['checkPeo'.$saledpt->checkNo] = session('admin.userName');
                $up['checkCon'.$saledpt->checkNo] = $post['yj'];
                $up['ischeck'] = 1;
                $save = $this->model->where(['CskID' => $id])->update($up);
                //发送消息审核通过
                $message = [
                    'touser' => getopenid($saledpt->cskAddpeo),
                    'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                    'url' => 'http://wap.rizhaolanhua.com/Saledepartment/detail?id='.$id,
                    'data' => [
                        'first' => ['value' => '您发起的仓储建库审批已审核', 'color' => '#333'],
                        'keyword1' => ['value' => '仓储建库审批', 'color' => '#333'],
                        'keyword2' => ['value' => $saledpt->CskTypeName, 'color' => '#333'],
                        'remark' => ['value' => '高高兴兴上班去  平平安安回家来！', 'color' => '#333']
                    ],
                ];
                sendTemplateMessage($message);
                $save?$this->success('审批成功'):$this->error('审批失败');
            }
        }
        $get = $this->request->get();
        $id = empty($get['id'])?'':$get['id'];
        $saledpt= $this->model->where('CskID',$id)->find();
        $saledpt->processCtrl = $this->processCtrl
                                    ->withJoin(['processCls'], 'LEFT')
                                    ->where(['processCtrl_Sequence'=>$saledpt->checkNo,'ptype' => 3])
                                    ->find();
        //文件类型
        $saledpt->filetype = getfiletype($saledpt->cFile);
        if($saledpt){
            for ($i=1;$i<=$saledpt->checkNo;$i++){
                $saledpt->step .= "<p>".$saledpt['checkPeo'.$i]."：".$saledpt['checkCon'.$i]."</p>";
            }
        }
        
        $this->assign('saledpt',$saledpt);
        $this->assign('menu',2);
        $this->assign('title',"仓储建库审批");
        return $this->fetch();
    }
    public function content($id){
        $get = $this->request->get();
        $id = empty($get['id'])?'':$get['id'];
        $saledpt= $this->model->where('CskID',$id)->find();
        $saledpt->processCtrl = $this->processCtrl
        ->withJoin(['processCls'], 'LEFT')
        ->where(['processCtrl_Sequence'=>$saledpt->checkNo,'ptype' => 3])
        ->find();
        
        $this->assign('saledpt',$saledpt);
        $this->assign('menu',2);
        $this->assign('title',"仓储建库审批");
        return $this->fetch();
    }
    
    public function refund($id){
        $post = $this->request->post();
        $contract = $this->model->where('CskID',$id)->find();
        $up = $this->model->where('CskID',$id)->update(['ischeck'=>2,'checkPeo'.$contract->checkNo=>session('admin.userName'),'checkCon'.$contract->checkNo=>$post['yj']]);
        if(!$up){
            $data = [
                'code'  => -1,
                'msg'   => '退回失败！请重新操作',
            ];
        }else{
            //发送消息审核通过
            $message = [
                'touser' => getopenid($contract->cskAddpeo),
                'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                'url' => 'http://wap.rizhaolanhua.com/Contract/detail?id='.$id,
                'data' => [
                    'first' => ['value' => '您发起的仓储建库审批已回退', 'color' => '#333'],
                    'keyword1' => ['value' => '仓储建库审批', 'color' => '#333'],
                    'keyword2' => ['value' => $contract->CskTypeName, 'color' => '#333'],
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