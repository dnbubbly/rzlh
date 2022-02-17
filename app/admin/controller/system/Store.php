<?php

namespace app\admin\controller\system;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\facade\Request;

/**
 * @ControllerAnnotation(title="system_store")
 */
class Store extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\SystemStore();
        
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
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        $row['status'] !=0 && $this->error('审批中或已归档，不能修改!');
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $row->add_name = $this->admin->getField('username',$row->add_id);
        $this->assign('row', $row);
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="发起审批")
     */
    public function agree($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        $row['status'] !=0 && $this->error('审批中或已归档，不需要提交!');
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
//                 return json($step);//审批流程
//                 return json($this->stepflow);//审批记录
                //添加审批待办
                addflowDetail(json_decode($step->processConfig)->nodeConfig->childNode,$this->stepflow,$this->model);
                
            } catch (\Exception $e) {
                var_dump($e->getMessage());
                $this->error('发起审批失败');
            }
            $save&&$up ? $this->success('发起审批成功') : $this->error('发起审批失败');
        }
        
    }
    /* 查看  */
    public function detail($id){
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        $row->add_name = $this->admin->getField('username',$row->add_id);
        $this->assign('row', $row);
        return $this->fetch();
    }
}