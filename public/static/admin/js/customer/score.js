define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'customer.score/index',
        add_url: 'customer.score/add',
        edit_url: 'customer.score/edit',
        delete_url: 'customer.score/delete',
        export_url: 'customer.score/export',
        modify_url: 'customer.score/modify',
    };
    var inputSelect = layui.inputSelect;
    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                skin: 'row ',
                toolbar: ['refresh',
                    [{
                       text: '添加',
                       url: init.add_url,
                       method: 'open',
                       auth: 'add',
                       class: 'layui-btn layui-btn-normal layui-btn-sm',
                       icon: 'fa fa-plus ',
                       extend: 'data-full="true"',
                     }],
                    'delete', 'export'],
                cols: [[
                    {type: 'checkbox'},                    {field: 'id', width: 80, title: 'id', search: false},                    {field: 'customerInfo.name', title: '客户名称', search: false},                    {field: 'date', width: 280, title: '评分日期', search: 'range', searchOp: 'range'},                    {field: 'total', width: 200, title: '总分', search: false},                    {field: 'status', width: 120, title: '状态', search: 'select', selectList: {0: '禁用', 1: '启用'}, templet: ea.table.switch},                    {width: 250, title: '操作', templet: ea.table.tool,
                    	operat: [
                    		[{
                                text: '编辑',
                                extra:'name',
                                url: init.edit_url,
                                method: 'open',
                                auth: 'edit',
                                class: 'layui-btn layui-btn-xs layui-btn-success',
                                extend: 'data-full="true"',
                            }],
                            'delete'
                        ]
                    }
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