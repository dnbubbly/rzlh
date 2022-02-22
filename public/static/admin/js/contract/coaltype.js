define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'contract.coaltype/index',
        add_url: 'contract.coaltype/add',
        edit_url: 'contract.coaltype/edit',
        delete_url: 'contract.coaltype/delete',
        export_url: 'contract.coaltype/export',
        modify_url: 'contract.coaltype/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                skin: 'row ',
                toolbar: ['refresh',
                    [{
                        text: '添加',
                        url: init.add_url,
                        method: 'open',
                        auth: 'add',
                        width: 500,
                        height: 270,
                        class: 'layui-btn layui-btn-normal layui-btn-sm',
                        icon: 'fa fa-plus ',
                    }],
                    'delete'],
                cols: [[
                    {type: 'checkbox'},
                    	operat: [
                            [{
                                text: '编辑',
                                url: init.edit_url,
                                method: 'open',
                                width: 500,
                                height: 270,
                                auth: 'edit',
                                class: 'layui-btn layui-btn-xs layui-btn-success',
                            }],
                            'delete']
                    }
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