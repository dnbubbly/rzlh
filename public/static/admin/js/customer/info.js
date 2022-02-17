define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'customer.info/index',
        add_url: 'customer.info/add',
        edit_url: 'customer.info/edit',
        delete_url: 'customer.info/delete',
        export_url: 'customer.info/export',
        modify_url: 'customer.info/modify',
        detail_url: 'customer.info/detail',
        agree_url: 'customer.info/agree',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                skin: 'row ',
                cols: [[
                    {type: 'checkbox'},
                    {field: 'systemAdmin.username', width: 120, title: '添加人'},
                    	width: 250,
                        title: '操作',
                        templet: ea.table.tool,
                        operat: [
                        	[{
	                            text: '编辑',
	                            url: init.edit_url,
	                            method: 'open',
	                            auth: 'edit',
	                            class: 'layui-btn layui-btn-xs layui-btn-success',
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
	                            text: '删除',
	                            extra: '确定删除？',
	                            url: init.delete_url,
	                            method: 'request',
	                            auth: 'delete',
	                            class: 'layui-btn layui-btn-xs layui-btn-danger',
	                            render: function (d) { return d.status === 0; }
	                        }, {
	                            text: '查看',
	                            url: init.detail_url,
	                            method: 'open',
	                            auth: 'detail',
	                            class: 'layui-btn layui-btn-xs layui-btn-success',
	                        }]
                        ]
                    },
                ]],
            });

            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
    };
    return Controller;
});