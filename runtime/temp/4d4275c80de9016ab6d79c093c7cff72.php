<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"/www/wwwroot/newm15/public/../application/index/view/video/index.html";i:1575280216;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>评分</title>
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
  
  .mtd-shipin-fenlei-right .checked{background-color: #1E9FFF;color:#fff;}
  .middlebutton{border-radius: 0px !important;height: 35px !important;border-right: 0px !important;}
  .mtd-shipin-td-btn a,.mtd-shipin-td-btn button{display: block !important;float: left !important;width: 73px !important;}
  .mtd-shipin-tab-right .layui-btn-sm{display: block !important;float: left !important;}
  .mtd-shipin-tab{height: 42px;}
  .mtd-shipin-td-btn .playa button{height: 35px;border-radius: 0px;border-right: 0px;}
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
      <!-- 分类、点赞数和使用 -->
      <form id="conditionform" method="" action="<?php echo url('index'); ?>">
        <div class="layui-col-md12">
          <div class="layui-card layui-card-padding">
            <!-- 第一行  分类 -->
            <div class="mtd-shipin-fenlei border-bottom clear">
              <span class="left mtd-shipin-fenlei-left">分类:</span>
              <div class="left mtd-shipin-fenlei-right">
                <input type="hidden" name="sort" id="sortinput" value="<?php echo $condition['sort']; ?>">
                <a href="javascript:;" data-value="0" class="sorts <?php if($condition['sort'] == 0): ?> checked <?php endif; ?>">全部</a>
                <?php if(is_array($sort) || $sort instanceof \think\Collection || $sort instanceof \think\Paginator): $i = 0; $__LIST__ = $sort;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                  <a href="javascript:;" class="sorts <?php if($condition['sort'] == $item['sort_id']): ?> checked <?php endif; ?>" data-value="<?php echo $item['sort_id']; ?>"><?php echo $item['sort_name']; ?></a>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </div>
              <div class="right mtd-shipin-fenlei-sousuo">
                <div class="layui-form-item">
                  <div class="layui-input-block">
                    <i class="layui-icon layui-icon-search"></i>
                    <input type="text" name="keyword" autocomplete="off" placeholder="请输入关键词或视频热词" class="layui-input" value="<?php echo $condition['keyword']; ?>">
                    <span class="layui-icon layui-icon-return" id="keyword"></span>
                  </div>
                </div>
                <!-- <a href="#" title=""><img src="/static/index/layuiadmin/imgs/wen.png" alt=""></a> -->
              </div>
            </div>
            <!-- 第二行  点赞数 -->
            <div class="mtd-shipin-dianzan border-bottom">
              <div class="layui-form-item">
                <!-- 点赞数 下拉 -->
                <div class="layui-inline">
                  <label class="layui-form-label">点赞数：</label>
                  <div class="layui-input-inline">
                    <select name="like" lay-verify="required" class="selectinput">
                      <option value="0" <?php if($condition['like'] === 0): ?> selected="" <?php endif; ?>>全部</option>
                      <?php if(is_array($specification['like']) || $specification['like'] instanceof \think\Collection || $specification['like'] instanceof \think\Paginator): $i = 0; $__LIST__ = $specification['like'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$likeitem): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $likeitem['spec_value']; ?>" <?php if($condition['like'] === $likeitem['spec_value']): ?> selected="" <?php endif; ?>><?php echo $likeitem['spec_title']; ?></option>
                      <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                  </div>
                </div>
                <!-- 视频时长 下拉 -->
                <div class="layui-inline">
                  <label class="layui-form-label">视频时长：</label>
                  <div class="layui-input-inline">
                    <select name="duration" lay-verify="required" class="selectinput">
                      <option value="0" <?php if($condition['duration'] === 0): ?> selected="" <?php endif; ?>>全部</option>
                      <?php if(is_array($specification['duration']) || $specification['duration'] instanceof \think\Collection || $specification['duration'] instanceof \think\Paginator): $i = 0; $__LIST__ = $specification['duration'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$duraitem): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $duraitem['spec_value']; ?>" <?php if($condition['duration'] === $duraitem['spec_value']): ?> selected="" <?php endif; ?>><?php echo $duraitem['spec_title']; ?></option>
                      <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                  </div>
                </div>
                <!-- 关联商品视频 -->
                <input type="checkbox" name="is_goods" id="is_goodsinput" <?php if($condition['is_goods'] != 0): ?> checked="" <?php endif; ?>> 关联商品视频
              </div>
            </div>
            <!-- 第三行  限豪华版使用 -->
            <div class="mtd-shipin-haohua border-bottom">
              <span class="layui-badge">限豪华版使用</span>
              <span class="layui-guanzhong">观众画像筛选: </span>
              <div class="layui-form-item">
                <!-- 男女比例 下拉 -->
                <div class="layui-inline">
                  <label class="layui-form-label">男女比例：</label>
                  <div class="layui-input-inline">
                    <select name="sex" lay-verify="required" class="selectinput">
                      <option value="">全部</option>
                      <option value="1">1:2</option>
                      <option value="2">1:2</option>
                    </select>
                  </div>
                </div>
                <!-- 主要年龄 下拉 -->
                <div class="layui-inline">
                  <label class="layui-form-label">主要年龄：</label>
                  <div class="layui-input-inline">
                    <select name="age" lay-verify="required" class="selectinput">
                      <option value="">全部</option>
                      <option value="1">20岁</option>
                      <option value="2">30岁</option>
                    </select>
                  </div>
                </div>
                <!-- 主要地域 下拉 -->
                <div class="layui-inline">
                  <label class="layui-form-label">主要省份：</label>
                    <div class="layui-input-inline">
                      <select name="province">
                        <option value="0">省份</option>
                        <option value="浙江" selected="">山东份</option>
                        <option value="你的工号">江西省</option>
                        <option value="你最喜欢的老师">福建省</option>
                      </select>
                    </div>
<!--                     <div class="layui-input-inline">
                      <select name="city">
                        <option value="0">地区</option>
                        <option value="杭州">杭州</option>
                        <option value="宁波">宁波</option>
                        <option value="温州">温州</option>
                      </select>
                    </div> -->
                  </div>
              </div>
            </div>
            <!-- 第四行  tab选项卡 -->
            <div class="mtd-shipin-tab clear">
              <!-- 左边排序选项 -->
              <div class="mtd-shipin-tab-left">
                <input name="order" type="hidden" id="orderinput" value="<?php echo $condition['order']; ?>">
                <ul class="clear">
                  <a href="javascript:;"><li class="ordera <?php if($condition['order'] == 'vt_hot desc'): ?> checked-bottom <?php endif; ?>" data-value="vt_hot desc">综合排序</li></a>
                  <a href="javascript:;"><li class="ordera <?php if($condition['order'] == 'vt_like desc'): ?> checked-bottom <?php endif; ?>" data-value="vt_like desc">点赞最多</li></a>
                  <a href="javascript:;"><li class="ordera <?php if($condition['order'] == 'vt_comment desc'): ?> checked-bottom <?php endif; ?>" data-value="vt_comment desc">评论最多</li></a>
                  <a href="javascript:;"><li class="ordera <?php if($condition['order'] == 'vt_reposts desc'): ?> checked-bottom <?php endif; ?>" data-value="vt_reposts desc">分享最多</li></a>
                </ul>
              </div>
              <!-- 右边时间选项 -->
              <div class="mtd-shipin-tab-right right">
                <!-- 以下button不能换行，不然会有左右间隔 -->
                <input type="hidden" name="createtime" value="<?php echo $condition['createtime']; ?>" id="createinput">
                <?php if(is_array($specification['createtime']) || $specification['createtime'] instanceof \think\Collection || $specification['createtime'] instanceof \think\Paginator): $i = 0; $__LIST__ = $specification['createtime'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$specitem): $mod = ($i % 2 );++$i;?>
                  <button type="button" class="layui-btn layui-btn-sm createtimebut <?php if($condition['createtime'] == $specitem['spec_value']): ?> mtd-shipin-btn-checked <?php endif; ?>" data-value="<?php echo $specitem['spec_value']; ?>"><?php echo $specitem['spec_title']; ?></button>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </div>
            </div>
          </div>
        </div>
      </form>

      <!-- 列表 -->
      <div class="layui-col-md12">
        <div class="layui-form">
          <?php if(!empty($videos)): ?>
          <table class="layui-table" lay-even="" lay-skin="nob">
            <colgroup>
              <col width="100">
              <col width="460">
              <col width="110">
              <col width="110">
              <col width="110">
              <col width="110">
              <col width="220">
            </colgroup>
            <thead>
              <tr class="mtd-shipin-tr">
                <th>排名</th>
                <th width="100px" style="text-align: left;width:100px !important">视频内容</th>
                <th>主播名称</th>
                <th>点赞数</th>
                <th>评论数</th>
                <th>传播价值</th>
                <th>操作</th>
              </tr> 
            </thead>
            <tbody>
              <?php if(is_array($videos) || $videos instanceof \think\Collection || $videos instanceof \think\Paginator): $i = 0; $__LIST__ = $videos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): $mod = ($i % 2 );++$i;?>
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
                  <td class="mtd-mtd-shipin-table-td2" style="width:100px !important"><img src="<?php echo $video['video_cover']; ?>" alt=""><p class="yichu" style="width:400px"><?php echo $video['video_desc']; ?></p></td>
                  <td><a href="<?php echo url('kol/info',array('kid'=>$video['video_kid'])); ?>"><?php echo $video['video_username']; ?></a></td>
                  <td class="color8c"><?php echo $video['vt_like']; ?></td>
                  <td class="color8c"><?php echo $video['vt_comment']; ?></td>
                  <td><img src="/static/index/layuiadmin/imgs/sheng.png" class="shengzhi"><?php echo $video['vt_hot']; ?></td>
                  <td class="mtd-shipin-td-btn">
                    <a href="<?php echo url('show',array('vid'=>$video['video_id'])); ?>" class="mtd-shipin-td-btn mtd-shipin-td-btna">
                      <button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo1.png" alt="">详情</button>
                    </a>
                    <a href="<?php echo $video['video_url']; ?>" target="_blank" class="playa">
                      <button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo2.png" alt="">播放</button>
                    </a>
                    <button type="button" class="layui-btn layui-btn-sm collbat" data-value="<?php echo $video['video_id']; ?>"><img src="/static/index/layuiadmin/imgs/caozuo3.png" alt="">收藏</button>
                  </td>
                </tr>
              <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
          </table>
          <?php else: ?>
            <div id="emptydatabox">暂无数据</div>
          <?php endif; ?>
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
  </script>

  <script>
      layui.config({
          base: '/static/index/layuiadmin/' //静态资源所在路径
      }).extend({
          index: '/lib/index' //主入口模块
      }).use(['index', 'form', 'layer'], function(){
        var layer = layui.layer
        ,form = layui.form
        ,$ = layui.$;

        //分类筛选
        $(".sorts").click(function(){
          $("#sortinput").val($(this).attr('data-value'));
          GetDataFunction();
        });

        //关键词筛选
        $("#keyword").click(function(){
          GetDataFunction();
        });


        $('.selectinput').change(function(){
          GetDataFunction();
        });

        //监听商品选择
        $("#is_goodsinput").change(function(){ 
        　　GetDataFunction();
        });

        //监听发布时间
        $(".createtimebut").click(function(){
          $("#createinput").val($(this).attr('data-value'));
        　 GetDataFunction();
        });

        $(".ordera").click(function(){
          $("#orderinput").val($(this).attr('data-value'));
          GetDataFunction();
        });

        $(".collbat").click(function(){
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

        function GetDataFunction(){
          $("#conditionform").submit();
        }
      });
  </script>
</body>
</html>