<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"D:\phpstudy_pro\WWW\M15\public/../application/index\view\mcn\kol.html";i:1578029528;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>kol管理</title>
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
.mtd-shipin-td-btn a,.mtd-shipin-td-btn button{display: block !important;float: left !important;width: 73px !important;}
.layui-input-block {margin-left: 70px}
.shouluren-xinxi{margin-top: 10px;}
.shouluren-xinxi-left {width: 350px;}
.shouluren-xinxi .left {float: left;}
.shouluren-xinxi-left p{
  overflow: hidden;
  text-overflow:ellipsis;
  white-space: nowrap;
}
.claimKol {display: block;float: right;margin-top: 20px;}
.claimAll {margin-top: 5px;}
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
              <p>官网：<a href="<?php echo $data['mcn_website']; ?>" target="_blank"><?php echo $data['mcn_website']; ?></a></p>
              <p>MCN官方抖音号：<?php echo $data['mcn_officialaccount']; ?></p>
            </div>
          </div>
        </div>
      </div>
      <!-- 红人管理 -->
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body clear">
            <div class="mcn-kol-red-left left">
              <h3>红人搜索</h3>
              <!-- 搜索 -->
              <div class="layui-form-item">
                <div class="layui-input-inline sanbang-right-div" style="width: 210px">
                  <input type="" name="" lay-verify="pass" placeholder="请输入Kol关键字" autocomplete="off" class="layui-input">
                  <button type="button" class="layui-btn layui-btn-sm" style="background-color: #1ca3fc;"><i class="layui-icon layui-icon-search"></i></button>
                </div>
              </div>
              <p style="color: #000;display: inline-block">共<?php echo count($kol); ?>个红人</p>
            </div>
            <div class="mcn-kol-red-right right clear">
              <!-- 默认排序 -->
              <form class="layui-form left" action="">
                <div class="layui-form-item">
                  <div class="layui-input-block">
                    <select name="interest" lay-filter="aihao">
                      <option value="0" selected="">默认排序</option>
                      <option value="1">写作</option>
                      <option value="2">阅读</option>
                    </select>
                  </div>
                </div>
              </form>
              <!-- 时间选项 -->
              <div class="mtd-shipin-tab-right mtd-shipin-td-btn left" style="margin: 0 10px">
                <button type="button" class="layui-btn layui-btn-sm mtd-shipin-btn-checked">按平台</button>
                <button type="button" class="layui-btn layui-btn-sm">按分组</button>
                <button type="button" class="layui-btn layui-btn-sm">按经纪人</button>
              </div>
              <!-- 全部平台 -->
              <form class="layui-form" action="">
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
              <!-- +认领红人 -->
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm insert" id="claim" style="color:#1ca3fc">+认领红人</button>
            </div>
          </div>
        </div>
      </div>
      <!-- 收藏的信息 -->
      <?php if(is_array($kol) || $kol instanceof \think\Collection || $kol instanceof \think\Paginator): $i = 0; $__LIST__ = $kol;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
        <div class="layui-col-md12">
          <div class="layui-card">
            <div class="layui-card-body">
              <div class="mtd-shipin-index-div clear">
                  <div class="mtd-shipin-div-left">
                    <img src="<?php echo $item['kol_avatar']; ?>" alt="" class="mtd-list1-shenyang-left-img">
                    <div class="mtd-list1-shenyang-div">
                      <img src="/static/index/layuiadmin/imgs/10.png" alt="" style="width: 18px"><?php echo $item['kt_hot']; ?>
                    </div>
                  </div>
                  <div class="mtd-shipin-div-center">
                    <h5><?php echo $item['kol_nickname']; ?></h5>
                    <p>分类：<?php echo $item['kol_sort']; ?></p>
                    <p>报价：280,000（1-20秒）、420,000元（20-60秒）</p>
                    <p>所属经纪人：<?php echo $item['agent']; ?></p>
                  </div>
                  <div class="mtd-shipin-div-right mcn-kol-div-rigth right">
                    <div class="layui-col-md10 mcn-kol-div-rigth-l">
                      <ul class="clear" style="border-bottom: 1px solid #eee">
                        <li><h3><?php echo $item['kt_fans']; ?></h3><p>粉丝总数</p></li>
                        <li><h3>526.0万</h3><p>粉丝质量</p></li>
                        <li><h3><?php echo $item['kt_mean_like']; ?></h3><p>集均点赞 <img src="/static/index/layuiadmin/imgs/wen2.png" alt=""></p></li>
                        <li><h3><?php echo $item['kt_mean_comment']; ?></h3><p>集均评论 <img src="/static/index/layuiadmin/imgs/wen2.png" alt=""></p></li>
                        <li><h3><?php echo $item['kt_mean_share']; ?></h3><p>集均分享 <img src="/static/index/layuiadmin/imgs/wen2.png" alt=""></p></li>
                      </ul>
                      <ul class="clear">
                        <li><h3><?php echo $item['kt_inc_fans']; ?></h3><p>昨日新增粉丝</p></li>
                        <li><h3><?php echo $item['statistical']['weekfans']; ?></h3><p>本周新增粉丝</p></li>
                        <li><h3><?php echo $item['statistical']['lweekfans']; ?></h3><p>上周新增粉丝</p></li>
                        <li><h3><?php echo $item['statistical']['monthfans']; ?></h3><p>本月新增粉丝</p></li>
                        <li><h3><?php echo $item['statistical']['lmonthfans']; ?></h3><p>上月新增粉丝</p></li>
                      </ul>
                    </div>
                    <div class="layui-col-md2 mcn-kol-div-rigth-r">
                      <p>0</p>
                      <p>竞品红人</p>
                    </div>
                  </div>
              </div>
              <div class="button5" style="text-align: right">
                <?php if($item['mk_isshow'] == 1): ?>
                  <button type="button" class="layui-btn layui-btn-primary layui-btn-sm isshow" data-value="<?php echo $item['kol_id']; ?>" data-type="0" style="color:#1ca3fc">隐藏红人</button>
                <?php else: ?>
                  <button type="button" class="layui-btn layui-btn-primary layui-btn-sm isshow" data-value="<?php echo $item['kol_id']; ?>" data-type="1" style="color:#1ca3fc">展示红人</button>
                <?php endif; if($item['mk_agent'] == 0): ?>
                  <button type="button" class="layui-btn layui-btn-primary layui-btn-sm agent" data-value="<?php echo $item['kol_id']; ?>" style="color:#1ca3fc">分配经纪人</button>
                <?php endif; ?>
                <button type="button" class="layui-btn layui-btn-primary layui-btn-sm remove" data-value="<?php echo $item['kol_id']; ?>" style="color:#f25b52">解除认领</button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
  </div>

  <div class="layui-col-md12" id="included" style="display:none">
    <div class="shoulu-one" style="border:0px;">
      <!-- <h3>添加收录弹框</h3> -->
      <p style="color: #000">认领红人</p>
      <!-- 搜索 -->
      <div class="right sanbang-right" style="width: 100%">
        <div class="layui-form-item" style="width: 100%">
          <div class="layui-input-inline sanbang-right-div" style="width: 100%">
            <input type="text" placeholder="请输入抖音号或红人名称" autocomplete="off" class="layui-input" id="includedinp" style="width: 100%">
            <button type="button" class="layui-btn layui-btn-sm includedbat" style="background-color:#5597e5">搜索</button>
          </div>
        </div>
      </div>
      <div id="searchcentent"></div>
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

  //展示或隐藏
  $(".isshow").click(function(){
    $.ajax({
      url:"<?php echo url('KolShow'); ?>",
      data:{id:$(this).attr('data-value'),type:$(this).attr('data-type')},
      success:function(res){
        layer.msg(res.msg);
        if(res.code == 1)
          setTimeout(function(){window.location.reload()},2000);
      },error:function(){
        layer.msg('服务器错误，清稍后重试！');
      }
    })
  });

  // 分配经纪人
  $(".agent").click(function () {
    var kolId = $(this).attr('data-value');
    var url = "<?php echo url('McnAgent/agentList','',false); ?>/kol_id/" + kolId;
    layer.open({
      type: 2
      ,title:'分配经纪人'
      ,content: url
      ,shadeClose: true
      ,area: ['70%', '60%']
      ,maxmin: true
    });
  });


  // 解除认领
  $(".remove").click(function(){

    var id = $(this).attr('data-value');

    layer.confirm('您确定要解除该红人与您的关系吗？', {
      btn: ['确定','取消'] //按钮
    }, function(){
      $.ajax({
        url:"<?php echo url('Kolremove'); ?>",
        data:{id:id},
        success:function(res){
          layer.msg(res.msg);
          if(res.code == 1)
            setTimeout(function(){window.location.reload()},2000);
        },error:function(){
          layer.msg('服务器错误，清稍后重试！');
        }
      });
    });
  });

  // 点击认领红人
  $("#claim").click(function () {
    layer.open({
      type: 1,
      area:['590px','300px'],
      shade: false,
      title: false, //不显示标题
      content: $('#included'),
    });
  });

  // 搜索红人
  $(".includedbat").click(function(){

    var keyword = $("#includedinp").val();

    if(keyword == '')
      layer.msg('请输入抖音号或者UID');
    else{
      $.ajax({
        url:"<?php echo url('KolList'); ?>",
        type:"GET",
        data:{keyword:keyword},
        success:function(res){
          if(res.code == 1){
            var html = '';
            html += "<button type='button' class='layui-btn layui-btn-normal claimAll'>认领全部</button>";
            $.each(res.data,function(key,value){

              html +=  "<div class='shouluren-xinxi clear'>"+
                      "<img src='"+value.kol_avatar+"' class='left'>"+
                      "<div class='left shouluren-xinxi-left'>"+
                      "<p style='color: #000'>"+value.kol_nickname+"</p>"+
                      "<p>抖音号："+value.kol_number+"</p>"+
                      "<p>简介："+value.kol_signature+"</p>"+
                      "</div>";
              if(value.isClaim) {
                html += "<button type='button' disabled='true' class='layui-btn layui-btn-disabled' data-value='"+value.kol_id+"'>已发送认领</button>";
              } else {
                html += "<button type='button' class='layui-btn layui-btn-normal claimKol' data-value='"+value.kol_id+"'>确认认领</button>";
              }
              html += "</div>";
            });

          }else
            var html = "<p class='clear' style='text-align:center;margin-top80px'>"+ res.msg +"</p>";

          $("#searchcentent").empty();
          $("#searchcentent").append(html);

        },error:function(){

        }
      });
    }
  });

  // 确认认领
  $("body").on('click','.claimKol',function(){
    var obj = $(this);
    var kolId = obj.attr('data-value');
    $.ajax({
      url:"<?php echo url('confirmClaim'); ?>",
      data:{kolId:kolId,isall:0},
      type:"GET",
      beforeSend:function(XMLHttpRequest){
        obj.text('认领中...');
      },success:function(res){
        if(res.code == 1){
          obj.text(res.msg);
          layer.msg(res.msg);
        }else
          obj.text('认领失败');
      },complete:function(XMLHttpRequest,textStatus){
        obj.removeClass('layui-btn-normal');
        obj.addClass('layui-btn-disabled');
        obj.text('已发送认领');
      },error:function(){
        layer.msg('网络错误，请稍后重试');
      }
    });
  });

  // 全部认领
  $("body").on('click','.claimAll',function(){
    var obj = $(this);
    var kolId = [];

    $(".claimKol").each(function() {
      if($(this).hasClass('layui-btn-normal')) {
        kolId.push($(this).attr("data-value"));
      }
    });

    $.ajax({
      url:"<?php echo url('confirmClaim'); ?>",
      data:{kolId:kolId,isall:1},
      type:"GET",
      beforeSend:function(XMLHttpRequest){
        obj.text('认领中...');
      },success:function(res){
        if(res.code == 1){
          obj.text(res.msg);
          layer.msg(res.msg);
        }else
          obj.text('认领失败');
      },complete:function(XMLHttpRequest,textStatus){
        obj.removeClass('layui-btn-normal claimKol');
        obj.addClass('layui-btn-disabled');
        obj.text('已发送认领');

        $('.claimKol').removeClass('layui-btn-normal');
        $('.claimKol').addClass('layui-btn-disabled');
        $('.claimKol').text('已发送认领');
      },error:function(){
        layer.msg('网络错误，请稍后重试');
      }
    });
  });



});
</script>
</body>
</html>