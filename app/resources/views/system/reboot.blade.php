

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>重启&关闭</title>
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
          <div class="layui-card-header">重启系统</div>
          <div class="layui-card-body" pad15>

            <div class="layui-form" lay-filter="">

              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit lay-filter="reboot">重启系统</button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">关闭系统</div>
          <div class="layui-card-body" pad15>

            <div class="layui-form" lay-filter="">

              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit lay-filter="shutdown">关闭系统</button>
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
    //重新启动
    form.on('submit(reboot)', function(obj){
      layer.confirm('真的需要重启系统吗？', function(index){
        $.ajax({
          type: "GET",
          url: "/system/reboot?act=reboot",
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
          }
        });
        layer.close(index);
      });
    });

    //关闭
    form.on('submit(shutdown)', function(obj){

      layer.confirm('确定需要关闭系统吗？', function(index){
        layer.close(index);
        $.ajax({
          type: "GET",
          url: "/system/reboot?act=shutdown",
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
          }
        });
      });
    });

  });

  </script>
</body>
</html>