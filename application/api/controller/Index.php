<?php
namespace app\api\controller;

use app\api\controller\Base;

//指数计算方法

class Index extends Base
{
	//视频热度计算函数
	public function VideoHot($data)
	{
		//点赞得分
		$likeScore = round($data['vt_like'] / 14000,2);
		$likeScore = $likeScore > 2500 ? 2500 : $likeScore;
		//转发得分
		$repostsScore = round($data['vt_reposts'] / 75,2);
		$repostsScore = $repostsScore > 4000 ? 4000 : $repostsScore;
		//评论得分
		$commentScore = round($data['vt_comment'] / 120,2);
		$commentScore = $commentScore > 2500 ? 2500 : $commentScore;
		//下载得分
		$downloadScore = round($data['vt_download'] / 400,2);
		$downloadScore = $downloadScore > 1000 ? 1000 : $downloadScore;
		return $likeScore + $repostsScore + $commentScore + $downloadScore;
	}

	//用户热度计算函数
	public function UserHot($data)
	{
		//作品数得分
		$worksScore = round($data['kt_videocount'] / 2,2);
		$worksScore = $worksScore > 500 ? 500 : $worksScore;
		//粉丝数得分
		$fansScore = round($data['kt_fans'] / 10000,2);
		$fansScore = $fansScore > 6000 ? 6000 : $fansScore;
		//分享总数得分
		$shareScore = round($data['kt_share'] / 33333,2);
		$shareScore = $shareScore > 600 ? 600 : $shareScore;
		//点赞总数得分
		$likeScore = round($data['kt_like'] / 33333,2);
		$likeScore = $likeScore > 300 ? 300 : $likeScore;
		//评论总数得分
		$commScore = round($data['kt_comments'] / 33333,2);
		$commScore = $commScore > 200 ? 200 : $commScore;
		//下载总数得分
		$downloadScore = round($data['kt_comments'] / 33333,2);
		$downloadScore = $downloadScore > 100 ? 100 : $downloadScore;
		// //成长指数分？？？
		// $frequency = round($data['kt_videocount'] / 0.06,2);
		// $frequency = $frequency > 500 ? 500 : $frequency;
		$growth = 0;
		//视频平均价值
		$videohotScore = round($data['kt_video_hot'] / 16,2);
		$videohotScore = $videohotScore > 600 ? 600 : $videohotScore;
		//发布频率分？？？
		$frequency = round(30 / 0.06,2);
		$frequency = $frequency > 500 ? 500 : $frequency;

		return $worksScore + $fansScore + $shareScore + $likeScore + $commScore + $videohotScore + $frequency;
	}
}