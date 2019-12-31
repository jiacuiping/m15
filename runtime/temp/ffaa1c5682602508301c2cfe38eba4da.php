<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"/www/wwwroot/newm15/public/../application/index/view/login/login.html";i:1577343054;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo \think\Session::get('config.website_name'); ?> - 登录</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/index/layuiadmin/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/index/layuiadmin/style/admin.css" media="all">
    <link rel="stylesheet" href="/static/index/layuiadmin/style/iconfont.css" media="all">
    <link rel="stylesheet" href="/static/index/layuiadmin/style/animate.min.css" media="all">
    <link rel="stylesheet" href="/static/index/layuiadmin/style/style.css" media="all"> 
    <script src="/static/index/layuiadmin/layui/layui.js"></script>
    <script src="/static/index/layuiadmin/style/js/wow.min.js"></script>
    <script src="/static/index/layuiadmin/style/js/jquery.min.js"></script>
    <style>
        
        .layui-form-item {
            margin-bottom: 25px;
        }

        .loginbut {
            width: 100%;
            background: #ed830b;
            border-radius: 4px;
            height: 42px;
            line-height: 42px;
            text-align: center;
            color: #fff;
            display: block;
            margin: 18px 0;
            font-size: 16px;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <div id="login">
        <!-- 头部 -->
        <div id="login-top">
            <!-- 导航条 -->
            <div id="login-top-nav" class="clear">
                <div class="login-top-nav">
                    <!-- 左侧 -->
                    <div class="left">
                      <img src="/static/index/layuiadmin/imgs/logo-chengzi.png" alt="">
                      <p>橙子数据<!-- <span>抖音版</span> --></p>
                    </div>
                    <!-- 右侧 -->
                    <div class="right clear">
                        <ul class="login-top-nav-right clear left">
                            <li><a href="#">首页</a></li>
                            <li><a href="#">搜索</a></li>
                            <li><a href="#">排行</a></li>
                            <li><a href="#">监控</a></li>
                        </ul>
                        <!-- 登录注册 -->
                        <div class="right login-top-nav-btn">
                            <a href="#">
                               <button type="button" class="layui-btn layui-btn-sm denglu-btn">登录</button> 
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 文字 -->
            <div id="login-top-content">
                <h1 class="wow bounceInUp">短视频热门视频、商品及账号的数据分析平台</h1>
                <p class="wow bounceInUp">
                    大数据追踪短视频流量趋势，提供热门视频、音乐、爆款商品及优质账号，<br>
                    助力账号内容定位、粉丝增长、粉丝画像优化及流量变现。
                </p>
                <a href="#" class="denglu-btn">立即登录</a>
            </div>

            <!-- 点击登录按钮弹出内容： -->
            <div class="denglu-mask">
                <!-- 扫码登录 -->
                <div class="denglu-content" id="saoma-denglu">
                    <p class="denglu-guan" style="width: 4%;margin-left: 83%;">X</p>
                    <div class="denglu-wrapper">
                        <!-- 两个标题 -->
                        <div class="denglu-nav">
                            <ul class="clear">
                                <li><a href="#" class="login-btn-active" id="saoma">扫码登录</a></li>
                                <li><a href="#" id="tel">手机登录</a></li>
                            </ul>
                        </div>
                        <!-- 扫码登录页面 内容 -->
                        <div class="denglu-content1">
                            <div class="denglu-content-img">


                                <img src="/static/index/layuiadmin/imgs/erweima.png" alt="">


                            </div>
                            <div class="denglu-bottom">
                                <h4>欢迎使用<?php echo \think\Session::get('config.website_name'); ?></h4>
                                <p>使用微信扫一扫登录或注册</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 手机登录 -->
                <div class="shouji-content" id="shouji-denglu" style="display:none">
                    <p class="denglu-guan" style="padding-right: 0">X</p>
                    <div class="shouji-wrapper">
                        <!-- 两个标题 -->
                        <div class="denglu-nav">
                            <ul class="clear">
                                <li><a href="#" id="saoma1">扫码登录</a></li>
                                <li><a href="#" class="login-btn-active" id="tel1">手机登录</a></li>
                            </ul>
                        </div>
                        <!-- 扫码登录页面 内容 -->
                        <div class="denglu-content2" style="margin-top: 65px;">
                            <div class="denglu-content-mima">
                                <!-- 表单 ：手机号码  登录密码 -->
                                <form id="loginForm">
                                    <div class="layui-form-item">
                                        <div class="layui-input-block login-input">
                                            <img src="/static/index/layuiadmin/imgs/login-shouji.png" alt="">
                                            <input type="text" name="mobile" autocomplete="off" placeholder="请输入手机号码" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-input-block  login-input">
                                            <img src="/static/index/layuiadmin/imgs/login-suo.png" alt="">
                                            <input type="password" name="password" autocomplete="off" placeholder="请输入登录密码" class="layui-input">
                                            <!-- <a href="#">忘记密码</a> -->
                                        </div>
                                    </div>
                                    <div class="loginbut">登录</div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 内容 截图 文字 -->
        <div id="login-content">
            <!-- 短视频热门视频及音乐 -->
            <div class="login-content-1 clear">
                <div class="layui-container">
                    <div class="wow bounceInUp layui-col-md6 left login-content-1-left">
                        <img src="/static/index/layuiadmin/imgs/loginly5.png">
                        <h4>短视频热门视频及音乐</h4>
                        <p>快速发现短视频平台最新热点，把握短视频热门趋势，追热点、生产爆款视频快人一步</p>
                        <ul>
                            <li>发现短视频海量实时热门视频</li>
                            <li>发现短视频优质音乐</li>
                            <li>借助热点，融入内容创作，获取更多流量</li>
                        </ul>
                    </div>
                    <div class="wow bounceInUp layui-col-md6 right login-content-1-right">
                        <img src="/static/index/layuiadmin/imgs/login2.png" class="teshu-img">
                    </div>
                </div>
            </div>
            <!-- 短视频爆款电商数据 -->
            <div class="login-content-2 clear">
                <div class="layui-container">
                    <div class="wow bounceInUp layui-col-md6 right login-content-1-left">
                        <img src="/static/index/layuiadmin/imgs/loginly6.png">
                        <h4 style="color: #FF7A16">短视频爆款电商数据</h4>
                        <p>挖掘短视频热卖商品及带货账号，实现精准选品、高转化电商变现。</p>
                        <ul>
                            <li>分析商品销量数据</li>
                            <li>发现热门带货视频</li>
                            <li>挖掘优质带货达人</li>
                        </ul>
                    </div>
                    <div class="wow bounceInUp layui-col-md6 left login-content-1-right">
                        <img src="/static/index/layuiadmin/imgs/login2.png">
                    </div>
                </div>
            </div>
            <!-- 多账号高效运营 -->
            <div class="login-content-1 clear">
                <div class="layui-container">
                    <div class="wow bounceInUp layui-col-md6 left login-content-1-left">
                        <img src="/static/index/layuiadmin/imgs/loginly7.png">
                        <h4 style="color: #3DA9FF">多账号高效运营</h4>
                        <p>支持超200个短视频号日常数据管理，助力企业机构掌握旗下账号数据动向。</p>
                        <ul>
                            <li>实时的新增粉丝、点赞、评论、转发等数据</li>
                            <li>监控作品的热度趋势</li>
                            <li>了解对标账号的数据变化</li>
                        </ul>
                    </div>
                    <div class="wow bounceInUp layui-col-md6 right login-content-1-right">
                        <img src="/static/index/layuiadmin/imgs/login2.png">
                    </div>
                </div>
            </div>
            <!-- 多维度排行榜 -->
            <div class="login-content-2 clear">
                <div class="layui-container">
                    <div class="wow bounceInUp layui-col-md6 right login-content-1-left">
                        <img src="/static/index/layuiadmin/imgs/loginly8.png">
                        <h4 style="color: #52C41A">多维度排行榜</h4>
                        <p>了解所处行业流量趋势，定位账号内容，寻找优质达人</p>
                        <ul>
                            <li>细分34个垂直行业排行榜</li>
                            <li>成长排行榜及时发现优质潜力达人</li>
                            <li>企业榜、成长榜、地区榜…</li>
                        </ul>
                    </div>
                    <div class="wow bounceInUp layui-col-md6 left login-content-1-right">
                        <img src="/static/index/layuiadmin/imgs/login2.png">
                    </div>
                </div>
            </div>
        </div>

        <!-- 更多实用工具 -->
        <div id="login-more" class="clear">
            <div class="layui-container">
                <h3>更多实用工具</h3>
                <div class="login-more-four layui-col-md3">
                    <img src="/static/index/layuiadmin/imgs/loginly1.png">
                    <h4>数据监控</h4>
                    <p>监控视频发布后的粉丝涨粉变化，互动数据变化，更好进行复盘分析</p>
                </div>
                <div class="login-more-four layui-col-md3">
                    <img src="/static/index/layuiadmin/imgs/loginly2.png">
                    <h4>账号及视频查询</h4>
                    <p>600万播主数据和7亿条视频数据，想要什么播主都能查到</p>
                </div>
                <div class="login-more-four layui-col-md3">
                    <img src="/static/index/layuiadmin/imgs/loginly3.png">
                    <h4>粉丝画像</h4>
                    <p>分析发布后的视频观众画像，及时调整，避免泛粉产生，提高账号精准度</p>
                </div>
                <div class="login-more-four layui-col-md3">
                    <img src="/static/index/layuiadmin/imgs/loginly4.png">
                    <h4>视频观众分析</h4>
                    <p>根据视频观众画像和账号粉丝画像，及时调整内容，优化粉丝群体结构</p>
                </div>
            </div>
        </div>

        <!-- 案例分析 -->
        <div id="login-anli">
            <div class="layui-container">
                <h3>案例分析</h3>
                <div class="login-anli clear">
                    <!-- 一个完整的案例 -->
                    <div class="layui-col-md6">
                        <div class="login-anli-list">
                            <!-- 头像、用户名 -->
                            <div class="login-anli-list-top">
                                <img src="/static/index/layuiadmin/imgs/touxiang.jpg">
                                <div class="login-anli-list-top-right">
                                    <h5>小茗同学</h5>
                                    <p>**电商</p>
                                </div>
                            </div>
                            <!-- 内容 -->
                            <div class="login-anli-list-content">
                                <p>做电商号更多需要的精准群体不是粉丝数量。这时候我们根据视频观众画像和账号粉丝画像，发现粉丝男女比例年龄比例有偏差，就及时调整内容，优化粉丝群体结构</p>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md6">
                        <div class="login-anli-list">
                            <!-- 头像、用户名 -->
                            <div class="login-anli-list-top">
                                <img src="/static/index/layuiadmin/imgs/touxiang.jpg">
                                <div class="login-anli-list-top-right">
                                    <h5>小茗同学</h5>
                                    <p>**电商</p>
                                </div>
                            </div>
                            <!-- 内容 -->
                            <div class="login-anli-list-content">
                                <p>做电商号更多需要的精准群体不是粉丝数量。这时候我们根据视频观众画像和账号粉丝画像，发现粉丝男女比例年龄比例有偏差，就及时调整内容，优化粉丝群体结构</p>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md6">
                        <div class="login-anli-list">
                            <!-- 头像、用户名 -->
                            <div class="login-anli-list-top">
                                <img src="/static/index/layuiadmin/imgs/touxiang.jpg">
                                <div class="login-anli-list-top-right">
                                    <h5>小茗同学</h5>
                                    <p>**电商</p>
                                </div>
                            </div>
                            <!-- 内容 -->
                            <div class="login-anli-list-content">
                                <p>做电商号更多需要的精准群体不是粉丝数量。这时候我们根据视频观众画像和账号粉丝画像，发现粉丝男女比例年龄比例有偏差，就及时调整内容，优化粉丝群体结构</p>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md6">
                        <div class="login-anli-list">
                            <!-- 头像、用户名 -->
                            <div class="login-anli-list-top">
                                <img src="/static/index/layuiadmin/imgs/touxiang.jpg">
                                <div class="login-anli-list-top-right">
                                    <h5>小茗同学</h5>
                                    <p>**电商</p>
                                </div>
                            </div>
                            <!-- 内容 -->
                            <div class="login-anli-list-content">
                                <p>做电商号更多需要的精准群体不是粉丝数量。这时候我们根据视频观众画像和账号粉丝画像，发现粉丝男女比例年龄比例有偏差，就及时调整内容，优化粉丝群体结构</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 按钮 -->
                <div class="login-anli-anniu">
                    <a href="#">免费试用</a>
                </div>
            </div>
        </div>
 
        <!-- 尾部 -->
        <div id="login-footer" class="clear">
            <!-- 三列字 -->
            <div class="layui-container">
                <div class="layui-col-md6 clear">
                    <div class="layui-col-md4 login-footer-four">
                        <h4>功能介绍</h4>
                        <p>功能介绍</p>
                        <p>版本区别</p>
                        <p>抖音号榜单</p>
                    </div>
                    <div class="layui-col-md4 login-footer-four">
                        <h4>关于我们</h4>
                        <p>联系我们</p>
                        <p>加入我们</p>
                    </div>
                    <div class="layui-col-md4 login-footer-four">
                        <h4>更多产品</h4>
                        <p>M15数据</p>
                        <p>M15助手</p>
                        <p>M15数据</p>
                    </div>
                </div>
                <!-- 二维码 -->
                <div class="layui-col-md5 login-erweima right">
                    <img src="/static/index/layuiadmin/imgs/erweima.png" alt="">
                    <p>扫一扫关注M15数据微信公众号，即可了解更多关于我们的资讯</p>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">

// 1. wow.js 初始化
    new WOW().init();

// 2. 登录遮罩层
    $(".denglu-btn").click(function(){

        $.ajax({
            url:"<?php echo url('login/getdyqrcode'); ?>",
            success:function(res){

            },error:function(){
                layer.msg('二维码获取失败！');
            }
        });

        $(".denglu-mask").show();
    });
    $(".denglu-guan").click(function(){
      $(".denglu-mask").hide();
    });


    // 扫码登录
    $("#saoma").unbind("click").click(function ()
       {
        $("#saoma-denglu").attr("style","display:block");
        $("#shouji-denglu").attr("style","display:none");

     });

      $("#tel").unbind("click").click(function ()
      {
        $("#saoma-denglu").attr("style","display:none");
        $("#shouji-denglu").attr("style","display:block");

    });

    // 手机登录
    $("#saoma1").unbind("click").click(function ()
       {
        $("#saoma-denglu").attr("style","display:block");
        $("#shouji-denglu").attr("style","display:none");

     });

      $("#tel1").unbind("click").click(function ()
      {
        $("#saoma-denglu").attr("style","display:none");
        $("#shouji-denglu").attr("style","display:block");

    });

    // 表单
    layui.use(['form', 'layedit', 'laydate'], function(){
      var form = layui.form
      ,$     = layui.$
      ,layer = layui.layer
      ,layedit = layui.layedit
      ,laydate = layui.laydate;

      $(".loginbut").click(function(){
        $.ajax({
            url:"<?php echo url('login'); ?>",
            type:"POST",
            data:$("#loginForm").serialize(),
            success:function(res){
                layer.msg(res.msg);
                if(res.code)
                    setTimeout(function(){window.location.href="<?php echo url('index/index'); ?>";},2000);
            },error:function(){
                layer.msg('服务器错误，请稍后重试！');
            }
        });
      });
    });


// 3. 注册遮罩层
    $(".zhuce-btn").click(function(){
      $(".zhuce-mask").show();
    });
    $(".denglu-guan").click(function(){
      $(".zhuce-mask").hide();
    });

</script>
</body>
</html>


