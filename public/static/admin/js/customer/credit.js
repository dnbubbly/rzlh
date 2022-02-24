define(["jquery", "easy-admin"], function ($, ea) {
	
	var form = layui.form;
	
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'customer.credit/index',
        add_url: 'customer.credit/add',
        edit_url: 'customer.credit/edit',
        delete_url: 'customer.credit/delete',
        export_url: 'customer.credit/export',
        modify_url: 'customer.credit/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                skin: 'row ',
                cols: [[
                    {type: 'checkbox'},                    {field: 'id', width: 60, title: 'id', search: false},                    {field: 'customerInfo.name', title: '客户名称'},                    {field: 'grade', width: 120, title: '准入得分', search: false},                    {field: 'startdate', width: 150, title: '使用期限开始', search: false},                    {field: 'enddate', width: 150, title: '使用期限结束', search: false},                    {field: 'money', width: 120, title: '授信额度', search: false},                    {field: 'remain_money', width: 120, title: '剩余额度', search: false},                    {field: 'status', title: '状态', width: 120, templet: ea.table.switch},                    {field: 'systemAdmin.username', width: 120, title: '添加人'},                    {width: 250, title: '操作', templet: ea.table.tool},
                ]],
            });

            ea.listen();
        },
        add: function () {
        	form.on("select(cus)", function(data){
        		$.ajax({
	                url:"../customer.credit/score",
	                dataType:'json',
	                type:'post',
	                data:{'id':data.value},
	                success:function(t){
	                	if(t.data!=null){
	                		$("input[name='grade']").val(t.data.total);	
	                	}else{
	                		$("input[name='grade']").val("暂无准入评分");	
	                	}
	                    
	                },
	                error: function () {
	                    layer.open({content:'网络繁忙,请重试',skin:'msg',time:2});
	                }
	            })
        	})
            ea.listen();
        },
        edit: function () {
        	form.on("select(cus)", function(data){
        		$.ajax({
	                url:"/customer.credit/score",
	                dataType:'json',
	                type:'post',
	                data:{'id':data.value},
	                success:function(t){
	                	if(t.data!=null){
	                		$("input[name='grade']").val(t.data.total);	
	                	}else{
	                		$("input[name='grade']").val("暂无准入评分");	
	                	}
	                    
	                },
	                error: function () {
	                    layer.open({content:'网络繁忙,请重试',skin:'msg',time:2});
	                }
	            })
        	})
            ea.listen();
        },
    };
    return Controller;
});