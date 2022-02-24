define(["jquery", "easy-admin", "vue", "axios", "vuex", "ELEMENT", "autocomplete"], function ($, ea, Vue, axios, Vuex, element) {

	var autocomplete = layui.autocomplete;
	var transfer = layui.transfer;
	
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'system.step/index',
        add_url: 'system.step/add',
        edit_url: 'system.step/edit',
        delete_url: 'system.step/delete',
        export_url: 'system.step/export',
        modify_url: 'system.step/modify',
        stepnode_url: 'system.step/addnode',
        form_url: 'system.step/form',
        condition_url: 'system.step/addcondition',
        role_url: 'system.auth/roles',
        employee_url: 'system.admin/json',
        department_url: 'system.department/json'
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                skin: 'row ',
                cols: [[
                    {type: 'checkbox'},                    {field: 'id', width: 60, title: 'id'},                    {field: 'number', width: 260, title: '流程编号'},                    {field: 'title', width: 260, title: '流程名称'},                    {field: 'model', width: 260, title: '应用模块'},                    {field: 'status', title: '状态', width: 85, search: 'select', selectList: {0: '禁用', 1: '启用'}, templet: ea.table.switch},                    {field: 'create_time', minWidth: 250, title: '创建时间'},                    {
                    	width: 350,
                        title: '操作',
                        templet: ea.table.tool,
                        operat: [
                            'edit',
                            [{
                                text: '表单设计',
                                url: init.form_url,
                                method: 'open',
                                auth: 'form',
                                class: 'layui-btn layui-btn-warm layui-btn-xs',
                            }],
                            [{
                                text: '流程设计',
                                url: init.stepnode_url,
                                method: 'open',
                                extend: 'data-full="true"',
                                auth: 'addnode',
                                class: 'layui-btn layui-btn-normal layui-btn-xs',
                            }],
                            'delete'
                        ]
                    },
                ]],
            });

            ea.listen();
        },
        add: function () {
        	autocomplete.render({
                elem: $('#model')[0],
                url: ea.url('system.menu/getMenuTips?type=1'),
                template_val: '{{d.node}}',
                template_txt: '{{d.node}} <span class=\'layui-badge layui-bg-gray\'>{{d.title}}</span>',
                onselect: function (resp) {
                }
            });
            ea.listen();
        },
        edit: function () {
        	autocomplete.render({
                elem: $('#model')[0],
                url: ea.url('system.menu/getMenuTips?type=1'),
                template_val: '{{d.node}}',
                template_txt: '{{d.node}} <span class=\'layui-badge layui-bg-gray\'>{{d.title}}</span>',
                onselect: function (resp) {
                }
            });
            ea.listen();
        },
        form: function(){
        	var tra = transfer.render({
        		elem: "#test",
        		title: ['候选条件','已选条件'],
        		data: JSON.parse(condotion),
        		height: 300,
        		value: JSON.parse(allowcolum)
        	})
        	
        	var app = new Vue({
                el: '#app',
                data: {
                    data: ''
                },
                methods: {
                	getUrlKey(name,url){
          			　　return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(url) || [, ""])[1].replace(/\+/g, '%20')) || null
                	},
                	saveSet() {
                		var getData = tra.getData('test'); //获取右侧数据
            			axios.post("../"+init.form_url, {
            				data: JSON.stringify(getData),
            				id: this.getUrlKey("id",window.location.href),
            			}).then(res => {
            			    if (res.data.code == 1) {
            			    	ea.msg.success(res.data.msg);
            			    }else{
            			    	ea.msg.error(res.data.msg);
            			    }
            			})
            		},
                }
                
        	})

        	ea.listen();
        },
        addnode: function (){
        	Vue.use(element)
        	Vue.use(Vuex)
        	let mixins={
        		data(){
        		    return {
        		      visibleDialog: false,
        		      searchVal: "",
        		      activeName: "1",
        		      departments: {},
        		      roles: [],
        		    }
        		  },
        		  methods:{
        		    getRoleList() {
        		      axios.get('/'+init.role_url).then(res => {
        		          this.roles = res.data;
        		      })
        		    },
        		    getDepartmentList(pid = 1) {
        		        axios.get('/'+init.department_url+'?pid='+pid).then(res => {
        		            this.departments = res.data;
        		        })
        		    },
        		    getDebounceData(event, type = 1) {
        		      this.debounce(function () {
        		          if (event.target.value) {
        		              if (type == 1) {
        		                  this.departments.childDepartments = [];
        		                  axios.get('/'+init.employee_url+'?searchName='+event.target.value+'&page=1&limit=30').then(res => {
        		                      this.departments.employees = res.data
        		                  })
        		              } else {
        		                  axios.get('/'+init.employee_url+'?searchName='+event.target.value+'&page=1&limit=30').then(res => {
        		                      this.roles = res.data
        		                  })
        		              }
        		          } else {
        		              type == 1 ? this.getDepartmentList() : this.getRoleList();
        		          }
        		      }.bind(this))()
        		    },
        		    timer: "",
        		    debounce(fn, delay = 500) {
        		        var _this = this;
        		        return function(arg) {
        		            //获取函数的作用域和变量
        		            let that = this;
        		            let args = arg;
        		            clearTimeout(_this.timer) // 清除定时器
        		            _this.timer = setTimeout(function() {
        		                fn.call(that, args)
        		            }, delay)
        		        }
        		    },
        		    setCookie(val) { //cookie设置[{key:value}]、获取key、清除['key1','key2']
        		        for (var i = 0, len = val.length; i < len; i++) {
        		            for (var key in val[i]) {
        		                document.cookie = key + '=' + encodeURIComponent(val[i][key]) + "; path=/";
        		            }
        		        }
        		    },
        		    getCookie(name) {
        		        var strCookie = document.cookie;
        		        var arrCookie = strCookie.split("; ");
        		        for (var i = 0, len = arrCookie.length; i < len; i++) {
        		            var arr = arrCookie[i].split("=");
        		            if (name == arr[0]) {
        		                return decodeURIComponent(arr[1]);
        		            }
        		        }
        		    },
        		    clearCookie(name) {
        		        var myDate = new Date();
        		        myDate.setTime(-1000); //设置时间    
        		        for (var i = 0, len = name.length; i < len; i++) {
        		            document.cookie = "" + name[i] + "=''; path=/; expires=" + myDate.toGMTString();
        		        }
        		    },
        		    arrToStr(arr) {
        		        if (arr) {
        		            return arr.map(item => { return item.name }).toString()
        		        }
        		    },
        		    toggleClass(arr, elem, key = 'id') {
        		        return arr.some(item => { return item[key] == elem[key] });
        		    },
        		    toChecked(arr, elem, key = 'id') {
        		        var isIncludes = this.toggleClass(arr, elem, key);
        		        !isIncludes ? arr.push(elem) : this.removeEle(arr, elem, key);
        		    },
        		    removeEle(arr, elem, key = 'id') {
        		        var includesIndex;
        		        arr.map((item, index) => {
        		            if (item[key] == elem[key]) {
        		                includesIndex = index
        		            }
        		        });
        		        arr.splice(includesIndex, 1);
        		    },
        		    setApproverStr(nodeConfig) {
        		        if (nodeConfig.settype == 1) {
        		            if (nodeConfig.nodeUserList.length == 1) {
        		                return nodeConfig.nodeUserList[0].name
        		            } else if (nodeConfig.nodeUserList.length > 1) {
        		                if (nodeConfig.examineMode == 1) {
        		                    return this.arrToStr(nodeConfig.nodeUserList)
        		                } else if (nodeConfig.examineMode == 2) {
        		                    return nodeConfig.nodeUserList.length + "人会签"
        		                }
        		            }
        		        } else if (nodeConfig.settype == 2) {
        		            let level = nodeConfig.directorLevel == 1 ? '直接主管' : '第' + nodeConfig.directorLevel + '级主管'
        		            if (nodeConfig.examineMode == 1) {
        		                return level
        		            } else if (nodeConfig.examineMode == 2) {
        		                return level + "会签"
        		            }
        		        } else if (nodeConfig.settype == 4) {
        		            if (nodeConfig.selectRange == 1) {
        		                return "发起人自选"
        		            } else {
        		                if (nodeConfig.nodeUserList.length > 0) {
        		                    if (nodeConfig.selectRange == 2) {
        		                        return "发起人自选"
        		                    } else {
        		                        return '发起人从' + nodeConfig.nodeUserList[0].name + '中自选'
        		                    }
        		                } else {
        		                    return "";
        		                }
        		            }
        		        } else if (nodeConfig.settype == 5) {
        		            return "发起人自己"
        		        } else if (nodeConfig.settype == 7) {
        		            return '从直接主管到通讯录中级别最高的第' + nodeConfig.examineEndDirectorLevel + '个层级主管'
        		        }
        		    },
        		    dealStr(str, obj) {
        		        let arr = [];
        		        let list = str.split(",");
        		        for (var elem in obj) {
        		            list.map(item => {
        		                if (item == elem) {
        		                    arr.push(obj[elem].value)
        		                }
        		            })
        		        }
        		        return arr.join("或")
        		    },  
        		    conditionStr(nodeConfig, index) {
        		        var { conditionList, nodeUserList } = nodeConfig.conditionNodes[index];
        		        if (conditionList.length == 0) {
        		            return (index == nodeConfig.conditionNodes.length - 1) && nodeConfig.conditionNodes[0].conditionList.length != 0 ? '其他条件进入此流程' : '请设置条件'
        		        } else {
        		            let str = ""
        		            for (var i = 0; i < conditionList.length; i++) {
        		                var { columnId, columnType, showType, showName, optType, zdy1, opt1, zdy2, opt2, fixedDownBoxValue } = conditionList[i];
        		                if (columnId == 0) {
        		                    if (nodeUserList.length != 0) {
        		                        str += '发起人属于：'
        		                        str += nodeUserList.map(item => { return item.name }).join("或") + " 并且 "
        		                    }
        		                }
        		                if (columnType == "String" && showType == "2") {
        		                	if (zdy1) {
        		                        var optTypeStr = ["","=", "like"][optType]
        		                        str += `${showName} ${optTypeStr} ${zdy1} 并且 `
        		                    }
        		                }
        		                if (columnType == "String" && showType == "3") {
        		                    if (zdy1) {
        		                        str += showName + '属于：' + this.dealStr(zdy1, JSON.parse(fixedDownBoxValue)) + " 并且 "
        		                    }
        		                }
        		                if (columnType == "Double") {
        		                    if (optType != 6 && zdy1) {
        		                        var optTypeStr = ["", "<", ">", "≤", "=", "≥"][optType]
        		                        str += `${showName} ${optTypeStr} ${zdy1} 并且 `
        		                    } else if (optType == 6 && zdy1 && zdy2) {
        		                        str += `${zdy1} ${opt1} ${showName} ${opt2} ${zdy2} 并且 `
        		                    }
        		                }
        		            }
        		            return str ? str.substring(0, str.length - 4) : '请设置条件'
        		        }
        		    },
        		    copyerStr(nodeConfig) {
        		        if (nodeConfig.nodeUserList.length != 0) {
        		            return this.arrToStr(nodeConfig.nodeUserList)
        		        } else {
        		            if (nodeConfig.ccSelfSelectFlag == 1) {
        		                return "发起人自选"
        		            }
        		        }
        		    }, 
        		    toggleStrClass(item, key) {
        		        let a = item.zdy1 ? item.zdy1.split(",") : []
        		        return a.some(item => { return item == key });
        		    },
        		}
        	}
        	const store=new Vuex.Store({
        		state: {
        			promoterDrawer: false,
        	        flowPermission: [],
        	        approverDrawer: false,
        	        approverConfig:{},
        	        copyerDrawer: false,
        	        copyerConfig:{},
        	        conditionDrawer: false,
        	        conditionsConfig:{
        	            conditionNodes: [],
        	        },
        	    },
        	    mutations: {
        	        updatePromoter(status,promoterDrawer){
        	            status.promoterDrawer = promoterDrawer
        	        },
        	        updateFlowPermission(status,flowPermission){
        	            status.flowPermission = flowPermission
        	        },
        	        updateApprover(status,approverDrawer){
        	            status.approverDrawer = approverDrawer
        	        },
        	        updateApproverConfig(status,approverConfig){
        	            status.approverConfig = approverConfig
        	        },
        	        updateCopyer(status,copyerDrawer){
        	            status.copyerDrawer = copyerDrawer
        	        },
        	        updateCopyerConfig(status,copyerConfig){
        	            status.copyerConfig = copyerConfig
        	        },
        	        updateCondition(status,conditionDrawer){
        	            status.conditionDrawer = conditionDrawer
        	        },
        	        updateConditionsConfig(status,conditionsConfig){
        	            status.conditionsConfig = conditionsConfig
        	        },
        	    },
        	    actions: {}
        	})
        	Vue.component('nodewrap', {
        		template: "#nodewrap",
        		mixins: [ mixins],
        		props: ["nodeconfig", "flowpermission", "istried", "tableid"],
        	    data() {
        	        return {
        	            placeholderList: ["发起人", "审核人", "抄送人"],
        	            isInputList: [],
        	            isInput: false,
        	            visible: false,
        	        }
        	    },
        	    mounted() {
        	        if (this.nodeconfig.type == 1) {
        	            this.nodeconfig.error = !this.setApproverStr(this.nodeconfig)
        	        } else if (this.nodeconfig.type == 2) {
        	            this.nodeconfig.error = !this.copyerStr(this.nodeconfig)
        	        } else if (this.nodeconfig.type == 4) {
        	            for (var i = 0; i < this.nodeconfig.conditionNodes.length; i++) {
        	                this.nodeconfig.conditionNodes[i].error = this.conditionStr(this.nodeconfig, i) == "请设置条件" && i != this.nodeconfig.conditionNodes.length - 1
        	            }
        	        }
        	    },
        	    computed: {
        	        flowPermission1() {
        	            return this.$store.state.flowPermission
        	        },
        	        approverConfig1() {
        	            return this.$store.state.approverConfig
        	        },
        	        copyerConfig1() {
        	            return this.$store.state.copyerConfig
        	        },
        	        conditionsConfig1() {
        	            return this.$store.state.conditionsConfig
        	        },
        	    },
        	    watch: {
        	        flowPermission1(data) {
        	            if (data.flag&&data.id === this._uid) {
        	                this.$emit('update:flowpermission',data.value)
        	            }
        	        },
        	        approverConfig1(data) {
        	            if (data.flag&&data.id === this._uid) {
        	                this.$emit('update:nodeconfig',data.value)
        	            }
        	        },
        	        copyerConfig1(data) {
        	            if (data.flag&&data.id === this._uid) {
        	                this.$emit('update:nodeconfig',data.value)
        	            }
        	        },
        	        conditionsConfig1(data) {
        	            if (data.flag&&data.id === this._uid) {
        	                this.$emit('update:nodeconfig',data.value)
        	            }
        	        },
        	    },
        	    methods: {
        	        clickEvent(index) {
        	            if (index || index === 0) {
        	                this.$set(this.isInputList, index, true)
        	            } else {
        	                this.isInput = true;
        	            }
        	        },
        	        blurEvent(index) {
        	            if (index || index === 0) {
        	                this.$set(this.isInputList, index, false)
        	                this.nodeconfig.conditionNodes[index].nodeName = this.nodeconfig.conditionNodes[index].nodeName ? this.nodeconfig.conditionNodes[index].nodeName : "条件"
        	            } else {
        	                this.isInput = false;
        	                this.nodeconfig.nodeName = this.nodeconfig.nodeName ? this.nodeconfig.nodeName : this.placeholderList[this.nodeconfig.type]
        	            }
        	        },
        	        delNode() {
        	            this.$emit("update:nodeconfig", this.nodeconfig.childNode);
        	        },
        	        addTerm() {
        	            let len = this.nodeconfig.conditionNodes.length + 1
        	            this.nodeconfig.conditionNodes.push({
        	                "nodeName": "条件" + len,
        	                "type": 3,
        	                "priorityLevel": len,
        	                "conditionList": [],
        	                "nodeUserList": [],
        	                "childNode": null
        	            });
        	            for (var i = 0; i < this.nodeconfig.conditionNodes.length; i++) {
        	                this.nodeconfig.conditionNodes[i].error = this.conditionStr(this.nodeconfig, i) == "请设置条件" && i != this.nodeconfig.conditionNodes.length - 1
        	            }
        	            this.$emit("update:nodeconfig", this.nodeconfig);
        	        },
        	        delTerm(index) {
        	            this.nodeconfig.conditionNodes.splice(index, 1)
        	            this.nodeconfig.conditionNodes.map((item, index) => {
        	                item.priorityLevel = index + 1
        	                item.nodeName = `条件${index + 1}`
        	            });
        	            for (var i = 0; i < this.nodeconfig.conditionNodes.length; i++) {
        	                this.nodeconfig.conditionNodes[i].error = this.conditionStr(this.nodeconfig, i) == "请设置条件" && i != this.nodeconfig.conditionNodes.length - 1
        	            }
        	            this.$emit("update:nodeconfig", this.nodeconfig);
        	            if (this.nodeconfig.conditionNodes.length == 1) {
        	                if (this.nodeconfig.childNode) {
        	                    if (this.nodeconfig.conditionNodes[0].childNode) {
        	                        this.reData(this.nodeconfig.conditionNodes[0].childNode, this.nodeconfig.childNode)
        	                    } else {
        	                        this.nodeconfig.conditionNodes[0].childNode = this.nodeconfig.childNode
        	                    }
        	                }
        	                this.$emit("update:nodeconfig", this.nodeconfig.conditionNodes[0].childNode);
        	            }
        	        },
        	        reData(data, addData) {
        	            if (!data.childNode) {
        	                data.childNode = addData
        	            } else {
        	                this.reData(data.childNode, addData)
        	            }
        	        },
        	        setPerson(priorityLevel) {
        	            var { type } = this.nodeconfig;
        	            if (type == 0) {
        	                this.$store.commit('updatePromoter',true)
        	                this.$store.commit('updateFlowPermission',{
        	                    value:this.flowpermission,
        	                    flag:false,
        	                    id:this._uid
        	                })
        	            } else if (type == 1) {
        	                this.$store.commit('updateApprover',true)
        	                this.$store.commit('updateApproverConfig',{
        	                    value: {...JSON.parse(JSON.stringify(this.nodeconfig)),
        	                    ...{settype:this.nodeconfig.settype ? this.nodeconfig.settype : 1}},
        	                    flag:false,
        	                    id:this._uid
        	                })
        	            } else if (type == 2) {
        	                this.$store.commit('updateCopyer',true)
        	                this.$store.commit('updateCopyerConfig',{
        	                    value:JSON.parse(JSON.stringify(this.nodeconfig)),
        	                    flag:false,
        	                    id:this._uid
        	                })
        	            } else {
        	                this.$store.commit('updateCondition',true)
        	                this.$store.commit('updateConditionsConfig',{
        	                    value:JSON.parse(JSON.stringify(this.nodeconfig)),
        	                    priorityLevel,
        	                    flag:false,
        	                    id:this._uid
        	                })
        	            }
        	        }
        	    }
        	    
        	})
        	Vue.component('addnode', {
        		template: "#add-node",
        		props: ["childnodep"],
        		data() {
        	        return {
        	            visible: false
        	        }
        	    },
        	    methods: {
        	        addType(type) {
        	            this.visible = false;
        	            if (type != 4) {
        	                var data;
        	                if (type == 1) {
        	                    data = {
        	                        "nodeName": "审核人",
        	                        "error": true,
        	                        "type": 1,
        	                        "settype": 1,
        	                        "selectMode": 0,
        	                        "selectRange": 0,
        	                        "directorLevel": 1,
        	                        "examineMode": 1,
        	                        "noHanderAction": 1,
        	                        "examineEndDirectorLevel": 0,
        	                        "childNode": this.childnodep,
        	                        "nodeUserList": []
        	                    }
        	                } else if (type == 2) {
        	                    data = {
        	                        "nodeName": "抄送人",
        	                        "type": 2,
        	                        "ccSelfSelectFlag": 1,
        	                        "childNode": this.childnodep,
        	                        "nodeUserList": []
        	                    }
        	                }
        	                this.$emit("update:childnodep", data)
        	            } else {
        	                this.$emit("update:childnodep", {
        	                    "nodeName": "路由",
        	                    "type": 4,
        	                    "childNode": null,
        	                    "conditionNodes": [{
        	                        "nodeName": "条件1",
        	                        "error": true,
        	                        "type": 3,
        	                        "priorityLevel": 1,
        	                        "conditionList": [],
        	                        "nodeUserList": [],
        	                        "childNode": this.childnodep,
        	                    }, {
        	                        "nodeName": "条件2",
        	                        "type": 3,
        	                        "priorityLevel": 2,
        	                        "conditionList": [],
        	                        "nodeUserList": [],
        	                        "childNode": null
        	                    }]
        	                })
        	            }
        	        }
        	        
        	    }
        	})
        	//发起人
        	Vue.component('promoterdrawer', {
        		template: "#promoterdrawer",
        		mixins: [ mixins],
        		data() {
        			return {
        				flowPermission: [],
        				promoterVisible: false,
        				checkedList: [],
        			}
        		},
        		computed: {
        			flowPermission1() {
        				return this.$store.state.flowPermission.value
        			}
        		},
        		watch: {
        			flowPermission1(val) {
        				this.flowPermission = val
        			}
        		},
        		methods: {
        			addPromoter() {
        				this.checkedList = this.flowPermission;
        				this.promoterVisible = true;
        			},
        			surePromoter(data) {
        				this.flowPermission = data;
        				this.promoterVisible = false;
        			},
        			savePromoter() {
        				this.$store.commit('updateFlowPermission', {
        					value: this.flowPermission,
        					flag: true,
        					id: this.$store.state.flowPermission.id
        				})
        				this.closeDrawer()
        			},
        			closeDrawer() {
        				this.$store.commit('updatePromoter', false)
        			}
        		}
        	})
        	//审批人
        	Vue.component('approverdrawer', {
        		template: "#approverdrawer",
        		mixins: [ mixins],
        	    props: ['directormaxlevel'],
        	    data(){
        	        return {
        	            approverConfig:{},
        	            approverVisible: false,
        	            approverRoleVisible: false,
        	            approverEmplyessList: [],
        	            checkedRoleList: [],
        	            checkedList:[]
        	        }
        	    },
        	    computed:{
        	        approverConfig1(){
        	            return this.$store.state.approverConfig.value
        	        }
        	    },
        	    watch:{
        	        approverConfig1(val){
        	            this.approverConfig = val;
        	        }
        	    },
        	    methods:{
        	        changeRange() {
        	            this.approverConfig.nodeUserList = [];
        	        },
        	        changeType(val) {
        	            this.approverConfig.nodeUserList = [];
        	            this.approverConfig.examineMode = 1;
        	            this.approverConfig.noHanderAction = 2;
        	            if (val == 2) {
        	                this.approverConfig.directorLevel = 1;
        	            } else if (val == 4) {
        	                this.approverConfig.selectMode = 1;
        	                this.approverConfig.selectRange = 1;
        	            } else if (val == 7) {
        	                this.approverConfig.examineEndDirectorLevel = 1
        	            }
        	        },
        	        addApprover() {
        	            this.approverVisible = true;
        	            this.checkedList = this.approverConfig.nodeUserList
        	        },
        	        addRoleApprover() {
        	            this.approverRoleVisible = true;
        	            this.checkedRoleList = this.approverConfig.nodeUserList
        	        },
        	        sureApprover(data) {
        	            this.approverConfig.nodeUserList = data;
        	            this.approverVisible = false;
        	        },
        	        sureRoleApprover(data){
        	            this.approverConfig.nodeUserList = data;
        	            this.approverRoleVisible = false;
        	        },
        	        saveApprover() {
        	            this.approverConfig.error = !this.setApproverStr(this.approverConfig)
        	            this.$store.commit('updateApproverConfig',{
        	                value:this.approverConfig,
        	                flag:true,
        	                id:this.$store.state.approverConfig.id
        	            })
        	            this.$emit("update:nodeConfig", this.approverConfig);
        	            this.closeDrawer()
        	        },
        	        closeDrawer(){
        	            this.$store.commit('updateApprover',false)
        	        }
        	    }
        	})
        	//抄送人
        	Vue.component('copyerdrawer', {
        		template: "#copyerdrawer",
        		mixins: [ mixins],
        		data(){
        	        return {
        	            copyerConfig: {},
        	            ccSelfSelectFlag: [],
        	            copyerVisible: false,
        	            checkedList: [],
        	        }
        	    },
        	    computed:{
        	        copyerConfig1(){
        	            return this.$store.state.copyerConfig.value
        	        }
        	    },
        	    watch:{
        	        copyerConfig1(val){
        	            this.copyerConfig = val;
        	            this.ccSelfSelectFlag = this.copyerConfig.ccSelfSelectFlag == 0 ? [] : [this.copyerConfig.ccSelfSelectFlag]
        	        }
        	    },
        	    methods:{
        	        addCopyer() {
        	            this.copyerVisible = true;
        	            this.checkedList = this.copyerConfig.nodeUserList
        	        },
        	        sureCopyer(data) {
        	            this.copyerConfig.nodeUserList = data;
        	            this.copyerVisible = false;
        	        },
        	        saveCopyer() {
        	            this.copyerConfig.ccSelfSelectFlag = this.ccSelfSelectFlag.length == 0 ? 0 : 1;
        	            this.copyerConfig.error = !this.copyerStr(this.copyerConfig);
        	            this.$store.commit('updateCopyerConfig',{
        	                value:this.copyerConfig,
        	                flag:true,
        	                id:this.$store.state.copyerConfig.id
        	            })
        	            this.closeDrawer();
        	        },
        	        closeDrawer(){
        	            this.$store.commit('updateCopyer',false)
        	        },     
        	    }
        	})
        	//条件
        	Vue.component('conditiondrawer', {
        		template: "#conditiondrawer",
        		mixins: [ mixins],
        		props: ['tableid'],
        		data(){
        	        return {
        	            conditionVisible: false,
        	            conditionsConfig: {
        	                conditionNodes: [],
        	            },
        	            conditionConfig: {},
        	            PriorityLevel:"",
        	            conditions: [],
        	            conditionList: [],
        	            checkedList: [],
        	            conditionRoleVisible: false,
        	        }
        	    },
        	    computed:{
        	        conditionsConfig1(){
        	            return this.$store.state.conditionsConfig
        	        },
        	    },
        	    watch:{
        	        conditionsConfig1(val){
        	            this.conditionsConfig = val.value;
        	            this.PriorityLevel = val.priorityLevel
        	            this.conditionConfig = val.priorityLevel
        	            ?this.conditionsConfig.conditionNodes[val.priorityLevel - 1]
        	            :{nodeUserList:[],conditionList:[]}
        	        },
        	    },
        	    methods:{
        	        changeOptType(item) {
        	            if (item.optType == 1) {
        	                item.zdy1 = 2;
        	            } else {
        	                item.zdy1 = 1;
        	                item.zdy2 = 2;
        	            }
        	        },
        	        toStrChecked(item, key) {
        	            let a = item.zdy1 ? item.zdy1.split(",") : []
        	            var isIncludes = this.toggleStrClass(item, key);
        	            if (!isIncludes) {
        	                a.push(key)
        	                item.zdy1 = a.toString()
        	            } else {
        	                this.removeStrEle(item, key);
        	            }
        	        },
        	        removeStrEle(item, key) {
        	            let a = item.zdy1 ? item.zdy1.split(",") : []
        	            var includesIndex;
        	            a.map((item, index) => {
        	                if (item == key) {
        	                    includesIndex = index
        	                }
        	            });
        	            a.splice(includesIndex, 1);
        	            item.zdy1 = a.toString()
        	        }, 
        	        addCondition() {
        	            this.conditionList = [];
        	            this.conditionVisible = true;
        	            axios.get('/'+init.condition_url+'?tableId='+this.tableid).then(res => {
        	                this.conditions = res.data;
        	                if (this.conditionConfig.conditionList) {
        	                    for (var i = 0; i < this.conditionConfig.conditionList.length; i++) {
        	                        var { columnId } = this.conditionConfig.conditionList[i]
        	                        if (columnId == 0) {
        	                            this.conditionList.push({ columnId: 0 })
        	                        } else {
        	                            this.conditionList.push(this.conditions.filter(item => { return item.columnId == columnId; })[0])
        	                        }
        	                    }
        	                }
        	            })
        	        },
        	        sureCondition() {
        	            //1.弹窗有，外面无+
        	            //2.弹窗有，外面有不变
        	            for (var i = 0; i < this.conditionList.length; i++) {
        	                var { columnId, showName, columnName, showType, columnType, fixedDownBoxValue } = this.conditionList[i];
        	                if (this.toggleClass(this.conditionConfig.conditionList, this.conditionList[i], "columnId")) {
        	                    continue;
        	                }
        	                if (columnId == 0) {
        	                    this.conditionConfig.nodeUserList == [];
        	                    this.conditionConfig.conditionList.push({
        	                        "type": 1,
        	                        "columnId": columnId,
        	                        "showName": '发起人'
        	                    });
        	                } else {
        	                    if (columnType == "Double") {
        	                        this.conditionConfig.conditionList.push({
        	                            "showType": showType,
        	                            "columnId": columnId,
        	                            "type": 2,
        	                            "showName": showName,
        	                            "optType": "1",
        	                            "zdy1": "2",
        	                            "opt1": "<",
        	                            "zdy2": "",
        	                            "opt2": "<",
        	                            "columnDbname": columnName,
        	                            "columnType": columnType,
        	                        })
        	                    } else if(columnType == "String" && showType == "2"){
        	                    	this.conditionConfig.conditionList.push({
        	                            "showType": showType,
        	                            "columnId": columnId,
        	                            "type": 2,
        	                            "showName": showName,
        	                            "optType": "1",
        	                            "zdy1": "",
        	                            "columnDbname": columnName,
        	                            "columnType": columnType,
        	                        })
        	                    } else if (columnType == "String" && showType == "3") {
        	                        this.conditionConfig.conditionList.push({
        	                            "showType": showType,
        	                            "columnId": columnId,
        	                            "type": 2,
        	                            "showName": showName,
        	                            "zdy1": "",
        	                            "columnDbname": columnName,
        	                            "columnType": columnType,
        	                            "fixedDownBoxValue": fixedDownBoxValue
        	                        })
        	                    }
        	                }
        	            }
        	            ////3.弹窗无，外面有-
        	            for (let i = this.conditionConfig.conditionList.length - 1; i >= 0; i--) {
        	                if (!this.toggleClass(this.conditionList, this.conditionConfig.conditionList[i], "columnId")) {
        	                    this.conditionConfig.conditionList.splice(i, 1);
        	                }
        	            }
        	            this.conditionConfig.conditionList.sort(function (a, b) { return a.columnId - b.columnId; });
        	            this.conditionVisible = false;
        	        },
        	        saveCondition() {
        	            this.$store.commit('updateCondition',false)
        	            var a = this.conditionsConfig.conditionNodes.splice(this.PriorityLevel - 1, 1)//截取旧下标
        	            this.conditionsConfig.conditionNodes.splice(this.conditionConfig.priorityLevel - 1, 0, a[0])//填充新下标
        	            this.conditionsConfig.conditionNodes.map((item, index) => {
        	                item.priorityLevel = index + 1
        	            });
        	            for (var i = 0; i < this.conditionsConfig.conditionNodes.length; i++) {
        	                this.conditionsConfig.conditionNodes[i].error = this.conditionStr(this.conditionsConfig, i) == "请设置条件" && i != this.conditionsConfig.conditionNodes.length - 1
        	            }
        	            this.$store.commit('updateConditionsConfig',{
        	                value:this.conditionsConfig,
        	                flag:true,
        	                id:this.$store.state.conditionsConfig.id
        	            })
        	        },
        	        addConditionRole() {
        	            this.conditionRoleVisible = true;
        	            this.checkedList = this.conditionConfig.nodeUserList
        	        },
        	        sureConditionRole(data) {
        	            this.conditionConfig.nodeUserList = data;
        	            this.conditionRoleVisible = false;
        	        },
        	    }
        	})
        	//选择人员部门
        	Vue.component('employees', {
        		template: "#employees",
        		mixins: [ mixins],
        		props: ['visible', 'data', 'isdepartment'],
        		watch: {
        		    visible(val) {
        		        this.visibleDialog = this.visible
        		        if (val) {
        		            this.getDepartmentList();
        		            this.searchVal = "";
        		            this.checkedEmployessList = this.data.filter(item=>item.type===1).map(({name,targetId})=>({
        		                employeeName: name,
        		                id: targetId
        		            }));
        		            this.checkedDepartmentList = this.data.filter(item=>item.type===3).map(({name,targetId})=>({
        		                departmentName: name,
        		                id: targetId
        		            }));
        		        }
        		    },
        		    visibleDialog(val) {
        		        this.$emit('update:visible', val)
        		    }
        		},
        		computed: {
        		    total() {
        		        return this.checkedDepartmentList.length + this.checkedEmployessList.length
        		    }
        		},
        		data() {
        		    return {
        		        checkedDepartmentList: [],
        		        checkedEmployessList: [],
        		    }
        		},
        		methods: {
        		    saveDialog() {
        		        let checkedList = [...this.checkedDepartmentList, ...this.checkedEmployessList].map(item =>({
        		            type: item.employeeName ? 1 : 3,
        		            targetId: item.id,
        		            name: item.employeeName || item.departmentName
        		        }))
        		        this.$emit('change', checkedList)
        		    },
        		    delList() {
        		        this.checkedDepartmentList = [];
        		        this.checkedEmployessList = []
        		    }
        		}
        	})
        	//角色
        	Vue.component('role', {
        		template: "#role",
        		mixins: [ mixins],
        		props:['visible','data'],
        		watch:{
        		    visible(val){
        		      this.visibleDialog = this.visible
        		      if(val){
        		        this.getRoleList();
        		        this.searchVal = "";
        		        this.checkedRoleList = this.data.map(({name,targetId})=>({
        		          roleName: name,
        		          roleId: targetId
        		        }));
        		      }
        		    },
        		    visibleDialog(val){
        		      this.$emit('update:visible',val)
        		    }
        		},
        		computed:{
        		    total(){
        		      return this.checkedRoleList.length
        		    }
        		},
        		data(){
        		    return {
        		      checkedRoleList: [],
        		    }
        		},
        		methods:{
        		    saveDialog(){
        		      let checkedList = this.checkedRoleList.map(item=>({
        		        type: 2,
        		        targetId: item.roleId,
        		        name: item.roleName
        		      }))
        		      this.$emit('change',checkedList)
        		    },
        		    delList(){
        		      this.checkedRoleList=[];
        		    }
        		}
        	})
        	//角色部门
        	Vue.component('employeesroles', {
        		template: "#employeesroles",
        		mixins: [ mixins],
        		props:['visible','data','isDepartment'],
        		watch:{
        		    visible(val){
        		      this.visibleDialog = this.visible
        		      if(val){
        		        this.activeName = "1";
        		        this.getDepartmentList();
        		        this.searchVal = "";
        		        this.checkedEmployessList = this.data.filter(item=>item.type===1).map(({name,targetId})=>({
        		          employeeName: name,
        		          id: targetId
        		        }));
        		        this.checkedRoleList = this.data.filter(item=>item.type===2).map(({name,targetId})=>({
        		          roleName: name,
        		          roleId: targetId
        		        }));
        		        this.checkedDepartmentList = this.data.filter(item=>item.type===3).map(({name,targetId})=>({
        		          departmentName: name,
        		          id: targetId
        		        }));
        		      }
        		    },
        		    visibleDialog(val){
        		      this.$emit('update:visible',val)
        		    }
        		},
        		computed:{
        		    total(){
        		      return this.checkedEmployessList.length + this.checkedRoleList.length + this.checkedDepartmentList.length
        		    }
        		},
        		data(){
        		    return {
        		      checkedRoleList: [],
        		      checkedEmployessList: [],
        		      checkedDepartmentList: []
        		    }
        		},
        		methods:{
        		    handleClick() {
        		      this.searchVal = "";
        		      this.conditionRoleSearchName = "";
        		      if (this.activeName == 1) {
        		          this.getDepartmentList();
        		      } else {
        		          this.getRoleList();
        		      }
        		    },
        		    saveDialog(){
        		      let checkedList = [...this.checkedRoleList,...this.checkedEmployessList,...this.checkedDepartmentList].map(item=>({
        		        type: item.employeeName?1:(item.roleName?2:3),
        		        targetId: item.id || item.roleId,
        		        name: item.employeeName || item.roleName || item.departmentName
        		      }))
        		      this.$emit('change',checkedList)
        		    },
        		    delList(){
        		      this.checkedEmployessList=[];
        		      this.checkedRoleList=[];
        		      this.checkedDepartmentList=[]
        		    }
        		}
        	})
        	//错误提示
        	Vue.component('errordialog', {
        		template: "#errordialog",
        		props: ["list", "visible"],
        		data(){
        		    return {
        		      visibleDialog: false,
        		    }
    		  	},
    		  	watch:{
    		  		visible(val){
    		  			this.visibleDialog = val
    		  		},
    		  		visibleDialog(val){
    		  			this.$emit('update:visible',val)
    		  		}
    		  	}	
        	})
        	var app = new Vue({
                el: '#app',
                store,
                data() {
            		return {
            			isTried: false,
            			tipList: [],
            			tipVisible: false,
            			nowVal: 100,
            			processConfig: {},
            			nodeConfig: {},
            			workFlowDef: {},
            			flowPermission: [],
            			directorMaxLevel: 0,
            			tableId: "",
            		};
            	},
            	created() {
            		axios.post("../"+init.stepnode_url, {
            			id: this.getUrlKey("id",window.location.href),
            			act: "get"
            		}).then(({data}) => {
            			this.processConfig = data;
            			let {nodeConfig,flowPermission,directorMaxLevel,workFlowDef,tableId} = data;
            			this.nodeConfig = nodeConfig;
            			this.flowPermission = flowPermission;
            			this.directorMaxLevel = directorMaxLevel;
            			this.workFlowDef = workFlowDef;
            			this.tableId = tableId
            		})
            	},
            	methods: {
            		reErr({childNode}) {
            			if (childNode) {
            				let {type,error,nodeName,conditionNodes} = childNode
            				if (type == 1 || type == 2) {
            					if (error) {
            						this.tipList.push({ name: nodeName, type: ["","审核人","抄送人"][type] })
            					}
            					this.reErr(childNode)
            				} else if (type == 3) {
            					this.reErr(childNode)
            				} else if (type == 4) {
            					this.reErr(childNode)
            					for (var i = 0; i < conditionNodes.length; i++) {
            						if (conditionNodes[i].error) {
            							this.tipList.push({ name: conditionNodes[i].nodeName, type: "条件" })
            						}
            						this.reErr(conditionNodes[i])
            					}
            				}
            			} else {
            				childNode = null
            			}
            		},
            		getUrlKey(name,url){
            			　　return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(url) || [, ""])[1].replace(/\+/g, '%20')) || null

            		},
            		saveSet() {
            			this.isTried = true;
            			this.tipList = [];
            			this.reErr(this.nodeConfig);
            			if (this.tipList.length != 0) {
            				this.tipVisible = true;
            				return;
            			}
            			this.processConfig.flowPermission = this.flowPermission;
            			this.processConfig.nodeConfig = this.nodeConfig;
            			this.processConfig.directorMaxLevel = this.directorMaxLevel;
            			this.processConfig.workFlowDef = this.workFlowDef;
            			axios.post("../"+init.stepnode_url, {
            				id: this.getUrlKey("id",window.location.href),
            				act: 'save',
            				processConfig: this.processConfig,
            			}).then(res => {
            			    if (res.data.code == 1) {
            			    	ea.msg.success(res.data.msg, function () {
                                    ea.api.closeCurrentOpen({
                                        refreshTable: init.table_render_id
                                    });
                                });
            			    }else{
            			    	ea.msg.error(res.data.msg, function () {
                                    ea.api.closeCurrentOpen({
                                        refreshTable: init.table_render_id
                                    });
                                });
            			    }
            			})
            		},
            		zoomSize(type) {
            			if (type == 1) {
            				if (this.nowVal == 50) {
            					return;
            				}
            				this.nowVal -= 10;
            			} else {
            				if (this.nowVal == 300) {
            					return;
            				}
            				this.nowVal += 10;
            			}
            		}
            	}
            });
        	
        	ea.listen();
        }
    };
    return Controller;
});