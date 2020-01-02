<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"D:\phpstudy_pro\WWW\M15\public/../application/index\view\mcn\agent.html";i:1577952567;}*/ ?>
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
      <!-- 经纪人管理 -->
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body clear">
            <div class="mcn-kol-red-left left">
              <h3>经纪人管理</h3>
              <p style="color: #000;display: inline-block">提示：按经纪人查看红人数据请到“kol管理”中操作</p>
            </div>
            <div class="mcn-kol-red-right right">
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm" style="color:#1ca3fc" id="addAgent">+添加经纪人</button>
            </div>
          </div>
        </div>
      </div>
      <!-- 经纪人列表 -->
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">经纪人列表</div>
          <div class="layui-card-body">

            <!-- 分组循环-->
            <?php if(is_array($macAgents) || $macAgents instanceof \think\Collection || $macAgents instanceof \think\Paginator): $i = 0; $__LIST__ = $macAgents;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
            <div class="fenzu-div1">
              <!-- 标题 -->
              <div class="fenzu-div1-top">
                <h3><?php echo $item['agent_name']; ?><span><?php echo $item['kol_num']; ?>个红人</span></h3>
                <div class="right">
                  <a href="javascript:void(0);" title="" style="color: #34b0fa" agent-id="<?php echo $item['agent_id']; ?>" class="updateAgent">修改经纪人名称</a>
                  <a href="javascript:void(0);" title="" style="color: #f5655d; margin-left: 15px" agent-id="<?php echo $item['agent_id']; ?>" class="delAgent">删除经纪人</a>
                </div>
              </div>
              <!-- 列表 -->
              <ul class="clear">
                <!-- 循环组内红人 -->
                <?php if(is_array($item['kols']) || $item['kols'] instanceof \think\Collection || $item['kols'] instanceof \think\Paginator): $i = 0; $__LIST__ = $item['kols'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$kol): $mod = ($i % 2 );++$i;?>
                <a href="<?php echo url('kol/info',array('kid'=>$kol['kol_id'])); ?>">
                  <li class="layui-col-md3" kol-id="<?php echo $kol['kol_id']; ?>">
                    <div class="mcn-ul-li">
                      <img src="<?php echo $kol['kol_avatar']; ?>" alt="" class="mcn-touxiang">
                      <p class="yichu"><?php echo $kol['kol_nickname']; ?></p>
                      <img src="/static/index/layuiadmin/imgs/1.png" alt="">
                    </div>
                  </li>
                </a>
                <?php endforeach; endif; else: echo "" ;endif; ?>

                <!-- 列表最后一个 添加红人 点击时有弹框 -->
                <a href="#" title="" class="dianji" agent-id="<?php echo $item['agent_id']; ?>">
                  <li class="layui-col-md3">
                    <div class="mcn-ul-li">
                      <p style="text-align: center;display: block; max-width: 100%;color: #34b0fa" >+添加红人</p>
                    </div>
                  </li>
                </a>
              </ul>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>

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
/*  $(".dianji").click(function(){
  $(".mask").show();
});
  $(".guan").click(function(){
  $(".mask").hide();
});*/
$(".dianji").click(function(){
  var agent_id = $(this).attr('agent-id');
  var url = "<?php echo url('McnAgent/viewKol','',false); ?>/agentId/" + agent_id;
  layer.open({
    type: 2
    ,title:'添加红人'
    ,content: url
    ,shadeClose: true
    ,area: ['70%', '60%']
    ,maxmin: true
  });
});

// 添加经纪人
$("#addAgent").click(function(){
  layer.open({
    type: 2
    ,title:'添加分组'
    ,content: "<?php echo url('McnAgent/create'); ?>"
    ,shadeClose: true
    ,area: ['70%', '60%']
    ,maxmin: true
  });
});

// 修改经纪人
$(".updateAgent").click(function(){
  var agent_id = $(this).attr('agent-id');
  var url = "<?php echo url('McnAgent/update','',false); ?>/id/" + agent_id;
  layer.open({
    type: 2
    ,title:'修改经纪人'
    ,content: url
    ,shadeClose: true
    ,area: ['70%', '60%']
    ,maxmin: true
  });
});

// 删除经纪人
$(".delAgent").click(function(){
  var agent_id = $(this).attr('agent-id');
  var url = "<?php echo url('McnAgent/delete','',false); ?>/id/" + agent_id;
  layer.confirm('您确定要删除该经纪人吗？', function(index){
    $.ajax({
      url:url,
      success:function(res){
        layer.msg(res.msg);
        if(res.code)
          setTimeout(function(){window.location.reload()},2000);
      },error:function(){
        layer.msg('服务器错误，请稍后重试！');
      }
    });
    layer.close(index);
  });
});

</script>
</body>
</html>