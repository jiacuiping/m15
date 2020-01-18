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



class TbInterfaces extends Base
{
	private $key = 'awnxxvwav6czf00h';
	private $url = 'https://open.douyin.com';
	private $secret = '19afaed4ee427baba3c4b48d6ed20c1b';

	//淘宝客API测试接口
	public function Index()
	{
		header("Content-type: text/html; charset=utf-8");
		include "../extend/taobaosdk/TopSdk.php";

		//将下载到的SDK里面的TopClient.php的$gatewayUrl的值改为沙箱地址:http://gw.api.tbsandbox.com/router/rest
		//正式环境时需要将该地址设置为：http://gw.api.taobao.com/router/rest



		// $c = new TopClient;
		// $c->appkey = $appkey;
		// $c->secretKey = $secret;
		// $req = new TbkItemRecommendGetRequest;
		// $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url");
		// $req->setNumIid("123");
		// $req->setCount("20");
		// $req->setPlatform("1");
		// $resp = $c->execute($req);





		$c = new \TopClient;
		$c->appkey = "28272469"; // 可替换为您的沙箱环境应用的AppKey
		$c->secretKey = "002748e6276e88d4ceeabffb286360b5"; // 可替换为您的沙箱环境应用的AppSecret
		//$sessionkey= "test";  // 必须替换为沙箱账号授权得到的真实有效SessionKey

		$req = new \TbkItemRecommendGetRequest;
		$req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url");
		$req->setNumIid("123");
		$req->setCount("20");
		$req->setPlatform("1");
		$resp = $c->execute($req);

		echo "api result:";
		print_r($resp);
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

	    $result = json_decode($json,true);

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