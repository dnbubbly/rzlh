<?php /*a:1:{s:51:"E:\wamp64\www\rzlh\app\mobile\view\login\index.html";i:1640846005;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<meta name="Keywords" content="">
<meta name="Description" content="">
<title>日照兰花业财一体化系统</title>
<link rel="stylesheet" type="text/css" href="/static/mobile/css/login.css?v=<?php echo time(); ?>" media="all">
<link rel="stylesheet" type="text/css" href="/static/mobile/css/layer.css?v=<?php echo time(); ?>" >
<script type="text/javascript" src="/static/mobile/js/rem.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/plugs/jquery-3.4.1/jquery-3.4.1.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/plugs/layui-v2.5.6/layui.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/mobile/js/layer.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/mobile/js/login.js?v=<?php echo time(); ?>"></script>
<body>
	<div class="head">
		<div class="img">
			<img src="/static/mobile/images/head.jpg">
		</div>
		<p>日照兰花业财一体化</p>
	</div>
	<!--登陆-->
	<form class="layui-form" action="#">
		<div class="item">
			<input type="text" name="username" maxlength="11" placeholder="请输入您的帐号" autocomplete="off" class="name">
		</div>
		<div class="item">
			<input type="password" name="password" placeholder="请输入您的密码" autocomplete="off" class="password">
		</div>
		<?php if($captcha == 1): ?>
		<div class="item">
			<input type="text" name="captcha" maxlength="6" placeholder="请输入验证码" autocomplete="off" class="vcode">
			<span id="vcode"><img id="refreshCaptcha" class="validateImg"  src="<?php echo url('login/captcha'); ?>" onclick="this.src='<?php echo url('login/captcha'); ?>?seed='+Math.random()"></span>
		</div>
        <?php endif; ?>
        <!-- <div class="tip">
        	<span class="icon-nocheck"></span>
        	<span class="login-tip">保持登录</span>
        </div> -->
		<div class="submit"><button lay-filter="login" lay-submit>登&nbsp;陆</button></div>
	</form>
	<div class="footer">
	    版权所有：日照兰花冶电能源有限公司<span class="padding-5">|</span><a target="_blank" href="http://www.miitbeian.gov.cn">ICP***号</a>
	</div>
</body>
</html>