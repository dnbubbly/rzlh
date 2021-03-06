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
        $this->contractColliery =  new \app\admin\model\ContractColliery();
        $this->ship = new \app\admin\model\ContractShip();
    }
    
    public function index()
    {   
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $page = isset($post['page']) && !empty($post['page']) ? $post['page'] : 1;
            $limit = isset($post['limit']) && !empty($post['limit']) ? $post['limit'] : 15;
            $list= array();
            $where[] = ['add_id','=',session('admin.id')];
            $where[] = ['road','=',2];//铁路
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
                $xq = $this->model->where('plan',$result[0]['words'])->count();
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
            $s = $this->model->where('plan',$post['plan'])->count();
            if($s>0){
                $this->error("该需求号铁路装车已添加");
            }
            $add['cfile'] = $post['cfile'];
            $add['cfile2'] = $post['cfile2'];
            $add['plan'] = $post['plan'];
            $add['trans_type'] = $post['trans_type'];
            $add['setdate'] = strtotime($post['setdate']);
            $add['loaddate'] = strtotime($post['loaddate']);
            $add['start_station'] = $post['start_station'];
            $add['end_station'] = $post['end_station'];
            $add['cusname'] = $post['cusname'];
            $add['coaltype'] = $post['coaltype'];
            $add['mini'] = $post['mini'];
            $add['road'] = $post['road'];
            $add['store'] = $post['store'];
            $add['create_time'] = time();//装车类型
            $add['add_id'] = session('admin.id');
            try {
                $save = $this->model->insert($add);
                $scid = $this->model->getLastInsID();
                $arr = $post['sale'];
                $store = $post['store']?$post['store']:"虚拟库";
                foreach ($arr as $a){
                    $in['sc_id'] = $scid;
                    $in['plan'] = $a['plan'];
                    $in['cartype'] = $a['cartype'];
                    $in['carcode'] = $a['carcode'];
                    $in['sweight'] = $a['sweight'];
                    $in['fweight'] = $a['fweight'];
                    $in['create_time'] = time();
                    $in['add_id'] = session('admin.id');
                    if( $in['plan']){
                        $this->salecoaldetail->insert($in);
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
        $stocks = $this->systemStore->where('status',2)->order('name asc')->select();
        $this->assign('stocks',$stocks);
        $colliery = $this->contractColliery->where('status',1)->order("name asc")->select();
        $this->assign('colliery',$colliery);
        $ship = $this->ship->where('status',1)->order("name asc")->select();
        $this->assign('ship',$ship);
        $this->assign('menu',2);
        $this->assign('title',"添加铁路装车入库");
        return $this->fetch();
    }
    
    public function edit($id){
        $row = $this->model->where('id',$id)->find();
        empty($row) && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $add['cfile'] = $post['cfile'];
            $add['cfile2'] = $post['cfile2'];
            $add['plan'] = $post['plan'];
            $add['trans_type'] = $post['trans_type'];
            $add['setdate'] = strtotime($post['setdate']);
            $add['loaddate'] = strtotime($post['loaddate']);
            $add['start_station'] = $post['start_station'];
            $add['end_station'] = $post['end_station'];
            $add['cusname'] = $post['cusname'];
            $add['coaltype'] = $post['coaltype'];
            $add['mini'] = $post['mini'];
            $add['road'] = $post['road'];
            $add['store'] = $post['store'];
            $add['update_time'] = time();//装车类型
            $add['add_id'] = session('admin.id');
            unset($post['ScID']);
            try {
                $save = $this->model->where('id',$id)->update($add);
                //删除装车明细
                $arr = $post['sale'];
                $salecoaldetail = $this->salecoaldetail->where('sc_id',$id)->select();
                $del = $salecoaldetail->delete();
                foreach ($arr as $a){
                    $in['sc_id'] = $id;
                    $in['plan'] = $a['plan'];
                    $in['cartype'] = $a['cartype'];
                    $in['carcode'] = $a['carcode'];
                    $in['sweight'] = $a['sweight'];
                    $in['fweight'] = $a['fweight'];
                    $in['create_time'] = time();
                    $in['update_time'] = time();
                    $in['add_id'] = session('admin.id');
                    if( $in['plan']){
                        $this->salecoaldetail->insert($in);
                    }
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
        $saleCoalDetail = $this->salecoaldetail->where('sc_id',$id)->select();
        $count1 = 0;$count2 = 0;
        foreach ($saleCoalDetail as $saleD){
            $count1 +=$saleD->ScdWeight;
            $count2 +=$saleD->ScdWeight2;
        }
        $this->assign('count1',$count1);
        $this->assign('count2',$count2);
        $this->assign('saleCoalDetail',$saleCoalDetail);
        //库存仓库
        $stocks = $this->systemStore->where('status',2)->order('name asc')->select();
        $this->assign('stocks',$stocks);
        $colliery = $this->contractColliery->where('status',1)->order("name asc")->select();
        $this->assign('colliery',$colliery);
        $ship = $this->ship->where('status',1)->order("name asc")->select();
        $this->assign('ship',$ship);
        $row->cfile2 = explode(";",$row->cfile2)[0];
        $this->assign('row', $row);
        $this->assign('menu',2);
        $this->assign('title',"修改铁路装车");
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
    public function del($id){
        $row = $this->model->where('id', $id)->select();
        $row->isEmpty() && $this->error('数据不存在');
        try {
            $saleCoalD = $this->salecoaldetail->where('sc_id',$id)->select();
            $saleCoalD->delete();
            $save = $row->delete();
        } catch (\Exception $e) {
            $this->error('删除失败'.$e->getMessage());
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