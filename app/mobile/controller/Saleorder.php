<?php
namespace app\mobile\controller;


use app\common\controller\AdminController;
use think\App;
use think\facade\Db;

class Saleorder extends AdminController
{
    protected $layout = FALSE;
    
    protected $sort = [
        'sDate' => 'desc',
    ];
    
    public function __construct(App $app)
    {
        parent::__construct($app);
        
        $this->model = new \app\admin\model\SalesOrder();
        
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
            $where[] = ['ProcessCtrl_Authorizor','=',];
            $where[] = ['sType','=','销售'];
            $where[] = ['ptype','=',4];
            $subQuery = "( SELECT [sPlace],[sCode],[ghdw],[sShdep],stuff((select ',' + convert(varchar(25), sid)
                   from [Tbl_SalesOrder] as t where [sales_order].sPlace = t.sPlace  for xml path('')),1,1,'') as ids FROM [Tbl_SalesOrder] [sales_order] LEFT JOIN [Tbl_processctrl] [processCtrl] ON [sales_order].[checkNo] = [processCtrl].[processCtrl_Sequence] INNER JOIN [Tbl_processcls] [processCls] ON [processCtrl].[ProcessCls_Code] = [processCls].[ProcessCls_Id] WHERE [ischeck] = 0  AND [ProcessCtrl_Authorizor] = '".session('admin.userCode')."'  AND [sType] = '销售'  AND [ptype] = 4  GROUP BY [sPlace],[sCode],[ghdw],[sShdep] )";

            $count = Db::table($subQuery . ' a')->count();
            $list = Db::table($subQuery . ' b')->page($page, $limit)->select();
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
        $this->assign('title',"销售通知审批");
        return $this->fetch();
    }
    
    public function detail($id)
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $ids = explode(',',$id);
            $i=0;
            foreach ($ids as $v){
                $saledpt= $this->model->where('sid',$v)->find();
                $where[] = ['ptype','=',4];
                $where[] = ['processCtrl_Sequence','>',$saledpt->checkNo];
                $step = $this->processCtrl
                ->withJoin(['processCls'], 'LEFT')
                ->where($where)
                ->count();
                if($step){//有下一步
                    $up['checkPeo'.$saledpt->checkNo] = session('admin.userName');
                    $up['checkCon'.$saledpt->checkNo] = $post['yj'];
                    $up['checkNo'] = $saledpt->checkNo+1;
                    $save = $this->model->where('sid',$v)->update($up);
                    if ($i == count($ids)- 1) {
                        //发送消息下一步审核人
                        $message = [
                            'touser' => getnext(4,$saledpt->checkNo+1),
                            'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                            'url' => 'http://wap.rizhaolanhua.com/Saledepartment/detail?id='.$id,
                            'data' => [
                                'first' => ['value' => getname($saledpt->sOperator).'发起的销售通知审批', 'color' => '#333'],
                                'keyword1' => ['value' => '销售通知审批', 'color' => '#333'],
                                'keyword2' => ['value' => '编号：'.$saledpt->sPlace, 'color' => '#333'],
                                'remark' => ['value' => '请尽快审批！！', 'color' => '#333']
                            ],
                        ];
                        sendTemplateMessage($message);
                    }
                }else{//没有下一步
                    $up['checkPeo'.$saledpt->checkNo] = session('admin.userName');
                    $up['checkCon'.$saledpt->checkNo] = $post['yj'];
                    $up['ischeck'] = 1;
                    $save = $this->model->where(['sid' => $v])->update($up);
                    if ($i == count($ids)- 1) {
                        //发送消息审核通过
                        $message = [
                            'touser' => getopenid($saledpt->sOperator),
                            'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                            'url' => 'http://wap.rizhaolanhua.com/Saledepartment/detail?id='.$id,
                            'data' => [
                                'first' => ['value' => '您发起的销售通知审批已审核', 'color' => '#333'],
                                'keyword1' => ['value' => '销售通知审批', 'color' => '#333'],
                                'keyword2' => ['value' => '编号：'.$saledpt->sPlace, 'color' => '#333'],
                                'remark' => ['value' => '高高兴兴上班去  平平安安回家来！', 'color' => '#333']
                            ],
                        ];
                        sendTemplateMessage($message);
                    }
                }
                $i++;
            }
            $save?$this->success('审批成功'):$this->error('审批失败');
        }
        $get = $this->request->get();
        $id = empty($get['id'])?'':$get['id'];
        $ids = explode(',',$id);
        $saledpt= $this->model->where('sid',$ids[0])->find();
        $saledpt->processCtrl = $this->processCtrl
        ->withJoin(['processCls'], 'LEFT')
        ->where(['processCtrl_Sequence'=>$saledpt->checkNo,'ptype' => 4])
        ->find();
        
        $this->assign('saledpt',$saledpt);
        $this->assign('menu',2);
        $this->assign('title',"销售通知审批");
        return $this->fetch();
    }
    public function content($id){
        $get = $this->request->get();
        $id = empty($get['id'])?'':$get['id'];
        $ids = explode(',',$id);
        $saledpt = array();
        foreach ($ids as $v){
            $saledpt[] = $this->model->where('sid',$v)->find();
        }
        $this->assign('saledpt',$saledpt);
        $this->assign('menu',2);
        $this->assign('title',"销售通知审批");
        return $this->fetch();
    }
    
    public function refund($id){
        $post = $this->request->post();
        $ids = explode(',',$id);
        foreach ($ids as $v){
            $contract = $this->model->where('sid',$v)->find();
            $up = $this->model->where('sid',$id)->update(['ischeck'=>2,'checkPeo'.$contract->checkNo=>session('admin.userName'),'checkCon'.$contract->checkNo=>$post['yj']]);
        }
        if(!$up){
            $data = [
                'code'  => -1,
                'msg'   => '退回失败！请重新操作',
            ];
        }else{
            //发送消息审核通过
            $message = [
                'touser' => getopenid($contract->sOperator),
                'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                'url' => 'http://wap.rizhaolanhua.com/Contract/detail?id='.$id,
                'data' => [
                    'first' => ['value' => '您发起的销售通知审批已回退', 'color' => '#333'],
                    'keyword1' => ['value' => '销售通知审批', 'color' => '#333'],
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
    /**
     * 首页
     */
    public function cgindex()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $page = isset($post['page']) && !empty($post['page']) ? $post['page'] : 1;
            $limit = isset($post['limit']) && !empty($post['limit']) ? $post['limit'] : 15;
            $list= array();
            $where[] = ['ischeck','=',0];
            $where[] = ['ProcessCtrl_Authorizor','=',];
            $where[] = ['sType','=','采购'];
            $where[] = ['ptype','=',5];
            $subQuery = "( SELECT [sPlace],[sCode],[sShdep],[ghdw],stuff((select ',' + convert(varchar(25), sid)
                   from [Tbl_SalesOrder] as t where [sales_order].sPlace = t.sPlace  for xml path('')),1,1,'') as ids FROM [Tbl_SalesOrder] [sales_order] LEFT JOIN [Tbl_processctrl] [processCtrl] ON [sales_order].[checkNo] = [processCtrl].[processCtrl_Sequence] INNER JOIN [Tbl_processcls] [processCls] ON [processCtrl].[ProcessCls_Code] = [processCls].[ProcessCls_Id] WHERE [ischeck] = 0  AND [ProcessCtrl_Authorizor] = '".session('admin.userCode')."'  AND [sType] = '采购'  AND [ptype] = 5  GROUP BY [sPlace],[sCode],[sShdep],[ghdw] )";
            
            $count = Db::table($subQuery . ' a')->count();
            $list = Db::table($subQuery . ' b')->page($page, $limit)->select();
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
        $this->assign('title',"采购通知审批");
        return $this->fetch();
    }
    public function cgdetail($id)
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $ids = explode(',',$id);
            $i = 0;
            foreach ($ids as $v){
                $saledpt= $this->model->where('sid',$v)->find();
                $where[] = ['ptype','=',5];
                $where[] = ['processCtrl_Sequence','>',$saledpt->checkNo];
                $step = $this->processCtrl
                ->withJoin(['processCls'], 'LEFT')
                ->where($where)
                ->count();
                if($step){//有下一步
                    $up['checkPeo'.$saledpt->checkNo] = session('admin.userName');
                    $up['checkCon'.$saledpt->checkNo] = $post['yj'];
                    $up['checkNo'] = $saledpt->checkNo+1;
                    $save = $this->model->where('sid',$v)->update($up);
                    if ($i == count($ids)- 1) {
                        //发送消息下一步审核人
                        $message = [
                            'touser' => getnext(5,$saledpt->checkNo+1),
                            'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                            'url' => 'http://wap.rizhaolanhua.com/Saledepartment/detail?id='.$id,
                            'data' => [
                                'first' => ['value' => getname($saledpt->sOperator).'发起的采购通知审批', 'color' => '#333'],
                                'keyword1' => ['value' => '采购通知审批', 'color' => '#333'],
                                'keyword2' => ['value' => '编号：'.$saledpt->sCode, 'color' => '#333'],
                                'remark' => ['value' => '请尽快审批！！', 'color' => '#333']
                            ],
                        ];
                        sendTemplateMessage($message);
                    }
                }else{//没有下一步
                    $up['checkPeo'.$saledpt->checkNo] = session('admin.userName');
                    $up['checkCon'.$saledpt->checkNo] = $post['yj'];
                    $up['ischeck'] = 1;
                    $save = $this->model->where(['sid' => $v])->update($up);
                    if ($i == count($ids)- 1) {
                        //发送消息审核通过
                        $message = [
                            'touser' => getopenid($saledpt->sOperator),
                            'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                            'url' => 'http://wap.rizhaolanhua.com/Saledepartment/detail?id='.$id,
                            'data' => [
                                'first' => ['value' => '您发起的采购通知审批已审核', 'color' => '#333'],
                                'keyword1' => ['value' => '采购通知审批', 'color' => '#333'],
                                'keyword2' => ['value' => '编号：'.$saledpt->sCode, 'color' => '#333'],
                                'remark' => ['value' => '高高兴兴上班去  平平安安回家来！', 'color' => '#333']
                            ],
                        ];
                        sendTemplateMessage($message);
                    }
                }
                $i++;
            }
            $save?$this->success('审批成功'):$this->error('审批失败');
        }
        $get = $this->request->get();
        $id = empty($get['id'])?'':$get['id'];
        $ids = explode(',',$id);
        $saledpt= $this->model->where('sid',$ids[0])->find();
        $saledpt->processCtrl = $this->processCtrl
        ->withJoin(['processCls'], 'LEFT')
        ->where(['processCtrl_Sequence'=>$saledpt->checkNo,'ptype' => 4])
        ->find();
        
        $this->assign('saledpt',$saledpt);
        $this->assign('menu',2);
        $this->assign('title',"采购通知审批");
        return $this->fetch();
    }
    public function cgcontent($id){
        $get = $this->request->get();
        $id = empty($get['id'])?'':$get['id'];
        $ids = explode(',',$id);
        $saledpt = array();
        foreach ($ids as $v){
            $saledpt[] = $this->model->where('sid',$v)->find();
        }
        $this->assign('saledpt',$saledpt);
        $this->assign('menu',2);
        $this->assign('title',"采购通知审批");
        return $this->fetch();
    }
    
    public function cgrefund($id){
        $post = $this->request->post();
        $ids = explode(',',$id);
        foreach ($ids as $v){
            $contract = $this->model->where('sid',$v)->find();
            $up = $this->model->where('sid',$id)->update(['ischeck'=>2,'checkPeo'.$contract->checkNo=>session('admin.userName'),'checkCon'.$contract->checkNo=>$post['yj']]);
        }
        if(!$up){
            $data = [
                'code'  => -1,
                'msg'   => '退回失败！请重新操作',
            ];
        }else{
            //发送消息审核通过
            $message = [
                'touser' => getopenid($contract->sOperator),
                'template_id' => 'z6X8WYAdRjsxIzzwo1TwjAORQAJGd_saADWCacXoIgg',
                'url' => 'http://wap.rizhaolanhua.com/Contract/detail?id='.$id,
                'data' => [
                    'first' => ['value' => '您发起的采购通知审批已回退', 'color' => '#333'],
                    'keyword1' => ['value' => '采购通知审批', 'color' => '#333'],
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
    
}