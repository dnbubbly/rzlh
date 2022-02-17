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
                    {type: 'checkbox'},                    {field: 'id', width: 60, title: 'id', search: false},                    {field: 'origin', width: 110, title: '发站'},                    {field: 'end', width: 110, title: '到站'},                    {field: 'provinces', width: 110, title: '省市'},                    {field: 'bureau', width: 110, title: '所属局'},                    {field: 'mileage', width: 110, title: '里程', search: false},                    {field: 'freight', width: 110, title: '运费', search: false},                    {field: 'carcost', width: 110, title: '取送车费', search: false},                    {field: 'premium', width: 110, title: '保费', search: false},
                    {field: 'status', title: '状态', width: 95, search: 'select', selectList: {0: '禁用', 1: '启用'}, templet: ea.table.switch},
                    {field: 'systemAdmin.username', minWidth: 100, title: '添加人', search: false},                    {field: 'create_time', minWidth: 200, title: '创建时间', search: false},                    {width: 250, title: '操作', templet: ea.table.tool},
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