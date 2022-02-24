<?php

namespace app\mobile\controller;


use app\common\controller\MobileController;
use think\facade\Filesystem;
use think\File;
use think\App;
use think\Image;
require_once '../vendor/baiduocr/AipOcr.php';

class RailwayLoading extends MobileController
{
    protected $layout = FALSE;
    
    public function __construct(App $app)
    {
        parent::__construct($app);
        
        $this->model = new \app\admin\model\SaleCoal();
        $this->salecoaldetail = new \app\admin\model\SaleCoaldetail();
        $this->systemStore =  new \app\admin\model\SystemStore();
    }
    
    public function index()
    {   
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $page = isset($post['page']) && !empty($post['page']) ? $post['page'] : 1;
            $limit = isset($post['limit']) && !empty($post['limit']) ? $post['limit'] : 15;
            $list= array();
            $where[] = ['add_id','=',session('admin.id')];
            $where[] = ['type','=',1];//销售
            $count = $this->model
            ->where($where)
            ->count();
            $list = $this->model
            ->where($where)
            ->page($page, $limit)
            ->order('loaddate desc')
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
        $this->assign('title',"铁路装车");
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
            $uploadConfig['upload_allow_ext'] = 'jpg,jpeg,png,bmp,pdf';
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
            
            //识别
            $appId="25341977";
            $apiKey="j68oCdhpGavjqxHMjxVivoVB";
            $secretKey="zRh3HtyZfMrkCjdwAjGOK6GNQShzZX9k";  
            $AipOcr = new \AipOcr($appId, $apiKey, $secretKey);
            if(isImage($this->completeFileUrl)){
                $images=\think\Image::open(strstr($this->completeFileUrl,"upload"));
                $images->thumb(2000,2000,1)->save(strstr($this->completeFileUrl,"upload"));
                $options = array();
                $options["language_type"] = "CHN_ENG";
                $options["detect_direction"] = "true";
                $return = $AipOcr->basicAccurate(file_get_contents($this->completeFileUrl),$options);//通用文字识别（高精度含位置版）
                $arr= $return['words_result'];
//                 return json($arr);
                foreach ($arr as $k=>$v){
                    if(strpos($v['words'],'需求号：')!== false){
                        $arr[$k]['words'] = mb_substr($arr[$k]['words'],4);//需求号
                        $result[] = $arr[$k];
                    }
                    if($v['words']=='发站（公司）'){
                        $result[] = $arr[$k+1];//发站
                    }
                    if($v['words']=='到站（公司）'){
                        $result[] = $arr[$k+1];//到站
                    }
                    if($v['words']=='名称'){
                        for ($i=0;$i<5;$i++){
                            if($arr[$k-$i]['words']=='收货人'){
                                $result[] = $arr[$k+1];//收货单位
                            }
                        }
                    }
                    if($v['words']=='(kg)'){
                        $result[] = $arr[$k+1];//品种
                    }
                    if(strpos($v['words'],'制单日期')!== false){
                        $arr[$k]['words'] = mb_substr($arr[$k]['words'],4);//制单日期
                        $result[] = $arr[$k];//制单日期
                    }
                }
                //需求号重复
                $xq = $this->model->where('ScPlan',$result[0]['words'])->count();
                if($xq>0){
                    $this->error('该需求号已添加！');
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
            }else{
                $num = getPdfPages($this->completeFileUrl);
                
                $options = array();
                $options["pdf_file_num"] = 1;
                $return = $AipOcr->basicAccuratepdf(file_get_contents($this->completeFileUrl),$options);//通用文字识别（高精度含位置版）
                $arr= $return['words_result'];
                $rel = array();
                foreach ($arr as $k=>$v){
                    if(strpos($v['words'],'需求号：')!== false){
                        $arr[$k]['words'] = mb_substr($arr[$k]['words'],4);//需求号
                        $result[] = $arr[$k];
                    }elseif (strpos($v['words'],'求号：')!== false){
                        $arr[$k]['words'] = mb_substr($arr[$k]['words'],3);//需求号
                        $result[] = $arr[$k];
                    }
                    if($v['words']=='发站（公司）'){
                        $result[] = $arr[$k+1];//发站
                    }
                    if($v['words']=='到站（公司）'){
                        $result[] = $arr[$k+1];//到站
                    }
                    if($v['words']=='名称'){
                        for ($j=0;$j<5;$j++){
                            if($arr[$k-$j]['words']=='收货人'){
                                $result[] = $arr[$k+1];//收货单位
                            }
                        }
                    }
                    if($v['words']=='(kg)'){
                        $result[] = $arr[$k+1];//品种
                    }
                    if(strpos($v['words'],'制单日期')!== false){
                        $arr[$k]['words'] = mb_substr($arr[$k]['words'],4);//制单日期
                        $result[] = $arr[$k];//制单日期
                    }
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
            }
            
            return json($data);
        }
    }
    
    public function add(){
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $s = $this->model->where('ScPlan',$post['ScPlan'])->count();
            if($s>0){
                $this->error("该需求号铁路装车已添加");
            }
            $add['cFile'] = $post['cFile'];
            $add['cFile2'] = $post['cFile2'];
            $add['cFilename'] = $post['cFilename'];
            $add['ScPlan'] = $post['ScPlan'];
            $add['iszc'] = $post['iszc'];
            $add['ScCityOwned'] = $post['ScCityOwned'];
            $add['ScDate'] = $post['ScDate'];
            $add['ScStarName'] = $post['ScStarName'];
            $add['ScStopName'] = $post['ScStopName'];
            $add['ScDepartment'] = $post['ScDepartment'];
            $add['ScCoalType'] = $post['ScCoalType'];
            $add['scZhuangH'] = $post['scZhuangH'];
            $add['YsType'] = $post['YsType'];
            $add['ScN'] = $post['ScN'];
            $add['SnID'] = 0;
            $add['ScType'] = 1;
            $add['ischeck'] = 1;
            $add['isjs'] = 0;
            $add['checkNo'] = 1;
            $add['ScPeople'] = session('admin.userCode');
            try {
                $save = $this->model->insert($add);
                $scid = $this->model->getLastInsID();
                $arr = $post['sale'];
                $SdDepartment = $post['ScN']?$post['ScN']:"虚拟库";
                foreach ($arr as $a){
                    $in['ScID'] = $scid;
                    $in['ScdDep'] = $a['ScdDep'];
                    $in['ScdCarType'] = $a['ScdCarType'];
                    $in['ScdCarCode'] = $a['ScdCarCode'];
                    $in['ScdWeight'] = $a['ScdWeight'];
                    $in['ScdWeight2'] = $a['ScdWeight2'];
                    $in['ScdCoal'] = 0;
                    $in['isdh'] = 0;
                    $in['ScdDate'] = date("Y-m-d");
                    $in['ScdOperate'] = session('admin.userCode');
                    if( $in['ScdDep']){
                        $this->saleCoalDetail->insert($in);
                        //保存入库更新库存
                        $saleCoal = $this->model->where('ScID',$scid)->find();
                        $this->saleRoadG->saveStore($saleCoal, $a['ScdWeight2'],$SdDepartment);
                    }
                    
                }
                if($save){
                    $data = [
                        'code'  => 1,
                        'msg'   => '提交成功',
                        'data'  => $scid,
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
        $stocks = $this->systemStore->order('name asc')->select();
        $this->assign('stocks',$stocks);
        $this->assign('menu',2);
        $this->assign('title',"添加铁路装车入库");
        return $this->fetch();
    }
    
    public function edit($ScID){
        $row = $this->model->where('ScID',$ScID)->find();
        empty($row) && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $add['cFile'] = $post['cFile'];
            $add['cFilename'] = $post['cFilename'];
            $add['cFile2'] = $post['cFile2'];
            $add['ScPlan'] = $post['ScPlan'];
            $add['iszc'] = $post['iszc'];
            $add['ScCityOwned'] = $post['ScCityOwned'];
            $add['ScDate'] = $post['ScDate'];
            $add['ScStarName'] = $post['ScStarName'];
            $add['ScStopName'] = $post['ScStopName'];
            $add['ScDepartment'] = $post['ScDepartment'];
            $add['ScCoalType'] = $post['ScCoalType'];
            $add['scZhuangH'] = $post['scZhuangH'];
            $add['YsType'] = $post['YsType'];
            $add['ScN'] = $post['ScN'];
            $add['SnID'] = 0;
            $add['ScType'] = 1;
            $add['isjs'] = 0;
            $add['ScPeople'] = session('admin.userCode');
            unset($post['ScID']);
            try {
                $save = $this->model->where('ScID',$ScID)->update($add);
                //删除装车明细
                $arr = $post['sale'];
                $this->saleCoalDetail->where('ScID',$ScID)->delete();
                $saleCoal = $this->model->where('ScID',$ScID)->find();
                $salesOrder= $this->salesOrder->where('sid',$saleCoal->sid)->find();
                //删除原先库存数量
                $saleRoadG= $this->saleRoadG->where('ScID',$ScID)->select();
                $prevm = date('ym',strtotime('-1 month'));//上月
                $nextm = date("ym");//本月
                $SdDepartment = $post['ScN']?$post['ScN']:"虚拟库";
                foreach ($saleRoadG as $v){
                    $coal = $this->coalStocksG ->where(['CsGangkou'=>$SdDepartment,'CsTypeName'=>$saleCoal->ScCoalType])->find();
                    $csgup['RKSL'.$nextm] = $coal->{'RKSL'.$nextm}-$v->SdWeight;
                    $csgup['JCSL'.$nextm] = $coal->{'JCSL'.$nextm}-$v->SdWeight;
                    $this->coalStocksG->where('CsID',$coal->CsID)->update($csgup);
                }
                $this->saleRoadG->where('ScID',$ScID)->delete();
                foreach ($arr as $a){
                    $in['ScID'] = $ScID;
                    $in['ScdDep'] = $a['ScdDep'];
                    $in['ScdCarType'] = $a['ScdCarType'];
                    $in['ScdCarCode'] = $a['ScdCarCode'];
                    $in['ScdWeight'] = $a['ScdWeight'];
                    $in['ScdWeight2'] = $a['ScdWeight2'];
                    $in['ScdCoal'] = '';
                    $in['ScdDate'] = date("Y-m-d");
                    $in['ScdOperate'] = session('admin.userCode');
                    if( $in['ScdDep']){
                        $this->saleCoalDetail->insert($in);
                    }
                    //保存入库更新库存
                    $saleCoal = $this->model->where('ScID',$ScID)->find();
                    $this->saleRoadG->updateStore($saleCoal, $a['ScdWeight2'],$SdDepartment);
                }
                if($save){
                    $data = [
                        'code'  => 1,
                        'msg'   => '提交成功',
                        'data'  => '',
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
        if(!isHttp($row->cFile)){
            //不是图片显示文件类型
            $row->filetype = getfiletype($row->cFile);
        }
        $row->cFile2 = explode(";",$row->cFile2)[0];
        //查询销售采购单编号
        $row->cg = $this->salesOrder->where('sid',$row->sid)->find();
        $saleCoalDetail = $this->saleCoalDetail->where('ScID',$ScID)->select();
        $count1 = 0;$count2 = 0;
        foreach ($saleCoalDetail as $saleD){
            $count1 +=$saleD->ScdWeight;
            $count2 +=$saleD->ScdWeight2;
        }
        $this->assign('count1',$count1);
        $this->assign('count2',$count2);
        $this->assign('saleCoalDetail',$saleCoalDetail);
        //库存仓库
        $stocks = $this->CoalStocks->order('CskTypeName asc')->select();
        $this->assign('stocks',$stocks);
        $this->assign('row', $row);
        $this->assign('menu',2);
        $this->assign('title',"修改铁路装车入库");
        return $this->fetch();
    }
    /**
     * 上传文件并识别
     */
    public function detailupload(){
        if ($this->request->isAjax()) {
            $data = [
                'upload_type' => 'local',
                'file'        => $this->request->file("file"),
            ];
            $uploadConfig = array();
            $uploadConfig['upload_allow_ext'] = 'jpg,jpeg,png,bmp,pdf';
            $uploadConfig['upload_allow_size'] = '10240000';
            $uploadConfig['upload_allow_mime'] = 'image/gif,image/jpeg,video/x-msvideo,text/plain,image/png';
            $uploadConfig['upload_allow_type'] = 'local,alioss,qnoss,txcos';
            empty($data['upload_type']) && $data['upload_type'] = $uploadConfig['upload_type'];
            $rule = [
                'upload_type|指定上传类型有误' => "in:{$uploadConfig['upload_allow_type']}",
                'file|文件'              => "require|file|fileExt:{$uploadConfig['upload_allow_ext']}|fileSize:{$uploadConfig['upload_allow_size']}",
                ];
            $this->validate($data, $rule);
            $this->completeFilePath = Filesystem::disk('public')->putFile('upload', $data['file']);
            $this->completeFileUrl = request()->domain() . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $this->completeFilePath);
            if(isImage($this->completeFileUrl)){
                try {
                    $images=\think\Image::open(strstr($this->completeFileUrl,"upload"));
                    $images->thumb(2000,2000,1)->save(strstr($this->completeFileUrl,"upload"));
                    //识别
                    $appId="25341977";
                    $apiKey="j68oCdhpGavjqxHMjxVivoVB";
                    $secretKey="zRh3HtyZfMrkCjdwAjGOK6GNQShzZX9k";
                    $AipOcr = new \AipOcr($appId, $apiKey, $secretKey);
                    $options = array();
                    $options["language_type"] = "CHN_ENG";
                    $options["detect_direction"] = "false";
                    $return = $AipOcr->basicAccurate(file_get_contents($this->completeFileUrl),$options);//通用文字识别（高精度含位置版）
                    $arr= $return['words_result'];
                    $detaildata = array();
//                     return json($arr);
                    //截取有用的内容
                    $s = 0;
                    foreach ($arr as $k=>$v){
                        //运单号
                        if($v['words']=='1'){
                            $s = $k-1;
                            break;
                        }
                    }
                    foreach ($arr as $k=>$v){
                        if($k>$s){
                            $detaildata[] = $v['words'];
                        }
                    }
                    $nu = $detaildata[1];
                    $num = intval(substr($nu,strlen($nu)-3,3));
                    $kk = 0;
                    $rel = array();
                    foreach ($detaildata as $k=>$v){
                        if(preg_match('/^[_0-9a-zA-Z]{16,18}$/i',$v)){
                            $sw[0] = $kk;
                            $sw[1] = $v;
                            $sw[2] = $detaildata[$k+1];
                            $sw[3] = $detaildata[$k+2];
                            $sw[4] = $detaildata[$k+3];
                            $rel[] = $sw;
                            $kk++;
                            $num++;
                        }
                    }
                    $count1 = 0;
                    $count2 = 0;
                    foreach ($rel as $key=>$val){
                        foreach ($val as $k=>$v){
                            if($k>2){
                                $val[$k+1] = $val[$k];
                            }
                            $rel[$key] = $val;
                        }
                        foreach ($val as $k=>$v){
                            if ($k==2){
                                $val[2] = substr($v,0,strlen($v)-7);
                                $val[3] = substr($v,strlen($v)-7,7);
                            }
                            $rel[$key] = $val;
                        }
                        
                    }
                    foreach ($rel as $r){
                        //$count1 +=isset($r[4])?$r[4]:0;
                        //$count2 +=isset($r[5])?$r[5]:0;
                    }
                } catch (\Exception $e){
                    $this->error($e->getMessage());
                    $this->error("图片识别失败，请上传清晰的电子版装车明细");
                }
                
                
                if ($this->completeFileUrl) {
                    $data = [
                        'code'     => 1,
                        'msg'      => '上传成功',
                        'file'     => $this->completeFileUrl,
                        'filename' => $this->request->file("file")->getOriginalName(),
                        'data'     => $rel,
                        'count1'     => $count1,
                        'count2'     => $count1,
                    ];
                } else {
                    $data = [
                        'code'  => 0,
                        'msg'   => '上传失败',
                        'data'  => '',
                    ];
                }
            }else{
                try {
                    //识别
                    $appId="25341977";
                    $apiKey="j68oCdhpGavjqxHMjxVivoVB";
                    $secretKey="zRh3HtyZfMrkCjdwAjGOK6GNQShzZX9k";
                    $AipOcr = new \AipOcr($appId, $apiKey, $secretKey);
                    $num = getPdfPages($this->completeFileUrl);
                    $count1 =0;
                    $count2 =0;
                    $rel = array();
                    for($i=1;$i<=$num;$i++){
                        $options = array();
                        $options["pdf_file_num"] = $i;
                        $return = $AipOcr->basicAccuratepdf(file_get_contents($this->completeFileUrl),$options);//通用文字识别（高精度含位置版）
                        $arr= $return['words_result'];
                        $rel1= array();
                        if($i==1){
                            //截取有用的内容
                            foreach ($arr as $k=>$v){
                                //运单号
                                if($v['words']=='备注'){
                                    $s = $k;
                                }
                            }
                            foreach ($arr as $k=>$v){
                                if($k>$s){
                                    $detaildata[] = $v['words'];
                                }
                            }
                        }else{
                            //截取有用的内容
                            foreach ($arr as $k=>$v){
                                $detaildata[] = $v['words'];
                            }
                            
                        }
                    }
                    $carnum = $this->getcarnum($detaildata);
                    for ($j=1;$j<=$carnum;$j++){
                        $rel[] = $this->onetotwo($detaildata,$j);
                    }
                    foreach ($rel as $key=>$val){
                        foreach ($val as $k=>$v){
                            if($k>2){
                                $val[$k+1] = $val[$k];
                            }
                            $rel[$key] = $val;
                        }
                        foreach ($val as $k=>$v){
                            if ($k==2){
                                $val[2] = substr($v,0,strlen($v)-7);
                                $val[3] = substr($v,strlen($v)-7,7);
                            }
                            $rel[$key] = $val;
                        }
                    }
                    
                    //             return json($rel);
                    foreach ($rel as $r){
                        $count1 +=$r[4];
                        $count2 +=$r[5];
                    }
                    if ($this->completeFileUrl) {
                        $data = [
                            'code'     => 1,
                            'msg'      => '上传成功',
                            'file'     => $this->completeFileUrl,
                            'filename' => $this->request->file("file")->getOriginalName(),
                            'rel'      => $rel,
                            'count1'   => $count1,
                            'count2'   => $count2
                        ];
                    } else {
                        $data = [
                            'code'  => 0,
                            'msg'   => '上传失败',
                            'data'  => '',
                        ];
                    }
                    
                } catch (\Exception $e){
                    $this->error($e->getMessage());;
                    $this->error("图片识别失败，请上传清晰的电子版装车明细");
                }
                
                
                if ($this->completeFileUrl) {
                    $data = [
                        'code'     => 1,
                        'msg'      => '上传成功',
                        'file'     => $this->completeFileUrl,
                        'filename' => $this->request->file("file")->getOriginalName(),
                        'data'     => $rel,
                        'count1'     => $count1,
                        'count2'     => $count1,
                    ];
                } else {
                    $data = [
                        'code'  => 0,
                        'msg'   => '上传失败',
                        'data'  => '',
                    ];
                }
            }
            
            return json($data);
        }
    }
    public function del($ScID){
        $row = $this->model->where('ScID', $ScID)->select();
        $row->isEmpty() && $this->error('数据不存在');
        try {
            //删除装车明细
            $saleCoalD = $this->saleCoalDetail->where('ScID',$ScID)->select();
            if(count($saleCoalD)>0){
                $this->saleCoalDetail->where('ScID',$ScID)->delete();
                $this->saleRoadG->delStore($ScID);
            }
            $save = $this->model->where('ScID',$ScID)->delete();
        } catch (\Exception $e) {
            $this->error('删除失败');
        }
        $save ? $this->success('删除成功') : $this->error('删除失败');
    }
    
    //匹配有几车箱
    public function getcarnum($arr,$i=1){
        foreach ($arr as $k=>$v){
            if($i==$v){
                $i++;
            }
        }
        return $i-1;
    }
    //获取下表
    public function getindex($arr,$i){
        $s = 0;
        foreach ($arr as $k=>$v){
            if($v==$i){
                $s = $k;
                break;
            }
        }
        return $s;
    }
    public function gen($x,$y,$x1,$y1){
        return sqrt(($x-$x1)*($x-$x1)+($y-$y1)*($y-$y1));
    }
    public function onetotwo($arr,$i=1){
        $re = array();
        foreach ($arr as $k=>$v){
            if($this->getindex($arr, $i+1)){
                if($this->getindex($arr,$i)<=$k&&$k<$this->getindex($arr, $i+1)){
                    $re[] = $v;
                }
            }else{
                if($this->getindex($arr,$i)<=$k){
                    $re[] = $v;
                }
            }
        }
        return $re;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}