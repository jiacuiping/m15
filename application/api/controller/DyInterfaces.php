<?php
namespace app\api\controller;

use app\api\controller\Base;
use think\Cookie;
use think\Session;

//models


class DyInterfaces extends Base
{
    /**
     * Client Key: awnxxvwav6czf00h
     * Client Secret: 19afaed4ee427baba3c4b48d6ed20c1b
     */

    private $url = 'https://open.douyin.com';
	private $key = 'awnxxvwav6czf00h';
	private $secret = '19afaed4ee427baba3c4b48d6ed20c1b';


    //获取登陆二维码
    /**
     * 获取授权码(code)
     * /platform/oauth/connect/
     *
     * client_key       string 应用唯一标识
     * response_type    string 填写code Available values : code
     * scope            string 应用授权作用域,多个授权作用域以英文逗号（,）分隔
     * redirect_uri     string 授权成功后的回调地址，必须以http/https开头。
     * state            string 用于保持请求和回调的状态
     */
    public function GetLoginQrcode()
    {
        $url = $this->url . '/platform/oauth/connect';
        $redirect_uri = "http://new.m15.ltd/index/login/dyqrcode";
        $scope = "aweme.share,user_info,video.create,video.delete,video.data,video.list,toutiao.video.create,toutiao.video.data,video.comment,im,enterprise.data,following.list,fans.list,fans.data";
        $url = $url . "?client_key=" . $this->key . "&response_type=code&scope=" . $scope . "&redirect_uri=" . $redirect_uri . "&state=1";
        header('Location:' . $url);
        exit();
    }


    /**
     * 获取access_token
     * /oauth/access_token/
     *
     * client_key       string 应用唯一标识
     * client_secret    string 应用唯一标识对应的密钥
     * code             string 授权码
     * grant_type       string 写死"authorization_code"即可 Available values : authorization_code
     */
    public function get_access_token()
    {
        $code = session::get('code');
        $url = $this->url . '/oauth/access_token/';

        $params = array(
            'client_key'		=> $this->key,
            'client_secret' 	=> $this->secret,
            'code'		        => $code,
            'grant_type'		=> 'authorization_code',
        );

        //$url = $url . "?client_key={$this->key}&client_secret={$this->secret}&code={$code}&grant_type=authorization_code";
        $data = $this->curl_post($url, $params);
        if(isset($data['message']) && $data['message'] == 'success') {
            session::set('access_token',$data['data']['access_token']);
            session::set('open_id',$data['data']['open_id']);
            session::set('refresh_token',$data['data']['refresh_token']);
            return $data['data'];
        } else {
            return false;
        }
    }


    /**
     * 获取用户信息
     * /oauth/userinfo/
     *
     * access_token string
     * open_id
     */
    public function getUserInfo($access_token, $openid)
    {
        $url = $this->url . '/oauth/userinfo/';

        $params = array(
            'access_token'  => $access_token,
            'open_id'       => $openid
        );

        $result = $this->curl_post($url, $params);
        if(isset($result['message']) && $result['message'] == 'success') {
            return $result['data'];
        } else {
            return false;
        }
    }

    /**
     * 粉丝列表
     * /fans/list/
     *
     * open_id          string   通过/oauth/access_token/获取，用户唯一标志
     * access_token     string   调用/oauth/access_token/生成的token，此token需要用户授权。
     * cursor           integer  分页游标, 第一页请求cursor是0, response中会返回下一页请求用到的cursor, 同时response还会返回has_more来表明是否有更多的数据。
     * count            integer  每页数量
     */
    public function GetFansList()
    {
        $openId = session::get('open_id');
        $accessToken = session::get('access_token');
        $url = $this->url . '/fans/list/';

        $params = array(
            'open_id'		=> $openId,
            'access_token' 	=> $accessToken,
            'cursor'		=> 0,
            'count'		    => 10,
        );

        $result = $this->curl_get($url, $params);
        dump($result);die;
        if(isset($result['data']['error_code']) == 0) {
            return $result['data'];
        } else {
            return false;
        }
    }


    /**
     * 获取粉丝统计数据
     * /fans/data/
     *
     * open_id string 通过/oauth/access_token/获取，用户唯一标志
     * access_token string 调用/oauth/access_token/生成的token，此token需要用户授权。
     */
    public function GetKolFansInfo()
    {
        $openId = session::get('open_id');
        $accessToken = session::get('access_token');
        $url = $this->url . '/fans/data/';

        $params = array(
            'open_id'		=> $openId,
            'access_token' 	=> $accessToken,
        );

        $result = $this->curl_get($url, $params);
        dump($result);die;
        if(isset($result['data']['error_code']) == 0) {
            return $result['data'];
        } else {
            return false;
        }
    }

    /**
     * 获取用户的关注列表
     * /following/list/
     *
     * open_id          string   通过/oauth/access_token/获取，用户唯一标志
     * access_token     string   调用/oauth/access_token/生成的token，此token需要用户授权。
     * cursor           integer  分页游标, 第一页请求cursor是0, response中会返回下一页请求用到的cursor, 同时response还会返回has_more来表明是否有更多的数据。
     * count            integer  每页数量
     */
    public function followingListGet()
    {

        $openId = session::get('open_id');
        $accessToken = session::get('access_token');
        $url = $this->url . '/following/list/';

        $params = array(
            'open_id'		=> $openId,
            'access_token' 	=> $accessToken,
            'cursor'		=> 0,
            'count'		    => 10,
        );

        $result = $this->curl_get($url, $params);
        dump($result);die;
        if(isset($result['data']['error_code']) == 0) {
            return $result['data'];
        } else {
            return false;
        }
    }

    /**
     * 查询授权账号视频数据
     * /video/list/
     *
     * open_id          string   通过/oauth/access_token/获取，用户唯一标志
     * access_token     string   调用/oauth/access_token/生成的token，此token需要用户授权。
     * cursor           integer  分页游标, 第一页请求cursor是0, response中会返回下一页请求用到的cursor, 同时response还会返回has_more来表明是否有更多的数据。
     * count            integer  每页数量
     */
    public function videoListGet()
    {
        $openId = session::get('open_id');
        $accessToken = session::get('access_token');
        $url = $this->url . '/video/list/';

        $params = array(
            'open_id'		=> $openId,
            'access_token' 	=> $accessToken,
            'cursor'		=> 0,
            'count'		    => 10,
        );

        $result = $this->curl_get($url, $params);
        dump($result);die;
        if(isset($result['data']['error_code']) == 0) {
            return $result['data'];
        } else {
            return false;
        }
    }

    /**
     * 获取实时热点词
     * /hotsearch/sentences/
     *
     * access_token     string   调用/oauth/client_token/生成的token，此token不需要用户授权。
     */
    public function hotsearchSentencesGet()
    {
        $data = $this->get_client_token();
        $url = $this->url . '/hotsearch/sentences/';

        $params = array(
            'access_token' 	=> $data['access_token'],
        );

        $result = $this->curl_get($url, $params);
        dump($result);die;
        if(isset($result['data']['error_code']) == 0) {
            return $result['data'];
        } else {
            return false;
        }
    }



    /**
     * 刷新access_token
     * /oauth/refresh_token/
     *
     * client_key       string 应用唯一标识
     * grant_type       string 填refresh_token Available values : refresh_token
     * refresh_token    string 填写通过access_token获取到的refresh_token参数
     */
    public function get_refresh_token()
    {

        $url = $this->url . '/platform/oauth/connect/';

        $params = array(
            'client_key'	    => $this->key,
            'grant_type' 	    => "refresh_token",
            'refresh_token'		=> "",
        );

        $result = $this->curl_post($url, $params);
        if(isset($result['message']) && $result['message'] == 'success') {
            return $result['data'];
        } else {
            return false;
        }
    }


    /**
     * 生成client_token
     * /oauth/client_token/
     *
     * client_key       string 应用唯一标识
     * client_secret    string 应用唯一标识对应的密钥
     * grant_type       string Available values : client_credential
     */
    public function get_client_token()
    {
        $url = $this->url . '/oauth/client_token/';

        $params = array(
            'client_key'		=> $this->key,
            'client_secret' 	=> $this->secret,
            'grant_type'		=> 'client_credential',
        );

        $result = $this->curl_post($url, $params);
        if(isset($result['message']) && $result['message'] == 'success') {
            return $result['data'];
        } else {
            return false;
        }
    }



	//请求方法
	public function curl_post($url,$data=array())
	{
//		$data = array_merge(array('client_key'=>$this->key),$data);

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

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //执行命令
	    $json = curl_exec($curl);

        //关闭URL请求
	    curl_close($curl);

	    $result = json_decode($json,true);

	    return $result;
	}

    public function curl_get($url,$data=array())
    {
        //初始化
        $ch = curl_init();
        //设置选项，包括URL
        $query = http_build_query($data);
        $url = $url.'?'. $query;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);

        $result = json_decode($output,true);

        return $result;
    }
}