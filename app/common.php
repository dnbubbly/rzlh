<?php
// 应用公共文件

use app\common\service\AuthService;
use think\facade\Cache;
use app\admin\model\StepFlow;
use app\admin\model\StepFlowdetail;
use app\common\constants\AdminConstant;
use app\admin\model\SystemAdmin;
use app\admin\model\SystemDepartment;
use EasyAdmin\tool\CommonTool;

if (!function_exists('__url')) {

    /**
     * 构建URL地址
     * @param string $url
     * @param array $vars
     * @param bool $suffix
     * @param bool $domain
     * @return string
     */
    function __url(string $url = '', array $vars = [], $suffix = true, $domain = false)
    {
        return url($url, $vars, $suffix, $domain)->build();
    }
}

if (!function_exists('password')) {

    /**
     * 密码加密算法
     * @param $value 需要加密的值
     * @param $type  加密类型，默认为md5 （md5, hash）
     * @return mixed
     */
    function password($value)
    {
        $value = sha1('blog_') . md5($value) . md5('_encrypt') . sha1($value);
        return sha1($value);
    }

}

if (!function_exists('xdebug')) {

    /**
     * debug调试
     * @deprecated 不建议使用，建议直接使用框架自带的log组件
     * @param string|array $data 打印信息
     * @param string $type 类型
     * @param string $suffix 文件后缀名
     * @param bool $force
     * @param null $file
     */
    function xdebug($data, $type = 'xdebug', $suffix = null, $force = false, $file = null)
    {
        !is_dir(runtime_path() . 'xdebug/') && mkdir(runtime_path() . 'xdebug/');
        if (is_null($file)) {
            $file = is_null($suffix) ? runtime_path() . 'xdebug/' . date('Ymd') . '.txt' : runtime_path() . 'xdebug/' . date('Ymd') . "_{$suffix}" . '.txt';
        }
        file_put_contents($file, "[" . date('Y-m-d H:i:s') . "] " . "========================= {$type} ===========================" . PHP_EOL, FILE_APPEND);
        $str = (is_string($data) ? $data : (is_array($data) || is_object($data)) ? print_r($data, true) : var_export($data, true)) . PHP_EOL;
        $force ? file_put_contents($file, $str) : file_put_contents($file, $str, FILE_APPEND);
    }
}
if (!function_exists('parseNodeStr')) {
    /**
     * 驼峰转下划线规则
     * @param string $node
     * @return string
     */
    function parseNodeStr($node)
    {
        $array = explode('/', $node);
        foreach ($array as $key => $val) {
            if ($key == 0) {
                $val = explode('.', $val);
                foreach ($val as &$vo) {
                    $vo = CommonTool::humpToLine(lcfirst($vo));
                }
                $val = implode('.', $val);
                $array[$key] = $val;
            }
        }
        $node = implode('/', $array);
        return $node;
    }
}

if (!function_exists('parseNodeTuo')) {
    /**
     * 转驼峰
     * @param string $node
     * @return string
     */
    function parseNodeTuo($node)
    {
        $array = explode('.', $node);
        foreach ($array as $key => $val) {
            $val = ucfirst($val);
            $array[$key] = $val;
        }
        $node = implode('', $array);
        return $node;
    }
}

if (!function_exists('parseContrlTuo')) {
    /**
     * 获取控制类
     * @param string $node
     * @return string
     */
    function parseContrlTuo($node)
    {
        $array = explode('.', $node);
        foreach ($array as $key => $val) {
            $val = ucfirst($val);
            $array[$key] = $val;
        }
        return $array;
    }
}

if (!function_exists('sysconfig')) {

    /**
     * 获取系统配置信息
     * @param $group
     * @param null $name
     * @return array|mixed
     */
    function sysconfig($group, $name = null)
    {
        $where = ['group' => $group];
        $value = empty($name) ? Cache::get("sysconfig_{$group}") : Cache::get("sysconfig_{$group}_{$name}");
        if (empty($value)) {
            if (!empty($name)) {
                $where['name'] = $name;
                $value = \app\admin\model\SystemConfig::where($where)->value('value');
                Cache::tag('sysconfig')->set("sysconfig_{$group}_{$name}", $value, 3600);
            } else {
                $value = \app\admin\model\SystemConfig::where($where)->column('value', 'name');
                Cache::tag('sysconfig')->set("sysconfig_{$group}", $value, 3600);
            }
        }
        return $value;
    }
}

if (!function_exists('array_format_key')) {

    /**
     * 二位数组重新组合数据
     * @param $array
     * @param $key
     * @return array
     */
    function array_format_key($array, $key)
    {
        $newArray = [];
        foreach ($array as $vo) {
            $newArray[$vo[$key]] = $vo;
        }
        return $newArray;
    }

}

if (!function_exists('auth')) {

    /**
     * auth权限验证
     * @param $node
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    function auth($node = null)
    {
        $authService = new AuthService(session('admin.id'));
        $check = $authService->checkNode($node);
        return $check;
    }

}
if (!function_exists('iset_field')) {
    /**
     * iset_field  判断数据表存在某一字段
     * @param $node
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    function iset_field($model,$field){
        $fields=$model->getTableFields();
        return array_search($field,$fields);
    }
}
if (!function_exists('addflowDetail')) {
    /**
     * 根据当前步骤json生成待办详情
     * @param unknown $stepflow
     * @param unknown $json  */
    function addflowDetail($json,$sf,$tm){
        $stepflowDetail = new StepFlowdetail();
        if($json->type==1){//审批
            //直接生成审批待办（待办，已办）
            if($json->settype==1){//指定人员
                if($json->examineMode==1){//依次审批
                    foreach ($json->nodeUserList as $key=>$nodeu){
                        if($key!=0){
                            $sfd['status'] = -1;
                        }
                        $sfd['f_id'] = $sf->id;
                        $sfd['type'] = $json->type;
                        $sfd['examine'] = 1;//依次审批
                        $sfd['current_step'] = $json->nodeName;
                        $sfd['user_id'] = $nodeu->targetId;
                        $sfd['step_json'] = json_encode($json);
                        $stepflowDetail->insert($sfd);
                    }
                }elseif ($json->examineMode==2){//会签
                    foreach ($json->nodeUserList as $key=>$nodeu){
                        $sfd['f_id'] = $sf->id;
                        $sfd['type'] = $json->type;
                        $sfd['examine'] = 2;//会审
                        $sfd['current_step'] = $json->nodeName;
                        $sfd['user_id'] = $nodeu->targetId;
                        $sfd['step_json'] = json_encode($json);
                        $stepflowDetail->insert($sfd);
                    }
                }
            }elseif ($json->settype==2){//主管
                $sfd['f_id'] = $sf->id;
                $sfd['type'] = $json->type;
                $sfd['examine'] = 1;//依次审批
                $sfd['current_step'] = $json->nodeName;
                $sfd['user_id'] = getLeaderuper(session('admin.id'),$json->directorLevel);
                $sfd['step_json'] = json_encode($json);
                $stepflowDetail->insert($sfd);
            }elseif ($json->settype==7){//连续多级主管
                for ($i = 1;$i<=$json->examineEndDirectorLevel;$i++){
                    if($i!=1){
                        $sfd['status'] = -1;
                    }
                    $sfd['f_id'] = $sf->id;
                    $sfd['type'] = $json->type;
                    $sfd['examine'] = 1;//依次审批
                    $sfd['current_step'] = $json->nodeName;
                    $sfd['user_id'] = getLeaderuper(session('admin.id'),$i);
                    $sfd['step_json'] = json_encode($json);
                    $stepflowDetail->insert($sfd);
                }
            }
            
        }elseif ($json->type==2){//抄送
            //直接生成抄送待办（未读，已读）
            foreach ($json->nodeUserList as $key=>$nodeu){
                if($key!=0){
                    $sfd['status'] = -1;
                }
                $sfd['f_id'] = $sf->id;
                $sfd['type'] = $json->type;
                $sfd['examine'] = 1;
                $sfd['current_step'] = $json->nodeName;
                $sfd['user_id'] = $nodeu->targetId;
                $sfd['step_json'] = json_encode($json);
                $stepflowDetail->insert($sfd);
            }
            if($json->childNode){//有下一轮审批
                addflowDetail($json->childNode,$sf,$tm);//抄送添加后自动下一个审批
            }
        }elseif ($json->type==4){//路由
            if($json->conditionNodes){
                foreach ($json->conditionNodes as $cn){
                    addjson($cn,$json->childNode);
                    if(conditionRe($cn,$tm)){
                        addflowDetail($cn->childNode,$sf,$tm);
                    }
                }
                
            }
        }
        return $json;
    }
}

if (!function_exists('getLeader')) {
    /**
     * 获取用户的主管
     * @param unknown $id
     * @param number $num  */
    function getLeader($id){
        $employ = new SystemAdmin();
        $department = new SystemDepartment();
        if ($id== AdminConstant::SUPER_ADMIN_ID ){//超管的主管是他自己
            $lead = $id;
        }else{
            $staff = $employ->where('id',$id)->find();
            $dpt = $department->where('id',$staff->dep_id)->find();
            if($staff->is_leader==1){
                $lead = $dpt->leader_id;
            }else{
                $leader = $employ->where(['dep_id'=>$dpt->id,'is_leader'=>1])->find();
                $lead = $leader->id;
            }
        }
        return $lead;
    }
}

if (!function_exists('getLeaderuper')) {
    /**
     * 返回num级主管
     * @param unknown $id
     * @param unknown $num  */
    function getLeaderuper($id,$num=1){
        
        for ($i=0;$i<$num;$i++){
            $id= getLeader($id)?getLeader($id):$id;
        }
        return $id;
    }
}

if (!function_exists('conditionRe')) {
    /**
     * 返回num级主管
     * @param unknown $id
     * @param unknown $num  */
    function conditionRe($conditionList,$tm,$re=false){
        $where = array();
        if(count($conditionList->conditionList)>0){
            foreach ($conditionList->conditionList as $cl){
                if($cl->type==1){
                    if(in_array(session('admin.id'), array_column($conditionList->nodeUserList, 'targetId'))){
                        $re = true;
                    }
                }else{
                    if($cl->showType==1){
                        if($cl->optType==4){
                            $where[] = [$cl->columnDbname,'=',$cl->zdy1];
                        }elseif ($cl->optType==1){
                            $where[] = [$cl->columnDbname,'<',$cl->zdy1];
                        }elseif ($cl->optType==2){
                            $where[] = [$cl->columnDbname,'>',$cl->zdy1];
                        }elseif ($cl->optType==3){
                            $where[] = [$cl->columnDbname,'<=',$cl->zdy1];
                        }elseif ($cl->optType==5){
                            $where[] = [$cl->columnDbname,'>=',$cl->zdy1];
                        }
                    }elseif ($cl->showType==2){
                        if($cl->optType==1){
                            $where[] = [$cl->columnDbname,'=',$cl->zdy1];
                        }elseif($cl->optType==2){
                            $where[] = [$cl->columnDbname,'like','%'.$cl->zdy1.'%'];
                        }
                    }
                }
            }
            $ifhave = $tm->where($where)->find();
            if($ifhave){
                $re = true;
            }else{
                $re = false;
            }
        }else{
            $re = true;
        }
        return $re;
    }
}

if (!function_exists('addjson')) {
    /**
     * 路由节点最后添加json
     * @param unknown $id
     * @param unknown $num  */
    function addjson($cnode,$json){
        if($cnode->childNode!=null){
            addjson($cnode->childNode,$json);
        }else{
            $cnode->childNode = $json;
        }
    }
}
if (!function_exists('havenextflow')) {
    /**
     * 后续审批有无审批
     * @param unknown $id
     * @param unknown $num  */
    function havenextflow($json){
        if($json->childNode){
            if($json->childNode->type==1){
                return true;
            }else{
                havenextflow($json->childNode);
            }
        }
        return false;
    }
}

if (!function_exists('is_weixin')){
    function is_weixin(){
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }
}
if (!function_exists('getfiletype')){
    /**
     * 文件类型
     * @param unknown $url
     * @return number  */
    function getfiletype($url){
        $pic = array('.jpg','.png','.gif','.jpeg','.bmp');
        $pdf = array('.pdf');
        $word = array('.doc','.docx');
        $zip = array('.zip','.rar','.arj');
        $re = 5;
        if(strtolower(in_array(strrchr($url, '.'),$pic))){
            $re = 1;
        }elseif(strtolower(in_array(strrchr($url, '.'),$pdf))){
            $re = 2;
        }elseif(strtolower(in_array(strrchr($url, '.'),$word))){
            $re = 3;
        }elseif(strtolower(in_array(strrchr($url, '.'),$zip))){
            $re = 4;
        }else{
            $re = 5;
        }
        return $re;
    }
}
if (!function_exists('sendTemplateMessage')){
    /*  */
    function sendTemplateMessage($data){
        $config = [
            
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id'  => 'wx06d520616f83aa10',         // AppID
            'secret'  => '872f39f31cfec874c82f1cb13625399f',     // AppSecret
            'log' => [
                'level'      => 'debug',
                'permission' => 0777,
                'file'       => ROOT_PATH.'runtime/wechat/wxpay.log',
            ],
        ];
        $wx = Factory::officialAccount($config);
        $wx->template_message->send($data);
    }
}
if (!function_exists('getopenid')){
    /**
     * 获取用户openid
     * @param unknown $code  */
    function getopenid($code){
        $employ = new Employ();
        $openid = $employ->where('userCode',$code)->find();
        return $openid->openid;
    }
}
//判断是否图片
if (!function_exists('isImage')){
    function isImage($filename){
        $file  = fopen($filename, "rb");
        $bin  = fread($file, 2); // 只读2字节
        fclose($file);
        $strInfo = @unpack("C2chars", $bin);
        $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
        $fileType = '';
        if($typeCode == 255216 /*jpg*/ || $typeCode == 7173 /*gif*/ || $typeCode == 13780 /*png*/){
            return $typeCode;
        }else{
            return false;
            
        }
    }
    
}
//获取pdf文件页数
if (!function_exists('getPdfPages')){
    function getPdfPages($path){
        // 打开文件
        $fp=@fopen($path,"r");
        if (!$fp) {
            return array(false,"打开文件\"{$path}\"失败");
        }else {
            $max=0;
            while(!feof($fp)) {
                $line = fgets($fp,255);
                if (preg_match('/\/Count [0-9]+/', $line, $matches)){
                    preg_match('/[0-9]+/',$matches[0], $matches2);
                    if ($max<$matches2[0]) $max=$matches2[0];
                }
            }
            fclose($fp);
            // 返回页数
            return $max;
        }
    }
}

if (!function_exists('getname')){
    /**
     * 获取用户openid
     * @param unknown $code  */
    function getname($code){
        $employ = new Employ();
        $openid = $employ->where('userCode',$code)->find();
        return $openid->userName;
    }
}
if (!function_exists('encrypt')){
    /**
     * 加密函数
     * @param string $txt 需要加密的字符串
     * @param string $key 密钥
     * @return string 返回加密结果
     */
    function encrypt($txt, $key = ''){
        if (empty($txt)) return $txt;
        if (empty($key)) $key = md5($key);
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
        $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
        $nh1 = rand(0,64);
        $nh2 = rand(0,64);
        $nh3 = rand(0,64);
        $ch1 = $chars{$nh1};
        $ch2 = $chars{$nh2};
        $ch3 = $chars{$nh3};
        $nhnum = $nh1 + $nh2 + $nh3;
        $knum = 0;$i = 0;
        while(isset($key{$i})) $knum +=ord($key{$i++});
        $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum%8,$knum%8 + 16);
        $txt = base64_encode(time().'_'.$txt);
        $txt = str_replace(array('+','/','='),array('-','_','.'),$txt);
        $tmp = '';
        $j=0;$k = 0;
        $tlen = strlen($txt);
        $klen = strlen($mdKey);
        for ($i=0; $i<$tlen; $i++) {
            $k = $k == $klen ? 0 : $k;
            $j = ($nhnum+strpos($chars,$txt{$i})+ord($mdKey{$k++}))%64;
            $tmp .= $chars{$j};
        }
        $tmplen = strlen($tmp);
        $tmp = substr_replace($tmp,$ch3,$nh2 % ++$tmplen,0);
        $tmp = substr_replace($tmp,$ch2,$nh1 % ++$tmplen,0);
        $tmp = substr_replace($tmp,$ch1,$knum % ++$tmplen,0);
        return $tmp;
    }
}
if (!function_exists('decrypt')){
    /**
     * 解密函数
     * @param string $txt 需要解密的字符串
     * @param string $key 密匙
     * @return string 字符串类型的返回结果
     */
    function decrypt($txt, $key = '', $ttl = 0){
        if (empty($txt)) return $txt;
        if (empty($key)) $key = md5($key);
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
        $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
        $knum = 0;$i = 0;
        $tlen = @strlen($txt);
        while(isset($key{$i})) $knum +=ord($key{$i++});
        $ch1 = @$txt{$knum % $tlen};
        $nh1 = strpos($chars,$ch1);
        $txt = @substr_replace($txt,'',$knum % $tlen--,1);
        $ch2 = @$txt{$nh1 % $tlen};
        $nh2 = @strpos($chars,$ch2);
        $txt = @substr_replace($txt,'',$nh1 % $tlen--,1);
        $ch3 = @$txt{$nh2 % $tlen};
        $nh3 = @strpos($chars,$ch3);
        $txt = @substr_replace($txt,'',$nh2 % $tlen--,1);
        $nhnum = $nh1 + $nh2 + $nh3;
        $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum % 8,$knum % 8 + 16);
        $tmp = '';
        $j=0; $k = 0;
        $tlen = @strlen($txt);
        $klen = @strlen($mdKey);
        for ($i=0; $i<$tlen; $i++) {
            $k = $k == $klen ? 0 : $k;
            $j = strpos($chars,$txt{$i})-$nhnum - ord($mdKey{$k++});
            while ($j<0) $j+=64;
            $tmp .= $chars{$j};
        }
        $tmp = str_replace(array('-','_','.'),array('+','/','='),$tmp);
        $tmp = trim(base64_decode($tmp));
        if (preg_match("/\d{10}_/s",substr($tmp,0,11))){
            if ($ttl > 0 && (time() - substr($tmp,0,11) > $ttl)){
                $tmp = null;
            }else{
                $tmp = substr($tmp,11);
            }
        }
        return $tmp;
    }
}
if (!function_exists('convertUrlQuery')){
    /**
     * 获取url参数
     */
    function convertUrlQuery($query){
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }
}
if (!function_exists('isHttp')){
    /**
     * 获取url参数
     */
    function isHttp($url){
        $res = strpos($url, "http://");
        if($res===false){
            return false;
        }else{
            return true;
        }
    }
}
















