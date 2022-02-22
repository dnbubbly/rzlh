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
                    {type: 'checkbox'},                    {field: 'id', title: 'id'},                    {field: 'cqd_id', title: '执行标准id'},                    {field: 'v1', title: 'v1'},                    {field: 's1', title: 's1'},                    {field: 'v2', title: 'v2'},                    {field: 's2', title: 's2'},                    {field: 'g1', title: 'g1'},                    {field: 'g2', title: 'g2'},                    {field: 'p1', title: 'p1'},                    {field: 'p2', title: 'p2'},                    {field: 'create_time', title: 'create_time'},                    {width: 250, title: '操作', templet: ea.table.tool},
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