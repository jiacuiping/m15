<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"/www/wwwroot/newm15/public/../application/index/view/user/index.html";i:1576662412;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>登录记录</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/index/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/admin.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/style.css" media="all">
</head>
<style>
  .mtd-shipin-tab-left2{width: 100%}
  .mtd-shipin-tab-left2 ul li{width: 23%;text-align: center}
  .layui-form-label{width: 80px !important;}
  .layui-input-inline{width: 30.2% !important;}
  .changebat {width: 20%;height: 40px;background-color: #1e9fff;text-align: center;line-height: 40px;color: #fff;border-radius: 5px;margin: 25px auto;cursor: pointer;}
</style>
<body>
  <!-- 点击shipin里的详情按钮跳到此页 -->
  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <!-- 标题 -->



      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body layui-shenhuilu clear">
            <img src="<?php echo $user['user_avatar']; ?>" alt="" class="left">
            <div class="layui-shenhuilu-r left">
              <h4><?php echo $user['user_name']; ?><span class="mianfei-ban"><?php echo $user['user_vip']['level_name']; ?></span></h4>
              <p>关联抖音号：<?php echo $user['dyaccount']; ?></p>
              <p>账号类型：<?php if($user['user_type'] == 2): ?> 讲师 <?php elseif($user['user_type'] == 3): ?> 广告商 <?php elseif($user['user_type'] == 4): ?> MCN <?php else: ?> 普通用户 <?php endif; ?></p>
            </div>
          </div>
          <div class="layui-card-body">
            <?php if($user['user_mobile'] == ''): ?>
              <p>绑定手机使用账号登录<span class="mianfei-ban">推荐绑定</span>
                <a href="#" style="color: #349adc;margin-left: 20px;">点击绑定</a>
              </p>
              <p>绑定手机号后可设定登录密码</p>
            <?php endif; ?>
          </div>
        </div>
      </div>  
      <!-- tab导航条 -->
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body" style="padding: 10px 15px 0 15px;">
            <div class="mtd-shipin-tab-left mtd-shipin-tab-left2">
              <ul class="clear">
                <li><a href="<?php echo url('index'); ?>" <?php if($type == 'log'): ?> class="checked-bottom" <?php endif; ?>>登录记录</a></li>
                <li><a href="<?php echo url('index',array('type'=>'invoice')); ?>" <?php if($type == 'invoice'): ?> class="checked-bottom" <?php endif; ?>>发票信息</a></li>
                <li><a href="<?php echo url('index',array('type'=>'address')); ?>" <?php if($type == 'address'): ?> class="checked-bottom" <?php endif; ?>>地址信息</a></li>
                <li><a href="<?php echo url('index',array('type'=>'auth')); ?>" <?php if($type == 'auth'): ?> class="checked-bottom" <?php endif; ?>>认证信息</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>





      <!-- table -->
      <?php if($type == 'log'): ?>
        <div class="layui-col-md12">
          <div class="layui-card">
            <div class="layui-card-body">
              <!-- 表格 -->
              <div class="layui-form">
                <table class="layui-table">
                  <thead>
                    <tr>
                      <th>机器码</th>
                      <th>登录IP</th>
                      <th>登录时间</th>
                      <th>登陆方式</th>
                    </tr> 
                  </thead>
                  <tbody>
                    <?php if(is_array($logs) || $logs instanceof \think\Collection || $logs instanceof \think\Paginator): $i = 0; $__LIST__ = $logs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                      <tr>
                        <td><?php echo $item['log_code']; if($item['log_code'] == $machinecode): ?> (本机) <?php endif; ?></td>
                        <td><?php echo $item['log_ip']; ?></td>
                        <td><?php echo $item['log_time']; ?></td>
                        <td><?php if($item['log_type'] == 1): ?> 用户名登陆 <?php elseif($item['log_type'] == 2): ?> 手机号登陆 <?php elseif($item['log_type'] == 3): ?> 验证码登陆 <?php elseif($item['log_type'] == 4): ?> 扫码登陆 <?php endif; ?></td>
                      </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      <?php elseif($type == 'invoice'): ?>

        <div>111</div>

      <?php elseif($type == 'address'): ?>
        <div class="layui-col-md12">
          <form class="layui-form" id="addressform">
            <div class="layui-card">
              <div class="layui-card-body">
                <!-- 地址信息 -->
                <div class="jichu-xinxi">
                  <!-- <h3>地址信息</h3> -->
                  <div class="layui-form-item">
                    <label class="layui-form-label">省、市、区</label>
                    <div class="layui-input-inline">
                      <select name="address_province" class="provinceselect">
                        <option value="0" <?php if($address['address_province'] == 0): ?> selected="" <?php endif; ?>>请选择省</option>
                        <?php if(is_array($cityinfos['province']) || $cityinfos['province'] instanceof \think\Collection || $cityinfos['province'] instanceof \think\Paginator): $i = 0; $__LIST__ = $cityinfos['province'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$prov): $mod = ($i % 2 );++$i;?>
                          <option value="<?php echo $prov['id']; ?>" <?php if($address['address_province'] == $prov['id']): ?> selected="" <?php endif; ?>><?php echo $prov['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                      </select>
                    </div>
                    <div class="layui-input-inline">
                      <select name="address_city" class="cityselect">
                        <option value="0">选择市</option>
                        <?php if(is_array($cityinfos['citys']) || $cityinfos['citys'] instanceof \think\Collection || $cityinfos['citys'] instanceof \think\Paginator): $i = 0; $__LIST__ = $cityinfos['citys'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$city): $mod = ($i % 2 );++$i;?>
                          <option value="<?php echo $city['id']; ?>" <?php if($address['address_city'] == $city['id']): ?> selected="" <?php endif; ?>><?php echo $city['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                      </select>
                    </div>
                    <div class="layui-input-inline" style="margin-right:0px;">
                      <select name="address_area" class="areaselect">
                        <option value="0">选择区</option>
                        <?php if(is_array($cityinfos['areas']) || $cityinfos['areas'] instanceof \think\Collection || $cityinfos['areas'] instanceof \think\Paginator): $i = 0; $__LIST__ = $cityinfos['areas'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$area): $mod = ($i % 2 );++$i;?>
                          <option value="<?php echo $area['id']; ?>" <?php if($address['address_area'] == $area['id']): ?> selected="" <?php endif; ?>><?php echo $area['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                      </select>
                    </div>
                  </div>
                  <div class="layui-form-item">
                    <label class="layui-form-label">详细地址</label>
                    <div class="layui-input-block fp-shangchaun">
                      <input type="text" name="address_info" autocomplete="off" placeholder="请输入详细地址" class="layui-input" value="<?php echo $address['address_info']; ?>">
                    </div>
                  </div>
                </div>
                <!-- 用户信息 -->
                <div class="jichu-xinxi">
                  <!-- <h3>用户信息</h3> -->
                  <div class="layui-form-item">
                    <label class="layui-form-label">联系人姓名</label>
                    <div class="layui-input-block fp-shangchaun">
                      <input type="text" name="address_contact" autocomplete="off" placeholder="请输入联系人姓名" class="layui-input" value="<?php echo $address['address_contact']; ?>">
                    </div>
                  </div>
                  <div class="layui-form-item">
                    <label class="layui-form-label">联系电话</label>
                    <div class="layui-input-block fp-shangchaun">
                      <input type="number" name="address_mobile" autocomplete="off" placeholder="请输入联系手机号码" class="layui-input" value="<?php echo $address['address_mobile']; ?>">
                    </div>
                  </div>
                </div>
                <div class="changebat">提交修改</div>
              </div>
            </div>
          </form>
        </div>
      <?php elseif($type == 'auth'): ?>
        <div>222</div>
      <?php endif; ?>
    </div>
  </div>
  <script src="/static/index/layuiadmin/layui/layui.js"></script>  
  <script src="/static/index/layuiadmin/style/js/jquery.min.js"></script>  
  <script src="/static/index/layuiadmin/style/js/jquery.easy-pie-chart.js"></script>  
  <script src="/static/index/layuiadmin/style/js/echarts.min.js"></script>
<!--   
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
  </script> -->

  <script>
      layui.config({
          base: '/static/index/layuiadmin/' //静态资源所在路径
      }).extend({
          index: '/lib/index' //主入口模块
      }).use(['index', 'form', 'layer'], function(){
        var layer = layui.layer
        ,form = layui.form
        ,$ = layui.$;


        form.on('select', function(data){
          $.ajax({
            url:"<?php echo url('selectCity'); ?>",
            type:"GET",
            data:{adcode:data.value},
            success:function(res){
              if(res.code == 1){
                if(res.level != 3)
                  appendHtml(res.level,res.citys);
              }else
                layer.msg(res.msg);
            },error:function(){
              layer.msg('服务器错误，请稍后重试');
            }
          })
        });

        function appendHtml(level,citys)
        {
          var object = level == 1 ? $(".cityselect") : $(".areaselect");
          var html = level == 1 ? "<option value='0'>选择市</option>" : "<option value='0'>选择区</option>";

          $(citys).each(function(index,item){
            html += "<option value="+item.id+">"+item.name+"</option>";
          });

          object.empty();
          object.append(html);

          form.render('select');
        }

        $(".changebat").click(function(){
          $.ajax({
            url:"<?php echo url('ChangeAddress'); ?>",
            type:"POST",
            data:$("#addressform").serialize(),
            success:function(res){
              layer.msg(res.msg);
              if(res.code)
                setTimeout(function(){window.location.reload()},2000);
            },error:function(){
              layer.msg('服务器错误，请稍后重试');
            }
          });
        });
      });
  </script>

</body>
</html>