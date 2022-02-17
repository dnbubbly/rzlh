<?php /*a:2:{s:58:"E:\wamp64\www\rlzy\app\admin\view\system\step\addnode.html";i:1644808938;s:53:"E:\wamp64\www\rlzy\app\admin\view\layout\default.html";i:1638342384;}*/ ?>
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
				<div class="fd-nav-title">{{workFlowDef.name}}</div>
			</div>
			<div class="fd-nav-right">
				<button type="button" class="ant-btn button-publish" @click="saveSet"><span>发 布</span></button>
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
                        <input type="text" class="ant-input editable-title-input" v-if="nodeconfig.type!=0&&isInput"
                        @blur="blurEvent()" @focus="$event.currentTarget.select()" v-focus
                        v-model="nodeconfig.nodeName" :placeholder="placeholderList[nodeconfig.type]">
                        <span class="editable-title" @click="clickEvent()" v-if="nodeconfig.type!=0&&!isInput">{{nodeconfig.nodeName}}</span>
                        <i class="anticon anticon-close close" v-if="nodeconfig.type!=0" @click="delNode()"></i>
                    </div>
                    <div class="content" @click="setPerson">
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
                    <button class="add-branch" @click="addTerm">添加条件</button>
                    <div class="col-box" v-for="(item,index) in nodeconfig.conditionNodes" :key="index">
                        <div class="condition-node">
                            <div class="condition-node-box">
                                <div class="auto-judge" :class="istried&&item.error?'error active':''">
                                    <div class="title-wrapper">
                                        <input type="text" class="ant-input editable-title-input" v-if="isInputList[index]"
                                        @blur="blurEvent(index)" @focus="$event.currentTarget.select()" v-focus v-model="item.nodeName">
                                        <span class="editable-title" @click="clickEvent(index)" v-if="!isInputList[index]">{{item.nodeName}}</span>
                                        <span class="priority-title" @click="setPerson(item.priorityLevel)">优先级{{item.priorityLevel}}</span>
                                        <i class="anticon anticon-close close" @click="delTerm(index)"></i>
                                    </div>
                                    <div class="content" @click="setPerson(item.priorityLevel)">{{conditionStr(nodeconfig,index)}}</div>
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
            <el-popover placement="right-start" v-model="visible">
                <div class="add-node-popover-body">
                    <a class="add-node-popover-item approver" @click="addType(1)">
                        <div class="item-wrapper">
                            <span class="iconfont"></span>
                        </div>
                        <p>审批人</p>
                    </a>
                    <a class="add-node-popover-item notifier" @click="addType(2)">
                        <div class="item-wrapper">
                            <span class="iconfont"></span>
                        </div>
                        <p>抄送人</p>
                    </a>
                    <a class="add-node-popover-item condition" @click="addType(4)">
                        <div class="item-wrapper">
                            <span class="iconfont"></span>
                        </div>
                        <p>条件分支</p>
                    </a>
                </div>
                <button class="btn" type="button" slot="reference">
                    <span class="iconfont"></span>
                </button>
            </el-popover>
        </div>
    </div>
</template>
<!-- 发起人 -->
<template id="promoterdrawer">
	<el-drawer :append-to-body="true" title="发起人" :visible.sync="$store.state.promoterDrawer" direction="rtl" class="set_promoter" size="550px" :before-close="savePromoter"> 
        <div class="demo-drawer__content">
            <div class="promoter_content drawer_content">
                <p>{{arrToStr(flowPermission)?arrToStr(flowPermission):'所有人'}}</p>
                <el-button type="primary" @click="addPromoter">添加/修改发起人</el-button>
            </div>
            <div class="demo-drawer__footer clear">
                <el-button type="primary" @click="savePromoter">确 定</el-button>
                <el-button @click="closeDrawer">取 消</el-button>
            </div>
            <employees :isdepartment="true" :visible.sync="promoterVisible" :data.sync="checkedList" @change="surePromoter"></employees>
        </div>
    </el-drawer>
</template>
<!-- 审批人 -->
<template id="approverdrawer">
	<el-drawer :append-to-body="true" title="审批人设置" :visible.sync="$store.state.approverDrawer" direction="rtl" class="set_promoter" size="550px" :before-close="saveApprover">  
        <div class="demo-drawer__content">
            <div class="drawer_content">
                <div class="approver_content">
                    <el-radio-group v-model="approverConfig.settype" class="clear" @change="changeType">
                        <el-radio :label="1">指定成员</el-radio>
                        <el-radio :label="2">主管</el-radio>
                        <!-- <el-radio :label="4">发起人自选</el-radio>
                        <el-radio :label="5">发起人自己</el-radio> -->
                        <el-radio :label="7">连续多级主管</el-radio>
                    </el-radio-group>
                </div>
                <div class="approver_manager" v-if="approverConfig.settype==1">
                	<el-button type="primary" @click="addApprover">添加/修改成员</el-button>
	                <p class="selected_list" v-if="approverConfig.settype==1">
	                	<span v-for="(item,index) in approverConfig.nodeUserList" :key="index">{{item.name}}
	                    	<img src="/static/admin/images/add-close1.png" @click="removeEle(approverConfig.nodeUserList,item,'targetId')">
	                    </span>
	                 	<a v-if="approverConfig.nodeUserList.length!=0" @click="approverConfig.nodeUserList=[]">清除</a>
	                </p>
                </div>
                <div class="approver_manager" v-if="approverConfig.settype==2">
                    <p>
                        <span>发起人的：</span>
                        <select v-model="approverConfig.directorLevel">
                            <option v-for="item in directormaxlevel" :value="item" :key="item">{{item==1?'直接':'第'+item+'级'}}主管</option>
                        </select>
                    </p>
                    <p class="tip">找不到主管时，由上级主管代审批</p>
                </div>
                <div class="approver_self" v-if="approverConfig.settype==5">
                    <p>该审批节点设置“发起人自己”后，审批人默认为发起人</p>
                </div>
                <div class="approver_self_select" v-show="approverConfig.settype==4">
                    <el-radio-group v-model="approverConfig.selectMode" style="width: 100%;">
                        <el-radio :label="1">选一个人</el-radio>
                        <el-radio :label="2">选多个人</el-radio>
                    </el-radio-group>
                    <h3>选择范围</h3>
                    <el-radio-group v-model="approverConfig.selectRange" style="width: 100%;" @change="changeRange">
                        <el-radio :label="1">全公司</el-radio>
                        <el-radio :label="2">指定成员</el-radio>
                        <el-radio :label="3">指定角色</el-radio>
                    </el-radio-group>
                    <el-button type="primary" @click="addApprover" v-if="approverConfig.selectRange==2">添加/修改成员</el-button>
                    <el-button type="primary" @click="addRoleApprover" v-if="approverConfig.selectRange==3">添加/修改角色</el-button>
                    <p class="selected_list" v-if="approverConfig.selectRange==2||approverConfig.selectRange==3">
                        <span v-for="(item,index) in approverConfig.nodeUserList" :key="index">{{item.name}}
                            <img src="/static/admin/images/add-close1.png" @click="removeEle(approverConfig.nodeUserList,item,'targetId')">
                        </span>
                        <a v-if="approverConfig.nodeUserList.length!=0&&approverConfig.selectRange!=1" @click="approverConfig.nodeUserList=[]">清除</a>
                    </p>
                </div>
                <div class="approver_manager" v-if="approverConfig.settype==7">
                    <p>审批终点</p>
                    <p style="padding-bottom:20px">
                        <span>发起人的：</span>
                        <select v-model="approverConfig.examineEndDirectorLevel">
                            <option v-for="item in directormaxlevel" :value="item" :key="item">{{'第'+item}}层级主管</option>
                        </select>
                    </p>
                </div>
                <div class="approver_some" v-if="(approverConfig.settype==1&&approverConfig.nodeUserList.length>1)||approverConfig.settype==2||(approverConfig.settype==4&&approverConfig.selectMode==2)">
                    <p>多人审批时采用的审批方式</p>
                    <el-radio-group v-model="approverConfig.examineMode" class="clear">
                        <el-radio :label="1">依次审批</el-radio>
                        <br/>
                        <el-radio :label="2" v-if="approverConfig.settype!=2">会签(须所有审批人同意)</el-radio>
                    </el-radio-group>
                </div>
                <div class="approver_some" v-if="approverConfig.settype==2||approverConfig.settype==7">
                    <p>审批人为空时</p>
                    <el-radio-group v-model="approverConfig.noHanderAction" class="clear">
                        <el-radio :label="1">自动审批通过/不允许发起</el-radio>
                        <br/>
                        <el-radio :label="2">转交给审核管理员</el-radio>
                    </el-radio-group>
                </div>
            </div>
            <div class="demo-drawer__footer clear">
                <el-button type="primary" @click="saveApprover">确 定</el-button>
                <el-button @click="closeDrawer">取 消</el-button>
            </div>
            <employees :visible.sync="approverVisible" :data.sync="checkedList" @change="sureApprover"></employees>
            <role :visible.sync="approverRoleVisible" :data.sync="checkedRoleList"  @change="sureRoleApprover"></role>
        </div>
    </el-drawer>
</template>
<!-- 抄送人 -->
<template id="copyerdrawer">
    <el-drawer :append-to-body="true" title="抄送人设置" :visible.sync="$store.state.copyerDrawer" direction="rtl" class="set_copyer" size="550px" :before-close="saveCopyer"> 
        <div class="demo-drawer__content">
            <div class="copyer_content drawer_content">
                <el-button type="primary" @click="addCopyer">添加成员</el-button>
                <p class="selected_list">
                    <span v-for="(item,index) in copyerConfig.nodeUserList" :key="index">{{item.name}}
                        <img src="/static/admin/images/add-close1.png" @click="removeEle(copyerConfig.nodeUserList,item,'targetId')">
                    </span>
                    <a v-if="copyerConfig.nodeUserList&&copyerConfig.nodeUserList.length!=0" @click="copyerConfig.nodeUserList=[]">清除</a>
                </p>
                <!-- <el-checkbox-group v-model="ccSelfSelectFlag" class="clear">
                    <el-checkbox :label="1">允许发起人自选抄送人</el-checkbox>
                </el-checkbox-group> -->
            </div>
            <div class="demo-drawer__footer clear">
                <el-button type="primary" @click="saveCopyer">确 定</el-button>
                <el-button @click="closeDrawer">取 消</el-button>
            </div>
            <employeesroles :visible.sync="copyerVisible" :data.sync="checkedList" @change="sureCopyer"></employeesroles>
        </div>
    </el-drawer>
</template>
<!-- 条件 -->
<template id="conditiondrawer">
    <el-drawer :append-to-body="true" title="条件设置" :visible.sync="$store.state.conditionDrawer" direction="rtl" class="condition_copyer" size="550px" :before-close="saveCondition"> 
        <select v-model="conditionConfig.priorityLevel" class="priority_level">
            <option v-for="item in conditionsConfig.conditionNodes.length" :value="item" :key="item">优先级{{item}}</option>
        </select>
        <div class="demo-drawer__content">
            <div class="condition_content drawer_content">
                <p class="tip">当审批单同时满足以下条件时进入此流程</p>
                <ul>
                    <li v-for="(item,index) in conditionConfig.conditionList" :key="index">
                        <span class="ellipsis">{{item.type==1?'发起人':item.showName}}：</span>
                        <div v-if="item.type==1">
                            <p :class="conditionConfig.nodeUserList.length > 0?'selected_list':''" @click.self="addConditionRole" style="cursor:text">
                                <span v-for="(item1,index1) in conditionConfig.nodeUserList" :key="index1">
                                    {{item1.name}}<img src="/static/admin/images/add-close1.png" @click="removeEle(conditionConfig.nodeUserList,item1,'targetId')">
                                </span>
                                <input type="text" placeholder="请选择具体人员/角色/部门" v-if="conditionConfig.nodeUserList.length == 0" @click="addConditionRole">
                            </p>
                        </div>
                        <div v-else-if="item.columnType == 'String' && item.showType == 3">
                            <p class="check_box">
                                <a :class="toggleStrClass(item,item1.key)&&'active'" @click="toStrChecked(item,item1.key)"
                                v-for="(item1,index1) in JSON.parse(item.fixedDownBoxValue)" :key="index1">{{item1.value}}</a>
                            </p>
                        </div>
                        <div v-else-if="item.columnType == 'String' && item.showType == 2">
                            <p>
                                <select v-model="item.optType" :style="'width:'+(item.optType==6?370:100)+'px'">
                                    <option value="1">精确查询</option>
                                    <option value="2">模糊查询</option>
                                </select>
                                <input type="text" :placeholder="'请输入'+item.showName" v-model="item.zdy1">
                            </p>
                        </div>
                        <div v-else>
                            <p>
                                <select v-model="item.optType" :style="'width:'+(item.optType==6?370:100)+'px'" @change="changeOptType(item)">
                                    <option value="1">小于</option>
                                    <option value="2">大于</option>
                                    <option value="3">小于等于</option>
                                    <option value="4">等于</option>
                                    <option value="5">大于等于</option>
                                    <option value="6">介于两个数之间</option>
                                </select>
                                <input v-if="item.optType!=6" type="text" :placeholder="'请输入'+item.showName" v-enter-number="2" v-model="item.zdy1">
                            </p>
                            <p v-if="item.optType==6">
                                <input type="text" style="width:75px;" class="mr_10" v-enter-number="2" v-model="item.zdy1">
                                <select style="width:60px;" v-model="item.opt1">
                                    <option value="<">&lt;</option>
                                    <option value="≤">≤</option>
                                </select>
                                <span class="ellipsis" style="display:inline-block;width:60px;vertical-align: text-bottom;">{{item.showName}}</span>
                                <select style="width:60px;" class="ml_10" v-model="item.opt2">
                                    <option value="<">&lt;</option>
                                    <option value="≤">≤</option>
                                </select>
                                <input type="text" style="width:75px;" v-enter-number="2" v-model="item.zdy2">
                            </p>
                        </div>
                        <a v-if="item.type==1" @click="conditionConfig.nodeUserList= [];removeEle(conditionConfig.conditionList,item,'columnId')">删除</a>
                        <a v-if="item.type==2" @click="removeEle(conditionConfig.conditionList,item,'columnId')">删除</a>
                    </li>
                </ul>
                <el-button type="primary" @click="addCondition">添加条件</el-button>
                <el-dialog title="选择条件" :visible.sync="conditionVisible" width="480px" append-to-body class="condition_list">
                    <p>请选择用来区分审批流程的条件字段</p>
                    <p class="check_box">
                        <a :class="toggleClass(conditionList,{columnId:0},'columnId')&&'active'" @click="toChecked(conditionList,{columnId:0},'columnId')">发起人</a>
                        <a v-for="(item,index) in conditions" :key="index" :class="toggleClass(conditionList,item,'columnId')&&'active'" 
                        @click="toChecked(conditionList,item,'columnId')">{{item.showName}}</a>
                    </p>
                    <span slot="footer" class="dialog-footer">
                        <el-button @click="conditionVisible = false">取 消</el-button>
                        <el-button type="primary" @click="sureCondition">确 定</el-button>
                    </span>
                </el-dialog>
            </div>
            <employeesroles :visible.sync="conditionRoleVisible" :data.sync="checkedList" @change="sureConditionRole" :isDepartment="true" ></employeesroles>
            <div class="demo-drawer__footer clear">
                <el-button type="primary" @click="saveCondition">确 定</el-button>
                <el-button @click="$store.commit('updateCondition',false)">取 消</el-button>
            </div>
        </div>
    </el-drawer>
</template>
<template id="employees">
	<el-dialog title="选择成员" :visible.sync="visibleDialog" width="600px" append-to-body class="promoter_person">
        <div class="person_body clear">
          <div class="person_tree l">
              <input type="text" placeholder="搜索成员" v-model="searchVal" @input="getDebounceData($event)">
              <p class="ellipsis tree_nav" v-if="!searchVal">
                  <span @click="getDepartmentList(1)" class="ellipsis">总经办</span>
                  <span v-for="(item,index) in departments.titleDepartments" class="ellipsis" 
                  :key="index+'a'" @click="getDepartmentList(item.id)">{{item.departmentName}}</span>   
              </p>
              <ul>
                  <li v-for="(item,index) in departments.childDepartments" :key="index+'b'" class="check_box" :class="{not: !isdepartment}">
                      <a v-if="isdepartment" :class="toggleClass(checkedDepartmentList,item)&&'active'" @click="toChecked(checkedDepartmentList,item)">
                          <img src="/static/admin/images/icon_file.png">{{item.departmentName}}</a>
                      <a v-else><img src="/static/admin/images/icon_file.png">{{item.departmentName}}</a>
                      <i @click="getDepartmentList(item.id)">下级</i>
                  </li>
                  <li v-for="(item,index) in departments.employees" :key="index+'c'" class="check_box">
                      <a :class="toggleClass(checkedEmployessList,item)&&'active'" @click="toChecked(checkedEmployessList,item)" :title="item.departmentNames">
                          <img src="/static/admin/images/icon_people.png">{{item.employeeName}}</a>
                  </li>
              </ul>
          </div>
          <div class="has_selected l">
              <p class="clear">已选（{{total}}）
                  <a @click="delList">清空</a>
              </p>
              <ul>
                  <template v-if="isdepartment">
                    <li v-for="(item,index) in checkedDepartmentList" :key="index+'d'">
                        <img src="/static/admin/images/icon_file.png">
                        <span>{{item.departmentName}}</span>
                        <img src="/static/admin/images/cancel.png" @click="removeEle(checkedDepartmentList,item)">
                    </li>
                  </template>
                  <li v-for="(item,index) in checkedEmployessList" :key="index+'e'">
                      <img src="/static/admin/images/icon_people.png">
                      <span>{{item.employeeName}}</span>
                      <img src="/static/admin/images/cancel.png" @click="removeEle(checkedEmployessList,item)">
                  </li>
              </ul>
          </div>
        </div>
        <span slot="footer" class="dialog-footer">
          <el-button @click="$emit('update:visible',false)">取 消</el-button>
          <el-button type="primary" @click="saveDialog">确 定</el-button>
        </span>
    </el-dialog>
</template>
<template id="role">
   <el-dialog title="选择角色" :visible.sync="visibleDialog" width="600px" append-to-body class="promoter_person">
      <div class="person_body clear">
          <div class="person_tree l">
              <input type="text" placeholder="搜索角色" v-model="searchVal" @input="getDebounceData($event,2)">
              <ul>
                  <li v-for="(item,index) in roles" :key="index+'b'" class="check_box not"
                      :class="toggleClass(checkedRoleList,item,'roleId')&&'active'" @click="checkedRoleList=[item]">
                      <a :title="item.description"><img src="/static/admin/images/icon_role.png">{{item.roleName}}</a>
                  </li>
              </ul>
          </div>
          <div class="has_selected l">
              <p class="clear">已选（{{total}}）
                  <a @click="delList">清空</a>
              </p>
              <ul>
                  <li v-for="(item,index) in checkedRoleList" :key="index+'e'">
                      <img src="/static/admin/images/icon_role.png">
                      <span>{{item.roleName}}</span>
                      <img src="/static/admin/images/cancel.png" @click="removeEle(checkedRoleList,item,'roleId')">
                  </li>
              </ul>
          </div>
      </div>
      <span slot="footer" class="dialog-footer">
          <el-button @click="$emit('update:visible',false)">取 消</el-button>
          <el-button type="primary" @click="saveDialog">确 定</el-button>
      </span>
  </el-dialog>
</template>
<template id="employeesroles">
  <el-dialog title="选择成员" :visible.sync="visibleDialog" width="600px" append-to-body class="promoter_person">
      <div class="person_body clear">
          <div class="person_tree l">
              <input type="text" placeholder="搜索成员" v-model="searchVal" @input="getDebounceData($event,activeName)">
              <el-tabs v-model="activeName" @tab-click="handleClick">
                  <el-tab-pane label="组织架构" name="1"></el-tab-pane>
                  <el-tab-pane label="角色列表" name="2"></el-tab-pane>
              </el-tabs>
              <p class="ellipsis tree_nav" v-if="activeName==1&&!searchVal">
                  <span @click="getDepartmentList(0)" class="ellipsis">总经办</span>
                  <span v-for="(item,index) in departments.titleDepartments" class="ellipsis" 
                  :key="index+'a'" @click="getDepartmentList(item.id)">{{item.departmentName}}</span>   
              </p>
              <ul style="height: 360px;" v-if="activeName==1">
                  <li v-for="(item,index) in departments.childDepartments" :key="index+'b'" class="check_box" :class="{not: !isDepartment}">
                      <a v-if="isDepartment" :class="toggleClass(checkedDepartmentList,item)&&'active'" @click="toChecked(checkedDepartmentList,item)">
                        <img src="/static/admin/images/icon_file.png">{{item.departmentName}}</a>
                      <a v-else><img src="/static/admin/images/icon_file.png">{{item.departmentName}}</a>
                      <i @click="getDepartmentList(item.id)">下级</i>
                  </li>
                  <li v-for="(item,index) in departments.employees" :key="index+'c'" class="check_box">
                      <a :class="toggleClass(checkedEmployessList,item)&&'active'" @click="toChecked(checkedEmployessList,item)" :title="item.departmentNames">
                          <img src="/static/admin/images/icon_people.png">{{item.employeeName}}</a>
                  </li>
              </ul>
              <ul style="height: 360px;" v-if="activeName==2">
                  <li v-for="(item,index) in roles" :key="index+'c'" class="check_box">
                      <a :class="toggleClass(checkedRoleList,item,'roleId')&&'active'" @click="toChecked(checkedRoleList,item,'roleId')" :title="item.description">
                          <img src="/static/admin/images/icon_role.png">{{item.roleName}}</a>
                  </li>
              </ul>
          </div>
          <div class="has_selected l">
              <p class="clear">已选（{{total}}）
                  <a @click="delList">清空</a>
              </p>
              <ul>
                  <li v-for="(item,index) in checkedRoleList" :key="index+'e'">
                      <img src="/static/admin/images/icon_role.png">
                      <span>{{item.roleName}}</span>
                      <img src="/static/admin/images/cancel.png" @click="removeEle(checkedRoleList,item,'roleId')">
                  </li>
                  <template v-if="isDepartment">
                    <li v-for="(item,index) in checkedDepartmentList" :key="index+'e1'">
                      <img src="/static/admin/images/icon_file.png">
                      <span>{{item.departmentName}}</span>
                      <img src="/static/admin/images/cancel.png" @click="removeEle(checkedDepartmentList,item)">
                  </li>
                  </template>
                  <li v-for="(item,index) in checkedEmployessList" :key="index+'e2'">
                      <img src="/static/admin/images/icon_people.png">
                      <span>{{item.employeeName}}</span>
                      <img src="/static/admin/images/cancel.png" @click="removeEle(checkedEmployessList,item)">
                  </li>
              </ul>
          </div>
      </div>
      <span slot="footer" class="dialog-footer">
          <el-button @click="$emit('update:visible',false)">取 消</el-button>
          <el-button type="primary" @click="saveDialog">确 定</el-button>
      </span>
  </el-dialog>
</template>
<template id="errordialog">
  <el-dialog title="提示" :visible.sync="visibleDialog">
    <div class="ant-confirm-body">
      <i class="anticon anticon-close-circle" style="color: #f00;"></i>
      <span class="ant-confirm-title">当前无法发布</span>
      <div class="ant-confirm-content">
        <div>
          <p class="error-modal-desc">以下内容不完善，需进行修改</p>
          <div class="error-modal-list">
            <div class="error-modal-item" v-for="(item,index) in list" :key="index">
              <div class="error-modal-item-label">流程设计</div>
              <div class="error-modal-item-content">{{item.name}} 未选择{{item.type}}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <span slot="footer" class="dialog-footer">
      <el-button @click="visibleDialog = false">我知道了</el-button>
      <el-button type="primary" @click="visibleDialog = false">前往修改</el-button>
    </span>
  </el-dialog>
</template>
</body>
</html>