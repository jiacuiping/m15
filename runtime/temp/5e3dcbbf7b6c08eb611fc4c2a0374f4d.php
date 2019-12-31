<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"D:\phpstudy_pro\WWW\M15\public/../application/index\view\mcn\agent.html";i:1577706968;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>经纪人工作台</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/index/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/admin.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/style.css" media="all">
</head>
<style>
.layui-form-item{margin-bottom: 0}
.layui-input-block{margin-left: 0}
.guan{padding: 0 10px 10px 10px; text-align: right }
.mask{width: 100%;height: 100vh; background: rgba(0,0,0,0.75);position: fixed; top: 0;z-index: 99999; display: none;}
.content{width: 450px;max-height: 450px;overflow: auto;background: #fff;margin: 10% auto;padding: 10px}
.sanbang-right-div,.sanbang-right{width: 100%!important}
</style>
<body>
  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <!-- 轮播 -->
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body" style="padding: 0">
            <div class="layui-carousel" id="test1">
              <div carousel-item>
                <div class="mtd-shipin-banner"><img src="/static/index/layuiadmin/imgs/banner.png" alt=""></div>
                <div class="mtd-shipin-banner"><img src="/static/index/layuiadmin/imgs/banner.png" alt=""></div>
                <div class="mtd-shipin-banner"><img src="/static/index/layuiadmin/imgs/banner.png" alt=""></div>
                <div class="mtd-shipin-banner"><img src="/static/index/layuiadmin/imgs/banner.png" alt=""></div>
                <div class="mtd-shipin-banner"><img src="/static/index/layuiadmin/imgs/banner.png" alt=""></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- 头像加右侧数据 -->
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body mcn-kol-top clear">
            <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="" class="left">
            <div class="left">
              <h3>美ONE</h3>
              <P>美腕（上海）网络科技有限公司</P>
              <p>美ONE成立于2014年，是通过挖掘及培养专业达人，饼打造专业内容、。。。。</p>
              <p>官网：http////////</p>
              <p>同MCN抖音号：</p>
            </div>
          </div>
        </div>
      </div>
      <!-- 经纪人管理 -->
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body clear">
            <div class="mcn-kol-red-left left">
              <h3>经纪人管理</h3>
              <p style="color: #000;display: inline-block">提示：按经纪人查看红人数据请到“kol管理”中操作</p>
            </div>
            <div class="mcn-kol-red-right right">
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm" style="color:#1ca3fc">+添加经纪人</button>
            </div>
          </div>
        </div>
      </div>
      <!-- 经纪人列表 -->
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">经纪人列表</div>
          <div class="layui-card-body">
            <!--  -->
            <div class="fenzu-div1">
              <!-- 标题 -->
              <div class="fenzu-div1-top">
                <h3>用户_3946<span>6个红人</span></h3>
                <div class="right">
                  <a href="#" title="" style="color: #f5655d; margin-left: 15px">删除经纪人</a>
                </div>
              </div>
              <!-- 列表 -->
              <ul class="clear">
                <li class="layui-col-md3">
                  <div class="mcn-ul-li">
                    <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="" class="mcn-touxiang">
                    <p class="yichu">会说话的...</p>
                    <img src="/static/index/layuiadmin/imgs/1-1.png" alt="">
                  </div>
                </li>
                <li class="layui-col-md3">
                  <div class="mcn-ul-li">
                    <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="" class="mcn-touxiang">
                    <p class="yichu">会说话的...</p>
                    <img src="/static/index/layuiadmin/imgs/1-1.png" alt="">
                  </div>
                </li>
                <li class="layui-col-md3">
                  <div class="mcn-ul-li">
                    <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="" class="mcn-touxiang">
                    <p class="yichu">会说话的...</p>
                    <img src="/static/index/layuiadmin/imgs/1-1.png" alt="">
                  </div>
                </li>
                <!-- 列表最后一个 添加红人 点击时有弹框 -->
                <a href="#" title="" class="dianji">
                  <li class="layui-col-md3">
                    <div class="mcn-ul-li">
                      <p style="text-align: center;display: block; max-width: 100%;color: #34b0fa">+添加经纪人</p>
                    </div>
                  </li>
                </a>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- 点击添加时出现遮罩层 -->
      <div class="mask">
        <div class="content">
          <p class="guan">X</p>
          <div class="wrapper">
              <!-- 搜索 -->
              <div class="sanbang-right">
                <div class="layui-form-item">
                  <div class="layui-input-inline sanbang-right-div">
                    <input type="" name="" lay-verify="pass" placeholder="请输入抖音号或红人名称" autocomplete="off" class="layui-input">
                    <button type="button" class="layui-btn layui-btn-sm" style="background-color: #1988fe;">搜索</button>
                  </div>
                </div>
              </div>
              <!-- 一个搜索结果 -->
              <div class="wrapper-div clear">
                <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="" class="left">
                <div class="wrapper-div-content left">
                  <h3>小茗同学</h3>
                  <p>抖音号：330757574</p>
                  <p>粉丝数：112人</p>
                  <p>作品数：52个</p>
                  <p>简介：十年吉萨复活甲撒护理费</p>
                </div>
                <button type="button" class="layui-btn right layui-btn-disabled">已添加</button>
              </div>
              <div class="wrapper-div clear">
                <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="" class="left">
                <div class="wrapper-div-content left">
                  <h3>小茗同学</h3>
                  <p>抖音号：330757574</p>
                  <p>粉丝数：112人</p>
                  <p>作品数：52个</p>
                  <p>简介：十年吉萨复活甲撒护理费</p>
                </div>
                <button type="button" class="layui-btn right layui-btn-normal">添加</button>
              </div>
              <div class="wrapper-div clear">
                <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="" class="left">
                <div class="wrapper-div-content left">
                  <h3>小茗同学</h3>
                  <p>抖音号：330757574</p>
                  <p>粉丝数：112人</p>
                  <p>作品数：52个</p>
                  <p>简介：十年吉萨复活甲撒护理费</p>
                </div>
                <button type="button" class="layui-btn right layui-btn-normal">添加</button>
              </div>
           </div>
        </div>
      </div>

    </div>
  </div>

<script src="/static/index/layuiadmin/layui/layui.js"></script>  
<script src="/static/index/layuiadmin/style/js/jquery.min.js"></script>  
<script>

// 1.轮播 
layui.use('carousel', function(){
  var carousel = layui.carousel;
  carousel.render({
    elem: '#test1'
    ,width: '100%' //设置容器宽度
    ,arrow: 'always' //始终显示箭头
    //,anim: 'updown' //切换动画方式
  });
});

// 2.下拉选择
layui.use(['form', 'layedit', 'laydate'], function(){
  var form = layui.form
  ,layer = layui.layer
  ,layedit = layui.layedit
  ,laydate = layui.laydate;
  
});


// 3.点击添加经纪人时 遮罩层
  $(".dianji").click(function(){
  $(".mask").show();
});
  $(".guan").click(function(){
  $(".mask").hide();
});
</script>
</body>
</html>