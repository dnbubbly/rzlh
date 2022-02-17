<?php /*a:2:{s:55:"E:\wamp64\www\rlzy\app\admin\view\system\store\add.html";i:1643006170;s:53:"E:\wamp64\www\rlzy\app\admin\view\layout\default.html";i:1638342384;}*/ ?>
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
            <label class="layui-form-label">仓库全称</label>
            <div class="layui-input-block">
                <input type="text" name="name" class="layui-input" lay-verify="required" placeholder="请输入仓库全程" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">仓库编号</label>
            <div class="layui-input-block">
                <input type="text" name="number" class="layui-input" lay-verify="required" placeholder="请输入仓库编号" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">仓库地址</label>
            <div class="layui-input-block">
                <input type="text" name="address" class="layui-input" lay-verify="required" placeholder="请输入仓库地址" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系人</label>
            <div class="layui-input-block">
                <input type="text" name="contacts" class="layui-input" lay-verify="required" placeholder="请输入联系人" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系人电话</label>
            <div class="layui-input-block">
                <input type="text" name="tel" class="layui-input" lay-verify="required" placeholder="请输入联系人电话" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">仓储协议</label>
            <div class="layui-input-block layuimini-upload">
                <input name="agreement" class="layui-input layui-col-xs6" lay-verify="required" lay-reqtext="请上传仓储协议" placeholder="请上传仓储协议" value="">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="agreement" data-upload-number="one" data-upload-exts="pdf|doc|docx|zip|rar" data-upload-icon="image"><i class="fa fa-upload"></i> 上传</a></span>
                    <span><a class="layui-btn layui-btn-normal" id="select_agreement" data-upload-select="agreement" data-upload-number="one" data-upload-mimetype="*"><i class="fa fa-list"></i> 选择</a></span>
                </div>
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">备注说明</label>
            <div class="layui-input-block">
                <textarea name="remark" class="layui-textarea"  placeholder="请输入备注说明"></textarea>
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