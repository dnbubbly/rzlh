define(["jquery", "easy-admin", "tableSelect", "vue"], function ($, ea, tableSelect, Vue) {

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
        detail_url: 'contract.info/detail',
        agree_url: 'contract.info/agree',
        sign_url: 'contract.info/sign',
        file_url: 'contract.info/file',
    };
    
    var init1 = {
            table_elem: '#currentTablefile',
            table_render_id: 'currentTablefileRenderId',
            index_url: 'contract.info/fileindex',
            addone_url: 'contract.info/addone',
            add_url: 'contract.info/add',
            edit_url: 'contract.info/edit',
            delete_url: 'contract.info/delete',
            export_url: 'contract.info/export',
            modify_url: 'contract.info/modify',
            detail_url: 'contract.info/detail',
            agree_url: 'contract.info/agree',
            sign_url: 'contract.info/sign',
            file_url: 'contract.info/file',
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
                    }]],
                cols: [[
                    {type: 'checkbox'},                    {field: 'id', width: 60, title: 'id', search: false},
                    {field: 'number', width: 180, title: '合同编号'},                    {field: 'contractType.name', fieldAlias: 'contractType.id', width: 120, title: '合同类型', search: 'select', selectList: getContractType},                    {field: 'hezuofang', minWidth: 300, title: '合作方'},
                    {field: 'zxq', width: 300, title: '执行期', search: false},                    {field: 'date', width: 200, title: '起草日期'},
                    {field: 'status', width: 80,  title: '状态', search: 'select', selectList: {0: '未生效', 1: '审核中',2: '已生效',3: '已退回'}},
                    {field: 'isfile', width: 120,  title: '是否归档', search: 'select', selectList: {0: '未归档', 1: '已归档'}},                    {field: 'systemAdmin.username', width: 80, title: '起草人', search: false},                    {
                    	width: 350,
                        title: '操作',
                        templet: ea.table.tool,
                        operat: [
                        	[{
	                            text: '编辑',
	                            url: init.edit_url,
	                            method: 'open',
	                            auth: 'edit',
	                            class: 'layui-btn layui-btn-xs layui-btn-success',
	                            extend: 'data-full="true"',
	                            render: function (d) { return d.status === 0||d.status === 3; }
	                        }, {
	                            text: '发起审批',
	                            url: init.agree_url,
	                            method: 'request',
	                            auth: 'agree',
	                            class: 'layui-btn layui-btn-xs layui-btn-warm',
	                            render: function (d) { return d.status === 0; }
	                        }, {
	                            text: '重新审批',
	                            url: init.agree_url,
	                            method: 'request',
	                            auth: 'agree',
	                            class: 'layui-btn layui-btn-xs layui-btn-warm',
	                            render: function (d) { return d.status === 3; }
	                        }, {
	                            text: '签署',
	                            url: init.sign_url,
	                            method: 'open',
	                            width: 1000,
                                height: 600,
	                            auth: 'sign',
	                            class: 'layui-btn layui-btn-xs',
	                            render: function (d) { return d.isour !== 1||d.isother !== 1; }
	                        }, {
	                            text: '归档',
	                            extra: '确定归档？',
	                            url: init.file_url,
	                            method: 'open',
	                            auth: 'file',
	                            class: 'layui-btn layui-btn-xs layui-btn-warm',
	                            render: function (d) { return d.status === 2&&d.isfile === 0&&d.isour === 1&&d.isother === 1; }
	                        }, {
	                            text: '删除',
	                            extra: '确定删除？',
	                            url: init.delete_url,
	                            method: 'request',
	                            auth: 'delete',
	                            class: 'layui-btn layui-btn-xs layui-btn-danger',
	                            render: function (d) { return d.status === 0&&d.isfile === 0; }
	                        }, {
	                            text: '查看',
	                            url: init.detail_url,
	                            method: 'open',
	                            auth: 'detail',
	                            class: 'layui-btn layui-btn-xs layui-btn-success',
	                            extend: 'data-full="true"',
	                        }]
                        ]
                    },
                ]],
            });

            ea.listen();
        },
        fileindex: function () {
            ea.table.render({
                init: init1,
                skin: 'row ',
                toolbar: ['refresh'],
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', width: 60, title: 'id', search: false},
                    {field: 'number', width: 180, title: '合同编号'},
                    {field: 'contractType.name', fieldAlias: 'contractType.id', width: 120, title: '合同类型', search: 'select', selectList: getContractType},
                    {field: 'hezuofang', minWidth: 300, title: '合作方'},
                    {field: 'zxq', width: 300, title: '执行期', search: false},
                    {field: 'date', width: 200, title: '起草日期'},
                    {field: 'status', width: 80,  title: '状态', search: 'select', selectList: {0: '未生效', 1: '审核中',2: '已生效',3: '已退回'}},
                    {field: 'isfile', width: 120,  title: '是否归档', search: 'select', selectList: {0: '未归档', 1: '已归档'}},
                    {field: 'systemAdmin.username', width: 80, title: '起草人', search: false},
                    {
                    	width: 150,
                        title: '操作',
                        templet: ea.table.tool,
                        operat: [
                        	[{
	                            text: '查看',
	                            url: init.detail_url,
	                            method: 'open',
	                            auth: 'detail',
	                            class: 'layui-btn layui-btn-xs layui-btn-success',
	                            extend: 'data-full="true"',
	                        }]
                        ]
                    },
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
            	window.location.href = "../"+init.add_url+'?cus_id='+res.data.cus_id+'&draft='+res.data.draft+'&lead='+res.data.lead;
            });
        },
        add: function () {
        	form.on('select(road)', function(data){
				if(data.value == 1){
					$("#rail1").find('select').attr('lay-verify','');
	        		$("#rail2").find('select').attr('lay-verify','');
					$("#rail1").hide();
	        		$("#rail2").hide();
	        		$("#hight1").show();
	        		$("#hight2").show();
	        		$("#hight1").find('.layui-form-label').addClass('required');
	        		$("#hight2").find('.layui-form-label').addClass('required');
	        		$("#hight1").find('select').attr('lay-verify','required');
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
        				+'<td><select id="coal'+i+'" name="coal['+(i-1)+'][type]" lay-search="" data-select="/contract.coaltype/index" data-fields="id,name" data-value=""></select></td>'
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
        		ea.open("质量奖惩","../contract.info/quality?index="+index,"80%","600px");
        	});
        	$("body").on("click", ".delt", function() {
        		$(this).parent().parent().remove();
        	});
        	tableSelect.render({
                elem: '#buyername',	//定义输入框input对象 必填
                checkedKey: 'id', //表格的唯一建值，非常重要，影响到选中状态 必填
                searchKey: 'name',	//搜索输入框的name值 默认keyword
                searchPlaceholder: '关键词搜索',	//搜索输入框的提示文字 默认关键词搜索
                height:'400',  //自定义高度
                width:'900',  //自定义宽度
                table: {	//定义表格参数，与LAYUI的TABLE模块一致，只是无需再定义表格elem
                    url:'../customer.info/reindex',
                    cols: [[
                    	{ type: 'radio' },
                    	{field: 'code', width: 100,  title: '编号'},
                        {field: 'name', title: '客户全称'},
                        {field: 'simple_name', width: 100, title: '简称', search: false},
                        {field: 'username', width: 120, title: '添加人'},
                    ]]
                },
                done: function (elem, data) {
                	var NEWJSON = []
                	var NEWJSON1 = []
                    layui.each(data.data, function (index, item) {
                        NEWJSON.push(item.name)
                        NEWJSON1.push(item.id)
                    })
                    elem.val(NEWJSON.join(","))
                    $("#buyerid").val(NEWJSON1.join(","));
                }
            })
            tableSelect.render({
                elem: '#sellername',	//定义输入框input对象 必填
                checkedKey: 'id', //表格的唯一建值，非常重要，影响到选中状态 必填
                searchKey: 'name',	//搜索输入框的name值 默认keyword
                searchPlaceholder: '关键词搜索',	//搜索输入框的提示文字 默认关键词搜索
                height:'400',  //自定义高度
                width:'900',  //自定义宽度
                table: {	//定义表格参数，与LAYUI的TABLE模块一致，只是无需再定义表格elem
                    url:'../customer.info/reindex',
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
                	var NEWJSON1 = []
                    layui.each(data.data, function (index, item) {
                        NEWJSON.push(item.name)
                        NEWJSON1.push(item.id)
                    })
                    elem.val(NEWJSON.join(","))
                    $("#sellerid").val(NEWJSON1.join(","));
                }
            })
            tableSelect.render({
                elem: '#receivingname',	//定义输入框input对象 必填
                checkedKey: 'id', //表格的唯一建值，非常重要，影响到选中状态 必填
                searchKey: 'name',	//搜索输入框的name值 默认keyword
                searchPlaceholder: '关键词搜索',	//搜索输入框的提示文字 默认关键词搜索
                height:'400',  //自定义高度
                width:'900',  //自定义宽度
                table: {	//定义表格参数，与LAYUI的TABLE模块一致，只是无需再定义表格elem
                    url:'../customer.info/reindex',
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
                	var NEWJSON1 = []
                    layui.each(data.data, function (index, item) {
                        NEWJSON.push(item.name)
                        NEWJSON1.push(item.id)
                    })
                    elem.val(NEWJSON.join(","))
                    $("#receivingid").val(NEWJSON1.join(","));
                }
            })
            tableSelect.render({
                elem: '#settlementname',	//定义输入框input对象 必填
                checkedKey: 'id', //表格的唯一建值，非常重要，影响到选中状态 必填
                searchKey: 'name',	//搜索输入框的name值 默认keyword
                searchPlaceholder: '关键词搜索',	//搜索输入框的提示文字 默认关键词搜索
                height:'400',  //自定义高度
                width:'900',  //自定义宽度
                table: {	//定义表格参数，与LAYUI的TABLE模块一致，只是无需再定义表格elem
                    url:'../customer.info/reindex',
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
                	var NEWJSON1 = []
                    layui.each(data.data, function (index, item) {
                        NEWJSON.push(item.name)
                        NEWJSON1.push(item.id)
                    })
                    elem.val(NEWJSON.join(","))
                    $("#settlementid").val(NEWJSON1.join(","));
                }
            })
            ea.listen();
        },
        edit: function () {
        	form.on('select(road)', function(data){
				if(data.value == 1){
					$("#rail1").find('select').attr('lay-verify','');
	        		$("#rail2").find('select').attr('lay-verify','');
					$("#rail1").hide();
	        		$("#rail2").hide();
	        		$("#hight1").show();
	        		$("#hight2").show();
	        		$("#hight1").find('.layui-form-label').addClass('required');
	        		$("#hight2").find('.layui-form-label').addClass('required');
	        		$("#hight1").find('select').attr('lay-verify','required');
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
        				+'<td><select id="coal'+i+'" name="coal['+(i-1)+'][type]" lay-search="" data-select="/contract.coaltype/index" data-fields="id,name" data-value=""></select></td>'
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
        		ea.open("质量奖惩","../contract.info/quality?index="+index,"80%","600px");
        	});
        	$("body").on("click", ".delt", function() {
        		$(this).parent().parent().remove();
        	});
        	tableSelect.render({
                elem: '#buyername',	//定义输入框input对象 必填
                checkedKey: 'id', //表格的唯一建值，非常重要，影响到选中状态 必填
                searchKey: 'name',	//搜索输入框的name值 默认keyword
                searchPlaceholder: '关键词搜索',	//搜索输入框的提示文字 默认关键词搜索
                height:'400',  //自定义高度
                width:'900',  //自定义宽度
                table: {	//定义表格参数，与LAYUI的TABLE模块一致，只是无需再定义表格elem
                    url:'../customer.info/reindex',
                    cols: [[
                    	{ type: 'radio' },
                    	{field: 'code', width: 100,  title: '编号'},
                        {field: 'name', title: '客户全称'},
                        {field: 'simple_name', width: 100, title: '简称', search: false},
                        {field: 'username', width: 120, title: '添加人'},
                    ]]
                },
                done: function (elem, data) {
                	var NEWJSON = []
                	var NEWJSON1 = []
                    layui.each(data.data, function (index, item) {
                        NEWJSON.push(item.name)
                        NEWJSON1.push(item.id)
                    })
                    elem.val(NEWJSON.join(","))
                    $("#buyerid").val(NEWJSON1.join(","));
                }
            })
            tableSelect.render({
                elem: '#sellername',	//定义输入框input对象 必填
                checkedKey: 'id', //表格的唯一建值，非常重要，影响到选中状态 必填
                searchKey: 'name',	//搜索输入框的name值 默认keyword
                searchPlaceholder: '关键词搜索',	//搜索输入框的提示文字 默认关键词搜索
                height:'400',  //自定义高度
                width:'900',  //自定义宽度
                table: {	//定义表格参数，与LAYUI的TABLE模块一致，只是无需再定义表格elem
                    url:'../customer.info/reindex',
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
                	var NEWJSON1 = []
                    layui.each(data.data, function (index, item) {
                        NEWJSON.push(item.name)
                        NEWJSON1.push(item.id)
                    })
                    elem.val(NEWJSON.join(","))
                    $("#sellerid").val(NEWJSON1.join(","));
                }
            })
            tableSelect.render({
                elem: '#receivingname',	//定义输入框input对象 必填
                checkedKey: 'id', //表格的唯一建值，非常重要，影响到选中状态 必填
                searchKey: 'name',	//搜索输入框的name值 默认keyword
                searchPlaceholder: '关键词搜索',	//搜索输入框的提示文字 默认关键词搜索
                height:'400',  //自定义高度
                width:'900',  //自定义宽度
                table: {	//定义表格参数，与LAYUI的TABLE模块一致，只是无需再定义表格elem
                    url:'../customer.info/reindex',
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
                	var NEWJSON1 = []
                    layui.each(data.data, function (index, item) {
                        NEWJSON.push(item.name)
                        NEWJSON1.push(item.id)
                    })
                    elem.val(NEWJSON.join(","))
                    $("#receivingid").val(NEWJSON1.join(","));
                }
            })
            tableSelect.render({
                elem: '#settlementname',	//定义输入框input对象 必填
                checkedKey: 'id', //表格的唯一建值，非常重要，影响到选中状态 必填
                searchKey: 'name',	//搜索输入框的name值 默认keyword
                searchPlaceholder: '关键词搜索',	//搜索输入框的提示文字 默认关键词搜索
                height:'400',  //自定义高度
                width:'900',  //自定义宽度
                table: {	//定义表格参数，与LAYUI的TABLE模块一致，只是无需再定义表格elem
                    url:'../customer.info/reindex',
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
                	var NEWJSON1 = []
                    layui.each(data.data, function (index, item) {
                        NEWJSON.push(item.name)
                        NEWJSON1.push(item.id)
                    })
                    elem.val(NEWJSON.join(","))
                    $("#settlementid").val(NEWJSON1.join(","));
                }
            })
            ea.listen();
        },
        detail: function(){
        	$("body").on("click", ".qualityt", function() {
        		var index = $(this).data('index');
        		ea.open("质量奖惩","../contract.info/qualitydetail?index="+index,"80%","600px");
        	});
        	ea.listen();
        },
        sign: function(){
        	ea.listen();
        },
        file: function(){
        	ea.listen();
        },
        quality: function () {
        	var s = $('input[name="coal['+index+'][json]"]',window.parent.document).val();
        	var app = new Vue({
                el: '#app',
                data: {
                    data: s!=''?JSON.parse(s):''
                },
                updated: function () {
            		layui.use('form', function () {
            	        layui.form.render('select');
            	    })
            	}
            });
        	
        	layui.form.on('select(qz1)', function (data) {
        		console.info($(this).parent().parent().parent().find('select').attr('model'));
        		var name = $(this).parent().parent().parent().find('select').attr('model');
        		var del = $(this).parent().parent().parent().find('select').attr('del');
        		app.data[name][del] = data.value;
            })
            
            layui.form.on('select(qz)', function (data) {
        		var name = $(this).parent().parent().parent().find('select').attr('model');
        		var del = $(this).parent().parent().parent().find('select').attr('del');
        		var index = $(this).parent().parent().parent().find('select').attr('index');
        		var key = $(this).parent().parent().parent().parent().parent().parent().index();
        		console.info( data.value);
        		app.data[name][del][key][index] = data.value;

        		console.info(app.data[name][del][key][index]);
            })
        	
        	$("body").on("click", ".addqt", function() {
        		$(this).parent().html('<i class="layui-icon delqt" style="font-size: 20px;line-height: 38px;"></i>');
        		var name = $(this).attr('model');
        		var del = $(this).attr('del');
        		console.info(del);
        		app.data[name][del].push(["", "＜", "≤", "", "每升高", "", "加价", ""]);
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
        qualitydetail: function () {
        	var s = $('input[name="coal['+index+'][json]"]',window.parent.document).val();
        	var app = new Vue({
                el: '#app',
                data: {
                    data: s!=''?JSON.parse(s):'',
                }
            });
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