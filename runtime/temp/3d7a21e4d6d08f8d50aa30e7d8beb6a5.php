<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"/www/wwwroot/newm15/public/../application/index/view/kol/info.html";i:1575970891;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>数据概览</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/index/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/admin.css" media="all">
  <link rel="stylesheet" href="/static/index/layuiadmin/style/style.css" media="all">
</head>
<style>
  .easyPieChart{margin: 0 20px;}
  .mtd-shipin-tab-left{width: 100%}
  .mtd-shipin-tab-left ul li{width: 15%; text-align: center}
  .jia-span-top{margin-top: 4px}
  .mtd-shipin-td-btn a,.mtd-shipin-td-btn button{display: block !important;float: left !important;width: 73px !important;}
  .mtd-shipin-tab-left2{width: 100%}
  .mtd-shipin-tab-left2 ul li{width: 15%; text-align: center}
    .mask{width: 100%;height: 100vh; background:rgba(0,0,0,0.75);position: fixed; top: 0;z-index: 99999; display: none;}
    .mask-content{width: 300px;height:200px;background: #fff;margin: 10% auto;padding:15px;position: relative}
    .mask-content h3{color: #000;margin-bottom: 15px}

    .mask2{width: 100%;height: 100vh;position: fixed; top: 0;z-index: 99999; display: none;}
    .mask-content2{width: 300px;background: #fff;margin: 10% auto;padding:15px;position: relative}
    .mask-content2 h3{color: #000;margin-bottom: 15px;padding-bottom: 10px;border-bottom: 1px solid #ddd;}
    .mtd-shipin-td-btn .playa button{height: 35px;border-radius: 0px;border-right: 0px;}
    .layui-layer-tips .layui-layer-content{background-color:#ddd !important;}
    .layui-layer-content{
      width: 85px;
      height: 105px;
      background-color: #ddd;
    }
    .layui-layer-tips i.layui-layer-TipsR{
      border-bottom-color:#ddd !important;
    }
    #qrcodebox{
      width: 100px;
      height: 100px;
      background-color: #ddd;
      background-size: 100% 100%;
      font-size: 1px;
      text-align: center;
      padding-top: 5px;
      float: left;
      #display: none;
    }
</style>
<body onLoad="initPieChart();">
<div class="layui-fluid" id="LAY-flow-demo">
    <div class="layui-row layui-col-space15">
      <!-- 收藏的信息 -->
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body">
            <div class="mtd-shipin-index-div">
              <!-- 左边头像和热度 -->
                <div class="mtd-shipin-div-left">
                  <img src="<?php echo $kol['kol_avatar']; ?>" alt="" class="mtd-list1-shenyang-left-img">
                  <div class="mtd-list1-shenyang-div">
                    <img src="/static/index/layuiadmin/imgs/10.png" alt="" style="width: 18px"><?php echo $trend['kt_hot']; ?>
                  </div>
                </div>
                <!-- 中间信息抖音号，分类和简介 -->
                <div class="mtd-shipin-div-center" style="width: 35%;padding-right: 5%;">
                  <h5><?php echo $kol['kol_nickname']; if($kol['kol_is_goods'] == 1): ?>
                      <img src="/static/index/layuiadmin/imgs/3.png" alt="" class="center-img">
                    <?php endif; ?>
                    <img src="/static/index/layuiadmin/imgs/2.png" class="mtd-shipin-list1-img3" id="showqrcode">
                    <!--<img src="/static/index/layuiadmin/imgs/a-2.png" alt="" class="span-margin-l"> 抖音人气好物推荐官-->
                  </h5>
                  <p>抖音号：<?php echo $kol['kol_number']; ?>
                    <span class="span-margin-l">性别：<?php if($kol['kol_sex'] == 1): ?> 男 <?php elseif($kol['kol_sex'] == 2): ?> 女 <?php else: ?> 未设置 <?php endif; ?></span>
                    <span class="span-margin-l">地区：<?php echo $kol['kol_cityname']; ?></span>
                    <span class="span-margin-l">年龄：<?php echo $kol['kol_age']; ?></span>
                  </p>
                  <p>分类：<?php echo $kol['kol_sort']; ?><!--<span class="span-margin-l kouhong">口红</span><span class="span-margin-l kouhong">彩妆</span></p>-->
                  <p style="height: 30px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">简介：<?php echo $kol['kol_signature']; ?></p>
                  <p>
                    <?php if($kol['kol_verifyname'] != ''): ?>
                      <span class="mz-span-img" style="margin-right: 20px;"><img src="/static/index/layuiadmin/imgs/huangguan.png" alt=""><?php echo $kol['kol_verifyname']; ?></span>
                    <?php endif; if($kol['kol_achievement'] != ''): ?>
                      <span class="span-margin-l mz-span-img" style="background-color:#ff8000;margin-left: 0px"><img src="/static/index/layuiadmin/imgs/huangguan.png" alt=""><?php echo $kol['kol_achievement']; ?></span>
                    <?php endif; ?>
                  </p>
                </div>
                <div id="hot" style="margin-right: 8%">
                  <p style="margin-top: 10px">平台热度</p>
                  <p><?php echo $trend['kt_hot']; ?></p>
                </div>
                <!-- 右边四个仪表盘图表 -->
                <div class="mtd-shipin-div-right">
                  <div class="container" style="width: 100%">
                    <div class="chart">
                      <div class="percentage" data-percent="55"><span style="font-size:1px"><?php echo $trend['kt_fans']; ?></span></div>
                      <div class="labeler">粉丝数</div>
                    </div>
                    <div class="chart">
                      <div class="percentage" data-percent="46"><span><?php echo $trend['kt_mean_like']; ?></span></div>
                      <div class="labeler">平均点赞</div>
                    </div>
                    <div class="chart">
                      <div class="percentage" data-percent="92"><span><?php echo $trend['kt_mean_comment']; ?></span></div>
                      <div class="labeler">平均评论</div>
                    </div>
                    <div class="chart">
                      <div class="percentage" data-percent="84"><span><?php echo $trend['kt_mean_share']; ?></span></div>
                      <div class="labeler">平均分享</div>
                    </div>
                  </div>
                </div>
            </div>
            <!-- 好多button -->
            <div class="button5">
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">加入我的抖音号</button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">相似号查询</button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">视频监控</button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">立即合作</button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm collbat" data-value="<?php echo $kol['kol_id']; ?>">加入收藏</button>
            </div>
          </div>
        </div>
      </div>
      <!-- tab选项 -->
      <div class="layui-col-md12" style="padding-bottom: 0">
        <div class="layui-card">
          <div class="layui-card-body" style="padding:10px 15px 0 15px;">
            <div class="mtd-shipin-tab-left">
              <ul class="clear">
                <li><a href="<?php echo url('info',array('kid'=>$kol['kol_id'],'type'=>'data')); ?>" class="checktab <?php if($type == 'data'): ?> checked-bottom <?php endif; ?>">数据概览</a></li>
                <li><a href="<?php echo url('info',array('kid'=>$kol['kol_id'],'type'=>'video')); ?>" class="checktab <?php if($type == 'video'): ?> checked-bottom <?php endif; ?>">播主视频</a></li>
                <li><a href="<?php echo url('info',array('kid'=>$kol['kol_id'],'type'=>'mcn')); ?>" class="checktab <?php if($type == 'mcn'): ?> checked-bottom <?php endif; ?>">MCN系统</a></li>
                <li><a href="<?php echo url('info',array('kid'=>$kol['kol_id'],'type'=>'fans')); ?>" class="checktab <?php if($type == 'fans'): ?> checked-bottom <?php endif; ?>">粉丝数据分析</a></li>
                <li><a href="<?php echo url('info',array('kid'=>$kol['kol_id'],'type'=>'goods')); ?>" class="checktab <?php if($type == 'goods'): ?> checked-bottom <?php endif; ?>">电商数据分析</a></li>
                <li><a href="<?php echo url('info',array('kid'=>$kol['kol_id'],'type'=>'task')); ?>" class="checktab <?php if($type == 'task'): ?> checked-bottom <?php endif; ?>">任务平台</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      
      <?php if($type == 'data'): ?>
        <!-- 数据概览 内容 -->
        <div class="clickchangetab" id="shujugailan">
          <!-- 总播放量 总下载数 总评论数 总转发数 总点赞数 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-body">
                <div class="mtd-shipin-list1-ul">
                  <ul class="clear">
                    <li>
                      <div class="mtd-shipin-list1-div">
                        <img src="/static/index/layuiadmin/imgs/5-2.png" alt="">
                        <div class="mtd-shipin-list1-div-right right">
                          <h4><?php echo $trend['kt_fans']; ?></h4>
                          <p>总粉丝量</p>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="mtd-shipin-list1-div">
                        <img src="/static/index/layuiadmin/imgs/8.png" alt="">
                        <div class="mtd-shipin-list1-div-right right">
                          <h4><?php echo $trend['kt_like']; ?></h4>
                          <p>总点赞数</p>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="mtd-shipin-list1-div">
                        <img src="/static/index/layuiadmin/imgs/6.png" alt="">
                        <div class="mtd-shipin-list1-div-right right">
                          <h4><?php echo $trend['kt_comments']; ?></h4>
                          <p>总评论数</p>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="mtd-shipin-list1-div">
                        <img src="/static/index/layuiadmin/imgs/7.png" alt="">
                        <div class="mtd-shipin-list1-div-right right">
                          <h4><?php echo $trend['kt_share']; ?></h4>
                          <p>总转发数</p>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="mtd-shipin-list1-div">
                        <img src="/static/index/layuiadmin/imgs/4.png" alt="">
                        <div class="mtd-shipin-list1-div-right right">
                          <h4><?php echo $trend['kt_download']; ?></h4>
                          <p>总下载数</p>
                        </div>
                      </div>
                    </li>
                    <li>
                    <div class="mtd-shipin-list1-div">
                        <img src="/static/index/layuiadmin/imgs/5-2.png" alt="">
                        <div class="mtd-shipin-list1-div-right right">
                          <h4><?php echo $change['fans']; ?></h4>
                          <p>近30天增量</p>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="mtd-shipin-list1-div">
                        <img src="/static/index/layuiadmin/imgs/8.png" alt="">
                        <div class="mtd-shipin-list1-div-right right">
                          <h4><?php echo $change['like']; ?></h4>
                          <p>近30天增量</p>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="mtd-shipin-list1-div">
                        <img src="/static/index/layuiadmin/imgs/6.png" alt="">
                        <div class="mtd-shipin-list1-div-right right">
                          <h4><?php echo $change['comment']; ?></h4>
                          <p>近30天增量</p>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="mtd-shipin-list1-div">
                        <img src="/static/index/layuiadmin/imgs/7.png" alt="">
                        <div class="mtd-shipin-list1-div-right right">
                          <h4><?php echo $change['share']; ?></h4>
                          <p>近30天增量</p>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="mtd-shipin-list1-div">
                        <img src="/static/index/layuiadmin/imgs/4.png" alt="">
                        <div class="mtd-shipin-list1-div-right right">
                          <h4><?php echo $change['download']; ?></h4>
                          <p>近30天增量</p>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <!-- m15价值指数 he  互动价值 -->
          <div class="layui-col-md6">
            <div class="layui-card">
              <div class="layui-card-header">M15价值指数</div>
              <div class="layui-card-body">
                <div class="border-f8f8f8 clear">
                  <!-- 左侧 -->
                  <div class="left" style="width: 50%;margin-top: 70px">
                    <div class="mtd-shipin-div-left">
                      <div class="mtd-list1-shenyang-div">
                        <img src="/static/index/layuiadmin/imgs/10.png" alt="" style="width: 18px"><?php echo $trend['kt_hot']; ?>
                      </div>
                    </div>
                    <p style="font-size:16px;margin: 5px 0">价值指数</p>
                    <p>Sentiments two occasional affromting solidtude travdfughs abf one jhdjshf,</p>
                  </div>
                  <!-- 右侧仪表盘 -->
                  <div id="tb8" class="right" style="width: 50%;height:300px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="layui-col-md6">
            <div class="layui-card">
              <div class="layui-card-header">互动价值</div>
              <div class="layui-card-body">
                <div class="border-f8f8f8 border-f8f8f8-bg clear">
                  <p class="left">3000w</p>
                  <div id="tb9" class="left" style="height: 300px;width: 50%"></div>
                </div>
              </div>
            </div>
          </div>
          <!-- 查看爆款视频 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-body">
                <a href="<?php echo url('info',array('kid'=>$kol['kol_id'],'type'=>'video')); ?>" style="text-align:right;display:block;color:#1988fc">查看爆款视频 ></a>
              </div>
            </div>
          </div>
          <!-- 五个爆款 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-body">
                <ul class="kol-search-list1-ul clear">
                  <li><div class="kol-search-list1-ul-div"><p>发布视频总数</p><p><?php echo $trend['kt_videocount']; ?>个</p></div></li>
                  <li><div class="kol-search-list1-ul-div"><p>本月视频数量</p><p><?php echo $trend['monthvideo']; ?>个</p></div></li>
                  <li>
                    <div class="kol-search-list1-ul-div">
                      <img src="/static/index/layuiadmin/imgs/huo3.png" alt="" class="left" style="margin-top: 7px">
                      <div style="display: inline-block">
                        <p>大爆款视频（占比）</p>
                        <p><?php echo $trend['kt_bighot']; ?>个/（<?php echo $trend['bighotb']; ?>）</p>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="kol-search-list1-ul-div">
                      <img src="/static/index/layuiadmin/imgs/huo2.png" alt="" class="left" style="margin-top: 7px">
                      <div style="display: inline-block">
                        <p>中爆款视频（占比）</p>
                        <p><?php echo $trend['kt_midhot']; ?>个/（<?php echo $trend['midhotb']; ?>）</p>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="kol-search-list1-ul-div">
                      <img src="/static/index/layuiadmin/imgs/huo1.png" alt="" class="left" style="margin-top: 7px">
                      <div style="display: inline-block">
                        <p>小爆款视频（占比）</p>
                        <p><?php echo $trend['kt_smahot']; ?>个/（<?php echo $trend['smahotb']; ?>）</p>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <!-- 最新视频数据表现 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-header">最新视频数据表现</div>
              <div class="layui-card-body">
                <div id="tb1" style="width: 100%; height: 500px"></div>
              </div>
            </div>
          </div>
          <!-- 播放趋势 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-header">播放趋势<span class="right mtd-shipin-td-btn jia-span-top">
                <a href="list1.html" title="" class="mtd-shipin-td-btn mtd-shipin-td-btna">
                  <button type="button" class="layui-btn layui-btn-sm  mtd-shipin-btn-checked">总量</button>
                </a>
                <button type="button" class="layui-btn layui-btn-sm">增量</button></span>
              </div>
              <div class="layui-card-body">
                <div id="tb2" style="width: 100%; height: 300px"></div>
              </div>
            </div>
          </div>

          <!-- 粉丝趋势 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-header">粉丝趋势<span class="right mtd-shipin-td-btn jia-span-top">
                <button type="button" class="layui-btn layui-btn-sm  mtd-shipin-btn-checked">总量</button>
                <button type="button" class="layui-btn layui-btn-sm">增量</button></span>
              </div>
              <div class="layui-card-body">
                <div id="tb3" style="width: 100%; height: 300px"></div>
              </div>
            </div>
          </div>

          <!-- 评论趋势 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-header">评论趋势<span class="right mtd-shipin-td-btn jia-span-top">
                <a href="list1.html" title="" class="mtd-shipin-td-btn mtd-shipin-td-btna">
                  <button type="button" class="layui-btn layui-btn-sm  mtd-shipin-btn-checked">总量</button>
                </a>
                <button type="button" class="layui-btn layui-btn-sm">增量</button></span>
              </div>
              <div class="layui-card-body">
                <div id="tb4" style="width: 100%; height: 300px"></div>
              </div>
            </div>
          </div>
          <!-- 转发趋势 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-header">分享趋势<span class="right mtd-shipin-td-btn jia-span-top">
                <a href="list1.html" title="" class="mtd-shipin-td-btn mtd-shipin-td-btna">
                  <button type="button" class="layui-btn layui-btn-sm  mtd-shipin-btn-checked">总量</button>
                </a>
                <button type="button" class="layui-btn layui-btn-sm">增量</button></span>
              </div>
              <div class="layui-card-body">
                <div id="tb5" style="width: 100%; height: 300px"></div>
              </div>
            </div>
          </div>
          <!-- 点赞趋势 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-header">点赞趋势<span class="right mtd-shipin-td-btn jia-span-top">
                <a href="list1.html" title="" class="mtd-shipin-td-btn mtd-shipin-td-btna">
                  <button type="button" class="layui-btn layui-btn-sm  mtd-shipin-btn-checked">总量</button>
                </a>
                <button type="button" class="layui-btn layui-btn-sm">增量</button></span>
              </div>
              <div class="layui-card-body">
                <div id="tb6" style="width: 100%; height: 300px"></div>
              </div>
            </div>
          </div>
          <!-- 评论热词TOP10 -->
          <div class="layui-col-md6">
            <div class="layui-card">
              <div class="layui-card-header">评论热词TOP10</div>
              <div class="layui-card-body">
                <div id="tb7" style="width: 100%; height: 300px"></div>
              </div>
            </div>
          </div>
          <!-- 搜索关键词 -->
          <div class="layui-col-md6">
            <div class="layui-card">
              <!-- 关键词搜索 -->
              <div class="layui-card-header">
                <div class="layui-form-item">
                  <div class="layui-input-inline sanbang-right-div" style="width: 210px;margin-top: 2px">
                    <input type="" name="" lay-verify="pass" placeholder="输入关键词" autocomplete="off" class="layui-input">
                    <button type="button" class="layui-btn layui-btn-sm" style="background-color: #1e9fff;">搜索</button>
                  </div>
                </div>
              </div>
              <!-- 关键词搜索出来的结果 -->
              <div class="layui-card-body" style="height: 300px;overflow: auto;">
                <div class="kol-search-list1-jieguo">
                  <ul>
                    <li>
                      <p class="yichu"><span style="color: #1e9fff">毛毛</span>我叫阿娇，我像你，你叫阿娇我叫阿娇，我像你，你叫阿娇我叫阿娇，我像你，你叫阿娇我叫阿娇，我像你，你叫阿娇我叫阿娇，我像你，你叫阿娇</p>
                      <p>2019-10-25</p>
                    </li>
                    <li>
                      <p class="yichu"><span style="color: #1e9fff">毛毛</span>我叫阿娇，我像你，你叫阿娇我叫阿娇，我像你，你叫阿娇我叫阿娇，我像你，你叫阿娇我叫阿娇，我像你，你叫阿娇我叫阿娇，我像你，你叫阿娇</p>
                      <p>2019-10-25</p>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php elseif($type ==  'video'): ?>
        <!-- 播主视频 内容 -->
        <div class="clickchangetab" id="bozhushipin">
          <!-- 搜索，发布时间 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-body clear">
                <!-- 搜索 -->
                <div class="layui-form-item left" style="margin-bottom: 0;display: inline-block;">
                  <div class="layui-input-inline sanbang-right-div" style="width: 210px;margin-top: 2px">
                    <input type="" id="videokeyword" placeholder="输入关键词" autocomplete="off" class="layui-input" value="<?php echo $condition['keyword']; ?>">
                    <button type="button" class="layui-btn layui-btn-sm" id="videosearch" style="background-color: #1e9fff;">搜索</button>
                  </div>
                </div>
                <!-- 多选 -->
                <form class="layui-form" action="" lay-filter="example" style="display: inline-block;height: 40px">
                  <div class="layui-form-item" pane="">
                    <div class="layui-input-block" style=" margin-left: 10px">
                      <input type="checkbox" lay-skin="primary" title="关联商品视频" <?php if($condition['goods'] != 0): ?> checked="" <?php endif; ?> id="videogoods">
                      <!-- <input type="checkbox" name="like1[read]" lay-skin="primary" title="屏蔽已删除视频"> -->
                    </div>
                  </div>
                </form>
                <!-- 发布时间 -->
              <!-- <form class="layui-form right" action="" style="display: inline-block;">
                    <div class="layui-form-item" style="margin-bottom: 0">
                      <div class="layui-inline">
                        <label class="layui-form-label">发布时间：</label>
                        <div class="layui-input-inline">
                          <input type="text" class="layui-input" id="test6" placeholder=" - ">
                        </div>
                      </div>
                    </div>
                </form> -->
              </div>
              </div>
            </div>
          </div>
          <!-- 最新，最热，tab选项 -->
          <div class="layui-col-md12" style="padding-bottom: 0">
            <div class="layui-card">
              <div class="layui-card-body" style="padding:10px 15px 0 15px;margin-top: 10px">
                <div class="mtd-shipin-tab-left">
                  <ul class="clear">
                    <input type="hidden" name="pamar[order]" id="orderinput" value="<?php echo $condition['order']; ?>">
                    <li><a href="javascript:;" class="ordera <?php if($condition['order'] == 'v.create_time desc'): ?> checked-bottom <?php endif; ?>" data-value="v.create_time desc">最新视频</a></li>
                    <li><a href="javascript:;" class="ordera <?php if($condition['order'] == 'vt.vt_hot desc'): ?> checked-bottom <?php endif; ?>" data-value="vt.vt_hot desc">最热视频</a></li>
                  </ul>
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
                  <col width="90">
                  <col width="80">
                  <col width="80">
                  <col width="100">
                  <col width="120">
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
                      <td class="mtd-mtd-shipin-table-td2" style="width:100px !important"><img src="<?php echo $video['video_cover']; ?>" alt=""><p class="yichu" style="width:375px"><?php echo $video['video_desc']; ?></p></td>
                      <td><?php echo $video['video_username']; ?></td>
                      <td class="color8c"><?php echo $video['vt_like']; ?></td>
                      <td class="color8c"><?php echo $video['vt_comment']; ?></td>
                      <td><img src="/static/index/layuiadmin/imgs/sheng.png" class="shengzhi"><?php echo $video['vt_hot']; ?></td>
                      <td class="color8c"><?php echo date('Y-m-d H:i',$video['create_time']); ?></td>
                      <td class="mtd-shipin-td-btn">
                        <a href="<?php echo url('video/show',array('vid'=>$video['video_id'])); ?>" class="mtd-shipin-td-btn mtd-shipin-td-btna">
                          <button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo1.png" alt="">详情</button>
                        </a>
                        <a href="<?php echo $video['video_url']; ?>" target="_blank" class="playa">
                          <button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo2.png" alt="">播放</button>
                        </a>
                        <button type="button" class="layui-btn layui-btn-sm collbatvideo" data-value="<?php echo $video['video_id']; ?>"><img src="/static/index/layuiadmin/imgs/caozuo3.png" alt="">收藏</button>
                      </td>
                    </tr>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
              </table> 
            </div>
          </div> 
        </div>
      <?php elseif($type == 'mcn'): ?>
        <!-- MCN系统 内容 -->
        <div class="clickchangetab" id="mcn-xitong">
          <!-- 公司信息 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-body">
                <!-- 头像和信息 -->
                <div class="kol-list3-div1">
                  <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                  <div class="kol-list3-div1-content">
                    <p style="color: #000">美ONE</p>
                    <p>美腕（上海）网络科技有限公司</p>
                    <p>官方抖音号：1064545</p>
                  </div>
                </div>
                <!-- 成立 -->
                <div class="kol-list3-div1">
                  <p>美ONE成立于2014年，是通过挖掘及培养专业达人，并打造专业内容以实现产品销售的电商企业，目前旗下有数百位达人，..........</p>
                </div>
                <!-- 官网 -->
                <div class="kol-list3-div1">
                  <p>官网：<span style="color: #000">http://www.meione.cc/</span></p>
                </div>
                <!-- 同机构抖音号 -->
                <div class="kol-list3-div1 kol-list3-div2">
                  <p>同MCN机构抖音号</p>
                  <ul class="clear">
                    <li>
                      <div class="kol-list3-div2-div">
                        <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                        <p>办公室小野</p>
                      </div>
                    </li>
                    <li>
                      <div class="kol-list3-div2-div">
                        <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                        <p>办公室小野</p>
                      </div>
                    </li>
                    <li>
                      <div class="kol-list3-div2-div">
                        <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                        <p>办公室小野</p>
                      </div>
                    </li>
                    <li>
                      <div class="kol-list3-div2-div">
                        <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                        <p>办公室小野</p>
                      </div>
                    </li>
                    <li>
                      <div class="kol-list3-div2-div">
                        <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                        <p>办公室小野</p>
                      </div>
                    </li>
                    <li>
                      <div class="kol-list3-div2-div">
                        <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                        <p>办公室小野</p>
                      </div>
                    </li>
                    <li>
                      <div class="kol-list3-div2-div">
                        <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                        <p>办公室小野</p>
                      </div>
                    </li>
                    <li>
                      <div class="kol-list3-div2-div">
                        <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                        <p>办公室小野</p>
                      </div>
                    </li>
                    <li>
                      <div class="kol-list3-div2-div">
                        <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                        <p>办公室小野</p>
                      </div>
                    </li>
                    <li>
                      <div class="kol-list3-div2-div">
                        <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                        <p>办公室小野</p>
                      </div>
                    </li>
                    <li>
                      <div class="kol-list3-div2-div">
                        <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                        <p>办公室小野</p>
                      </div>
                    </li>
                    <li>
                      <div class="kol-list3-div2-div">
                        <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                        <p>办公室小野</p>
                      </div>
                    </li>
                    <li>
                      <div class="kol-list3-div2-div">
                        <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                        <p>办公室小野</p>
                      </div>
                    </li>
                  </ul>
                  <p style="text-align: center">没有更多了~</p>
                </div>
              </div>
            </div>
          </div>
          <!-- 机构介绍 及 图片 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-body">
                <p style="color:#000">机构介绍：</p>
                <p class="p-span-margin-l">
                  <span>行业：电子家电</span>
                  <span>类型：楼书 企业样本</span>
                  <span>用途：形象宣传</span>
                  <span>设计风格：原创设计 简约</span>
                </p>
                <img src="/static/index/layuiadmin/imgs/content.png" alt="" width="100%">
              </div>
            </div>
          </div>
        </div>
      <?php elseif($type == 'fans'): if(empty($public)): ?>
          <div style="text-align: center;padding-top:550px;">暂无粉丝信息</div>
        <?php else: ?>
          <!-- 粉丝数据分析 内容 -->
          <div class="clickchangetab" id="fensi-shuju">
            <!-- 年龄分布 图表 -->
            <div class="layui-col-md6">
              <div class="layui-card">
                <div class="layui-card-header">年龄分布</div>
                <div class="layui-card-body">
                  <div id="fs-tb1" style="height: 500px"></div>
                </div>
              </div>
            </div>
            <!-- 地域分布-->
            <div class="layui-col-md6">
                  <div class="layui-card">
                    <div class="layui-card-header">地域分布<span class="right mtd-shipin-td-btn jia-span-top">
                      <a href="list1.html" title="" class="mtd-shipin-td-btn mtd-shipin-td-btna">
                        <button type="button" class="layui-btn layui-btn-sm  mtd-shipin-btn-checked">省份</button>
                      </a>
                      <button type="button" class="layui-btn layui-btn-sm">城市</button></span>
                    </div>
                    <div class="layui-card-body" style="height: 500px">
                      <table class="layui-table" lay-even="" lay-skin="nob">
                        <colgroup>
                          <col width="150">
                          <col width="150">
                        </colgroup>
                        <thead>
                          <tr>
                            <th>名称</th>
                            <th>占比</th>
                          </tr> 
                        </thead>
                        <tbody>
                          <tr>
                            <td>广东</td>
                            <td>12.44%</td>
                          </tr>
                          <tr>
                            <td>北京</td>
                            <td>14.07%</td>
                          </tr>
                          <tr>
                            <td>浙江</td>
                            <td>8.61%</td>
                          </tr>
                          <tr>
                            <td>江苏</td>
                            <td>7.18%</td>
                          </tr>
                          <tr>
                            <td>山东</td>
                            <td>6.94%</td>
                          </tr>
                          <tr>
                            <td>河北</td>
                            <td>5.98%</td>
                          </tr>
                          <tr>
                            <td>四川</td>
                            <td>5.26%</td>
                          </tr>
                          <tr>
                            <td>辽宁</td>
                            <td>5.02%</td>
                          </tr>
                          <tr>
                            <td>福建</td>
                            <td>4.55%</td>
                          </tr>
                          <tr>
                            <td>安徽</td>
                            <td>4.31%</td>
                          </tr>
                          <tr>
                            <td>湖北</td>
                            <td>4.07%</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
            </div>
            <!-- 粉丝活跃时间分布-天 图表 -->
            <div class="layui-col-md6">
              <div class="layui-card">
                <div class="layui-card-header">粉丝活跃时间分布-天</div>
                <div class="layui-card-body">
                  <div id="fs-tb2" style="height: 500px"></div>
                </div>
              </div>
            </div>
            <!-- 粉丝活跃时间分布-周 图表 -->
            <div class="layui-col-md6">
              <div class="layui-card">
                <div class="layui-card-header">粉丝活跃时间分布-周</div>
                <div class="layui-card-body">
                  <div id="fs-tb3" style="height: 500px"></div>
                </div>
              </div>
            </div>
          </div>
        <?php endif; elseif($type == 'goods'): ?>
        <!-- 电商数据分析 内容 -->
        <div class="clickchangetab" id="dianshang-shipin">
          <!-- 上榜趋势图 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-header">上榜趋势图<span class="right mtd-shipin-td-btn jia-span-top">
                <a href="list1.html" title="" class="mtd-shipin-td-btn mtd-shipin-td-btna">
                  <button type="button" class="layui-btn layui-btn-sm  mtd-shipin-btn-checked">总量</button>
                </a>
                <button type="button" class="layui-btn layui-btn-sm">增量</button></span>
              </div>
              <div class="layui-card-body">
                <div id="dianshang-tb1" style="height: 400px"></div>
              </div>
            </div>
          </div>
          <!-- 品牌分类、商品分类、搜索、商品列表 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-body">
                <!-- 品牌分类 -->
                <div class="mtd-shipin-fenlei border-bottom clear">
                  <span class="left mtd-shipin-fenlei-left">品牌分类:</span>
                  <div class="left mtd-shipin-fenlei-right">
                    <a href="#" title="">全部(32)</a>
                    <a href="#" title="">other(7)</a>
                    <a href="#" title="">她及其他(7)</a>
                    <a href="#" title="">美康粉黛(1)</a>
                    <a href="#" title="">其他(10)</a>
                  </div>
                </div>
                <!-- 商品分类 -->
                <div class="mtd-shipin-fenlei border-bottom clear">
                  <span class="left mtd-shipin-fenlei-left">商品分类:</span>
                  <div class="left mtd-shipin-fenlei-right">
                    <a href="#" title="">全部(32)</a>
                    <a href="#" title="">T恤_男(10)</a>
                    <a href="#" title="">卫衣男(8)</a>
                    <a href="#" title="">other(7)</a>
                    <a href="#" title="">她及其他(7)</a>
                    <a href="#" title="">美康粉黛(1)</a>
                    <a href="#" title="">其他(10)</a>
                  </div>
                </div>
                <!-- 搜索 -->
                <div class="mtd-shipin-fenlei border-bottom clear">
                  <div class="layui-form-item left" style="margin-bottom: 0;display: inline-block;">
                    <div class="layui-input-inline sanbang-right-div" style="width: 250px;margin-top: 2px">
                      <input type="" name="" lay-verify="pass" placeholder="输入关键词" autocomplete="off" class="layui-input">
                      <button type="button" class="layui-btn layui-btn-sm" style="background-color: #1e9fff;">搜索</button>
                    </div>
                  </div>
                  <!-- 价格范围 -->
                  <form class="layui-form" action="" class="right" style="display: inline-block;float: right">
                    <div class="layui-form-item" style="margin-bottom: 0">
                      <div class="layui-inline">
                        <label class="layui-form-label">价格：</label>
                        <div class="layui-input-inline" style="width: 100px;">
                          <input type="text" name="price_min" placeholder="￥" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline" style="width: 100px;">
                          <input type="text" name="price_max" placeholder="￥" autocomplete="off" class="layui-input">
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                <!-- 商品列表 -->
                <div class="kol-shangpin-list clear">
                  <p>商品列表</p>
                  <div class="right kol-shangpin-list-right">
                    <!-- 右边时间选项 -->
                    <div class="layui-form" style="display: inline-block">
                      <div class="layui-form-item" style="margin-bottom: 0">
                        <div class="layui-inline">
                          <label class="layui-form-label">日期：</label>
                          <div class="layui-input-inline">
                            <input type="text" class="layui-input" id="test1" placeholder="yyyy-MM-dd">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="mtd-shipin-tab-right mtd-shipin-td-btn">
                      <!-- 以下button不能换行，不然会有左右间隔 -->
                      <button type="button" class="layui-btn layui-btn-sm mtd-shipin-btn-checked">昨天</button>
                      <button type="button" class="layui-btn layui-btn-sm">最近7天</button>
                      <button type="button" class="layui-btn layui-btn-sm">全部</button>
                      <button type="button" class="layui-btn layui-btn-sm">自定义近3天</button>
                    </div>
                  </div>
                </div>
            </div>
            </div>
          </div>
          <!-- tab选项 -->
          <div class="layui-col-md12" style="padding-bottom: 0">
            <div class="layui-card">
              <div class="layui-card-body" style="padding:10px 15px 0 15px;">
                <div class="mtd-shipin-tab-left">
                  <ul class="clear">
                    <li><a href="#" title="" class="checked-bottom">按热度排序</a></li>
                    <li><a href="#" title="">按关联视频数排序</a></li>
                    <li><a href="#" title="">按浏览量增量排序</a></li>
                    <li><a href="#" title="">按全网销量增量排序</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div> 
          <!-- 表格 -->
          <table class="layui-table" lay-even="" lay-skin="nob">
            <colgroup>
              <col width="100">
              <col width="300">
              <col width="100">
              <col width="100">
              <col>
              <col>
              <col>
              <col>
            </colgroup>
            <thead>
              <tr>
                <th>排名</th>
                <th>商品</th>
                <th>热度</th>
                <th>关联视频数</th>
                <th>抖音浏览量增量</th>
                <th>全网销售增量</th>
                <th>售价</th>
                <th>操作</th>
              </tr> 
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td class="mtd-mtd-shipin-table-td2 kol-mtd-shipin-table-td2">
                  <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                  <p class="yichu">欧式蕾丝床席梦思欧式蕾丝床席梦思欧式蕾丝床席梦思</p>
                </td>
                <td class="color8c">4.3w</td>
                <td class="color8c">327</td>
                <td class="color8c">7728</td>
                <td class="color8c">570</td>
                <td style="color: red">￥88.0</td>
                <td class="mtd-shipin-td-btn"><a href="list1.html" title="" class="mtd-shipin-td-btn"><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo5.png" alt="">详情</button></a></td>
              </tr>
             <tr>
                <td>2</td>
                <td class="mtd-mtd-shipin-table-td2 kol-mtd-shipin-table-td2">
                  <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                  <p class="yichu">欧式蕾丝床席梦思欧式蕾丝床席梦思欧式蕾丝床席梦思</p>
                </td>
                <td class="color8c">4.3w</td>
                <td class="color8c">327</td>
                <td class="color8c">7728</td>
                <td class="color8c">570</td>
                <td style="color: red">￥88.0</td>
                <td class="mtd-shipin-td-btn"><a href="list1.html" title="" class="mtd-shipin-td-btn"><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo5.png" alt="">详情</button></a></td>
              </tr>
              <tr>
                <td>3</td>
                <td class="mtd-mtd-shipin-table-td2 kol-mtd-shipin-table-td2">
                  <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                  <p class="yichu">欧式蕾丝床席梦思欧式蕾丝床席梦思欧式蕾丝床席梦思</p>
                </td>
                <td class="color8c">4.3w</td>
                <td class="color8c">327</td>
                <td class="color8c">7728</td>
                <td class="color8c">570</td>
                <td style="color: red">￥88.0</td>
                <td class="mtd-shipin-td-btn"><a href="list1.html" title="" class="mtd-shipin-td-btn"><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo5.png" alt="">详情</button></a></td>
              </tr>
              <tr>
                <td>4</td>
                <td class="mtd-mtd-shipin-table-td2 kol-mtd-shipin-table-td2">
                  <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                  <p class="yichu">欧式蕾丝床席梦思欧式蕾丝床席梦思欧式蕾丝床席梦思</p>
                </td>
                <td class="color8c">4.3w</td>
                <td class="color8c">327</td>
                <td class="color8c">7728</td>
                <td class="color8c">570</td>
                <td style="color: red">￥88.0</td>
                <td class="mtd-shipin-td-btn"><a href="list1.html" title="" class="mtd-shipin-td-btn"><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo5.png" alt="">详情</button></a></td>
              </tr>
              <tr>
                <td>5</td>
                <td class="mtd-mtd-shipin-table-td2 kol-mtd-shipin-table-td2">
                  <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                  <p class="yichu">欧式蕾丝床席梦思欧式蕾丝床席梦思欧式蕾丝床席梦思</p>
                </td>
                <td class="color8c">4.3w</td>
                <td class="color8c">327</td>
                <td class="color8c">7728</td>
                <td class="color8c">570</td>
                <td style="color: red">￥88.0</td>
                <td class="mtd-shipin-td-btn"><a href="list1.html" title="" class="mtd-shipin-td-btn"><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo5.png" alt="">详情</button></a></td>
              </tr>
            </tbody>
          </table>
        </div>
      <?php elseif($type == 'task'): ?>
        <!-- 任务平台 内容 -->
        <div class="clickchangetab" id="renwu-pingtai">
          <!-- 一个完整的任务平台 -->
          <div class="layui-col-md6 kol-list6 clear">
            <div class="layui-card">
              <div class="layui-card-body">
                <div class="kol-list6-bai">
                  <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                  <div class="kol-list6-content">
                    <p style="color: #000">李佳琪的大V视频定制</p>
                    <p style="color: #ff5a01">￥4500.0</p>
                    <p>成交：0次&nbsp;&nbsp;&nbsp;&nbsp;好评率：0%</p>
                  </div>
                  <button class="layui-btn layui-btn-sm hezuo-btn right">立即合作</button>
                </div>
                <div class="kol-list6-hui">
                  <p><span style="color: #000">服务评价</span><a href="#" title="" class="right">查看更多评价> </a></p>
                  <p>与店家沟通起来很顺利，制作水平也很专业，无需多费口舌就可以理解到我想要的功能点，这让我很开心，省了不少事。。距离约定时间提前了半小时交付，同时店家也承诺如果后续有使用问题可以随时沟通。</p>
                </div>
              </div>
            </div>
          </div>
          <!--  -->
          <div class="layui-col-md6 kol-list6 clear">
            <div class="layui-card">
              <div class="layui-card-body">
                <div class="kol-list6-bai">
                  <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                  <div class="kol-list6-content">
                    <p style="color: #000">李佳琪的大V视频定制</p>
                    <p style="color: #ff5a01">￥4500.0</p>
                    <p>成交：0次&nbsp;&nbsp;&nbsp;&nbsp;好评率：0%</p>
                  </div>
                  <button class="layui-btn layui-btn-sm hezuo-btn right">立即合作</button>
                </div>
                <div class="kol-list6-hui">
                  <p><span style="color: #000">服务评价</span><a href="#" title="" class="right">查看更多评价> </a></p>
                  <p>与店家沟通起来很顺利，制作水平也很专业，无需多费口舌就可以理解到我想要的功能点，这让我很开心，省了不少事。。距离约定时间提前了半小时交付，同时店家也承诺如果后续有使用问题可以随时沟通。</p>
                </div>
              </div>
            </div>
          </div>
          <!--  -->
          <div class="layui-col-md6 kol-list6 clear">
            <div class="layui-card">
              <div class="layui-card-body">
                <div class="kol-list6-bai">
                  <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                  <div class="kol-list6-content">
                    <p style="color: #000">李佳琪的大V视频定制</p>
                    <p style="color: #ff5a01">￥4500.0</p>
                    <p>成交：0次&nbsp;&nbsp;&nbsp;&nbsp;好评率：0%</p>
                  </div>
                  <button class="layui-btn layui-btn-sm hezuo-btn right">立即合作</button>
                </div>
                <div class="kol-list6-hui">
                  <p><span style="color: #000">服务评价</span><a href="#" title="" class="right">查看更多评价> </a></p>
                  <p>与店家沟通起来很顺利，制作水平也很专业，无需多费口舌就可以理解到我想要的功能点，这让我很开心，省了不少事。。距离约定时间提前了半小时交付，同时店家也承诺如果后续有使用问题可以随时沟通。</p>
                </div>
              </div>
            </div>
          </div>
          <!--  -->
          <div class="layui-col-md6 kol-list6 clear">
            <div class="layui-card">
              <div class="layui-card-body">
                <div class="kol-list6-bai">
                  <img src="/static/index/layuiadmin/imgs/touxiang.jpg" alt="">
                  <div class="kol-list6-content">
                    <p style="color: #000">李佳琪的大V视频定制</p>
                    <p style="color: #ff5a01">￥4500.0</p>
                    <p>成交：0次&nbsp;&nbsp;&nbsp;&nbsp;好评率：0%</p>
                  </div>
                  <button class="layui-btn layui-btn-sm hezuo-btn right">立即合作</button>
                </div>
                <div class="kol-list6-hui">
                  <p><span style="color: #000">服务评价</span><a href="#" title="" class="right">查看更多评价> </a></p>
                  <p>与店家沟通起来很顺利，制作水平也很专业，无需多费口舌就可以理解到我想要的功能点，这让我很开心，省了不少事。。距离约定时间提前了半小时交付，同时店家也承诺如果后续有使用问题可以随时沟通。</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
</div>
<!-- 任务平台中的弹框 -->
<!-- 确认发布 弹框内容 -->
<div class="mask">
  <div class="mask-content">
    <div class="wrapper">
      <h3><span class="juxian"></span>发布单人任务<a href="#" class="guan right">X</a></h3>
      <p>这是一段关于单人任务说明的一个简介这是一段关于单人任务说明的一个简介这是一段关于单人任务说明的一个简介这是一段关于单人任务说明的一个简介</p>
      <div style="color: #fd6500;text-align: center; margin-top: 20px">
        <input type="checkbox" name="like1[write]" lay-skin="primary" title="" checked="">阅读并同意
      </div>
      <div class="two-btn">
        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm guan" style="color: #fd6500;">取消</button>
        <button type="button" class="layui-btn layui-btn-sm layui-btn-danger hezuo-btn2">确认发布</button>
      </div>
    </div>
  </div>
</div>

<!-- 立即下单 弹框内容 -->
<div class="mask2">
  <div class="mask-content2" style="width:500px;height: 485px">
    <div class="wrapper">
      <h3><span class="juxian"></span>发布单人任务<span style="color: #b7b7b7;font-size: 14px;">下单后，我们将尽快为您联系</span><a href="#" class="guan right">X</a></h3>
      <!-- 我需要 -->
      <div class="content2-content clear">
        <p class="left">我需要<span style="color: red"> *</span></p>
        <textarea name="" style="width: 80%; height: 100px" class="left" placeholder="李佳琪的大V定制服务"></textarea>
      </div>
      <!-- 预算金额 -->
      <div class="content2-content clear">
        <p class="left">预算金额<span style="color: red"> *</span></p>
        <form class="layui-form" action=""style="width: 80%;display: inline-block;height:35px" class="left">
          <div class="layui-form-item">
            <div class="layui-input-block" style="margin-left: 0">
              <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="/元" class="layui-input" style="width: 45%">
            </div>
          </div>
        </form>
      </div>
      <!-- 播放指标 -->
      <div class="content2-content clear">
        <p class="left">播放指标<span style="color: red"> *</span></p>
        <form class="layui-form" action=""style="width: 80%;display: inline-block;height:35px" class="left">
          <div class="layui-form-item">
            <div class="layui-input-block" style="margin-left: 0">
              <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="/次" class="layui-input" style="width: 45%">
            </div>
          </div>
        </form>
      </div>
      <p style="color:#ff6600;text-align: right">总计 4500.00元</p>
      <!-- 手机号码 -->
      <div class="content2-content clear">
        <p class="left">手机号码<span style="color: red"> *</span></p>
        <form class="layui-form" action=""style="width: 80%;display: inline-block;height:35px" class="left">
          <div class="layui-form-item">
            <div class="layui-input-block" style="margin-left: 0">
              <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="131****9757" class="layui-input">
            </div>
          </div>
        </form>
      </div>
      <p style="color:#67ad3b;display: block;text-align: center">信息保护中，仅官方可见</p>
      <!-- 截止日期 -->
      <div class="content2-content clear">
        <p class="left">截止日期<span style="color: red"> *</span></p>
        <div class="layui-form" action=""style="width: 80%;display: inline-block;height:35px" class="left">
          <div class="layui-form-item">
            <div class="layui-inline">
              <div class="layui-input-inline">
                <input type="text" class="layui-input" id="test1" placeholder="2019-09-26">
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- 商品链接 -->
      <div class="content2-content clear">
        <p class="left">商品链接<span style="color: red"> *</span></p>
        <form class="layui-form" action=""style="width: 80%;display: inline-block;height:35px" class="left">
          <div class="layui-form-item">
            <div class="layui-input-block" style="margin-left: 0">
              <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
            </div>
          </div>
        </form>
      </div>
      <div class="two-btn">
        <a href="xiadan.html" title=""><button type="button" class="layui-btn layui-btn-sm layui-btn-danger hezuo-btn2">立即下单</button></a>
      </div>
    </div>
  </div>
</div>


<script src="/static/index/layuiadmin/layui/layui.js"></script>  
<script src="/static/index/layuiadmin/style/js/jquery.min.js"></script>  
<script src="/static/index/layuiadmin/style/js/jquery.easy-pie-chart.js"></script>  
<script src="/static/index/layuiadmin/style/js/echarts.min.js"></script>
<script>
layui.config({
    base: '/static/index/layuiadmin/' //静态资源所在路径
}).extend({
    index: '/lib/index' //主入口模块
}).use(['index', 'form', 'layer'], function(){
  var layer = layui.layer
  ,form = layui.form
  ,$ = layui.$;
  
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


  $(".ordera").click(function(){
    $("#orderinput").val($(this).attr('data-value'));
    GetVideoDataFunction();
  });

  $("#videosearch").click(function(){
    GetVideoDataFunction();
  });

  function GetVideoDataFunction(){

    var keyword = $("#videokeyword").val();
    var goods = $("#videogoods").is(':checked') ? 1 : 0;
    var order = $("#orderinput").val() == 'v.create_time desc' ? 'create_time' : 'hot';

    location.href = "<?php echo url('info','',false); ?>/kid/"+<?php echo $kol['kol_id']; ?> +"/type/video/order/"+order+"/goods/"+goods+"/keyword/"+keyword;
  }

  $("#showqrcode").mouseover(function(){
    layer.open({
      type: 4,
      shade: 0,
      content: ["<div id='qrcodebox' style='background-image: url(<?php echo $kol['kol_qrcode']; ?>)'></div>", '#showqrcode']
    });
  });

  $("#showqrcode").mouseout(function(){
    var index = layer.tips();
    layer.close(index);
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

</script>
<?php if($type == 'data'): ?>
  <script>
    // 3.最新视频数据表现  图表
    var myChart = echarts.init(document.getElementById("tb1"));
    var option = {
        title : {},
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['点赞','评论']
        },
        toolbox: {
            show : true,
            feature : {
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                data : <?php echo $recently['title']; ?>
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'点赞',
                type:'bar',
                data:<?php echo $recently['like']; ?>,
                color:['#0b62a5'],
            },
            {
                name:'评论',
                type:'bar',
                data:<?php echo $recently['comment']; ?>,
                color:['#7791a2'],
            }
        ]
    };
    myChart.setOption(option); 

    // 4. 播放趋势
    var myChart = echarts.init(document.getElementById("tb2"));
    var option = {
        title: {},
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:['总量','增量']
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: <?php echo $trends['date']; ?>
        },
        yAxis: {
            type: 'value'
        },
        series: [
            {
                name:'总量',
                type:'line',
                stack: '总量',
                color:['#7b92a2'],
                smooth: true,
                data:[120, 132, 101, 134, 90, 230, 210]
            },
            {
                name:'增量',
                type:'line',
                stack: '总量',
                color:['#0b62a5'],
                smooth: true,
                data:[220, 182, 191, 234, 290, 330, 310]
            },
        ]
    };
    myChart.setOption(option); 

    // 5. 粉丝趋势
    var myChart = echarts.init(document.getElementById("tb3"));
    var option = {
        title: {},
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:['总量','增量']
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: <?php echo $trends['date']; ?>
        },
        yAxis: {
            type: 'value'
        },
        series: [
            {
                name:'总量',
                type:'line',
                stack: '总量',
                color:['#7b92a2'],
                smooth: true,
                data:<?php echo $trends['fans']; ?>
            },
            // {
            //     name:'增量',
            //     type:'line',
            //     stack: '总量',
            //     color:['#0b62a5'],
            //     smooth: true,
            //     data:[220, 182, 191, 234, 290, 330, 310]
            // },
        ]
    };
    myChart.setOption(option); 

    // 6. 评论趋势
    var myChart = echarts.init(document.getElementById("tb4"));
    var option = {
        title: {},
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:['总量','增量']
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: <?php echo $trends['date']; ?>
        },
        yAxis: {
            type: 'value'
        },
        series: [
            {
                name:'总量',
                type:'line',
                stack: '总量',
                color:['#7b92a2'],
                smooth: true,
                data:<?php echo $trends['comments']; ?>
            },
            // {
            //     name:'增量',
            //     type:'line',
            //     stack: '总量',
            //     color:['#0b62a5'],
            //     smooth: true,
            //     data:[220, 182, 191, 234, 290, 330, 310]
            // },
        ]
    };
    myChart.setOption(option); 

    // 7. 转发趋势
    var myChart = echarts.init(document.getElementById("tb5"));
    var option = {
        title: {},
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:['总量','增量']
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: <?php echo $trends['date']; ?>
        },
        yAxis: {
            type: 'value'
        },
        series: [
            {
                name:'总量',
                type:'line',
                stack: '总量',
                color:['#7b92a2'],
                smooth: true,
                data:<?php echo $trends['share']; ?>
            },
            // {
            //     name:'增量',
            //     type:'line',
            //     stack: '总量',
            //     color:['#0b62a5'],
            //     smooth: true,
            //     data:[220, 182, 191, 234, 290, 330, 310]
            // },
        ]
    };
    myChart.setOption(option); 

    // 8. 点赞趋势
    var myChart = echarts.init(document.getElementById("tb6"));
    var option = {
        title: {},
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:['总量','增量']
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: <?php echo $trends['date']; ?>
        },
        yAxis: {
            type: 'value'
        },
        series: [
            {
                name:'总量',
                type:'line',
                stack: '总量',
                color:['#7b92a2'],
                smooth: true,
                data:<?php echo $trends['like']; ?>
            },
            // {
            //     name:'增量',
            //     type:'line',
            //     stack: '总量',
            //     color:['#0b62a5'],
            //     smooth: true,
            //     data:[220, 182, 191, 234, 290, 330, 310]
            // },
        ]
    };
    myChart.setOption(option); 

    // 9. 评论热词TOP10
    var myChart = echarts.init(document.getElementById("tb7"));
    var option = {
        color: ['#018fcf'],
        tooltip : {
            trigger: 'axis',
            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis : [
            {
                type : 'category',
                data : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sep','Oct','Nov','Dec'],
                axisTick: {
                    alignWithLabel: true
                }
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'直接访问',
                type:'bar',
                barWidth: '60%',
                data:[10, 52, 200, 334, 390, 330, 22,10, 52, 200, 334, 390]
            }
        ]
    };
    myChart.setOption(option); 


    // 10. 左侧仪表盘
    var myChart = echarts.init(document.getElementById("tb8"));
    var option = {
        tooltip : {
            formatter: "{a} <br/>{b} : {c}%"
        },
        toolbox: {
            feature: {
                restore: {},
                saveAsImage: {}
            }
        },
        series: [
            {
                name: '业务指标',
                type: 'gauge',
                data: [{value: <?php echo $trend['tbhot']; ?>, name: ''}]
            }
        ]
    };
    setInterval(function () {
        option.series[0].data[0].value = (Math.random() * 100).toFixed(2) - 0;
        myChart.setOption(option, true);
    },2000);
    myChart.setOption(option);

    // 11. 左侧仪表盘
    var myChart = echarts.init(document.getElementById("tb9"));
    var option = {
        tooltip : {
            formatter: "{a} <br/>{b} : {c}%"
        },
        toolbox: {
            feature: {
                restore: {},
                saveAsImage: {}
            }
        },
        series: [
            {
                name: '业务指标',
                type: 'gauge',
                data: [{value: 70, name: ''}]
            }
        ]
    };
  </script>
<?php endif; ?>


<script>
  setInterval(function () {
      option.series[0].data[0].value = (Math.random() * 100).toFixed(2) - 0;
      myChart.setOption(option, true);
  },2000);
  myChart.setOption(option);



  // 12. 年月日期时间范围
  layui.use('laydate', function(){
    var laydate = layui.laydate;
    //日期范围
    laydate.render({
      elem: '#test6'
      ,range: true
    });
  });

  // 13.最新视频数据表现  图表
  layui.use(['form', 'layedit', 'laydate'], function(){
    var form = layui.form
    ,layer = layui.layer
    ,layedit = layui.layedit
    ,laydate = layui.laydate;
    
    //创建一个编辑器
    var editIndex = layedit.build('LAY_demo_editor');
  });
</script>
  <?php if($type == 'fans' && !empty($public)): ?>
    <script>
      var myChart = echarts.init(document.getElementById("fs-tb1")); 
      var option = {
          xAxis: {
              type: 'category',
              data: <?php echo $public['age']['key']; ?>,
          },
          color: ['#3398DB'],
          yAxis: {
              type: 'value'
          },
          series: [{
              data: <?php echo $public['age']['val']; ?>,
              type: 'bar'
          }]
      };
      myChart.setOption(option); 

      // 2.粉丝活跃时间分布-天 图表
      var myChart = echarts.init(document.getElementById("fs-tb2")); 
      var option = {
          xAxis: {
              type: 'category',
              data: ['0时', '2时', '4时', '6时', '80时', '10时', '12时', '14时', '16时', '18时', '20时', '22时']
          },
          color: ['#3398DB'],
          yAxis: {
              type: 'value'
          },
          series: [{
              data: [120, 200, 150, 80, 70, 110, 130, 150, 80, 70, 110, 130],
              type: 'bar'
          }]
      };
      myChart.setOption(option);


      // 3.粉丝活跃时间分布-天 图表
      var myChart = echarts.init(document.getElementById("fs-tb3")); 
      var option = {
          xAxis: {
              type: 'category',
              data: ['星期一', '星期二', '星期三', '星期四', '星期五', '星期六', '星期日']
          },
          color: ['#3398DB'],
          yAxis: {
              type: 'value'
          },
          series: [{
              data: [120, 200, 150, 80, 70, 110, 130],
              type: 'bar'
          }]
      };
      myChart.setOption(option);
    </script>
  <?php endif; ?>
<script>
// 电商数据分析中的js
// 1. 上榜趋势图
var myChart = echarts.init(document.getElementById("dianshang-tb1")); 
var option = {
    xAxis: {
        type: 'category',
        data: ['08-22', '08-23', '08-24', '08-25', '08-26', '08-27', '08-28','08-29', '08-30', '08-31', '09-01', '09-02', '09-03', '09-04']
    },
    color:['#0092ff'],
    yAxis: {
        type: 'value'
    },
    series: [{
        data: [820, 932, 901, 934, 1290, 1330, 1320,820, 932, 901, 934, 1290, 1330, 1320],
        type: 'line',
        smooth: true
    }]
};
myChart.setOption(option); 


//任务平台中的js
// 1.确认发布弹框
$(".hezuo-btn").click(function(){
  $(".mask").show();
});
  $(".guan").click(function(){
  $(".mask").hide();
});

// 2.立即下单弹框
$(".hezuo-btn2").click(function(){
  $(".mask2").show();
});
  $(".guan").click(function(){
  $(".mask2").hide();
});

// 3.时间
layui.use('laydate', function(){
  var laydate = layui.laydate;
  //常规用法
  laydate.render({
    elem: '#test1'
  });
});


// tab 修改
$(".checktab").click(function(){
  
  var self = $(this);

  $(".clickchangetab").hide();
  $(".checktab").removeClass('checked-bottom');

  self.addClass('checked-bottom');

  $("#"+self.attr('data-value')).show();

})

</script>
</body>
</html>