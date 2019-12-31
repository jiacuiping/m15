<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"/www/wwwroot/newm15/public/../application/index/view/music/index.html";i:1573732032;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>热门音乐</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/index/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/admin.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/style.css" media="all">
</head>
<style>
  .layui-form-item .layui-input-inline{width: 300px}
  .layui-btn{background-color: #1e9fff}
  .ranktab{width: 33%;float:left;padding: 0px;text-align: center;cursor:pointer}
  .operationbat{width: 50%;float:left;padding: 0px;text-align: center;cursor:pointer}
</style>
<body>
  <div class="layui-fluid" id="LAY-demo-carousel">
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
      <!-- 三榜 -->
      <div class="layui-col-md12" style="padding-top: 0">
        <form id="myform" method="" action="<?php echo url('index'); ?>">
          <div class="sanbang clear">
            <div class="left sanbang-left mtd-shipin-td-btn" style="width:150px;">
              <input type="hidden" name="createtime" id="createtime" value="<?php echo $condition['createtime']; ?>">
              <button type="button" data-value="1" class="layui-btn-sm ranktab <?php if($condition['createtime'] == '1'): ?> mtd-shipin-btn-checked <?php endif; ?>">日榜</button>
              <button type="button" data-value="7" class="layui-btn-sm ranktab <?php if($condition['createtime'] == '7'): ?> mtd-shipin-btn-checked <?php endif; ?>">周榜</button>
              <button type="button" data-value="30" class="layui-btn-sm ranktab <?php if($condition['createtime'] == '30'): ?> mtd-shipin-btn-checked <?php endif; ?>">月榜</button>
            </div>
            <div class="right sanbang-right">
              <div class="layui-form-item">
                <div class="layui-input-inline sanbang-right-div">
                  <input type="text" name="keyword" placeholder="请输入音乐关键字" autocomplete="off" class="layui-input" value="<?php echo $condition['keyword']; ?>">
                  <button type="button" class="layui-btn layui-btn-sm searchbat">搜索</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <!-- 一行字 -->
      <div class="clear yihangzi">
        <p>共搜索到<?php echo $count; ?>个结果</p>
        <p class="right">最近更新时间：<?php echo $lasttime; ?></p>
      </div>
      <!-- 表格 -->
      <div class="layui-col-md12" style="padding-top: 0">
        <div class="layui-form">
          <table class="layui-table" lay-even="" lay-skin="nob">
            <colgroup>
              <col width="100">
              <col width="450">
              <col width="200">
              <col width="200">
              <col width="150">
              <col>
            </colgroup>
            <thead>
              <tr class="mtd-shipin-tr">
                <th>排名</th>
                <th style="text-align: left">音乐名称</th>
                <th>音乐人</th>
                <th>使用次数</th>
                <th>操作</th>
              </tr> 
            </thead>
            <tbody>
              <?php if(is_array($music) || $music instanceof \think\Collection || $music instanceof \think\Paginator): $i = 0; $__LIST__ = $music;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                <tr>
                  <?php if($i == 1): ?>
                    <td><img src="/static/index/layuiadmin/imgs/jin.png" style="width: 25px"></td>
                  <?php elseif($i == 2): ?>
                    <td><img src="/static/index/layuiadmin/imgs/yin.png" style="width: 25px"></td>
                  <?php elseif($i == 3): ?>
                    <td><img src="/static/index/layuiadmin/imgs/tong.png" style="width: 25px"></td>
                  <?php else: ?>
                    <td><?php echo $i; ?></td>
                  <?php endif; ?>
                  <td class="mtd-mtd-shipin-table-td2"><img src=<?php echo $item['music_cover']; ?>><p class="yichu"><?php echo $item['music_title']; ?></p></td>
                  <td class="color8c"><?php echo $item['music_username']; ?></td>
                  <td class="color8c"><?php echo $item['music_usercount']; ?></td>
                  <td class="mtd-shipin-td-btn">
                    <button type="button" class="layui-btn-sm operationbat goinfo" data-value="<?php echo $item['music_id']; ?>"><img src="/static/index/layuiadmin/imgs/caozuo1.png" alt="">详情</button>
                    <button type="button" class="layui-btn-sm operationbat collbat" data-value="<?php echo $item['music_id']; ?>"><img src="/static/index/layuiadmin/imgs/caozuo3.png" alt="">收藏</button>
                  </td>
                </tr>
              <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
          </table> 
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

    //分类筛选
    $(".ranktab").click(function(){
      $("#createtime").val($(this).attr('data-value'));
      $("#myform").submit();
    });

    $(".searchbat").click(function(){
      $("#myform").submit();
    });

    $(".goinfo").click(function(){
      location.href="<?php echo url('info','',false); ?>/mid/" + $(this).attr('data-value');
    });

    $(".collbat").click(function(){
      var key = $(this).attr('data-value');
      $.ajax({
        url:"<?php echo url('Collection/createCollect'); ?>",
        data:{type:'music',key:key},
        success:function(res){
          if(res.code == 1)
            layer.msg('收藏成功');
          else
            layer.msg('您已收藏该音乐');
        },error:function(){
          layer.msg('服务器错误，请稍后重试！');
        }
      });
    });

  });
</script>
</body>
</html>