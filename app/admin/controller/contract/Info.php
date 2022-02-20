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
        
        $this->quality = new \app\admin\model\ContractQuality();
        
        $this->assign('getDraftList', $this->model->getDraftList());

        $this->assign('getLeadList', $this->model->getLeadList());

        $this->assign('getCheckNumList', $this->model->getCheckNumList());
        
        $this->assign('getCheckTypeList', $this->model->getCheckTypeList());

        $this->assign('getTaxList', $this->model->getTaxList());

        $this->assign('getFreightList', $this->model->getFreightList());

        $this->assign('getMarkupList', $this->model->getMarkupList());

        $this->assign('getSettleList', $this->model->getSettleList());

    }
    
    /**
     * @NodeAnotation(title="选择合同类型")
     */
    public function addone()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $this->success('保存成功',$post);
        }
        $this->assign('getTypeList', $this->model->getDraftList());
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="添加")
     */
    public function add()
    {
        $get = $this->request->get();
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                return json_decode(htmlspecialchars_decode($post['coal'][0]['json']));
                $save = $this->model->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $cus_id = empty($get['cus_id'])?'':$get['cus_id'];
        $draft= empty($get['draft'])?'':$get['draft'];
        $lead= empty($get['lead'])?'':$get['lead'];
        if($cus_id==1){
            $this->assign('seller', "日照兰花冶电能源有限公司");
        }elseif ($cus_id==2){
            $this->assign('buyer', "日照兰花冶电能源有限公司");
        }
        return $this->fetch();
    }
    public function quality()
    {   
        $get = $this->request->get();
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $arr['data'] = $post['qz'];
            $arr['index'] = $get['index'];
            $this->success('保存成功',$arr);
        }
        //质量标准
        $qtype = $this->quality->where('status',1)->group('name')->order('id asc')->select();
        foreach ($qtype as $k=>$v){
            $qtype[$k]['unit'] = $this->quality->where(['status'=>1,'name'=>$v->name])->select();
        }
        
        $this->assign('qtype',$qtype);
        return $this->fetch();
    }
    
}