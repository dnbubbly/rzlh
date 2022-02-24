<?php /*a:2:{s:59:"E:\wamp64\www\rzlh\app\admin\view\contract\info\detail.html";i:1645521907;s:53:"E:\wamp64\www\rzlh\app\admin\view\layout\default.html";i:1645080665;}*/ ?>
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
        <input type="hidden" name="type" value="<?php echo htmlentities((isset($row['type']) && ($row['type'] !== '')?$row['type']:'')); ?>">
        <input type="hidden" name="draft" value="<?php echo htmlentities((isset($row['draft']) && ($row['draft'] !== '')?$row['draft']:'')); ?>">
        <input type="hidden" name="lead" value="<?php echo htmlentities((isset($row['lead']) && ($row['lead'] !== '')?$row['lead']:'')); ?>">
        <div class="layui-form-item">
            <label class="layui-form-label">销方</label>
            <div class="layui-input-block">
                <input type="text" name="sellername" class="layui-input" lay-verify="required" <?php if($row['type']==1): ?> readonly="true" <?php else: ?> id="sellername" <?php endif; ?> placeholder="请输入销方" value="<?php echo htmlentities((isset($seller) && ($seller !== '')?$seller:'')); ?>" ts-selected="<?php echo htmlentities($row['seller']); ?>">
            	<input type="hidden" name="seller" class="layui-input" <?php if($row['type']==1): ?> value="0" <?php else: ?> id="sellerid" <?php endif; ?> value="<?php echo htmlentities((isset($row['seller']) && ($row['seller'] !== '')?$row['seller']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">购方</label>
            <div class="layui-input-block">
                <input type="text" name="buyername" class="layui-input" lay-verify="required" <?php if(app('request')->param('cus_id')==2): ?> readonly="buyer" <?php else: ?> id="buyername" <?php endif; ?> placeholder="请输入购方" value="<?php echo htmlentities((isset($row['customerInfoBuyer']['name']) && ($row['customerInfoBuyer']['name'] !== '')?$row['customerInfoBuyer']['name']:'')); ?>" ts-selected="<?php echo htmlentities($row['buyer']); ?>">
            	<input type="hidden" name="buyer" class="layui-input" <?php if($row['type']==2): ?> value="0" <?php else: ?> id="buyerid" <?php endif; ?> value="<?php echo htmlentities((isset($row['buyer']) && ($row['buyer'] !== '')?$row['buyer']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
        	<div class="layui-inline">
	            <label class="layui-form-label">合同编号</label>
	            <div class="layui-input-inline">
	                <input type="text" name="number" class="layui-input" readonly="true" style="background:#f1f1f1" placeholder="合同编号系统生成" value="<?php echo htmlentities((isset($row['number']) && ($row['number'] !== '')?$row['number']:'')); ?>">
	            </div>
	        </div>
	        <div class="layui-inline">
	        	<label class="layui-form-label">签订地点</label>
	            <div class="layui-input-inline">
	            	<select name="address" lay-verify="required" disabled lay-search="" data-select="<?php echo url('contract.address/index'); ?>" data-fields="id,name" data-value="<?php echo htmlentities((isset($row['address']) && ($row['address'] !== '')?$row['address']:'')); ?>">
		        	</select>
	            </div>
	        </div>
	        <div class="layui-inline">
	        	<label class="layui-form-label">起草日期</label>
	            <div class="layui-input-inline">
	                <input type="text" name="date" readonly="true" autocomplete="off" class="layui-input" lay-verify="required" placeholder="请输入起草日期" value="<?php echo htmlentities((isset($row['date']) && ($row['date'] !== '')?$row['date']:'')); ?>">
	            </div>
	        </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
	            <label class="layui-form-label">收货单位</label>
	            <div class="layui-input-inline">
	                <input type="text" name="receivingname" readonly="true" class="layui-input" lay-verify="required" placeholder="请输入收货单位" value="<?php echo htmlentities((isset($row['customerInfoReceiving']['name']) && ($row['customerInfoReceiving']['name'] !== '')?$row['customerInfoReceiving']['name']:'')); ?>" id="receivingname" ts-selected="<?php echo htmlentities($row['receiving']); ?>">
	                <input type="hidden" name="receiving" class="layui-input" id="receivingid" value="<?php echo htmlentities((isset($row['receiving']) && ($row['receiving'] !== '')?$row['receiving']:'')); ?>">
	            </div>
	        </div>
	        <div class="layui-inline">
	        	<label class="layui-form-label">结算单位</label>
	            <div class="layui-input-inline">
	                <input type="text" name="settlementname" readonly="true" class="layui-input" lay-verify="required" placeholder="请输入结算单位" value="<?php echo htmlentities((isset($row['customerInfoSettlement']['name']) && ($row['customerInfoSettlement']['name'] !== '')?$row['customerInfoSettlement']['name']:'')); ?>" id="settlementname" ts-selected="<?php echo htmlentities($row['settlement']); ?>">
	            	<input type="hidden" name="settlement" class="layui-input" id="settlementid" value="<?php echo htmlentities((isset($row['settlement']) && ($row['settlement'] !== '')?$row['settlement']:'')); ?>">
	            </div>
	        </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
              <legend>一、数量及价格</legend>
        </fieldset>
        <div class="layui-form-item">
				<table class="layui-table" style="width:800px;margin: 0;table-layout: fixed;">
				    <thead>
				    	<tr>
				        	<th width="8%" style="text-align: center;">序号</th>
				        	<th width="28%" style="text-align: center;">品种规格</th>
				        	<th width="15%" style="text-align: center;">数量</th>
				        	<th width="15%" style="text-align: center;">价格</th>
				        	<th width="10%" style="text-align: center;">质量奖惩</th>
				      	</tr> 
				    </thead>
		    		<tbody id="td">
		    			<?php foreach($row->detail as $k=>$v): ?>
		    			<tr>
		    				<td style="text-align: center;"><?php echo htmlentities($k+1); ?></td>
		    				<td><select name="coal[<?php echo htmlentities($k); ?>][type]" lay-verify="required" lay-filter="road" lay-search="" data-select="<?php echo url('contract.coaltype/index'); ?>" data-fields="id,name" data-value="<?php echo htmlentities($v['type']); ?>"></select></td>
		    				<td><input class="layui-input" name="coal[<?php echo htmlentities($k); ?>][num]" value="<?php echo htmlentities($v['num']); ?>"></td>
		    				<td><input class="layui-input" name="coal[<?php echo htmlentities($k); ?>][price]" value="<?php echo htmlentities($v['price']); ?>"></td>
		    				<td style="text-align: center;"><i data-index="<?php echo htmlentities($k); ?>" class="layui-icon qualityt">&#xe659;</i><input type="hidden" name="coal[<?php echo htmlentities($k); ?>][json]" value="<?php echo htmlentities($v['json']); ?>"></td>
		    			</tr>
		    			<?php endforeach; ?>
		      		</tbody>
		      	</table>
		    </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
              <legend>二、执行期</legend>
        </fieldset>
        <div class="layui-form-item">
        	<div class="layui-inline">
	            <label class="layui-form-label">执行期起</label>
	            <div class="layui-input-inline">
	                <input type="text" name="startdate" readonly="true" autocomplete="off" class="layui-input" lay-verify="required" placeholder="请输入执行期起" value="<?php echo htmlentities((isset($row['startdate']) && ($row['startdate'] !== '')?$row['startdate']:'')); ?>">
	            </div>
	        </div>
	        <div class="layui-inline">
	            <label class="layui-form-label">执行期止</label>
	            <div class="layui-input-inline">
	                <input type="text" name="enddate" readonly="true" autocomplete="off" class="layui-input" lay-verify="required" placeholder="请输入执行期止" value="<?php echo htmlentities((isset($row['enddate']) && ($row['enddate'] !== '')?$row['enddate']:'')); ?>">
	            </div>
	        </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
              <legend>三、交（提）货方式</legend>
        </fieldset>
        <div class="layui-form-item">
        	<div class="layui-inline">
	            <label class="layui-form-label">物流方式</label>
	            <div class="layui-input-inline">
	            	<select name="road" lay-verify="required" disabled  lay-filter="road" lay-search="" data-select="<?php echo url('contract.ship/index'); ?>" data-fields="id,name" data-value="<?php echo htmlentities((isset($row['road']) && ($row['road'] !== '')?$row['road']:'')); ?>">
		        	</select>
	            </div>
	        </div>
	        <div class="layui-inline" id="rail1" <?php if($row['road']!=2): ?> style="display: none;" <?php endif; ?>>
	            <label class="layui-form-label">发站</label>
	            <div class="layui-input-inline">
	            	<select name="rstart_station" disabled lay-verify="required" lay-search="" data-select="<?php echo url('contract.railwaysite/index'); ?>" data-fields="id,name" data-value="<?php echo htmlentities((isset($row['start_station']) && ($row['start_station'] !== '')?$row['start_station']:'')); ?>">
		        	</select>
	            </div>
	        </div>
	        <div class="layui-inline" id="rail2" <?php if($row['road']!=2): ?> style="display: none;" <?php endif; ?>>
	            <label class="layui-form-label">到站</label>
	            <div class="layui-input-inline">
	            	<select name="rend_station" disabled lay-verify="required" lay-search="" data-select="<?php echo url('contract.railwaysite/index'); ?>" data-fields="id,name" data-value="<?php echo htmlentities((isset($row['end_station']) && ($row['end_station'] !== '')?$row['end_station']:'')); ?>">
		        	</select>
	            </div>
	        </div>
	        <div class="layui-inline" id="hight1" <?php if($row['road']!=1): ?> style="display: none;" <?php endif; ?>>
	            <label class="layui-form-label">始发地</label>
	            <div class="layui-input-inline">
	            	<select name="hstart_station" disabled lay-verify="" lay-search="" data-select="<?php echo url('contract.highwaysite/index'); ?>" data-fields="id,name" data-value="<?php echo htmlentities((isset($row['start_station']) && ($row['start_station'] !== '')?$row['start_station']:'')); ?>">
		        	</select>
	            </div>
	        </div>
	        <div class="layui-inline" id="hight2" <?php if($row['road']!=1): ?> style="display: none;" <?php endif; ?>>
	            <label class="layui-form-label">目的地</label>
	            <div class="layui-input-inline">
	            	<select name="hend_station" disabled lay-verify="" lay-search="" data-select="<?php echo url('contract.highwaysite/index'); ?>" data-fields="id,name" data-value="<?php echo htmlentities((isset($row['end_station']) && ($row['end_station'] !== '')?$row['end_station']:'')); ?>">
		        	</select>
	            </div>
	        </div>
        </div>
        <div class="layui-form-item">
        	<div class="layui-inline">
	            <label class="layui-form-label">交货方式</label>
	            <div class="layui-input-inline">
	            	<select name="delivery" disabled lay-verify="required" lay-search="" data-select="<?php echo url('contract.delivery/index'); ?>" data-fields="id,name" data-value="<?php echo htmlentities((isset($row['delivery']) && ($row['delivery'] !== '')?$row['delivery']:'')); ?>">
		        	</select>
	            </div>
	        </div>
	        <div class="layui-inline">
	            <label class="layui-form-label">交货地点</label>
	            <div class="layui-input-inline">
	            	<select name="delivery_address" disabled lay-verify="required" lay-search="" data-select="<?php echo url('contract.address/index'); ?>" data-fields="id,name" data-value="<?php echo htmlentities((isset($row['delivery_address']) && ($row['delivery_address'] !== '')?$row['delivery_address']:'')); ?>">
		        	</select>
	            </div>
	        </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
              <legend>四、数量和质量验收标准及方法</legend>
        </fieldset>
        <div class="layui-form-item">
        	<div class="layui-inline">
	            <label class="layui-form-label">数量验收</label>
	            <div class="layui-input-block">
	            	<select name="check_num" disabled lay-verify="required">
	                    <option value=''></option>
	                    <?php foreach($getCheckNumList as $k=>$v): ?>
	                    <option <?php if($row['check_num']==$k): ?> selected <?php endif; ?> value='<?php echo htmlentities($k); ?>' ><?php echo htmlentities($v); ?></option>
	                    <?php endforeach; ?>
	                </select>
	            </div>
	        </div>
        	<div class="layui-inline">
	            <label class="layui-form-label">质量验收</label>
	            <div class="layui-input-block">
	            	<select name="check_type" disabled lay-verify="required">
	                    <option value=''></option>
	                    <?php foreach($getCheckTypeList as $k=>$v): ?>
	                    <option <?php if($row['check_type']==$k): ?> selected <?php endif; ?> value='<?php echo htmlentities($k); ?>' ><?php echo htmlentities($v); ?></option>
	                    <?php endforeach; ?>
	                </select>
	            </div>
	        </div>
	        
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
              <legend>五、付款结算</legend>
        </fieldset>
        <div class="layui-form-item">
        	<div class="layui-inline">
	        	<label class="layui-form-label">发票</label>
	            <div class="layui-input-block">
	            	<select name="tax" disabled lay-verify="required">
	                    <option value=''></option>
	                    <?php foreach($getTaxList as $k=>$v): ?>
	                    <option <?php if($row['tax']==$k): ?> selected <?php endif; ?> value='<?php echo htmlentities($k); ?>' ><?php echo htmlentities($v); ?></option>
	                    <?php endforeach; ?>
	                </select>
	            </div>
	        </div>
	        <div class="layui-inline">
	        	<label class="layui-form-label">运费</label>
	            <div class="layui-input-block">
	            	<select name="freight" disabled lay-verify="required">
	                    <option value=''></option>
	                    <?php foreach($getFreightList as $k=>$v): ?>
	                    <option <?php if($row['freight']==$k): ?> selected <?php endif; ?> value='<?php echo htmlentities($k); ?>' ><?php echo htmlentities($v); ?></option>
	                    <?php endforeach; ?>
	                </select>
	            </div>
	        </div>
        </div>
        <div class="layui-form-item">
        	<div class="layui-inline">
	        	<label class="layui-form-label">承兑加价</label>
	            <div class="layui-input-block">
	            	<select name="markup" disabled lay-verify="required">
	                    <option value=''></option>
	                    <?php foreach($getMarkupList as $k=>$v): ?>
	                    <option <?php if($row['markup']==$k): ?> selected <?php endif; ?> value='<?php echo htmlentities($k); ?>' ><?php echo htmlentities($v); ?></option>
	                    <?php endforeach; ?>
	                </select>
	            </div>
	        </div>
	        <div class="layui-inline">
	        	<input type="text" name="markupnum" readonly="true" class="layui-input" placeholder="请输入承兑加价数量" value="<?php echo htmlentities((isset($row['markupnum']) && ($row['markupnum'] !== '')?$row['markupnum']:'')); ?>">
	        </div>
	    </div>
        <div class="layui-form-item">
            <div class="layui-inline">
	            <label class="layui-form-label">预付款比例</label>
	            <div class="layui-input-block">
	                <input type="text" name="advance" readonly="true" class="layui-input" lay-verify="required" placeholder="请输入预付款比例" value="<?php echo htmlentities((isset($row['advance']) && ($row['advance'] !== '')?$row['advance']:'')); ?>">
	            </div>
	        </div>
	        <div class="layui-inline">
	        	<label class="layui-form-label">预付款比例说明</label>
	            <div class="layui-input-block">
	                <input type="text" name="advance_remark" readonly="true" class="layui-input"  placeholder="请输入预付款比例说明" value="<?php echo htmlentities((isset($row['advance_remark']) && ($row['advance_remark'] !== '')?$row['advance_remark']:'')); ?>">
	            </div>
	        </div>
	        <div class="layui-inline">
	        	<label class="layui-form-label">结算方式</label>
	            <div class="layui-input-block">
	            	<select name="settle" disabled lay-verify="required">
	                    <option value=''></option>
	                    <?php foreach($getSettleList as $k=>$v): ?>
	                    <option <?php if($row['settle']==$k): ?> selected <?php endif; ?> value='<?php echo htmlentities($k); ?>' ><?php echo htmlentities($v); ?></option>
	                    <?php endforeach; ?>
	                </select>
	            </div>
	         </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
              <legend>六、法律条款</legend>
        </fieldset>
        <div class="layui-form-item">
        	<textarea name="law" rows="20" class="layui-textarea editor" readonly="true" placeholder="请输入底部内容"><?php echo html_entity_decode($row['law']); ?></textarea>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <textarea name="remark" class="layui-textarea" readonly="true" placeholder="请输入备注"><?php echo htmlentities((isset($row['remark']) && ($row['remark'] !== '')?$row['remark']:'')); ?></textarea>
            </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
              <legend>七、附件</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label required">谈判纪要</label>
            <div class="layui-input-block">
            	<input type="text" name="confile" readonly="true" class="layui-input" lay-verify="required" value="<?php echo htmlentities((isset($row['confile']) && ($row['confile'] !== '')?$row['confile']:'')); ?>">
      
            </div>
        </div>
        <?php if($row['lead']==2): ?>
        <div class="layui-form-item">
            <label class="layui-form-label required">电子合同</label>
            <div class="layui-input-block">
            	<input type="text" name="elefile" readonly="true" class="layui-input" lay-verify="required" value="<?php echo htmlentities((isset($row['confile']) && ($row['confile'] !== '')?$row['confile']:'')); ?>">
            </div>
        </div>
        <?php endif; ?>
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