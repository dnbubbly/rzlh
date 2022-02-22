define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'contract.qualitylevel/index',
        add_url: 'contract.qualitylevel/add',
        edit_url: 'contract.qualitylevel/edit',
        delete_url: 'contract.qualitylevel/delete',
        export_url: 'contract.qualitylevel/export',
        modify_url: 'contract.qualitylevel/modify',
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