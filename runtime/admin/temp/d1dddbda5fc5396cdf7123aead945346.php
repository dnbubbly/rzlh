<?php /*a:5:{s:58:"E:\wamp64\www\rzlh\app\admin\view\system\config\index.html";i:1645080665;s:53:"E:\wamp64\www\rzlh\app\admin\view\layout\default.html";i:1645080665;s:57:"E:\wamp64\www\rzlh\app\admin\view\system\config\site.html";i:1645080665;s:57:"E:\wamp64\www\rzlh\app\admin\view\system\config\logo.html";i:1645080665;s:59:"E:\wamp64\www\rzlh\app\admin\view\system\config\upload.html";i:1645080665;}*/ ?>
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
    <div class="layuimini-main" id="app">

        <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
            <ul class="layui-tab-title">
                <li class="layui-this">????????????</li>
                <li>LOGO??????</li>
                <li>????????????</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <form id="app-form" class="layui-form layuimini-form">

    <div class="layui-form-item">
        <label class="layui-form-label">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="site_name" class="layui-input" lay-verify="required" placeholder="?????????????????????" value="<?php echo sysconfig('site','site_name'); ?>">
            <tip>?????????????????????</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">???????????????</label>
        <div class="layui-input-block layuimini-upload">
            <input name="site_ico" class="layui-input layui-col-xs6" lay-verify="required" placeholder="????????????????????????,ico??????" value="<?php echo sysconfig('site','site_ico'); ?>">
            <div class="layuimini-upload-btn">
                <span><a class="layui-btn" data-upload="site_ico" data-upload-number="one" data-upload-exts="ico"><i class="fa fa-upload"></i> ??????</a></span>
                <span><a class="layui-btn layui-btn-normal" id="select_site_ico" data-upload-select="site_ico" data-upload-number="one"><i class="fa fa-list"></i> ??????</a></span>
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="site_version" class="layui-input" lay-verify="required" placeholder="?????????????????????" value="<?php echo sysconfig('site','site_version'); ?>">
            <tip>?????????????????????</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="site_beian" class="layui-input" lay-verify="required" placeholder="?????????????????????" value="<?php echo sysconfig('site','site_beian'); ?>">
            <tip>?????????????????????</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="site_copyright" class="layui-input" lay-verify="required" placeholder="?????????????????????" value="<?php echo sysconfig('site','site_copyright'); ?>">
            <tip>?????????????????????</tip>
        </div>
    </div>

    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="system.config/save" data-refresh="false">??????</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">??????</button>
    </div>

</form>
                </div>
                <div class="layui-tab-item">
                    <form id="app-form" class="layui-form layuimini-form">

    <div class="layui-form-item">
        <label class="layui-form-label">LOGO??????</label>
        <div class="layui-input-block">
            <input type="text" name="logo_title" class="layui-input" lay-verify="required" placeholder="?????????LOGO??????" value="<?php echo sysconfig('site','logo_title'); ?>">
            <tip>?????????????????????</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">LOGO??????</label>
        <div class="layui-input-block layuimini-upload">
            <input name="logo_image" class="layui-input layui-col-xs6" lay-verify="required" placeholder="?????????LOGO??????" value="<?php echo sysconfig('site','logo_image'); ?>">
            <div class="layuimini-upload-btn">
                <span><a class="layui-btn" data-upload="logo_image" data-upload-number="one" data-upload-exts="ico|png|jpg|jpeg"><i class="fa fa-upload"></i> ??????</a></span>
                <span><a class="layui-btn layui-btn-normal" id="select_logo_image" data-upload-select="logo_image" data-upload-number="one"><i class="fa fa-list"></i> ??????</a></span>
            </div>
        </div>
    </div>

    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="system.config/save" data-refresh="false">??????</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">??????</button>
    </div>

</form>
                </div>
                <div class="layui-tab-item">
                    <form id="app-form" class="layui-form layuimini-form">

    <div class="layui-form-item">
        <label class="layui-form-label required">????????????</label>
        <div class="layui-input-block">
            <?php foreach(['local'=>'????????????','alioss'=>'?????????oss','qnoss'=>'?????????oss','txcos'=>'?????????cos'] as $key=>$val): ?>
            <input type="radio" v-model="upload_type" name="upload_type" lay-filter="upload_type" value="<?php echo htmlentities($key); ?>" title="<?php echo htmlentities($val); ?>" <?php if($key==sysconfig('upload','upload_type')): ?>checked=""<?php endif; ?>>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label required">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="upload_allow_ext" class="layui-input" lay-verify="required" lay-reqtext="?????????????????????" placeholder="?????????????????????" value="<?php echo sysconfig('upload','upload_allow_ext'); ?>">
            <tip>???????????????????????????</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label required">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="upload_allow_size" class="layui-input" lay-verify="required" lay-reqtext="???????????????????????????" placeholder="???????????????????????????" value="<?php echo sysconfig('upload','upload_allow_size'); ?>">
            <tip>???????????????????????????</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'alioss'" v-cloak>
        <label class="layui-form-label required">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="alioss_access_key_id" class="layui-input" lay-verify="required" lay-reqtext="?????????????????????" placeholder="?????????????????????" value="<?php echo sysconfig('upload','alioss_access_key_id'); ?>">
            <tip>?????????FSGGshu64642THSk</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'alioss'" v-cloak>
        <label class="layui-form-label required">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="alioss_access_key_secret" class="layui-input" lay-verify="required" lay-reqtext="?????????????????????" placeholder="?????????????????????" value="<?php echo sysconfig('upload','alioss_access_key_secret'); ?>">
            <tip>?????????5fsfPReYKkFSGGshu64642THSkmTInaIm</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'alioss'" v-cloak>
        <label class="layui-form-label required">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="alioss_endpoint" class="layui-input" lay-verify="required" lay-reqtext="?????????????????????" placeholder="?????????????????????" value="<?php echo sysconfig('upload','alioss_endpoint'); ?>">
            <tip>?????????https://oss-cn-shenzhen.aliyuncs.com</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'alioss'" v-cloak>
        <label class="layui-form-label required">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="alioss_bucket" class="layui-input" lay-verify="required" lay-reqtext="?????????????????????" placeholder="?????????????????????" value="<?php echo sysconfig('upload','alioss_bucket'); ?>">
            <tip>?????????easy-admin</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'alioss'" v-cloak>
        <label class="layui-form-label required">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="alioss_domain" class="layui-input" lay-verify="required" lay-reqtext="?????????????????????" placeholder="?????????????????????" value="<?php echo sysconfig('upload','alioss_domain'); ?>">
            <tip>?????????easy-admin.oss-cn-shenzhen.aliyuncs.com</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'txcos'" v-cloak>
        <label class="layui-form-label required">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="txcos_secret_id" class="layui-input" lay-verify="required" lay-reqtext="?????????????????????" placeholder="?????????????????????" value="<?php echo sysconfig('upload','txcos_secret_id'); ?>">
            <tip>?????????AKIDta6OQCbALQGrCI6ngKwQffR3dfsfrwrfs</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'txcos'" v-cloak>
        <label class="layui-form-label required">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="txcos_secret_key" class="layui-input" lay-verify="required" lay-reqtext="?????????????????????" placeholder="?????????????????????" value="<?php echo sysconfig('upload','txcos_secret_key'); ?>">
            <tip>?????????VllEWYKtClAbpqfFdTqysXxGQM6dsfs</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'txcos'" v-cloak>
        <label class="layui-form-label required">???????????????</label>
        <div class="layui-input-block">
            <input type="text" name="txcos_region" class="layui-input" lay-verify="required" lay-reqtext="????????????????????????" placeholder="????????????????????????" value="<?php echo sysconfig('upload','txcos_region'); ?>">
            <tip>?????????ap-guangzhou</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'txcos'" v-cloak>
        <label class="layui-form-label required">???????????????</label>
        <div class="layui-input-block">
            <input type="text" name="tecos_bucket" class="layui-input" lay-verify="required" lay-reqtext="????????????????????????" placeholder="????????????????????????" value="<?php echo sysconfig('upload','tecos_bucket'); ?>">
            <tip>?????????easyadmin-1251997243</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'qnoss'" v-cloak>
        <label class="layui-form-label required">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="qnoss_access_key" class="layui-input" lay-verify="required" lay-reqtext="?????????????????????" placeholder="?????????????????????" value="<?php echo sysconfig('upload','qnoss_access_key'); ?>">
            <tip>?????????v-lV3tXev7yyfsfa1jRc6_8rFOhFYGQvvjsAQxdrB</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'qnoss'" v-cloak>
        <label class="layui-form-label required">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="qnoss_secret_key" class="layui-input" lay-verify="required" lay-reqtext="?????????????????????" placeholder="?????????????????????" value="<?php echo sysconfig('upload','qnoss_secret_key'); ?>">
            <tip>?????????XOhYRR9JNqxsWVEO-mHWB4193vfsfsQADuORaXzr</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'qnoss'" v-cloak>
        <label class="layui-form-label required">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="qnoss_bucket" class="layui-input" lay-verify="required" lay-reqtext="????????????????????????" placeholder="????????????????????????" value="<?php echo sysconfig('upload','qnoss_bucket'); ?>">
            <tip>?????????easyadmin</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'qnoss'" v-cloak>
        <label class="layui-form-label required">????????????</label>
        <div class="layui-input-block">
            <input type="text" name="qnoss_domain" class="layui-input" lay-verify="required" lay-reqtext="?????????????????????" placeholder="?????????????????????" value="<?php echo sysconfig('upload','qnoss_domain'); ?>">
            <tip>?????????http://q0xqzappp.bkt.clouddn.com</tip>
        </div>
    </div>

    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="system.config/save" data-refresh="false">??????</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">??????</button>
    </div>

</form>
<script>
    var upload_type = "<?php echo sysconfig('upload','upload_type'); ?>";
</script>
                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>