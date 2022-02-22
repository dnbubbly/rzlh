define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'contract.coaltype/index',
        add_url: 'contract.coaltype/add',
        edit_url: 'contract.coaltype/edit',
        delete_url: 'contract.coaltype/delete',
        export_url: 'contract.coaltype/export',
        modify_url: 'contract.coaltype/modify',
    };

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
                        width: 500,
                        height: 270,
                        class: 'layui-btn layui-btn-normal layui-btn-sm',
                        icon: 'fa fa-plus ',
                    }],
                    'delete'],
                cols: [[
                    {type: 'checkbox'},                    {field: 'id', width: 80, title: 'id', search: false},                    {field: 'name', title: '品种名称'},                    {field: 'status', width: 120, search: 'select', selectList: ["禁用","启用"], title: '状态', templet: ea.table.switch},                    {field: 'create_time', width: 200, title: '创建时间', search: false},                    {field: 'systemAdmin.username', width: 120, title: '添加人', search: false},                    {width: 250, title: '操作', templet: ea.table.tool,
                    	operat: [
                            [{
                                text: '编辑',
                                url: init.edit_url,
                                method: 'open',
                                width: 500,
                                height: 270,
                                auth: 'edit',
                                class: 'layui-btn layui-btn-xs layui-btn-success',
                            }],
                            'delete']
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