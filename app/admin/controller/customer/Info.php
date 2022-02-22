<?php

namespace app\admin\controller\customer;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\facade\Request;

/**
 * @ControllerAnnotation(title="customer_info")
 */
class Info extends AdminController
{
    protected $sort = [
        'name' => 'asc',
    ];

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\CustomerInfo();
        
        $this->step = new \app\admin\model\SystemStep();
        
        $this->stepflow = new \app\admin\model\StepFlow();
        
        $this->admin = new \app\admin\model\SystemAdmin();
        
        $this->assign('getSystemAdminList', $this->model->getSystemAdminList());
        
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
            ->withJoin('systemAdmin', 'LEFT')
            ->where($where)
            ->count();
            $list = $this->model
            ->withJoin('systemAdmin', 'LEFT')
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
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="添加")
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            $post['date'] = strtotime($post['date']);
            try {
                $save = $this->model->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        //编号
        $code = $this->model->field("code")->order('id desc')->findOrEmpty();
        $this->assign('code',$num=str_pad($code->code+1,4,"0",STR_PAD_LEFT));
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
                $this->error('发起审批失败');
            }
            $save&&$up ? $this->success('发起审批成功') : $this->error('发起审批失败');
        }
        
    }
    
    /**
     * @NodeAnotation(title="查看")
     */
    public function detail($id){
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        $this->assign('row', $row);
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="生效列表")
     */
    public function reindex()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $where[] = ['customer_info.status','=',2];
            $count = $this->model
            ->withJoin('systemAdmin', 'LEFT')
            ->where($where)
            ->count();
            $list = $this->model
            ->withJoin('systemAdmin', 'LEFT')
            ->where($where)
            ->page($page, $limit)
            ->order("name asc")
            ->select();
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
     * @NodeAnotation(title="未生效列表")
     */
    public function indexscore()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                $this->selectWhere[] = ['status','=',0];
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $where[] = ['customer_info.status','=',0];
            $count = $this->model
            ->withJoin('systemAdmin', 'LEFT')
            ->where($where)
            ->count();
            $list = $this->model
            ->withJoin('systemAdmin', 'LEFT')
            ->where($where)
            ->page($page, $limit)
            ->order("name asc")
            ->select();
            var_dump($this->model->getLastSql());
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
}