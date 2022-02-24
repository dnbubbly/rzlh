<?php /*a:2:{s:60:"E:\wamp64\www\rzlh\app\admin\view\customer\score\detail.html";i:1645497960;s:53:"E:\wamp64\www\rzlh\app\admin\view\layout\default.html";i:1645080665;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo sysconfig('site','site_name'); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="/static/admin/css/public.css?v=<?php echo htmlentities($version); ?>" media="all">
    <script>
        window.CONFIG = {
            ADMIN: "<?php echo htmlentities((isset($adminModuleName) && ($adminModuleName !== '')?$adminModuleName:'admin')); ?>",
            CONTROLLER_JS_PATH: "<?php echo htmlentities((isset($thisControllerJsPath) && ($thisControllerJsPath !== '')?$thisControllerJsPath:'')); ?>",
            ACTION: "<?php echo htmlentities((isset($thisAction) && ($thisAction !== '')?$thisAction:'')); ?>",
            AUTOLOAD_JS: "<?php echo htmlentities((isset($autoloadJs) && ($autoloadJs !== '')?$autoloadJs:'false')); ?>",
            IS_SUPER_ADMIN: "<?php echo htmlentities((isset($isSuperAdmin) && ($isSuperAdmin !== '')?$isSuperAdmin:'false')); ?>",
            VERSION: "<?php echo htmlentities((isset($version) && ($version !== '')?$version:'1.0.0')); ?>",
            CSRF_TOKEN: "<?php echo token(); ?>",
        };
    </script>
    <script src="/static/plugs/layui-v2.5.6/layui.all.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
    <script src="/static/plugs/require-2.3.6/require.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
    <script src="/static/config-admin.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
</head>
<body>
<div class="layuimini-container">
    <form id="app-form" class="layui-form layuimini-form layui-form-pane">
		<table class="layui-table" runat="server" border="1">
			<colgroup>
				<col width="200" />
				<col width="200" />
				<col width="200" />
				<col width="80" />
				<col width="200" />
				<col width="100" />
				<col />
			</colgroup>
			<thead>
				<tr>
					<th style="text-align: center;">指标名称</th>
					<th style="text-align: center;">评价内容</th>
					<th style="text-align: center;">评价标准</th>
					<th style="text-align: center;">满分
					</th><th style="text-align: center;">计分标准说明</th>
					<th style="text-align: center;">评分</th>
					<th style="text-align: center;">备注</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td align="center" rowspan="22">企业基本情况</td>
					<td align="center" rowspan="8">注册资本金</td>
					<td align="center">200000万元以上</td>
					<td align="center">20</td>
					<td align="center" rowspan="8">符合</td>
					<td align="center" rowspan="8">
						<?php echo htmlentities($row['grade1']); ?>
					</td>
					<td align="center" rowspan="8">关键点：注册资金低于5000万元的其他所有制企业，不予授信。（平台业务有上游公司担保条件的除外）</td>
				</tr>
				<tr>
					<td align="center">150000～200000万元</td>
					<td align="center">18</td>
				</tr>
				<tr>
					<td align="center">80000～150000万元</td>
					<td align="center">15</td>
				</tr>
				<tr>
					<td align="center">20000～80000万元</td>
					<td align="center">13</td>
				</tr>
				<tr>
					<td align="center">5000～20000万元</td>
					<td align="center">10</td>
				</tr>
				<tr>
					<td align="center">1000～5000万元</td>
					<td align="center">5</td>
				</tr>
				<tr>
					<td align="center">500～1000万元</td>
					<td align="center">3</td>
				</tr>
				<tr>
					<td align="center">100～500万元</td>
					<td align="center">1</td>
				</tr>
				<tr>
					<td align="center" rowspan="6">企业性质</td>
					<td align="center">央企</td>
					<td align="center">10</td>
					<td align="center" rowspan="6">其他所有制企业：国有参股企业、民营企业等。</td>
					<td align="center" rowspan="6">
						<?php echo htmlentities($row['grade2']); ?>
					</td>
					<td align="center" rowspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td align="center">省级国企</td>
					<td align="center">9</td>
				</tr>
				<tr>
					<td align="center">市级国企</td>
					<td align="center">8</td>
				</tr>
				<tr>
					<td align="center">上市企业</td>
					<td align="center">5</td>
				</tr>
				<tr>
					<td align="center">国有控股</td>
					<td align="center">2</td>
				</tr>
				<tr>
					<td align="center">其他所有制企业</td>
					<td align="center">1</td>
				</tr>
				<tr>
					<td align="center" rowspan="5">经营年限</td>
					<td align="center">8年以上</td>
					<td align="center">5</td>
					<td align="center" rowspan="5">&nbsp;</td>
					<td align="center" rowspan="5">
						<?php echo htmlentities($row['grade3']); ?>
					</td>
					<td align="center" rowspan="5">&nbsp;</td>
				</tr>
				<tr>
					<td align="center">5～8年</td>
					<td align="center">4</td>
				</tr>
				<tr>
					<td align="center">3～5年</td>
					<td align="center">3</td>
				</tr>
				<tr>
					<td align="center">1～3年</td>
					<td align="center">2</td>
				</tr>
				<tr>
					<td align="center">1年以下</td>
					<td align="center">1</td>
				</tr>
				<tr>
					<td align="center" rowspan="3">企业类型</td>
					<td align="center">钢铁、煤炭生产型企业</td>
					<td align="center">10</td>
					<td align="center" rowspan="3">&nbsp;</td>
					<td align="center" rowspan="3">
						<?php echo htmlentities($row['grade4']); ?>
					</td>
					<td align="center" rowspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td align="center">中转、加工、物流运输、贸易型企业</td>
					<td align="center">5</td>
				</tr>
				<tr>
					<td align="center">纯贸易型企业</td>
					<td align="center">1</td>
				</tr>
				<tr>
					<td align="center" rowspan="4">企业信用报告（国家官网、天眼查企查查、启信宝等）</td>
					<td align="center">新增法律诉讼案件</td>
					<td align="center">按条扣分</td>
					<td align="center">15</td>
					<td align="center">近两年涉及材料款的买卖合同纠纷且败诉，单条记录涉及金额500万元以内，每条记录扣 2分，单条记录超过 500 万元每条记录扣3分，扣完为止；上述金额累计超过5000万元，否决。</td>
					<td align="center">
						<?php echo htmlentities($row['grade5']); ?>
					</td>
					<td align="center">&nbsp;</td>
				</tr>
				<tr>
					<td align="center">企业整体运行情况</td>
					<td align="center">按不良信息条数扣分</td>
					<td align="center">10</td>
					<td align="center">查询天眼查、企查查、国家企业公示系统、中国政府采购网、中国执行信息公开网等信用网站，政府采购严重违法失信行为（否决）、行政处罚（50 万）、严重违法、股权出质、动产抵押等不良信息记录涉及金额500 万元以内，每条记录扣 2 分，单笔不良记录达到或超过 500 万元，扣 3 分，扣完为止</td>
					<td align="center">
						<?php echo htmlentities($row['grade6']); ?>
					</td>
					<td align="center">&nbsp;</td>
				</tr>
				<tr>
					<td align="center" rowspan="2">失信信息、经营异常</td>
					<td align="center">按条扣分</td>
					<td align="center">5</td>
					<td align="center">经营层换届后失信信息（包括高管信息、高管违法、高管变动、造假欺诈、贪污受贿、违纪违规、偷税漏税等）每条扣1分</td>
					<td align="center" rowspan="2">
						<?php echo htmlentities($row['grade7']); ?>
					</td>
					<td align="center" rowspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td align="center">债务违约</td>
					<td align="center">否决</td>
					<td align="center">&nbsp;</td>
				</tr>
				<tr>
					<td align="center" rowspan="3">近两年年度财务审计报告</td>
					<td align="center" rowspan="3">营业收入/盈利情况</td>
					<td align="center">无异常</td>
					<td align="center">10</td>
					<td align="center">查实年度财务审计、公司经营状况等情况，包括营业收入、利润等情况</td>
					<td align="center" rowspan="3">
						<?php echo htmlentities($row['grade8']); ?>
					</td>
					<td align="center" rowspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td align="center">异常（暴增/剧减）</td>
					<td align="center">否决</td>
					<td align="center">特殊情况需考虑的，报公司董事长办公会议集体决议</td>
				</tr>
				<tr>
					<td align="center">不提供</td>
					<td align="center">0</td>
					<td align="center">&nbsp;</td>
				</tr>
				<tr>
					<td align="center" rowspan="7">初步商谈合同约定条款</td>
					<td align="center" rowspan="2">回款（发货）期限</td>
					<td align="center">按约定期限付款（发货）</td>
					<td align="center">5</td>
					<td align="center" rowspan="2">&nbsp;</td>
					<td align="center" rowspan="2">
						<?php echo htmlentities($row['grade9']); ?>
					</td>
					<td align="center" rowspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td align="center">付款（发货）时间不固定</td>
					<td align="center">0</td>
				</tr>
				<tr>
					<td align="center" rowspan="3">结算方式</td>
					<td align="center">现汇</td>
					<td align="center">5</td>
					<td align="center" rowspan="3">&nbsp;</td>
					<td align="center" rowspan="3">
						<?php echo htmlentities($row['grade10']); ?>
					</td>
					<td align="center" rowspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td align="center">银承</td>
					<td align="center">1</td>
				</tr>
				<tr>
					<td align="center">商承</td>
					<td align="center">0</td>
				</tr>
				<tr>
					<td align="center" rowspan="2">验收标准</td>
					<td align="center">以我方为准</td>
					<td align="center">5</td>
					<td align="center" rowspan="2">&nbsp;</td>
					<td align="center" rowspan="2">
						<?php echo htmlentities($row['grade11']); ?>
					</td>
					<td align="center" rowspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td align="center">以对方为准</td>
					<td align="center">0</td>
				</tr>
				<tr>
					<td align="center">评分标准</td>
					<td align="center" colspan="3">评级：满分为100分。60-70分及格，70-80分为良，85分及以上为优，先款后货销售业务和先货后款采购业务可以适当放宽分数。有否决项直接否决。</td>
					<td align="center">得分合计：</td>
					<td align="center" rowspan="2">
						<?php echo htmlentities($row['total']); ?>
					</td>
				</tr>
			</tbody>
		</table>
        <div class="layui-form-item">
            <label class="layui-form-label required">资信审查</label>
            <div class="layui-input-block layuimini-upload">
                <input name="file1" class="layui-input layui-col-xs6" lay-verify="required" placeholder="请上传资信审查" value="<?php echo htmlentities((isset($row['file1']) && ($row['file1'] !== '')?$row['file1']:'')); ?>" style="width: calc(100% - 85px);">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="file1" data-upload-number="one" data-upload-exts="pdf|rar|doc|docx|xls|xlsx|txt" data-upload-icon="file"><i class="fa fa-upload"></i> 上传</a></span>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">尽职调查</label>
            <div class="layui-input-block layuimini-upload">
                <input name="file2" class="layui-input layui-col-xs6" lay-verify="required" placeholder="请上传尽职调查" value="<?php echo htmlentities((isset($row['file2']) && ($row['file2'] !== '')?$row['file2']:'')); ?>" style="width: calc(100% - 85px);">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="file2" data-upload-number="one" data-upload-exts="pdf|rar|doc|docx|xls|xlsx|txt" data-upload-icon="file"><i class="fa fa-upload"></i> 上传</a></span>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">添加人</label>
            <div class="layui-input-block">
                <input type="hidden" name="add_id" class="layui-input" readonly="true" style="background:#f1f1f1"  value="<?php echo htmlentities($row['systemAdmin']['id']); ?>">
               	<input type="text" name="" class="layui-input" readonly="true" style="background:#f1f1f1"  value="<?php echo htmlentities($row['systemAdmin']['username']); ?>">
            </div>
        </div>
    </form>
</div>
</body>
</html>