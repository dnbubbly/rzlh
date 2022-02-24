<?php

namespace app\mobile\controller;


use app\common\controller\MobileController;
use think\App;

class My extends MobileController
{

    protected $layout = FALSE;
    
    public function __construct(App $app)
    {
        parent::__construct($app);
        
        $this->model = new \app\admin\model\SystemAdmin();
        
        $this->stepFlowdetail= new \app\admin\model\StepFlowdetail();
        
        $this->contract = new \app\admin\model\ContractInfo();
    }
    /**
     * 首页
     */
    public function index()
    {
        $num1 = $this->stepFlowdetail->where("status",0)->count();;
        $num2 = $this->stepFlowdetail->where("status",1)->count();;
        
        $member = $this->model->where(['id'=>session('admin.id')])->find();
        $this->assign('num1',$num1);
        $this->assign('num2',$num2);
        $this->assign('member', $member);
        $this->assign('menu',3);
        $this->assign('title',"我的");
        return $this->fetch();
    }
    
    /* 已办 */
    public function already(){
        
        $this->assign('menu',3);
        $this->assign('title',"已办审批");
        return $this->fetch();
    }
    public function detail()
    {
        $member = $this->model->where(['id'=>session('admin.id')])->find();
        $member->sex = $this->model->getSexList()[$member->sex];
        $this->assign('member', $member);
        $this->assign('menu',3);
        $this->assign('title',"个人信息");
        return $this->fetch();
    }
}
