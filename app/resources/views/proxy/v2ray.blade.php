<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>V2RAY节点</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/web-static/layuiadmin/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/web-static/layuiadmin/style/admin.css" media="all">
</head>
<body>

<div class="layui-card layadmin-header">

</div>

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">V2ray节点</div>
                <div class="layui-card-body">
                    <table class="layui-hide" id="table" lay-filter="table"></table>

                    <script type="text/html" id="toolbar-header">
                        <div class="layui-btn-group">
                            <button class="layui-btn layui-btn-sm" lay-event="del">删除节点</button>
                            <button class="layui-btn layui-btn-sm" lay-event="add">添加节点</button>
                        </div>
                    </script>

                    <script type="text/html" id="toolbar_inner_option">
                        <div class="layui-btn-group">
                            <a class="layui-btn layui-btn-success layui-btn-xs" lay-event="app"><i
                                        class="layui-icon layui-icon-note"></i>应用</a>

                            <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i
                                        class="layui-icon layui-icon-edit"></i>编辑</a>
                        </div>
                    </script>
                    <script type="text/html" id="statusTpl">
                        @{{#  if(d.status == 0){ }}
                            <button class="layui-btn layui-btn-success layui-btn-xs">空闲中</button>
                        @{{#  } else if(d.status == 1){ }}
                            <button class="layui-btn layui-btn-danger layui-btn-xs">使用中</button>
                        @{{#  } }}

                    </script>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="/web-static/js/jquery-1.10.1.min.js"></script>
<script src="/web-static/layuiadmin/layui/layui.js"></script>
<script src="/web-static/js/jquery.base64.js"></script>
<script>
    layui.config({
        base: '/web-static/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'table'], function(){
        var admin = layui.admin
            ,table = layui.table;

        table.render({
            elem: '#table'
            ,url: '/proxy/v2ray/data'
            ,toolbar: '#toolbar-header'
            ,title: '用户数据表'
            ,cols: [[
                {type: 'checkbox', fixed: 'left'}
                ,{field:'id', title:'ID', width:40, fixed: 'left'}
                ,{field:'country', title:'地区', width:80,align:'center'}
                ,{field:'vnext_address', title:'地址', align:'center'}
                ,{field:'vnext_port', title:'端口', width:80 , align:'center'}
                ,{field:'user_id', title:'UID',  align:'center'}
                ,{field:'user_aid', title:'AID', width:60 , align:'center'}
                ,{field:'stream_network', title:'协议', width:80, align:'center'}
                ,{field:'stream_network_security', title:'安全', width:80, align:'center'}
                ,{field:'ws_path', title:'目录', width:100,  align:'center'}
                ,{field:'status', title:'状态', width:80,  templet: '#statusTpl', align:'center'}
                ,{fixed: 'right', title:'操作', toolbar: '#toolbar_inner_option', width:150,align:'center'}
            ]]
            ,page: true
        });

        //头工具栏事件
        table.on('toolbar(table)', function(obj){
            var checkStatus = table.checkStatus(obj.config.id);
            switch(obj.event){
                case 'del':
                    var checkStatus = table.checkStatus(obj.config.id);
                    var data = checkStatus.data;
                    var field = JSON.stringify(data);
                    field = $.base64.encode(field);
                    layer.confirm('确定需要删除所选择的项目？', function (index) {
                        //提交数据到后台
                        $.ajax({
                            url: '/proxy/v2ray/delete',
                            type: 'POST',
                            data: {'field':field},
                            success: function (data) {
                                layer.msg(data.msg);
                            },
                            error: function (data) {
                                layer.msg('服务器内部错误!');
                            }
                        });
                        table.reload('table');
                        layer.close(index); //关闭弹层
                    });
                    break;
                case 'add':
                    layer.open({
                        type: 2
                        , title: '添加V2ray节点'
                        , content: "/proxy/v2ray/create"
                        , area: ['550px', '500px']
                        , btn: ['确定', '取消']
                        , yes: function (index, layero) {
                            var iframeWindow = window['layui-layer-iframe' + index]
                                , submit = layero.find('iframe').contents().find("#submit");
                            //监听提交
                            iframeWindow.layui.form.on('submit(submit)', function (data) {
                                var field = data.field; //获取提交的字段
                                //提交数据到后台
                                $.ajax({
                                    url: '/proxy/v2ray/store',
                                    type: 'POST',
                                    data: field,
                                    success: function (data) {
                                        layer.msg(data.msg);
                                    },
                                    error: function (data) {
                                        layer.msg('服务器内部错误!');
                                    }
                                });
                                table.reload('table');
                                layer.close(index); //关闭弹层
                            });
                            submit.trigger('click');
                        }
                    });
                    break;
            };
        });

        //监听行工具事件
        table.on('tool(table)', function(obj){
            var data = obj.data;
            var row = obj.data;
            if(obj.event === 'app'){
                layer.confirm('确定使用当前节点作为代理？', function(index){
                    var tr = $(obj.tr);
                    $.ajax({
                        url: '/proxy/v2ray/app?id=' + row.id,
                        type: 'POST',
                        data: {},
                        success: function (data) {
                            layer.msg(data.msg);
                            table.reload('table'); //数据刷新
                            layer.close(index); //关闭弹层
                        },
                        error: function (data) {
                            layer.msg('服务器内部错误!');
                        }
                    });
                });
            } else if(obj.event === 'edit'){
                var tr = $(obj.tr);
                layer.open({
                    type: 2
                    , title: '编辑V2ray节点'
                    , content: "/proxy/v2ray/edit?id=" + row.id
                    , area: ['550px', '500px']
                    , btn: ['确定', '取消']
                    , yes: function (index, layero) {
                        var iframeWindow = window['layui-layer-iframe' + index]
                            , submit = layero.find('iframe').contents().find("#submit");

                        //监听提交
                        iframeWindow.layui.form.on('submit(submit)', function (data) {
                            var field = data.field;
                            //提交 Ajax 成功后，静态更新表格中的数据
                            $.ajax({
                                url: "/proxy/v2ray/update?id=" + row.id,
                                type: 'POST',
                                data: field,
                                success: function (data) {
                                    layer.msg(data.msg);
                                },
                                error: function (data) {
                                    layer.msg('服务器内部错误!');
                                }
                            });
                            table.reload('table'); //数据刷新
                            layer.close(index); //关闭弹层
                        });

                        submit.trigger('click');
                    }
                    , success: function (layero, index) {

                    }
                })
            }
        });

    });
</script>
</body>
</html>