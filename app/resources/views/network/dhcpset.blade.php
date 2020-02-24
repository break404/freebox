<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>DHCP设置</title>
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
          <div class="layui-card-header">DHCP设置</div>
          <div class="layui-card-body" pad15>
            
            <div class="layui-form" lay-filter="">
              <div class="layui-form-item">
                <label class="layui-form-label">DHCP服务</label>
                <div class="layui-input-inline">
                  @if($config->dhcp_enable==0)
                    <input type="checkbox" name="dhcp_enable"  id="dhcp_enable" lay-skin="switch" lay-text="开启|关闭">
                  @else
                    <input type="checkbox" name="dhcp_enable"  id="dhcp_enable" lay-skin="switch" lay-text="开启|关闭" checked>
                  @endif
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">开始IP</label>
                <div class="layui-input-inline">
                  <input type="text" name="dhcp_start_ip" id="dhcp_start_ip" lay-verify="required" class="layui-input" value="{{$config->dhcp_start}}">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">结束IP</label>
                <div class="layui-input-inline">
                  <input type="text" name="dhcp_end_ip" id="dhcp_end_ip" lay-verify="required" class="layui-input" value="{{$config->dhcp_end}}">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">地址租期</label>
                <div class="layui-input-inline">
                  <select name="dhcp_time" id="dhcp_time" lay-verify="" lay-filter="dhcp_time">
                    @for ($i = 1; $i < 25; $i++)
                      @if($config->dhcp_time ==$i)
                        <option value="{{$i}}" selected>{{$i}}</option>
                      @else
                        <option value="{{$i}}" >{{$i}}</option>
                      @endif
                    @endfor
                  </select>
                </div>
              </div>

              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit lay-filter="dhcp_set">修改配置</button>
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
    //提交
    form.on('submit(dhcp_set)', function(obj){
      var dhcp_enable = $('#dhcp_enable').val();
      var dhcp_start_ip = $('#dhcp_start_ip').val();
      var dhcp_end_ip = $('#dhcp_end_ip').val();
      var dhcp_time = $('#dhcp_time').val();
      $.ajax({
        type: "GET",
        url: "/network/dhcpset?act=set",
        data: {"dhcp_enable": dhcp_enable, "dhcp_start": dhcp_start_ip,'dhcp_end':dhcp_end_ip,'dhcp_time':dhcp_time},
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

  });

  </script>
</body>
</html>