define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'step.flowdetail/index',
        add_url: 'step.flowdetail/add',
        edit_url: 'step.flowdetail/edit',
        delete_url: 'step.flowdetail/delete',
        export_url: 'step.flowdetail/export',
        modify_url: 'step.flowdetail/modify',
        reback_url: 'step.flowdetail/reback',
        detail_url: 'step.flowdetail/detail',
        examine_url: 'step.flowdetail/examine',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                skin: 'row ',
                toolbar: ['refresh','export'],
                cols: [[
                    {type: 'checkbox'},                    {field: 'id', width: 60, title: 'id', search: false},                    {field: 'stepFlow.title', width: 300, title: '审批记录', search: false},
                    {field: 'type', title: '类型', width: 95, search: 'select', selectList: {1: '审批', 2: '抄送'}},
                    {field: 'current_step', width: 200, title: '当前审批步骤', search: false},                    {field: 'systemAdmin.username', width: 100, title: '审批人', search: false},
                    {field: 'status', title: '状态', width: 95, search: 'select', selectList: {0: '待办', 1: '已办'}},                    {field: 'replay', title: '审批意见', search: false},                    {field: 'date', title: '审批时间', search: false},
                    {
                    	width: 250,
                    	title: '操作', 
                    	templet: ea.table.tool,
                    	operat: [
                            [{
                                text: '通过',
                                url: init.examine_url,
                                method: 'open',
                                auth: 'examine',
                                class: 'layui-btn layui-btn-xs layui-btn-success',
                                extend: 'data-full="true"',
                                render: function (d) { return d.status === 0&&d.type === 1; }
                            }, {
                                text: '退回',
                                url: init.reback_url,
                                method: 'open',
                                auth: 'reback',
                                class: 'layui-btn layui-btn-xs layui-btn-warm',
                                extend: 'data-full="true"',
                                render: function (d) { return d.status === 0&&d.type === 1; }
                            }, {
                                text: '查看',
                                url: init.detail_url,
                                method: 'open',
                                auth: 'detail',
                                extend: 'data-full="true"',
                                class: 'layui-btn layui-btn-xs layui-btn-normal',
                            }],
                        ]
                    }
                ]],
            });

            ea.listen();
        },
        examine: function () {
        	var ifr = document.getElementById('iframepage')
        	var b = $('#app-form').width();
        	ifr.onload = function(){
        	    var wd = ifr.contentWindow.document.documentElement.scrollWidth;
        	    var ht = ifr.contentWindow.document.documentElement.scrollHeight;
        	    var x = b / wd;
        	    ifr.style.width = wd +'px';
        	    ifr.style.height = ht +'px';
        	    
        	    $('.box').css("height",ht*x + 'px');
        	 
        	}
            ea.listen();
        },
        reback: function () {
        	var ifr = document.getElementById('iframepage')
        	var b = $('#app-form').width();
        	ifr.onload = function(){
        	    var wd = ifr.contentWindow.document.documentElement.scrollWidth;
        	    var ht = ifr.contentWindow.document.documentElement.scrollHeight;
        	    var x = b / wd;
        	    ifr.style.width = wd +'px';
        	    ifr.style.height = ht +'px';
        	    
        	    $('.box').css("height",ht*x + 'px');
        	 
        	}
            ea.listen();
        },
        detail: function () {
        	var ifr = document.getElementById('iframepage')
        	var b = $('#app-form').width();
        	ifr.onload = function(){
        	    var wd = ifr.contentWindow.document.documentElement.scrollWidth;
        	    var ht = ifr.contentWindow.document.documentElement.scrollHeight;
        	    var x = b / wd;
        	    ifr.style.width = wd +'px';
        	    ifr.style.height = ht +'px';
        	    
        	    $('.box').css("height",ht*x + 'px');
        	 
        	}
            ea.listen();
        },
    };
    return Controller;
});