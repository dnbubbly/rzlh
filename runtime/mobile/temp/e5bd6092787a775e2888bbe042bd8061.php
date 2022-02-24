<?php /*a:3:{s:55:"E:\wamp64\www\rzlh\app\mobile\view\workbench\index.html";i:1642484892;s:51:"E:\wamp64\www\rzlh\app\mobile\view\common\head.html";i:1639116506;s:50:"E:\wamp64\www\rzlh\app\mobile\view\common\bot.html";i:1640934938;}*/ ?>
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
<script type="text/javascript" src="/static/mobile/js/rem.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/plugs/jquery-3.4.1/jquery-3.4.1.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/mobile/js/layer.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/mobile/js/swiper.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/plugs/layui-v2.5.6/layui.js?v=<?php echo time(); ?>"></script>
<style id="layuimini-bg-color">
</style>
<body>
<div class="head fc">
	<?php echo htmlentities($title); ?>
</div>

<div class="workbench">
	<div class="nav">
		<div class="tit">业务审批</div>
		<ul>
			<li>
				<a href="/Contract"><div class="img"><img src="/static/mobile/pic/nav1.png"></div><p>合同审批</p></a>
			</li>
			<li>
				<a href="/Saledepartment"><div class="img"><img src="/static/mobile/pic/nav2.png"></div><p>客户准入审批</p></a>
			</li>
			<li>
				<a href="/Saleorder/"><div class="img"><img src="/static/mobile/pic/nav3.png"></div><p>销售通知审批</p></a>
			</li>
			<li>
				<a href="/Saleorder/cgindex"><div class="img"><img src="/static/mobile/pic/nav4.png"></div><p>采购开单审批</p></a>
			</li>
		</ul>
	</div>
	<div class="nav">
		<div class="tit">业务操作</div>
		<ul>
			<li>
				<a href="/Load/"><div class="img"><img src="/static/mobile/pic/nav5.png"></div><p>装车入库</p></a>
			</li>
		</ul>
	</div>
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