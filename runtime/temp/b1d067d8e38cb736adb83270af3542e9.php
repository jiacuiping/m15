<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"/www/wwwroot/newm15/public/../application/index/view/kol/result.html";i:1573825447;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>我收藏的素材</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/index/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/admin.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/style.css" media="all">
</head>
<style>
  .easyPieChart{margin: 0 20px;}
  .layui-form-item .layui-input-inline{width: 260px}
</style>
<body onLoad="initPieChart();">
<div class="layui-fluid" id="LAY-flow-demo">
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
      <!-- 搜索和搜索条件 -->
      <div class="layui-col-md12">
        <div class="layui-card" style="height: 60px;">
          <div class="layui-card-body" style="padding: 10px 15px 3px 15px;">
            <!-- 搜索 -->
            <div class="sanbang-right left" style="height: 38px">
                <div class="layui-form-item">
                  <div class="layui-input-inline sanbang-right-div">
                    <input type="" name="keyword" placeholder="请输入关键词" autocomplete="off" class="layui-input" value="<?php echo $data['keyword']; ?>">
                    <button type="button" class="layui-btn layui-btn-sm" style="background-color: #1988fe">搜索</button>
                  </div>
                </div>
            </div>
            <!-- 搜索条件 -->
<!--             <form class="layui-form" action="" lay-filter="example" style="display: inline-block;height: 38px">
              <div class="layui-form-item left">
                <div class="layui-input-block" style="width: 110px; margin-left: 0">
                  <select name="interest" lay-filter="aihao">
                    <option value="" selected="">搜索条件</option>
                    <option value="0">写作</option>
                    <option value="1">阅读</option>
                    <option value="2">游戏</option>
                  </select>
                </div>
              </div>
            </form> -->
          </div>
        </div>
      </div>
      <!-- 搜索结果 -->
      <div class="layui-col-md12">
        <p><span class="left">共搜索到<?php echo $count; ?>个结果</span><!-- <span class="right">最近更新时间：2019-08-23</span> --></p>
      </div>
      <!-- tab选项 -->
      <div class="layui-col-md12" style="padding-bottom: 0">
        <div class="layui-card">
          <div class="layui-card-body" style="padding:10px 15px 0 15px;">
            <div class="mtd-shipin-tab-left">
              <ul class="clear">
                <li><a href="index.html" title="" class="checked-bottom">综合排序</a></li>
                <li><a href="#" title="">粉丝最多</a></li>
                <li><a href="#" title="">点赞最多</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <?php if(is_array($result) || $result instanceof \think\Collection || $result instanceof \think\Paginator): $i = 0; $__LIST__ = $result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
        <!-- 收藏的信息 -->
        <div class="layui-col-md12">
          <div class="layui-card">
            <div class="layui-card-body">
              <div class="mtd-shipin-index-div">
                <!-- 左边头像和热度 -->
                  <div class="mtd-shipin-div-left">
                    <img src="<?php echo $item['kol_avatar']; ?>" alt="" class="mtd-list1-shenyang-left-img">
                    <div class="mtd-list1-shenyang-div">
                      <img src="/static/index/layuiadmin/imgs/10.png" alt="" style="width: 18px"><?php echo $item['kt_hot']; ?>
                    </div>
                  </div>
                  <!-- 中间信息抖音号，分类和简介 -->
                  <div class="mtd-shipin-div-center">
                    <h5><?php echo $item['kol_nickname']; if($item['kol_is_goods'] == 1): ?>
                        <img src="/static/index/layuiadmin/imgs/3.png" alt="" class="center-img">
                      <?php endif; ?>
                    </h5>
                    <p>抖音号：<?php echo $item['kol_number']; ?><span class="span-margin-l">性别：<?php if($item['kol_sex'] == 1): ?> 男 <?php elseif($item['kol_sex'] == 2): ?> 女 <?php endif; ?></span><span class="span-margin-l">地区：<?php echo $item['kol_cityname']; ?></span><span class="span-margin-l">年龄：<?php echo $item['kol_age']; ?></span></p>
                    <p>分类：<?php echo $item['kol_sort']; ?><!-- <span class="span-margin-l kouhong">口红</span><span class="span-margin-l kouhong">彩妆</span> --></p>
                    <p>简介：<?php echo $item['kol_signature']; ?></p>
                    <?php if($item['kol_verifyname'] != ''): ?>
                      <span class="mz-span-img" style="margin-right: 20px;"><img src="/static/index/layuiadmin/imgs/huangguan.png" alt=""><?php echo $item['kol_verifyname']; ?></span>
                    <?php endif; if($item['kol_achievement'] != ''): ?>
                      <span class="span-margin-l mz-span-img" style="background-color:#ff8000;margin-left: 0px"><img src="/static/index/layuiadmin/imgs/huangguan.png" alt=""><?php echo $item['kol_achievement']; ?></span>
                    <?php endif; ?>
                    <!-- <p><span class="mz-span-img"><img src="/static/index/layuiadmin/imgs/huangguan.png" alt="">美妆排行榜周榜第一名</span><span class="span-margin-l mz-span-img" style="background-color:#ff8000"><img src="/static/index/layuiadmin/imgs/huangguan.png" alt="">今日电商大人销量榜第三名</span></p> -->
                  </div>
                  <!-- 右边四个仪表盘图表 -->
                  <div class="mtd-shipin-div-right">
                    <div class="container" style="width: 100%">
                      <div class="chart">
                        <div class="percentage" data-percent="55"><span><?php echo $item['kt_fans']; ?></span></div>
                        <div class="labeler">粉丝数</div>
                      </div>
                      <div class="chart">
                        <div class="percentage" data-percent="46"><span><?php echo $item['kt_mean_like']; ?></span>w</div>
                        <div class="labeler">平均点赞</div>
                      </div>
                      <div class="chart">
                        <div class="percentage" data-percent="92"><span><?php echo $item['kt_mean_comment']; ?></span></div>
                        <div class="labeler">平均评论</div>
                      </div>
                      <div class="chart">
                        <div class="percentage" data-percent="84"><span><?php echo $item['kt_mean_share']; ?></span></div>
                        <div class="labeler">平均分享</div>
                      </div>
                    </div>
                  </div>
              </div>
              <!-- 好多button -->
              <div class="button5">
                <a href="<?php echo url('info',array('kid'=>$item['kol_id'])); ?>" title=""><button type="button" class="layui-btn layui-btn-sm layui-btn-normal">查看详情</button></a>
                <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">加入我的抖音号</button>
                <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">相似号查询</button>
                <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">视频监控</button>
                <button type="button" class="layui-btn layui-btn-primary layui-btn-sm collbat" data-value="<?php echo $item['kol_id']; ?>">加入收藏</button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; endif; else: echo "" ;endif; ?>



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

// 2.四个仪表盘 图表
var initPieChart = function() {
  $('.percentage').easyPieChart({
    animate: 1000
  });
  $('.percentage-light').easyPieChart({
    barColor: function(percent) {
      percent /= 100;
      return "rgb(" + Math.round(255 * (1-percent)) + ", " + Math.round(255 * percent) + ", 0)";
    },
    trackColor: '#666',
    scaleColor: false,
    lineCap: 'butt',
    lineWidth: 15,
    animate: 1000
  });

  $('.updateEasyPieChart').on('click', function(e) {
    e.preventDefault();
    $('.percentage, .percentage-light').each(function() {
    var newValue = Math.round(100*Math.random());
    $(this).data('easyPieChart').update(newValue);
    $('span', this).text(newValue);
    });
  });
};

//3.
layui.use(['form', 'layedit', 'laydate'], function(){
  var form = layui.form
  ,layer = layui.layer
  ,layedit = layui.layedit
  ,laydate = layui.laydate;
  
  $(".collbat").click(function(){
    var key = $(this).attr('data-value');
    $.ajax({
      url:"<?php echo url('Collection/createCollect'); ?>",
      data:{type:'kol',key:key},
      success:function(res){
        if(res.code == 1)
          layer.msg('收藏成功');
        else
          layer.msg('您已收藏该Kol');
      },error:function(){
        layer.msg('服务器错误，请稍后重试！');
      }
    });
  });


  //日期
  laydate.render({
    elem: '#date'
  });
  laydate.render({
    elem: '#date1'
  });
  
  //创建一个编辑器
  var editIndex = layedit.build('LAY_demo_editor');
 
  //自定义验证规则
  form.verify({
    title: function(value){
      if(value.length < 5){
        return '标题至少得5个字符啊';
      }
    }
    ,pass: [
      /^[\S]{6,12}$/
      ,'密码必须6到12位，且不能出现空格'
    ]
    ,content: function(value){
      layedit.sync(editIndex);
    }
  });
  
  //监听指定开关
  form.on('switch(switchTest)', function(data){
    layer.msg('开关checked：'+ (this.checked ? 'true' : 'false'), {
      offset: '6px'
    });
    layer.tips('温馨提示：请注意开关状态的文字可以随意定义，而不仅仅是ON|OFF', data.othis)
  });
  
  //监听提交
  form.on('submit(demo1)', function(data){
    layer.alert(JSON.stringify(data.field), {
      title: '最终的提交信息'
    })
    return false;
  });
 
  //表单赋值
  layui.$('#LAY-component-form-setval').on('click', function(){
    form.val('example', {
      "username": "贤心" // "name": "value"
      ,"password": "123456"
      ,"interest": 1
      ,"like[write]": true //复选框选中状态
      ,"close": true //开关状态
      ,"sex": "女"
      ,"desc": "我爱 layui"
    });
  });
  
  //表单取值
  layui.$('#LAY-component-form-getval').on('click', function(){
    var data = form.val('example');
    alert(JSON.stringify(data));
  });
  
});
</script>
</body>
</html>