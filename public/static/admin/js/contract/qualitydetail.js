define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'contract.qualitydetail/index',
        add_url: 'contract.qualitydetail/add',
        edit_url: 'contract.qualitydetail/edit',
        delete_url: 'contract.qualitydetail/delete',
        export_url: 'contract.qualitydetail/export',
        modify_url: 'contract.qualitydetail/modify',
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