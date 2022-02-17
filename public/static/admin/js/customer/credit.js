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
                    {type: 'checkbox'},
                ]],
            });

            ea.listen();
        },
        add: function () {
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