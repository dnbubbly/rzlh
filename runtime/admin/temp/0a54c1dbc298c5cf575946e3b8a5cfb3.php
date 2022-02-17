<?php /*a:2:{s:55:"E:\wamp64\www\rlzy\app\admin\view\system\site\edit.html";i:1642469999;s:53:"E:\wamp64\www\rlzy\app\admin\view\layout\default.html";i:1638342384;}*/ ?>
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
        
        <div class="layui-form-item">
            <label class="layui-form-label required">发站</label>
            <div class="layui-input-block">
                <input type="text" name="origin" class="layui-input" lay-verify="required" placeholder="请输入发站" placeholder="请输入发站" value="<?php echo htmlentities((isset($row['origin']) && ($row['origin'] !== '')?$row['origin']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">到站</label>
            <div class="layui-input-block">
                <input type="text" name="end" class="layui-input" lay-verify="required" placeholder="请输入到站" placeholder="请输入到站" value="<?php echo htmlentities((isset($row['end']) && ($row['end'] !== '')?$row['end']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">省市</label>
            <div class="layui-input-block">
                <input type="text" name="provinces" class="layui-input" lay-verify="required" placeholder="请输入省市" placeholder="请输入省市" value="<?php echo htmlentities((isset($row['provinces']) && ($row['provinces'] !== '')?$row['provinces']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">所属局</label>
            <div class="layui-input-block">
                <input type="text" name="bureau" class="layui-input" lay-verify="required" placeholder="请输入所属局" placeholder="请输入所属局" value="<?php echo htmlentities((isset($row['bureau']) && ($row['bureau'] !== '')?$row['bureau']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">里程</label>
            <div class="layui-input-block">
                <input type="text" name="mileage" class="layui-input" lay-verify="required" placeholder="请输入里程" placeholder="请输入里程" value="<?php echo htmlentities((isset($row['mileage']) && ($row['mileage'] !== '')?$row['mileage']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">运费</label>
            <div class="layui-input-block">
                <input type="text" name="freight" class="layui-input" lay-verify="required" placeholder="请输入运费" value="<?php echo htmlentities((isset($row['freight']) && ($row['freight'] !== '')?$row['freight']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">取送车费</label>
            <div class="layui-input-block">
                <input type="text" name="carcost" class="layui-input" lay-verify="required" placeholder="请输入取送车费" value="<?php echo htmlentities((isset($row['carcost']) && ($row['carcost'] !== '')?$row['carcost']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">保费</label>
            <div class="layui-input-block">
                <input type="text" name="premium" class="layui-input" lay-verify="required" placeholder="请输入保费"  placeholder="请输入保费" value="<?php echo htmlentities((isset($row['premium']) && ($row['premium'] !== '')?$row['premium']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
            	<textarea name="remark" rows="10" class="layui-textarea editor" placeholder="请输入备注"><?php echo (isset($row['remark']) && ($row['remark'] !== '')?$row['remark']:''); ?></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">添加人</label>
            <div class="layui-input-block">
               <input type="hidden" name="add_id" class="layui-input" readonly="true" style="background:#f1f1f1"  value="<?php echo session('admin.id'); ?>">
               <input type="text" name="" class="layui-input" readonly="true" style="background:#f1f1f1"  value="<?php echo session('admin.username'); ?>">
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