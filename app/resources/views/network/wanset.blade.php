<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>外网设置</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<link rel="stylesheet" href="/web-static/layuiadmin/layui/css/layui.css" media="all">
	<link rel="stylesheet" href="/web-static/layuiadmin/style/admin.css" media="all">
</head>
<body>

<div class="layui-fluid">
	<div class="layui-row layui-col-space15">
		<div class="layui-col-md12">
			<div class="layui-card">
				<div class="layui-card-header">外网设置</div>
				<div class="layui-card-body" pad15>

					<div class="layui-form" lay-filter="" style="padding-left: 10px;">
						<div class="layui-form-item">
							<label class="layui-form-label">联网方式</label>
							<div class="layui-input-inline">
								<select name="role" lay-verify="" lay-filter="selectRole">
									@if($config->wan_mode=='dhcp')
										<option value="1" selected>DHCP</option>
									@else
										<option value="1">DHCP</option>
									@endif
									@if($config->wan_mode=='static')
										<option value="2" selected>静态IP</option>
									@else
										<option value="2" >静态IP</option>
									@endif
									@if($config->wan_mode=='pppoe')
										<option value="3" selected>PPPOE</option>
									@else
										<option value="3" >PPPOE</option>
									@endif
								</select>
							</div>
						</div>
					</div>
					<div class="layui-tab-content">
						<!--dhcp set-->
						@if($config->wan_mode=='dhcp')
							<div class="layui-tab-item layui-tab-item1 layui-show">
						@else
							<div class="layui-tab-item layui-tab-item1">
						@endif
							<div class="layui-form-item">
								<label class="layui-form-label">IP</label>
								<div class="layui-input-inline">
									<input type="text" name="dhcp_ip" id = "dhcp_ip"class="layui-input" value="0.0.0.0" disabled>
								</div>

							</div>
							<div class="layui-form-item">
								<label class="layui-form-label">子网掩码</label>
								<div class="layui-input-inline">
									<input type="text" name="dhcp_mask" id="dhcp_mask" class="layui-input" value="255.255.255.0" disabled>
								</div>
							</div>

							<div class="layui-form-item">
								<label class="layui-form-label">网关</label>
								<div class="layui-input-inline">
									<input type="text" name="dhcp_gateway" id ="dhcp_gateway" class="layui-input" value="0.0.0.0" disabled>
								</div>
							</div>

							<div class="layui-form-item">
								<div class="layui-input-block">
									<button class="layui-btn layui-btn-normal" lay-submit lay-filter="dhcp_reconnect">更新IP</button>
									<button class="layui-btn" lay-submit lay-filter="set_dhcp">修改配置</button>
								</div>
							</div>
						</div>
						<!--静态-->
						@if($config->wan_mode=='static')
							<div class="layui-tab-item layui-tab-item2 layui-show">
						@else
							<div class="layui-tab-item layui-tab-item2">
						@endif
							<div class="layui-form-item">
								<label class="layui-form-label">IP地址</label>
								<div class="layui-input-inline">
									<input type="text" name="static_ip" id="static_ip" class="layui-input" value="{{$config->wan_ip}}" >
								</div>

							</div>
							<div class="layui-form-item">
								<label class="layui-form-label">子网掩码</label>
								<div class="layui-input-inline">
									<input type="text" name="static_mask" id="static_mask" class="layui-input" value="{{$config->wan_netmask}}">
								</div>
							</div>

							<div class="layui-form-item">
								<label class="layui-form-label">网关</label>
								<div class="layui-input-inline">
									<input type="text" name="static_gateway" id="static_gateway" class="layui-input" value="{{$config->wan_gateway}}">
								</div>
							</div>
							
							<div class="layui-form-item">
								<label class="layui-form-label">DNS1</label>
								<div class="layui-input-inline">
									<input type="text" name="static_dns1" id="static_dns1" class="layui-input" value="{{$config->wan_dns1}}">
								</div>
							</div>
							
							<div class="layui-form-item">
								<label class="layui-form-label">DNS2</label>
								<div class="layui-input-inline">
									<input type="text" name="static_dns2" id="static_dns2" class="layui-input" value="{{$config->wan_dns2}}">
								</div>
								<div class="layui-form-mid layui-word-aux">可以留空</div>
							</div>

							<div class="layui-form-item">
								<div class="layui-input-block">
									<button class="layui-btn" lay-submit lay-filter="set_static">修改配置</button>
								</div>
							</div>
						</div>
						<!--pppoe-->
						@if($config->wan_mode=='pppoe')
							<div class="layui-tab-item layui-tab-item3 layui-show">
						@else
							<div class="layui-tab-item layui-tab-item3">
						@endif
							<div class="layui-form-item">
								<label class="layui-form-label">账号</label>
								<div class="layui-input-inline">
									<input type="text" name="pppoe_username" id="pppoe_username" class="layui-input" value="{{$config->ppp_name}}" >
								</div>

							</div>
							<div class="layui-form-item">
								<label class="layui-form-label">密码</label>
								<div class="layui-input-inline">
									<input type="text" name="pppoe_pwd" id="pppoe_pwd" class="layui-input" value="{{$config->ppp_password}}" >
								</div>
							</div>

							<div class="layui-form-item">
								<label class="layui-form-label">DNS1</label>
								<div class="layui-input-inline">
									<input type="text" name="pppoe_dns1" id="pppoe_dns1" class="layui-input" value="">
								</div>
							</div>
							
							<div class="layui-form-item">
								<label class="layui-form-label">DNS2</label>
								<div class="layui-input-inline">
									<input type="text" name="pppoe_dns2" id="pppoe_dns2" class="layui-input" value="">
								</div>
								<div class="layui-form-mid layui-word-aux">可以留空</div>
							</div>

							<div class="layui-form-item">
								<div class="layui-input-block">
									<button class="layui-btn" lay-submit lay-filter="set_pppoe">修改配置</button>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<script src="/web-static/layuiadmin/layui/layui.js"></script>
<script>
	layui.config({
		base: '/web-static/layuiadmin/' //静态资源所在路径
	}).extend({
		index: 'lib/index' //主入口模块
	}).use(['index', 'set'],function () {
		var $ = layui.$
				,form = layui.form;
		form.render();

		//联网设置--重新获得dhcp地址
		form.on('submit(dhcp_reconnect)', function(obj){
			$.ajax({
				type: "POST",
				url: "/network/set_dhcp_reconnect",
				data: {},
				success: function (res) {
					if (res.code == 200) {
						layer.msg(res.msg, {
							offset: '15px'
							,icon: 1
						});
					} else {
						layer.msg(res.msg, {
							offset: '15px'
							,icon: 2
						});
					}
				},
				error:function () {
					layer.msg('提交数据失败！', {
						offset: '15px'
						,icon: 2
					});
				}
			});
		});

		//联网设置--设置联网模式为dhcp
		form.on('submit(set_dhcp)', function(obj){
			$.ajax({
				type: "POST",
				url: "/network/set_dhcp",
				data: {},
				success: function (res) {
					if (res.code == 200) {
						layer.msg(res.msg, {
							offset: '15px'
							,icon: 1
						});
					} else {
						layer.msg(res.msg, {
							offset: '15px'
							,icon: 2
						});
					}
				},
				error:function () {
					layer.msg('提交数据失败！', {
						offset: '15px'
						,icon: 2
					});
				}
			});
		});

		//联网设置--设置联网模式为static
		form.on('submit(set_static)', function(obj){
			var static_ip = $('#static_ip').val();
			var static_mask = $('#static_mask').val();
			var static_gateway=$('#static_gateway').val();
			var static_dns1 = $('#static_dns1').val();
			var static_dns2 =$('#static_dns2').val;
			$.ajax({
				type: "POST",
				url: "/network/set_static",
				data: {'ip':static_ip,'mask':static_mask,'gateway':static_gateway,'dns1':static_dns1,'dns2':static_dns2},
				success: function (res) {
					if (res.code == 200) {
						layer.msg(res.msg, {
							offset: '15px'
							,icon: 1
						});
					} else {
						layer.msg(res.msg, {
							offset: '15px'
							,icon: 2
						});
					}
				},
				error:function () {
					layer.msg('提交数据失败！', {
						offset: '15px'
						,icon: 2
					});
				}
			});
		});

		//联网设置--设置联网模式为pppoe
		form.on('submit(set_pppoe)', function(obj){
			var pppoe_username = $('#pppoe_username').val();
			var pppoe_pwd = $('#pppoe_pwd').val();
			var pppoe_dns1=$('#pppoe_dns1').val();
			var pppoe_dns2 = $('#pppoe_dns2').val();

			$.ajax({
				type: "POST",
				url: "/network/set_pppoe",
				data: {'username':pppoe_username,'pwd':pppoe_pwd,'dns1':pppoe_dns1,'dns2':pppoe_dns2},
				success: function (res) {
					if (res.code == 200) {
						layer.msg(res.msg, {
							offset: '15px'
							,icon: 1
						});
					} else {
						layer.msg(res.msg, {
							offset: '15px'
							,icon: 2
						});
					}
				},
				error:function () {
					layer.msg('提交数据失败！', {
						offset: '15px'
						,icon: 2
					});
				}
			});
		});


		//根据选择联网模式执行显示的事件
		form.on('select(selectRole)',function(data){
			var select = data.value;
			$('.layui-tab-item').removeClass('layui-show');
			$('.layui-tab-item'+select).addClass('layui-show');
		});

	});
	
</script>
</body>
</html>