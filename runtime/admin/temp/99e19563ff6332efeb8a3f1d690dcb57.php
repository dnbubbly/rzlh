<?php /*a:2:{s:60:"E:\wamp64\www\rzlh\app\admin\view\contract\info\quality.html";i:1645433102;s:53:"E:\wamp64\www\rzlh\app\admin\view\layout\default.html";i:1645080665;}*/ ?>
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
    <form id="app" class="layui-form layuimini-form layui-form-pane">
    	<div class="layui-tab layui-tab-card">
  			<ul class="layui-tab-title">
  				<?php foreach($qtype as $k=>$v): ?>
    			<li <?php if($k==0): ?> class="layui-this" <?php endif; ?>><?php echo htmlentities($v['name']); ?></li>
			    <?php endforeach; ?>
  			</ul>
  			
  			<div class="layui-tab-content" style="min-height: 100px;">
  				<?php foreach($qtype as $k=>$v): ?>
    			<div v-if="data" class="layui-tab-item <?php if($k==0): ?>layui-show<?php endif; ?>">
    				<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
			        	<legend>执行标准</legend>
			        </fieldset>
			        <div class="layui-form-item">
			        	<div class="layui-col-lg3 layui-col-md3 layui-col-sm3">
							<span class="layui-form-label"><?php echo htmlentities($v['name']); ?></span>
								<div class="layui-input-block">
									<select name="qz[<?php echo htmlentities($v['name']); ?>][qz1]" lay-search="" v-model="data.<?php echo htmlentities($v['name']); ?>.qz1">
										<option value=""></option>
										<?php foreach($v['unit'] as $u): ?>
										<option  value="<?php echo htmlentities($u['unit']); ?>"><?php echo htmlentities($u['unit']); ?></option>
										<?php endforeach; ?>
									</select>
                   				</div>					
						</div>
						<div class="layui-col-lg3 layui-col-md3 layui-col-sm3" style="left: -2px;">
			    			<span class="layui-form-label">执行标准</span>
							<div class="layui-input-block">
								<input name="qz[<?php echo htmlentities($v['name']); ?>][qz2]" type="text" class="layui-input" v-model="data.<?php echo htmlentities($v['name']); ?>.qz2">
							</div>					
						</div>
					</div>
					<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				        <legend>质量奖惩</legend>
				    </fieldset>
    				<div class="layui-form-item" v-for="(value, key) in data.<?php echo htmlentities($v['name']); ?>.qz">
    					<div class="layui-col-space15 qline">
							<div class="layui-col-lg2 layui-col-md2 layui-col-sm2">
					    		<div class="layui-col-lg6 layui-col-md6 layui-col-sm6"><input name="qz[<?php echo htmlentities($v['name']); ?>][qz3][]" type="text" class="layui-input" v-model="value[0]"></div>
								<div class="layui-col-lg6 layui-col-md6 layui-col-sm6" style="left: -1px">
									<select name="qz[<?php echo htmlentities($v['name']); ?>][qz4][]" lay-verify="required" lay-search="" v-model="value[1]">
										<option value=""></option>
										<option value="＜" selected = "selected" >＜</option>
										<option value="≤">≤</option>
									</select>
	                     		</div>
	                     			
							</div>
							<div style="text-align: center;line-height:38px;float: left">实际值</div>
							<div class="layui-col-lg2 layui-col-md2 layui-col-sm2">
					        	<div class="layui-col-lg6 layui-col-md6 layui-col-sm6">
									<select name="qz[<?php echo htmlentities($v['name']); ?>][qz5][]" lay-verify="required" lay-search="" v-model="value[2]">
										<option value=""></option>
										<option value="＜">＜</option>
										<option value="≤" selected = "selected" >≤</option>
									</select>
	                        	</div>
	                        	<div class="layui-col-lg6 layui-col-md6 layui-col-sm6"><input name="qz[<?php echo htmlentities($v['name']); ?>][qz6][]" type="text" class="layui-input" v-model="value[3]"></div>
							</div>
							<div class="layui-col-lg2 layui-col-md2 layui-col-sm2">
								<div class="layui-col-lg6 layui-col-md6 layui-col-sm6">
									<select name="qz[<?php echo htmlentities($v['name']); ?>][qz7][]" lay-verify="required" lay-search="" v-model="value[4]">
										<option value=""></option>
										<option value="每升高" selected = "selected" >每升高</option>
										<option value="每降低">每降低</option>
									</select>
	                        	</div>
	                        	<div class="layui-col-lg6 layui-col-md6 layui-col-sm6"><input name="qz[<?php echo htmlentities($v['name']); ?>][qz8][]" type="text" class="layui-input" v-model="value[5]"></div>				
							</div>
							<div class="layui-col-lg2 layui-col-md2 layui-col-sm2">
								<div class="layui-col-lg6 layui-col-md6 layui-col-sm6">
									<select name="qz[<?php echo htmlentities($v['name']); ?>][qz9][]" lay-verify="required" lay-search="" v-model="value[6]">
										<option value=""></option>
										<option value="加价" selected = "selected" >加价</option>
										<option value="减价">减价</option>
									</select>
	                        	</div>
	                        	<div class="layui-col-lg6 layui-col-md6 layui-col-sm6"><input name="qz[<?php echo htmlentities($v['name']); ?>][qz10][]" type="text" class="layui-input" v-model="value[7]"></div>					
							</div>
							<div class="layui-col-lg1 layui-col-md1 layui-col-sm1" style="text-align: center;">
								<i v-if="key !== data.<?php echo htmlentities($v['name']); ?>.qz.length-1" class="layui-icon delqt" style="font-size: 20px;line-height: 38px;"></i>
					    		<i v-else class="layui-icon addqt" style="font-size: 20px; color: #5FB878;line-height: 38px"></i>
				    		</div>
			    		</div>
					</div>
    			</div>
    			<div v-else class="layui-tab-item <?php if($k==0): ?>layui-show<?php endif; ?>">
    				<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
			        	<legend>执行标准</legend>
			        </fieldset>
			        <div class="layui-form-item">
			        	<div class="layui-col-lg3 layui-col-md3 layui-col-sm3">
							<span class="layui-form-label"><?php echo htmlentities($v['name']); ?></span>
								<div class="layui-input-block">
									<select name="qz[<?php echo htmlentities($v['name']); ?>][qz1]" lay-search="">
										<option value=""></option>
										<?php foreach($v['unit'] as $u): ?>
										<option  value="<?php echo htmlentities($u['unit']); ?>"><?php echo htmlentities($u['unit']); ?></option>
										<?php endforeach; ?>
									</select>
                   				</div>					
						</div>
						<div class="layui-col-lg3 layui-col-md3 layui-col-sm3" style="left: -2px;">
			    			<span class="layui-form-label">执行标准</span>
							<div class="layui-input-block">
								<input name="qz[<?php echo htmlentities($v['name']); ?>][qz2]" type="text" class="layui-input">
							</div>					
						</div>
					</div>
					<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				        <legend>质量奖惩</legend>
				    </fieldset>
    				<div class="layui-form-item">
    					<div class="layui-col-space15 qline">
							<div class="layui-col-lg2 layui-col-md2 layui-col-sm2">
					    		<div class="layui-col-lg6 layui-col-md6 layui-col-sm6"><input name="qz[<?php echo htmlentities($v['name']); ?>][qz3][]" type="text" class="layui-input"></div>
								<div class="layui-col-lg6 layui-col-md6 layui-col-sm6" style="left: -1px">
									<select name="qz[<?php echo htmlentities($v['name']); ?>][qz4][]" lay-verify="required" lay-search="">
										<option value=""></option>
										<option value="＜" selected = "selected" >＜</option>
										<option value="≤">≤</option>
									</select>
	                     		</div>
	                     			
							</div>
							<div style="text-align: center;line-height:38px;float: left">实际值</div>
							<div class="layui-col-lg2 layui-col-md2 layui-col-sm2">
					        	<div class="layui-col-lg6 layui-col-md6 layui-col-sm6">
									<select name="qz[<?php echo htmlentities($v['name']); ?>][qz5][]" lay-verify="required" lay-search="">
										<option value=""></option>
										<option value="＜">＜</option>
										<option value="≤" selected = "selected" >≤</option>
									</select>
	                        	</div>
	                        	<div class="layui-col-lg6 layui-col-md6 layui-col-sm6"><input name="qz[<?php echo htmlentities($v['name']); ?>][qz6][]" type="text" class="layui-input"></div>
							</div>
							<div class="layui-col-lg2 layui-col-md2 layui-col-sm2">
								<div class="layui-col-lg6 layui-col-md6 layui-col-sm6">
									<select name="qz[<?php echo htmlentities($v['name']); ?>][qz7][]" lay-verify="required" lay-search="">
										<option value=""></option>
										<option value="每升高" selected = "selected" >每升高</option>
										<option value="每降低">每降低</option>
									</select>
	                        	</div>
	                        	<div class="layui-col-lg6 layui-col-md6 layui-col-sm6"><input name="qz[<?php echo htmlentities($v['name']); ?>][qz8][]" type="text" class="layui-input"></div>				
							</div>
							<div class="layui-col-lg2 layui-col-md2 layui-col-sm2">
								<div class="layui-col-lg6 layui-col-md6 layui-col-sm6">
									<select name="qz[<?php echo htmlentities($v['name']); ?>][qz9][]" lay-verify="required" lay-search="">
										<option value=""></option>
										<option value="加价" selected = "selected" >加价</option>
										<option value="减价">减价</option>
									</select>
	                        	</div>
	                        	<div class="layui-col-lg6 layui-col-md6 layui-col-sm6"><input name="qz[<?php echo htmlentities($v['name']); ?>][qz10][]" type="text" class="layui-input"></div>					
							</div>
							<div class="layui-col-lg1 layui-col-md1 layui-col-sm1" style="text-align: center;">
					    		<i class="layui-icon addqt" style="font-size: 20px; color: #5FB878;line-height: 38px"></i>
				    		</div>
			    		</div>
					</div>
    			</div>
    			<?php endforeach; ?>
  			</div>
		</div>
		<div class="hr-line"></div>
        <div class="layui-form-item text-center">
            <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>确认</button>
            <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
        </div>
    </form>
</div>
<script>
var index = <?php echo htmlentities($index); ?>;
</script>
</body>
</html>