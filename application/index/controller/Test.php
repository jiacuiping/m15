<?php
namespace app\index\controller;

use app\index\controller\LoginBase;

use think\Session;

//models
use app\admin\model\Kol;
use app\admin\model\User;
use app\admin\model\Video;
use app\admin\model\Music;
use app\admin\model\Topic;
use app\admin\model\KolTrend;
use app\admin\model\VideoTrend;
use app\admin\model\MusicTrend;
use app\admin\model\TopicTrend;
use app\admin\model\VideoComment;


//commons
use app\api\controller\Index;
use app\api\controller\GetData;
use app\api\controller\Automated;
use app\api\controller\Interfaces;
use app\api\controller\RemoveData;


class Test extends LoginBase
{
	private $GetData;
    private $key = '08F0EDE0D84F0683E4E1E5ECB1F0EAE8';
    private $url = 'http://api01.6bqb.com/';

	public function __construct()
	{
		parent::__construct();
		$this->GetData = new GetData;
	}

    //测试方法
    public function test()
    {
        $this->SaveKolTrend(107,76579979873,1);
        $this->SaveKolTrend(108,1525748808822476,1);
        die;
    }


    //更新红人趋势
    public function SaveKolTrend($kid,$kolNumber,$isMonitoring=0)
    {
        $obj = new KolTrend;

        if($isMonitoring == 0){
            $Trend = $obj->GetOneData(array('kt_kol_id'=>$kid,'kt_batec'=>date('Y-m-d'),'kt_monitoring'=>0));
            $isInsert = empty($Trend) ? true : false;
        }else
            $isInsert = true;

        if($isInsert){
            $url = 'http://api01.6bqb.com/douyin/user';
            $result = $this->curl($url,array('uid'=>$kolNumber));

            if($result['code'] == 1){
                $data = $result['data']['data'];

                $statistics        = array(
                    'kt_kol_id'     => $kid,
                    'kt_kol_uid'    => $data['uid'],
                    'kt_batec'      => date('Y-m-d'),
                    'kt_interact_value' => $data['is_star'] ? 1 : 0,
                    'kt_dynamic'    => $data['dongtai_count'],
                    'kt_fans'       => $data['followers_detail'][0]['fans_count'],
                    'kt_videocount' => $data['aweme_count'],
                    'kt_focus'      => $data['following_count'],
                    'kt_likes'      => $data['favoriting_count'],
                    'kt_like'       => $data['total_favorited'],
                    'kt_monitoring' => $isMonitoring,
                    'kt_mean_like'  => floor($data['total_favorited']/$data['aweme_count']),
                );

                $Index = new Index;
                $GetData = new GetData;

                //总数据及平均数据
                $datas = $GetData->GetKolDataCount($kolNumber);

                if(!empty($datas)){
                    $statistics['kt_mean_comment'] = floor($datas['comments'] / $datas['count']);
                    $statistics['kt_mean_share'] = floor($datas['reposts'] / $datas['count']);
                    $statistics['kt_mean_down'] = floor($datas['download'] / $datas['count']);
                    $statistics['kt_comments'] = floor($statistics['kt_mean_comment'] * $statistics['kt_videocount']);
                    $statistics['kt_share'] = floor($statistics['kt_mean_share'] * $statistics['kt_videocount']);
                    $statistics['kt_download'] = floor($statistics['kt_mean_down'] * $statistics['kt_videocount']);
                    $statistics['kt_bighot'] = floor($datas['bighot'] * floor($statistics['kt_videocount']/$datas['count']));
                    $statistics['kt_midhot'] = floor($datas['midhot'] * floor($statistics['kt_videocount']/$datas['count']));
                    $statistics['kt_samhot'] = floor($datas['samhot'] * floor($statistics['kt_videocount']/$datas['count']));
                    $statistics['kt_video_hot'] = floor($datas['hot'] / $datas['count']);
                }else
                    $statistics['kt_share'] = $statistics['kt_comments'] = $statistics['kt_download'] = $statistics['kt_video_hot'] = 0;

                $kTrend = db('kol_trend')->where(array('kt_kol_id'=>$kid,'kt_monitoring'=>$isMonitoring))->order('kt_time desc')->find();

                if(!empty($kTrend)){
                    $statistics['kt_inc_like'] = $statistics['kt_like'] - $kTrend['kt_like'];
                    $statistics['kt_inc_comment'] = $statistics['kt_comments'] - $kTrend['kt_comments'];
                    $statistics['kt_inc_share'] = $statistics['kt_share'] - $kTrend['kt_share'];
                    $statistics['kt_inc_down'] = $statistics['kt_download'] - $kTrend['kt_download'];
                    $statistics['kt_inc_fans'] = $statistics['kt_fans'] - $kTrend['kt_fans'];
                    if($statistics['kt_videocount'] > $kTrend['kt_videocount'])
                        $this->InsertUserWorks($kolNumber,$statistics['kt_videocount']-$kTrend['kt_videocount']);
                }else
                    $statistics['kt_inc_like'] = $statistics['kt_inc_comment'] = $statistics['kt_inc_share'] = $statistics['kt_inc_down'] = $statistics['kt_inc_fans'] = 0;

                $statistics['kt_hot'] = $Index->UserHot($statistics);

                dump($statistics);die;

                $obj->CreateData($statistics);
            }
        }
        return true;
    }

    /**
     * 新添加用户添加作品
     * @param uid   true    int     用户uid
     * @param page  false   int     页数
     * @return 返回用户视频作品列表
     */
    public function InsertUserWorks($uid,$number=10,$page=0)
    {
        $kol = new Kol;
        $VideoObj = new Video;

        $i = 0;

        $url = $this->url.'douyin/user/feed';

        $result = $this->curl($url,array('uid'=>$uid,'page'=>$page));

        $sort = $kol->GetField(array('kol_uid'=>$uid),'kol_sort');

        if($result['code'] == 1){
            foreach ($result['data']['data'] as $key => $value) {

                if($value['is_top'] == 1){
                    $number++;
                    $i++;
                    continue;
                }

                if($i < $number){
                    $video = array(
                        'video_platform'    => 1,
                        'video_apiuid'      => $value['author_user_id'],
                        'video_username'    => $value['author']['nickname'],
                        'video_number'      => $value['aweme_id'],
                        'video_sort'        => $sort,
                        'video_title'       => '',
                        'video_cover'       => $value['video']['cover']['url_list'][0],
                        'video_sharetitle'  => $value['share_info']['share_title'],
                        'video_url'         => $value['share_info']['share_url'],
                        'video_goods'       => $value['status']['with_goods'] ? 1 : 0,
                        'video_duration'    => $value['duration'],
                        'video_music'       => $value['music']['mid'],
                        'video_desc'        => $value['desc'],
                        'video_status'      => $value['status']['is_delete'] ? 1 : 0,
                        'create_time'       => $value['create_time'],
                        'update_time'       => time(),
                        'statistics'        => array(
                            'vt_video_id'       => $value['status']['aweme_id'],
                            'vt_video_number'   => $value['status']['aweme_id'],
                            'vt_batec'          => date('Y-m-d'),
                            'vt_like'           => $value['statistics']['digg_count'],
                            'vt_reposts'        => $value['statistics']['share_count'],
                            'vt_comment'        => $value['statistics']['comment_count'],
                            'vt_download'       => $value['statistics']['download_count'],
                            'vt_play'           => $value['statistics']['play_count'],
                            'vt_time'           => time(),
                        ),
                    );

                    $id = $this->CreateData($video,'video');

                    if($id){
                        $statistics = $video['statistics'];
                        $statistics['vt_video_id'] = $id;
                        $this->CreateData($statistics,'videoTrend');
                    }
                    $i++;
                }else
                    break;
            }
        }
    }

    public function GetAllVideo()
    {
        $kol = new Kol;
        $VideoObj = new Video;

        $list = db('allvideo')->where('status',0)->find();

        if($list){

            $list['countvideo'] = db('kol_trend')->where(array('kt_kol_uid'=>$list['kol_uid']))->value('kt_videocount');
            $list['insertvideo'] = db('video')->where(array('video_apiuid'=>$list['kol_uid']))->count();

            db('allvideo')->where('kol_uid',$list['kol_uid'])->update($list);

            if($list['insertvideo'] < $list['countvideo']){

                $url = $this->url.'douyin/user/feed';

                $result = $this->curl($url,array('uid'=>$list['kol_uid'],'page'=>$list['newpage']));

                if($result['code'] == 1){

                    db('allvideo')->where('kol_uid',$list['kol_uid'])->update(['newpage'=>$result['data']['page']]);

                    $sort = $kol->GetField(array('kol_uid'=>$list['kol_uid']),'kol_sort');

                    $this->addVideo($result['data']['data'],$sort);

                }else
                    db('allvideo')->where('kol_uid',$list['kol_uid'])->update(['status'=>-1]);
            }else
                db('allvideo')->where('kol_uid',$list['kol_uid'])->update(['status'=>1]);
        }
    }




    /**
     * 获取用户详细信息
     * @param uid       true    int     用户uid
     * @return 单个用户详细信息
     */
    public function GetUserInfo($uid,$type="insert")
    {
        $Kol = new Kol;

        $url = $this->url.'douyin/user';

        $result = $this->curl($url,array('uid'=>$uid));

        if($result['code'] == 1){

            $data = $result['data']['data'];

            $user = array(
                'kol_platform'      => 1,
                'kol_uid'           => $data['uid'],
                'kol_number'        => $data['unique_id'] == '' ? $data['short_id'] : $data['unique_id'],
                'kol_nickname'      => $data['nickname'],
                'kol_avatar'        => $data['avatar_168x168']['url_list'][0],
                'kol_qrcode'        => $data['share_info']['share_qrcode_url']['url_list'][0],
                'kol_school'        => isset($data['school_name']) ? $data['school_name'] : '',
                'kol_sex'           => $data['gender'],
                'kol_is_luban'      => $data['with_luban_entry'] ? 1 : 0,
                'kol_is_goods'      => $data['with_fusion_shop_entry'],
                'kol_is_star'       => $data['is_star'] ? 1 : 0,
                'kol_is_gov'        => $data['is_gov_media_vip'],
                'kol_weibo'         => $data['weibo_url'],
                'kol_signature'     => $data['signature'],
                'kol_verifyname'    => $data['enterprise_verify_reason'],
                'kol_achievement'   => $data['custom_verify'],
                'kol_constellation' => $data['constellation'],
                'kol_birthdayY'     => $data['birthday'],
                'kol_age'           => date('Y') - $data['birthday'],
                'kol_countries'     => $data['country'],
                'kol_cityname'      => $data['city'],
                'kol_area'          => $data['district'],
                'statistics'        => array(
                    'kt_kol_uid'    => $data['uid'],
                    'kt_batec'      => date('Y-m-d'),
                    'kt_interact_value' => $data['is_star'] ? 1 : 0,
                    'kt_dynamic'    => $data['dongtai_count'],
                    'kt_fans'       => $data['followers_detail'][0]['fans_count'],
                    'kt_videocount' => $data['aweme_count'],
                    'kt_focus'      => $data['following_count'],
                    'kt_likes'      => $data['favoriting_count'],
                    'kt_like'       => $data['total_favorited'],
                    'kt_mean_like'  => floor($data['total_favorited']/$data['aweme_count']),
                ),
            );

            $user['kol_sort'] = $this->GetUserSort($user['kol_nickname'],$user['kol_uid']);

            if($data['province'] != '')
                $user['kol_province'] = db('area')->where(array('pid'=>0,'shortname'=>$data['province']))->value('id');
            elseif($data['city'] != '')
                $user['kol_province'] = db('area')->where(array('pid'=>array('neq',0),'shortname'=>$data['city']))->value('pid');
            else
                $user['kol_province'] = 0;

            dump($user);die;

            if($type == 'insert'){

                $id = $this->CreateData($user,'kol');

                if($id){
                    $statistics = $user['statistics'];
                    $statistics['kt_kol_id'] = $id;
                    $idd = $this->CreateData($statistics,'kolTrend');
                }
            }
            return $type == 'insert' ? true : $user;
        }
    }


    //获取用户分类
    public function GetUserSort($name,$uid)
    {
        $url = $this->url.'douyin/plus/user/search';

        $result = $this->curl($url,array('keyword'=>$name));

        if($result['code'] == 1){

            $info = $result['data']['data'][0];

            $sid = $info['user_info']['uid'] == $uid ? db('video_sort')->where('sort_name',$info['tag'])->value('sort_id') : 0;

            return $sid == '' ? 0 : $sid;
        }else
            return 0;
    }



    /**
     * 新添加用户添加作品
     * @param uid   true    int     用户uid
     * @param page  false   int     页数
     * @return 返回用户视频作品列表
     */
    public function addVideo($data,$sort)
    {
        foreach ($data as $key => $value) {
            $video = db('video')->where('video_number',$value['aweme_id'])->find();
            if(!$video){
                $video = array(
                    'video_platform'    => 1,
                    'video_apiuid'      => $value['author_user_id'],
                    'video_username'    => $value['author']['nickname'],
                    'video_number'      => $value['aweme_id'],
                    'video_sort'        => $sort,
                    'video_title'       => '',
                    'video_cover'       => $value['video']['cover']['url_list'][0],
                    'video_sharetitle'  => $value['share_info']['share_title'],
                    'video_url'         => $value['share_info']['share_url'],
                    'video_goods'       => $value['status']['with_goods'] ? 1 : 0,
                    'video_duration'    => $value['duration'],
                    'video_music'       => $value['music']['mid'],
                    'video_desc'        => $value['desc'],
                    'video_status'      => $value['status']['is_delete'] ? 1 : 0,
                    'create_time'       => $value['create_time'],
                    'update_time'       => time(),
                    'statistics'        => array(
                        'vt_video_id'       => $value['status']['aweme_id'],
                        'vt_video_number'   => $value['status']['aweme_id'],
                        'vt_batec'          => date('Y-m-d'),
                        'vt_like'           => $value['statistics']['digg_count'],
                        'vt_reposts'        => $value['statistics']['share_count'],
                        'vt_comment'        => $value['statistics']['comment_count'],
                        'vt_download'       => $value['statistics']['download_count'],
                        'vt_play'           => $value['statistics']['play_count'],
                        'vt_time'           => time(),
                    ),
                );
                $id = $this->CreateData($video,'video');
                if($id){
                    $statistics = $video['statistics'];
                    $statistics['vt_video_id'] = $id;
                    $idd = $this->CreateData($statistics,'videoTrend');
                }
            }
        }
    }



    /**
     * 获取音乐详情
     * @param musicId   true    int     音乐ID
     * @return 音乐详情
     */
    public function GetMusicInfo($musicId)
    {

        $url = $this->url.'douyin/music/detail';

        $result = $this->curl($url,array('musicId'=>$musicId));

        if($result['code'] == 1){

            $data = $result['data']['data'];

            $music = array(
                'music_platform'    => 1,
                'music_apiuid'      => 0,
                'music_number'      => $data['mid'],
                'music_username'    => $data['author'],
                'music_title'       => $data['title'],
                'music_cover'       => $data['cover_large']['url_list'][0],
                'music_url'         => $data['play_url']['url_list'][0],
                'music_duration'    => $data['duration'],
                'music_usercount'   => $data['user_count'],
                'create_time'       => time(),
                'update_time'       => time(),
                'statistics'        => array(
                    'mt_music_number'   => $data['mid'],
                    'mt_rank'           => 0,
                    'mt_batch'          => date('Y-m-d'),
                    'mt_usercount'      => $data['user_count'],
                    'mt_hot'            => 0,
                    'mt_time'           => time(),
                )
            );

            //添加音乐信息
            $id = $this->CreateData($music,'music');
            if($id){
                //添加音乐趋势
                $statistics = $music['statistics'];
                $statistics['mt_music_id'] = $id;
                $this->CreateData($statistics,'musicTrend');
            }
        }

    }


    //生成二维码
    public function CreateQrcode($url,$vid){

        vendor('phpqrcode');//引入类库
        $errorCorrectionLevel = 'L';  //容错级别
        $matrixPointSize = 5;      //生成图片大小
        //生成二维码图片
        //设置二维码文件名
        $filename = '/uploads/qrcode/'.$vid.'.png';
        //生成二维码
        \QRcode::png($url,$filename , $errorCorrectionLevel, $matrixPointSize, 2);

        return $filename;
    }


    /**
     * 获取视频评论
     * @param page  false   int     页数
     * @return 话题列表
     */
    public function GetVideoComment($vid=0,$page=1)
    {
        $url = $this->url.'douyin/comment';
        $result = $this->curl($url,array('itemId'=>$vid,'page'=>$page));

        if($result['code'] == 1){

            $obj = new GetData;

            $video = $obj->GetVideoInfo($vid,'video_number');

            $data = $result['data']['data'];

            foreach ($data as $key => $value) {
                $comment = array(
                    'comm_video'    => $video['video_id'],
                    'comm_appid'    => $value['cid'],
                    'comm_text'     => $value['text'],
                    'comm_like'     => $value['digg_count'],
                    'comm_reply'    => $value['reply_comment_total'],
                    'comm_reply_id' => $value['reply_id'],
                    'comm_nickname' => $value['user']['nickname'],
                    'comm_avatar'   => $value['user']['avatar_thumb']['url_list'][0],
                    'comm_time'     => $value['create_time'],
                );

                $res = $this->CreateData($comment,'videoComment');
            }

            $CommentObj = new VideoComment;
            $commentcount = $CommentObj->GetCount(array('comm_video'=>$video['video_id']));
            if($commentcount < 20 && $result['data']['hasNext'])
                $this->GetVideoComment($vid,$page+1);   //如果数据库该视频的评论数小于500并且有下一页则继续查询
        }
    }












    /**
     * 使用fsocketopen()方式发送异步请求,put方式
     */
    public function syncRequest($url, $param = array(),$timeout = 10)
    {
        $urlParmas = parse_url($url);
        $host = $urlParmas['host'];
        $path = $urlParmas['path'];
        $scheme = $urlParmas['scheme'];
        $port = isset($urlParmas['port'])? $urlParmas['port'] :80;
        $errno = 0;
        $errstr = '';
        if($scheme == 'https') {
            $host = 'ssl://'.$host;
        }
        $fp = fsockopen($host, $port, $errno, $errstr, $timeout);
        stream_set_blocking($fp,true);//开启了手册上说的非阻塞模式
        $query = isset($param)? http_build_query($param) : '';
        //如果传递参数在body中,则使用
        if(!empty($postData)) $query = $postData;
        $out = "PUT ".$path." HTTP/1.1\r\n";
        $out .= "host:".$host."\r\n";
        $out .= "content-length:".strlen($query)."\r\n";
        //传递参数为url=?p1=1&p2=2的方式,使用application/x-www-form-urlencoded方式
        //$out .= "content-type:application/x-www-form-urlencoded\r\n";
        //传递参数为json字符串的方式,并且在请求体的body中,使用application/json
        $out .= "content-type:application/json\r\n";
        $out .= "connection:close\r\n\r\n";
        $out .= $query;

        fputs($fp, $out);
        //usleep(1000); // 这一句也是关键，如果没有这延时，可能在nginx服务器上就无法执行成功
        $result = "";
        /*
        //获取返回结果, 如果不循环接收返回值,请求发出后直接关闭连接, 则为异步请求
        while(!feof($fp)) {
            $result .= fgets($fp, 1024);
        }*/
        //print_r($result);
        fclose($fp);
    }



    public function GetUserInfo1($uid,$id)
    {
        $url = 'http://api01.6bqb.com/douyin/user';

        $result = $this->curl($url,array('uid'=>$uid));

        if($result['code'] == 1){

            $data = $result['data']['data'];

            $user = array(
                'statistics'        => array(
                    'kt_kol_uid'    => $data['uid'],
                    'kt_batec'      => date('Y-m-d'),
                    'kt_interact_value' => $data['is_star'] ? 1 : 0,
                    'kt_dynamic'    => $data['dongtai_count'],
                    'kt_fans'       => $data['followers_detail'][0]['fans_count'],
                    'kt_videocount' => $data['aweme_count'],
                    'kt_focus'      => $data['following_count'],
                    'kt_likes'      => $data['favoriting_count'],
                    'kt_like'       => $data['total_favorited'],
                    'kt_mean_like'  => floor($data['total_favorited']/$data['aweme_count']),
                ),
            );

            $statistics = $user['statistics'];
            $statistics['kt_kol_id'] = $id;
            $idd = $this->CreateData($statistics,'kolTrend');
        }
    }





    /**
     * 添加数据
     * @param data  false   array   要添加的数据
     * @param type  false   string  数据类型
     * @return 话题列表
     */
    public function CreateData($data,$type)
    {
        if($type == 'video'){

            //添加视频详情

            $obj = new Video;
            $Video = $obj->GetOneData(array('video_number'=>$data['video_number']));
            if(!$Video){
                $data['video_qrcode'] = $this->CreateQrcode($data['video_url'],$data['video_number']);
                $result = $obj->CreateData($data);
                $this->GetMusicInfo($data['video_music']);
                //新添加的视频 获取视频评论
                $this->GetVideoComment($data['video_number']);
                return $result['code'] == 1 ? $result['id'] : 0;
            }else
                return $Video['video_id'];
        }elseif($type == 'videoTrend'){

            //添加视频趋势

            $obj = new VideoTrend;
            $Trend = $obj->GetOneData(array('vt_video_number'=>$data['vt_video_number'],'vt_batec'=>$data['vt_batec'],'vt_monitoring'=>0));
            if(!$Trend){
                $Index = new Index;
                $data['vt_hot'] = $Index->VideoHot($data);
                $result = $obj->CreateData($data);
                return $result['code'] == 1 ? $result['id'] : 0;
            }else
                return $Trend['vt_id'];
        }elseif($type == 'videoComment'){

            //添加视频评论

            $obj = new VideoComment;

            $Comment = $obj->GetOneData(array('comm_appid'=>$data['comm_appid']));

            if(!$Comment){
                $result = $obj->CreateData($data);
                return $result['code'] == 1 ? $result['id'] : 0;
            }else
                return $Comment['comm_id'];

        }elseif($type == 'kol'){

            //添加红人详情

            $obj = new Kol;
            $kol = $obj->GetOneData(array('kol_uid'=>$data['kol_uid']));
            if(!$kol){
                $result = $obj->CreateData($data);
                //最新用户添加50条视频
                //$this->InsertUserWorks($data['kol_uid'],5);
                //添加用户舆情信息
                //$this->GetKolFansInfo($data['kol_uid']);
                return $result['code'] == 1 ? $result['id'] : 0;
            }else
                return $kol['kol_id'];
        }elseif($type == 'kolTrend'){

            //添加红人趋势信息

            $obj = new KolTrend;
            $Trend = $obj->GetOneData(array('kt_kol_uid'=>$data['kt_kol_uid'],'kt_batec'=>$data['kt_batec']));

            if(!$Trend){

                $Index = new Index;
                $GetData = new GetData;
                $VideoTrend = new VideoTrend;

                //总数据及平均数据
                $datas = $GetData->GetKolDataCount($data['kt_kol_uid']);

                if(!empty($datas)){
                    $data['kt_mean_comment'] = floor($datas['comments'] / $datas['count']);
                    $data['kt_mean_share'] = floor($datas['reposts'] / $datas['count']);
                    $data['kt_mean_down'] = floor($datas['download'] / $datas['count']);

                    $data['kt_comments'] = floor($data['kt_mean_comment'] * $data['kt_videocount']);
                    $data['kt_share'] = floor($data['kt_mean_share'] * $data['kt_videocount']);
                    $data['kt_download'] = floor($data['kt_mean_down'] * $data['kt_videocount']);

                    $data['kt_bighot'] = floor($datas['bighot'] * floor($data['kt_videocount']/$datas['count']));
                    $data['kt_midhot'] = floor($datas['midhot'] * floor($data['kt_videocount']/$datas['count']));
                    $data['kt_samhot'] = floor($datas['samhot'] * floor($data['kt_videocount']/$datas['count']));
                    $data['kt_video_hot'] = floor($datas['hot'] / $datas['count']);
                }else{
                    $data['kt_share'] = $data['kt_comments'] = $data['kt_download'] = $data['kt_video_hot'] = 0;
                }

                //新增数据
                $NewTrend = $GetData->GetKolOneTrend($data['kt_kol_id'],false);

                if(!empty($NewTrend)){
                    $data['kt_inc_like'] = $data['kt_like'] - $NewTrend['kt_like'];
                    $data['kt_inc_comment'] = $data['kt_comments'] - $NewTrend['kt_comments'];
                    $data['kt_inc_share'] = $data['kt_share'] - $NewTrend['kt_share'];
                    $data['kt_inc_down'] = $data['kt_download'] - $NewTrend['kt_download'];
                    $data['kt_inc_fans'] = $data['kt_fans'] - $NewTrend['kt_fans'];
                }else{
                    $data['kt_inc_like'] = $data['kt_like'];
                    $data['kt_inc_comment'] = $data['kt_comments'];
                    $data['kt_inc_share'] = $data['kt_share'];
                    $data['kt_inc_down'] = $data['kt_download'];
                    $data['kt_inc_fans'] = $data['kt_fans'];
                }
                
                //补全数据
                $data['kt_hot'] = $Index->UserHot($data);

                $result = $obj->CreateData($data);
                return $result['code'] == 1 ? $result['id'] : 0;
            }else
                return $Trend['kt_id'];
        }elseif($type == 'topic'){

            //添加话题信息

            $obj = new Topic;
            $topic = $obj->GetOneData(array('topic_number'=>$data['topic_number']));
            if(!$topic){
                $result = $obj->CreateData($data);
                return $result['code'] == 1 ? $result['id'] : 0;
            }else
                return $topic['topic_id'];
        }elseif($type == 'topicTrend'){

            //添加话题趋势

            $obj = new TopicTrend;
            $Trend = $obj->GetOneData(array('tt_topic_id'=>$data['tt_topic_id'],'tt_batch'=>$data['tt_batch']));
            if(!$Trend){
                $result = $obj->CreateData($data);
                return $result['code'] == 1 ? $result['id'] : 0;
            }else
                return $Trend['vt_id'];
        }elseif($type == 'music'){

            //添加音乐信息

            $obj = new Music;
            $music = $obj->GetOneData(array('music_number'=>$data['music_number']));
            if(!$music){
                $result = $obj->CreateData($data);
                return $result['code'] == 1 ? $result['id'] : 0;
            }else
                return $music['music_id'];
        }elseif($type == 'musicTrend'){

            //添加音乐趋势信息

            $GetData = new GetData;
            $obj = new MusicTrend;

            $trend = $GetData->GetMusicOneTrend($data['mt_music_number'],false);

            $data['mt_inc_count'] = empty($trend) ? $data['mt_usercount'] : $data['mt_usercount'] - $trend['mt_usercount'];

            $Trend = $obj->GetOneData(array('mt_music_id'=>$data['mt_music_id'],'mt_batch'=>$data['mt_batch']));
            if(!$Trend){
                $musicobj = new Music;
                $musicobj->where('music_id',$data['mt_music_id'])->update(['music_usercount'=>$data['mt_usercount']]);
                $result = $obj->CreateData($data);
                return $result['code'] == 1 ? $result['id'] : 0;
            }else
                return $Trend['mt_id'];
        }
    }


    // //获取粉丝数据
    // public function GetFansInfo()
    // {
    //     $url = $this->url.'douyin/user/follower';

    //     $data = array();

    //     for($i=0;$i<200;$i=$i+20){
    //         $page = db('config')->where('config_id',1)->value('config_page');
    //         $result = $this->curl($url,array('uid'=>76579979873,'page'=>$page));
    //         if($result['code'] == 1){
    //             db('config')->where('config_id',1)->update(['config_page'=>$result['data']['page']]);
    //             $data = array_merge($data,$result['data']['data']);
    //         }
    //     }

    //     foreach ($data as $key => $value) {
    //         if(!db('testfans')->where('fans_uid',$value['uid'])->find()){
    //             $oneinfo[] = array(
    //                 'fans_uid'      => $value['uid'],
    //                 'fans_age'      => date('Y') - $value['birthday'],
    //                 'fans_sex'      => $value['gender'],
    //                 'fans_time'     => time(),
    //             );
    //         }
    //     }

    //     db('testfans')->insertAll($oneinfo);
    // }



    // public function CountFansInfo()
    // {

    //     $data = db('testfans')->select();

    //     $count = count($data);

    //     $sex['default'] = $sex['men'] = $sex['women'] = $a = $b = $c = $d = $e = 0;

    //     foreach ($data as $key => $value) {

    //         if($value['fans_sex'] == 0)
    //             $sex['default']++;
    //         else
    //             $value['fans_sex'] == 1 ? $sex['men']++ : $sex['women']++;

    //         if($value['fans_age'] < 16)
    //             $a++;
    //         elseif($value['fans_age'] > 16 && $value['fans_age'] < 20)
    //             $b++;
    //         elseif($value['fans_age'] > 20 && $value['fans_age'] < 26)
    //             $c++;
    //         elseif($value['fans_age'] > 26 && $value['fans_age'] < 36)
    //             $d++;
    //         elseif($value['fans_age'] > 36 && $value['fans_age'] < 2000)
    //             $e++;
    //     }

    //     $sexInfo['未设置'] = round($sex['default']/$count*100,2);
    //     $sexInfo['男'] = round($sex['men']/$count*100,2);
    //     $sexInfo['女'] = round($sex['women']/$count*100,2);
    //     $ageInfo["'<16'"] = round($a/$count*100,2);
    //     $ageInfo["'16-20'"] = round($b/$count*100,2);
    //     $ageInfo["'20-26'"] = round($c/$count*100,2);
    //     $ageInfo["'26-36'"] = round($d/$count*100,2);
    //     $ageInfo["'>36'"] = round($e/$count*100,2);

    //     dump($ageInfo);
    //     echo "<hr>";
    //     dump($sexInfo);
    //     die;
    // }




    /**
     * 获取用户粉丝信息
     * @param uid   false   int     用户主键
     * @return 话题列表
     */
    public function GetKolFansInfo($uid,$page=0)
    {
        if(!db('publicopinion')->where(array('public_key'=>$uid,'public_type'=>'kol'))->find()){

            $url = $this->url.'douyin/user/follower';

            $page = 0;
            $data = array();

            for($i=0;$i<300;$i=$i+20){

                $result = $this->curl($url,array('uid'=>$uid,'page'=>$page));

                if($result['code'] == 1){
                    $page = $result['data']['page'];
                    $data = array_merge($data,$result['data']['data']);
                }
            }

            $sex['default'] = $sex['men'] = $sex['women'] = $a = $b = $c = $d = $e = 0;

            foreach ($data as $key => $value) {

                if($value['gender'] == 0)
                    $sex['default']++;
                else
                    $value['gender'] == 1 ? $sex['men']++ : $sex['women']++;

                $age = date('Y') - $value['birthday'];

                if($age < 16)
                    $a++;
                elseif($age > 16 && $age < 20)
                    $b++;
                elseif($age > 20 && $age < 26)
                    $c++;
                elseif($age > 26 && $age < 36)
                    $d++;
                elseif($age > 36)
                    $e++;
            }

            $sexInfo['未设置'] = round($sex['default']/300*100,2);
            $sexInfo['男'] = round($sex['men']/300*100,2);
            $sexInfo['女'] = round($sex['women']/300*100,2);
            $ageInfo["'<16'"] = round($a/300*100,2);
            $ageInfo["'16-20'"] = round($b/300*100,2);
            $ageInfo["'20-26'"] = round($c/300*100,2);
            $ageInfo["'26-36'"] = round($d/300*100,2);
            $ageInfo["'>36'"] = round($e/300*100,2);

            dump($sexInfo);
            echo "<hr/>";
            dump($ageInfo);
            die;

            $insert = array(
                'public_type'       => 'kol',
                'public_key'        => $uid,
                'public_age'        => json_encode($ageInfo),
                'public_sex'        => json_encode($sexInfo),
                'create_time'       => time()
            );

            dump($insert);die;

            db('publicopinion')->insert($insert);
        }
    }

    //请求方法
    public function curl($url,$data=array())
    {
        $data = array_merge(array('apikey'=>'08F0EDE0D84F0683E4E1E5ECB1F0EAE8'),$data);

        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL,$url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //执行命令
        $json = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        
        $result = json_decode($json,true);

        if($result['retcode'] != '0000'){
            $error = array(
                'error_url'     => $url,
                'error_data'    => json_encode($data),
                'error_date'    => date('Y-m-d H:i:s',time()),
                'error_code'    => $result['retcode'],
                'error_time'    => time()
            );
            db('error')->insert($error);
            return array('code'=>0);
        }else
            return array('code'=>1,'data'=>$result);
    }


}
