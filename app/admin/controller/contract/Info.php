<?php

namespace app\admin\controller\contract;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="contract_info")
 */
class Info extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\ContractInfo();
        
        $this->assign('getDraftList', $this->model->getDraftList());

        $this->assign('getLeadList', $this->model->getLeadList());

        $this->assign('getCheckTypeList', $this->model->getCheckTypeList());

        $this->assign('getTaxList', $this->model->getTaxList());

        $this->assign('getFreightList', $this->model->getFreightList());

        $this->assign('getMarkupList', $this->model->getMarkupList());

        $this->assign('getSettleList', $this->model->getSettleList());

    }
    
    /**
     * @NodeAnotation(title="添加")
     */
    public function addone()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $save = $this->model->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('getTypeList', $this->model->getDraftList());
        return $this->fetch();
    }

    
}