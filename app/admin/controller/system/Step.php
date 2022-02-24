<?php

namespace app\admin\controller\system;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use function GuzzleHttp\json_encode;
use think\facade\Db;
use EasyAdmin\tool\CommonTool;

/**
 * @ControllerAnnotation(title="system_step")
 */
class Step extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\SystemStep();
        
    }
    
    /**
     * @NodeAnotation(title="添加")
     */
    public function add($id = null)
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $save = $this->model->save($post);
                //添加默认审批流程
                $step = array();
                $step['tableId'] = $this->model->id;
                $step['workFlowDef'] = ['name'=>$this->model->title];
                $step['directorMaxLevel'] = 3;//审批主管最大层级
                $step['flowPermission'] = [];
                $json = '{"nodeName":"\u53d1\u8d77\u4eba","type":0,"priorityLevel":"","settype":"","selectMode":"","selectRange":"","directorLevel":"","examineMode":"","noHanderAction":"","examineEndDirectorLevel":"","ccSelfSelectFlag":"","conditionList":[],"nodeUserList":[],"childNode":{"nodeName":"\u5ba1\u6838\u4eba","error":"","type":1,"settype":2,"selectMode":0,"selectRange":0,"directorLevel":1,"examineMode":1,"noHanderAction":2,"examineEndDirectorLevel":0,"childNode":{"nodeName":"\u6284\u9001\u4eba","type":2,"ccSelfSelectFlag":"","childNode":"","nodeUserList":[],"error":""},"nodeUserList":[]},"conditionNodes":[]}';
                $step['nodeConfig'] = json_decode($json,JSON_NUMERIC_CHECK);
                $this->model->where('id',$this->model->id)->update(['processConfig'=>json_encode($step,JSON_NUMERIC_CHECK)]);
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        return $this->fetch();
    }
    /**
     * @NodeAnotation(title="表单设计")
     */
    public function form($id){
        $table = $this->model->where('id',$id)->find();
        $tableName = str_replace(".","_",$table['model']);
        $table['model']= str_replace('.', ' ', $table['model']);
        $table['model']= ucwords($table['model']);
        $table['model']= str_replace(' ', '', $table['model']);
        $className = '\\app\\admin\\model\\'.$table['model'];
        $model = new $className;
        $tableName = CommonTool::humpToLine(lcfirst($tableName));
        $prefix = config('database.connections.mysql.prefix');
        $dbList = Db::query("show full columns from {$prefix}{$tableName}");
        $allow = [
            'id',
            'status',
            'remark',
            'create_time',
            'update_time',
            'delete_time',
            'add_id'
        ];
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $post['data'] = json_decode(htmlspecialchars_decode($post['data']),true);
            $allow = array();
            foreach ($post['data'] as $de){
                $allow[] = $de['value'];
            }
            try {
                $data = array();
                foreach ($dbList as $k=>$v) {
                    if (in_array($dbList[$k]['Field'], $allow)) {
                        if(strstr($dbList[$k]['Type'],"tinyint")){
                            $d['columnId'] = $id.str_pad($k,2,'0',STR_PAD_LEFT);
                            $d['showType'] = 3;
                            $d['showName'] = preg_replace('/\(.*?\)/', '', $dbList[$k]['Comment']);
                            $d['columnName'] = $dbList[$k]['Field'];
                            $d['columnType'] = "String";
                            $func = "get".ucwords($dbList[$k]['Field'])."List";
                            $arr = $model->$func();
                            $fdbv= array();
                            foreach ($arr as $k=>$v){
                                $fdbv[] = array('key'=>$k,'value'=>$v);
                            }
                            $d['fixedDownBoxValue'] = json_encode($fdbv);
                        }else if(strstr($dbList[$k]['Type'],"varchar")){
                            $d['columnId'] = $id.str_pad($k,2,'0',STR_PAD_LEFT);
                            $d['showType'] = 2;
                            $d['showName'] = preg_replace('/\(.*?\)/', '', $dbList[$k]['Comment']);
                            $d['columnName'] = $dbList[$k]['Field'];
                            $d['columnType'] = "String";
                            $d['fixedDownBoxValue'] = '';
                        }else{
                            $d['columnId'] = $id.str_pad($k,2,'0',STR_PAD_LEFT);
                            $d['showType'] = 1;
                            $d['showName'] = preg_replace('/\(.*?\)/', '', $dbList[$k]['Comment']);
                            $d['columnName'] = $dbList[$k]['Field'];
                            $d['columnType'] = "Double";
                            $d['fixedDownBoxValue'] = '';
                        }
                        $data[] = $d;
                    }
                }
                $save = $this->model->where('id',$id)->update(['conditions'=>json_encode($data,JSON_NUMERIC_CHECK),'allowcolum'=>json_encode($allow,JSON_NUMERIC_CHECK)]);
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $condotion = array();
        foreach ($dbList as $k=>$v) {
            if (!in_array($dbList[$k]['Field'], $allow)) {
                $d['value'] = $dbList[$k]['Field'];
                $d['title'] = preg_replace('/\(.*?\)/', '', $dbList[$k]['Comment']);
                
                $condotion[] = $d;
            }
        }
        $allowcolum= json_decode($table->allowcolum);
        $this->assign('allowcolum', $allowcolum);
        $this->assign('condotion', $condotion);
        return $this->fetch();
    }
    /**
     * @NodeAnotation(title="流程设计")
     */
    public function addnode($id = null)
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            if($post['act']=='save'){
                $up['processConfig'] = json_encode($post['processConfig'],JSON_NUMERIC_CHECK);
                try {
                    $save = $this->model->where('id',$post['id'])->update($up);
                } catch (\Exception $e) {
                    $this->error('发布失败');
                }
                $save ? $this->success('发布成功') : $this->error('未做修改或发布失败');
            }else if($post['act']=='get'){
                $data = $this->model->where('id',$post['id'])->field('processConfig')->find();
                return json_decode($data['processConfig']);
            }
        }
        return $this->fetch();
    }
    
    public function addcondition()
    {
        $get = $this->request->get('', null, null);
        $tableId = isset($get['tableId']) && !empty($get['tableId']) ? $get['tableId'] : '';
        $list = $this->model->field('conditions')->where('id',$tableId)->find();
//         var_dump($this->model->getLastSql());
        return json_decode($list['conditions']);
    }

    
}