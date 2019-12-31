<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"D:\phpstudy_pro\WWW\M15\public/../application/index\view\mcn\group.html";i:1577786422;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>分组管理</title>
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
      <!-- 分组管理 -->
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body clear">
            <div class="mcn-kol-red-left left">
              <h3>分组管理</h3>
              <p style="color: #000;display: inline-block">提示：按分组查看数据青岛：“数据概览”中操作</p>
            </div>
            <div class="mcn-kol-red-right right">
              <button type="button" id="addGroup" class="layui-btn layui-btn-primary layui-btn-sm" style="color:#1ca3fc">+添加分组</button>
            </div>
          </div>
        </div>
      </div>
      <!-- 红人分组列表 -->
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">红人分组列表</div>
          <div class="layui-card-body">

            <!-- 分组循环-->
            <?php if(is_array($macGroups) || $macGroups instanceof \think\Collection || $macGroups instanceof \think\Paginator): $i = 0; $__LIST__ = $macGroups;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
            <div class="fenzu-div1">
              <!-- 标题 -->
              <div class="fenzu-div1-top">
                <h3><?php echo $item['group_name']; ?><span><?php echo $item['kol_num']; ?>个红人</span></h3>
                <div class="right">
                  <a href="javascript:void(0);" title="" style="color: #34b0fa" group-id="<?php echo $item['group_id']; ?>" class="updateGroup">修改分组名称</a>
                  <a href="javascript:void(0);" title="" style="color: #f5655d; margin-left: 15px" group-id="<?php echo $item['group_id']; ?>">删除分组</a>
                </div>
              </div>
              <!-- 列表 -->
              <ul class="clear">
                <!-- 循环组内红人 -->
                <?php if(is_array($item['kols']) || $item['kols'] instanceof \think\Collection || $item['kols'] instanceof \think\Paginator): $i = 0; $__LIST__ = $item['kols'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$kol): $mod = ($i % 2 );++$i;?>
                <li class="layui-col-md3" kol-id="<?php echo $kol['kol_id']; ?>">
                  <div class="mcn-ul-li">
                    <img src="<?php echo $kol['kol_avatar']; ?>" alt="" class="mcn-touxiang">
                    <p class="yichu"><?php echo $kol['kol_nickname']; ?></p>
                    <img src="/static/index/layuiadmin/imgs/1.png" alt="">
                  </div>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; ?>

                <!-- 列表最后一个 添加红人 点击时有弹框 -->
                <a href="#" title="" class="dianji">
                  <li class="layui-col-md3">
                    <div class="mcn-ul-li">
                      <p style="text-align: center;display: block; max-width: 100%;color: #34b0fa">+添加红人</p>
                    </div>
                  </li>
                </a>
              </ul>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>

          </div>
        </div>
      </div>

      <!-- 点击红人时出现遮罩层 -->
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

  // 添加分组
  $("#addGroup").click(function(){
    layer.open({
      title:'添加分组'
      ,content: "<?php echo url('McnGroup/create'); ?>"
      ,shadeClose: true
      ,area: ['70%', '60%']
      ,maxmin: true
    });
  });

  // 修改分组
  $(".updateGroup").click(function(){
    var group_id = $(this).attr('group-id');
    var url = "<?php echo url('McnGroup/update','',false); ?>/id/" + group_id;
    layer.open({
      title:'修改分组'
      ,content: url
      ,shadeClose: true
      ,area: ['70%', '60%']
      ,maxmin: true
    });
  });

  // 删除分组
  /*$(".delGroup").click(function(){
    var url = "<?php echo url('delete','',false); ?>/id/" + dataid;
    layer.confirm('您确定要删除该分类吗？', function(index){
      $.ajax({
        url:url,
        success:function(res){
          layer.msg(res.msg);
          if(res.code)
            setTimeout(function(){window.location.reload()},2000);
        },error:function(){
          layer.msg('服务器错误，请稍后重试！');
        }
      })
      layer.close(index);
    });
  });*/



</script>
</body>
</html>