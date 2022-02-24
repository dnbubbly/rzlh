<?php

namespace app\mobile\controller;


use app\common\controller\MobileController;
use think\App;
use app\common\constants\AdminConstant;

class Index extends MobileController
{
    
    protected $layout = FALSE;
    
    public function __construct(App $app)
    {
        parent::__construct($app);
                
        $this->model= new \app\admin\model\StepFlowdetail();
        
        $this->stepflow = new \app\admin\model\StepFlow();
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
            if(AdminConstant::SUPER_ADMIN_ID != session('admin.id')){
                $where[] = ['user_id','=',session('admin.id')];
            }
            $where[] = ['step_flowdetail.status','=',0];
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
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        $this->assign('menu',1);
        $this->assign('title',"待办");
        return $this->fetch();
    }
}
