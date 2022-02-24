<?php

namespace app\mobile\controller;


use app\common\controller\AdminController;
use think\facade\Filesystem;
use think\File;
use think\App;
use think\Image;
require_once '../vendor/baiduocr/AipOcr.php';

class HighwayLoadingsale extends AdminController
{
    protected $layout = FALSE;
    
    public function __construct(App $app)
    {
        parent::__construct($app);
        
        $this->model = new \app\admin\model\SaleCoal();
        $this->salesOrder = new \app\admin\model\SalesOrder();
        $this->saleCoalDetail = new \app\admin\model\SaleCoalDetail();
        $this->saleRoadG = new \app\admin\model\SaleRoadG();
        $this->coalStocksG = new \app\admin\model\CoalStocksG();
        $this->CoalStocks = new \app\admin\model\CoalStocks();
        $this->cstarz = new \app\admin\model\Cstarz();
        $this->cstopz = new \app\admin\model\Cstopz();
        $this->ccoalType = new \app\admin\model\CcoalType();
    }
    
    public function index()
    {   
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $page = isset($post['page']) && !empty($post['page']) ? $post['page'] : 1;
            $limit = isset($post['limit']) && !empty($post['limit']) ? $post['limit'] : 15;
            $list= array();
            $where[] = ['ScPeople','=',session('admin.userCode')];
            $where[] = ['ischeck','=',1];
            $where[] = ['ScType','=',2];
            $where[] = ['iszc','=',3];
            $count = $this->model
            ->where($where)
            ->count();
            $list = $this->model
            ->where($where)
            ->page($page, $limit)
            ->order('ScDate desc')
            ->select();
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        $this->assign('menu',2);
        $this->assign('title',"销售公路装车");
        return $this->fetch();
    }
    
    /**
     * 上传文件并识别
     */
    public function upload(){
        if ($this->request->isAjax()) {
            $data = [
                'upload_type' => 'local',
                'file'        => $this->request->file("file"),
            ];
            $uploadConfig = array();
            $uploadConfig['upload_allow_ext'] = 'jpg,jpeg,png,bmp';
            $uploadConfig['upload_allow_size'] = '10240000';
            $uploadConfig['upload_allow_mime'] = 'image/gif,image/jpeg,video/x-msvideo,text/plain,image/png';
            $uploadConfig['upload_allow_type'] = 'local,alioss,qnoss,txcos';
            empty($data['upload_type']) && $data['upload_type'] = $uploadConfig['upload_type'];
            $rule = [
                'upload_type|指定上传类型有误' => "in:{$uploadConfig['upload_allow_type']}",
                'file|文件'              => "require|file|fileExt:{$uploadConfig['upload_allow_ext']}|fileSize:{$uploadConfig['upload_allow_size']}",
                ];
            $this->validate($data, $rule);
            try {
                $this->completeFilePath = Filesystem::disk('public')->putFile('upload', $data['file']);
                $this->completeFileUrl = request()->domain() . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $this->completeFilePath);
            } catch (\Exception $e){
                $this->error($e->getMessage());
            }
            
            try {
                $images=\think\Image::open(strstr($this->completeFileUrl,"upload"));
                $images->rotate(270)->thumb(2000,2000,1)->save(strstr($this->completeFileUrl,"upload"));
                //识别
                $appId="25341977";
                $apiKey="j68oCdhpGavjqxHMjxVivoVB";
                $secretKey="zRh3HtyZfMrkCjdwAjGOK6GNQShzZX9k";
                $AipOcr = new \AipOcr($appId, $apiKey, $secretKey);
                $options = array();
                $options["language_type"] = "CHN_ENG";
                $options["detect_direction"] = "true";
                $return = $AipOcr->basicAccurate(file_get_contents($this->completeFileUrl),$options);//通用文字识别（高精度含位置版）
                $arr= $return['words_result'];
                //                 return json($arr);
                foreach ($arr as $k=>$v){
                    if($v['words']=='矿发数'){
                        $result[2]= $arr[$k+1];//矿发数
                    }elseif($v['words']=='原发'){
                        $result[2]= $arr[$k+1];//矿发数
                    }
                }
                $return = $AipOcr->weightNote(file_get_contents($this->completeFileUrl),$options);
                $arr= $return['words_result'][0];
                $result[0]['words'] = $arr['PlateNum'][0]['word'];
                if(strpos($arr['NetWeight'][0]['word'],'吨')!== false){
                    $result[1]['words'] = mb_substr($arr['NetWeight'][0]['word'],0,mb_strlen($arr['NetWeight'][0]['word'])-1);//净重
                }else{
                    $result[1]['words'] = $arr['NetWeight'][0]['word'];
                }
                $pattern = '/^[\x{4e00}-\x{9fa5}]{1}[A-Z]{1}[A-Z_0-9]{5}$/u';
                if(!preg_match($pattern, $result[0]['words'])){
                    $data = [
                        'code'  => 0,
                        'msg'   => '识别失败！',
                        'data'  => '',
                    ];
                    return json($data);
                }
                if ($this->completeFileUrl) {
                    $data = [
                        'code'     => 1,
                        'msg'      => '上传成功',
                        'file'     => $this->completeFileUrl,
                        'filename' => $this->request->file("file")->getOriginalName(),
                        'data'     => $result,
                    ];
                } else {
                    $data = [
                        'code'  => 0,
                        'msg'   => '上传失败',
                        'data'  => '',
                    ];
                }
            } catch (\Exception $e) {
                $data = [
                    'code'  => 0,
                    'msg'   => "请上传不带水印的图片！",
                    'data'  => '',
                ];
            }
            return json($data);
        }
    }
    
    public function add(){
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $s = $this->model->where('ScPlan',$post['ScPlan'])->count();
            if($s>0){
                $this->error("该提货单号的公路装车已添加");
            }
            $ad['SnID'] = 0;
            $ad['ScType'] = 2;
            $ad['ischeck'] = 1;
            $ad['isjs'] = 0;
            $ad['checkNo'] = 1;
            $ad['ScPeople'] = session('admin.userCode');
            $ad['ScCityOwned'] = $post['ScDate'];
            $ad['ScPlan'] = $post['ScPlan'];
            $ad['iszc'] = $post['iszc'];
            $ad['ScDate'] = $post['ScDate'];
            $ad['ScStarName'] = $post['ScStarName'];
            $ad['ScStopName'] = $post['ScStopName'];
            $ad['ScCoalType'] = $post['ScCoalType'];
            $ad['ScDepartment'] = $post['ScDepartment'];
            $ad['YsType'] = $post['YsType'];
            $ad['ScN'] = $post['ScN'];
            try {
                $save = $this->model->insert($ad);
                $scid = $this->model->getLastInsID();
                //保存明细
                $arr = $post['sale'];
                foreach ($arr as $a){
                    if($a['ScdDep']){
                        $in['ScID'] = $scid;
                        $in['ScdDep'] = $a['ScdDep'];
                        $in['ScdCarType'] = '';
                        $in['ScdCarCode'] = '';
                        $in['ScdWeight'] = $a['ScdWeight'];
                        $in['ScdWeight2'] = $a['ScdWeight2'];
                        $in['ScdCoal'] = 0;
                        $in['ScdDate'] = date("Y-m-d");
                        $in['ScdOperate'] = session('admin.userCode');
                        $this->saleCoalDetail->insert($in);
                    }
                    
                }
                //保存起运地
                $cstarz = $this->cstarz->where("mname",$ad['ScStarName'])->select();
                if(!count($cstarz)>0){
                    $cstarzarr['mname'] = $ad['ScStarName'];
                    $this->cstarz->insert($cstarzarr);
                }
                //保存运达地
                $cstopz = $this->cstopz->where("mname",$ad['ScStopName'])->select();
                if(!count($cstopz)>0){
                    $cstopzarr['mname'] = $ad['ScStopName'];
                    $this->cstopz->insert($cstopzarr);
                }
                //保存品种
                $ccoalType = $this->ccoalType->where("mname",$ad['ScCoalType'])->select();
                if(!count($ccoalType)>0){
                    $ccoalTypearr['mname'] = $ad['ScCoalType'];
                    $this->ccoalType->insert($ccoalTypearr);
                }
                if($save){
                    $data = [
                        'code'  => 1,
                        'msg'   => '提交成功',
                        'data'  => $this->model->getLastInsID(),
                    ];
                }else{
                    $data = [
                        'code'  => 0,
                        'msg'   => '保存失败',
                        'data'  => '',
                    ];
                }
            } catch (\Exception $e) {
                $data = [
                    'code'  => 0,
                    'msg'   => $e->getMessage(),
                    'data'  => '',
                ];
            }
            return json($data);
        }
        //库存仓库
        $stocks = $this->CoalStocks->order('CskTypeName asc')->select();
        //起运地
        $cstarz = $this->cstarz->select();
        //运达地
        $cstopz = $this->cstopz->select();
        //品种
        $ccoalType = $this->ccoalType->select();
        $this->assign('stocks',$stocks);
        $this->assign('cstarz',$cstarz);
        $this->assign('cstopz',$cstopz);
        $this->assign('ccoalType',$ccoalType);
        $this->assign('menu',2);
        $this->assign('title',"添加销售公路装车");
        return $this->fetch();
    }
    
    public function edit($ScID){
        $row = $this->model->where('ScID',$ScID)->find();
        empty($row) && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            if($post['ScPlan']!=$row['ScPlan']){
                $s = $this->model->where('ScPlan',$post['ScPlan'])->count();
                if($s>0){
                    $this->error("该提货单号的公路装车已添加");
                }
            }
            $ad['SnID'] = 0;
            $ad['ScType'] = 2;
            $ad['ischeck'] = 1;
            $ad['isjs'] = 0;
            $ad['checkNo'] = 1;
            $ad['ScPeople'] = session('admin.userCode');
            $ad['ScCityOwned'] = $post['ScDate'];
            $ad['ScPlan'] = $post['ScPlan'];
            $ad['iszc'] = $post['iszc'];
            $ad['ScDate'] = $post['ScDate'];
            $ad['ScStarName'] = $post['ScStarName'];
            $ad['ScStopName'] = $post['ScStopName'];
            $ad['ScCoalType'] = $post['ScCoalType'];
            $ad['ScDepartment'] = $post['ScDepartment'];
            $ad['YsType'] = $post['YsType'];
            $ad['ScN'] = $post['ScN'];
            $arr = $post['sale'];
            try {
                $saleCoalD = $this->saleCoalDetail->where('ScID',$ScID)->select();
                //删除装车明细
                $this->saleCoalDetail->where('ScID',$ScID)->delete();
                $saleCoal = $this->model->where('ScID',$post['ScID'])->find();
                $salesOrder= $this->salesOrder->where('sid',$saleCoal->sid)->find();
                foreach ($arr as $a){
                    if($a['ScdDep']){
                        $in['ScID'] = $ScID;
                        $in['ScdDep'] = $a['ScdDep'];
                        $in['ScdCarType'] = '';
                        $in['ScdCarCode'] = '';
                        $in['ScdWeight'] = $a['ScdWeight'];
                        $in['ScdWeight2'] = $a['ScdWeight2'];
                        $in['ScdCoal'] = 0;
                        $in['ScdDate'] = date("Y-m-d");
                        $in['ScdOperate'] = session('admin.userCode');
                        $this->saleCoalDetail->insert($in);
                    }
                }
                //保存起运地
                $cstarz = $this->cstarz->where("mname",$ad['ScStarName'])->select();
                if(!count($cstarz)>0){
                    $cstarzarr['mname'] = $ad['ScStarName'];
                    $this->cstarz->insert($cstarzarr);
                }
                //保存运达地
                $cstopz = $this->cstopz->where("mname",$ad['ScStopName'])->select();
                if(!count($cstopz)>0){
                    $cstopzarr['mname'] = $ad['ScStopName'];
                    $this->cstopz->insert($cstopzarr);
                }
                //保存品种
                $ccoalType = $this->ccoalType->where("mname",$ad['ScCoalType'])->select();
                if(!count($ccoalType)>0){
                    $ccoalTypearr['mname'] = $ad['ScCoalType'];
                    $this->ccoalType->insert($ccoalTypearr);
                }
                $save = $this->model->where('ScID',$ScID)->update($ad);
                if($save){
                    $data = [
                        'code'  => 1,
                        'msg'   => '提交成功',
                        'data'  => $ScID,
                    ];
                }else{
                    $data = [
                        'code'  => 0,
                        'msg'   => '修改失败',
                        'data'  => '',
                    ];
                }
                
            } catch (\Exception $e) {
                $data = [
                    'code'  => 0,
                    'msg'   => $e->getMessage(),
                    'data'  => '',
                ];
            }
            return json($data);
        }
        //查询销售采购单编号
        $row->cg = $this->salesOrder->where('sid',$row->sid)->find();
        //库存仓库
        $stocks = $this->CoalStocks->order('CskTypeName asc')->select();
        //起运地
        $cstarz = $this->cstarz->select();
        //运达地
        $cstopz = $this->cstopz->select();
        //品种
        $ccoalType = $this->ccoalType->select();
        $saleCoalDetail = $this->saleCoalDetail->where('ScID',$ScID)->select();
        $count1 = 0;$count2 = 0;
        foreach ($saleCoalDetail as $saleD){
            $count1 +=$saleD->ScdWeight;
            $count2 +=$saleD->ScdWeight2;
        }
        $this->assign('count1',$count1);
        $this->assign('count2',$count2);
        $this->assign('saleCoalDetail',$saleCoalDetail);
        $this->assign('cou',count($saleCoalDetail));
        $this->assign('stocks',$stocks);
        $this->assign('cstarz',$cstarz);
        $this->assign('cstopz',$cstopz);
        $this->assign('ccoalType',$ccoalType);
        $this->assign('row', $row);
        $this->assign('menu',2);
        $this->assign('title',"修改销售公路装车");
        return $this->fetch();
    }
    public function del($ScID){
        $row = $this->model->where('ScID', $ScID)->select();
        $row->isEmpty() && $this->error('数据不存在');
        try {
            //删除装车明细
            $saleCoalD = $this->saleCoalDetail->where('ScID',$ScID)->select();
            if(count($saleCoalD)>0){
                //$this->saleCoalDetail->where('ScID',$ScID)->delete();
            }
            $save = $this->model->where('ScID',$ScID)->delete();
        } catch (\Exception $e) {
            $this->error('删除失败');
        }
        $save ? $this->success('删除成功') : $this->error('删除失败');
    } 
}