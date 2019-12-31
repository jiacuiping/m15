<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"/www/wwwroot/newm15/public/../application/index/view/kol/rank.html";i:1574909222;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>kol榜单</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/index/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/admin.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/style.css" media="all">
</head>
<style>
  .layui-input-block{margin-left: 0}
  .layui-input{padding: 0 30px 0 30px;}
  .layui-input-block{position: relative}
  .layui-input-block i{position: absolute; top: 11px; left: 10px}
  .layui-input-block input{border-radius: 10px}
  .layui-input-block span{position: absolute; top: 11px; right: 10px}
  select{width: 100%;margin-top: 5px;height: 30px}
  .layui-form-item{margin-bottom: 0;display: inline-block}
  .layui-form-label{text-align: left;padding:9px 0 }
  .mtd-shipin-td-btn a,.mtd-shipin-td-btn button{display: block !important;float: left !important;width: 73px !important;}
  .mtd-shipin-fenlei-right .checked{background-color: #1E9FFF;color:#fff;}
  .ranktab{width: 33%;float:left;padding: 0px;text-align: center;cursor:pointer}
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
      <form id="myform" method="" action="<?php echo url('rank'); ?>">
        <div class="layui-col-md12">
          <div class="layui-card layui-card-padding">
            <!-- 第一行  所属省份 -->
            <div class="mtd-shipin-fenlei border-bottom clear">
              <span class="left mtd-shipin-fenlei-left" style="width: 60px;text-align: center;">所属省份:</span>
              <div class="left mtd-shipin-fenlei-right" style="width: 95%;">
                <input type="hidden" name="city" id="cityinput" value="<?php echo $condition['city']; ?>">
                <a href="javascript:;" data-value="0" class="citys <?php if($condition['city'] == 0): ?> checked <?php endif; ?>">全部</a>
                <?php if(is_array($citys) || $citys instanceof \think\Collection || $citys instanceof \think\Paginator): $i = 0; $__LIST__ = $citys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$city): $mod = ($i % 2 );++$i;?>
                  <a href="javascript:;" class="citys <?php if($condition['city'] == $city['id']): ?> checked <?php endif; ?>" data-value="<?php echo $city['id']; ?>"><?php echo $city['shortname']; ?></a>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </div>
            </div>
            <!-- 第二行  分类 -->
            <div class="mtd-shipin-fenlei border-bottom clear" style="padding:10px 0 ">
              <span class="left mtd-shipin-fenlei-left" style="width: 60px;text-align: center;">所属分类:</span>
              <div class="left mtd-shipin-fenlei-right" style="width:76%;">
                <input type="hidden" name="sort" id="sortinput" value="<?php echo $condition['sort']; ?>">
                <a href="javascript:;" data-value="0" class="sorts <?php if($condition['sort'] == 0): ?> checked <?php endif; ?>">全部</a>
                <?php if(is_array($sort) || $sort instanceof \think\Collection || $sort instanceof \think\Paginator): $i = 0; $__LIST__ = $sort;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                  <a href="javascript:;" class="sorts <?php if($condition['sort'] == $item['sort_id']): ?> checked <?php endif; ?>" data-value="<?php echo $item['sort_id']; ?>"><?php echo $item['sort_name']; ?></a>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </div>
              <div class="right mtd-shipin-fenlei-sousuo">
                <form class="layui-form" action="">
                  <div class="layui-form-item">
                    <div class="layui-input-block">
                      <i class="layui-icon layui-icon-search"></i>
                      <input type="text" name="keyword" lay-verify="title" autocomplete="off" placeholder="请输入关键词" class="layui-input" value="<?php echo $condition['keyword']; ?>">
                      <span class="layui-icon layui-icon-return searchbat"></span>
                    </div>
                  </div>
                </form>
                <a href="#" title=""><img src="/static/index/layuiadmin/imgs/wen.png" alt=""></a>
              </div>
            </div>
            <!-- 第三行  日榜周榜月榜 -->
            <div class="sanbang clear border-bottom">
              <div class="left sanbang-left mtd-shipin-td-btn" style="width:220px;">
                <input type="hidden" name="createtime" id="createtime" value="<?php echo $condition['createtime']; ?>">
                <button type="button" data-value="1" class="layui-btn-sm ranktab <?php if($condition['createtime'] == '1'): ?> mtd-shipin-btn-checked <?php endif; ?>">日榜</button>
                <button type="button" data-value="7" class="layui-btn-sm ranktab <?php if($condition['createtime'] == '7'): ?> mtd-shipin-btn-checked <?php endif; ?>">周榜</button>
                <button type="button" data-value="30" class="layui-btn-sm ranktab <?php if($condition['createtime'] == '30'): ?> mtd-shipin-btn-checked <?php endif; ?>">月榜</button>
              </div>
              <div class="right">
                <p>数据更新时间 &nbsp;&nbsp;&nbsp;<button type="button" class="layui-btn layui-btn-xs layui-btn-normal">导出</button></p>
              </div>
            </div>
            <!-- 第四行  tab选项卡 -->
            <div class="mtd-shipin-tab clear">  
              <div class="mtd-shipin-tab-left">
                <input name="order" type="hidden" id="orderinput" value="<?php echo $condition['order']; ?>">
                <ul class="clear">
                  <a href="javascript:;"><li class="ordera <?php if($condition['order'] == 'kt.kt_hot desc'): ?> checked-bottom <?php endif; ?>" data-value="kt.kt_hot desc">指数排行</li></a>
                  <a href="javascript:;"><li class="ordera <?php if($condition['order'] == 'kt.kt_inc_fans desc'): ?> checked-bottom <?php endif; ?>" data-value="kt.kt_inc_fans desc">涨粉排行榜</li></a>
                  <a href="javascript:;"><li class="ordera <?php if($condition['order'] == 'kt.kt_growth desc'): ?> checked-bottom <?php endif; ?>" data-value="kt.kt_growth desc">成长排行榜</li></a>
                  <a href="javascript:;"><li class="ordera <?php if($condition['order'] == 'lanv desc'): ?> checked-bottom <?php endif; ?>" data-value="lanv">蓝V排行榜</li></a>
                </ul>
              </div>
              <!-- 右边时间选项 -->
              <div class="mtd-shipin-tab-right right">
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
            </div>
            </div>
          </div>
        </form>
      </div>
      <!-- 列表 -->
      <div class="layui-col-md12">
        <div class="layui-form">
          <table class="layui-table" lay-even="" lay-skin="nob">
            <colgroup>
              <col width="120">
              <col width="200">
              <col width="100">
              <col width="100">
              <col width="100">
              <col width="100">
              <col width="150">
              <col width="120">
            </colgroup>
            <thead>
              <tr class="mtd-shipin-tr">
                <th>排名</th>
                <th style="text-align: left">播主</th>
                <th>粉丝数</th>
                <th>平均点赞</th>
                <th>平均评论</th>
                <th>平均转发</th>
                <th>价值指数</th>
                <th>操作</th>
              </tr> 
            </thead>
            <tbody>
              <?php if(is_array($kol) || $kol instanceof \think\Collection || $kol instanceof \think\Paginator): $i = 0; $__LIST__ = $kol;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
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
                    <img src="<?php echo $item['kol_avatar']; ?>" alt=""style="width: 50px; height: 50px;border-radius: 50%"><p class="yichu"><?php echo $item['kol_nickname']; ?></p>
                  </td>
                  <td><?php echo $item['kt_fans']; ?></td>
                  <td class="color8c"><?php echo $item['kt_mean_like']; ?></td>
                  <td class="color8c"><?php echo $item['kt_mean_comment']; ?></td>
                  <td class="color8c"><?php echo $item['kt_mean_share']; ?></td>
                  <td style="color: red"><img src="/static/index/layuiadmin/imgs/10-2.png" class="shengzhi"><?php echo $item['kt_hot']; ?></td>
                  <td class="mtd-shipin-td-btn">
                    <a href="<?php echo url('info',array('kid'=>$item['kol_id'])); ?>" title="" class="mtd-shipin-td-btn">
                      <button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo1.png" alt="">详情</button>
                    </a>
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

// 2.日期范围
layui.use(['laydate','layer','form'], function(){
  var laydate = layui.laydate
      ,layer = layui.layer
      ,form = layui.form
      ,$ = layui.$;

    //分类筛选
    $(".sorts").click(function(){
      $("#sortinput").val($(this).attr('data-value'));
      GetDataFunction();
    });

    //省份筛选
    $(".citys").click(function(){
      $("#cityinput").val($(this).attr('data-value'));
      GetDataFunction();
    });
    //关键词筛选
    $(".searchbat").click(function(){
      $("#myform").submit();
    });
    //时间筛选
    $(".ranktab").click(function(){
      $("#createtime").val($(this).attr('data-value'));
      $("#myform").submit();
    });
    //排序
    $(".ordera").click(function(){
      $("#orderinput").val($(this).attr('data-value'));
      GetDataFunction();
    });
    function GetDataFunction(){
      $("#myform").submit();
    }

  laydate.render({
    elem: '#test6'
    ,range: true
  });
});
  </script>
</body>
</html>