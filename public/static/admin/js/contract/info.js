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
                    {type: 'checkbox'},                    {field: 'id', title: 'id'},                    {field: 'type', title: '合同类型'},                    {field: 'draft', search: 'select', selectList: {"1":"新增合同","2":"变更合同"}, title: '起草类型'},                    {field: 'lead', search: 'select', selectList: {"1":"我方主导","2":"对方主导"}, title: '主导类型'},                    {field: 'seller', title: '销方'},                    {field: 'buyer', title: '购方'},                    {field: 'number', title: '合同编号'},                    {field: 'address', title: '签订地点'},                    {field: 'date', title: '起草日期'},                    {field: 'receiving', title: '收货单位'},                    {field: 'settlement', title: '结算单位'},                    {field: 'startdate', title: '执行期起'},                    {field: 'enddate', title: '执行期止'},                    {field: 'road', title: '物流方式'},                    {field: 'start_station', title: '发站'},                    {field: 'end_station', title: '到站'},                    {field: 'delivery', title: '交货方式'},                    {field: 'delivery_address', title: '交货地点'},                    {field: 'check_type', search: 'select', selectList: {"1":"以到厂后收货单位采、制、化验结果为准","2":"以第三方检测机构化验结果为准","3":"以购方化验结果为准","4":"以销方化验结果为准"}, title: '质量验收'},                    {field: 'tax', search: 'select', selectList: {"1":"不含税","2":"含 13%增值税发票"}, title: '发票'},                    {field: 'freight', search: 'select', selectList: {"1":"单价包含运费","2":"单价不包含运费，购方自提","3":"单价不包含运费，销方办理运输，费用由购方承担"}, title: '运费'},                    {field: 'markup', search: 'select', selectList: {"1":"不加价","2":"6月以内承兑加价","3":"6月以上承兑加价"}, title: '承兑加价'},                    {field: 'markupnum', title: '承兑加价数量'},                    {field: 'advance', title: '预付款比例'},                    {field: 'advance_remark', title: '预付款比例说明'},                    {field: 'settle', search: 'select', selectList: {"1":"一票结算","2":"二票结算"}, title: '结算方式'},                    {field: 'remark', title: '备注', templet: ea.table.text},                    {field: 'elefile', title: '电子合同', templet: ea.table.url},                    {field: 'create_time', title: '创建时间'},                    {field: 'add_id', title: '添加人'},                    {width: 250, title: '操作', templet: ea.table.tool},
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