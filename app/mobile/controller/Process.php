<?php

namespace app\mobile\controller;


use app\common\controller\AdminController;
use think\App;

class Process extends AdminController
{

    protected $layout = FALSE;
    /**
     * 初始化方法
     */
    public function initialize()
    {
        $this->model = new \app\admin\model\ProcessCtrl();
        
        $this->processCtrl= new \app\admin\model\ProcessCls();
    }
    /**
     * 流程详情
     */
    public function detail($id)
    {
        $step = $this->model
        ->withJoin(['processCls'], 'LEFT')
        ->where('ProcessCls_Id',$id)
        ->order('processCtrl_Sequence asc')
        ->select();
        $this->assign('step',$step);
        $this->assign('menu',2);
        $this->assign('title',"审批流程");
        return $this->fetch();
    }
}
