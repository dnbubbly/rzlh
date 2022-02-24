<?php /*a:2:{s:48:"E:\wamp64\www\rzlh\app\mobile\view\my\index.html";i:1645705614;s:50:"E:\wamp64\www\rzlh\app\mobile\view\common\bot.html";i:1640934938;}*/ ?>
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
<body style="background: #dfefff;">
<div class="user_head center">
	<div class="user_info">
     	<div class="user_icon">
			<div class="user_name">
				<div class="title"><?php echo htmlentities($member['username']); ?>【<?php echo htmlentities($member['number']); ?>】</div>
				<div class="level"><?php echo htmlentities($member['job']); ?></div>
			</div>
		</div>
	</div>
	<div class="userli">
		<a href="/index"><div class="num"><?php echo htmlentities($num1); ?></div><p>我的待办</p></a>
		<a href="/my/already/"><div class="num"><?php echo htmlentities($num2); ?></div><p>已办事项</p></a>
		<a href="/my/detail/"><img src="/static/mobile/images/memicon5.png" class="icon"><p>个人信息</p></a>
	</div>
</div>
<div class="user center">
	<div class="user_menu">
		<ul>
			<li>
				<a href="/Railwayloading/add">
					<img src="/static/mobile/images/memicon1.png" class="icon">
                	<h4 class="left">铁路装车</h4>
                	<p class="right"><span></span><img src="/static/mobile/images/next1.png" class="next"></p>
                </a>
            </li>
			<li>
				<a href="/Highwayloading/add">
					<img src="/static/mobile/images/memicon3.png" class="icon">
                	<h4 class="left">公路装车</h4>
                	<p class="right"><span></span><img src="/static/mobile/images/next1.png" class="next"></p>
                </a>
            </li>
            <li onclick="out()">
				<a href="javascript:void(0)">
					<img src="/static/mobile/images/memicon7.png" class="icon">
                	<h4 class="left">退出登录</h4>
                	<p class="right"><span></span><img src="/static/mobile/images/next1.png" class="next"></p>
                </a>
            </li>
		</ul>
	</div>
</div>
<script>
function out(){
	layer.open({
	    content: '您确定退出登录吗？'
	    ,btn: ['确定', '不要']
	    ,yes: function(index){
			$.ajax({
		        url:"/login/out",
		        dataType:'json',
		        type:'post',
		        success:function(t){
		        	if(t.code==1){
		        		window.location = '/login';
		        	}else{
		        		layer.open({content:t.msg,skin:'msg',time:2});
		        	}
		        },
		        error: function () {
		            layer.open({content:'网络繁忙,请重试',skin:'msg',time:2});
		        }
		    })
	    }
	})
}
</script>
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