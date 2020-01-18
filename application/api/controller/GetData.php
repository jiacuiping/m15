<?php
namespace app\api\controller;

use app\api\controller\Base;
use think\Db;
use think\Session;

//models
use app\admin\model\Kol;
use app\admin\model\Mcn;
use app\admin\model\User;
use app\admin\model\Video;
use app\admin\model\Music;
use app\admin\model\Goods;
use app\admin\model\Topic;
use app\admin\model\Rotate;
use app\admin\model\McnKol;
use app\admin\model\Collect;
use app\admin\model\McnAgent;
use app\admin\model\McnGroup;
use app\admin\model\KolTrend;
use app\admin\model\VideoSort;
use app\admin\model\GoodsSort;
use app\admin\model\VideoTrend;
use app\admin\model\GoodsTrend;
use app\admin\model\TopicTrend;
use app\admin\model\MusicTrend;
use app\admin\model\VideoComment;
use app\admin\model\Certification;
use app\admin\model\Publicopinion;
use app\admin\model\Specification;

//获取数据类

class GetData extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 获取广告位
	 * @param position 	false 	string 	展示位置
	 * @return 数据列表
	 */
	public function GetRotate($position='video')
	{
		$Rotate = new Rotate;

		return $Rotate->GetDataList(array('rotate_address'=>$position,'rotate_status'=>1));
	}

	/**
	 * 获取规格信息
	 * @param type 	false 	string 	类型，为all时返回全部
	 * @return 数据列表
	 */
	public function GetSpecification($type='like')
	{
		$Specification = new Specification;

		if($type == 'all'){
			$data['like'] = $Specification->GetDataList(array('spec_type'=>'like'));
			$data['fans'] = $Specification->GetDataList(array('spec_type'=>'fans'));
			$data['kolv'] = $Specification->GetDataList(array('spec_type'=>'kolv'));
			$data['sales'] = $Specification->GetDataList(array('spec_type'=>'sales'));
			$data['duration'] = $Specification->GetDataList(array('spec_type'=>'duration'));
			$data['createtime'] = $Specification->GetDataList(array('spec_type'=>'createtime'));
		}elseif($type == 'like')
			$data = $Specification->GetDataList(array('spec_type'=>'like'));
		elseif($type == 'duration')
			$data['duration'] = $Specification->GetDataList(array('spec_type'=>'duration'));
		elseif($type == 'createtime')
			$data['createtime'] = $Specification->GetDataList(array('spec_type'=>'createtime'));
		elseif($type == 'fans')
			$data['fans'] = $Specification->GetDataList(array('spec_type'=>'fans'));
		elseif($type == 'kolv')
			$data['kolv'] = $Specification->GetDataList(array('spec_type'=>'kolv'));
		elseif($type == 'sales')
			$data['sales'] = $Specification->GetDataList(array('spec_type'=>'sales'));
		return $data;
	}

	/**
	 * 获取规格信息
	 * @param type 	false 	string 	类型，为all时返回全部
	 * @return 数据列表
	 */
	public function GetCitys($pid=0)
	{
		return db('area')->where('pid',$pid)->select();
	}

	/**
	 * 获取视频分类
	 * @return 数据列表
	 */
	public function GetVideoSort()
	{
		$Sort = new VideoSort;
		return $Sort->GetDataList(array('sort_status'=>1));
	}
	/**
	 * 获取商品分类
	 * @return 数据列表
	 */
	public function GetGoodsSort()
	{
		$Sort = new GoodsSort;
		return $Sort->GetDataList(array('sort_status'=>1));
	}

	/**
	 * 获取视频列表
	 * @param where 	true 	string 	关键词
	 * @return 数据列表
	 */
	public function GetVideoList($where=array(),$page=1,$limit=100,$order='vt_hot desc')
	{
		$Kol = new Kol;
		$Video = new Video;
//		$sql = "(select a.* from m15_video_trend as a right join (select vt_video_id, max(vt_time) as maxtime from m15_video_trend where vt_video_id is not null group by vt_video_id) as b on a.vt_video_id=b.vt_video_id and a.vt_time=b.maxtime order by a.vt_video_id asc)";
		$sql = "(select a.vt_video_id,a.vt_video_number,a.vt_hot,a.vt_like,a.vt_comment from m15_video_trend as a right join (select vt_video_id, max(vt_time) as maxtime from m15_video_trend where vt_video_id is not null group by vt_video_id) as b on a.vt_video_id=b.vt_video_id and a.vt_time=b.maxtime order by a.vt_video_id asc)";

		$data = $Video
				 -> alias('v')
				 -> join("$sql vt",'v.video_number = vt.vt_video_number')
				 -> where($where)
				 -> order($order)
				 -> page($page,$limit)
				 -> select();

		foreach ($data as $key => $value) {
			$data[$key]['vt_like'] = $this->TreatmentNumber($value['vt_like']);
			$data[$key]['video_kid'] = $Kol->GetField(array('kol_uid'=>$value['video_apiuid']),'kol_id');
			$data[$key]['vt_comment'] = $this->TreatmentNumber($value['vt_comment']);

		}

		return $data;
	}

	/**
	 * 获取视频详情
	 * id 		id
	 * field 	video_id/video_number
	 * @return 数据列表
	 */
	public function GetVideoInfo($id=0,$field="video_id")
	{
		$Video = new Video;
		$Sort = new VideoSort;
		
		$data = $Video->GetOneData(array($field=>$id));

		$data['video_sort'] = $data['video_sort'] == 0 ? '暂无' : $Sort->GetField(array('sort_id'=>$data['video_sort']),'sort_name');
		$data['video_title'] = strlen($data['video_desc']) > 60 ? SubstrString($data['video_desc'],23).'...' : $data['video_desc'];

		return $data;
	}

	/**
	 * 获取视频评论
	 * id 		id
	 * @return 数据列表
	 */
	public function GetVideoComment($vid=0,$limit=200)
	{
		$Comment = new VideoComment;

		return $Comment->GetDataList(array('comm_video'=>$vid),1,$limit);
	}

	/**
	 * 获取视频总条数
	 * @param where 	true 	string 	关键词
	 * @return 数据列表
	 */
	public function GetVideoCount($where=array())
	{
		$Video = new Video;

		return $Video
				 -> alias('v')
				 -> join("__VIDEO_TREND__ vt",'v.video_number = vt.vt_video_number','left')
				 -> where($where)
				 -> count();
	}

	//获取视频最新趋势
	public function GetVideoOneTrend($vid=0,$isChangeNumber=true)
	{
		$VideoTrend = new VideoTrend;
		$trend = $VideoTrend->where(array('vt_video_id'=>$vid))->order('vt_time desc')->find();

		if(!$trend) return array();

		if($isChangeNumber){
			$trend['vt_like'] = $this->TreatmentNumber($trend['vt_like']);
			$trend['vt_reposts'] = $this->TreatmentNumber($trend['vt_reposts']);
			$trend['vt_comment'] = $this->TreatmentNumber($trend['vt_comment']);
			$trend['vt_download'] = $this->TreatmentNumber($trend['vt_download']);
		}

		return $trend;
	}

	//获取视频所有趋势
	public function GetVideoTrendList($vid)
	{
		$Trend = new VideoTrend;
		return $Trend->GetDataList(array('vt_video_id'=>$vid));
	}


	//获取视频增长数据
	public function GetVideoIncData($vid,$day=7,$isChangeNumber=true)
	{
		$Video = new Video;
		$VideoTrend = new VideoTrend;

		$newData = $this->GetVideoOneTrend($vid,false);

		$oldtime = strtotime("-$day day");

		$oldData = $VideoTrend->where(array('vt_video_id'=>$vid,'vt_time'=>array('LT',$oldtime)))->order('vt_time desc')->find();

		if(!$oldData)
			$oldData = $VideoTrend->where(array('vt_video_id'=>$vid,))->order('vt_time')->find();

		$result = array(
			'like'			=> $newData['vt_like'] - $oldData['vt_like'],
			'reposts'		=> $newData['vt_reposts'] - $oldData['vt_reposts'],
			'comment'		=> $newData['vt_comment'] - $oldData['vt_comment'],
			'download'		=> $newData['vt_download'] - $oldData['vt_download'],
		);

		if($isChangeNumber){
			$result['like'] = $this->TreatmentNumber($result['like']);
			$result['reposts'] = $this->TreatmentNumber($result['reposts']);
			$result['comment'] = $this->TreatmentNumber($result['comment']);
			$result['download'] = $this->TreatmentNumber($result['download']);
		}

		return $result;
	}

	/**
	 * 获取音乐列表
	 * @return 数据列表
	 */
	public function GetMusicList($where=array(),$page=1,$limit=50,$order='music_usercount desc')
	{
		$Music = new Music;

		$data = $Music-> where($where)->order($order)->page($page,$limit)->select();

		foreach ($data as $key => $value) {
			$data[$key]['music_usercount'] = $this->TreatmentNumber($value['music_usercount']);
		}

		return $data;
	}

	/**
	 * 获取音乐总条数
	 * @param where 	true 	string 	关键词
	 * @return 数据列表
	 */
	public function GetMusicCount($where=array())
	{
		$Music = new Music;
		
		return $Music->where($where)->count();
	}

	/**
	 * 获取音乐详情
	 * @param where 	true 	string 	关键词
	 * @return 数据列表
	 */
	public function GetMusicInfo($mid=0,$field='music_id')
	{
		$Music = new Music;
		
		$data = $Music->GetOneData(array($field=>$mid));

		if($data){		
			$data['music_duration'] = $this->TreatmentTime($data['music_duration']);
			$data['music_usercount'] = $this->TreatmentNumber($data['music_usercount']);
		}else
			return array();

		return $data;
	}

	//获取音乐最新趋势
	public function GetMusicOneTrend($mid=0,$isChangeNumber=true)
	{
		$MusicTrend = new MusicTrend;
		$trend = $MusicTrend->where(array('mt_music_number'=>$mid))->order('mt_time desc')->find();

		if(!$trend) return array();

		if($isChangeNumber){
			$trend['mt_usercount'] = $this->TreatmentNumber($trend['mt_usercount']);
		}

		return $trend;
	}

	/**
	 * 获取用户列表
	 * id 		id
	 * field 	kol_id/kol_uid
	 * @return 数据列表
	 */
	public function GetKolList($where=array(),$page=1,$limit=50,$order='kt.kt_hot desc',$isTreat=true)
	{
		$Kol = new Kol;

		$Sort = new VideoSort;

		$sql = "(select a.* from m15_kol_trend as a right join (select kt_kol_id, max(kt_time) as maxtime from m15_kol_trend where kt_kol_id is not null group by kt_kol_id) as b on a.kt_kol_id=b.kt_kol_id and a.kt_time=b.maxtime order by a.kt_kol_id asc)";

		$data = $Kol
				 -> alias('k')
				 -> join("$sql kt",'k.kol_id = kt.kt_kol_id')
				 -> where($where)
				 -> order($order)
				 -> page($page,$limit)
				 -> select();

		foreach ($data as $key => $value) {
			if($isTreat){
				$data[$key]['kt_fans'] = $this->TreatmentNumber($value['kt_fans']);
				$data[$key]['kt_inc_fans'] = $this->TreatmentNumber($value['kt_inc_fans']);
				$data[$key]['kt_mean_like'] = $this->TreatmentNumber($value['kt_mean_like']);
				$data[$key]['kt_inc_like'] = $this->TreatmentNumber($value['kt_inc_like']);
				$data[$key]['kt_mean_comment'] = $this->TreatmentNumber($value['kt_mean_comment']);
				$data[$key]['kt_inc_comment'] = $this->TreatmentNumber($value['kt_inc_comment']);
				$data[$key]['kt_mean_share'] = $this->TreatmentNumber($value['kt_mean_share']);
			}
			$data[$key]['kol_age'] = $value['kol_birthdayY'] == '' ? '未设置' : date('Y') - substr($value['kol_birthdayY'], 0,4);
			$data[$key]['kol_sort'] = $value['kol_sort'] == 0 ? '暂无分类' : $Sort->GetField(array('sort_id'=>$value['kol_sort']),'sort_name');
		}


		return $data;
	}	

	/**
	 * 获取用户数量
	 * id 		id
	 * field 	kol_id/kol_uid
	 * @return 数据列表
	 */
	public function GetKolCount($where=array())
	{
		$Kol = new Kol;

		return $Kol->alias('k')->where($where)->count();
	}

	/**
	 * 获取用户信息
	 * id 		id
	 * field 	kol_id/kol_uid
	 * @return 数据列表
	 */
	public function GetKolInfo($id=0,$field="kol_id")
	{
		$Kol = new Kol;
		$Sort = new VideoSort;
		$data = $Kol->GetOneData(array($field=>$id));

		if(!$data) return array();

		$data['kol_sort'] = $data['kol_sort'] == 0 ? '暂无分类' : $Sort->GetField(array('sort_id'=>$data['kol_sort']),'sort_name');
		$data['kol_age'] = $data['kol_birthdayY'] == '' ? '未设置' : date('Y') - substr($data['kol_birthdayY'], 0,4);

		return $data;
	}

	/**
	 * 获取用户最新趋势
	 * @return 数据列表
	 */
	public function GetKolOneTrend($uid=0,$isChangeNumber=true)
	{
		$Video = new Video;
		$Trend = new KolTrend;

		$trend = $Trend->where(array('kt_kol_id'=>$uid))->order('kt_time desc')->find();

		if(empty($trend)) return array();

		$trend['monthvideo'] = $Video->where(array('video_apiuid'=>$trend['kt_kol_uid'],'create_time'=>array('GT',mktime(0,0,0,date('m'),1,date('Y')))))->count();
		$trend['tbhot'] = round($trend['kt_hot']/100,2);
		$trend['bighotb'] = round($trend['kt_bighot']/$trend['kt_videocount']*100)."％";
		$trend['midhotb'] = round($trend['kt_midhot']/$trend['kt_videocount']*100)."％";
		$trend['smahotb'] = round($trend['kt_smahot']/$trend['kt_videocount']*100)."％";

		if($isChangeNumber){
			$trend['kt_fans'] = $this->TreatmentNumber($trend['kt_fans']);
			$trend['kt_like'] = $this->TreatmentNumber($trend['kt_like']);
			$trend['kt_mean_like'] = $this->TreatmentNumber($trend['kt_mean_like']);
			$trend['kt_comments'] = $this->TreatmentNumber($trend['kt_comments']);
			$trend['kt_mean_comment'] = $this->TreatmentNumber($trend['kt_mean_comment']);
			$trend['kt_share'] = $this->TreatmentNumber($trend['kt_share']);
			$trend['kt_mean_share'] = $this->TreatmentNumber($trend['kt_mean_share']);
		}

		return $trend;
	}

	/**
	 * 获取用户所有趋势
	 * @return 数据列表
	 */
	public function GetKolTrendList($uid=0)
	{
		$Trend = new KolTrend;
		return $Trend->GetDataList(array('kt_kol_id'=>$vid));
	}

	/**
	 * 获取用户增长数据
	 * @return 数据列表
	 */
	public function GetKolIncData($kid,$day=7,$isChangeNumber=true)
	{
		$Kol = new Kol;
		$KolTrend = new KolTrend;

		$newData = $this->GetKolOneTrend($kid,false);

		$oldtime = strtotime("-$day day");

		$oldData = $KolTrend->where(array('kt_kol_id'=>$kid,'kt_time'=>array('GT',$oldtime)))->order('kt_time')->find();

		if(!$oldData)
			$oldData = $KolTrend->where(array('kt_kol_id'=>$kid,))->order('kt_time')->find();

		$result = array(
			'fans'			=> $newData['kt_fans'] - $oldData['kt_fans'],
			'like'			=> $newData['kt_like'] - $oldData['kt_like'],
			'comment'		=> $newData['kt_comments'] - $oldData['kt_comments'],
			'share'			=> $newData['kt_share'] - $oldData['kt_share'],
			'download'		=> $newData['kt_download'] - $oldData['kt_download'],
			'videocount'	=> $newData['kt_videocount'] - $oldData['kt_videocount'],
		);

		if($isChangeNumber){
			$result['fans'] = $this->TreatmentNumber($result['fans']);
			$result['like'] = $this->TreatmentNumber($result['like']);
			$result['comment'] = $this->TreatmentNumber($result['comment']);
			$result['share'] = $this->TreatmentNumber($result['share']);
			$result['videocount'] = $this->TreatmentNumber($result['videocount']);
			$result['download'] = $this->TreatmentNumber($result['download']);
		}


		return $result;
	}

	/**
	 * 获取用户数据汇总
	 * @return 数据列表
	 */
	public function GetKolDataCount($kol)
	{
		$sql = "(select a.* from m15_video_trend as a right join (select vt_video_id, max(vt_time) as maxtime from m15_video_trend where vt_video_id is not null group by vt_video_id) as b on a.vt_video_id=b.vt_video_id and a.vt_time=b.maxtime order by a.vt_video_id asc)";

        $videos = db('video')
        		-> alias('v')
        		-> join("$sql vt",'v.video_id = vt.vt_video_id')
        		-> where('v.video_apiuid',$kol)
        		-> select();


        if(empty($videos)) return array();

        $big = $mid = $sam = 0;

        foreach ($videos as $key => $value) {
        	if($value['vt_like'] > 2000000)
        		$big++;
        	elseif($value['vt_like'] > 1000000)
        		$mid++;
        	elseif($value['vt_like'] > 500000)
        		$sam++;
        }

		$data = array(
			'like'			=> array_sum(array_column($videos,'vt_like')),
			'reposts'		=> array_sum(array_column($videos,'vt_reposts')),
			'comments'		=> array_sum(array_column($videos,'vt_comment')),
			'download'		=> array_sum(array_column($videos,'vt_download')),
			'hot'			=> array_sum(array_column($videos,'vt_hot')),
			'count' 		=> count($videos),
			'bighot'		=> $big,
			'midhot'		=> $mid,
			'samhot'		=> $sam,
		);

		return $data;
	}


	/**
	 * 获取话题列表
	 * @return 数据列表
	 */
	public function GetTopicList($where=array(),$page=1,$limit=50,$order='topic_view desc')
	{
		$Topic = new Topic;

		$data = $Topic-> where($where)->order($order)->page($page,$limit)->select();

		foreach ($data as $key => $value) {
			$data[$key]['topic_view'] = $this->TreatmentNumber($value['topic_view']);
		}

		return $data;
	}

	/**
	 * 获取话题总条数
	 * @param where 	true 	string 	关键词
	 * @return 数据列表
	 */
	public function GetTopicCount($where=array())
	{
		$Topic = new Topic;
		
		return $Topic->where($where)->count();
	}

	
	/**
	 * 获取话题总条数
	 * @param where 	true 	string 	关键词
	 * @return 数据列表
	 */
	public function GetTopicInfo($tid,$field="topic_id")
	{
		$Topic = new Topic;
		
		$data = $Topic->GetOneData(array($field=>$tid));

		if($data){		
			$data['topic_view'] = $this->TreatmentNumber($data['topic_view']);
			$data['topic_count'] = $this->TreatmentNumber($data['topic_count']);
		}else
			return array();

		return $data;
	}


	/**
	 * 获取收藏信息
	 * @param where 	true 	string 	关键词
	 * @return 数据列表
	 */
	public function GetCollectList($type="video",$page=1,$limit=50)
	{
		$obj = new Collect;
		$ids = $obj->where(array('collect_user'=>session::get('user.user_id'),'collect_type'=>$type))->column('collect_key');
		if($type == 'video'){
			return $this->GetVideoList(array('v.video_id'=>array('in',$ids)),$page);
		}elseif($type == 'music'){
			return $this->GetMusicList(array('music_id'=>array('in',$ids)),$page);
		}elseif($type == 'topic'){
			return $this->GetTopicList(array('topic_id'=>array('in',$ids)),$page);
		}elseif($type == 'kol'){
			return $this->GetKolList(array('kol_id'=>array('in',$ids)),$page);
		}
	}


	/*********************************************************** MCN ***************************************************************/

	//获取MCN信息
	public function GetMcnData()
	{
		$User = new User;
		
		$uid = session::get('user.user_id');

		$user = $User->GetOneData(array('user_id'=>$uid));

		if(!$user) return array('code'=>0,'msg'=>'用户不存在');

		if($user['user_type'] != 4 && $user['user_type'] != 8) return array('code'=>0,'msg'=>'当前账号暂未认证MCN机构，暂时无法使用MCN功能！');

		if($user['user_certification'] == 0) array('code'=>0,'msg'=>'未查询到当前账户的认证信息，请稍后重试！');

		$Certification = new Certification;

		$status = $Certification->GetField(array('certification_id'=>$user['user_certification']),'certification_status');

		if($status == 0) return array('code'=>0,'msg'=>'当前账户认证信息未审核，如长时间未审核，请联系客服人员询问');

		if($status == -1) return array('code'=>0,'msg'=>'您提交的MCN机构认证信息未审核通过，暂时无法使用MCN功能');

		//if($user['user_type'] != 4) return array('code'=>0,'msg'=>'当前账号暂未提交认证MCN机构，暂时无法使用MCN功能！');

		$Mcn = new Mcn;

		$info = $Mcn->GetOneData(array('mcn_id'=>$user['user_expansion']));

		return array('code'=>1,'data'=>$info);
	}


	//获取MCN统计信息
	public function GetMcnStatistical($mcn=0,$type='app',$key=0)
	{
		$where['mk_mcn'] = $mcn;
		if($key != 0){
			if($type == 'group') $where['mk_group'] = $key;
			if($type == 'agent') $where['mk_agent'] = $key;
		}

		$McnKol = new McnKol;
		$McnAgent = new McnAgent;
		$McnGroup = new McnGroup;

		$kols = $McnKol->GetColumn($where,'mk_kol');

		if($kols){

			$infos = $this->GetKolList(array('kol_id'=>array('in',$kols)),1,99999,'kt.kt_hot desc',false);

			foreach ($kols as $key => $value) {
				$weekinfo[] = $this->GetKolIncData($value,7,false);
				$lweekinfo[] = $this->GetKolIncData($value,14,false);
				//$monthinfo[] = $this->GetKolIncData($value,30,false);
				//$lmonthinfo[] = $this->GetKolIncData($value,60,false);
			}

			$result = array(
				'number'	=> count($infos),
				'fans'		=> array_sum(array_column($infos,'kt_fans')),
				'incfans'	=> array_sum(array_column($infos,'kt_inc_fans')),
				'weekfans'	=> array_sum(array_column($weekinfo,'fans')),
				//'monthfans'	=> array_sum(array_column($monthinfo,'fans')),
			);
			$result['lweekfans'] = array_sum(array_column($lweekinfo,'fans')) - $result['weekfans'];
			//$result['lmonthfans'] = array_sum(array_column($lmonthinfo,'fans')) - $result['monthfans'];
		}else{
			$result = array(
				'number'	=> 0,
				'fans'		=> 0,
				'incfans'	=> 0,
				'weekfans'	=> 0,
				'lweekfans'	=> 0,
				//'monthfans'	=> 0,
				//'lmonthfans'=> 0,
			);
		}

		$result['agent'] = $McnAgent->GetCount(array('agent_mcn'=>$mcn));
		$result['group'] = $McnGroup->GetCount(array('group_mcn'=>$mcn));

		return $result;
	}




	/*********************************************************** Goods ***************************************************************/

	//获取商品列表
	public function GetGoodsList($where=array(),$page=1,$limit=100,$order='gt_index desc')
	{
		$Kol = new Kol;
		$Goods = new Goods;
		$sql = "(select a.* from m15_goods_trend as a right join (select gt_goods_id, max(gt_time) as maxtime from m15_goods_trend where gt_goods_id is not null group by gt_goods_id) as b on a.gt_goods_id=b.gt_goods_id and a.gt_time=b.maxtime order by a.gt_goods_id asc)";

		$data = $Goods
				 -> alias('g')
				 -> join("$sql gt",'g.goods_number = gt.gt_goods_number')
				 -> where($where)
				 -> order($order)
				 -> page($page,$limit)
				 -> select();

		foreach ($data as $key => $value) {
			$data[$key]['gt_sales'] = $this->TreatmentNumber($value['gt_sales']);
			$data[$key]['gt_inc_sales'] = $this->TreatmentNumber($value['gt_inc_sales']);
			$data[$key]['gt_browse'] = $this->TreatmentNumber($value['gt_browse']);
			$data[$key]['gt_inc_browse'] = $this->TreatmentNumber($value['gt_inc_browse']);
		}

		return $data;
	}

	//获取商品详情
	public function GetGoodsInfo($goods)
	{
		$Goods = new Goods;
		$GoodsSort = new GoodsSort;

		$info = $Goods->GetOneData(array('goods_id'=>$goods));

		if(!$info) return array('code'=>0,'msg'=>'商品不存在');

		$info['trend'] = $this->GetGoodsOneTrend($goods);

		$info['goods_sortname'] = $GoodsSort->GetField(array('sort_id'=>$info['goods_type']),'sort_name');

		return array('code'=>1,'data'=>$info);
	}

	//获取商品最新趋势
	public function GetGoodsOneTrend($gid,$is_monitoring=0,$isChangeNumber=true)
	{
		$Goods = new Goods;
		$Trend = new GoodsTrend;

		$trend = $Trend->where(array('gt_goods_id'=>$gid,'gt_is_monitoring'=>$is_monitoring))->order('gt_time desc')->find();

		if(empty($trend)) return array();

		if($isChangeNumber){
			$trend['gt_sales'] = $this->TreatmentNumber($trend['gt_sales']);
			$trend['gt_inc_sales'] = $this->TreatmentNumber($trend['gt_inc_sales']);
			$trend['gt_browse'] = $this->TreatmentNumber($trend['gt_browse']);
			$trend['gt_inc_browse'] = $this->TreatmentNumber($trend['gt_inc_browse']);
		}

		return $trend;
	}


	/**
	 * 获取变化趋势
	 * @return 数据列表
	 */
	public function GetChangeTrend($id=0,$type='kol',$number=5)
	{
		if($type == 'kol'){
			$trendobj = new KolTrend;
			$data = $trendobj->where('kt_kol_id',$id)->order('kt_time desc')->limit($number)->select();

			foreach ($data as $key => $value) {
				$data[$key]['kt_batec'] = "'".substr($value['kt_batec'],5,5)."'";
			}

			$result = array(
				'date' 		=> '['.implode(',',array_reverse(array_column($data, 'kt_batec'))).']',
				'hot' 		=> '['.implode(',',array_reverse(array_column($data, 'kt_hot'))).']',
				'fans' 		=> '['.implode(',',array_reverse(array_column($data, 'kt_fans'))).']',
				'like' 		=> '['.implode(',',array_reverse(array_column($data, 'kt_like'))).']',
				'share' 	=> '['.implode(',',array_reverse(array_column($data, 'kt_share'))).']',
				'comments' 	=> '['.implode(',',array_reverse(array_column($data, 'kt_comments'))).']',
				'download' 	=> '['.implode(',',array_reverse(array_column($data, 'kt_download'))).']',
			);

		}elseif($type == 'video'){
			$trendobj = new VideoTrend;
			$data = $trendobj->where('vt_video_id',$id)->order('vt_time desc')->limit($number)->select();

			foreach ($data as $key => $value) {
				$data[$key]['vt_batec'] = substr($value['vt_batec'],8,2);
			}

			$result = array(
				'date' 		=> '['.implode(',',array_reverse(array_column($data, 'vt_batec'))).']',
				'hot' 		=> '['.implode(',',array_reverse(array_column($data, 'vt_hot'))).']',
				'like' 		=> '['.implode(',',array_reverse(array_column($data, 'vt_like'))).']',
				'reposts' 	=> '['.implode(',',array_reverse(array_column($data, 'vt_reposts'))).']',
				'comment' 	=> '['.implode(',',array_reverse(array_column($data, 'vt_comment'))).']',
				'download' 	=> '['.implode(',',array_reverse(array_column($data, 'vt_download'))).']',
			);

		}elseif($type == 'music'){
			$trendobj = new MusicTrend;
			$data = $trendobj->where('mt_music_id',$id)->order('mt_time desc')->limit($number)->select();
			
			foreach ($data as $key => $value) {
				$data[$key]['mt_batch'] = substr($value['mt_batch'],8,2);
			}

			$result = array(
				'date' 		=> '['.implode(',',array_reverse(array_column($data, 'mt_batch'))).']',
				'usercount' => '['.implode(',',array_reverse(array_column($data, 'mt_usercount'))).']',
			);

		}elseif($type == 'topic'){
			$trendobj = new TopicTrend;
			$data = $trendobj->where('tt_topic_id',$id)->order('tt_time desc')->limit($number)->select();
			
			foreach ($data as $key => $value) {
				$data[$key]['tt_batch'] = substr($value['tt_batch'],8,2);
			}

			$result = array(
				'date' 		=> '['.implode(',',array_reverse(array_column($data, 'tt_batch'))).']',
				'usercount' => '['.implode(',',array_reverse(array_column($data, 'tt_user_count'))).']',
				'viewcount' => '['.implode(',',array_reverse(array_column($data, 'tt_view_count'))).']',
			);
		}elseif($type == 'kolv'){
			$Kol = new Kol;
			$Video = new Video;
			$trendobj = new VideoTrend;
			$data = array();

			$kolinfo = $Kol->GetOneDataById($id);

			$ids = $Video->field('video_id,video_desc')->where('video_apiuid',$kolinfo['kol_uid'])->limit($number)->select();

			foreach ($ids as $key => $value) {
				$trend = $this->GetVideoOneTrend($value['video_id'],false);
				if($trend){
					$data[] = array(
						'title'		=> "'".$value['video_desc']."'",
						'like'		=> $trend['vt_like'],
						'comment'	=> $trend['vt_comment'],
					);
				}
			}
				
			$result = array(
				'title'		=> '['.implode(',',array_reverse(array_column($data, 'title'))).']',
				'like'		=> '['.implode(',',array_reverse(array_column($data, 'like'))).']',
				'comment'	=> '['.implode(',',array_reverse(array_column($data, 'comment'))).']',
			);
		}elseif($type == 'mcn'){

			$Trendobj = new KolTrend;

			$data = db('kol_trend')
				-> field('kt_batec,sum(kt_inc_fans) as fans,sum(kt_inc_like) as likes,sum(kt_inc_comment) as comment,sum(kt_inc_share) as share')
				-> where(array('kt_kol_id'=>array('in',$id)))
				-> order('kt_time desc')
				-> group('kt_batec')
				-> limit($number)
				-> select();

			foreach ($data as $key => $value) {
				$data[$key]['kt_batec'] = "'".substr($value['kt_batec'],5,5)."'";
			}

			$result = array(
				'date' 		=> '['.implode(',',array_reverse(array_column($data, 'kt_batec'))).']',
				'fans' 		=> '['.implode(',',array_reverse(array_column($data, 'fans'))).']',
				'like' 		=> '['.implode(',',array_reverse(array_column($data, 'likes'))).']',
				'share' 	=> '['.implode(',',array_reverse(array_column($data, 'share'))).']',
				'comments' 	=> '['.implode(',',array_reverse(array_column($data, 'comment'))).']',
			);
		}
		return $result;
	}


	//获取舆情信息
	public function GeetPublicOpinionInfo($key, $type)
	{
		$Public = new Publicopinion;

		$data = $Public->GetOneData(array('public_key'=>$key,'public_type'=>$type));

		if($data){

			if($data['public_age'] != ''){
				$age = json_decode($data['public_age'],true);
				$data['age'] = array(
					'key'	=> '['.implode(',',array_keys($age)).']',
					'val'	=> '['.implode(',',array_values($age)).']',
				);
			}

			if($data['public_sex'] != ''){
				$sex = json_decode($data['public_sex'],true);
				$data['sex'] = array(
					'key'	=> '['.implode(',',array_keys($sex)).']',
					'val'	=> '['.implode(',',array_values($sex)).']',
				);
			}

			return array('code'=>1,'msg'=>'获取成功','data'=>$data);
		}else
			return array('code'=>0,'msg'=>'暂无数据');
	}


	/**
	 * 时间转换日期
	 * @return 数据列表
	 */
	public function TreatmentTime($time,$type="seconds")
	{
		if($type == 'minutes') $time = $time/1000;

		return $time >= 60 ? (floor($time/60)).'分'.($time%60).'秒' : $time.'秒';
	}

	/**
	 * 数字转换万级
	 * @return 数据列表
	 */
	public function TreatmentNumber($number=0)
	{
		if($number < 10000)
			return $number;
		elseif($number >10000 && $number < 100000000)
			return round($number/10000,1).'万';
		elseif($number > 100000000)
			return round($number/100000000,1).'亿';
	}
}
