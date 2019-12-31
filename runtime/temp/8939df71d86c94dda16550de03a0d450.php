<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:70:"/www/wwwroot/newm15/public/../application/index/view/goods/search.html";i:1577684490;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>商品搜索</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/index/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/admin.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/style.css" media="all">
</head>
<style>
.layui-input-block {margin-left: 70px}
.border-bottom{margin: 15px 0;}
.layui-form-label{color: #000}
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
      <!-- 搜索，高级搜索 -->
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body">
            <div class="kol-search clear">
              <!-- 右侧  使用帮助 -->
                <a href="#" title="" style="color:#5597e5">
                  <p style="text-align: right;margin-bottom: 10px"><img src="/static/index/layuiadmin/imgs/wen.png" alt=""> 使用帮助</p>
                </a>
              <!-- 搜索 -->
              <div class="right sanbang-right" style="width: 100%">
                <div class="layui-form-item" style="width: 100%">
                  <div class="layui-input-inline sanbang-right-div" style="width: 100%">
                    <input type="" name="" lay-verify="pass" placeholder="请输入关键词" autocomplete="off" class="layui-input" style="width: 100%">
                    <a href="list.html" title=""><button type="button" class="layui-btn layui-btn-sm" style="background-color:#5597e5">搜索</button></a>
                  </div>
                </div>
              </div>
              <!-- 商品分类 -->
              <div class="mtd-shipin-fenlei border-bottom clear">
                <span class="left mtd-shipin-fenlei-left">商品分类:</span>
                <div class="left mtd-search-fenlei">
                  <a href="#" title="">全部</a>
                  <a href="#" title="">网红美女</a>
                  <a href="#" title="">网红帅哥</a>
                  <a href="#" title="">搞笑</a>
                  <a href="#" title="">情感</a>
                  <a href="#" title="">剧情</a>
                  <a href="#" title="">美食</a>
                  <a href="#" title="">美妆</a>
                  <a href="#" title="">种草</a>
                  <a href="#" title="">穿搭</a>
                  <a href="#" title="">美妆</a>
                  <a href="#" title="">种草</a>
                  <a href="#" title="">穿搭</a>
                </div>
              </div>
              <!-- 发布时间 -->
              <div class="mtd-shipin-fenlei border-bottom clear">
                <span class="left mtd-shipin-fenlei-left">发布时间:</span>
                <div class="left mtd-search-fenlei">
                  <a href="#" title="">24小时</a>
                  <a href="#" title="">近7天</a>
                  <a href="#" title="">近30天</a>
                  <a href="#" title="">自定义时间</a>
                </div>
              </div>
              <!-- 点赞数 -->
              <div class="mtd-shipin-fenlei border-bottom clear">
                <span class="left mtd-shipin-fenlei-left">点赞数:</span>
                <div class="left mtd-search-fenlei">
                  <a href="#" title="">不限</a>
                  <a href="#" title=""><1万</a>
                  <a href="#" title="">1万~5万</a>
                  <a href="#" title="">5万~10万</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
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

</script>

</html>