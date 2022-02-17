<?php

namespace app\admin\controller\step;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use app\common\constants\AdminConstant;

/**
 * @ControllerAnnotation(title="step_flow")
 */
class Flow extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\StepFlow();
        
        $this->systemStep = new \app\admin\model\SystemStep();
        
        $this->stepflowdetail= new \app\admin\model\StepFlowdetail();
        
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
                $where[] = ['u_id','=',session('admin.id')];
            }
            $count = $this->model
            ->withJoin(['systemAdmin'], 'LEFT')
            ->where($where)
            ->count();
            $list = $this->model
            ->withJoin(['systemAdmin'], 'LEFT')
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
     * @NodeAnotation(title="审批流程")
     */
    public function flow($id){
        if ($this->request->isPost()) {
            $post = $this->request->post();
            if($post['act']=='get'){
                $model = $this->model->where('id',$post['id'])->field('model')->find();
                $data = $this->systemStep->where('model',$model['model'])->field('processConfig')->find();
                return json_decode($data['processConfig']);
            }
        }
        return $this->fetch();
    }
    /**
     * @NodeAnotation(title="审批步骤")
     */
    public function flowdetail($id){
        $flowdetil = $this->stepflowdetail
            ->withJoin(['stepFlow','systemAdmin'], 'LEFT')
            ->where(['f_id'=>$id,'step_flowdetail.status'=>1])
            ->select()
            ->order("id desc");
        $this->assign('flowdetil',$flowdetil);
        return $this->fetch();
    }
}