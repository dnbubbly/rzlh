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
                    {type: 'checkbox'},                    {field: 'id', title: 'id'},                    {field: 'cd_id', title: '合同明细id'},                    {field: 'name', title: '名称'},                    {field: 'standard', title: '执行标准'},                    {field: 'create_time', title: 'create_time'},                    {width: 250, title: '操作', templet: ea.table.tool},
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