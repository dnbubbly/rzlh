<?php

namespace app\mobile\controller;


use app\common\controller\MobileController;
use think\facade\Filesystem;
use think\File;
use think\App;
use think\Image;
require_once '../vendor/baiduocr/AipOcr.php';

class HighwayLoading extends MobileController
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
        $this->coaltype= new \app\admin\model\ContractCoaltype();
        $this->highwaysite= new \app\admin\model\ContractHighwaysite();
    }
    
    public function index()
    {   
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $page = isset($post['page']) && !empty($post['page']) ? $post['page'] : 1;
            $limit = isset($post['limit']) && !empty($post['limit']) ? $post['limit'] : 15;
            $list= array();
            $where[] = ['add_id','=',session('admin.id')];
            $where[] = ['road','=',1];//公路
            $count = $this->model
            ->where($where)
            ->count();
            $list = $this->model
            ->where($where)
            ->page($page, $limit)
            ->order('loaddate desc')
            ->select();
            foreach ($list as $li){
                $li->start_station = $this->model->getContractHighwaysiteList()[$li->start_station];
                $li->end_station = $this->model->getContractHighwaysiteList()[$li->end_station];
                $li->coaltype= $this->model->getContractCoaltypeList()[$li->coaltype];
            }
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);}
        $this->assign('menu',2);
        $this->assign('title',"公路装车");
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
            $s = $this->model->where('plan',$post['plan'])->count();
            if($s>0){
                $this->error("该提货单号的公路装车已添加");
            }
            if(count($post['sale'])==0){
                $this->error("请上传装车明细");
            }
            
            $add['plan'] = $post['plan'];
            $add['trans_type'] = $post['trans_type'];
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
                //保存明细
                $arr = $post['sale'];
                foreach ($arr as $a){
                    if($a['plan']){
                        $in['sc_id'] = $scid;
                        $in['plan'] = $a['plan'];
                        $in['sweight'] = $a['sweight'];
                        $in['fweight'] = $a['fweight'];
                        $in['create_time'] = time();
                        $in['add_id'] = session('admin.id');
                        $this->salecoaldetail->insert($in);
                    }
                    
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
        $stocks = $this->systemStore->where('status',2)->order('name asc')->select();
        $this->assign('stocks',$stocks);
        $colliery = $this->contractColliery->where('status',1)->order("name asc")->select();
        $this->assign('colliery',$colliery);
        $ship = $this->ship->where('status',1)->order("name asc")->select();
        $this->assign('ship',$ship);
        //起运地
        $highwaysite = $this->highwaysite->where('status',1)->select();
        $this->assign('highwaysite',$highwaysite);
        //品种
        $coaltype= $this->coaltype->where('status',1)->select();
        $this->assign('coaltype',$coaltype);
        $this->assign('menu',2);
        $this->assign('title',"添加公路装车");
        return $this->fetch();
    }
    
    public function edit($id){
        $row = $this->model->where('id',$id)->find();
        empty($row) && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            if($post['plan']!=$row['plan']){
                $s = $this->model->where('plan',$post['plan'])->count();
                if($s>0){
                    $this->error("该提货单号的公路装车已添加");
                }
            }
            $add['plan'] = $post['plan'];
            $add['trans_type'] = $post['trans_type'];
            $add['start_station'] = $post['start_station'];
            $add['end_station'] = $post['end_station'];
            $add['cusname'] = $post['cusname'];
            $add['coaltype'] = $post['coaltype'];
            $add['mini'] = $post['mini'];
            $add['road'] = $post['road'];
            $add['store'] = $post['store'];
            $add['create_time'] = time();//装车类型
            $add['add_id'] = session('admin.id');
            $arr = $post['sale'];
            try {
                $save = $this->model->where('id',$id)->update($add);
                $salecoaldetail= $this->salecoaldetail->where('sc_id',$id)->select();
                $del = $salecoaldetail->delete();
                foreach ($arr as $a){
                    if($a['plan']){
                        $in['sc_id'] = $id;
                        $in['plan'] = $a['plan'];
                        $in['sweight'] = $a['sweight'];
                        $in['fweight'] = $a['fweight'];
                        $in['create_time'] = time();
                        $in['add_id'] = session('admin.id');
                        $this->salecoaldetail->insert($in);
                       
                    }
                }
                if($save){
                    $data = [
                        'code'  => 1,
                        'msg'   => '提交成功',
                        'data'  => $id,
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
        $saleCoalDetail = $this->salecoaldetail->where('sc_id',$id)->select();
        $count1 = 0;$count2 = 0;
        foreach ($saleCoalDetail as $saleD){
            $count1 +=$saleD->sweight;
            $count2 +=$saleD->fweight;
        }
        $this->assign('count1',$count1);
        $this->assign('count2',$count2);
        $this->assign('saleCoalDetail',$saleCoalDetail);
        $this->assign('cou',count($saleCoalDetail));
        //库存仓库
        $stocks = $this->systemStore->where('status',2)->order('name asc')->select();
        $this->assign('stocks',$stocks);
        $colliery = $this->contractColliery->where('status',1)->order("name asc")->select();
        $this->assign('colliery',$colliery);
        $ship = $this->ship->where('status',1)->order("name asc")->select();
        $this->assign('ship',$ship);
        //起运地
        $highwaysite = $this->highwaysite->where('status',1)->select();
        $this->assign('highwaysite',$highwaysite);
        //品种
        $coaltype= $this->coaltype->where('status',1)->select();
        $this->assign('coaltype',$coaltype);
        $this->assign('row', $row);
        $this->assign('menu',2);
        $this->assign('title',"修改公路装车");
        return $this->fetch();
    }
    public function del($id){
        $row = $this->model->where('id', $id)->select();
        $row->isEmpty() && $this->error('数据不存在');
        try {
            //删除装车明细
            $saleCoalD = $this->salecoaldetail->where('sc_id',$id)->select();
            if(count($saleCoalD)>0){
                $row->delete();
            }
            $save = $saleCoalD->delete();
        } catch (\Exception $e) {
            $this->error('删除失败');
        }
        $save ? $this->success('删除成功') : $this->error('删除失败');
    }
    public function print($id){
        $row = $this->model->where('id',$id)->find();
        empty($row) && $this->error('数据不存在');
        //防伪码
        require "../vendor/phpqrcode/phpqrcode.php";
        $qRcode = new \QRcode();
        $server_url = $_SERVER['SERVER_NAME']?"http://".$_SERVER['SERVER_NAME']:"http://".$_SERVER['HTTP_HOST'];
        //var_dump($server_url.'/Highwayloading/pass?id='.$ScID);//http://wap.rizhaolanhua.com/Highwayloading/pass?id=217
        $data = $server_url."/Highwayloading/pass?code=".encrypt('id='.$id);
        //var_dump($data);
        
        $file ="upload/highway/".$id.".jpg";
        // 纠错级别：L、M、Q、H
        $level = 'Q';
        // 点的大小：1到10,用于手机端4就可以了
        $size = 10;
        // 生成的文件名
        $qRcode->png($data, false, $level, $size);
        
        file_put_contents($file,ob_get_contents());
        ob_end_clean();
        if(file_exists($file)){
            $this->assign('file', $file);
        }
        $saleCoalDetail = $this->salecoaldetail->where('sc_id',$id)->select();
        $count1 = 0;$count2 = 0;
        foreach ($saleCoalDetail as $saleD){
            $count1 +=$saleD->sweight;
            $count2 +=$saleD->fweight;
        }
        $this->assign('count1',$count1);
        $this->assign('count2',$count2);
        $this->assign('count',count($saleCoalDetail));
        $this->assign('saleCoalDetail',$saleCoalDetail);
        
        $row->start_station = $this->model->getContractHighwaysiteList()[$row->start_station];
        $row->end_station = $this->model->getContractHighwaysiteList()[$row->end_station];
        $row->coaltype= $this->model->getContractCoaltypeList()[$row->coaltype];
        
        $this->assign('row', $row);
        $this->assign('menu',2);
        $this->assign('title',"打印装车单");
        return $this->fetch();
    }
    public function pass($code){
        $code = decrypt($code);
        $arr = convertUrlQuery($code);
        $row = $this->model->where('id',$arr['id'])->find();
        empty($row) && $this->error('数据不存在');
        $saleCoalDetail = $this->salecoaldetail->where('sc_id',$arr['id'])->select();
        $count1 = 0;$count2 = 0;
        foreach ($saleCoalDetail as $saleD){
            $count1 +=$saleD->sweight;
            $count2 +=$saleD->fweight;
        }
        $this->assign('count1',$count1);
        $this->assign('count2',$count2);
        $this->assign('count',count($saleCoalDetail));
        $this->assign('saleCoalDetail',$saleCoalDetail);
        
        $this->assign('row', $row);
        $this->assign('menu',2);
        $this->assign('title',"公路装车单");
        return $this->fetch();
    }
    public function resort($arr){
        $len = count($arr);
        for ($i = 0; $i < $len-1; $i++) {
            for ($j = $i+1; $j < $len - 1; $j++) {
                if (abs($arr[$i]['location']['top']-$arr[$j]['location']['top'])>abs($arr[$i]['location']['top']-$arr[$j+1]['location']['top'])) {//如果前边的大于后边的
                    $tmp = $arr[$j];//交换数据
                    $arr[$j] = $arr[$j + 1];
                    $arr[$j + 1] = $tmp;
                }
            }
        }
        return $arr;
    }
}