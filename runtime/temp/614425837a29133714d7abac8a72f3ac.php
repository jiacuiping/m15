<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"/www/wwwroot/newm15/public/../application/index/view/kol/search.html";i:1577345342;}*/ ?>
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
.layui-input-block {margin-left: 70px}
.border-bottom{margin: 15px 0;}
.layui-form-label{color: #000}
.checkinput{background-color: #5597e5;color: #fff !important;}
.addincluded{cursor: pointer;}
.shouluren-xinxi{margin-top: 10px;}
.shouluren-xinxi-left {width: 350px;}
.shouluren-xinxi .left {float: left;}
.shouluren-xinxi-left p{
  overflow: hidden;
  text-overflow:ellipsis;
  white-space: nowrap;
}
.goincluded {display: block;float: right;margin-top: 3px;}
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
      <form class="layui-form" id="searchform" method="POST" action="<?php echo url('search'); ?>">
      <input type="hidden" name="type" id="typeinput">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body">
            <div class="kol-search clear">
              <!-- 右侧  使用帮助 -->
                <a href="#" title="" style="color:#5597e5">
                  <p style="text-align: right"><img src="/static/index/layuiadmin/imgs/wen.png" alt=""> 使用帮助</p>
                </a>
              <!-- 关键词搜索  高级搜索 -->
              <div class="guanjianci-search">
                <a href="javascript:;" class="changesearch" data-type="default"><img id="defaultimg" src="/static/index/layuiadmin/imgs/xuanze1.png" width="20px"> 关键词搜索</a>
                <a href="javascript:;" class="changesearch" data-type="senior"><img id="seniorimg" src="/static/index/layuiadmin/imgs/xuanze2.png" width="20px" style="margin-left: 15px"> 高级搜索</a>
              </div>
              <!-- 搜索 -->
              <div class="right sanbang-right" style="width: 100%">
                <div class="layui-form-item" style="width: 100%">
                  <div class="layui-input-inline sanbang-right-div" style="width: 100%">
                    <input type="" name="keyword" lay-verify="pass" placeholder="请输入关键词" autocomplete="off" class="layui-input" style="width: 100%">
                    <a href="javascript:;" title=""><button type="button" class="layui-btn layui-btn-sm" id="search" style="background-color:#5597e5">搜索</button></a>
                  </div>
                </div>
              </div>

              <p>精确搜索、关联搜索、语义匹配、为您提供强大的抖音号智能搜索功能<span class="right addincluded" style="color:#5597e5 ">添加收录</span></p>

              <div id="SeniorSearch" style="display: none">
                <!-- 所属行业 -->
                <div class="mtd-shipin-fenlei border-bottom clear">
                  <input type="hidden" name="industry" id="industry" value="0">
                  <span class="left mtd-shipin-fenlei-left">所属行业:</span>
                  <div class="left mtd-search-fenlei">
                    <input type="hidden" name="sort" id="sortinput" value="<?php echo $condition['sort']; ?>"> 
                    <a href="javascript:;" class="sorts <?php if($condition['sort'] == 0): ?> checkinput <?php endif; ?>" data-value="0">全部</a>
                    <?php if(is_array($sort) || $sort instanceof \think\Collection || $sort instanceof \think\Paginator): $i = 0; $__LIST__ = $sort;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                      <a href="javascript:;" class="sorts <?php if($condition['sort'] === $item['sort_id']): ?> checkinput <?php endif; ?>" data-value="<?php echo $item['sort_id']; ?>"><?php echo $item['sort_name']; ?></a>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                  </div>
                </div>
                <!-- 内容标签 -->
<!--                 <div class="mtd-shipin-fenlei border-bottom clear">
                  <span class="left mtd-shipin-fenlei-left">内容标签:</span>
                  <div class="left mtd-search-fenlei">
                    <a href="javascript:;">全部</a>
                    <a href="javascript:;">网红美女</a>
                    <a href="javascript:;">网红帅哥</a>
                    <a href="javascript:;">搞笑</a>
                    <a href="javascript:;">情感</a>
                    <a href="javascript:;">剧情</a>
                    <a href="javascript:;">美食</a>
                    <a href="javascript:;">美妆</a>
                    <a href="javascript:;">种草</a>
                    <a href="javascript:;">穿搭</a>
                    <a href="javascript:;">美妆</a>
                    <a href="javascript:;">种草</a>
                    <a href="javascript:;">穿搭</a>
                  </div>
                </div> -->
                <!-- 粉丝数 -->
                <div class="mtd-shipin-fenlei border-bottom clear">
                  <span class="left mtd-shipin-fenlei-left">粉丝数:</span>
                  <div class="left mtd-search-fenlei">
                    <input type="hidden" name="fans" id="fansinput" value="<?php echo $condition['fans']; ?>">
                    <a href="javascript:;" data-value="0" class="fans <?php if($condition['fans'] == 0): ?> checkinput <?php endif; ?>">不限</a>
                    <?php if(is_array($specification['fans']) || $specification['fans'] instanceof \think\Collection || $specification['fans'] instanceof \think\Paginator): $i = 0; $__LIST__ = $specification['fans'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$fansitem): $mod = ($i % 2 );++$i;?>
                      <a href="javascript:;" class="fans <?php if($condition['fans'] === $fansitem['spec_value']): ?> checkinput <?php endif; ?>" data-value="<?php echo $fansitem['spec_value']; ?>"><?php echo $fansitem['spec_title']; ?></a>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                  </div>
                </div>
                <!-- 点赞数 -->
                <div class="mtd-shipin-fenlei border-bottom clear">
                  <span class="left mtd-shipin-fenlei-left">点赞数:</span>
                  <div class="left mtd-search-fenlei">
                    <input type="hidden" name="like" id="likeinput" value="<?php echo $condition['like']; ?>">
                    <a href="javascript:;" data-value="0" class="like <?php if($condition['like'] == 0): ?> checkinput <?php endif; ?>">不限</a>
                    <?php if(is_array($specification['like']) || $specification['like'] instanceof \think\Collection || $specification['like'] instanceof \think\Paginator): $i = 0; $__LIST__ = $specification['like'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$likeitem): $mod = ($i % 2 );++$i;?>
                      <a href="javascript:;" class="like <?php if($condition['like'] === $likeitem['spec_value']): ?> checkinput <?php endif; ?>"" data-value="<?php echo $likeitem['spec_value']; ?>"><?php echo $likeitem['spec_title']; ?></a>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                  </div>
                </div>
                <!-- 飞瓜指数 -->
                <div class="mtd-shipin-fenlei border-bottom clear">
                  <span class="left mtd-shipin-fenlei-left">红人价值:</span>
                  <div class="left mtd-search-fenlei">
                    <input type="hidden" name="hot" id="hotinput" value="<?php echo $condition['hot']; ?>">
                    <a href="javascript:;" data-value="0" class="hot <?php if($condition['hot'] == 0): ?> checkinput <?php endif; ?>">不限</a>
                    <?php if(is_array($specification['kolv']) || $specification['kolv'] instanceof \think\Collection || $specification['kolv'] instanceof \think\Paginator): $i = 0; $__LIST__ = $specification['kolv'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$kolvitem): $mod = ($i % 2 );++$i;?>
                      <a href="javascript:;" class="hot <?php if($condition['hot'] === $kolvitem['spec_value']): ?> checkinput <?php endif; ?>" data-value="<?php echo $kolvitem['spec_value']; ?>"><?php echo $kolvitem['spec_title']; ?></a>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                  </div>
                </div>
                <!-- 地域 -->
                <div class="mtd-shipin-fenlei border-bottom clear">
                  <div class="layui-col-md12">
                    <form class="layui-form" action="">
                      <div class="layui-form-item">
                        <label class="layui-form-label">地域：</label>
                        <div class="layui-input-inline">
                          <select name="province">
                            <option value="0" <?php if($condition['province'] == 0): ?> selected='' <?php endif; ?>>全国</option>
                            <?php if(is_array($citys) || $citys instanceof \think\Collection || $citys instanceof \think\Paginator): $i = 0; $__LIST__ = $citys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$prov): $mod = ($i % 2 );++$i;?>
                              <option value="<?php echo $prov['id']; ?>" <?php if($condition['province'] == $prov['id']): ?> selected='' <?php endif; ?>><?php echo $prov['name']; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                          </select>
                        </div>
<!--                         <div class="layui-input-inline">
                          <select name="quiz2">
                            <option value="" disabled="">地区</option>
                            <option value="杭州">杭州</option>
                            <option value="宁波">宁波</option>
                            <option value="温州">温州</option>
                          </select>
                        </div> -->
                      </div>
                    </form>
                  </div>
                </div>
                <!-- 性别 年龄 -->
                <div class="mtd-shipin-fenlei border-bottom clear">
                  <div class="layui-col-md6">
                    <form class="layui-form" action="">
                      <div class="layui-form-item">
                        <label class="layui-form-label">性别:</label>
                        <div class="layui-input-block">
                          <select name="sex" lay-filter="aihao">
                            <option value="0" <?php if($condition['sex'] == 0): ?> selected="" <?php endif; ?>>不限</option>
                            <option value="1" <?php if($condition['sex'] == 1): ?> selected="" <?php endif; ?>>男</option>
                            <option value="2" <?php if($condition['sex'] == 2): ?> selected="" <?php endif; ?>>女</option>
                          </select>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="layui-col-md6">
                    <form class="layui-form" action="">
                      <div class="layui-form-item">
                        <label class="layui-form-label">年龄:</label>
                        <div class="layui-input-block">
                          <select name="age" lay-filter="aihao">
                            <option value="0" <?php if($condition['age'] === 0): ?> selected="" <?php endif; ?>>不限</option>
                            <option value="10-15" <?php if($condition['age'] === '10-15'): ?> selected="" <?php endif; ?>>10-15</option>
                            <option value="15-25" <?php if($condition['age'] === '15-25'): ?> selected="" <?php endif; ?>>15-25</option>
                            <option value="25-35" <?php if($condition['age'] === '25-35'): ?> selected="" <?php endif; ?>>25-35</option>
                            <option value="35-45" <?php if($condition['age'] === '35-45'): ?> selected="" <?php endif; ?>>35-45</option>
                            <option value="45-99999" <?php if($condition['age'] === '45-99999'): ?> selected="" <?php endif; ?>>>45</option>
                          </select>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <!-- 条件 -->
<!--                 <div class="mtd-shipin-fenlei border-bottom clear">
                  <div class="layui-col-md12">
                    <form class="layui-form" action="">
                      <div class="layui-form-item" pane="">
                        <label class="layui-form-label">条件:</label>
                        <div class="layui-input-block">
                          <input type="checkbox" name="like1[write]" lay-skin="primary" title="个人认证号" checked="">
                          <input type="checkbox" name="like1[read]" lay-skin="primary" title="机构认证号">
                          <input type="checkbox" name="like1[game]" lay-skin="primary" title="已开通商品橱窗">
                        </div>
                      </div>
                    </form>
                  </div>
                </div> -->
                <!-- 认证信息 -->
                <div class="mtd-shipin-fenlei border-bottom clear">
                  <div class="layui-col-md12">
                    <form class="layui-form" action="">
                      <div class="layui-form-item">
                        <label class="layui-form-label">认证信息:</label>
                        <div class="layui-input-block">
                          <input type="text" name="vname" lay-verify="title" autocomplete="off" placeholder="请输入认证信息关键词进行搜索，示例：抖音人气好物推荐官" class="layui-input" style="width: 95%">
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <!-- 粉丝画像筛选 -->
                <div class="mtd-shipin-fenlei border-bottom clear">
                  <p style="color: #000； margin-bottom: 15px">粉丝画像筛选:</p>
                  <div class="layui-col-md6">
                    <form class="layui-form" action="">
                      <div class="layui-form-item">
                        <label class="layui-form-label">男女比例不限:</label>
                        <div class="layui-input-block" style="margin-left: 118px">
                          <select name="interest" lay-filter="aihao">
                            <option value="" selected="">不限</option>
                            <option value="0">男</option>
                            <option value="1">女</option>
                          </select>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="layui-col-md6">
                    <form class="layui-form" action="">
                      <div class="layui-form-item">
                        <label class="layui-form-label">主要年龄:</label>
                        <div class="layui-input-block" style="margin-left: 90px">
                          <select name="interest" lay-filter="aihao">
                            <option value="" selected="">不限</option>
                            <option value="0">10-20</option>
                            <option value="1">20-40</option>
                          </select>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="layui-col-md12">
                    <form class="layui-form" action="">
                      <div class="layui-form-item">
                        <label class="layui-form-label">主要地域：</label>
                        <div class="layui-input-inline">
                          <select name="quiz1">
                            <option value="" selected="">省份</option>
                            <option value="浙江">浙江省</option>
                            <option value="你的工号">江西省</option>
                            <option value="你最喜欢的老师">福建省</option>
                          </select>
                        </div>
                        <div class="layui-input-inline">
                          <select name="quiz2">
                            <option value="" disabled="">地区</option>
                            <option value="杭州">杭州</option>
                            <option value="宁波">宁波</option>
                            <option value="温州">温州</option>
                          </select>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    </div>
  </div>
  <div>
    
    <div class="layui-col-md12" id="included" style="display:none">
      <div class="shoulu-one" style="border:0px;">
        <!-- <h3>添加收录弹框</h3> -->
        <p style="color: #000">收录抖音号</p>
          <!-- 搜索 -->
          <div class="right sanbang-right" style="width: 100%">
            <div class="layui-form-item" style="width: 100%">
              <div class="layui-input-inline sanbang-right-div" style="width: 100%">
                <input type="text" placeholder="请输入抖音号ID或主页链接" autocomplete="off" class="layui-input" id="includedinp" style="width: 100%">
                <button type="button" class="layui-btn layui-btn-sm includedbat" style="background-color:#5597e5">搜索</button>
              </div>
            </div>
          </div>
          <div id="searchcentent"></div>
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

// 2.下拉
layui.use(['form', 'layedit', 'laydate'], function(){
  var form = layui.form
  ,layer = layui.layer
  ,layedit = layui.layedit
  ,laydate = layui.laydate
  ,     $  = layui.$;
  
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
  

  $(".addincluded").click(function(){
    layer.open({
      type: 1,
      area:['590px','300px'],
      shade: false,
      title: false, //不显示标题
      content: $('#included'),
    });
  });

  $(".includedbat").click(function(){

    var keyword = $("#includedinp").val();

    if(keyword == '')
      layer.msg('请输入抖音号或者UID');
    else{
      $.ajax({
        url:"<?php echo url('included'); ?>",
        type:"GET",
        data:{keyword:keyword},
        success:function(res){
          if(res.code == 1){
            var html = '';
            $.each(res.data,function(key,value){

              html +=  "<div class='shouluren-xinxi clear'>"+
                        "<img src='"+value.avatar+"' class='left'>"+
                        "<div class='left shouluren-xinxi-left'>"+
                          "<p style='color: #000'>"+value.nickname+"</p>"+
                          "<p>抖音号："+value.access+"</p>"+
                          "<p>简介："+value.signature+"</p>"+
                        "</div>"+
                        "<button type='button' class='layui-btn layui-btn-normal goincluded' data-value='"+value.uid+"'>确认收录</button>"+
                        "<button type='button' class='layui-btn layui-btn-normal goincluded all' data-value='"+value.uid+"'>收录全部</button>"+
                      "</div>";
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

  $("body").on('click','.goincluded',function(){
    
    var thisobj = $(this);

    var isall = $(this).hasClass('all') ? 1 : 0;

    $.ajax({
      url:"<?php echo url('goincluded'); ?>",
      data:{uid:thisobj.attr('data-value'),isall:isall},
      type:"GET",
      beforeSend:function(XMLHttpRequest){
        thisobj.text('收录中...');
      },success:function(res){
        if(res.code == 1){
          thisobj.text(res.msg);
          layer.msg(res.msg);
        }else
          thisobj.text('收录失败');        
      },complete:function(XMLHttpRequest,textStatus){
        thisobj.text('收录完成');
      },error:function(){
        layer.msg('网络错误，请稍后重试');
      }
    });
  });

  $(".changesearch").click(function(){
    if($(this).attr('data-type') == 'default'){
      $("#SeniorSearch").hide();
      $("#typeinput").val('default');
      $("#defaultimg").attr('src','/static/index/layuiadmin/imgs/xuanze1.png');
      $("#seniorimg").attr('src','/static/index/layuiadmin/imgs/xuanze2.png');
    }else{
      $("#SeniorSearch").show();
      $("#typeinput").val('senior');
      $("#defaultimg").attr('src','/static/index/layuiadmin/imgs/xuanze2.png');
      $("#seniorimg").attr('src','/static/index/layuiadmin/imgs/xuanze1.png');
    }
  });

  //分类筛选
  $(".sorts").click(function(){
    $(".sorts").removeClass('checkinput');
    $(this).addClass('checkinput');
    $("#sortinput").val($(this).attr('data-value'));
  });

  $(".fans").click(function(){
    $(".fans").removeClass('checkinput');
    $(this).addClass('checkinput');
    $("#fansinput").val($(this).attr('data-value'));
  })

  $(".like").click(function(){
    $(".like").removeClass('checkinput');
    $(this).addClass('checkinput');
    $("#likeinput").val($(this).attr('data-value'));
  })

  $(".hot").click(function(){
    $(".hot").removeClass('checkinput');
    $(this).addClass('checkinput');
    $("#hotinput").val($(this).attr('data-value'));
  })

  $("#search").click(function(){
    $("#searchform").submit();
  });
});
</script>
</html>