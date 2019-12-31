<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"/www/wwwroot/newm15/public/../application/index/view/collection/topic.html";i:1573729470;}*/ ?>
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
</head>
<style>
.operationbat{width: 50%;float:left;padding: 0px;text-align: center;cursor:pointer}
</style>
<body onLoad="initPieChart();">
  <!-- 点击shipin里的详情按钮跳到此页 -->
  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <!-- 轮播 -->
      <div class="layui-col-md12" style="padding-bottom: 0">
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
      <!-- tab导航条 -->
      <div class="layui-col-md12" style="padding-bottom: 0">
        <div class="layui-card">
          <div class="layui-card-body" style="padding:10px 15px 0 15px;">
            <div class="mtd-shipin-tab-left">
              <ul class="clear">
                <li><a href="<?php echo url('video'); ?>">视频收藏</a></li>
                <li><a href="<?php echo url('music'); ?>">音乐收藏</a></li>
                <li><a href="javascript:;" class="checked-bottom">话题收藏</a></li>
                <li><a href="<?php echo url('kol'); ?>">KOL收藏</a></li>
 <!--                <li><a href="<?php echo url('goods'); ?>">商品收藏</a></li>
                <li><a href="<?php echo url('task'); ?>">任务收藏</a></li>
                <li><a href="<?php echo url('course'); ?>">课程收藏</a></li> -->
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- 列表 -->
      <div class="layui-col-md12" style="padding-top: 0">
        <div class="layui-form">
          <?php if(empty($data)): ?>
            <div style="text-align: center;margin-top: 100px;">暂无话题数据</div>
          <?php else: ?>
            <table class="layui-table" lay-even="" lay-skin="nob">
              <colgroup>
                <col width="100">
                <col width="500">
                <col width="">
                <col width="">
                <col width="">
                <col width="250">
                <col>
              </colgroup>
              <thead>
                <tr class="mtd-shipin-tr">
                  <th>排名</th>
                  <th style="text-align: left">话题</th>
                  <th>参与人数</th>
                  <th>总播放数</th>
                  <th>趋势</th>
                  <th>操作</th>
                </tr> 
              </thead>
              <tbody>
                <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                  <tr>
                    <td><?php echo $i; ?></td>
                    <td class="mtd-mtd-shipin-table-td2">
                      <img src="<?php echo $item['topic_cover']; ?>" alt="">
                      <p class="yichu"><?php echo $item['topic_title']; ?></p>
                    </td>
                    <td class="color8c"><?php echo $item['topic_count']; ?></td>
                    <td class="color8c"><?php echo $item['topic_view']; ?></td>

                    <td><img src="/static/index/layuiadmin/imgs/shangjian.png" class="shengzhi"></td>

                    <td class="mtd-shipin-td-btn">
                      <button type="button" class="layui-btn-sm operationbat goinfo" data-value="<?php echo $item['topic_id']; ?>"><img src="/static/index/layuiadmin/imgs/caozuo1.png" alt="">详情</button>
                      <button type="button" class="layui-btn-sm operationbat removeCollect" data-value="<?php echo $item['topic_id']; ?>"><img src="/static/index/layuiadmin/imgs/caozuo3.png" alt="">收藏</button>
                    </td>
                  </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </tbody>
            </table> 
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  
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

  layui.config({
      base: '/static/index/layuiadmin/' //静态资源所在路径
  }).extend({
      index: '/lib/index' //主入口模块
  }).use(['index', 'form', 'layer'], function(){
    var layer = layui.layer
    ,form = layui.form
    ,$ = layui.$;

    $(".goinfo").click(function(){
      location.href="<?php echo url('topic/info','',false); ?>/tid/" + $(this).attr('data-value');
    });


    $(".removeCollect").click(function(){
      var key = $(this).attr('data-value');
      layer.confirm('确定取消收藏该话题吗？', {
        btn: ['确定','取消'] //按钮
      }, function(){
          $.ajax({
            url:"<?php echo url('cancelCollect'); ?>",
            data:{type:'topic',key:key},
            success:function(res){
              layer.msg(res.msg);
              if(res.code == 1)
                setTimeout(function(){location.reload()},1000);
            },error:function(){
              layer.msg('服务器错误，请稍后重试！');
            }
          });
      });
    });
  });
</script>
</body>
</html>