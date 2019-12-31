<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"/www/wwwroot/newm15/public/../application/index/view/video/show.html";i:1575964801;}*/ ?>
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
.mtd-shipin-tab-left{display: block}
.mtd-shipin-tab-left ul li{width: 30%;text-align: center}
.mtd-shipin-tab-left ul li a{padding-bottom: 3px;}


.mtd-shipin-td-btn a,.mtd-shipin-td-btn button{display: block !important;float: left !important;width: 73px !important;height: 35px}
.mtd-shipin-list1-img3 {float: left;}
.playvideo {
  border-top-left-radius: 5px !important;
  border-bottom-left-radius: 5px !important;
  border-right: 0px !important;
  border-top-right-radius: 0px !important;
  border-bottom-right-radius: 0px !important
}
.emptyhint {
  text-align: center;
  padding-top: 350px;
}

#qrcode{
  width: 100px;
  height: 100px;
  margin:auto;
  background:url() center no-repeat;
  background-size: 100% 100%;
}

#qrcodebox{
  width: 25%;
  background-color: #ddd;
  font-size: 1px;
  text-align: center;
  padding-top: 5px;
  float: left;
  display: none;
}
</style>
<body onLoad="initPieChart();">
  <!-- 点击shipin里的详情按钮跳到此页 -->
  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <!-- 视频介绍 -->
      <div class="layui-col-md6">
        <div class="layui-card">
          <div class="layui-card-body" style="height: 160px">
            <img src="<?php echo $video['video_cover']; ?>" alt="" class="mtd-shipin-list1-img">
            <div class="mtd-shipin-list1">
              <h4 class="yichu clear"><?php echo $video['video_title']; ?>
                <span class="right mtd-shipin-td-btn">
                  <img src="/static/index/layuiadmin/imgs/2.png" class="mtd-shipin-list1-img3" id="showqrcode">
                  <a href="<?php echo $video['video_url']; ?>" target="_blank">
                    <button type="button" class="layui-btn layui-btn-sm playvideo"><img src="/static/index/layuiadmin/imgs/caozuo2.png" alt="">播放</button>
                  </a>
                  <button type="button" class="layui-btn layui-btn-sm collbat" data-value="<?php echo $video['video_id']; ?>"><img src="/static/index/layuiadmin/imgs/caozuo3.png" alt="">收藏</button>
                </span>
              </h4>
              <h5>
                <div style="width: 65%;float: left;">                
                  <p>分类： <span><?php echo $video['video_sort']; ?></span></p>
                  <p>平台： <img src="/static/index/layuiadmin/imgs/1.png" alt="" class="mtd-shipin-list1-img2"><span>抖音</span></p>
                  <p class="yichu">简介： <?php echo $video['video_desc']; ?></p>
                  <p>视频时长： <?php echo $video['video_duration']/1000; ?>秒<span style="margin-left: 20px;color: #000">发布时间： <?php echo date('Y-m-d',$video['create_time']); ?></span></p>
                </div>
                <div id="qrcodebox">
                  <div id="qrcode" style="background-image: url(<?php echo $video['video_qrcode']; ?>)"></div>
                  抖音扫码观看完整视频
                </div>
              </h5>
            </div>
          </div>
        </div>
      </div>
      <!-- 粉丝点赞评论分享 -->
      <div class="layui-col-md6">
        <div class="layui-card">
          <div class="layui-card-body clear" style="height: 160px">
            <!-- 左边图片 -->
            <div class="mtd-list1-shenyang-left left">
              <p style="text-align: center;"><?php echo $author['kol_nickname']; if($author['kol_is_goods'] == 1): ?> <img src="/static/index/layuiadmin/imgs/3.png" style="margin-left:10px"> <?php endif; ?></p>
              <a href="<?php echo url('kol/info',array('kid'=>$author['kol_id'])); ?>"><img src="<?php echo $author['kol_avatar']; ?>" alt="" class="mtd-list1-shenyang-left-img"></a>
              <div class="mtd-list1-shenyang-div">
                <img src="/static/index/layuiadmin/imgs/10.png" alt="" style="width: 18px"><?php echo $atrend['kt_hot']; ?>
              </div>
            </div>
            <!-- 右边数据 -->
            <div class="mtd-list1-shenyang-right left">
              <div class="container" style="width: 100%">
                <div class="chart">
                  <div class="percentage" data-percent="55"><span><?php echo $atrend['kt_fans']; ?></span></div>
                  <div class="labeler">粉丝数</div>
                </div>
                <div class="chart">
                  <div class="percentage" data-percent="46"><span><?php echo $atrend['kt_mean_like']; ?></span></div>
                  <div class="labeler">平均点赞</div>
                </div>
                <div class="chart">
                  <div class="percentage" data-percent="92"><span><?php echo $atrend['kt_mean_comment']; ?></span></div>
                  <div class="labeler">平均评论</div>
                </div>
                <div class="chart">
                  <div class="percentage" data-percent="84"><span><?php echo $atrend['kt_mean_share']; ?></span></div>
                  <div class="labeler">平均分享</div>
                </div>
              </div>
              <p>抖音号：<?php echo $author['kol_number']; ?><span class="span-margin-l">性别：<?php if($author['kol_sex'] == 1): ?> 男 <?php elseif($author['kol_sex'] == 2): ?> 女 <?php else: ?> 未设置 <?php endif; ?></span><span class="span-margin-l">地区：<?php echo $author['kol_cityname']; ?></span></p>
              <p>分类：<?php echo $author['kol_sort']; ?></p>
              <?php if($author['kol_verifyname'] != ''): ?><p><img src="/static/index/layuiadmin/imgs/a-2.png" alt=""> <?php echo $author['kol_verifyname']; ?></p><?php endif; ?>
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
                <li><a href="<?php echo url('show',array('vid'=>$video['video_id'],'type'=>'video')); ?>" class="changetab <?php if(($type == 'video')): ?> checked-bottom <?php endif; ?>">视频详情</a></li>
                <li><a href="<?php echo url('show',array('vid'=>$video['video_id'],'type'=>'goods')); ?>" class="changetab <?php if(($type == 'goods')): ?> checked-bottom <?php endif; ?>">商品分析</a></li>
                <li><a href="<?php echo url('show',array('vid'=>$video['video_id'],'type'=>'music')); ?>" class="changetab <?php if(($type == 'music')): ?> checked-bottom <?php endif; ?>">素材分析</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <?php if($type == 'video'): ?>
        <!-- 视频信息 -->
        <div class="checkchangetab" id="videoInfo">
        <!-- 总播放量 总下载数 总评论数 总转发数 总点赞数 -->
        <div class="layui-col-md12">
          <div class="layui-card">
            <div class="layui-card-body">
              <div class="mtd-shipin-list1-ul">
                <ul class="clear">
                  <li>
                    <div class="mtd-shipin-list1-div">
                      <img src="/static/index/layuiadmin/imgs/4.png" alt="">
                      <div class="mtd-shipin-list1-div-right right">
                        <h4><?php echo $trend['vt_hot']; ?></h4>
                        <p>视频热度</p>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="mtd-shipin-list1-div">
                      <img src="/static/index/layuiadmin/imgs/5.png" alt="">
                      <div class="mtd-shipin-list1-div-right right">
                        <h4><?php echo $trend['vt_download']; ?></h4>
                        <p>总下载数</p>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="mtd-shipin-list1-div">
                      <img src="/static/index/layuiadmin/imgs/6.png" alt="">
                      <div class="mtd-shipin-list1-div-right right">
                        <h4><?php echo $trend['vt_comment']; ?></h4>
                        <p>总评论数</p>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="mtd-shipin-list1-div">
                      <img src="/static/index/layuiadmin/imgs/7.png" alt="">
                      <div class="mtd-shipin-list1-div-right right">
                        <h4><?php echo $trend['vt_reposts']; ?></h4>
                        <p>总转发数</p>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="mtd-shipin-list1-div">
                      <img src="/static/index/layuiadmin/imgs/8.png" alt="">
                      <div class="mtd-shipin-list1-div-right right">
                        <h4><?php echo $trend['vt_like']; ?></h4>
                        <p>总点赞数</p>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="mtd-shipin-list1-div">
                      <img src="/static/index/layuiadmin/imgs/4.png" alt="">
                      <div class="mtd-shipin-list1-div-right right">
                        <h4>0</h4>
                        <p>近30天增量</p>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="mtd-shipin-list1-div">
                      <img src="/static/index/layuiadmin/imgs/5.png" alt="">
                      <div class="mtd-shipin-list1-div-right right">
                        <h4><?php echo $inctrend['download']; ?></h4>
                        <p>近30天增量</p>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="mtd-shipin-list1-div">
                      <img src="/static/index/layuiadmin/imgs/6.png" alt="">
                      <div class="mtd-shipin-list1-div-right right">
                        <h4><?php echo $inctrend['comment']; ?></h4>
                        <p>近30天增量</p>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="mtd-shipin-list1-div">
                      <img src="/static/index/layuiadmin/imgs/7.png" alt="">
                      <div class="mtd-shipin-list1-div-right right">
                        <h4><?php echo $inctrend['reposts']; ?></h4>
                        <p>近30天增量</p>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="mtd-shipin-list1-div">
                      <img src="/static/index/layuiadmin/imgs/8.png" alt="">
                      <div class="mtd-shipin-list1-div-right right">
                        <h4><?php echo $inctrend['like']; ?></h4>
                        <p>近30天增量</p>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <!-- 评论热词 -->
              <div class="left layui-col-md6">
                <div class="layui-card">
                  <div class="layui-card-header">评论热词TOP10</div>
                  <div class="layui-card-body">
                    <div id="tb1" style="height:500px"></div>
                  </div>
                </div>
              </div>
              <div class="left layui-col-md6">
                <div class="layui-card" style="background-color: #f0f1f3">
                  <div class="layui-card-header">全部评论（<?php echo $trend['vt_comment']; ?>）</div>
                  <div class="layui-card-body clear layui-pinglun-overflow">
                  <!-- 一条评论 start -->
                    <?php if(is_array($comment) || $comment instanceof \think\Collection || $comment instanceof \think\Paginator): $i = 0; $__LIST__ = $comment;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$citem): $mod = ($i % 2 );++$i;?>
                      <div class="yi-pinglun">
                        <!-- 评论者信息 -->
                        <div class="pinglun-top">
                          <img src="<?php echo $citem['comm_avatar']; ?>" alt="">
                          <span style="color: #000"><?php echo $citem['comm_nickname']; ?></span>
                          <span class="right pinglun-span"><img src="/static/index/layuiadmin/imgs/xin-hui.png" alt=""><?php echo $citem['comm_like']; ?></span>
                        </div>
                        <!-- 评论内容 -->
                        <div class="pinglun-content"><?php echo $citem['comm_text']; ?></div>
                        <!-- 评论时间 -->
                        <p><?php echo $citem['comm_time']; ?></p>
                      </div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                  </div>
                </div>
              </div>
        <!-- 年龄分布 -->
          <div class="layui-col-md6">
            <div class="layui-card">
              <div class="layui-card-header">年龄分布</div>
              <div class="layui-card-body">
                <div id="tb3" style="height: 500px"></div>
              </div>
            </div>
          </div>
          <div class="layui-col-md6">
              <div class="layui-card">
                <div class="layui-card-header">地域分布<span class="right mtd-shipin-td-btn"><a href="list1.html" title="" class="mtd-shipin-td-btn mtd-shipin-td-btna"><button type="button" class="layui-btn layui-btn-sm  mtd-shipin-btn-checked">省份</button></a><button type="button" class="layui-btn layui-btn-sm">城市</button></span></div>
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
        <!-- 折线统计图 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-header">近5天点赞量趋势图</div><div class="layui-card-body"><div id="tb4" style="height:500px"></div></div>
            </div>
          </div>
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-header">近5天转发量趋势图</div><div class="layui-card-body"><div id="tb5" style="height:500px"></div></div>
            </div>
          </div>
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-header">近5天评论量趋势图</div><div class="layui-card-body"><div id="tb6" style="height:500px"></div></div>
            </div>
          </div>
        </div>
      <?php elseif($type == 'goods'): ?>
        <!-- 商品信息 -->
        <div class="checkchangetab" id="goodsInfo">
          
          <?php if(empty($goods)): ?>
            <div class="emptyhint">该视频不包含商品信息</div>
          <?php else: ?>
            <div class="layui-col-md12">
              <div class="layui-card">
                <div class="layui-card-body">
                  <img src="/static/index/layuiadmin/imgs/no1-1.png" alt="" style="width: 100px;height: 100px">
                  <div class="mtd-shipin-list2-div">
                    <h5><span>放心购</span>【荣景优品】5册 不吼不叫正面管教好妈妈胜过好老师，教育家教</h5>
                    <h3><span class="span1-1">￥78</span><del class="span1-2">原价：167</del></h3>
                    <p>抖音浏览量：<span>229.3w</span> &nbsp;&nbsp;&nbsp;全网销量：<span>3.2w</span></p>
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
                      <th style="text-align: left">视频内容</th>
                      <th>主播名称</th>
                      <th>点赞数</th>
                      <th>评论数</th>
                      <th>传播价值</th>
                      <th>操作</th>
                    </tr> 
                  </thead>
                  <tbody>
                    <tr>
                      <td><img src="/static/index/layuiadmin/imgs/no1.png" alt="" style="width: 25px"></td>
                      <td class="mtd-mtd-shipin-table-td2"><img src="/static/index/layuiadmin/imgs/no1-1.png" alt=""><p class="yichu">#亮剑 他号称晋西北第一喷子...</p></td>
                      <td>武博道馆</td>
                      <td class="color8c">42.9w</td>
                      <td class="color8c">9689</td>
                      <td><img src="/static/index/layuiadmin/imgs/sheng.png" class="shengzhi">97.5</td>
                      <td class="mtd-shipin-td-btn"><a href="list1.html" title="" class="mtd-shipin-td-btn mtd-shipin-td-btna"><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo1.png" alt="">详情</button></a><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo2.png" alt="">播放</button><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo3.png" alt="">收藏</button></td>
                    </tr>
                    <tr>
                      <td><img src="/static/index/layuiadmin/imgs/no2.png" alt="" style="width: 25px"></td>
                      <td class="mtd-mtd-shipin-table-td2"><img src="/static/index/layuiadmin/imgs/no2-2.png" alt=""><p class="yichu">相信看到最后，有很多人蠢蠢欲动!...</p></td>
                      <td>三洋乡墅</td>
                      <td class="color8c">42.9w</td>
                      <td class="color8c">9689</td>
                      <td><img src="/static/index/layuiadmin/imgs/sheng.png" class="shengzhi">97.5</td>
                      <td class="mtd-shipin-td-btn"><a href="list1.html" title="" class="mtd-shipin-td-btn mtd-shipin-td-btna"><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo1.png" alt="">详情</button></a><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo2.png" alt="">播放</button><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo3.png" alt="">收藏</button></td>
                    </tr>
                    <tr>
                      <td><img src="/static/index/layuiadmin/imgs/no3.png" alt="" style="width: 25px"></td>
                      <td class="mtd-mtd-shipin-table-td2"><img src="/static/index/layuiadmin/imgs/no3-3.png" alt=""><p class="yichu">[春色满堂]2019-09-19发布的视频...</p></td>
                      <td>春色满堂</td>
                      <td class="color8c">42.9w</td>
                      <td class="color8c">9689</td>
                      <td><img src="/static/index/layuiadmin/imgs/sheng.png" class="shengzhi">97.5</td>
                      <td class="mtd-shipin-td-btn"><a href="list1.html" title="" class="mtd-shipin-td-btn mtd-shipin-td-btna"><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo1.png" alt="">详情</button></a><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo2.png" alt="">播放</button><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo3.png" alt="">收藏</button></td>
                    </tr>
                    <tr>
                      <td>4</td>
                      <td class="mtd-mtd-shipin-table-td2"><img src="/static/index/layuiadmin/imgs/no1-1.png" alt=""><p class="yichu">#亮剑 他号称晋西北第一喷子...</p></td>
                      <td>武博道馆</td>
                      <td class="color8c">42.9w</td>
                      <td class="color8c">9689</td>
                      <td><img src="/static/index/layuiadmin/imgs/sheng.png" class="shengzhi">97.5</td>
                      <td class="mtd-shipin-td-btn"><a href="list1.html" title="" class="mtd-shipin-td-btn mtd-shipin-td-btna"><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo1.png" alt="">详情</button></a><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo2.png" alt="">播放</button><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo3.png" alt="">收藏</button></td>
                    </tr>
                    <tr>
                      <td>5</td>
                      <td class="mtd-mtd-shipin-table-td2"><img src="/static/index/layuiadmin/imgs/no2-2.png" alt=""><p class="yichu">相信看到最后，有很多人蠢蠢欲动!...</p></td>
                      <td>三洋乡墅</td>
                      <td class="color8c">42.9w</td>
                      <td class="color8c">9689</td>
                      <td><img src="/static/index/layuiadmin/imgs/sheng.png" class="shengzhi">97.5</td>
                      <td class="mtd-shipin-td-btn"><a href="list1.html" title="" class="mtd-shipin-td-btn mtd-shipin-td-btna"><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo1.png" alt="">详情</button></a><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo2.png" alt="">播放</button><button type="button" class="layui-btn layui-btn-sm"><img src="/static/index/layuiadmin/imgs/caozuo3.png" alt="">收藏</button></td>
                    </tr>
                  </tbody>
                </table> 
              </div>
            </div>
            
            <!-- 折线统计图 -->
            <div class="layui-col-md12">
              <div class="layui-card">
                <div class="layui-card-header">商品销量趋势图<span class="right mtd-shipin-td-btn"><a href="list1.html" title="" class="mtd-shipin-td-btn mtd-shipin-td-btna"><button type="button" class="layui-btn layui-btn-sm  mtd-shipin-btn-checked">总量</button></a><button type="button" class="layui-btn layui-btn-sm">增量</button></span></div>
                <div class="layui-card-body">
                  <div id="tubiao11" style="height:500px"></div>
                </div>
              </div>
            </div>
            <div class="layui-col-md12">
              <div class="layui-card">
                <div class="layui-card-header">商品浏览量趋势图<span class="right mtd-shipin-td-btn"><a href="list1.html" title="" class="mtd-shipin-td-btn mtd-shipin-td-btna"><button type="button" class="layui-btn layui-btn-sm  mtd-shipin-btn-checked">总量</button></a><button type="button" class="layui-btn layui-btn-sm">增量</button></span></div>
                <div class="layui-card-body">
                  <div id="tubiao2" style="height:500px"></div>
                </div>
              </div>
            </div>
            <div class="layui-col-md12">
              <div class="layui-card">
                <div class="layui-card-header">作品点赞趋势图<span class="right mtd-shipin-td-btn"><a href="list1.html" title="" class="mtd-shipin-td-btn mtd-shipin-td-btna"><button type="button" class="layui-btn layui-btn-sm  mtd-shipin-btn-checked">总量</button></a><button type="button" class="layui-btn layui-btn-sm">增量</button></span></div>
                <div class="layui-card-body">
                  <div id="tubiao3" style="height:500px"></div>
                </div>
              </div>
            </div>
            <p style="text-align: center">查看该商品浏览量和同期其他推广视频趋势图，可添加到【我的商品】</p>
            <button class="layui-btn layui-btn-sm layui-btn-normal button-margin" style="display: block;margin: 0 auto">添加我的商品</button>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <!-- 舆情信息-->
        <div class="checkchangetab" id="opinionInfo">
          <!-- 标题 -->
          <div class="layui-col-md12">
            <div class="layui-card">
              <div class="layui-card-body layui-shenhuilu clear">
                <img src="<?php echo $music['music_cover']; ?>" alt="" class="left">
                <div class="layui-shenhuilu-r left">
                  <a href="<?php echo url('music/info',array('mid'=>$music['music_id'])); ?>"><h4><?php echo $music['music_title']; ?><!-- <img src="/static/index/layuiadmin/imgs/caozuo3.png" alt=""> --></h4></a>
                  <p><?php echo $music['music_usercount']; ?>人使用<span class="span-margin-l">时长：<?php echo $music['music_duration']; ?></span></p>
                </div>
              </div>
            </div>
          </div>
          <!-- 两个图表 -->
          <div class="layui-col-md6">
            <div class="layui-card">
              <div class="layui-card-header">使用人数行业分布</div>
              <div class="layui-card-body">
                <div id="tubiao11" style="height: 500px"></div>
              </div>
            </div>
          </div>
          <div class="layui-col-md6">
            <div class="layui-card">
              <div class="layui-card-header">每日新增人数趋势图</div>
              <div class="layui-card-body">
                <div id="tubiao12" style="height: 500px"></div>
              </div>
            </div>
          </div>
          <!-- 好多视频缩图 -->
          <div class="layui-col-md12">
            <?php if(empty($videos)): ?>
              <div style="text-align: center;margin: 50px auto;">暂无相关视频</div>
            <?php else: ?>
              <ul class="shipun-thumb-ul clear">
                <?php if(is_array($videos) || $videos instanceof \think\Collection || $videos instanceof \think\Paginator): $i = 0; $__LIST__ = $videos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): $mod = ($i % 2 );++$i;?>
                  <a href="" title="">
                    <li>
                      <a href="<?php echo url('video/show',array('vid'=>$video['video_id'])); ?>">
                        <img src="<?php echo $video['video_cover']; ?>" alt=""><span><img src="/static/index/layuiadmin/imgs/xin-bai.png" alt="" class="bofang-xin"> <?php echo $video['vt_like']; ?>w</span>
                      </a>
                    </li>
                  </a>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </ul>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <script src="/static/index/layuiadmin/layui/layui.js"></script>  
  <script src="/static/index/layuiadmin/style/js/jquery.min.js"></script>  
  <script src="/static/index/layuiadmin/style/js/jquery.easy-pie-chart.js"></script>  
  <script src="/static/index/layuiadmin/style/js/echarts.min.js"></script>
  <script>

    // 1.轮播 
    layui.use(['carousel', 'layer'], function(){
      var carousel = layui.carousel
          ,layer = layui.layer;
      //建造实例
      carousel.render({
        elem: '#test1'
        ,width: '100%' //设置容器宽度
        ,arrow: 'always' //始终显示箭头
        //,anim: 'updown' //切换动画方式
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


      $("#showqrcode").mouseover(function(){
        $("#qrcodebox").show();
      });

      $("#showqrcode").mouseout(function(){
        $("#qrcodebox").hide();
      });

    });

    // 2.评论热词TOP10 图表
    var myChart = echarts.init(document.getElementById("tb1")); 
    var option = {
        color: ['#3398DB'],
        tooltip : {
            trigger: 'axis',
            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
            }
        },
        color:['#018fcf'],
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis : [
            {
                type : 'category',
                data : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun','Thu', 'Fri', 'Sat', 'Sun'],
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
                type:'bar',
                barWidth: '60%',
                data:[10, 52, 200, 334, 390, 330, 220,334, 390, 330, 220]
            }
        ]
    };
    myChart.setOption(option); 


    // 3. 年龄分布
    var myChart = echarts.init(document.getElementById("tb3")); 
    var option = {
        xAxis: {
            type: 'category',
            data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
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

    // 3.四个仪表盘 图表
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
  <?php if($type == 'video'): ?>
    <script>
      // 4.商品销量趋势图 图表
      var myChart = echarts.init(document.getElementById("tb4")); 
      var option = {
          xAxis: {
              type: 'category',
              data: <?php echo $trendlist['date']; ?>,
          },
          color:['#00a2ff'],
          yAxis: {
              type: 'value'
          },
          series: [{
              data: <?php echo $trendlist['like']; ?>,
              type: 'line',
              smooth: true
          }]
      };
      myChart.setOption(option); 


      // 5.商品浏览量趋势图 图表
      var myChart = echarts.init(document.getElementById("tb5")); 
      var option = {
          xAxis: {
              type: 'category',
              data: <?php echo $trendlist['date']; ?>,
          },
          color:['#00a2ff'],
          yAxis: {
              type: 'value'
          },
          series: [{
              data: <?php echo $trendlist['reposts']; ?>,
              type: 'line',
              smooth: true
          }]
      };
      myChart.setOption(option); 


      //6.作品点赞趋势图 图表
      var myChart = echarts.init(document.getElementById("tb6")); 
      var option = {
          xAxis: {
              type: 'category',
              data: <?php echo $trendlist['date']; ?>,
          },
          color:['#00a2ff'],
          yAxis: {
              type: 'value'
          },
          series: [{
              data: <?php echo $trendlist['comment']; ?>,
              type: 'line',
              smooth: true
          }]
      };
      myChart.setOption(option); 
    </script>
  <?php endif; ?>

  <script>
    // 2.商品销量趋势图 图表
    var myChart = echarts.init(document.getElementById("tubiao1")); 
    var option = {
        xAxis: {
            type: 'category',
            data: ['08-22', '08-23', '08-24', '08-25', '08-26', '08-27', '08-28']
        },
        color:['#00a2ff'],
        yAxis: {
            type: 'value'
        },
        series: [{
            data: [820, 932, 901, 934, 1290, 1330, 1320],
            type: 'line',
            smooth: true
        }]
    };
    myChart.setOption(option); 


    // 3.商品浏览量趋势图 图表
    var myChart = echarts.init(document.getElementById("tubiao2")); 
    var option = {
        xAxis: {
            type: 'category',
            data: ['08-22', '08-23', '08-24', '08-25', '08-26', '08-27', '08-28']
        },
        color:['#00a2ff'],
        yAxis: {
            type: 'value'
        },
        series: [{
            data: [820, 932, 901, 934, 1290, 1330, 1320],
            type: 'line',
            smooth: true
        }]
    };
    myChart.setOption(option); 


    // 4.作品点赞趋势图 图表
    var myChart = echarts.init(document.getElementById("tubiao3")); 
    var option = {
        xAxis: {
            type: 'category',
            data: ['08-22', '08-23', '08-24', '08-25', '08-26', '08-27', '08-28']
        },
        color:['#00a2ff'],
        yAxis: {
            type: 'value'
        },
        series: [{
            data: [820, 932, 901, 934, 1290, 1330, 1320],
            type: 'line',
            smooth: true
        }]
    };
    myChart.setOption(option);
  </script>

  <?php if($type == 'music'): ?>
    <script>
      // 2.使用人数行业分布 图表
      var myChart = echarts.init(document.getElementById("tubiao11")); 
      option = {
          xAxis: {
              type: 'category',
              data: ['1', '3G', '3GS', '4', '4S', '4SG', '5']
          },
          color:['#3c88ba'],
          yAxis: {
              type: 'value'
          },
          series: [{
              data: [120, 200, 150, 80, 70, 110, 130],
              type: 'bar'
          }]
      };
      myChart.setOption(option); 

      // 3.每日新增人数趋势图 图表
      var myChart = echarts.init(document.getElementById("tubiao12")); 
      var option = {
          xAxis: {
              type: 'category',
              boundaryGap: false,
              data: <?php echo $mtrend['date']; ?>
          },
          color:['#b3def1'],
          yAxis: {
              type: 'value'
          },
          series: [{
              data: <?php echo $mtrend['usercount']; ?>,
              type: 'line',
              areaStyle: {}
          }]
      };
      myChart.setOption(option); 
    </script>
  <?php endif; ?>
  <script>
    $(".changetab").click(function(){
      $('.changetab').removeClass('checked-bottom');
      $(this).addClass('checked-bottom');
      $(".checkchangetab").hide();
      $("#"+$(this).attr('data-value')).show();
    });
  </script>
</body>
</html>