<?php

namespace app\admin\controller\customer;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="customer_credit")
 */
class Credit extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\CustomerCredit();
        
        $this->score = new \app\admin\model\CustomerScore();
    }

    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFieds')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
            ->withJoin(['systemAdmin','customerInfo'], 'LEFT')
            ->where($where)
            ->count();
            $list = $this->model
            ->withJoin(['systemAdmin','customerInfo'], 'LEFT')
            ->where($where)
            ->page($page, $limit)
            ->order($this->sort)
            ->select();
            foreach ($list as $li){
                $li->startdate= date("Y-m-d",$li->startdate);
                $li->enddate= date("Y-m-d",$li->enddate);
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
     * @NodeAnotation(title="添加")
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            $post['startdate'] = strtotime($post['startdate']);
            $post['enddate'] = strtotime($post['enddate']);
            $post['remain_money'] = $post['money'];
            try {
                $save = $this->model->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
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
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $post['startdate'] = strtotime($post['startdate']);
                $post['enddate'] = strtotime($post['enddate']);
                $post['remain_money'] = $post['remain_money']+($post['money']-$row->money);
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $row->startdate= date("Y-m-d",$row->startdate);
        $row->enddate= date("Y-m-d",$row->enddate);
        $this->assign('row', $row);
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="获取评分信息")
     */
    public function score($id)
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $data = $this->score->where(['cus_id'=>$id,'status'=>1])->find();
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $this->success('',$data);
        }
    }
}