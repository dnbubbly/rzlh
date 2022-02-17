<?php /*a:2:{s:56:"E:\wamp64\www\rlzy\app\admin\view\contract\info\add.html";i:1645059161;s:53:"E:\wamp64\www\rlzy\app\admin\view\layout\default.html";i:1638342384;}*/ ?>
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
    <form id="app-form" class="layui-form layuimini-form">
        
        <div class="layui-form-item">
            <label class="layui-form-label">合同类型</label>
            <div class="layui-input-block">
                <input type="text" name="type" class="layui-input" lay-verify="required" placeholder="请输入合同类型" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">起草类型</label>
            <div class="layui-input-block">
                <?php foreach($getDraftList as $k=>$v): ?>
                <input type="radio" name="draft" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), explode(',',""))): ?>checked=""<?php endif; ?>>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">主导类型</label>
            <div class="layui-input-block">
                <?php foreach($getLeadList as $k=>$v): ?>
                <input type="radio" name="lead" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), explode(',',""))): ?>checked=""<?php endif; ?>>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">销方</label>
            <div class="layui-input-block">
                <input type="text" name="seller" class="layui-input" lay-verify="required" placeholder="请输入销方" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">购方</label>
            <div class="layui-input-block">
                <input type="text" name="buyer" class="layui-input" lay-verify="required" placeholder="请输入购方" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">合同编号</label>
            <div class="layui-input-block">
                <input type="text" name="number" class="layui-input" lay-verify="required" placeholder="请输入合同编号" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">签订地点</label>
            <div class="layui-input-block">
                <input type="text" name="address" class="layui-input" lay-verify="required" placeholder="请输入签订地点" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">起草日期</label>
            <div class="layui-input-block">
                <input type="text" name="date" data-date="" data-date-type="datetime" class="layui-input" lay-verify="required" placeholder="请输入起草日期" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">收货单位</label>
            <div class="layui-input-block">
                <input type="text" name="receiving" class="layui-input" lay-verify="required" placeholder="请输入收货单位" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">结算单位</label>
            <div class="layui-input-block">
                <input type="text" name="settlement" class="layui-input" lay-verify="required" placeholder="请输入结算单位" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">执行期起</label>
            <div class="layui-input-block">
                <input type="text" name="startdate" class="layui-input" lay-verify="required" placeholder="请输入执行期起" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">执行期止</label>
            <div class="layui-input-block">
                <input type="text" name="enddate" class="layui-input" lay-verify="required" placeholder="请输入执行期止" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">物流方式</label>
            <div class="layui-input-block">
                <input type="text" name="road" class="layui-input" lay-verify="required" placeholder="请输入物流方式" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发站</label>
            <div class="layui-input-block">
                <input type="text" name="start_station" class="layui-input" lay-verify="required" placeholder="请输入发站" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">到站</label>
            <div class="layui-input-block">
                <input type="text" name="end_station" class="layui-input" lay-verify="required" placeholder="请输入到站" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">交货方式</label>
            <div class="layui-input-block">
                <input type="text" name="delivery" class="layui-input" lay-verify="required" placeholder="请输入交货方式" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">交货地点</label>
            <div class="layui-input-block">
                <input type="text" name="delivery_address" class="layui-input" lay-verify="required" placeholder="请输入交货地点" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">质量验收</label>
            <div class="layui-input-block">
                <?php foreach($getCheckTypeList as $k=>$v): ?>
                <input type="radio" name="check_type" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), explode(',',""))): ?>checked=""<?php endif; ?>>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">发票</label>
            <div class="layui-input-block">
                <?php foreach($getTaxList as $k=>$v): ?>
                <input type="radio" name="tax" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), explode(',',""))): ?>checked=""<?php endif; ?>>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">运费</label>
            <div class="layui-input-block">
                <?php foreach($getFreightList as $k=>$v): ?>
                <input type="radio" name="freight" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), explode(',',""))): ?>checked=""<?php endif; ?>>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">承兑加价</label>
            <div class="layui-input-block">
                <?php foreach($getMarkupList as $k=>$v): ?>
                <input type="radio" name="markup" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), explode(',',""))): ?>checked=""<?php endif; ?>>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">承兑加价数量</label>
            <div class="layui-input-block">
                <input type="text" name="markupnum" class="layui-input" lay-verify="required" placeholder="请输入承兑加价数量" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">预付款比例</label>
            <div class="layui-input-block">
                <input type="text" name="advance" class="layui-input" lay-verify="required" placeholder="请输入预付款比例" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">预付款比例说明</label>
            <div class="layui-input-block">
                <input type="text" name="advance_remark" class="layui-input"  placeholder="请输入预付款比例说明" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">结算方式</label>
            <div class="layui-input-block">
                <?php foreach($getSettleList as $k=>$v): ?>
                <input type="radio" name="settle" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), explode(',',""))): ?>checked=""<?php endif; ?>>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">法律条款</label>
            <div class="layui-input-block">
                <textarea name="law" rows="20" class="layui-textarea editor" 法律条款 placeholder="请输入法律条款"></textarea>
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <textarea name="remark" class="layui-textarea"  placeholder="请输入备注"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">谈判纪要</label>
            <div class="layui-input-block layuimini-upload">
                <input name="file" class="layui-input layui-col-xs6" lay-verify="required"  placeholder="请上传谈判纪要" value="">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="file" data-upload-number="more" data-upload-exts="*" data-upload-icon="file"><i class="fa fa-upload" data-upload-sign=";"></i> 上传</a></span>
                    <span><a class="layui-btn layui-btn-normal" id="select_file" data-upload-select="file" data-upload-number="more" data-upload-mimetype="*" data-upload-sign=";"><i class="fa fa-list"></i> 选择</a></span>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">电子合同</label>
            <div class="layui-input-block layuimini-upload">
                <input name="elefile" class="layui-input layui-col-xs6"   placeholder="请上传电子合同" value="">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="elefile" data-upload-number="one" data-upload-exts="*" data-upload-icon="file"><i class="fa fa-upload"></i> 上传</a></span>
                    <span><a class="layui-btn layui-btn-normal" id="select_elefile" data-upload-select="elefile" data-upload-number="one" data-upload-mimetype="*"><i class="fa fa-list"></i> 选择</a></span>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">添加人</label>
            <div class="layui-input-block">
                <input type="text" name="add_id" class="layui-input"  placeholder="请输入添加人" value="">
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