<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"/www/wwwroot/newm15/public/../application/index/view/user/privilege.html";i:1576749531;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>我的特权</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/index/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/admin.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/style.css" media="all">
</head>
<style>
.mtd-shipin-tab-left2{width: 100%}
.mtd-shipin-tab-left2 ul li{width: 30%; text-align: center}
.layui-btn-disabled{background-color: #afbcc5;color: #fff;border: 0;}
tbody tr td:nth-child(2),thead tr th:nth-child(2){background-color: #fff3dd!important}
</style>
<body>
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
                <li><a href="<?php echo url('privilege'); ?>" <?php if($type == 'info'): ?> class="checked-bottom" <?php endif; ?>>会员信息</a></li>
                <li><a href="<?php echo url('privilege',array('type'=>'members')); ?>" <?php if($type == 'members'): ?> class="checked-bottom" <?php endif; ?>>会员开通记录</a></li>
                <li><a href="<?php echo url('privilege',array('type'=>'integral')); ?>" <?php if($type == 'integral'): ?> class="checked-bottom" <?php endif; ?>>积分充值记录</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!-- 账号版本 -->
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body">
            <!-- 当前账号状态 -->
            <div class="banben-div">
              <h2>当前账号状态</h2>
              <button class="layui-btn layui-btn-xs layui-btn-disabled"><?php echo $user['user_vip']['level_name']; ?></button>
              <p><?php echo $user['user_vip']['level_desc']; ?></p>
            </div>
            <!-- table 各个版本信息 -->
            <div class="layui-form">
              <table class="layui-table">
                <thead>
                  <tr>
                    <th>特权对比</th>
                    <th>免费版</th>
                    <?php if(is_array($vips) || $vips instanceof \think\Collection || $vips instanceof \think\Paginator): $i = 0; $__LIST__ = $vips;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vipitem): $mod = ($i % 2 );++$i;?>
                      <th><p><?php echo $vipitem['level_name']; ?></p><p>￥<?php echo $vipitem['level_monthlyfee']; ?>元/月</p></th>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                  </tr> 
                </thead>
                <tbody>
                  <tr>
                    <td>加入条件</td>
                    <td>注册即可</td>
                    <td><a href="zhifu.html"><button class="layui-btn layui-btn-sm layui-btn-normal">立即升级</button></td></a>
                    <td><a href="zhifu.html"><button class="layui-btn layui-btn-sm layui-btn-normal">立即升级</button></td></a>
                    <td><a href="zhifu.html"><button class="layui-btn layui-btn-sm layui-btn-normal">立即升级</button></td></a>
                  </tr>
                  <tr>
                    <td>全站视频课程</td>
                    <td>怀远教程五险观看/天</td>
                    <td>1个/天</td>
                    <td>1个/天</td>
                    <td>1个/天</td>
                  </tr>
                  <tr>
                    <td>全站图义教程</td>
                    <td>怀远教程五险观看/天</td>
                    <td><p style="color: #000">1个/天</p><p>(累计最多观看5个)</p></td>
                    <td><p style="color: #000">1个/天</p><p>(累计最多观看5个)</p></td>
                    <td><p style="color: #000">1个/天</p><p>(累计最多观看5个)</p></td>
                  </tr>
                  <tr>
                    <td>全站素材教程</td>
                    <td>怀远教程五险下载/天</td>
                    <td><p style="color: #000">1个/天</p><p>(累计最多观看5个)</p></td>
                    <td><p style="color: #000">1个/天</p><p>(累计最多观看5个)</p></td>
                    <td><p style="color: #000">1个/天</p><p>(累计最多观看5个)</p></td>
                  </tr>
                  <tr>
                    <td>全站源义件教程</td>
                    <td>怀远教程五险下载/天</td>
                    <td><p style="color: #000">1个/天</p><p>(累计最多观看5个)</p></td>
                    <td><p style="color: #000">1个/天</p><p>(累计最多观看5个)</p></td>
                    <td><p style="color: #000">1个/天</p><p>(累计最多观看5个)</p></td>
                  </tr>
                  <tr>
                    <td>虎课读书特权</td>
                    <td>读书内容五险收听/年</td>
                    <td><img src="/static/index/layuiadmin/imgs/cha.png" alt=""></td>
                    <td><img src="/static/index/layuiadmin/imgs/cha.png" alt=""></td>
                    <td><img src="/static/index/layuiadmin/imgs/cha.png" alt=""></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


<script src="/static/index/layuiadmin/layui/layui.js"></script>  
<script src="/static/index/layuiadmin/style/js/jquery.min.js"></script>  
<script src="/static/index/layuiadmin/style/js/jquery.easy-pie-chart.js"></script>  
<script src="/static/index/layuiadmin/style/js/echarts.min.js"></script>
</body>
</html>