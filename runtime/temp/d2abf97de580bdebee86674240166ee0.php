<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"/www/wwwroot/newm15/public/../application/index/view/music/info.html";i:1574834746;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>评分</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/index/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/admin.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/style.css" media="all">
  <style type="text/css">
    .play {
      background-color: #fff;
      color: #999;
      border: 1px solid #d9d9d9;
    }

  </style>
</head>
<body onLoad="initPieChart();">
  <!-- 点击shipin里的详情按钮跳到此页 -->
  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <!-- 标题 -->
      <!-- 视频介绍 -->
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body layui-shenhuilu clear">
            <img src="<?php echo $music['music_cover']; ?>" alt="" class="left">
            <div class="layui-shenhuilu-r left">
              <h4 style="width: auto"><?php echo $music['music_title']; ?>
                
                <a href="<?php echo $music['music_url']; ?>" target="_blank" class="playa">
                  <button type="button" class="layui-btn layui-btn-sm">播放</button>
                </a>
                <!-- <img src="/static/index/layuiadmin/imgs/caozuo3.png" alt=""> -->
              </h4>
              <p><?php echo $music['music_usercount']; ?>人使用<span class="span-margin-l">时长：<?php echo $music['music_duration']; ?></span></p>
            </div>
          </div>
        </div>
      </div>

      <!-- 两个图表 -->
      <div class="layui-col-md6">
        <div class="layui-card">
          <div class="layui-card-header">使用人数行业分布</div>
          <div class="layui-card-body">
            <div id="tubiao1" style="height: 500px"></div>
          </div>
        </div>
      </div>
      <div class="layui-col-md6">
        <div class="layui-card">
          <div class="layui-card-header">每日新增人数趋势图</div>
          <div class="layui-card-body">
            <div id="tubiao2" style="height: 500px"></div>
          </div>
        </div>
      </div>
      <!-- 好多视频缩图 -->
      <div class="layui-col-md12">
        <?php if(empty($videos)): ?>
          <div style="text-align: center;margin: 50px auto;">暂无相关视频</div>
        <?php else: ?>
          <ul class="shipun-thumb-ul clear">
            <?php if(is_array($videos) || $videos instanceof \think\Collection || $videos instanceof \think\Paginator): $i = 0; $__LIST__ = $videos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): $mod = ($i % 2 );++$i;?>
              <a href="" title="">
                <li>
                  <a href="<?php echo url('video/show',array('vid'=>$video['video_id'])); ?>">
                    <img src="<?php echo $video['video_cover']; ?>" alt=""><span><img src="/static/index/layuiadmin/imgs/xin-bai.png" alt="" class="bofang-xin"> <?php echo $video['vt_like']; ?>w</span>
                  </a>
                </li>
              </a>
            <?php endforeach; endif; else: echo "" ;endif; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>

  
  <script src="/static/index/layuiadmin/layui/layui.js"></script>  
  <script src="/static/index/layuiadmin/style/js/jquery.min.js"></script>  
  <script src="/static/index/layuiadmin/style/js/jquery.easy-pie-chart.js"></script>  
  <script src="/static/index/layuiadmin/style/js/echarts.min.js"></script>
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

    // 2.使用人数行业分布 图表
    var myChart = echarts.init(document.getElementById("tubiao1")); 
    option = {
        xAxis: {
            type: 'category',
            data: ['1', '3G', '3GS', '4', '4S', '4SG', '5']
        },
        color:['#3c88ba'],
        yAxis: {
            type: 'value'
        },
        series: [{
            data: [120, 200, 150, 80, 70, 110, 130],
            type: 'bar'
        }]
    };
    myChart.setOption(option); 

    // 3.每日新增人数趋势图 图表
    var myChart = echarts.init(document.getElementById("tubiao2")); 
    var option = {
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: <?php echo $trend['date']; ?>
        },
        color:['#b3def1'],
        yAxis: {
            type: 'value'
        },
        series: [{
            data: <?php echo $trend['usercount']; ?>,
            type: 'line',
            areaStyle: {}
        }]
    };
    myChart.setOption(option);
  </script>
</body>
</html>