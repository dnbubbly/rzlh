<?php /*a:2:{s:61:"E:\wamp64\www\rlzy\app\admin\view\step\flowdetail\detail.html";i:1644800651;s:53:"E:\wamp64\www\rlzy\app\admin\view\layout\default.html";i:1638342384;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo sysconfig('site','site_name'); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="/static/admin/css/public.css?v=<?php echo htmlentities($version); ?>" media="all">
    <script>
        window.CONFIG = {
            ADMIN: "<?php echo htmlentities((isset($adminModuleName) && ($adminModuleName !== '')?$adminModuleName:'admin')); ?>",
            CONTROLLER_JS_PATH: "<?php echo htmlentities((isset($thisControllerJsPath) && ($thisControllerJsPath !== '')?$thisControllerJsPath:'')); ?>",
            ACTION: "<?php echo htmlentities((isset($thisAction) && ($thisAction !== '')?$thisAction:'')); ?>",
            AUTOLOAD_JS: "<?php echo htmlentities((isset($autoloadJs) && ($autoloadJs !== '')?$autoloadJs:'false')); ?>",
            IS_SUPER_ADMIN: "<?php echo htmlentities((isset($isSuperAdmin) && ($isSuperAdmin !== '')?$isSuperAdmin:'false')); ?>",
            VERSION: "<?php echo htmlentities((isset($version) && ($version !== '')?$version:'1.0.0')); ?>",
            CSRF_TOKEN: "<?php echo token(); ?>",
        };
    </script>
    <script src="/static/plugs/layui-v2.5.6/layui.all.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
    <script src="/static/plugs/require-2.3.6/require.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
    <script src="/static/config-admin.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
</head>
<body>
<div class="layuimini-container">
	<form id="app-form" class="layui-form layuimini-form layui-form-pane">
	  	<div class="layui-field-box">
			<fieldset class="layui-elem-field layui-field-title" style="margin-top: 0;">
				<legend>审批记录</legend>
			</fieldset> 
	    	<div class="layui-form-item">
		    	<label class="layui-form-label">审批记录</label>
		        <div class="layui-input-block">
		            <input type="text"  name="user_id" class="layui-input" readonly="true" lay-verify="required" placeholder="请输入审批人" value="<?php echo htmlentities((isset($row['stepFlow']['title']) && ($row['stepFlow']['title'] !== '')?$row['stepFlow']['title']:'')); ?>">
		        </div>
		    </div>
		    <div class="layui-form-item">
		        <label class="layui-form-label">当前审批步骤</label>
		        <div class="layui-input-block">
		            <input type="text" name="current_step" class="layui-input" readonly="true" lay-verify="required" placeholder="请输入审批人" value="<?php echo htmlentities((isset($row['current_step']) && ($row['current_step'] !== '')?$row['current_step']:'')); ?>">
		        </div>
		    </div>
		    <div class="layui-form-item">
		        <label class="layui-form-label">审批人</label>
		        <div class="layui-input-block">
		            <input type="text" name="user_id" class="layui-input" readonly="true" lay-verify="required" placeholder="请输入审批人" value="<?php echo htmlentities((isset($row['systemAdmin']['name']) && ($row['systemAdmin']['name'] !== '')?$row['systemAdmin']['name']:'')); ?>">
		        </div>
		    </div>
		    <div class="layui-form-item layui-form-text">
		        <label class="layui-form-label">审批意见</label>
		        <div class="layui-input-block">
		        	<textarea name="replay" class="layui-textarea" placeholder="请输入备注信息"><?php echo htmlentities((isset($row['replay']) && ($row['replay'] !== '')?$row['replay']:'')); ?></textarea>
		        </div>
		    </div>
		    <div class="layui-form-item">
		        <label class="layui-form-label">审批时间</label>
		        <div class="layui-input-block">
		            <input type="text" name="date" data-date="" readonly="true" data-date-type="datetime" class="layui-input"  placeholder="请输入审批时间" value="<?php echo date('Y-m-d H:i:s',$row['date']); ?>">
		        </div>
		    </div> 
	  	</div>
		<div class="layui-field-box">
			<fieldset class="layui-elem-field layui-field-title" style="margin-top: 0;">
				<legend>审批记录</legend>
			</fieldset> 
	  		<ul class="layui-timeline">
	  			<?php foreach($ready as $re): ?>
	  			<li class="layui-timeline-item">
				   	<i class="layui-icon layui-timeline-axis"></i>
    				<div class="layui-timeline-content layui-text">
      					<h3 class="layui-timeline-title"><?php echo date("Y-m-d H:i:s",$re['date']); ?></h3>
      					<p><?php echo htmlentities($re['systemAdmin']['username']); ?>：<?php echo htmlentities($re['replay']); ?></p>
    				</div>
				</li>
				<?php endforeach; ?>
			</ul>
	  	</div>
	</form>
</div>

</body>
</html>