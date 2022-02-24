layui.use(['form', 'layer', 'jquery'], function() {
	 var form = layui.form,
     layer = parent.layer === undefined ? layui.layer : top.layer
    		 $ = layui.jquery;
	//登录按钮
	form.on("submit(login)", function(data) {
		console.info(data.field);
		$.ajax({
			url: "/mobile/login/index",
			async: false,
			type: "POST",
			dataType: 'JSON',
			data: data.field,
			success: function(res) {
				if (res.code == 1) {
					window.location = res.url;
				} else{
					layer.msg(res.msg,{
					    time: 2000
					},function(){
						$('#refreshCaptcha').trigger("click");
					});
				}
					
			}
		})
		return false;
	});
})