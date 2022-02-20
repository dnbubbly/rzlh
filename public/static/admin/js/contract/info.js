define(["jquery", "easy-admin", "tableSelect"], function ($, ea, tableSelect) {

	var tableSelect = layui.tableSelect;
	var form = layui.form;
	
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'contract.info/index',
        addone_url: 'contract.info/addone',
        add_url: 'contract.info/add',
        edit_url: 'contract.info/edit',
        delete_url: 'contract.info/delete',
        export_url: 'contract.info/export',
        modify_url: 'contract.info/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                skin: 'row ',
                toolbar: ['refresh',
                    [{
                        text: '添加',
                        url: init.addone_url,
                        method: 'open',
                        auth: 'add',
                        width: 500,
                        height: 323,
                        class: 'layui-btn layui-btn-normal layui-btn-sm',
                        icon: 'fa fa-plus ',
                        extend: 'data-full="true"',
                    }],
                    'delete'],
                cols: [[
                    {type: 'checkbox'},                    {field: 'id', title: 'id'},                    {field: 'type', title: '合同类型'},                    {field: 'draft', search: 'select', selectList: {"1":"新增合同","2":"变更合同"}, title: '起草类型'},                    {field: 'lead', search: 'select', selectList: {"1":"我方主导","2":"对方主导"}, title: '主导类型'},                    {field: 'seller', title: '销方'},                    {field: 'buyer', title: '购方'},                    {field: 'number', title: '合同编号'},                    {field: 'address', title: '签订地点'},                    {field: 'date', title: '起草日期'},                    {field: 'receiving', title: '收货单位'},                    {field: 'settlement', title: '结算单位'},                    {field: 'startdate', title: '执行期起'},                    {field: 'enddate', title: '执行期止'},                    {field: 'road', title: '物流方式'},                    {field: 'start_station', title: '发站'},                    {field: 'end_station', title: '到站'},                    {field: 'delivery', title: '交货方式'},                    {field: 'delivery_address', title: '交货地点'},                    {field: 'check_type', search: 'select', selectList: {"1":"以到厂后收货单位采、制、化验结果为准","2":"以第三方检测机构化验结果为准","3":"以购方化验结果为准","4":"以销方化验结果为准"}, title: '质量验收'},                    {field: 'tax', search: 'select', selectList: {"1":"不含税","2":"含 13%增值税发票"}, title: '发票'},                    {field: 'freight', search: 'select', selectList: {"1":"单价包含运费","2":"单价不包含运费，购方自提","3":"单价不包含运费，销方办理运输，费用由购方承担"}, title: '运费'},                    {field: 'markup', search: 'select', selectList: {"1":"不加价","2":"6月以内承兑加价","3":"6月以上承兑加价"}, title: '承兑加价'},                    {field: 'markupnum', title: '承兑加价数量'},                    {field: 'advance', title: '预付款比例'},                    {field: 'advance_remark', title: '预付款比例说明'},                    {field: 'settle', search: 'select', selectList: {"1":"一票结算","2":"二票结算"}, title: '结算方式'},                    {field: 'remark', title: '备注', templet: ea.table.text},                    {field: 'elefile', title: '电子合同', templet: ea.table.url},                    {field: 'create_time', title: '创建时间'},                    {field: 'add_id', title: '添加人'},                    {width: 250, title: '操作', templet: ea.table.tool},
                ]],
            });

            ea.listen();
        },
        addone: function () {
        	$('input:radio[name=draft]')[0].checked = true;
        	$('input:radio[name=lead]')[0].checked = true;
        	layui.form.render();
            ea.listen(function (data) {
                return data;
            }, function (res) {
            	window.location.href = "/"+init.add_url+'?cus_id='+res.data.cus_id+'&draft='+res.data.draft+'&lead='+res.data.lead;
            });
        },
        add: function () {
        	form.on('select(road)', function(data){
				if(data.value == 1){
					$("#rail1").hide();
					$("#rail1").find('select').attr('lay-verify','');
	        		$("#rail2").hide();
	        		$("#rail2").find('select').attr('lay-verify','');
	        		$("#hight1").show();
	        		$("#rail2").find('select').attr('lay-verify','required');
	        		$("#hight2").show();
	        		$("#hight2").find('select').attr('lay-verify','required');
					form.render('select');
				}else if(data.value == 2){
					$("#rail1").show();
	        		$("#rail2").show();
	        		$("#rail1").find('select').attr('lay-verify','required');
	        		$("#rail2").find('select').attr('lay-verify','required');
	        		$("#hight1").hide();
	        		$("#hight2").hide();
	        		$("#hight1").find('select').attr('lay-verify','');
	        		$("#hight2").find('select').attr('lay-verify','');
					form.render('select');//select是固定写法 不是选择器
				}
			});
        	$("body").on("click", ".addt", function() {
        		var length = $("#td tr").length;
        		$(this).parent().html('<i class="layui-icon delt">&#xe640;</i>');
        		var i = parseInt($("#td tr").eq(length-1).find("td").eq(0).html())+1;
            	$("#td tr").eq(-1).after('<tr><td style="text-align: center;">'+i+'</td>'
        				+'<td><select id="coal'+i+'" name="coal['+(i-1)+'][type]" lay-verify="required" lay-search="" data-select="/contract.coaltype/index" data-fields="id,name" data-value=""></select></td>'
        				+'<td><input class="layui-input" name="coal['+(i-1)+'][num]" value=""></td>'
        				+'<td><input class="layui-input" name="coal['+(i-1)+'][price]" value=""></td>'
        				+'<td style="text-align: center;"><i class="layui-icon addt">&#xe61f;</i></td>'
        				+'<td style="text-align: center;"><i data-index="'+(i-1)+'" class="layui-icon qualityt">&#xe659;</i><input type="hidden" name="coal['+(i-1)+'][json]" value=""></td></tr>')

        		var url = $("#coal"+i).attr('data-select'),
                selectFields = $("#coal"+i).attr('data-fields'),
                value = $("#coal"+i).attr('data-value'),
                that = "#coal"+i,
                html = '<option value=""></option>';
                    var fields = selectFields.replace(/\s/g, "").split(',');
                    if (fields.length !== 2) {
                        return admin.msg.error('下拉选择字段有误');
                    }
                    ea.request.get(
                        {
                            url: url,
                            data: {
                                selectFields: selectFields
                            },
                        }, function (res) {
                            var list = res.data;
                            list.forEach(val => {
                                var key = val[fields[0]];
                                if (value !== undefined && key.toString() === value) {
                                    html += '<option value="' + key + '" selected="">' + val[fields[1]] + '</option>';
                                } else {
                                    html += '<option value="' + key + '">' + val[fields[1]] + '</option>';
                                }
                            });
                            $(that).html(html);
                            form.render();
                        }
                    );
            });
        	$("body").on("click", ".qualityt", function() {
        		var index = $(this).data('index');
        		ea.open("质量奖惩","/contract.info/quality?index="+index,"80%","600px");
        	});
        	$("body").on("click", ".delt", function() {
        		$(this).parent().parent().remove();
        	});
        	tableSelect.render({
                elem: '#buyer',	//定义输入框input对象 必填
                checkedKey: 'id', //表格的唯一建值，非常重要，影响到选中状态 必填
                searchKey: 'name',	//搜索输入框的name值 默认keyword
                searchPlaceholder: '关键词搜索',	//搜索输入框的提示文字 默认关键词搜索
                height:'400',  //自定义高度
                width:'900',  //自定义宽度
                table: {	//定义表格参数，与LAYUI的TABLE模块一致，只是无需再定义表格elem
                    url:'/customer.info/index',
                    cols: [[
                    	{ type: 'radio' },
                    	{field: 'code', width: 100,  title: '编号'},
                        {field: 'name', title: '客户全称'},
                        {field: 'simple_name', width: 100, title: '简称', search: false},
                        {field: 'systemAdmin.username', width: 120, title: '添加人'},
                    ]]
                },
                done: function (elem, data) {
                	var NEWJSON = []
                    layui.each(data.data, function (index, item) {
                        NEWJSON.push(item.name)
                    })
                    elem.val(NEWJSON.join(","))
                }
            })
            tableSelect.render({
                elem: '#seller',	//定义输入框input对象 必填
                checkedKey: 'id', //表格的唯一建值，非常重要，影响到选中状态 必填
                searchKey: 'name',	//搜索输入框的name值 默认keyword
                searchPlaceholder: '关键词搜索',	//搜索输入框的提示文字 默认关键词搜索
                height:'400',  //自定义高度
                width:'900',  //自定义宽度
                table: {	//定义表格参数，与LAYUI的TABLE模块一致，只是无需再定义表格elem
                    url:'/customer.info/index',
                    cols: [[
                    	{ type: 'radio' },
                    	{field: 'code', width: 100,  title: '编号'},
                        {field: 'name', title: '客户全称'},
                        {field: 'simple_name', width: 100, title: '简称', search: false},
                        {field: 'systemAdmin.username', width: 120, title: '添加人'},
                    ]]
                },
                done: function (elem, data) {
                	var NEWJSON = []
                    layui.each(data.data, function (index, item) {
                        NEWJSON.push(item.name)
                    })
                    elem.val(NEWJSON.join(","))
                }
            })
            tableSelect.render({
                elem: '#receiving',	//定义输入框input对象 必填
                checkedKey: 'id', //表格的唯一建值，非常重要，影响到选中状态 必填
                searchKey: 'name',	//搜索输入框的name值 默认keyword
                searchPlaceholder: '关键词搜索',	//搜索输入框的提示文字 默认关键词搜索
                height:'400',  //自定义高度
                width:'900',  //自定义宽度
                table: {	//定义表格参数，与LAYUI的TABLE模块一致，只是无需再定义表格elem
                    url:'/customer.info/index',
                    cols: [[
                    	{ type: 'radio' },
                    	{field: 'code', width: 100,  title: '编号'},
                        {field: 'name', title: '客户全称'},
                        {field: 'simple_name', width: 100, title: '简称', search: false},
                        {field: 'systemAdmin.username', width: 120, title: '添加人'},
                    ]]
                },
                done: function (elem, data) {
                	var NEWJSON = []
                    layui.each(data.data, function (index, item) {
                        NEWJSON.push(item.name)
                    })
                    elem.val(NEWJSON.join(","))
                }
            })
            tableSelect.render({
                elem: '#settlement',	//定义输入框input对象 必填
                checkedKey: 'id', //表格的唯一建值，非常重要，影响到选中状态 必填
                searchKey: 'name',	//搜索输入框的name值 默认keyword
                searchPlaceholder: '关键词搜索',	//搜索输入框的提示文字 默认关键词搜索
                height:'400',  //自定义高度
                width:'900',  //自定义宽度
                table: {	//定义表格参数，与LAYUI的TABLE模块一致，只是无需再定义表格elem
                    url:'/customer.info/index',
                    cols: [[
                    	{ type: 'radio' },
                    	{field: 'code', width: 100,  title: '编号'},
                        {field: 'name', title: '客户全称'},
                        {field: 'simple_name', width: 100, title: '简称', search: false},
                        {field: 'systemAdmin.username', width: 120, title: '添加人'},
                    ]]
                },
                done: function (elem, data) {
                	var NEWJSON = []
                    layui.each(data.data, function (index, item) {
                        NEWJSON.push(item.name)
                    })
                    elem.val(NEWJSON.join(","))
                }
            })
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
        quality: function () {
        	$("body").on("click", ".addqt", function() {
        		var html = $(this).parent().parent().parent().prop("outerHTML");
        		$(this).parent().parent().parent().after(html);
        		$(this).parent().html('<i class="layui-icon delqt" style="font-size: 20px;line-height: 38px;"></i>');

            	form.render('select');
        	})
        	$("body").on("click", ".delqt", function() {
        		$(this).parent().parent().parent().remove();
        	})
        	ea.listen(function (data) {
                return data;
            }, function (res) {
            	var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
                $('input[name="coal['+res.data.index+'][json]"]',window.parent.document).val(JSON.stringify(res.data.data));
            });
        },
    };
    return Controller;
});