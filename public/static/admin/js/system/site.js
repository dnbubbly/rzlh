define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'system.site/index',
        add_url: 'system.site/add',
        edit_url: 'system.site/edit',
        delete_url: 'system.site/delete',
        export_url: 'system.site/export',
        modify_url: 'system.site/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                skin: 'row ',
                cols: [[
                    {type: 'checkbox'},
                    {field: 'status', title: '状态', width: 95, search: 'select', selectList: {0: '禁用', 1: '启用'}, templet: ea.table.switch},
                    {field: 'systemAdmin.username', minWidth: 100, title: '添加人', search: false},
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