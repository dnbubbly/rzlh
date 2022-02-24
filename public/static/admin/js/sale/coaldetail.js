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
                    {type: 'checkbox'},
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