<?php /*a:2:{s:53:"E:\wamp64\www\rzlh\app\admin\view\step\flow\flow.html";i:1645080665;s:53:"E:\wamp64\www\rzlh\app\admin\view\layout\default.html";i:1645080665;}*/ ?>
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
<link rel="stylesheet" href="/static/plugs/element-ui/element-ui.css?v=<?php echo htmlentities($version); ?>" media="all">
<link rel="stylesheet" href="/static/admin/css/workflow.css?v=<?php echo htmlentities($version); ?>" media="all">
<div id="app">
	<div>
		<div class="fd-nav">
			<div class="fd-nav-left">
				
			</div>
			<div class="fd-nav-right">
				
			</div>
		</div>
		<div class="fd-nav-content">
			<section class="dingflow-design">
				<div class="zoom">
					<div :class="'zoom-out'+ (nowVal==50?' disabled':'')" @click="zoomSize(1)"></div>
					<span>{{nowVal}}%</span>
					<div :class="'zoom-in'+ (nowVal==300?' disabled':'')" @click="zoomSize(2)"></div>
				</div>
				<div class="box-scale" id="box-scale" :style="'transform: scale('+nowVal/100+'); transform-origin: 50% 0px 0px;'">
					<nodewrap :nodeconfig.sync="nodeConfig" :flowpermission.sync="flowPermission" :istried.sync="isTried" :tableid="tableId"></nodewrap>
					<div class="end-node">
						<div class="end-node-circle"></div>
						<div class="end-node-text">流程结束</div>
					</div>
				</div>
			</section>
		</div>
		<errordialog :visible.sync="tipVisible" :list="tipList"	></errordialog>
		<promoterdrawer></promoterdrawer>
		<approverdrawer  :directormaxlevel="directorMaxLevel" ></approverdrawer>
		<copyerdrawer></copyerdrawer>
		<conditiondrawer :tableid="tableId"></conditiondrawer>
	</div>
</div>

<template id="nodewrap">
    <div>
        <div class="node-wrap" v-if="nodeconfig.type!=4">
            <div class="node-wrap-box" :class="(nodeconfig.type==0?'start-node ':'')+(istried&&nodeconfig.error?'active error':'')">
                <div>
                    <div class="title" :style="'background: rgb('+ ['87, 106, 149','255, 148, 62','50, 150, 250'][nodeconfig.type] +');'">
                        <span class="iconfont" v-show="nodeconfig.type==1"></span>
                        <span class="iconfont" v-show="nodeconfig.type==2"></span>
                        <span v-if="nodeconfig.type==0">{{nodeconfig.nodeName}}</span>
                        <span class="editable-title" >{{nodeconfig.nodeName}}</span>
                    </div>
                    <div class="content">
                        <div class="text" v-if="nodeconfig.type==0">{{arrToStr(flowpermission)?arrToStr(flowpermission):'所有人'}}</div>
                        <div class="text" v-if="nodeconfig.type==1">
                            <span class="placeholder" v-if="!setApproverStr(nodeconfig)">请选择{{placeholderList[nodeconfig.type]}}</span>
                            {{setApproverStr(nodeconfig)}}
                        </div>
                        <div class="text" v-if="nodeconfig.type==2">
                            <span class="placeholder" v-if="!copyerStr(nodeconfig)">请选择{{placeholderList[nodeconfig.type]}}</span>
                            {{copyerStr(nodeconfig)}}
                        </div>
                        <i class="anticon anticon-right arrow"></i>
                    </div>
                    <div class="error_tip" v-if="istried&&nodeconfig.error">
                        <i class="anticon anticon-exclamation-circle" style="color: rgb(242, 86, 67);"></i>
                    </div>
                </div>
            </div>
            <addnode :childnodep.sync="nodeconfig.childNode"></addnode>
        </div>
        <div class="branch-wrap" v-if="nodeconfig.type==4">
            <div class="branch-box-wrap">
                <div class="branch-box">
                    <button class="add-branch">条件</button>
                    <div class="col-box" v-for="(item,index) in nodeconfig.conditionNodes" :key="index">
                        <div class="condition-node">
                            <div class="condition-node-box">
                                <div class="auto-judge" :class="istried&&item.error?'error active':''">
                                    <div class="title-wrapper">
                                        <span class="editable-title">{{item.nodeName}}</span>
                                        <span class="priority-title">优先级{{item.priorityLevel}}</span>
                                    </div>
                                    <div class="content">{{conditionStr(nodeconfig,index)}}</div>
                                    <div class="error_tip" v-if="istried&&item.error">
                                        <i class="anticon anticon-exclamation-circle" style="color: rgb(242, 86, 67);"></i>
                                    </div>
                                </div>
                                <addnode :childNodeP.sync="item.childNode"></addnode>
                            </div>
                        </div>
                        <nodewrap v-if="item.childNode && item.childNode" :nodeconfig.sync="item.childNode" :tableid="tableid" :istried.sync="istried"></nodewrap>
                        <div class="top-left-cover-line" v-if="index==0"></div>
                        <div class="bottom-left-cover-line" v-if="index==0"></div>
                        <div class="top-right-cover-line" v-if="index==nodeconfig.conditionNodes.length-1"></div>
                        <div class="bottom-right-cover-line" v-if="index==nodeconfig.conditionNodes.length-1"></div>
                    </div>
                </div>
                <addnode :childnodep.sync="nodeconfig.childNode"></addnode>
            </div>
        </div>
        <nodewrap v-if="nodeconfig.childNode && nodeconfig.childNode" :nodeconfig.sync="nodeconfig.childNode" :istried.sync="istried" :tableid="tableid"></nodewrap>
    </div>
</template>
<template id="add-node">
	<div class="add-node-btn-box">
        <div class="add-node-btn">
        </div>
    </div>
</template>
</body>
</html>