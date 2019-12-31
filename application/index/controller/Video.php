<?php
namespace app\index\controller;

use app\index\controller\LoginBase;

use think\Session;
use app\api\controller\GetData;
use app\api\controller\Automated;


class Video extends LoginBase
{
	private $GetData;

	public function __construct()
	{
		parent::__construct();
		$this->GetData = new GetData;
		//分配分类
		$this->assign('sort',$this->GetData->GetVideoSort());
		//分配规格
		$this->assign('specification',$this->GetData->GetSpecification('all'));
	}


	public function index()
	{
        // $obj = new Automated;
        // $obj->ProcessingMusic();
        // die;
		//筛选：分类、关键词、点赞数、视频时长、关联商品、男女比例、主要年龄、主要地区、发布时间、页数
		//排序：指数排序、点赞排序、评论排序、转发排序
		$condition = array(
			'sort'		=> 0,
			'keyword'	=> '',
			'like'		=> 0,
			'duration'	=> 0,
			'is_goods'	=> 0,
			'sex'		=> 0,
			'age'		=> 0,
			'province'	=> 0,
			'city'		=> 0,
			'createtime'=> 1,
			'page'		=> 1,
			'order'		=> 'vt_hot desc',
		);

		$where = array();

        $sort = input('param.sort');
        if($sort && $sort !== 0){
        	$condition['sort'] = $sort;
            $where['v.video_sort'] = $sort;
        }

        $keyword = input('param.keyword');
		if($keyword && $keyword != ''){
			$condition['keyword'] = $keyword;
			$where['v.video_desc'] = ['like',"%" . $keyword . "%"];
		}

        $like = input('param.like');
        if($like && $like !== 0){
        	$condition['like'] = $like;
            $where['vt.vt_like'] = array('between',$like);
        }

        $duration = input('param.duration');
        if($duration && $duration !== 0){
        	$condition['duration'] = $duration;
            $where['v.video_duration'] = array('between',$duration);
        }

        $is_goods = input('param.is_goods');
        if($is_goods && $is_goods == 'on'){
        	$condition['is_goods'] = $is_goods;
            $where['v.video_goods'] = array('neq',0);
        }

        //性别、年龄、地区
        $createtime = input('param.createtime');
        if($createtime && $createtime !== 0){
            $time = $createtime < 1 ? time() - $createtime*86400 : strtotime("-$createtime day");
        	$condition['createtime'] = $createtime;
        	$where['v.create_time'] = array('between',[(int)$time,time()]);
        }

        $page = input('param.page');
        if($page && $page !== 1) $condition['page'] = $page;

        $order = input('param.order');
        if($order && $order !== 'v.vt_hot desc') $condition['order'] = $order;

        $count 		= $this->GetData->GetVideoCount($where);
        $allpage 	= intval(ceil($count / 50));
        $data 		= $this->GetData->GetVideoList($where,$condition['page'],100,$condition['order']);

        $this->assign('condition',$condition);
        $this->assign('videos',$data);
		return view();
	}

    //视频详情
    public function show($vid=0,$type='video')
    {
        //视频信息
        $video = $this->GetData->GetVideoInfo($vid);
        //视频作者信息
        $author = $this->GetData->GetKolInfo($video['video_apiuid'],'kol_uid');
        //作者最新趋势
        $atrend = $this->GetData->GetKolOneTrend($author['kol_id']);


        if($type == 'video'){
            //视频最新趋势
            $trend = $this->GetData->GetVideoOneTrend($vid,true);
            //评论信息
            $comment = $this->GetData->GetVideoComment($vid);
            //趋势变化信息
            $inctrend = $this->GetData->GetVideoIncData($vid,30);
            //视频近期趋势
            $trendlist = $this->GetData->GetChangeTrend($vid,'video');

            $this->assign('trend',$trend);
            $this->assign('comment',$comment);
            $this->assign('inctrend',$inctrend);
            $this->assign('trendlist',$trendlist);

        }elseif($type == 'goods'){
            //商品信息
            $goods = $video['video_goods'] != 0 ? $this->GetData->GetGoodsInfo($video['video_goods']) : array();
            $this->assign('goods',$goods);
        }else{
            //视频音乐信息
            $music = $this->GetData->GetMusicInfo($video['video_music'],'music_number');
            $mtrend = $this->GetData->GetChangeTrend($music['music_id'],'music');
            $videos = $this->GetData->GetVideoList(array('video_music'=>$video['video_music']),1,20);

            $this->assign('videos',$videos);
            $this->assign('mtrend',$mtrend);
            $this->assign('music',$music);
        }


        $this->assign('type',$type);
        $this->assign('video',$video);
        $this->assign('author',$author);
        $this->assign('atrend',$atrend);
        
        
        return view();
    }
}
