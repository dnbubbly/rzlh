<?php

// +----------------------------------------------------------------------
// | EasyAdmin
// +----------------------------------------------------------------------
// | PHP交流群: 763822524
// +----------------------------------------------------------------------
// | 开源协议  https://mit-license.org 
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zhongshaofa/EasyAdmin
// +----------------------------------------------------------------------

namespace app\admin\controller\system;


use app\admin\model\SystemAdmin;
use EasyAdmin\tool\CommonTool;
use jianyan\excel\Excel;
use think\facade\Db;
use app\admin\service\TriggerService;
use app\common\constants\AdminConstant;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Admin
 * @package app\admin\controller\system
 * @ControllerAnnotation(title="管理员管理")
 */
class Admin extends AdminController
{

    use \app\admin\traits\Curd;

    protected $sort = [
        'sort' => 'desc',
        'id'   => 'desc',
    ];
    
    protected $allowModifyFields = [
        'status',
        'is_leader',
        'sort'
    ];
    
    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SystemAdmin();
        $this->SystemDepartment= new \app\admin\model\SystemDepartment();
        $this->assign('auth_list', $this->model->getAuthList());
        $this->assign('getSexList', $this->model->getSexList());
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
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->withJoin('department', 'LEFT')
                ->where($where)
                ->count();
            $list = $this->model
                ->withoutField('password')
                ->withJoin('department', 'LEFT')
                ->where($where)
                ->page($page, $limit)
                ->order($this->sort)
                ->select();
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        $pidDepartmentList = $this->SystemDepartment->getPidDepartmentList();
        $cate = [];
        foreach ($pidDepartmentList as $k => $v) {
            $cate[$k] = array($v['id'],$v['title']);
        }
        $this->assign('pidDepartmentList', $cate);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="添加")
     */
    public function add($id = null)
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $authIds = $this->request->post('auth_ids', []);
            $post['auth_ids'] = implode(',', array_keys($authIds));
            $rule = [];
            $this->validate($post, $rule);
            try {
                if(env('EASYADMIN.PASSWORD', true)){
                    $post['password'] = password(env('EASYADMIN.PASSWORD', true));
                }
                $save = $this->model->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $pidDepartmentList = $this->SystemDepartment->getPidDepartmentList();
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
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $authIds = $this->request->post('auth_ids', []);
            $post['auth_ids'] = implode(',', array_keys($authIds));
            $rule = [];
            $this->validate($post, $rule);
            if (isset($row['password'])) {
                unset($row['password']);
            }
            try {
                $save = $row->save($post);
                TriggerService::updateMenu($id);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $row->auth_ids = explode(',', $row->auth_ids);
        $this->assign('row', $row);
        $pidDepartmentList = $this->SystemDepartment->getPidDepartmentList();
        $this->assign('id', $id);
        $this->assign('pidDepartmentList', $pidDepartmentList);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="设置密码")
     */
    public function password($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [
                'password|登录密码'       => 'require',
                'password_again|确认密码' => 'require',
            ];
            $this->validate($post, $rule);
            if ($post['password'] != $post['password_again']) {
                $this->error('两次密码输入不一致');
            }
            try {
                $save = $row->save([
                    'password' => password($post['password']),
                ]);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $row->auth_ids = explode(',', $row->auth_ids);
        $this->assign('row', $row);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="删除")
     */
    public function delete($id)
    {
        $this->checkPostRequest();
        $row = $this->model->whereIn('id', $id)->select();
        $row->isEmpty() && $this->error('数据不存在');
        $id == AdminConstant::SUPER_ADMIN_ID && $this->error('超级管理员不允许修改');
        if (is_array($id)){
            if (in_array(AdminConstant::SUPER_ADMIN_ID, $id)){
                $this->error('超级管理员不允许修改');
            }
        }
        try {
            $save = $row->delete();
        } catch (\Exception $e) {
            $this->error('删除失败');
        }
        $save ? $this->success('删除成功') : $this->error('删除失败');
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
        if (!in_array($post['field'], $this->allowModifyFields)) {
            $this->error('该字段不允许修改：' . $post['field']);
        }
        if ($post['id'] == AdminConstant::SUPER_ADMIN_ID && $post['field'] == 'status') {
            $this->error('超级管理员状态不允许修改');
        }
        $row = $this->model->find($post['id']);
        empty($row) && $this->error('数据不存在');
        if($post['field']=='is_leader'){
            if($this->model->where(['dep_id'=>$row->dep_id,'is_leader'=>1])->count()>0&&$post['value']==1){
                $this->error('一个部门只能有一个负责人');
            }
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
     * @NodeAnotation(title="导出")
     */
    public function export()
    {
        list($page, $limit, $where) = $this->buildTableParames();
        $tableName = $this->model->getName();
        $tableName = CommonTool::humpToLine(lcfirst($tableName));
        $prefix = config('database.connections.mysql.prefix');
        $dbList = Db::query("show full columns from {$prefix}{$tableName}");
        $header = [];
        foreach ($dbList as $vo) {
            $comment = !empty($vo['Comment']) ? $vo['Comment'] : $vo['Field'];
            if (!in_array($vo['Field'], $this->noExportFields)) {
                $header[] = [$comment, $vo['Field']];
            }
        }
        $list = $this->model
        ->withJoin('department', 'LEFT')
        ->where($where)
        ->limit(100000)
        ->order('id', 'desc')
        ->select()
        ->toArray();
        $fileName = time();
        return Excel::exportData($list, $header, $fileName, 'xlsx');
    }
    
    /**
     * @NodeAnotation(title="员工列表")
     */
    public function json()
    {
        $get = $this->request->get('', null, null);
        $page = isset($get['page']) && !empty($get['page']) ? $get['page'] : 1;
        $limit = isset($get['limit']) && !empty($get['limit']) ? $get['limit'] : 15;
        $searchName = isset($get['searchName']) && !empty($get['searchName']) ? $get['searchName'] : '';
        $where[] = ['name','like',"%{$searchName}%"];
        $list = $this->model
        ->where($where)
        ->page($page, $limit)
        ->order($this->sort)
        ->select();
//         var_dump($list);die;
        $data = array();
        foreach ($list as $li) {
            $d['departmentName'] = $this->SystemDepartment->field('title')->where('id',$li->dep_id)->find();
            $d['employeeName'] = $li['name'];
            $d['employeeDepartmentId'] = $li['dep_id'];
            $d['id'] = $li['id'];
            $d['departmentNames'] = $this->SystemDepartment->field('title')->where('id',$li->dep_id)->find();
            $data[] = $d;
        }
        
        return json($data);
    }
    
    
}
