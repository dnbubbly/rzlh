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
                    {type: 'checkbox'},                    {field: 'id', title: 'id'},                    {field: 'typez', title: '装车类型  （1:'销售';2:'采购'）'},                    {field: 'cusname', title: '收货单位'},                    {field: 'start_station', title: '发站'},                    {field: 'end_station', title: '到站'},                    {field: 'mini', title: '矿点'},                    {field: 'setdate', title: '定车日期'},                    {field: 'plan', title: '需求号'},                    {field: 'road', title: '物流方式'},                    {field: 'loaddate', title: '装车日期'},                    {field: 'coaltype', title: '品种规格'},                    {field: 'loadtype', title: '运类型   （1: '销售', 2: '采购'）'},                    {field: 'store', title: '仓库'},                    {field: 'trans_type', title: '发运类型   （1: '采购直发', 2: '采购转存',3: '仓库现货发运'）'},                    {field: 'status', title: '状态  （0: '未到货', 1: '已到货',2: '已结算'）', templet: ea.table.switch},                    {field: 'remark', title: '备注', templet: ea.table.text},                    {field: 'create_time', title: 'create_time'},                    {field: 'add_id', title: '添加人'},                    {width: 250, title: '操作', templet: ea.table.tool},
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