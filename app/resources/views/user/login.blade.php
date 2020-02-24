<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>{{env('APP_NAME')}}</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/web-static/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/web-static/layuiadmin/style/admin.css" media="all">
  <link rel="stylesheet" href="/web-static/layuiadmin/style/login.css" media="all">
</head>
<body>

  <div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">

    <div class="layadmin-user-login-main">
      <div class="layadmin-user-login-box layadmin-user-login-header">
        <h2>登录</h2>

      </div>
      <div class="layadmin-user-login-box layadmin-user-login-body layui-form">

        <div class="layui-form-item">
          <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
          <input type="password" name="password" id="LAY-user-login-password" lay-verify="required" placeholder="密码" class="layui-input">
        </div>

        <div class="layui-form-item" style="margin-bottom: 20px;">

          <a href="#" class="layadmin-user-jump-change layadmin-link" style="margin-top: 7px;">忘记密码？</a>
        </div>
        <div class="layui-form-item">
          <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="LAY-user-login-submit">登 入</button>
        </div>

      </div>
    </div>

    <div class="layui-trans layadmin-user-login-footer">

      <p>© 2019 <a href="http://www.freebox.link/" target="_blank">freebox.link</a></p>

    </div>



  </div>

  <script src="/web-static/layuiadmin/layui/layui.js"></script>
  <script>
layui.config({
    base: '/web-static/layuiadmin/' //静态资源所在路径
  }).extend({
    index: 'lib/index' //主入口模块
  }).use(['index', 'user'], function(){
    var $ = layui.$
    ,form = layui.form;

    form.render();

    //提交
    form.on('submit(LAY-user-login-submit)', function(obj){

        var pwd = $('#LAY-user-login-password').val();
      $.ajax({
        type: "GET",
        url: "/login?check=1",
        data: {"pwd": pwd},
        success: function (res) {
          if (res.code == 200) {
            window.location.href = "/network/index";
          } else {
            layer.msg('密码错误！', {
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