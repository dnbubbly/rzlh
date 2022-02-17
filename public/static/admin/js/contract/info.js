define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'contract.info/index',
        addone_url: 'contract.info/addone',
        add_url: 'contract.info/add',
        edit_url: 'contract.info/edit',
        delete_url: 'contract.info/delete',
        export_url: 'contract.info/export',
        modify_url: 'contract.info/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                skin: 'row ',
                toolbar: ['refresh',
                    [{
                        text: '添加',
                        url: init.addone_url,
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
                ]],
            });

            ea.listen();
        },
        addone: function () {
        	$('input:radio[name=cus_id]')[0].attr("selected",true);
        	$('input:radio[name=draft]')[0].checked = true;
        	$('input:radio[name=lead]')[0].checked = true;
        	layui.form.render();
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