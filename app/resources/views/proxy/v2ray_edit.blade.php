<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>编辑V2RAY节点</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/web-static/layuiadmin/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/web-static/layuiadmin/style/admin.css" media="all">
</head>
<body>

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">

                <div class="layui-card-body" pad15>

                    <div class="layui-form" lay-filter="">
                        <div class="layui-form-item">
                            <label class="layui-form-label">地区</label>
                            <div class="layui-input-inline">
                                <input type="text" name="country" id="country" lay-verify="required" class="layui-input" value="{{$v2ray->country}}">
                            </div>
                            <div class="layui-form-mid layui-word-aux">两位地区字母代码</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">域名</label>
                            <div class="layui-input-inline">
                                <input type="text" name="vnext_address" id="vnext_address" lay-verify="required"  class="layui-input" value="{{$v2ray->vnext_address}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">IP</label>
                            <div class="layui-input-inline">
                                <input type="text" name="vnext_ip" id="vnext_ip" lay-verify="required"  class="layui-input" value="{{$v2ray->vnext_ip}}">
                            </div>
                            <div class="layui-form-mid layui-word-aux">域名解析的IP</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">端口</label>
                            <div class="layui-input-inline">
                                <input type="text" name="vnext_port" id="vnext_port" lay-verify="required"  class="layui-input" value="{{$v2ray->vnext_port}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">ID</label>
                            <div class="layui-input-inline">
                                <input type="text" name="user_id" id="user_id" lay-verify="required"  class="layui-input" value="{{$v2ray->user_id}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">alterId</label>
                            <div class="layui-input-inline">
                                <input type="text" name="user_aid" id="user_aid" lay-verify="required"  class="layui-input" value="{{$v2ray->user_aid}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">协议</label>
                            <div class="layui-input-inline">
                                <select name="stream_network" id="stream_network" lay-verify="" >
                                    <option value="ws" selected>ws</option>
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">安全性</label>
                            <div class="layui-input-inline">
                                <select name="stream_network_security" id="stream_network_security" lay-verify="" >
                                    <option value="tls" selected>tls</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">目录</label>
                            <div class="layui-input-inline">
                                <input type="text" name="ws_path" id="ws_path" lay-verify="required"  class="layui-input" value="{{$v2ray->ws_path}}">
                            </div>
                            <div class="layui-form-mid layui-word-aux">目录结尾必须为/</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">主机头</label>
                            <div class="layui-input-inline">
                                <input type="text" name="ws_header_host" id="ws_header_host" lay-verify="required"  class="layui-input" value="{{$v2ray->ws_header_host}}">
                            </div>
                        </div>


                        <div class="layui-form-item layui-hide">
                            <button class="layui-btn" lay-submit lay-filter="submit" id="submit">提交</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="/web-static/js/jquery-1.10.1.min.js"></script>
<script src="/web-static/layuiadmin/layui/layui.js"></script>

<script>
    layui.config({
        base: '/web-static/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'form', 'element', 'layer', 'table', 'upload', 'laydate'], function () {
        var $ = layui.$
            , form = layui.form;
    })
</script>


</body>
</html>