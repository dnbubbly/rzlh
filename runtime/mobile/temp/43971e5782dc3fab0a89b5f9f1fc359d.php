<?php /*a:3:{s:49:"E:\wamp64\www\rzlh\app\mobile\view\my\detail.html";i:1645671218;s:51:"E:\wamp64\www\rzlh\app\mobile\view\common\head.html";i:1639116506;s:50:"E:\wamp64\www\rzlh\app\mobile\view\common\bot.html";i:1640934938;}*/ ?>
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
<link rel="stylesheet" type="text/css" href="/static/mobile/css/default.css?v=<?php echo time(); ?>" media="all">
<link rel="stylesheet" type="text/css" href="/static/mobile/css/swiper.min.css?v=<?php echo time(); ?>" media="all">
<link rel="stylesheet" type="text/css" href="/static/mobile/css/layer.css?v=<?php echo time(); ?>" >
<link rel="stylesheet" type="text/css" href="/static/plugs/layui-v2.5.6/css/layui.css?v=<?php echo time(); ?>" >
<script type="text/javascript" src="/static/mobile/js/rem.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/plugs/jquery-3.4.1/jquery-3.4.1.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/mobile/js/layer.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/mobile/js/swiper.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/plugs/layui-v2.5.6/layui.js?v=<?php echo time(); ?>"></script>
</head>
<body>
	<div class="head fc">
	<?php echo htmlentities($title); ?>
</div>

	<div class="user_detail">
		<ul>
	        <li onclick="editname()">
	            <h4 class="left">姓名</h4>
	            <p class="right"><span id="name"><?php echo htmlentities($member['username']); ?></span><img src="/static/mobile/images/next1.png" class="next"></p>
	        </li>
	        <li class="sex">
	            <h4 class="left">手机号</h4>
	            <p class="right"><span id="tel"><?php echo htmlentities($member['phone']); ?></span><img src="/static/mobile/images/next1.png" class="next"></p>
	        </li>
	        <li class="sex">
	            <h4 class="left">工号</h4>
	            <p class="right"><span id="tel"><?php echo htmlentities($member['number']); ?></span><img src="/static/mobile/images/next1.png" class="next"></p>
	        </li>
	        <li class="sex">
	            <h4 class="left">年龄</h4>
	            <p class="right"><span id="age"><?php echo htmlentities($member['age']); ?></span><img src="/static/mobile/images/next1.png" class="next"></p>
	        </li>
	        <li class="sex">
	            <h4 class="left">性别</h4>
	            <p class="right"><span id="qq"><?php echo htmlentities($member['sex']); ?></span><img src="/static/mobile/images/next1.png" class="next"></p>
	        </li>
	        <li class="sex">
	            <h4 class="left">职务</h4>
	            <p class="right"><span id="email"><?php echo htmlentities($member['job']); ?></span><img src="/static/mobile/images/next1.png" class="next"></p>
	        </li>
		</ul>
	</div>
	<div style="margin-top:25%;float: left;width: 100%;"></div>
	<div id="user_indexbot">
		<?php if($menu==1): ?>
		<a href="/index/">
	        <img src="/static/mobile/pic/xbh1.png" alt="">
	        <p>待办</p>
	    </a>
		<?php else: ?>
	    <a href="/index/">
	        <img src="/static/mobile/pic/xb1.png" alt="">
	        <p>待办</p>
	    </a>
	    <?php endif; if($menu==2): ?>
		<a href="/Workbench/">
	        <img src="/static/mobile/pic/xbh2.png" alt="">
	        <p>工作台</p>
	    </a>
		<?php else: ?>
	    <a href="/Workbench/">
	        <img src="/static/mobile/pic/xb2.png" alt="">
	        <p>工作台</p>
	    </a>
	    <?php endif; if($menu==3): ?>
		<a href="/my/">
	        <img src="/static/mobile/pic/xbh3.png" alt="">
	        <p>我的</p>
	    </a>
		<?php else: ?>
	    <a href="/my/">
	        <img src="/static/mobile/pic/xb3.png" alt="">
	        <p>我的</p>
	    </a>
	    <?php endif; ?>
	    <div class="clear"></div>
	</div>
</div>

</body>
</html>