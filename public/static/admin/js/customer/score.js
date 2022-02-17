define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'customer.score/index',
        add_url: 'customer.score/add',
        edit_url: 'customer.score/edit',
        delete_url: 'customer.score/delete',
        export_url: 'customer.score/export',
        modify_url: 'customer.score/modify',
    };
    var inputSelect = layui.inputSelect;
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
                       class: 'layui-btn layui-btn-normal layui-btn-sm',
                       icon: 'fa fa-plus ',
                       extend: 'data-full="true"',
                     }],
                    'delete', 'export'],
                cols: [[
                    {type: 'checkbox'},
                    	operat: [
                    		[{
                                text: '编辑',
                                extra:'name',
                                url: init.edit_url,
                                method: 'open',
                                auth: 'edit',
                                class: 'layui-btn layui-btn-xs layui-btn-success',
                                extend: 'data-full="true"',
                            }],
                            'delete'
                        ]
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