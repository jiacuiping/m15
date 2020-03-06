<?php
namespace app\api\controller;

use app\api\controller\Base;
use app\api\controller\RemoveData;
use app\api\controller\Interfaces;
use app\api\controller\DyInterfaces;

//models
use app\admin\model\Kol;
use app\admin\model\Video;
use app\admin\model\Music;
use app\admin\model\UserPublicopinionTime;

//自动执行

class Automated extends Base
{
	//构造方法
	public function __construct()
	{
		parent::__construct();
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

		$page = date('H');

		$number = ceil($Music->count()/24);

		$MusicData = $Music->field('music_id,music_number')->page($page,$number)->select();

		foreach ($KolData as $key => $value) {
			$Interfaces->SaveMusicTrend($value['music_id'],$value['music_number']);
		}
	}


	//更新商品趋势
	public function ProcessingGoods()
	{
		$Goods = new Goods;
		$Interfaces = new Interfaces;

		$page = date('H');

		$number = ceil($Goods->count()/24);

		$GoodsData = $Goods->field('goods_id,goods_number')->page($page,$number)->select();

		foreach ($GoodsData as $key => $value) {
			$Interfaces->SaveGoodsTrend($value['goods_id'],$value['goods_number']);
		}
	}


	//获取KOL舆情信息
	public function GetKolPublicInfo()
	{
		$DyInterfaces = new DyInterfaces;

		$UserPublicopinionTime = new UserPublicopinionTime;

		$data = $UserPublicopinionTime->GetDataList(array('time_status'=>0,'refresh_time'=>array('LT',time())));

		foreach ($data as $key => $value) {
			
			$result = $DyInterfaces->saveFansData($value['time_open_id']);

			if($result['code'] == 1){
				//如果执行成功，则续查
				$Time = array(
					'time_kol'		=> $value['time_kol'],
					'time_open_id'	=> $value['time_open_id'],
					'refresh_time'	=> GetTimestamp(7),
					'create_time'	=> time(),
					'time_status'	=> 0,
				);

				$UserPublicopinionTime->insert($Time);
			}
			//执行完毕，修改状态
			$UserPublicopinionTime->UpdateData(['time_id'=>$value['time_id'],'time_status'=>1]);
		}
	}
}