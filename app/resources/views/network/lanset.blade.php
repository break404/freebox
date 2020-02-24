

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>内网设置</title>
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
          <div class="layui-card-header">内网设置</div>
          <div class="layui-card-body" pad15>
            
            <div class="layui-form" lay-filter="">
              <div class="layui-form-item">
                <label class="layui-form-label">IP</label>
                <div class="layui-input-inline">
                  <input type="text" name="ip" id="ip" lay-verify="required" class="layui-input" value="{{$config->lan_ip}}">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">子网掩码</label>
                <div class="layui-input-inline">
                  <input type="text" name="mask" id="mask" lay-verify="required"  class="layui-input" value="{{$config->lan_netmask}}">
                </div>

              </div>

              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit lay-filter="lan_set">修改配置</button>
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
    form.on('submit(lan_set)', function(obj){
      var ip = $('#ip').val();
      var mask = $('#mask').val();
      $.ajax({
        type: "GET",
        url: "/network/lanset?act=set",
        data: {"ip": ip, "mask": mask},
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