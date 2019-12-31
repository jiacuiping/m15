<?php
namespace app\index\controller;

use think\Controller;
use app\api\controller\GetData;
use app\api\controller\Interfaces;


class Topic extends LoginBase
{
	private $GetData;

	public function __construct()
	{
		parent::__construct();
		$this->GetData = new GetData;
		$this->assign('specification',$this->GetData->GetSpecification('all'));
	}

	//话题列表
    public function index()
    {
        //$Interfaces = new Interfaces;

        //筛选：关键词、发布时间、页数
        $condition = array(
            'keyword'   => '',
            'createtime'=> 1,
            'page'      => 1,
        );

        $where = array();

        $keyword = input('param.keyword');
        if($keyword && $keyword != ''){
            $condition['keyword'] = $keyword;
            $where['topic_title'] = ['like',"%" . $keyword . "%"];
        }

        $createtime = input('param.createtime');
        if($createtime && $createtime !== 0){
            $time = $createtime < 1 ? time() - $createtime*86400 : strtotime("-$createtime day");
            $condition['createtime'] = $createtime;
            $where['create_time'] = array('between',[(int)$time,time()]);
        }

        $page = input('param.page');
        if($page && $page !== 1) $condition['page'] = $page;

        $count      = $this->GetData->GetTopicCount($where);
        $allpage    = intval(ceil($count / 50));
        $data       = $this->GetData->GetTopicList($where,$condition['page'],50);

        $this->assign('condition',$condition);
        $this->assign('lasttime',date('Y-m-d'));
        $this->assign('topic',$data);
        $this->assign('count',$count);
        return view();
    }


    //话题详情
    public function info($tid=0)
    {
        $topic = $this->GetData->GetTopicInfo($tid);
        $trend = $this->GetData->GetChangeTrend($tid,'topic');
        $videos = $this->GetData->GetVideoList(array('video_topic'=>$topic['topic_number']),1,20);

        $this->assign('topic',$topic);
        $this->assign('trend',$trend);
        $this->assign('videos',$videos);
        return view();
    }
}
