<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"/www/wwwroot/newm15/public/../application/index/view/kol/contrastresult.html";i:1577361879;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>kol榜单</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/index/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/iconfont.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/admin.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/style.css" media="all">
</head>
<style>
.kol-duibi-onclick{background: #1988fe;color: #fff}
.layui-form-item .layui-input-inline{width: 300px}
.layui-btn{background-color: #1e9fff}
.layui-btn-disabled,.layui-btn-disabled:hover{background-color:#d9d9d9;color: #fff;margin-top: 20px;}
.weimang-div button{margin-top: 20px;width: 80px; height: 38px}
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
      <!-- 所属省份 分类 -->
      <div class="layui-col-md12">
        <div class="layui-card layui-card-padding">
          <!--  tab选项卡 -->
          <div class="mtd-shipin-tab clear">  
            <div class="mtd-shipin-tab-left">
              <ul class="clear">
                <a href="index.html" title=""><li class="checked-bottom">KOL对比</li></a>
                <a href="lishi.html" title=""><li>对比历史</li></a>
              </ul>
            </div>
            <!-- 右边问号 -->
            <div class="mtd-shipin-tab-right right">
              <a href="#" style="color:#5597e5"><img src="/static/index/layuiadmin/imgs/wen.png" alt=""> 使用帮助</a>
            </div>
          </div>
        </div>
      </div>
      <!-- 左边列表 -->
      <div class="layui-col-xs5 layui-col-sm3 layui-col-md2">
        <div class="layui-card">
          <div class="layui-card-header">选中的KOL（<span style="color: #1988fe">3</span>）</div>
          <div class="layui-card-body" style="padding:0">
            <div class="kol-duibi-ul">
              <ul>
                <li class="checked kol-duibi-onclick">
                  <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                  <span>小名同学</span>
                  <i class="right iconfont icon-laji"></i>
                </li>
                <li>
                  <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                  <span>小名同学</span>
                  <i class="right iconfont icon-laji"></i>
                </li>
                <li>
                  <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                  <span>小名同学</span>
                  <i class="right iconfont icon-laji"></i>
                </li>
                <li>
                  <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                  <span>小名同学</span>
                  <i class="right iconfont icon-laji"></i>
                </li>

              </ul>
            </div>
            <div class="start-duibi">
              <p>本月使用次数：<span style="color: #1988fe">3</span> 剩余次数：<span>97</span></p>
              <a href="duibi.html" title=""><button type="button" class="layui-btn layui-btn-normal">开始对比</button></a>
            </div>
          </div>
        </div>
      </div>
      <!-- 右边搜索结果 -->
      <div class="layui-col-xs7 layui-col-sm9 layui-col-md10">
        <div class="layui-card">
          <!-- 搜索头部 -->
          <div class="layui-card-header clear">
            <h3 class="left" style="display: inline-block;color: #000; margin-right: 15px">搜索KOL</h3>
            <div class="left sanbang-right">
            <div class="layui-form-item">
              <div class="layui-input-inline sanbang-right-div" style="margin-top: 2px">
                <input type="" name="" lay-verify="pass" placeholder="请输入话题关键字" autocomplete="off" class="layui-input">
                <button type="button" class="layui-btn layui-btn-sm">搜索</button>
              </div>
            </div>
          </div></div>
          <!-- 搜索结果 -->
          <div class="layui-card-body">
            <div class="kol-duibi-dashed clear">
              <h4>搜索结果（<span style="color: #1988fe">4</span>）</h4>
                <div class="layui-col-xs6 weimang">
                  <div class="weimang-div">
                    <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                    <div class="weimang-div-right">
                      <h3>小名</h3>
                      <p>抖音号:548786</p>
                      <p>简介：离别无可避，爱无有尽时。</p>
                    </div>
                    <button type="button" class="right layui-btn layui-btn-disabled">已选择</button>
                  </div>
                </div>
                <div class="layui-col-xs6 weimang">
                  <div class="weimang-div">
                    <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                    <div class="weimang-div-right">
                      <h3>小名</h3>
                      <p>抖音号:548786</p>
                      <p>简介：离别无可避，爱无有尽时。</p>
                    </div>
                    <a href="list2.html" title=""><button type="button" class="right layui-btn layui-btn-normal">搜索</button></a>
                  </div>
                </div>
                <div class="layui-col-xs6 weimang">
                  <div class="weimang-div">
                    <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                    <div class="weimang-div-right">
                      <h3>小名</h3>
                      <p>抖音号:548786</p>
                      <p>简介：离别无可避，爱无有尽时。</p>
                    </div>
                    <a href="list2.html" title=""><button type="button" class="right layui-btn layui-btn-normal">搜索</button></a>
                  </div>
                </div>
                <div class="layui-col-xs6 weimang">
                  <div class="weimang-div">
                    <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                    <div class="weimang-div-right">
                      <h3>小名</h3>
                      <p>抖音号:548786</p>
                      <p>简介：离别无可避，爱无有尽时。</p>
                    </div>
                    <a href="list2.html" title=""><button type="button" class="right layui-btn layui-btn-normal">搜索</button></a>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="/static/index/layuiadmin/style/js/jquery.min.js"></script>  
  <script src="/static/index/layuiadmin/layui/layui.js"></script>  
  
  <script>

// 1.轮播 
layui.use('carousel', function(){
  var carousel = layui.carousel;
  //建造实例
  carousel.render({
    elem: '#test1'
    ,width: '100%' //设置容器宽度
    ,arrow: 'always' //始终显示箭头
    //,anim: 'updown' //切换动画方式
  });
});

// 2.左侧 ul li 信息点击时变蓝效果
$(document).ready(function() {
  $(".kol-duibi-ul ul li").click(function() {
    $(".kol-duibi-ul ul li").removeClass('kol-duibi-onclick')//若想点击以后样式不再回去，就把这行removeClass去掉
    $(this).addClass("kol-duibi-onclick");
  });}
);

  </script>
</body>
</html>