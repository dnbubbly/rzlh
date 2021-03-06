<?php /*a:2:{s:57:"E:\wamp64\www\rlzy\app\admin\view\customer\info\edit.html";i:1643010674;s:53:"E:\wamp64\www\rlzy\app\admin\view\layout\default.html";i:1638342384;}*/ ?>
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
        <div class="layui-row layui-col-space5">
            <div class="layui-col-md6">
		        <div class="layui-form-item">
		            <label class="layui-form-label">??????</label>
		            <div class="layui-input-block">
		                <input type="text" name="code" class="layui-input" lay-verify="required" placeholder="???????????????" value="<?php echo htmlentities((isset($row['code']) && ($row['code'] !== '')?$row['code']:'')); ?>">
		            </div>
		        </div>
		        <div class="layui-form-item">
		            <label class="layui-form-label">???????????????</label>
		            <div class="layui-input-block">
		                <input type="text" name="legal" class="layui-input"  placeholder="???????????????" value="<?php echo htmlentities((isset($row['legal']) && ($row['legal'] !== '')?$row['legal']:'')); ?>">
		            </div>
		        </div>
		        <div class="layui-form-item">
		            <label class="layui-form-label">??????????????????</label>
		            <div class="layui-input-block">
		                <input type="text" name="director" class="layui-input"  placeholder="??????????????????" value="<?php echo htmlentities((isset($row['director']) && ($row['director'] !== '')?$row['director']:'')); ?>">
		            </div>
		        </div>
		        <div class="layui-form-item">
		            <label class="layui-form-label">????????????</label>
		            <div class="layui-input-block">
		                <input type="text" name="address" class="layui-input"  placeholder="?????????????????????" value="<?php echo htmlentities((isset($row['address']) && ($row['address'] !== '')?$row['address']:'')); ?>">
		            </div>
		        </div>
		        <div class="layui-form-item">
		            <label class="layui-form-label">????????????</label>
		            <div class="layui-input-block">
		                <input type="text" name="bank" class="layui-input"  placeholder="?????????????????????" value="<?php echo htmlentities((isset($row['bank']) && ($row['bank'] !== '')?$row['bank']:'')); ?>">
		            </div>
		        </div>
			</div>
			<div class="layui-col-md6">
		        <div class="layui-form-item">
		            <label class="layui-form-label">????????????</label>
		            <div class="layui-input-block">
		                <input type="text" name="name" class="layui-input" lay-verify="required" placeholder="?????????????????????" value="<?php echo htmlentities((isset($row['name']) && ($row['name'] !== '')?$row['name']:'')); ?>">
		            </div>
		        </div>
		        <div class="layui-form-item">
		            <label class="layui-form-label">????????????</label>
		            <div class="layui-input-block">
		                <input type="text" name="simple_name" class="layui-input"  placeholder="?????????????????????" value="<?php echo htmlentities((isset($row['simple_name']) && ($row['simple_name'] !== '')?$row['simple_name']:'')); ?>">
		            </div>
		        </div>
				<div class="layui-form-item">
		            <label class="layui-form-label">??????????????????</label>
		            <div class="layui-input-block">
		                <input type="text" name="idnumber" class="layui-input"  placeholder="???????????????????????????" value="<?php echo htmlentities((isset($row['idnumber']) && ($row['idnumber'] !== '')?$row['idnumber']:'')); ?>">
		            </div>
		        </div>
		        <div class="layui-form-item">
		            <label class="layui-form-label">??????</label>
		            <div class="layui-input-block">
		                <input type="text" name="tel" class="layui-input"  placeholder="???????????????" value="<?php echo htmlentities((isset($row['tel']) && ($row['tel'] !== '')?$row['tel']:'')); ?>">
		            </div>
		        </div>
		        <div class="layui-form-item">
		            <label class="layui-form-label">????????????</label>
		            <div class="layui-input-block">
		                <input type="text" name="account" class="layui-input"  placeholder="?????????????????????" value="<?php echo htmlentities((isset($row['account']) && ($row['account'] !== '')?$row['account']:'')); ?>">
		            </div>
		        </div>
			</div>
		</div>
        <div class="layui-form-item">
            <label class="layui-form-label required">????????????</label>
            <div class="layui-input-block layuimini-upload">
                <input name="file" class="layui-input layui-col-xs6"   placeholder="?????????????????????" value="<?php echo htmlentities((isset($row['file']) && ($row['file'] !== '')?$row['file']:'')); ?>">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="file" data-upload-number="one" data-upload-exts="*" data-upload-icon="file"><i class="fa fa-upload"></i> ??????</a></span>
                    <span><a class="layui-btn layui-btn-normal" id="select_file" data-upload-select="file" data-upload-number="one" data-upload-mimetype="*"><i class="fa fa-list"></i> ??????</a></span>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">??????</label>
            <div class="layui-input-block">
                <input type="text" name="status" class="layui-input"  placeholder="???????????????" value="<?php echo htmlentities((isset($row['status']) && ($row['status'] !== '')?$row['status']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">?????????</label>
            <div class="layui-input-block">
                <input type="hidden" name="add_id" class="layui-input" readonly="true" style="background:#f1f1f1"  value="<?php echo session('admin.id'); ?>">
               	<input type="text" name="" class="layui-input" readonly="true" style="background:#f1f1f1"  value="<?php echo session('admin.username'); ?>">
            </div>
        </div>
        <div class="hr-line"></div>
        <div class="layui-form-item text-center">
            <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>??????</button>
            <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">??????</button>
        </div>

    </form>
</div>
</body>
</html>