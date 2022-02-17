<?php

namespace app\admin\controller\step;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use app\common\constants\AdminConstant;

/**
 * @ControllerAnnotation(title="step_flowdetail")
 */
class Flowdetailre extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\StepFlowdetail();
        
        $this->stepflow = new \app\admin\model\StepFlow();
        
    }

    
    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        $get = $this->request->get();
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            if(AdminConstant::SUPER_ADMIN_ID != session('admin.id')){
                $where[] = ['user_id','=',session('admin.id')];
            }
            $where[] = ['step_flowdetail.status','=',1];
            $count = $this->model
                ->withJoin(['stepFlow','systemAdmin'], 'LEFT')
                ->where($where)
                ->count();
            $list = $this->model
                ->withJoin(['stepFlow','systemAdmin'], 'LEFT')
                ->where($where)
                ->page($page, $limit)
                ->order($this->sort)
                ->select();
            foreach ($list as $li){
                $li->date = $li->date?date("Y-m-d H:i:s",$li->date):null;
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
     * @NodeAnotation(title="审批")
     */
    public function examine($id){
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        $row['status'] !=0 && $this->error('已审批!');
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $post['status'] = 1;
                $post['date'] = strtotime($post['date']);
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('审批通过失败');
            }
            
            //有下一步则生成下一步审批详情
            if(json_decode($row->step_json)->childNode){//有下一步
                $stepflow = $this->stepflow->where('id',$row->f_id)->find();
                $className = '\\app\\admin\\model\\'.parseNodeTuo($stepflow->model);
                $model = new $className;
                addflowDetail(json_decode($row->step_json)->childNode,$stepflow,$model);
            }else{//无下一步
                $stepflow = $this->stepflow->where('id',$row->f_id)->find();
                $className = '\\app\\admin\\model\\'.parseNodeTuo($stepflow->model);
                $model = new $className;
                $up['status'] = 2;
                $save = $model->where('id',$stepflow->a_id)->update($up);
            }
            $save ? $this->success('审批通过成功') : $this->error('审批通过失败');
        }
        $ready = $this->model->withJoin(['stepFlow','systemAdmin'], 'LEFT')->where(['id'=>$id,'step_flowdetail.status'=>1])->select()->order("id desc");
        $this->assign('ready', $ready);
        $this->assign('row', $row);
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="退回")
     */
    public function reback($id){
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        $row['status'] !=0 && $this->error('已审批!');
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $post['status'] = 1;
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('审批通过失败');
            }
            //有下一步则生成下一步审批详情
            
            $save ? $this->success('审批通过成功') : $this->error('审批通过失败');
        }
        $ready = $this->model->withJoin(['stepFlow','systemAdmin'], 'LEFT')->where(['id'=>$id,'step_flowdetail.status'=>1])->select()->order("id desc");
        $this->assign('ready', $ready);
        $this->assign('row', $row);
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="查看")
     */
    public function detail($id){
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        $ready = $this->model->withJoin(['stepFlow','systemAdmin'], 'LEFT')->where(['f_id'=>$row->f_id,'step_flowdetail.status'=>1])->select()->order("id desc");
        $this->assign('ready', $ready);
        $this->assign('row', $row);
        return $this->fetch();
    }
}