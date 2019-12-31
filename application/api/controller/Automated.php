<?php
namespace app\api\controller;

use app\api\controller\Base;
use app\api\controller\RemoveData;
use app\api\controller\Interfaces;

//models
use app\admin\model\Kol;
use app\admin\model\Video;
use app\admin\model\Music;
use app\admin\model\VideoTrend;

//自动执行

class Automated extends Base
{
	//构造方法
	public function __construct()
	{
		parent::__construct();

		// $this->ProcessingVideo();
		// $this->ProcessingKol();
		// $this->GetHotVideo();
	}

	//处理90天视频
	public function ProcessingVideo()
	{
		$VideoObj = new Video;
		$Interfaces = new Interfaces;
		$RemoveData = new RemoveData;

		//$page = date('H');

		$page = intval(((date('H')*60)+date('i'))/3);

		$number = ceil($VideoObj->count()/480);

		$VideoData = $VideoObj->field('video_id,video_reserved,create_time,video_number')->page($page,$number)->select();

		foreach ($VideoData as $key => $value) {

			$awhere = $value['video_reserved'] == 0 && $value['create_time'] < time()-7776000 ? 1 : 0;
			$bwhere = $value['video_reserved'] == 1 && $value['create_time'] < time()-15552000 ? 1 : 0;

			$awhere || $bwhere ? $RemoveData->RemoveVideo($value['video_id']) : $Interfaces->SaveVideoTrend($value['video_id'],$value['video_number']);
		}
	}

	//更新红人趋势
	public function ProcessingKol()
	{
		$Kol = new Kol;
		$Interfaces = new Interfaces;

		$page = date('H');

		$number = ceil($Kol->count()/24);

		$KolData = $Kol->field('kol_id,kol_uid')->page($page,$number)->select();

		foreach ($KolData as $key => $value) {
			$Interfaces->SaveKolTrend($value['kol_id'],$value['kol_uid']);
		}
	}

	//更新音乐趋势
	public function ProcessingMusic()
	{
		$Music = new Music;
		$Interfaces = new Interfaces;

		$number = ceil($Music->count()/24);

		$MusicData = $Music->field('music_id,music_number')->page($page,$number)->select();

		foreach ($KolData as $key => $value) {
			$Interfaces->SaveMusicTrend($value['music_id'],$value['music_number']);
		}
	}


	//生成KOL舆情信息
	public function GetKolPublicInfo()
	{
        $data = db('kol')
            -> alias('k')
            -> field('k.*,p.public_id')
            -> join('publicopinion p','k.kol_uid = p.public_key','left')
            -> select();

         $obj = new Interfaces;

        foreach ($data as $key => $value) {
            if($value['public_id'] == null){
                $obj->GetKolFansInfo($value['kol_uid']);
            }
        }
	}


	// //获取最热视频
	// public function GetHotVideo()
	// {
	// 	$Interfaces = new Interfaces;
	// 	$Interfaces->GetHotVideo('insert');
	// }

	// //获取热门音乐
	// public function GetHotMusic()
	// {
	// 	$Interfaces = new Interfaces;
	// 	$Interfaces->GetHotMusic('insert');
	// }
		// }
}