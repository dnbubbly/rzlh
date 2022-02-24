<?php /*a:2:{s:57:"E:\wamp64\www\rzlh\app\admin\view\contract\info\sign.html";i:1645528308;s:53:"E:\wamp64\www\rzlh\app\admin\view\layout\default.html";i:1645080665;}*/ ?>
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
    	<div class="layui-form-item" pane>
    		<label class="layui-form-label">我方是否已签署</label>
	        <div class="layui-input-block">
	            <input name="isour" type="checkbox" lay-skin="switch" lay-filter="switchTestH" lay-text="ON|OFF" <?php if($row['isour']==1): ?> checked="" <?php endif; ?>>
	        </div>
        </div>
        <div class="layui-form-item">
        	<span id="lbl_cDate" class="layui-form-label">签订日期</span>
            <div class="layui-input-block">
				<input name="oursigndate" type="text" lay-verify="required" data-date="" data-date-type="date" autocomplete="off" placeholder="请确认我方签订日期" value="<?php echo htmlentities((isset($row['oursigndate']) && ($row['oursigndate'] !== '')?$row['oursigndate']:'')); ?>" class="layui-input" >
			</div>
		</div>
        <div class="layui-form-item" pane>
            <label class="layui-form-label">对方是否已签署</label>
            <div class="layui-input-block">
              	<input name="isother" type="checkbox" lay-skin="switch" lay-filter="switchTestM" lay-text="ON|OFF" <?php if($row['isother']==1): ?> checked="" <?php endif; ?>>
            </div>
        </div>
        <div class="layui-form-item">
            <span id="lbl_cDated" class="layui-form-label">签订日期</span>
			<div class="layui-input-block">
				<input name="othersigndate" type="text" lay-verify="required" lay-verify="required" data-date="" data-date-type="date" autocomplete="off" placeholder="请确认对方签订日期" value="<?php echo htmlentities((isset($row['othersigndate']) && ($row['othersigndate'] !== '')?$row['othersigndate']:'')); ?>" class="layui-input">
			</div>
        </div>
        <div class="hr-line"></div>
        <div class="layui-form-item text-center">
            <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>确认</button>
            <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
        </div>

    </form>
</div>
</body>
</html>