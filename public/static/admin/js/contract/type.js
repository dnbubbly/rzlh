define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'contract.type/index',
        add_url: 'contract.type/add',
        edit_url: 'contract.type/edit',
        delete_url: 'contract.type/delete',
        export_url: 'contract.type/export',
        modify_url: 'contract.type/modify',
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
                    {type: 'checkbox'},                    {field: 'id', width: 120, title: 'id', search: false},                    {field: 'name', title: '名称'},                    {field: 'status', width: 120, search: 'select', selectList: ["禁用","启用"], title: '状态', templet: ea.table.switch},                    {width: 250, title: '操作', templet: ea.table.tool},
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