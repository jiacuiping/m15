<?php
namespace app\api\controller;

use app\api\controller\Base;

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



class DyInterfaces extends Base
{
	private $key = 'awnxxvwav6czf00h';
	private $url = 'https://open.douyin.com';
	private $secret = '19afaed4ee427baba3c4b48d6ed20c1b';

	//获取登陆二维码
	public function GetLoginQrcode()
	{
		$url = $this->url.'/platform/oauth/connect/';

		$pamar = array(
			'response_type'		=> 'code',
			'scope' 			=> '(user_info,video.data,video.list,video.comment,im)',
			'redirect_uri'		=> "http://new.m15.ltd/index/login/dyqrcode",
			'state'				=> 1,
		);

		$result = $this->curl($url,$pamar);

		dump($result);die;
	}

	//请求方法
	public function curl($url,$data=array())
	{
		$data = array_merge(array('client_key'=>$this->key),$data);

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

	    dump($json);die;
	    $result = json_decode($json,true);

	    dump($result);die;


	    // if($result['retcode'] != '0000'){
	    // 	$error = array(
	    // 		'error_url'		=> $url,
	    // 		'error_data'	=> json_encode($data),
	    // 		'error_date'	=> date('Y-m-d H:i:s',time()),
	    // 		'error_code'	=> $result['retcode'],
	    // 		'error_time'	=> time()
	    // 	);
	    // 	db('error')->insert($error);
	    // 	return array('code'=>0);
	    // }else
	    // 	return array('code'=>1,'data'=>$result);
	}

}