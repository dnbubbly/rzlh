define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'sale.coal/index',
        add_url: 'sale.coal/add',
        edit_url: 'sale.coal/edit',
        delete_url: 'sale.coal/delete',
        export_url: 'sale.coal/export',
        modify_url: 'sale.coal/modify',
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