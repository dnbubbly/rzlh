<?php

namespace app\mobile\controller;


use app\common\controller\MobileController;
use think\App;

class Load extends MobileController
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
        $this->assign('menu',2);
        $this->assign('title',"装车入库");
        return $this->fetch();
    }
}
