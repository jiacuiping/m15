<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:70:"D:\phpstudy_pro\WWW\M15\public/../application/index\view\mcn\data.html";i:1577706967;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>数据趋势</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/index/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/admin.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/style.css" media="all">
</head>
<style>
  html,body{width: 100%;height: 100%}
  .weirenzheng{
    width: 100%;
    height: 100%;
    text-align: center;
    line-height: 600px;
    font-size: 21px;
  }
  .layui-form-item{margin-bottom: 0}
  .layui-input-block{margin-left: 0}
  .guan{padding: 0 10px 10px 10px; text-align: right }
  .mask{width: 100%;height: 100vh; background: rgba(0,0,0,0.75);position: fixed; top: 0;z-index: 99999; display: none;}
  .content{width: 450px;max-height: 450px;overflow: auto;background: #fff;margin: 10% auto;padding: 10px}
  .sanbang-right-div,.sanbang-right{width: 100%!important}
  .mtd-shipin-tab-left{display: block}
  .mtd-shipin-tab-left ul li{width: 30%;text-align: center}
  .mtd-shipin-tab-left ul li a{padding-bottom: 3px;}
  .mtd-shipin-td-btn a,.mtd-shipin-td-btn button,.mtd-shipin-tab-right button{display: block !important;float: left !important;width: 73px !important;}
  .ordertap {width: 16% !important;}
  .mtd-shipin-td-btn .playa button{height: 35px;border-radius: 0px;border-right: 0px;}
</style>
<body>
  <?php if($code == 1): ?>
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
              <img src="<?php echo $data['mcn_logo']; ?>" alt="" class="left">
              <div class="left">
                <h3><?php echo $data['mcn_name']; ?></h3>
                <P><?php echo $data['mcn_authname']; ?></P>
                <p><?php echo $data['mcn_desc']; ?></p>
                <p>官网：<?php echo $data['mcn_website']; ?></p>
                <p>官方抖音号：<?php echo $data['mcn_officialaccount']; ?></p>
              </div>
            </div>
          </div>
        </div>
        <!-- 七条数据 -->
        <div class="layui-col-md12">
          <div class="layui-card">
            <div class="layui-card-body clear">
              <form id="conditionform">
                <input type="hidden" id="typeinput" name="type" value="<?php echo $condition['type']; ?>">
                <input type="hidden" id="typeinput" name="type" value="<?php echo $condition['type']; ?>">
                <input type="hidden" id="dayinput" name="day" value="<?php echo $condition['day']; ?>">
                <input type="hidden" id="orderinput" name="order" value="<?php echo $condition['order']; ?>">
              </form>
              <!-- 右侧选项和下拉 -->
              <div class="right">
                <!-- 时间选项 -->
                <div class="mtd-shipin-tab-right">
                  <!-- 以下button不能换行，不然会有左右间隔 -->
                  <button type="button" class="layui-btn layui-btn-sm basisOf <?php if($condition['basisOf'] == 'app'): ?> mtd-shipin-btn-checked <?php endif; ?>" data-value="app">按平台</button>
                  <button type="button" class="layui-btn layui-btn-sm basisOf <?php if($condition['basisOf'] == 'group'): ?> mtd-shipin-btn-checked <?php endif; ?>" data-value="group">按分组</button>
                  <button type="button" class="layui-btn layui-btn-sm basisOf <?php if($condition['basisOf'] == 'agent'): ?> mtd-shipin-btn-checked <?php endif; ?>" data-value="agent">按经纪人</button>
                </div>
                <!-- 全部平台 -->
                <form class="layui-form" action="" style="width: 150px;display: inline-block;">
                  <div class="layui-form-item">
                    <div class="layui-input-block">
                      <select name="interest" lay-filter="aihao">
                        <option value="0" selected="">全部平台</option>
                        <option value="1">写作</option>
                        <option value="2">阅读</option>
                      </select>
                    </div>
                  </div>
                </form>
              </div>
              <!-- 七条数据 -->
              <div class="layui-col-md12 mcn-seven">
                <ul class="clear">
                  <li><h3><?php echo $statistical['number']; ?></h3><p>已认领的红人</p></li>
                  <li><h3><?php echo $statistical['fans']; ?></h3><p>总粉丝数</p></li>
                  <li><h3><?php echo $statistical['incfans']; ?></h3><p>昨日新增粉丝</p></li>
                  <li><h3><?php echo $statistical['weekfans']; ?></h3><p>本周新增粉丝</p></li>
                  <li><h3><?php echo $statistical['lweekfans']; ?></h3><p>上周新增粉丝</p></li>
                  <li><h3><?php echo $statistical['agent']; ?></h3><p>经济人数量</p></li>
                  <li><h3><?php echo $statistical['group']; ?></h3><p>分组数量</p></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <!-- tab导航条 -->
        <div class="layui-col-md12">
          <div class="layui-card">
            <div class="layui-card-body" style="padding: 10px 15px 0 15px;">
              <div class="mtd-shipin-tab-left">
                <ul class="clear">
                  <li><a href="javascript:;" class="tapbut <?php if($condition['type'] == 'trend'): ?> checked-bottom <?php endif; ?>" data-value="trend">数据趋势</a></li>
                  <li><a href="javascript:;" class="tapbut <?php if($condition['type'] == 'kol'): ?> checked-bottom <?php endif; ?>" data-value="kol">详细数据</a></li>
                  <li><a href="javascript:;" class="tapbut <?php if($condition['type'] == 'video'): ?> checked-bottom <?php endif; ?>" data-value="video">红人热门视频</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <?php if($condition['type'] == 'trend'): ?>
          <div>
            <!-- 统计范围 -->
            <div class="layui-col-md12">
              <div class="layui-card">
                <div class="layui-card-body">
                  <p style="display:inline-block;color: #000">统计范围：所有已认领的红人</p>
                  <!-- 右边时间选项 -->
                  <div class="mtd-shipin-tab-right right">
                    <!-- 以下button不能换行，不然会有左右间隔 -->
                    <button type="button" class="layui-btn layui-btn-sm day <?php if($condition['day'] == '7'): ?> mtd-shipin-btn-checked <?php endif; ?>" data-value="7">近7天</button>
                    <button type="button" class="layui-btn layui-btn-sm day <?php if($condition['day'] == '30'): ?> mtd-shipin-btn-checked <?php endif; ?>" data-value="30">近30天</button>
                    <button type="button" class="layui-btn layui-btn-sm day <?php if($condition['day'] == '90'): ?> mtd-shipin-btn-checked <?php endif; ?>" data-value="90">近90天</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- 四个图表数据 -->
            <div class="layui-col-md12">
              <div class="layui-card">
                <div class="layui-card-header">新增粉丝</div>
                <div class="layui-card-body">
                  <div id="tb1" style="height: 300px"></div>
                </div>
              </div>
            </div>
            <div class="layui-col-md12">
              <div class="layui-card">
                <div class="layui-card-header">新增点赞</div>
                <div class="layui-card-body">
                  <div id="tb2" style="height: 300px"></div>
                </div>
              </div>
            </div>
            <div class="layui-col-md12">
              <div class="layui-card">
                <div class="layui-card-header">新增评论</div>
                <div class="layui-card-body">
                  <div id="tb3" style="height: 300px"></div>
                </div>
              </div>
            </div>
            <div class="layui-col-md12">
              <div class="layui-card">
                <div class="layui-card-header">新增转发</div>
                <div class="layui-card-body">
                  <div id="tb4" style="height: 300px"></div>
                </div>
              </div>
            </div>
          </div>
        <?php elseif($condition['type'] == 'kol'): ?>
          <div>
            <!-- 第四行  tab选项卡 -->
            <div class="layui-col-md12">
              <div class="layui-card">
                <div class="layui-card-body" style="padding:0 10px">
                  <div class="mtd-shipin-tab clear">  
                    <div class="mtd-shipin-tab-left">
                      <ul class="clear" style="width: 40%;float: left;">
                        <a href="javascript:;"><li class="ordertap checked-bottom">粉丝总数排行</li></a>
                        <a href="javascript:;"><li class="ordertap">新增粉丝排行</li></a>
                        <a href="javascript:;"><li class="ordertap">新增点赞排行</li></a>
                        <a href="javascript:;"><li class="ordertap">新增评论排行</li></a>
                        <a href="javascript:;"><li class="ordertap">新发视频排行</li></a>
                       </ul>
                    </div>
                    <!-- 右边时间选项和导出 -->
                    <div class="right">
                      <div class="mtd-shipin-tab-right">
                        <div class="layui-form">
                          <div class="layui-form-item">
                            <div class="layui-inline">
                              <div class="layui-input-inline">
                                <input type="text" class="layui-input" id="test6" placeholder=" - ">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <button type="button" class="layui-btn layui-btn-sm layui-btn-normal">导出</button>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
            <?php if(empty($kollist)): ?>
              <div>暂无认领红人信息</div>
            <?php else: ?>
              <!-- 列表 -->
              <div class="layui-col-md12">
                <div class="layui-form">
                  <table class="layui-table" lay-even="" lay-skin="nob">
                    <colgroup>
                      <col width="100">
                      <col width="250">
                      <col width="150">
                      <col width="">
                      <col width="">
                      <col width="">
                      <col width="250">
                      <col>
                    </colgroup>
                    <thead>
                      <tr class="mtd-shipin-tr">
                        <th>排名</th>
                        <th style="text-align: left">红人</th>
                        <th>粉丝总数</th>
                        <th>新增粉丝</th>
                        <th>新增点赞</th>
                        <th>新增评论</th>
                        <th>新发视频</th>
                        <th>操作</th>
                      </tr> 
                    </thead>
                    <tbody>
                      <?php if(is_array($kollist) || $kollist instanceof \think\Collection || $kollist instanceof \think\Paginator): $i = 0; $__LIST__ = $kollist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <tr>
                          <?php if($i == 1): ?>
                            <td><img src="/static/index/layuiadmin/imgs/no1.png" style="width: 25px"></td>
                          <?php elseif($i == 2): ?>
                            <td><img src="/static/index/layuiadmin/imgs/no2.png" style="width: 25px"></td>
                          <?php elseif($i == 3): ?>
                            <td><img src="/static/index/layuiadmin/imgs/no3.png" style="width: 25px"></td>
                          <?php else: ?>
                            <td><?php echo $i; ?></td>
                          <?php endif; ?>
                          <td class="mtd-mtd-shipin-table-td2">
                            <img src="<?php echo $item['kol_avatar']; ?>" alt=""style="width: 50px; height: 50px;border-radius: 50%">
                            <p class="yichu"><?php echo $item['kol_nickname']; ?></p>
                          </td>
                          <td><?php echo $item['kt_fans']; ?></td>
                          <td class="color8c"><?php echo $item['kt_inc_fans']; ?></td>
                          <td class="color8c"><?php echo $item['kt_inc_like']; ?></td>
                          <td class="color8c"><?php echo $item['kt_inc_comment']; ?></td>
                          <td class="color8c"><?php echo $item['kt_videocount']; ?></td>
                          <td class="mtd-shipin-td-btn">
                            <a href="<?php echo url('kol/info',array('kid'=>$item['kol_id'])); ?>" title="" class="mtd-shipin-td-btn">
                              <button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo1.png" alt="">详情</button>
                            </a>
                          </td>
                        </tr>
                      <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                  </table> 
                </div>
              </div>
            <?php endif; ?>
          </div>
        <?php elseif($condition['type'] == 'video'): ?>
          <div>
            <!-- 第四行  tab选项卡 -->
            <div class="layui-col-md12">
              <div class="layui-card">
                <div class="layui-card-body" style="padding:0 10px">
                  <div class="mtd-shipin-tab clear">  
                    <div class="mtd-shipin-tab-left">
                      <ul class="clear" style="width: 40%;float: left;">
                        <a href="javascript:;"><li class="ordertap checked-bottom">综合排序</li></a>
                        <a href="javascript:;"><li class="ordertap">点赞最多</li></a>
                        <a href="javascript:;"><li class="ordertap">评论最多</li></a>
                        <a href="javascript:;"><li class="ordertap">分享最多</li></a>
                       </ul>
                    </div>
                    <!-- 右边时间选项和导出 -->
                    <div class="right">
                      <div class="mtd-shipin-tab-right">
                        <div class="layui-form">
                          <div class="layui-form-item">
                            <label class="layui-form-label">发布时间：</label>
                            <div class="layui-inline">
                              <div class="layui-input-inline">
                                <input type="text" class="layui-input" id="test6" placeholder=" - ">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- 列表 -->
            <div class="layui-col-md12">
              <div class="layui-form">
                <table class="layui-table" lay-even="" lay-skin="nob">
                  <colgroup>
                    <col width="100">
                    <col width="420">
                    <col width="100">
                    <col width="80">
                    <col width="80">
                    <col width="80">
                    <col width="130">
                    <col width="230">
                    <col>
                  </colgroup>
                  <thead>
                    <tr class="mtd-shipin-tr">
                      <th>排名</th>
                      <th style="text-align: left">视频内容</th>
                      <th>主播名称</th>
                      <th>点赞数</th>
                      <th>评论数</th>
                      <th>传播价值</th>
                      <th>发布时间</th>
                      <th>操作</th>
                    </tr> 
                  </thead>
                  <tbody>

                    <?php if(is_array($video) || $video instanceof \think\Collection || $video instanceof \think\Paginator): $i = 0; $__LIST__ = $video;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vitem): $mod = ($i % 2 );++$i;?>
                      <tr>
                        <?php if($i == 1): ?>
                          <td><img src="/static/index/layuiadmin/imgs/no1.png" style="width: 25px"></td>
                        <?php elseif($i == 2): ?>
                          <td><img src="/static/index/layuiadmin/imgs/no2.png" style="width: 25px"></td>
                        <?php elseif($i == 3): ?>
                          <td><img src="/static/index/layuiadmin/imgs/no3.png" style="width: 25px"></td>
                        <?php else: ?>
                          <td><?php echo $i; ?></td>
                        <?php endif; ?>
                        <td class="mtd-mtd-shipin-table-td2" style="width:100px !important"><img src="<?php echo $vitem['video_cover']; ?>" alt=""><p class="yichu" style="width:375px"><?php echo $vitem['video_desc']; ?></p></td>
                        <td><?php echo $vitem['video_username']; ?></td>
                        <td class="color8c"><?php echo $vitem['vt_like']; ?></td>
                        <td class="color8c"><?php echo $vitem['vt_comment']; ?></td>
                        <td><img src="/static/index/layuiadmin/imgs/sheng.png" class="shengzhi"><?php echo $vitem['vt_hot']; ?></td>
                        <td class="color8c"><?php echo date('Y-m-d H:i',$vitem['create_time']); ?></td>
                        <td class="mtd-shipin-td-btn">
                          <a href="<?php echo url('video/show',array('vid'=>$vitem['video_id'])); ?>" class="mtd-shipin-td-btn mtd-shipin-td-btna">
                            <button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo1.png" alt="">详情</button>
                          </a>
                          <a href="<?php echo $vitem['video_url']; ?>" target="_blank" class="playa">
                            <button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo2.png" alt="">播放</button>
                          </a>
                          <button type="button" class="layui-btn layui-btn-sm collbatvideo" data-value="<?php echo $vitem['video_id']; ?>"><img src="/static/index/layuiadmin/imgs/caozuo3.png" alt="">收藏</button>
                        </td>
                      </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                  </tbody>
                </table> 
              </div>
            </div> 
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php else: ?>

    <div><?php echo $msg; ?></div>

  <?php endif; ?>

<!-- <div class="weirenzheng">您暂未认证MCN机构，无法使用MCN功能</div>
 -->
<script src="/static/index/layuiadmin/layui/layui.js"></script>  
<script src="/static/index/layuiadmin/style/js/jquery.min.js"></script>  
<script src="/static/index/layuiadmin/style/js/echarts.min.js"></script>  
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
  
  $(".collbatvideo").click(function(){
    var key = $(this).attr('data-value');
    $.ajax({
      url:"<?php echo url('Collection/createCollect'); ?>",
      data:{type:'video',key:key},
      success:function(res){
        if(res.code == 1)
          layer.msg('收藏成功');
        else
          layer.msg('您已收藏该视频');
      },error:function(){
        layer.msg('服务器错误，请稍后重试！');
      }
    });
  });

  $(".tapbut").click(function(){
    GoData("<?php echo $condition['basisOf']; ?>",<?php echo $condition['key']; ?>,$(this).attr('data-value'),<?php echo $condition['day']; ?>,"<?php echo $condition['order']; ?>");
  });

  $(".basisOf").click(function(){
    GoData($(this).attr('data-value'),<?php echo $condition['key']; ?>,"<?php echo $condition['type']; ?>",<?php echo $condition['day']; ?>,"<?php echo $condition['order']; ?>");
  })

  $(".day").click(function(){
    GoData("<?php echo $condition['basisOf']; ?>",<?php echo $condition['key']; ?>,"<?php echo $condition['type']; ?>",$(this).attr('data-value'),"<?php echo $condition['order']; ?>");
  })

  function GoData(basisOf,key,type,day,order){
    window.location.href = "<?php echo url('data','',false); ?>/basisOf/"+basisOf+"/key/"+key+"/type/"+type+"/day/"+day+"/order/"+order;
  }
});

</script>
<?php if($condition['type'] == 'trend'): ?>
  <script>
    // 3.新增粉丝 图表
    var myChart = echarts.init(document.getElementById("tb1")); 
    var option = {
        xAxis: {
            type: 'category',
            data: <?php echo $trend['date']; ?>
        },
        yAxis: {
            type: 'value'
        },
        color:['#049cff'],
        series: [{
            data: <?php echo $trend['fans']; ?>,
            type: 'line',
            smooth: true
        }]
    };
    myChart.setOption(option);
    // 4.新增播放 图表
    var myChart = echarts.init(document.getElementById("tb2")); 
    var option = {
        xAxis: {
            type: 'category',
            data: <?php echo $trend['date']; ?>
        },
        yAxis: {
            type: 'value'
        },
        color:['#049cff'],
        series: [{
            data: <?php echo $trend['like']; ?>,
            type: 'line',
            smooth: true
        }]
    };
    myChart.setOption(option);
    // 5.新增点赞 图表
    var myChart = echarts.init(document.getElementById("tb3")); 
    var option = {
        xAxis: {
            type: 'category',
            data: <?php echo $trend['date']; ?>
        },
        yAxis: {
            type: 'value'
        },
        color:['#049cff'],
        series: [{
            data: <?php echo $trend['comments']; ?>,
            type: 'line',
            smooth: true
        }]
    };
    myChart.setOption(option);
    // 6.新增评论 图表
    var myChart = echarts.init(document.getElementById("tb4")); 
    var option = {
        xAxis: {
            type: 'category',
            data: <?php echo $trend['date']; ?>
        },
        yAxis: {
            type: 'value'
        },
        color:['#049cff'],
        series: [{
            data: <?php echo $trend['share']; ?>,
            type: 'line',
            smooth: true
        }]
    };
    myChart.setOption(option);
  </script>
<?php endif; ?>
</body>
</html>