<?php
namespace app\api\controller;

use app\api\controller\Base;

//models
use app\admin\model\Kol;
use app\admin\model\User;
use app\admin\model\Video;
use app\admin\model\Music;
use app\admin\model\Goods;
use app\admin\model\Topic;
use app\admin\model\KolTrend;
use app\admin\model\VideoTrend;
use app\admin\model\GoodsTrend;
use app\admin\model\MusicTrend;
use app\admin\model\TopicTrend;
use app\admin\model\VideoComment;


//commons
use app\api\controller\Index;
use app\api\controller\GetData;



class Interfaces extends Base
{
	private $key = '08F0EDE0D84F0683E4E1E5ECB1F0EAE8';
	private $url = 'http://api01.6bqb.com/';


	/**************************************************** 综合 ****************************************************/

	/**
	 * 综合搜索
	 * @param keyword 	true 	string 	关键词
	 * @return 用户信息
	 */
	public function Search($keyword)
	{
		$url = $this->url.'douyin/general/search';

		$result = $this->curl($url,array('keyword'=>$keyword));
		dump($result);die;
	}

	/**
	 * 热点榜
	 * @return 热词 TOP30
	 */
	public function HotKeyword()
	{
		$url = $this->url.'douyin/hot/search';

		$result = $this->curl($url);
	}

	/**
	 * 热点榜
	 * @param id 	true 	string 	抖音号
	 * @return 热词 TOP30
	 */
	public function GetUid($id)
	{
		$url = $this->url.'douyin/uid';

		$result = $this->curl($url,array('id'=>$id));
	}

	/**
	 * 获取首页视频推荐
	 * @return 6条视频信息
	 */
	public function GetHomeRecommendVideo()
	{
		$url = $this->url.'douyin/feed';

		$result = $this->curl($url);
	}


	/**************************************************** 用户 ****************************************************/

	/**
	 * 获取用户详细信息
	 * @param uid 		true 	int 	用户uid
	 * @return 单个用户详细信息
	 */
	public function GetUserInfo($uid,$type="insert")
	{
		$Kol = new Kol;

		$userinfo = $Kol->where(array('kol_uid'=>$uid))->find();

		if(!$userinfo){

			$url = $this->url.'douyin/user';

			$result = $this->curl($url,array('uid'=>$uid));

			if($result['code'] == 1){

				$data = $result['data']['data'];

				$user = array(
					'kol_platform'		=> 1,
					'kol_uid'			=> $data['uid'],
					'kol_number'		=> $data['unique_id'] == '' ? $data['short_id'] : $data['unique_id'],
					'kol_nickname'		=> $data['nickname'],
					'kol_avatar'		=> $data['avatar_168x168']['url_list'][0],
					'kol_qrcode'		=> $data['share_info']['share_qrcode_url']['url_list'][0],
					'kol_school'		=> isset($data['school_name']) ? $data['school_name'] : '',
					'kol_sex'			=> $data['gender'],
					'kol_is_luban'		=> $data['with_luban_entry'] ? 1 : 0,
					'kol_is_goods'		=> $data['with_fusion_shop_entry'],
					'kol_is_star'		=> $data['is_star'] ? 1 : 0,
					'kol_is_gov'		=> $data['is_gov_media_vip'],
					'kol_weibo'			=> $data['weibo_url'],
					'kol_signature'		=> $data['signature'],
					'kol_verifyname'	=> $data['enterprise_verify_reason'],
					'kol_achievement'	=> $data['custom_verify'],
					'kol_constellation'	=> $data['constellation'],
					'kol_birthdayY'		=> $data['birthday'],
					'kol_age'			=> date('Y') - $data['birthday'],
					'kol_countries'		=> $data['country'],
					'kol_cityname'		=> $data['city'],
					'kol_area'			=> $data['district'],
					'statistics'		=> array(
						'kt_kol_uid'	=> $data['uid'],
						'kt_batec'		=> date('Y-m-d'),
						'kt_interact_value'	=> $data['is_star'] ? 1 : 0,
						'kt_dynamic'	=> $data['dongtai_count'],
						'kt_fans'		=> $data['followers_detail'][0]['fans_count'],
						'kt_videocount'	=> $data['aweme_count'],
						'kt_focus'		=> $data['following_count'],
						'kt_likes'		=> $data['favoriting_count'],
						'kt_like'		=> $data['total_favorited'],
						'kt_mean_like'	=> floor($data['total_favorited']/$data['aweme_count']),
					),
				);

				$user['kol_sort'] = $this->GetUserSort($user['kol_nickname'],$user['kol_uid']);

				if($data['province'] != '')
					$user['kol_province'] = db('area')->where(array('pid'=>0,'shortname'=>$data['province']))->value('id');
				elseif($data['city'] != '')
					$user['kol_province'] = db('area')->where(array('pid'=>array('neq',0),'shortname'=>$data['city']))->value('pid');
				else
					$user['kol_province'] = 0;

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
		}else
			$this->SaveKolTrend($userinfo['kol_id'],$uid);
	}

	/**
	 * 用户搜索
	 * @param keyword 	true 	string 	关键词
	 * @param page 		false 	int 	页数
	 * @return 用户列表
	 */
	public function SearchUser($keyword,$page=1)
	{
		$url = $this->url.'douyin/search';

		$result = $this->curl($url,array('keyword'=>$keyword,'page'=>$page));

		if($result['code'] == 1){

			foreach ($result['data']['data'] as $key => $value) {

				$data = $value['user_info'];

				$user[] = array(
					'uid'			=> $data['uid'],
					'avatar'		=> $data['avatar_168x168']['url_list']['0'],
					'nickname'		=> $data['nickname'],
					'access'		=> $data['unique_id'],
					'signature'		=> $data['signature'],
				);
			}

			return array('code'=>1,'msg'=>'获取成功','data'=>$user);

		}else{
			return array('code'=>0,'msg'=>'没有找到用户');
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
	 * 添加用户新发布作品
	 * @param uid 	true 	int 	用户uid
	 * @param page 	false 	int 	页数
	 * @return 返回用户视频作品列表
	 */
	public function GetUserWorks($uid,$page=0)
	{
		$Video = new Video;
		//获取数据库记录的用户最新作品发布时间
		$time = $Video->where('video_apiuid',$uid)->order('create_time desc')->value('create_time');
		$time = $time ? $time : 0;

		$url = $this->url.'douyin/user/feed';

		$result = $this->curl($url,array('uid'=>$uid,'page'=>$page));

		if($result['code'] == 1){
			foreach ($result['data']['data'] as $key => $value) {
				//判断用户是否有新作品发布
				if($value['create_time'] > $time){
					//有则声明数组，添加数据
					$video = array(
						'video_platform'	=> 1,
						'video_apiuid'		=> $value['author_user_id'],
						'video_username'	=> $value['author']['nickname'],
						'video_number'		=> $value['aweme_id'],
						'video_sort'		=> 0,
						'video_title'		=> '',
						'video_cover'		=> $value['video']['origin_cover']['url_list'][0],
						'video_sharetitle'	=> $value['share_info']['share_title'],
						'video_url'			=> $value['share_info']['share_url'],
						'video_goods'		=> $value['status']['with_goods'] ? 1 : 0,
						'video_duration'	=> $value['duration'],
						'video_music'		=> $value['music']['mid'],
						'video_desc'		=> $value['desc'],
						'video_status'		=> $value['status']['is_delete'] ? 1 : 0,
						'create_time'		=> $value['create_time'],
						'update_time'		=> time(),
						'statistics'		=> array(
							'vt_video_id'		=> $value['status']['aweme_id'],
							'vt_video_number'	=> $value['status']['aweme_id'],
							'vt_batec'			=> date('Y-m-d'),
							'vt_like'			=> $value['statistics']['digg_count'],
							'vt_reposts'		=> $value['statistics']['share_count'],
							'vt_comment'		=> $value['statistics']['comment_count'],
							'vt_download'		=> $value['statistics']['download_count'],
							'vt_play'			=> $value['statistics']['play_count'],
							'vt_time'			=> time(),
						),
					);

					$id = $this->CreateData($video,'video');
					if($id){
						$statistics = $video['statistics'];
						$statistics['vt_video_id'] = $id;
						$this->CreateData($statistics,'videoTrend');
					}
				}else
					return 1;	//没有则返回
			}
		}
	}

	/**
	 * 新添加用户添加作品
	 * @param uid 	true 	int 	用户uid
	 * @param page 	false 	int 	页数
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
						'video_platform'	=> 1,
						'video_apiuid'		=> $value['author_user_id'],
						'video_username'	=> $value['author']['nickname'],
						'video_number'		=> $value['aweme_id'],
						'video_sort'		=> $sort,
						'video_title'		=> '',
						'video_cover'		=> $value['video']['cover']['url_list'][0],
						'video_sharetitle'	=> $value['share_info']['share_title'],
						'video_url'			=> $value['share_info']['share_url'],
						'video_goods'		=> $value['status']['with_goods'] ? 1 : 0,
						'video_duration'	=> $value['duration'],
						'video_music'		=> $value['music']['mid'],
						'video_desc'		=> $value['desc'],
						'video_status'		=> $value['status']['is_delete'] ? 1 : 0,
						'create_time'		=> $value['create_time'],
						'update_time'		=> time(),
						'statistics'		=> array(
							'vt_video_id'		=> $value['status']['aweme_id'],
							'vt_video_number'	=> $value['status']['aweme_id'],
							'vt_batec'			=> date('Y-m-d'),
							'vt_like'			=> $value['statistics']['digg_count'],
							'vt_reposts'		=> $value['statistics']['share_count'],
							'vt_comment'		=> $value['statistics']['comment_count'],
							'vt_download'		=> $value['statistics']['download_count'],
							'vt_play'			=> $value['statistics']['play_count'],
							'vt_time'			=> time(),
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

	/**
	 * 明星榜
	 * @return 明星榜
	 */
	public function GetHotStar()
	{
		$url = $this->url.'douyin/hot/star';

		$result = $this->curl($url);
	}


	/**
	 * 获取用户粉丝信息
	 * @param uid 	false 	int 	用户主键
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

			$insert = array(
				'public_type'		=> 'kol',
				'public_key'		=> $uid,
				'public_age'		=> json_encode($ageInfo),
				'public_sex'		=> json_encode($sexInfo),
				'create_time'		=> time()
			);

			db('publicopinion')->insert($insert);
		}
	}


	/**************************************************** 品牌 ****************************************************/

	/**
	 * 获取品牌详情
	 * @param categoryId 	true 	int 	品牌分类ID
	 * @param brandId 		true 	int 	品牌ID
	 * @return 品牌详情
	 */
	public function GetBrandInfo($categoryId,$brandId)
	{
		$url = $this->url.'douyin/brand/detail';

		$result = $this->curl($url,array('categoryId'=>$categoryId,'brandId'=>$brandId));
	}

	/**
	 * 获取分类品牌
	 * @param categoryId 	true 	int 	品牌分类ID
	 * @return 品牌列表
	 */
	public function GetBrandSortList($categoryId)
	{
		$url = $this->url.'douyin/brand/list';

		$result = $this->curl($url,array('categoryId'=>$categoryId));
	}



	/**************************************************** 视频 ****************************************************/


	/**
	 * 今日最热视频
	 * @param type 	insert/return
	 * @return 最热视频 TOP20
	 */
	public function GetHotVideo($type='insert')
	{
		$url = $this->url.'douyin/hot/video';

		$result = $this->curl($url);

		if($result['code'] == 1){

			$Kol = new Kol;

			foreach ($result['data']['data']['aweme_list'] as $key => $value) {

				$info = $value['aweme_info'];

				if(!$Kol->GetOneData(array('kol_id'=>$info['author']['uid'])))
					$this->GetUserInfo($info['author']['uid'],'insert');

				$video = array(
					'video_platform'	=> 1,
					'video_apiuid'		=> $info['author']['uid'],
					'video_username'	=> $info['author']['nickname'],
					'video_number'		=> $info['status']['aweme_id'],
					'video_sort'		=> 0,
					'video_title'		=> '',
					'video_cover'		=> $info['video']['origin_cover']['url_list'][0],
					'video_sharetitle'	=> $info['share_info']['share_title'],
					'video_url'			=> $info['share_info']['share_url'],
					'video_goods'		=> $info['status']['with_goods'] ? 1 : 0,
					'video_duration'	=> $info['duration'],
					'video_music'		=> $info['music']['mid'],
					'video_desc'		=> $info['desc'],
					'video_status'		=> $info['status']['is_delete'] ? 1 : 0,
					'create_time'		=> $info['create_time'],
					'update_time'		=> time(),
					'statistics'		=> array(
						'vt_video_id'		=> $info['status']['aweme_id'],
						'vt_video_number'	=> $info['status']['aweme_id'],
						'vt_batec'			=> date('Y-m-d'),
						'vt_like'			=> $info['statistics']['digg_count'],
						'vt_reposts'		=> $info['statistics']['share_count'],
						'vt_comment'		=> $info['statistics']['comment_count'],
						'vt_download'		=> $info['statistics']['download_count'],
						'vt_play'			=> $info['statistics']['play_count'],
						'vt_time'			=> time(),
					),
				);
				if($type == 'insert'){
					$id = $this->CreateData($video,'video');
					if($id){
						//添加视频趋势
						$statistics = $video['statistics'];
						$statistics['vt_video_id'] = $id;
						$this->CreateData($statistics,'videoTrend');
					}
				}else
					$item[] = $video;

			}
			return $type == 'insert' ? true : $item;
		}
	}

	/**
	 * 视频分类
	 * @return 视频列表
	 */
	public function GetVideoSort()
	{
		$url = $this->url.'douyin/plus/category';

		$result = $this->curl($url);

		foreach ($result['data'] as $key => $value) {
			$item[] = array(
				'sort_platform'	=> 1,
				'sort_name'		=> $value['name'],
				'sort_parent'	=> 0,
				'sort_rank'		=> 0,
				'sort_status'	=> 1,
				'sort_time'		=> time(),
			);
		}

		return $item;
	}

	/**
	 * 视频搜索
	 * @param keyword 	true 	string 	关键词
	 * @param page 		false 	int 	页数
	 * @return 视频列表
	 */
	public function SearchVideo($keyword,$page=1)
	{
		$url = $this->url.'douyin/video/search';

		$result = $this->curl($url,array('keyword'=>$keyword,'page'=>$page));
	}



	public function NewGetVideoInfo($aweme_id)
	{
		//判断视频是否存在
		$Video = new Video;

		$Video->GetOneData(array('video_number'=>$aweme_id));

		//不存在则继续执行
		if(!$Video){

			$url = $this->url.'douyin/video/detail';
			//请求接口
			$result = $this->curl($url,array('aweme_id'=>$aweme_id));
			//判断是否请求成功
			if($result['code'] == 1){
				//赋值数据
				$info = $result['data']['data'][0];
				//组合数据
				$video = array(
					'video_platform'	=> 1,
					'video_apiuid'		=> $info['author']['uid'],
					'video_username'	=> $info['author']['nickname'],
					'video_number'		=> $info['status']['aweme_id'],
					'video_sort'		=> 0,
					'video_title'		=> '',
					'video_cover'		=> $info['video']['origin_cover']['url_list'][0],
					'video_sharetitle'	=> $info['share_info']['share_title'],
					'video_url'			=> $info['share_info']['share_url'],
					'video_goods'		=> $info['status']['with_goods'] ? 1 : 0,
					'video_duration'	=> $info['duration'],
					'video_music'		=> $info['music']['mid'],
					'video_desc'		=> $info['desc'],
					'video_status'		=> $info['status']['is_delete'] ? 1 : 0,
					'create_time'		=> $info['create_time'],
					'update_time'		=> time(),
					'statistics'		=> array(
						'vt_video_id'		=> $info['status']['aweme_id'],
						'vt_video_number'	=> $info['status']['aweme_id'],
						'vt_batec'			=> date('Y-m-d'),
						'vt_like'			=> $info['statistics']['digg_count'],
						'vt_reposts'		=> $info['statistics']['share_count'],
						'vt_comment'		=> $info['statistics']['comment_count'],
						'vt_download'		=> $info['statistics']['download_count'],
						'vt_play'			=> $info['statistics']['play_count'],
						'vt_monitoring'		=> 0,
						'vt_time'			=> time(),
					),
				);
				//判断是否含有商品
				if(isset($info['simple_promotions'])){

					$goods = json_decode($info['simple_promotions'],true);

					foreach ($goods as $key => $value) {
						$goodsinfo[] = $value['promotion_id'];
					}

					$video['video_goods'] = implode(',',$goodsinfo);
				}
				//前往添加视频
				$id = $this->CreateData($video,'video');
				//判断是否添加成功
				if($id){
					//组合视频趋势信息
					$statistics = $video['statistics'];
					$statistics['vt_video_id'] = $id;
					//添加趋势信息
					$idd = $this->CreateData($statistics,'videoTrend');
					//返回结果
					return $idd ? true : false;
				}else
					return false;//添加失败则返回结果
			}else
				return false;//请求失败则返回结果
		}else
			return true;//存在则返回结果
	}


	/**
	 * 获取视频详情
	 * @param aweme_id 	true 	int 	视频ID
	 * @return 单条视频详情
	 */
	public function GetVideoInfo($aweme_id, $type='insert',$isMonitoring=0)
	{
		$url = $this->url.'douyin/video/detail';

		$result = $this->curl($url,array('aweme_id'=>$aweme_id));
		if($result['code'] == 1){
			$info = $result['data']['data'][0];

			$video = array(
				'video_platform'	=> 1,
				'video_apiuid'		=> $info['author']['uid'],
				'video_username'	=> $info['author']['nickname'],
				'video_number'		=> $info['status']['aweme_id'],
				'video_sort'		=> 0,
				'video_title'		=> '',
				'video_cover'		=> $info['video']['origin_cover']['url_list'][0],
				'video_sharetitle'	=> $info['share_info']['share_title'],
				'video_url'			=> $info['share_info']['share_url'],
				'video_goods'		=> $info['status']['with_goods'] ? 1 : 0,
				'video_duration'	=> $info['duration'],
				'video_music'		=> $info['music']['mid'],
				'video_desc'		=> $info['desc'],
				'video_status'		=> $info['status']['is_delete'] ? 1 : 0,
				'create_time'		=> $info['create_time'],
				'update_time'		=> time(),
				'statistics'		=> array(
					'vt_video_id'		=> $info['status']['aweme_id'],
					'vt_video_number'	=> $info['status']['aweme_id'],
					'vt_batec'			=> date('Y-m-d'),
					'vt_like'			=> $info['statistics']['digg_count'],
					'vt_reposts'		=> $info['statistics']['share_count'],
					'vt_comment'		=> $info['statistics']['comment_count'],
					'vt_download'		=> $info['statistics']['download_count'],
					'vt_play'			=> $info['statistics']['play_count'],
					'vt_monitoring'		=> $isMonitoring,
					'vt_time'			=> time(),
				),
			);

			if(isset($info['simple_promotions'])){

				$goods = json_decode($info['simple_promotions'],true);

				foreach ($goods as $key => $value) {
					$goodsinfo[] = $value['promotion_id'];
				}

				$video['video_goods'] = implode(',',$goodsinfo);
			}

			if($type == 'insert'){
				$id = $this->CreateData($video,'video');
				if($id){
					$statistics = $video['statistics'];
					$statistics['vt_video_id'] = $id;
					$idd = $this->CreateData($statistics,'videoTrend');
				}
			}
			
			return $type == 'insert' ? true : $video;
		}
	}

	/**
	 * 音乐视频
	 * @param musicId 	true 	int 	音乐ID
	 * @param page 		false 	int 	页数
	 * @return 视频列表
	 */
	public function GetMusicVideo($musicId,$page=0)
	{
		$url = $this->url.'douyin/music/video';

		$result = $this->curl($url,array('musicId'=>$musicId,'page'=>$page));
	}


	/**************************************************** 音乐 ****************************************************/


	/**
	 * 热门音乐榜
	 * @return 最热音乐
	 */
	public function GetHotMusic()
	{
		$url = $this->url.'douyin/hot/music';

		$result = $this->curl($url);

		if($result['code'] == 1){
			foreach ($result['data']['data']['music_list'] as $key => $value) {

				$info = $value['music_info'];

				$music = array(
					'music_platform'	=> 1,
					'music_apiuid'		=> 0,
					'music_number'		=> $info['mid'],
					'music_username'	=> $info['author'],
					'music_title'		=> $info['title'],
					'music_cover'		=> $info['cover_large']['url_list'][0],
					'music_url'			=> $info['play_url']['url_list'][0],
					'music_duration'	=> $info['duration'],
					'music_usercount'	=> $info['user_count'],
					'create_time'		=> time(),
					'update_time'		=> time(),
					'statistics'		=> array(
						'mt_music_number'	=> $info['mid'],
						'mt_rank'			=> $key+1,
						'mt_batch'			=> date('Y-m-d'),
						'mt_usercount'		=> $info['user_count'],
						'mt_hot'			=> $value['hot_value'],
						'mt_time'			=> time(),
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
	}

	/**
	 * 音乐搜索
	 * @param keyword 	true 	string 	关键词
	 * @param page 		false 	int 	页数
	 * @return 音乐列表
	 */
	public function SearchMusic($keyword,$page=1)
	{
		$url = $this->url.'douyin/music/search';

		$result = $this->curl($url,array('keyword'=>$keyword,'page'=>$page));
	}

	/**
	 * 获取音乐分类
	 * @return 音乐分类
	 */
	public function GetMusicSort()
	{
		$url = $this->url.'douyin/music/category';

		$result = $this->curl($url);
	}

	/**
	 * 获取分类音乐
	 * @param id 	true 	int 	分类ID
	 * @param page 	false 	int 	页数
	 * @return 音乐列表
	 */
	public function GetMusicList($id,$page=0)
	{
		$url = $this->url.'douyin/music/list';

		$result = $this->curl($url,array('id'=>$id,'page'=>$page));
	}

	/**
	 * 获取音乐详情
	 * @param musicId 	true 	int 	音乐ID
	 * @return 音乐详情
	 */
	public function GetMusicInfo($musicId)
	{

		$url = $this->url.'douyin/music/detail';

		$result = $this->curl($url,array('musicId'=>$musicId));

		if($result['code'] == 1){

			$data = $result['data']['data'];

			$music = array(
				'music_platform'	=> 1,
				'music_apiuid'		=> 0,
				'music_number'		=> $data['mid'],
				'music_username'	=> $data['author'],
				'music_title'		=> $data['title'],
				'music_cover'		=> $data['cover_large']['url_list'][0],
				'music_url'			=> $data['play_url']['url_list'][0],
				'music_duration'	=> $data['duration'],
				'music_usercount'	=> $data['user_count'],
				'create_time'		=> time(),
				'update_time'		=> time(),
				'statistics'		=> array(
					'mt_music_number'	=> $data['mid'],
					'mt_rank'			=> 0,
					'mt_batch'			=> date('Y-m-d'),
					'mt_usercount'		=> $data['user_count'],
					'mt_hot'			=> 0,
					'mt_time'			=> time(),
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



	/**************************************************** 商品 ****************************************************/


	/**
	 * 抖音好物榜
	 * @param cid 	true 	int 	榜单类型
	 * @return 抖音好物榜
	 */
	public function GetGoods($cid)
	{
		$url = $this->url.'douyin/goods';

		$result = $this->curl($url,array('cid'=>$cid));

		if($result['code'] == 1){

			$data = $result['data']['data']['rank_list'];

			foreach ($data as $key => $value) {

				$goods = array(
					'goods_number'		=> $value['goods']['product_id'],
					'gooods_name'		=> $value['goods']['title'],
					'goods_user'		=> $value['author']['id'],
					'goods_nickname'	=> $value['author']['name'],
					'goods_cover'		=> substr($value['author']['avatar'],0,strripos($value['author']['avatar'],'/')).'/'.$value['goods']['cover'],
					'goods_images'		=> '',
					'goods_url'			=> $value['goods']['detail_url'],
					'goods_price'		=> $value['goods']['price'],
					'goods_mprice'		=> $value['goods']['market_price'],
					'goods_cowmmission'	=> $value['goods']['commodity_type'],
					'goods_type'		=> $cid,
					'goods_is_recommend'=> $value['is_recommended'],
					'update_time'		=> time(),
					'create_time'		=> time(),
					'statistics'		=> array(
						'gt_up_or_down'		=> $value['goods']['up_or_down'],
						'gt_sales'			=> $value['goods']['sales'],
						'gt_browse'			=> 0,
						'gt_kol'			=> 0,
						'gt_video'			=> 0,
						'gt_index'			=> 0,
						'gt_batch'			=> date('Y-m-d'),
						'gt_is_monitoring'	=> 0,
						'gt_create_time'	=> time(),
						'gt_time'			=> time(),
					),
				);

				$id = $this->CreateData($goods,'goods');

				if($id){
					//添加话题趋势
					$statistics = $goods['statistics'];
					$statistics['gt_goods_id'] = $id;
					$statistics['gt_goods_number'] = $goods['goods_number'];
					$this->CreateData($statistics,'goodsTrend');
				}
			}
		}
	}

	//获取抖音商品详情
	public function GetGoodsInfo($gid)
	{
		$url = $this->url."douyin/promotion/detail";

		$result = $this->curl($url,array('promotionId'=>$gid));

		if($result['code'] == 1){

			$data = $result['data']['data'][0];

			dump($data);die;

			$goods = array(
				'goods_number'		=> $data['promotion_id'],
				'gooods_name'		=> $data['title'],
				'goods_user'		=> 0,
				'goods_nickname'	=> '',
				'goods_cover'		=> $data['images'][0]['url_list']['0'],
				'goods_images'		=> '',
				'goods_url'			=> $data['detail_url'],
				'goods_price'		=> $data['price'],
				'goods_mprice'		=> $data['market_price'],
				'goods_commission'	=> $data['cos_fee'],
				'goods_type'		=> 0,
				'goods_is_recommend'=> 0,
				'update_time'		=> time(),
			);
		}

		dump($result);die;
	}


	/**************************************************** 话题 ****************************************************/


	/**
	 * 话题搜索
	 * @param keyword 	true 	string 	关键词
	 * @param page 		false 	int 	页数
	 * @return 话题列表
	 */
	public function SearchTopic($keyword,$page=1)
	{
		$url = $this->url.'douyin/topic/search';

		$result = $this->curl($url,array('keyword'=>$keyword,'page'=>$page));
	}


	/**
	 * 话题视频列表
	 * @param cid 	true 	int 	话题ID
	 * @param page 	false 	int 	页数
	 * @return 视频列表
	 */
	public function VideoTopicList($cid,$page=1)
	{
		$url = $this->url.'douyin/topic/video';

		$result = $this->curl($url,array('cid'=>$cid,'page'=>$page));
	}


	/**
	 * 获取话题详情
	 * @param cid 	true 	int 	话题ID
	 * @return 话题详情
	 */
	public function GetTopicInfo($cid)
	{
		$url = $this->url.'douyin/topic/detail';

		$result = $this->curl($url,array('cid'=>$cid));

		if($result['code'] == 1){

			$value = $result['data']['data'];

			$topic = array(
				'topic_platform'	=> 1,
				'topic_apiuid'		=> $value['author']['uid'],
				'topic_nickname'	=> $value['author']['nickname'],
				'topic_avatar'		=> $value['author']['avatar_thumb']['url_list'][0],
				'topic_number'		=> $value['cid'],
				'topic_title'		=> $value['cha_name'],
				'topic_cover'		=> $value['hashtag_profile'],
				'topic_ispgc'		=> $value['is_pgcshow'] ? 1 : 0,
				'topic_is_pk'		=> $value['is_challenge'],
				'topic_desc'		=> $value['desc'],
				'topic_view'		=> $value['view_count'],
				'topic_count'		=> $value['user_count'],
				'create_time'		=> time(),
				'update_time'		=> time(),
				'statistics'		=> array(
					'tt_batch'		=> date('Y-m-d'),
					'tt_user_count'	=> $value['user_count'],
					'tt_view_count'	=> $value['view_count'],
					'tt_time'		=> time()
				),
			);

			$id = $this->CreateData($topic,'topic');
			if($id){
				//添加话题趋势
				$statistics = $topic['statistics'];
				$statistics['tt_topic_id'] = $id;
				$statistics['tt_topic_number'] = $topic['topic_number'];
				$this->CreateData($statistics,'topicTrend');
			}
		}
	}


	/**
	 * 话题列表
	 * @param page 	false 	int 	页数
	 * @return 话题列表
	 */
	public function GetTopicList($page=1)
	{
		$url = $this->url.'douyin/topic';

		$result = $this->curl($url,array('page'=>$page));


		if($result['code'] == 1){

			$list = $result['data']['data'];


			foreach ($list as $key => $value) {

				$value = $value['challenge_info'];

				$topic = array(
					'topic_platform'	=> 1,
					'topic_apiuid'		=> $value['category_cover_info']['aweme_id'],
					'topic_nickname'	=> $value['author']['nickname'],
					'topic_avatar'		=> $value['author']['avatar_thumb']['url_list'][0],
					'topic_number'		=> $value['cid'],
					'topic_title'		=> $value['cha_name'],
					'topic_cover'		=> $value['category_cover_info']['cover']['url_list'][0],
					'topic_ispgc'		=> $value['is_pgcshow'] ? 1 : 0,
					'topic_is_pk'		=> $value['is_challenge'],
					'topic_desc'		=> $value['desc'],
					'topic_view'		=> $value['view_count'],
					'topic_count'		=> $value['user_count'],
					'create_time'		=> time(),
					'update_time'		=> time(),
					'statistics'		=> array(
						'tt_batch'		=> date('Y-m-d'),
						'tt_user_count'	=> $value['user_count'],
						'tt_view_count'	=> $value['view_count'],
						'tt_time'		=> time()
					),
				);

				$id = $this->CreateData($topic,'topic');
				if($id){
					//添加话题趋势
					$statistics = $topic['statistics'];
					$statistics['tt_topic_id'] = $id;
					$statistics['tt_topic_number'] = $topic['topic_number'];
					$this->CreateData($statistics,'topicTrend');
				}
			}
			return true;
		}
	}

	/**
	 * 获取视频评论
	 * @param page 	false 	int 	页数
	 * @return 话题列表
	 */
	public function GetVideoComment($vid=0,$page=1)
	{
		$url = $this->url.'douyin/comment';
		$result = $this->curl($url,array('itemId'=>$vid,'page'=>$page));
		dump($result);die;

		if($result['code'] == 1){

			$obj = new GetData;

			$video = $obj->GetVideoInfo($vid,'video_number');

			$data = $result['data']['data'];
			dump($data);die;

			foreach ($data as $key => $value) {
				$comment = array(
					'comm_video'	=> $video['video_id'],
					'comm_appid'	=> $value['cid'],
					'comm_text'		=> $value['text'],
					'comm_like'		=> $value['digg_count'],
					'comm_reply'	=> $value['reply_comment_total'],
					'comm_reply_id'	=> $value['reply_id'],
					'comm_nickname'	=> $value['user']['nickname'],
					'comm_avatar'	=> $value['user']['avatar_thumb']['url_list'][0],
					'comm_time'		=> $value['create_time'],
				);

				$res = $this->CreateData($comment,'videoComment');
			}

			$CommentObj = new VideoComment;
			$commentcount = $CommentObj->GetCount(array('comm_video'=>$video['video_id']));
			if($commentcount < 20 && $result['data']['hasNext'])
				$this->GetVideoComment($vid,$page+1);	//如果数据库该视频的评论数小于500并且有下一页则继续查询
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
        \QRcode::png($url,$filename , $errorCorrectionLevel, $matrixPointSize, 2,true);

        return $filename;
    }

	/**
	 * 添加数据
	 * @param data 	false 	array 	要添加的数据
	 * @param type 	false 	string 	数据类型
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
				$this->InsertUserWorks($data['kol_uid'],5);
				//添加用户舆情信息
				//$this->GetKolFansInfo($data['kol_uid']);
				return $result['code'] == 1 ? $result['id'] : 0;
			}else
				return $kol['kol_id'];
		}elseif($type == 'kolTrend'){

			//添加红人趋势信息

			$obj = new KolTrend;
			$Trend = $obj->GetOneData(array('kt_kol_uid'=>$data['kt_kol_uid'],'kt_batec'=>$data['kt_batec'],'kt_monitoring'=>0));

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
		}elseif($type == 'goods'){

			//添加商品

			$obj = new Goods;
			$topic = $obj->GetOneData(array('goods_number'=>$data['goods_number']));
			if(!$topic){
				$result = $obj->CreateData($data);
				return $result['code'] == 1 ? $result['id'] : 0;
			}else
				return $topic['goods_id'];

		}elseif($type == 'goodsTrend'){

			//添加商品趋势

			$index = new Index;
			$obj = new GoodsTrend;
			$GetData = new GetData;

			$Trend = $obj->GetOneData(array('gt_goods_id'=>$data['gt_goods_id'],'gt_batch'=>$data['gt_batch'],'gt_is_monitoring'=>0));

			if(!$Trend){

				$trend = $GetData->GetGoodsOneTrend($data['gt_goods_number'],0,false);

				if($trend){
					$data['gt_inc_sales'] = $data['gt_sales'] - $trend['gt_sales'];
					$data['gt_inc_browse'] = $data['gt_browse'] - $trend['gt_browse'];
				}else
					$data['gt_inc_sales'] = $data['gt_inc_browse'] = 0;

				$data['gt_index'] = $index->GoodsValue($data);
				$result = $obj->CreateData($data);
				return $result['code'] == 1 ? $result['id'] : 0;
			}else
				return $Trend['mt_id'];
		}
	} 

	/**
	 * 获取用户趋势总和
	 * @param uid 	false 	int 	用户主键
	 * @return 话题列表
	 */
	public function GetUserTrendCounts()
	{
		$videos = $Video->select();
	}


	//请求方法
	public function curl($url,$data=array())
	{
		$data = array_merge(array('apikey'=>$this->key),$data);
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
	    		'error_url'		=> $url,
	    		'error_data'	=> json_encode($data),
	    		'error_date'	=> date('Y-m-d H:i:s',time()),
	    		'error_code'	=> $result['retcode'],
	    		'error_time'	=> time()
	    	);
	    	db('error')->insert($error);
	    	return array('code'=>0);
	    }else
	    	return array('code'=>1,'data'=>$result);
	}




	/******************************************************** 更新方法 ***************************************************/

	//更新视频趋势
	public function SaveVideoTrend($vid,$videoNumber,$isMonitoring=0)
	{
		$obj = new VideoTrend;

		if($isMonitoring == 0){
			$Trend = $obj->GetOneData(array('vt_video_number'=>$videoNumber,'vt_batec'=>date('Y-m-d'),'vt_monitoring'=>0));
			$isInsert = empty($Trend) ? true : false;
		}else
			$isInsert = true;

		if($isInsert){
			$url = $this->url.'douyin/video/detail';
			$result = $this->curl($url,array('aweme_id'=>$videoNumber));
			if($result['code'] == 1){
				$info = $result['data']['data'][0];
				$statistics	= array(
					'vt_video_id'		=> $vid,
					'vt_video_number'	=> $videoNumber,
					'vt_batec'			=> date('Y-m-d'),
					'vt_like'			=> $info['statistics']['digg_count'],
					'vt_reposts'		=> $info['statistics']['share_count'],
					'vt_comment'		=> $info['statistics']['comment_count'],
					'vt_download'		=> $info['statistics']['download_count'],
					'vt_play'			=> $info['statistics']['play_count'],
					'vt_monitoring'		=> $isMonitoring,
					'vt_time'			=> time(),
				);

				$where['vt_monitoring'] = $isMonitoring;
				$where['vt_video_id'] = $vid;

				$vTrend = $obj->where($where)->order('vt_time desc')->find();

				if($vTrend){
					$statistics['vt_inc_like'] = $statistics['vt_like'] - $vTrend['vt_like'];
					$statistics['vt_inc_reposts'] = $statistics['vt_reposts'] - $vTrend['vt_reposts'];
					$statistics['vt_inc_comment'] = $statistics['vt_comment'] - $vTrend['vt_comment'];
					$statistics['vt_inc_download'] = $statistics['vt_download'] - $vTrend['vt_download'];
				}else
					$statistics['vt_inc_like'] = $statistics['vt_inc_reposts'] = $statistics['vt_inc_comment'] = $statistics['vt_inc_download'] = 0;

				$Index = new Index;
				$statistics['vt_hot'] = $Index->VideoHot($statistics);
				$result = $obj->CreateData($statistics);
			}
		}

		return true;
	}

	//更新音乐趋势
	public function SaveMusicTrend($mid,$musicNumber)
	{
		$obj = new MusicTrend;

		$Trend = $obj->GetOneData(array('mt_music_id'=>$mid,'mt_batch'=>date('Y-m-d')));

		if(!$Trend){
			$url = $this->url.'douyin/music/detail';
			$result = $this->curl($url,array('musicId'=>$musicNumber));
			if($result['code'] == 1){
				$data = $result['data']['data'];
				$statistics		= array(
					'mt_music_id'		=> $mid,
					'mt_music_number'	=> $musicNumber,
					'mt_rank'			=> 0,
					'mt_batch'			=> date('Y-m-d'),
					'mt_usercount'		=> $data['user_count'],
					'mt_hot'			=> 0,
					'mt_time'			=> time(),
				);

				$usercount = db('music_trend')->where('mt_music_id',$mid)->order('mt_time desc')->value('mt_usercount');
				$statistics['mt_inc_count'] = $usercount == Null ? 0 : $statistics['mt_usercount'] - $usercount;
				$obj->CreateData($statistics);
				db('music')->where('music_id',$mid)->update(['music_usercount'=>$statistics['mt_usercount']]);
			}
		}
	}


	//更新话题趋势
	public function SaveTopicTrend($tid,$topicNumber)
	{
		$obj = new TopicTrend;
		$Trend = $obj->GetOneData(array('tt_topic_id'=>$tid,'tt_batch'=>date('Y-m-d')));

		if(!$Trend){
			$url = $this->url.'douyin/topic/detail';
			$result = $this->curl($url,array('cid'=>$topicNumber));
			if($result['code'] == 1){
				$value = $result['data']['data'];
				$statistics		= array(
					'tt_topic_id'		=> $tid,
					'tt_topic_number'	=> $topicNumber,
					'tt_batch'			=> date('Y-m-d'),
					'tt_user_count'		=> $value['user_count'],
					'tt_view_count'		=> $value['view_count'],
					'tt_time'			=> time()
				);

				$tTrend = db('topic_trend')->where('tt_topic_id',$tid)->order('tt_time desc')->find();

				if($tTrend){
					$statistics['tt_inc_user'] = $statistics['tt_user_count'] - $tTrend['tt_user_count'];
					$statistics['tt_inc_view'] = $statistics['tt_view_count'] - $tTrend['tt_view_count'];
				}else
					$statistics['tt_inc_user'] = $statistics['tt_inc_view'] = 0;
				$obj->CreateData($statistics);
				db('topic')->where('topic_id',$tid)->update(['topic_view'=>$statistics['tt_view_count'],'topic_count'=>$statistics['tt_user_count']]);
			}
		}
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
	            	'kt_kol_id'		=> $kid,
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

	            $obj->CreateData($statistics);
	        }
		}
		return true;
	}
}