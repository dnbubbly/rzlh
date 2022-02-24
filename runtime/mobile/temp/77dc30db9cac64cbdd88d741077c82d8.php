<?php /*a:3:{s:51:"E:\wamp64\www\rzlh\app\mobile\view\index\index.html";i:1645664055;s:51:"E:\wamp64\www\rzlh\app\mobile\view\common\head.html";i:1639116506;s:50:"E:\wamp64\www\rzlh\app\mobile\view\common\bot.html";i:1645668292;}*/ ?>
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
<style id="layuimini-bg-color">
</style>
<body>
	<div class="head fc">
	<?php echo htmlentities($title); ?>
</div>

	<div class="indexlist">
		<ul class="ht">
			
		</ul>
	</div>
	<script>
	layui.use('flow', function() {
	    var $ = layui.jquery;
	    var flow = layui.flow;
	    flow.load({
	        elem: '.indexlist ul' //流加载容器
	        ,isAuto:true
	        ,isLazyimg:true
	        ,done: function(page, next){ //执行下一页的回调
	            var lis = [];
	            $.ajax({
	                url:"/Index",
	                dataType:'json',
	                type:'post',
	                data:{'page':page,'limit':5},
	                success:function(t){
	                    layui.each(t.data, function(index, item){
	                    	var type="";
	                    	if(item.cType==1){
	                    		type="销售合同";
	                    	}else {
	                    		type="采购合同";

	                    		item.cChegndLve = item.cSeller;
	                    	}
	                    	lis.push('<li>'
	                				+'<div class="tit" onclick="toCgsale(this)" data-id="">通知单编号：<p><span>合同编号：</span></p></div>'
	                				+'<div class="line"></div>'
	                				+'<div class="listcar" onclick="toSale(this)" data-id="120,121,122">'
	                				+'	<div class="ctop"><div><p>供货单位：<span></span></p><p>收货单位：<span></span></p></div></div>'
	                				+'</div>'
	                				+'<div class="line"></div>'
	                				+'<div class="btn"><a href="/Saleorder/cgdetail?id=" class="fc">审批</a><a href="/Saleorder/cgdetail?id=" class="fc">查看</a></div></li>'
	                		);
	
	                    });
	                    next(lis.join(''), page < t.pages);
	                },
	                error: function () {
	                    layer.open({content:'网络繁忙,请重试',skin:'msg',time:2});
	                }
	            })
	        }
	    });
	})
	function toindex(obj){
		var id = $(obj).data("id");
		window.location.href='/Contract/detail?id='+id;
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