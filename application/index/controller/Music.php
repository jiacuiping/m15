<?php
namespace app\index\controller;

use think\Controller;
use app\api\controller\GetData;


class Music extends LoginBase
{
	private $GetData;

	public function __construct()
	{
		parent::__construct();
		$this->GetData = new GetData;
		$this->assign('specification',$this->GetData->GetSpecification('all'));
	}

	//音乐列表
    public function index()
    {
		//筛选：关键词、发布时间、页数
		$condition = array(
			'keyword'	=> '',
			'createtime'=> 1,
			'page'		=> 1,
		);

		$where = array();

        $keyword = input('param.keyword');
		if($keyword && $keyword != ''){
			$condition['keyword'] = $keyword;
			$where['music_title'] = ['like',"%" . $keyword . "%"];
		}

        $createtime = input('param.createtime');
        if($createtime && $createtime !== 0){
            $time = $createtime < 1 ? time() - $createtime*86400 : strtotime("-$createtime day");
        	$condition['createtime'] = $createtime;
        	$where['create_time'] = array('between',[(int)$time,time()]);
        }

        $page = input('param.page');
        if($page && $page !== 1) $condition['page'] = $page;

        $count 		= $this->GetData->GetMusicCount($where);
        $allpage 	= intval(ceil($count / 50));
        $data 		= $this->GetData->GetMusicList($where,$condition['page'],50);

        $this->assign('condition',$condition);
        $this->assign('lasttime',date('Y-m-d'));
        $this->assign('music',$data);
        $this->assign('count',$count);
		return view();
    }


    //音乐详情
    public function info($mid=0)
    {
        $music = $this->GetData->GetMusicInfo($mid);
        $trend = $this->GetData->GetChangeTrend($mid,'music');
        $videos = $this->GetData->GetVideoList(array('video_music'=>$music['music_number']),1,20);

        $this->assign('videos',$videos);
        $this->assign('trend',$trend);
        $this->assign('music',$music);
        return view();
    }
}
