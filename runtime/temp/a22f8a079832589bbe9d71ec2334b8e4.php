<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"/www/wwwroot/newm15/public/../application/admin/view/login/login.html";i:1566979796;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>登入 - <?php echo \think\Session::get('config.website_name'); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/admin/css/admin.css" media="all">
    <link rel="stylesheet" href="/static/admin/css/login.css" media="all">
</head>
<body>
    <div class="layadmin-user-login layadmin-user-display-show" style="display: none;">
        <div class="layadmin-user-login-main">
            <div class="layadmin-user-login-box layadmin-user-login-header">
                <h2><?php echo \think\Session::get('config.website_name'); ?></h2>
                <p>Welcome to <?php echo \think\Session::get('config.website_ename'); ?></p>
            </div>

            <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
                <form id="myform">
                    <div class="layui-form-item">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-username"></label>
                        <input type="text" name="mobile" lay-verify="required" placeholder="用户名" class="layui-input">
                    </div>

                    <div class="layui-form-item">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-password"></label>
                        <input type="password" name="password" lay-verify="required" placeholder="密码" class="layui-input">
                    </div>

<!--                     <div class="layui-form-item">
                        <div class="layui-row">
                            <div class="layui-col-xs7">
                                <label class="layadmin-user-login-icon layui-icon layui-icon-vercode"></label>
                                <input type="text" name="vercode" lay-verify="required" placeholder="图形验证码" class="layui-input">
                            </div>
                            <div class="layui-col-xs5">
                                <div style="margin-left: 10px;">
                                    <img src="https://www.oschina.net/action/user/captcha" class="layadmin-user-login-codeimg" id="LAY-user-get-vercode">
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="layui-form-item" style="margin-bottom: 20px;">
                        <input type="checkbox" name="remember" lay-skin="primary" title="记住密码">
                        <a href="#" class="layadmin-user-jump-change layadmin-link" style="margin-top: 7px;">忘记密码？</a>
                    </div>
                </form>

                <div class="layui-form-item">
                    <button class="layui-btn layui-btn-fluid" id="loginbat">登 入</button>
                </div>

               <!-- <div class="layui-trans layui-form-item layadmin-user-login-other">
                    <label>社交账号登入</label>
                    <a href="javascript:;"><i class="layui-icon layui-icon-login-qq"></i></a>
                    <a href="javascript:;"><i class="layui-icon layui-icon-login-wechat"></i></a>
                    <a href="javascript:;"><i class="layui-icon layui-icon-login-weibo"></i></a>
                    <a href="<?php echo url('login/register'); ?>" class="layadmin-user-jump-change layadmin-link">注册帐号</a>
                </div>
            </div>-->
        </div>
        <div class="layui-trans layadmin-user-login-footer">
            <p>© 2018 <a href="#"><?php echo \think\Session::get('config.website_indexurl'); ?></a></p>
            <p>
                <span><a href="#">关于我们</a></span>
                <span><a href="http://wpa.qq.com/msgrd?v=3&uin=153522261&site=qq&menu=yes" target="_blank">联系站长</a></span>
                <span><a href="<?php echo \think\Session::get('config.website_indexurl'); ?>">前往首页</a></span>
            </p>
        </div>
    </div>
    <script src="/static/layui/layui.js"></script>
    <script src="/static/admin/js/jquery.js"></script>
    <script src="/static/layui/layui.js"></script>

    <script>

        layui.use('layer', function(){
            var layer = layui.layer;

            $("#loginbat").click(function(){
                $.ajax({
                    url:"<?php echo url('login/login'); ?>",
                    type:"POST",
                    data:$("#myform").serialize(),
                    success:function(res){
                        layer.msg(res.msg);
                        if(res.code)
                            setTimeout(function(){location.href="<?php echo url('index/index'); ?>"},2000);
                    },error:function(){

                    }
                })
            })
        });              

    </script>
</body>
</html>
