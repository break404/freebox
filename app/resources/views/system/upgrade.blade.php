

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>修改密码</title>
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
          <div class="layui-card-header">在线升级</div>
          <div class="layui-card-body" pad15>

            <div class="layui-form" lay-filter="">

              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit lay-filter="online_upgrade">在线升级</button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">离线升级</div>
          <div class="layui-card-body" pad15>

            <div class="layui-form" lay-filter="">
              <div class="layui-form-item">
                <label class="layui-form-label">升级包</label>
                <div class="layui-input-inline">
                  <input name="avatar" lay-verify="required" id="LAY_avatarSrc" placeholder=""  class="layui-input">
                </div>
                <div class="layui-input-inline layui-btn-container" style="width: auto;">
                  <button type="button" class="layui-btn layui-btn-primary" id="LAY_avatarUpload">
                    <i class="layui-icon">&#xe67c;</i>上传升级包
                  </button>
                </div>
              </div>

              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit lay-filter="offline_upgrade">离线升级</button>
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
    //在线升级
    form.on('submit(online_upgrade)', function(obj){

        $.ajax({
          type: "GET",
          url: "/system/upgrade?act=online",
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

    //离线升级
    form.on('submit(offline_upgrade)', function(obj){
        $.ajax({
          type: "GET",
          url: "/system/upgrade?act=offline",
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

  </script>
</body>
</html>