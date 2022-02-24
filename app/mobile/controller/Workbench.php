<?php

namespace app\mobile\controller;


use app\common\controller\MobileController;
use think\App;

class Workbench extends MobileController
{

    protected $layout = FALSE;
    
    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\admin\model\SystemAdmin();
    }
    /**
     * 首页
     */
    public function index()
    {
        $member = $this->model->where(['id'=>session('admin.id')])->find();
        
        $this->assign('member', $member);
        $this->assign('menu',2);
        $this->assign('title',"审批");
        return $this->fetch();
    }
}
