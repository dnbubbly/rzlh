define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'contract.detail/index',
        add_url: 'contract.detail/add',
        edit_url: 'contract.detail/edit',
        delete_url: 'contract.detail/delete',
        export_url: 'contract.detail/export',
        modify_url: 'contract.detail/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},                    {field: 'id', title: 'id'},                    {field: 'c_id', title: 'c_id'},                    {field: 'type', title: '品种'},                    {field: 'num', title: '数量'},                    {field: 'price', title: '单价'},                    {field: 'create_time', title: 'create_time'},                    {width: 250, title: '操作', templet: ea.table.tool},
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