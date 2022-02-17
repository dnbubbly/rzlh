<?php

namespace app\admin\controller\system;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use app\common\constants\DepartmentConstant;
use think\App;

/**
 * @ControllerAnnotation(title="system_department")
 */
class Department extends AdminController
{

    use \app\admin\traits\Curd;
    
    protected $sort = [
        'sort' => 'desc',
        'id'   => 'asc',
    ];

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\SystemDepartment();
        $this->employee = new \app\admin\model\SystemAdmin();
        
    }
    
    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            $count = $this->model->count();
            $list = $this->model->order($this->sort)->select();
            foreach ($list as $li){
                $li->num = $this->employee->where('dep_id',$li->id)->count();
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
    public function add($id = null)
    {
        $homeId = $this->model
        ->where([
            'pid' => DepartmentConstant::HOME_PID,
        ])
        ->value('id');
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [
                'pid|上级部门'   => 'require',
                'title|部门名称' => 'require',
            ];
            $this->validate($post, $rule);
            try {
                $save = $this->model->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            if ($save) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }
        $leader = $this->employee->where('dep_id',1)->select();
        $this->assign('leader',$leader);
        $pidDepartmentList = $this->model->getPidDepartmentList();
        $this->assign('id', $id);
        $this->assign('pidDepartmentList', $pidDepartmentList);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        $homeId = $this->model
        ->where([
            'id' => DepartmentConstant::HOME_PID,
        ])
        ->value('id');
        if ($id== $homeId) {
            $this->error('公司总部不允许关闭');
        }
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [
                'pid|上级部门'   => 'require',
                'title|部门名称' => 'require',
            ];
            $this->validate($post, $rule);
            try {
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            if ($save) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }
        $leader = $this->employee->where('dep_id',1)->select();
        $this->assign('leader',$leader);
        $pidDepartmentList= $this->model->getPidDepartmentList();
        $this->assign([
            'id'          => $id,
            'pidDepartmentList' => $pidDepartmentList,
            'row'         => $row,
        ]);
        return $this->fetch();
    }
    /**
     * @NodeAnotation(title="删除")
     */
    public function delete($id)
    {
        $this->checkPostRequest();
        $row = $this->model->whereIn('id', $id)->select();
        empty($row) && $this->error('数据不存在');
        $homeId = $this->model
        ->where([
            'id' => DepartmentConstant::HOME_PID,
        ])
        ->value('id');
        if ($id== $homeId) {
            $this->error('公司总部状态不允许删除');
        }
        try {
            $save = $row->delete();
        } catch (\Exception $e) {
            $this->error('删除失败');
        }
        if ($save) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    
    /**
     * @NodeAnotation(title="属性修改")
     */
    public function modify()
    {
        $this->checkPostRequest();
        $post = $this->request->post();
        $rule = [
            'id|ID'    => 'require',
            'field|字段' => 'require',
            'value|值'  => 'require',
        ];
        $this->validate($post, $rule);
        $row = $this->model->find($post['id']);
        if (!$row) {
            $this->error('数据不存在');
        }
        if (!in_array($post['field'], $this->allowModifyFields)) {
            $this->error('该字段不允许修改：' . $post['field']);
        }
        $homeId = $this->model
        ->where([
            'id' => DepartmentConstant::HOME_PID,
        ])
        ->value('id');
        if ($post['id'] == $homeId && $post['field'] == 'status') {
            $this->error('公司总部状态不允许关闭');
        }
        try {
            $row->save([
                $post['field'] => $post['value'],
            ]);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('保存成功');
    }
    
    /**
     * @NodeAnotation(title="添加菜单提示")
     */
    public function getMenuTips()
    {
        $node = input('get.keywords');
        $list = SystemNode::whereLike('node', "%{$node}%")
        ->field('node,title')
        ->limit(10)
        ->select();
        return json([
            'code'    => 0,
            'content' => $list,
            'type'    => 'success',
        ]);
    }
    /**
     * @NodeAnotation(title="部门列表")
     */
    public function json($pid)
    {
        $get = $this->request->get('', null, null);
        $name = isset($get['page']) && !empty($get['page']) ? $get['page'] : '';
        $page = isset($get['page']) && !empty($get['page']) ? $get['page'] : 1;
        $limit = isset($get['limit']) && !empty($get['limit']) ? $get['limit'] : 15;
        $where[] = ['pid','=',$pid];
        $list = $this->model
        ->where($where)
        ->page($page, $limit)
        ->order($this->sort)
        ->select();
        $data = array();
        foreach ($list as $li) {
            $d['departmentKey'] = $li['number'];
            $d['departmentName'] = $li['title'];
            $d['id'] = $li['id'];
            $d['parentId'] = $pid;
            $d['departmentNames'] = $li['title'];
            $data['childDepartments'][] = $d;
        }
        $employee = $this->employee->where('dep_id',$pid)->select();
        foreach ($employee as $e) {
            $d['id'] = $e['id'];
            $d['employeeName'] = $e['username'];
            $d['isLeave'] = $e['is_leader'];
            $d['isLeave'] = $e['is_leader'];
            $data['employees'][] = $d;
        }
        if($pid!=DepartmentConstant::HOME_PID){
            $titleDepartments= $this->model->where('id',$pid)->select();
            foreach ($titleDepartments as $t) {
                $d['departmentId'] = $t['id'];
                $d['departmentKey'] = $t['number'];
                $d['departmentName'] = $t['title'];
                $d['departmentNames'] = $t['title'];
                $d['id'] = $t['id'];
                $d['parentId'] = $t['pid'];
                $data['titleDepartments'][] = $d;
            }
        }
        return json($data);
    }
}