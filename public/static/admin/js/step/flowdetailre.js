define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'step.flowdetailre/index',
        add_url: 'step.flowdetailre/add',
        edit_url: 'step.flowdetailre/edit',
        delete_url: 'step.flowdetailre/delete',
        export_url: 'step.flowdetailre/export',
        modify_url: 'step.flowdetailre/modify',
        reback_url: 'step.flowdetailre/reback',
        detail_url: 'step.flowdetailre/detail',
        examine_url: 'step.flowdetailre/examine',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                skin: 'row ',
                cols: [[
                    {type: 'checkbox'},
                    {field: 'type', title: '类型', width: 95, search: 'select', selectList: {1: '审批', 2: '抄送'}},
                    {field: 'current_step', width: 200, title: '当前审批步骤', search: false},
                    {field: 'status', title: '状态', width: 95, search: 'select', selectList: {0: '待办', 1: '已办'}},
                    {
                    	width: 250,
                    	title: '操作', 
                    	templet: ea.table.tool,
                    	operat: [
                            [{
                                text: '通过',
                                url: init.examine_url,
                                method: 'open',
                                auth: 'examine',
                                class: 'layui-btn layui-btn-xs layui-btn-success',
                                render: function (d) { return d.status === 0; }
                            }, {
                                text: '退回',
                                url: init.reback_url,
                                method: 'open',
                                auth: 'reback',
                                class: 'layui-btn layui-btn-xs layui-btn-warm',
                                render: function (d) { return d.status === 0; }
                            }, {
                                text: '查看',
                                url: init.detail_url,
                                method: 'open',
                                auth: 'detail',
                                class: 'layui-btn layui-btn-xs layui-btn-normal',
                            }],
                        ]
                    }
                ]],
            });

            ea.listen();
        },
        examine: function () {
            ea.listen();
        },
        reback: function () {
            ea.listen();
        },
    };
    return Controller;
});