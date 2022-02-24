<?php

namespace app\mobile\controller;

use app\common\controller\AdminController;
use think\captcha\facade\Captcha;
use think\facade\Env;
use EasyWeChat\Factory;
use app\admin\model\Employ;
use app\admin\model\SystemAdmin;

class Login extends AdminController
{

    protected $layout = FALSE;
    
    public function initialize()
    {
        $this->config = [
            'app_id' => 'wx06d520616f83aa10',
            'secret' => '872f39f31cfec874c82f1cb13625399f',
            'response_type' => 'array',
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => '/login/callback',
            ],
        ];
        
    }
    /**
     * 用户登录
     * @return string
     * @throws \Exception
     */
    public function index()
    {
        $captcha = Env::get('easyadmin.captcha', 1);
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [
                'username|账号'      => 'require',
                'password|密码'       => 'require',
            ];
            $captcha == 1 && $rule['captcha|验证码'] = 'require|captcha';
            $this->validate($post, $rule);
            $admin = SystemAdmin::where(['useraccount' => $post['username']])->find();
            if (empty($admin)) {
                $this->error('您的账号不存在');
            }
            if (password($post['password']) != $admin->password) {
                $this->error('密码输入有误');
            }
            $admin->login_num += 1;
            if(session('openid')){
                $admin->openid = session('openid');
            }
            $admin->save();
            $admin = $admin->toArray();
            unset($admin['password']);
            $admin['expire_time'] = time() + 31536000;
            session('admin', $admin);
            $this->success('登录成功','',session('currentNode')?session('currentNode'):__url('index/index'));
        }
        $this->assign('captcha', $captcha);
        $this->assign('demo', $this->isDemo);
        return $this->fetch();
    }
    /**
     * 微信公众号登录
     *   */
    public function callback()
    {
        $code = input('code','');
        $app = Factory::officialAccount($this->config);
        $user = $app->oauth->user()->toArray();
        $member = new SystemAdmin();
        $admin= $member->where(['openid'=>$user['id']])->find();
        if(!$admin){
            session('openid',$user['id']);
            $this->redirect('/login',302);
        }else{
            $admin = $admin->toArray();
            $admin['expire_time'] = time() + 31536000;
            session('admin',$admin);
            $url = session('currentNode')?session('currentNode'):__url('index/index');
            $this->redirect($url,302);
        }
        
    }
    /**
     * 验证码
     * @return \think\Response
     */
    public function captcha()
    {
        return Captcha::create();
    }
    /**
     * 用户退出
     * @return mixed
     */
    public function out()
    {
        session('admin', null);
        session('currentNode',null);
        $this->success('退出登录成功');
    }
}
