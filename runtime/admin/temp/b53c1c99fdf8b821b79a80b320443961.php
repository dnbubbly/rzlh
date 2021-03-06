<?php /*a:2:{s:56:"E:\wamp64\www\rzlh\app\admin\view\contract\info\add.html";i:1645458760;s:53:"E:\wamp64\www\rzlh\app\admin\view\layout\default.html";i:1645080665;}*/ ?>
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
        <input type="hidden" name="type" value="<?php echo htmlentities(app('request')->param('cus_id')); ?>">
        <input type="hidden" name="draft" value="<?php echo htmlentities(app('request')->param('draft')); ?>">
        <input type="hidden" name="lead" value="<?php echo htmlentities(app('request')->param('lead')); ?>">
        <div class="layui-form-item">
            <label class="layui-form-label">??????</label>
            <div class="layui-input-block">
                <input type="text" name="sellername" class="layui-input" lay-verify="required" <?php if(app('request')->param('cus_id')==1): ?> readonly="true" <?php else: ?> id="sellername" <?php endif; ?> placeholder="???????????????" value="<?php echo htmlentities((isset($seller) && ($seller !== '')?$seller:'')); ?>">
            	<input type="hidden" name="seller" class="layui-input" <?php if(app('request')->param('cus_id')==1): ?> value="0" <?php else: ?> id="sellerid" <?php endif; ?>>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">??????</label>
            <div class="layui-input-block">
                <input type="text" name="buyername" class="layui-input" lay-verify="required" <?php if(app('request')->param('cus_id')==2): ?> readonly="buyer" <?php else: ?> id="buyername" <?php endif; ?> placeholder="???????????????" value="<?php echo htmlentities((isset($buyer) && ($buyer !== '')?$buyer:'')); ?>">
            	<input type="hidden" name="buyer" class="layui-input" <?php if(app('request')->param('cus_id')==2): ?> value="0" <?php else: ?> id="buyerid" <?php endif; ?>>
            </div>
        </div>
        <div class="layui-form-item">
        	<div class="layui-inline">
	            <label class="layui-form-label">????????????</label>
	            <div class="layui-input-inline">
	                <input type="text" name="number" class="layui-input" readonly="true" style="background:#f1f1f1" placeholder="????????????????????????" value="<?php echo htmlentities((isset($code) && ($code !== '')?$code:'')); ?>">
	            </div>
	        </div>
	        <div class="layui-inline">
	        	<label class="layui-form-label">????????????</label>
	            <div class="layui-input-inline">
	            	<select name="address" lay-verify="required" lay-search="" data-select="<?php echo url('contract.address/index'); ?>" data-fields="id,name" data-value="">
		        	</select>
	            </div>
	        </div>
	        <div class="layui-inline">
	        	<label class="layui-form-label">????????????</label>
	            <div class="layui-input-inline">
	                <input type="text" name="date" data-date="" data-date-type="date" autocomplete="off" class="layui-input" lay-verify="required" placeholder="?????????????????????" value="<?php echo date('Y-m-d'); ?>">
	            </div>
	        </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
	            <label class="layui-form-label">????????????</label>
	            <div class="layui-input-inline">
	                <input type="text" name="receivingname" class="layui-input" lay-verify="required" placeholder="?????????????????????" value="" id="receivingname">
	                <input type="hidden" name="receiving" class="layui-input" id="receivingid">
	            </div>
	        </div>
	        <div class="layui-inline">
	        	<label class="layui-form-label">????????????</label>
	            <div class="layui-input-inline">
	                <input type="text" name="settlementname" class="layui-input" lay-verify="required" placeholder="?????????????????????" value="" id="settlementname">
	            	<input type="hidden" name="settlement" class="layui-input" id="settlementid">
	            </div>
	        </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
              <legend>?????????????????????</legend>
        </fieldset>
        <div class="layui-form-item">
				<table class="layui-table" style="width:800px;margin: 0;table-layout: fixed;">
				    <thead>
				    	<tr>
				        	<th width="8%" style="text-align: center;">??????</th>
				        	<th width="28%" style="text-align: center;">????????????</th>
				        	<th width="15%" style="text-align: center;">??????</th>
				        	<th width="15%" style="text-align: center;">??????</th>
				        	<th width="10%" style="text-align: center;">??????</th>
				        	<th width="10%" style="text-align: center;">????????????</th>
				      	</tr> 
				    </thead>
		    		<tbody id="td">
		    			<tr>
		    				<td style="text-align: center;">1</td>
		    				<td><select name="coal[0][type]" lay-filter="road" lay-search="" data-select="<?php echo url('contract.coaltype/index'); ?>" data-fields="id,name" data-value=""></select></td>
		    				<td><input class="layui-input" name="coal[0][num]" value=""></td>
		    				<td><input class="layui-input" name="coal[0][price]" value=""></td>
		    				<td style="text-align: center;"><i class="layui-icon addt">&#xe61f;</i></td>
		    				<td style="text-align: center;"><i data-index="0" class="layui-icon qualityt">&#xe659;</i><input type="hidden" name="coal[0][json]" value=""></td>
		    			</tr>
		      		</tbody>
		      	</table>
		    </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
              <legend>???????????????</legend>
        </fieldset>
        <div class="layui-form-item">
        	<div class="layui-inline">
	            <label class="layui-form-label">????????????</label>
	            <div class="layui-input-inline">
	                <input type="text" name="startdate" data-date="" data-date-type="date" autocomplete="off" class="layui-input" lay-verify="required" placeholder="?????????????????????" value="">
	            </div>
	        </div>
	        <div class="layui-inline">
	            <label class="layui-form-label">????????????</label>
	            <div class="layui-input-inline">
	                <input type="text" name="enddate" data-date="" data-date-type="date" autocomplete="off" class="layui-input" lay-verify="required" placeholder="?????????????????????" value="">
	            </div>
	        </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
              <legend>???????????????????????????</legend>
        </fieldset>
        <div class="layui-form-item">
        	<div class="layui-inline">
	            <label class="layui-form-label">????????????</label>
	            <div class="layui-input-inline">
	            	<select name="road" lay-verify="required" lay-filter="road" lay-search="" data-select="<?php echo url('contract.ship/index'); ?>" data-fields="id,name" data-value="2">
		        	</select>
	            </div>
	        </div>
	        <div class="layui-inline" id="rail1">
	            <label class="layui-form-label">??????</label>
	            <div class="layui-input-inline">
	            	<select name="rstart_station" lay-verify="required" lay-search="" data-select="<?php echo url('contract.railwaysite/index'); ?>" data-fields="id,name" data-value="">
		        	</select>
	            </div>
	        </div>
	        <div class="layui-inline" id="rail2">
	            <label class="layui-form-label">??????</label>
	            <div class="layui-input-inline">
	            	<select name="rend_station" lay-verify="required" lay-search="" data-select="<?php echo url('contract.railwaysite/index'); ?>" data-fields="id,name" data-value="">
		        	</select>
	            </div>
	        </div>
	        <div class="layui-inline" id="hight1" style="display: none;">
	            <label class="layui-form-label">?????????</label>
	            <div class="layui-input-inline">
	            	<select name="hstart_station" lay-verify="" lay-search="" data-select="<?php echo url('contract.highwaysite/index'); ?>" data-fields="id,name" data-value="">
		        	</select>
	            </div>
	        </div>
	        <div class="layui-inline" id="hight2" style="display: none;">
	            <label class="layui-form-label">?????????</label>
	            <div class="layui-input-inline">
	            	<select name="hend_station" lay-verify="" lay-search="" data-select="<?php echo url('contract.highwaysite/index'); ?>" data-fields="id,name" data-value="">
		        	</select>
	            </div>
	        </div>
        </div>
        <div class="layui-form-item">
        	<div class="layui-inline">
	            <label class="layui-form-label">????????????</label>
	            <div class="layui-input-inline">
	            	<select name="delivery" lay-verify="required" lay-search="" data-select="<?php echo url('contract.delivery/index'); ?>" data-fields="id,name" data-value="">
		        	</select>
	            </div>
	        </div>
	        <div class="layui-inline">
	            <label class="layui-form-label">????????????</label>
	            <div class="layui-input-inline">
	            	<select name="delivery_address" lay-verify="required" lay-search="" data-select="<?php echo url('contract.address/index'); ?>" data-fields="id,name" data-value="">
		        	</select>
	            </div>
	        </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
              <legend>??????????????????????????????????????????</legend>
        </fieldset>
        <div class="layui-form-item">
        	<div class="layui-inline">
	            <label class="layui-form-label">????????????</label>
	            <div class="layui-input-block">
	            	<select name="check_num" lay-verify="required">
	                    <option value=''></option>
	                    <?php foreach($getCheckNumList as $k=>$v): ?>
	                    <option value='<?php echo htmlentities($k); ?>' ><?php echo htmlentities($v); ?></option>
	                    <?php endforeach; ?>
	                </select>
	            </div>
	        </div>
        	<div class="layui-inline">
	            <label class="layui-form-label">????????????</label>
	            <div class="layui-input-block">
	            	<select name="check_type" lay-verify="required">
	                    <option value=''></option>
	                    <?php foreach($getCheckTypeList as $k=>$v): ?>
	                    <option value='<?php echo htmlentities($k); ?>' ><?php echo htmlentities($v); ?></option>
	                    <?php endforeach; ?>
	                </select>
	            </div>
	        </div>
	        
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
              <legend>??????????????????</legend>
        </fieldset>
        <div class="layui-form-item">
        	<div class="layui-inline">
	        	<label class="layui-form-label">??????</label>
	            <div class="layui-input-block">
	            	<select name="tax" lay-verify="required">
	                    <option value=''></option>
	                    <?php foreach($getTaxList as $k=>$v): ?>
	                    <option value='<?php echo htmlentities($k); ?>' ><?php echo htmlentities($v); ?></option>
	                    <?php endforeach; ?>
	                </select>
	            </div>
	        </div>
	        <div class="layui-inline">
	        	<label class="layui-form-label">??????</label>
	            <div class="layui-input-block">
	            	<select name="freight" lay-verify="required">
	                    <option value=''></option>
	                    <?php foreach($getFreightList as $k=>$v): ?>
	                    <option value='<?php echo htmlentities($k); ?>' ><?php echo htmlentities($v); ?></option>
	                    <?php endforeach; ?>
	                </select>
	            </div>
	        </div>
        </div>
        <div class="layui-form-item">
        	<div class="layui-inline">
	        	<label class="layui-form-label">????????????</label>
	            <div class="layui-input-block">
	            	<select name="markup" lay-verify="required">
	                    <option value=''></option>
	                    <?php foreach($getMarkupList as $k=>$v): ?>
	                    <option value='<?php echo htmlentities($k); ?>' ><?php echo htmlentities($v); ?></option>
	                    <?php endforeach; ?>
	                </select>
	            </div>
	        </div>
	        <div class="layui-inline">
	        	<input type="text" name="markupnum" class="layui-input" placeholder="???????????????????????????" value="">
	        </div>
	    </div>
        <div class="layui-form-item">
            <div class="layui-inline">
	            <label class="layui-form-label">???????????????</label>
	            <div class="layui-input-block">
	                <input type="text" name="advance" class="layui-input" lay-verify="required" placeholder="????????????????????????" value="">
	            </div>
	        </div>
	        <div class="layui-inline">
	        	<label class="layui-form-label">?????????????????????</label>
	            <div class="layui-input-block">
	                <input type="text" name="advance_remark" class="layui-input"  placeholder="??????????????????????????????" value="">
	            </div>
	        </div>
	        <div class="layui-inline">
	        	<label class="layui-form-label">????????????</label>
	            <div class="layui-input-block">
	            	<select name="settle" lay-verify="required">
	                    <option value=''></option>
	                    <?php foreach($getSettleList as $k=>$v): ?>
	                    <option value='<?php echo htmlentities($k); ?>' ><?php echo htmlentities($v); ?></option>
	                    <?php endforeach; ?>
	                </select>
	            </div>
	         </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
              <legend>??????????????????</legend>
        </fieldset>
        <div class="layui-form-item">
            <textarea name="law" rows="20" class="layui-textarea editor" ???????????? placeholder="?????????????????????"></textarea>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">??????</label>
            <div class="layui-input-block">
                <textarea name="remark" class="layui-textarea"  placeholder="???????????????"></textarea>
            </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
              <legend>????????????</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label required">????????????</label>
            <div class="layui-input-block layuimini-upload">
                <input name="confile" class="layui-input layui-col-xs6" lay-verify="required"  placeholder="?????????????????????" value="" style="width: calc(100% - 85px);">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="confile" data-upload-number="more" data-upload-exts="pdf|rar|doc|docx|xls|xlsx|txt" data-upload-icon="file"><i class="fa fa-upload" data-upload-sign=";"></i> ??????</a></span>
                </div>
            </div>
        </div>
        <?php if(app('request')->param('lead')==2): ?>
        <div class="layui-form-item">
            <label class="layui-form-label required">????????????</label>
            <div class="layui-input-block layuimini-upload">
                <input name="elefile" class="layui-input layui-col-xs6" lay-verify="required"  placeholder="?????????????????????" value="" style="width: calc(100% - 85px);">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="elefile" data-upload-number="more" data-upload-exts="pdf|rar|doc|docx|xls|xlsx|txt" data-upload-icon="file"><i class="fa fa-upload" data-upload-sign=";"></i> ??????</a></span>
                </div>
            </div>
        </div>
        <?php endif; ?>
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