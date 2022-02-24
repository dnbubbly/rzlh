define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'sale.coaldetail/index',
        add_url: 'sale.coaldetail/add',
        edit_url: 'sale.coaldetail/edit',
        delete_url: 'sale.coaldetail/delete',
        export_url: 'sale.coaldetail/export',
        modify_url: 'sale.coaldetail/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},                    {field: 'id', title: 'id'},                    {field: 'sc_id', title: '主表id'},                    {field: 'cartype', title: '车种'},                    {field: 'carcode', title: '车号'},                    {field: 'sweight', title: '起票吨数'},                    {field: 'fweight', title: '矿发吨数'},                    {field: 'plan', title: '需求号'},                    {field: 'status', title: '是否质检', templet: ea.table.switch},                    {field: 'create_time', title: 'create_time'},                    {field: 'add_id', title: '添加人'},                    {width: 250, title: '操作', templet: ea.table.tool},
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