<?php /*a:2:{s:58:"E:\wamp64\www\rzlh\app\mobile\view\railwayloading\add.html";i:1644887457;s:50:"E:\wamp64\www\rzlh\app\mobile\view\common\bot.html";i:1640934938;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<meta name="Keywords" content="">
<meta name="Description" content="">
<title>日照兰花业财一体化系统</title>
<link rel="stylesheet" type="text/css" href="/static/mobile/css/default.css?v=<?php echo time(); ?>" media="all">
<link rel="stylesheet" type="text/css" href="/static/mobile/css/swiper.min.css?v=<?php echo time(); ?>" media="all">
<link rel="stylesheet" type="text/css" href="/static/mobile/css/layer.css?v=<?php echo time(); ?>" >
<link rel="stylesheet" type="text/css" href="/static/plugs/layui-v2.5.6/css/layui.css?v=<?php echo time(); ?>" >
<script type="text/javascript" src="/static/mobile/js/rem.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/plugs/jquery-3.4.1/jquery-3.4.1.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/mobile/js/layer.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/mobile/js/swiper.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/plugs/layui-v2.5.6/layui.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/mobile/js/Mdate/iScroll.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="/static/mobile/js/Mdate/Mdate.js?v=<?php echo time(); ?>"></script>
<body>
<style>
.layui-form-select dl {
	max-height: 200px;
}
</style>
	<div class="head fc">
		<a href="javascript:window.history.go(-1);" class="back"><i class="layui-icon">&#xe603;</i></a>
		<?php echo htmlentities($title); ?>
		<a class="add" href="/Railwayloading/">入库管理</a>
	</div>
	<div class="railwayloading">
		<div class="tip">请上传铁路大票车号表pdf文档</div>
		<div class="up">
			<div class="layui-upload-drag" id="office">
				<i class="layui-icon"></i>
				<p>点击上传</p>
			</div>
			<div class="example" onclick="showimg()" style="display: none"></div>
		</div>
	</div>
	<div class="railwayloading">
		<div class="tip">请上传电子版车号表或pdf文档</div>
		<div class="up">
			<div class="layui-upload-drag" id="office1">
				<i class="layui-icon"></i>
				<p>点击上传</p>
			</div>
			<div class="example" onclick="showimg()" style="display: none"></div>
		</div>
	</div>
	<div class="form layui-form-pane">
		<form id="app-form" class="layui-form layuimini-form">
			<input type="hidden" name="cFile" value="">
			<input type="hidden" name="cFilename" value="">
			<input type="hidden" name="cFile2" value="">
			<div class="layui-form-item">
	            <label class="layui-form-label required">需求号</label>
	            <div class="layui-input-block">
	                <input type="text" name="ScPlan" placeholder="请上传铁路货运单" class="layui-input"  value="">
	            </div>
	        </div>
	        <div class="layui-form-item">
	            <label class="layui-form-label required">发运类型</label>
	            <div class="layui-input-block">
	            	<select name="iszc" lay-filter="cYsType_select">
	            		<option value="">请选择</option>
	            		<option value="1">直发</option>
	            		<option value="2">转存</option>
	            		<option value="3">仓库现货发运</option>
	            	</select>
	            </div>
	        </div>
	        <div class="layui-form-item" id="ku" style="display: none;">
	            <label class="layui-form-label">入库仓库</label>
	            <div class="layui-input-block">
	            	<select name="ScN">
	            		<option value="">请选择</option>
	            		<?php foreach($stocks as $k=>$v): ?>
	            		<option value="<?php echo htmlentities($v['CskTypeName']); ?>"><?php echo htmlentities($v['CskTypeName']); ?></option>
	            		<?php endforeach; ?>
	            	</select>
	            </div>
	        </div>
	        <div class="layui-form-item">
	            <label class="layui-form-label required">定车日期</label>
	            <div class="layui-input-block">
	                <input type="text" name="ScCityOwned" autocomplete="off" class="layui-input" data-year="<?php echo date('Y'); ?>" data-month="<?php echo date('m'); ?>" data-day="<?php echo date('d'); ?>" id="ScCityOwned" placeholder="" value="">
	            </div>
	        </div>
			<div class="layui-form-item">
	            <label class="layui-form-label required">装车日期</label>
	            <div class="layui-input-block">
	                <input type="text" name="ScDate" autocomplete="off" class="layui-input" data-year="<?php echo date('Y'); ?>" data-month="<?php echo date('m'); ?>" data-day="<?php echo date('d'); ?>" id="dateSelectorOne" placeholder="" value="<?php echo date('Y-m-d'); ?>">
	            </div>
	        </div>
	        <!-- <div class="layui-form-item">
	            <label class="layui-form-label required">矿(库)点</label>
	            <div class="layui-input-block">
	                <input type="text" name="scZhuangH" class="layui-input" placeholder="请上传铁路货运单" value="">
	            </div>
	        </div> -->
	        <div class="layui-form-item">
	            <label class="layui-form-label required">发站</label>
	            <div class="layui-input-block">
	                <input type="text" name="ScStarName" class="layui-input" placeholder="请上传铁路货运单" value="">
	            </div>
	        </div>
	        <div class="layui-form-item">
	            <label class="layui-form-label required">到站</label>
	            <div class="layui-input-block">
	                <input type="text" name="ScStopName" class="layui-input" placeholder="请上传铁路货运单" value="">
	            </div>
	        </div>
	        <div class="layui-form-item">
	            <label class="layui-form-label required">收货单位</label>
	            <div class="layui-input-block">
	                <input type="text" name="ScDepartment" class="layui-input" placeholder="请上传铁路货运单" value="">
	            </div>
	        </div>
	        <div class="layui-form-item">
	            <label class="layui-form-label required">品种</label>
	            <div class="layui-input-block">
	                <input type="text" name="ScCoalType" class="layui-input" placeholder="请上传铁路货运单" value="">
	            </div>
	        </div>
	        <div class="layui-form-item">
	            <label class="layui-form-label required">物流方式</label>
	            <div class="layui-input-block">
	                <input type="text" name="YsType" class="layui-input" placeholder="请上传铁路货运单" value="">
	            </div>
	        </div>
	        <div class="layui-form-item">
	            <label class="layui-form-label required">矿点</label>
	            <div class="layui-input-block">
	                <input type="text" name="scZhuangH" class="layui-input" placeholder="请输入矿点" value="">
	            </div>
	        </div>
	        <div style="overflow-x: auto;">
				<table class="layui-table" style="width:100%;text-align: center;margin: 0;table-layout: fixed;">
				    <thead>
				    	<tr>
				        	<th width="8%" style="text-align: center;">序号</th>
				        	<th width="28%" style="text-align: center;">需求号</th>
				        	<th width="10%" style="text-align: center;">车种</th>
				        	<th width="15%" style="text-align: center;">车号</th>
				        	<th width="10%" style="text-align: center;">起票吨数</th>
				        	<th width="10%" style="text-align: center;">实装吨数</th>
				        	<th width="10%" style="text-align: center;">操作</th>
				      	</tr> 
				    </thead>
		    		<tbody id="td">
		    			<tr>
		    				<td>1</td>
		    				<td><input type="hidden" name="sale[0][ScdID]" value=""><input class="layui-input layui-table-edit" name="sale[0][ScdDep]" value=""></td>
		    				<td><input class="layui-input layui-table-edit" name="sale[0][ScdCarType]" value=""></td>
		    				<td><input class="layui-input layui-table-edit" name="sale[0][ScdCarCode]" value=""></td>
		    				<td><input class="layui-input layui-table-edit" oninput="count1(this)" name="sale[0][ScdWeight]" value=""></td>
		    				<td><input class="layui-input layui-table-edit" oninput="count1(this)" name="sale[0][ScdWeight2]" value=""></td>
		    				<td><i class="layui-icon" onclick="add(this)">&#xe61f;</i></td>
		    			</tr>	
		    			<tr>
		    				<td><span style="color: red">合计</span></td>
		    				<td></td>
		    				<td></td>
		    				<td></td>
		    				<td><span style="color: red" id="TotalSou1"></span></td>
		    				<td><span style="color: red" id="TotalSou2"></span></td>
		    				<td onclick="del()"></td>
		    			</tr>
		      		</tbody>
		      	</table>
		    </div>
	        <div class="layui-form-item text-center" style="margin-top: 0.2rem;margin-bottom: 0;">
	            <button type="submit" class="layui-btn" lay-submit lay-filter="demo1">确认</button>
	        </div>
		</form>
	</div>
	<script>
	totalSouFund();
	function count1(obj){
		$(obj).val($(obj).val());
		$("#td").find("tr:not(:last) input").keyup(function(){
			totalSouFund();
		})
	}
	function totalSouFund() {
		totalSou1 = 0;
		totalSou2 = 0;
		$("#td tr:not(:last)").each(function () {
			$(this).find("td:eq(4) input").each(function () {
				totalSou1 += getNumValue($(this)) ;
				$("#TotalSou1").html(Number(totalSou1.toFixed(4)));
			});
			$(this).find("td:eq(5) input").each(function () {
				totalSou2 += getNumValue($(this)) ;
				$("#TotalSou2").html(Number(totalSou2.toFixed(4)));
			});
		})
	}
	function getNumValue(controlid) {
		var num = controlid.val();
		if (validateInput(num)) {
			num = parseFloat(num);
		}else {
			controlid.val("");
			num = 0;
		}
		return num;
	}
	function validateInput(inputstr) {
		flag = false;
		if (inputstr != "") {
			if (isNaN(inputstr)) {
		  		flag = false; //如果输入字符不是数字
			}else{
				if (parseFloat(inputstr) < 0){
					flag = false;
				}else{
		  			flag = true;
				}
		  	}
		}
		return flag;
	}
	function add(obj){
		var length = $("#td tr").length;
		$(obj).parent().html('<i class="layui-icon" onclick="del(this)">&#xe640;</i>');
		var i = parseInt($("#td tr").eq(length-2).find("td").eq(0).html())+1;
    	$("#td tr").eq(-2).after('<tr><td>'+i+'</td>'
				+'<td><input type="hidden" name="sale['+(i-1)+'][ScdID]" value=""><input class="layui-input layui-table-edit" name="sale['+(i-1)+'][ScdDep]" value=""></td>'
				+'<td><input class="layui-input layui-table-edit" name="sale['+(i-1)+'][ScdCarType]" value=""></td>'
				+'<td><input class="layui-input layui-table-edit" name="sale['+(i-1)+'][ScdCarCode]" value=""></td>'
				+'<td><input class="layui-input layui-table-edit" oninput="count1(this)" name="sale['+(i-1)+'][ScdWeight]" value=""></td>'
				+'<td><input class="layui-input layui-table-edit" oninput="count1(this)" name="sale['+(i-1)+'][ScdWeight2]" value=""></td>'
				+'<td><i class="layui-icon" onclick="add(this)">&#xe61f;</i></td></tr>')
		totalSouFund();
	}
	function del(obj){
		$(obj).parent().parent().remove();
		totalSouFund();
	}
	new Mdate("dateSelectorOne",{
		beginYear: "2002",
	    beginMonth: "10",
	    beginDay: "24",
		endYear: "2050",
	    endMonth: "12",
	    endDay: "31",
		format: "-"
	});
	new Mdate("ScCityOwned",{
		beginYear: "2002",
	    beginMonth: "10",
	    beginDay: "24",
		endYear: "2050",
	    endMonth: "12",
	    endDay: "31",
		format: "-"
	});
	
	layui.use(['form','upload'], function() {
	    var $ = layui.jquery;
	    var upload = layui.upload;
	    var form = layui.form;
	    var tishi;
	    form.on('select(cYsType_select)', function(data) {
            //发运类型，采购入库显示入库仓库
            if (data.value == "2") {
            	$("#ku").show();
            }
            else {
            	$("#ku").hide();
            }
        });
	    upload.render({
	        elem: '#office1',
	        url: '/RailwayLoading/detailupload',
	        accept: 'file', //普通文件
	        before: function(obj){
	        	tishi = layer.msg('文档识别中..', {
	        	    icon: 16
	        	    ,shade: 0.3
	        	    ,time: false
	        	});
	        },
	        done: function(res){
	        	layer.close(tishi);
	        	if(res.code==1){
	        		$("#office1").parent().find(".example").show();
		        	$("#office1").parent().find(".example").html('<img src="'+res.file+'">');
		        	$("#td tr").eq(-2).remove();
		        	$("#td tr").eq(-1).remove();
		        	if($("input[name='cFile2']").val()!=''){
		        		$("input[name='cFile2']").val($("input[name='cFile2']").val()+";"+res.file);
		        	}else{
		        		$("input[name='cFile2']").val(res.file);
		        	}
		        	
		        	var count1 = 0;
		        	var count2 = 0;
		        	var num = $("#td tr").length+1;
	        		layui.each(res.data, function(index, item){
		        		$("#td").append('<tr><td>'+num+'<input type="hidden" name="sale['+(item[0]-1)+'][ScdID]" value="'+num+'"></td>'
		        		+'<td><input class="layui-input layui-table-edit" name="sale['+(item[0]-1)+'][ScdDep]" value="'+item[1]+'"></td>'
		        		+'<td><input class="layui-input layui-table-edit" name="sale['+(item[0]-1)+'][ScdCarType]" value="'+item[2]+'"></td>'
		        		+'<td><input class="layui-input layui-table-edit" name="sale['+(item[0]-1)+'][ScdCarCode]" value="'+item[3]+'"></td>'
		        		+'<td><input class="layui-input layui-table-edit" name="sale['+(item[0]-1)+'][ScdWeight]" value="'+item[4]+'"></td>'
		        		+'<td><input class="layui-input layui-table-edit" name="sale['+(item[0]-1)+'][ScdWeight2]" value="'+item[5]+'"></td>'
		        		+'<td><i class="layui-icon" onclick="del(this)">&#xe640;</i></td></tr>');
		        		count1 = count1+parseFloat(item[4]);
		        		count2 = count1+parseFloat(item[5]);
		        		num = num+1;
		        	})
		        	$("#td").append('<tr><td>'+num+'<input type="hidden" name="sale['+num+'][ScdID]" value=""></td>'
		        		+'<td><input class="layui-input layui-table-edit" name="sale['+num+'][ScdDep]" value=""></td>'
		        		+'<td><input class="layui-input layui-table-edit" name="sale['+num+'][ScdCarType]" value=""></td>'
		        		+'<td><input class="layui-input layui-table-edit" name="sale['+num+'][ScdCarCode]" value=""></td>'
		        		+'<td><input class="layui-input layui-table-edit" name="sale['+num+'][ScdWeight]" value=""></td>'
		        		+'<td><input class="layui-input layui-table-edit" name="sale['+num+'][ScdWeight2]" value=""></td>'
		        		+'<td><i class="layui-icon" onclick="add(this)">&#xe61f;</i></td></tr>');
		        	$("#td").append('<tr>'
		    				+'<td><span style="color: red">合计</span></td>'
		    				+'<td></td>'
		    				+'<td></td>'
		    				+'<td></td>'
		    				+'<td><span style="color: red">'+count1+'</span></td>'
		    				+'<td><span style="color: red">'+count2+'</span></td>'
		    				+'<td></td></tr>');
	        	}else{
	        		layer.msg(res.msg);
	        	}
	        	
	        }
		});
	    upload.render({
	        elem: '#office',
	        url: '/RailwayLoading/upload',
	        accept: 'file', //普通文件
	        before: function(obj){
	        	tishi = layer.msg('文档识别中..', {
	        	    icon: 16
	        	    ,shade: 0.3
	        	    ,time: false
	        	});
	        },
	        done: function(res){
	        	layer.close(tishi);
	        	if(res.code==1){
	        		$("input[name='ScPlan']").val(res.data[0].words);
			        $("input[name='ScStarName']").val(res.data[1].words);
			        $("input[name='ScStopName']").val(res.data[2].words);
		        	$("input[name='ScDepartment']").val(res.data[3].words);
		        	$("input[name='ScCoalType']").val(res.data[4].words);
		        	$("input[name='YsType']").val("铁路");
		        	$("input[name='cFile']").val(res.file);
		        	$("#office").parent().find(".example").show();
		        	$("#office").parent().find(".example").html('<img src="'+res.file+'">');
		        	$("input[name='cFilename']").val(res.filename);
	        	}else{
	        		layer.msg(res.msg);
	        	}
	        	
	        },
	        error: function(res, index, upload){
	        	layer.close(tishi);
	        	layer.msg(res.msg);
	        }
		});
	    form.on('submit(demo1)', function(data){
	    	if(!$("input[name='cFile']").val()){
	    		layer.msg("请先上传铁路货运单！");
	    		return false;
	    	}
	    	if(!$("input[name='ScCityOwned']").val()){
	    		layer.msg("请选择定车日期！");
	    		return false;
	    	}
	    	
	    	if(!$("select[name='iszc']").val()){
	    		layer.msg("请选择发运类型！");
	    		return false;
	    	}
	    	if($("select[name='iszc']").val()=="2"){
	    		if(!$("select[name='ScN']").val()){
		    		layer.msg("请选择入库仓库！");
		    		return false;
		    	}
	    	}
	    	$.ajax({
                url:"/Railwayloading/add/",
                dataType:'json',
                type:'post',
                beforeSend: function(obj){
    	        	tishi = layer.msg('保存中..', {
    	        	    icon: 16
    	        	    ,shade: 0.3
    	        	    ,time: false
    	        	});
    	        },
                data: data.field,
                success:function(t){
                	if(t.code==1){
                		window.location = '/Railwayloading/';
                	}else{
                		layer.msg(t.msg);
                	}
                },
                error: function () {
                    layer.open({content:'网络繁忙,请重试',skin:'msg',time:2});
                }
            })
	        return false;
	    });
	})
	</script>
	<div style="margin-top:25%;float: left;width: 100%;"></div>
	<div id="user_indexbot">
		<?php if($menu==1): ?>
		<a href="/index/">
	        <img src="/static/mobile/pic/xbh1.png" alt="">
	        <p>待办</p>
	    </a>
		<?php else: ?>
	    <a href="/index/">
	        <img src="/static/mobile/pic/xb1.png" alt="">
	        <p>待办</p>
	    </a>
	    <?php endif; if($menu==2): ?>
		<a href="/Workbench/">
	        <img src="/static/mobile/pic/xbh2.png" alt="">
	        <p>工作台</p>
	    </a>
		<?php else: ?>
	    <a href="/Workbench/">
	        <img src="/static/mobile/pic/xb2.png" alt="">
	        <p>工作台</p>
	    </a>
	    <?php endif; if($menu==3): ?>
		<a href="/my/">
	        <img src="/static/mobile/pic/xbh3.png" alt="">
	        <p>我的</p>
	    </a>
		<?php else: ?>
	    <a href="/my/">
	        <img src="/static/mobile/pic/xb3.png" alt="">
	        <p>我的</p>
	    </a>
	    <?php endif; ?>
	    <div class="clear"></div>
	</div>
</div>

</body>
</html>