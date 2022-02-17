<?php /*a:2:{s:55:"E:\wamp64\www\rlzy\app\admin\view\system\admin\add.html";i:1638926023;s:53:"E:\wamp64\www\rlzy\app\admin\view\layout\default.html";i:1638342384;}*/ ?>
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
		
		<div class="layui-form-item  layui-row layui-col-xs12">
            <label class="layui-form-label required">上级部门</label>
            <div class="layui-input-block">
                <select name="dep_id">
                    <?php foreach($pidDepartmentList as $vo): ?>
                    <option value="<?php echo htmlentities($vo['id']); ?>" <?php if($id==$vo['id']): ?>selected=""<?php endif; ?>><?php echo $vo['title']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
		
		<div class="layui-form-item">
            <label class="layui-form-label required">员工姓名</label>
            <div class="layui-input-block">
                <input type="text" name="name" class="layui-input" lay-verify="required" lay-reqtext="请输入员工姓名" placeholder="请输入员工姓名" value="">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">是否负责人</label>
            <div class="layui-input-block">
                <input type="checkbox" name="is_leader" checked="" lay-skin="switch" lay-text="ON|OFF" value="0">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">用户头像</label>
            <div class="layui-input-block layuimini-upload">
                <input name="head_img" class="layui-input layui-col-xs6" lay-verify="required" lay-reqtext="请上传用户头像" placeholder="请上传用户头像" value="">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="head_img" data-upload-number="one" data-upload-exts="png|jpg|ico|jpeg" data-upload-icon="image"><i class="fa fa-upload"></i> 上传</a></span>
                    <span><a class="layui-btn layui-btn-normal" id="select_head_img" data-upload-select="head_img" data-upload-number="one" data-upload-mimetype="image/*"><i class="fa fa-list"></i> 选择</a></span>
                </div>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">员工工号</label>
            <div class="layui-input-block">
                <input type="text" name="number" class="layui-input" lay-verify="required" lay-reqtext="请输入员工工号" placeholder="请输入员工工号" value="">
            </div>
        </div>
      
      	<div class="layui-form-item" pane="">
            <label class="layui-form-label required">性别</label>
            <div class="layui-input-block">
                <?php foreach($getSexList as $k=>$v): ?>
                <input type="radio" name="sex" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), explode(',',"0"))): ?>checked=""<?php endif; ?>>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">年龄</label>
            <div class="layui-input-block">
                <input type="text" name="age" class="layui-input" placeholder="请输入员工年龄" value="">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">职位</label>
            <div class="layui-input-block">
                <input type="text" name="job" class="layui-input" lay-verify="required" lay-reqtext="请输入职位" placeholder="请输入职位" value="">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">登录账户</label>
            <div class="layui-input-block">
                <input type="text" name="username" class="layui-input" lay-verify="required" lay-reqtext="请输入登录账户" placeholder="请输入登录账户" value="">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">用户手机</label>
            <div class="layui-input-block">
                <input type="text" name="phone" class="layui-input" lay-reqtext="请输入用户手机" placeholder="请输入用户手机" value="">
            </div>
        </div>

        <div class="layui-form-item" pane="">
            <label class="layui-form-label">角色权限</label>
            <div class="layui-input-block">
                <?php foreach($auth_list as $key=>$val): ?>
                <input type="checkbox" name="auth_ids[<?php echo htmlentities($key); ?>]" lay-skin="primary" title="<?php echo htmlentities($val); ?>">
                <?php endforeach; ?>
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">备注信息</label>
            <div class="layui-input-block">
                <textarea name="remark" class="layui-textarea" placeholder="请输入备注信息"></textarea>
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