<?php
namespace app\api\controller;

use app\api\controller\Base;
use app\api\controller\Interfaces;

//models
use app\admin\model\Kol;
use app\admin\model\Video;
use app\admin\model\Music;
use app\admin\model\VideoTrend;
use app\admin\model\VideoComment;
use app\admin\model\Publicopinion;

//自动执行

class RemoveData extends Base
{
	//构造方法
	public function __construct()
	{
		parent::__construct();
	}

	//处理90天视频
	public function RemoveVideo($vid)
	{
		$Video = new Video;
		$VideoTrend = new VideoTrend;
		$VideoComment = new VideoComment;
		$Publicopinion = new Publicopinion;

		$vinfo = $Video->GetOneDataById($vid);

		//删除趋势
		$videoTrendResult = $VideoTrend->where(array('vt_video_id'=>$vinfo['video_id'],'vt_video_number'=>$vinfo['video_number']))->delete();
		//删除评论
		$videoCommentResult = $VideoComment->where('comm_video',$vinfo['video_id'])->delete();
		//删除视频舆情
		$VideoPublic = $Publicopinion->where(array('public_key'=>$vinfo['video_number'],'public_type'=>'video'))->delete();
		//删除视频
		$VideoResult = $Video->DeleteData($vid);
		
		return true;
	}
}