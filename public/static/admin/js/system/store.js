define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'system.store/index',
        add_url: 'system.store/add',
        edit_url: 'system.store/edit',
        delete_url: 'system.store/delete',
        agree_url: 'system.store/agree',
        export_url: 'system.store/export',
        modify_url: 'system.store/modify',
        detail_url: 'system.store/detail',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                skin: 'row ',
                cols: [[
                    {type: 'checkbox'},                    {field: 'id', width: 60, title: 'id', search: false},                    {field: 'name', width: 280, title: '仓库全称'},                    {field: 'number', width: 100, title: '仓库编号'},                    {field: 'address', width: 180, title: '仓库地址'},                    {field: 'contacts', width: 100, title: '联系人'},                    {field: 'tel', width: 120, title: '联系人电话'},                    {field: 'create_time', title: '创建时间', search: false},
                    {field: 'status', title: '状态', width: 95, search: 'select', selectList: {0: '未生效', 1: '审核中',2: '已生效'}},                    {field: 'systemAdmin.username', width: 120, title: '添加人'},                    {
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
	                            render: function (d) { return d.status === 0; }
	                        }, {
	                            text: '发起审批',
	                            url: init.agree_url,
	                            method: 'request',
	                            auth: 'agree',
	                            class: 'layui-btn layui-btn-xs layui-btn-warm',
	                            render: function (d) { return d.status === 0; }
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