<?php
namespace app\api\controller;

use app\api\controller\Base;

use ETaobao\Factory;



class TbInterfaces extends Base
{
	//推广位ID：mm_24491795_415850079_108341300287
	//商品ID：611737531041
	//选品库ID：20160251

	//4c3dc023-b906-bc4b-6eb6-176c84bfffad

	private $OrderMan = '9thXcsMK08nMrdOm1UQDZFk9Ju5uuIzN';

	private $config = [
		    'appkey' => '28325600',
		    'secretKey' => '3a2a808089b0908c170eb7f0bd019365',
		    'format' => 'json',
		    'session' => '',//授权接口（sc类的接口）需要带上
		    'sandbox' => false,
		];


	//获取商品信息
	public function GetGoodsInfo($gid)
	{
		$param = [
		   'num_iids'	=> $gid,
		   'platform'	=> 1,
		   'ip'			=> $_SERVER["REMOTE_ADDR"],
		];

		return $this->RequestApis('getInfo',$param);
	}

	//高佣转链接接口
	public function HighCommission($gid)
	{
		$url = "http://api.tbk.dingdanxia.com/tbk/id_privilege";
		// $url = "http://api.tbk.dingdanxia.com/tbk/item_detailinfo";

		$param = array('apikey'	=> $this->OrderMan,'id' => $gid);

		return $this->curl($url,$param);
	}


	//统一请求接口
	public function RequestApis($api,$param,$issession=false)
	{
		if($issession == true){
			//请求的接口需授权
		}

		//实例化包
		$alibabapi = Factory::Tbk($this->config);

		$res = ObjectToArray($alibabapi->item->$api($param));

		return isset($res['results']) ? array('code'=>1,'msg'=>'请求成功','data'=>$res['results']) : array('code'=>0,'msg'=>$res['sub_msg'],'data'=>array());
	}

	//请求方法
	public function curl($url,$data=array())
	{
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

	    return json_decode($json,true);
	}


	// XML转换成数组
    private function simplexml_obj2array($obj)
    {
        if( count($obj) >= 1 )
        {
            $result = $keys = array();

            foreach( $obj as $key=>$value)
            {   
                isset($keys[$key]) ? ($keys[$key] += 1) : ($keys[$key] = 1);

                if( $keys[$key] == 1 )
                {
                    $result[$key] = $this->simplexml_obj2array($value);
                }
                elseif( $keys[$key] == 2 )
                {
                    $result[$key] = array($result[$key], $this->simplexml_obj2array($value));
                }
                else if( $keys[$key] > 2 )
                {
                    $result[$key][] = $this->simplexml_obj2array($value);
                }
            }
            return $result;
        }
        else if( count($obj) == 0 )
        {
            return (string)$obj;
        }
    }
}