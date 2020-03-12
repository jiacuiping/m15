<?php
namespace app\index\controller;

use app\admin\model\KolOauth;
use app\index\controller\LoginBase;

use think\Session;


//commons
use app\api\controller\Index;
use app\api\controller\GetData;


class Local extends Base
{
    private $url = 'https://open.douyin.com';
    private $key = 'awnxxvwav6czf00h';
    private $secret = '19afaed4ee427baba3c4b48d6ed20c1b';

    public function index()
    {
        return view();
    }

    public function videoUpload()
    {
        $file = $_FILES['file'];
        $temp = $file['tmp_name'];
        $this->videoUploadPost($temp);
    }

    /**
     * 创建抖音视频
     * /video/create/
     *
     * open_id          string   通过/oauth/access_token/获取，用户唯一标志
     * access_token     string   调用/oauth/access_token/生成的token，此token需要用户授权。
     */
    public function videoCreatePost()
    {
        $openId = "7e6d30c1-ecf1-4569-abd8-1d36b7c7eb12";
        // 根据openid获取授权信息
        $kolOauth = new KolOauth();
        $oauthData = $kolOauth->GetOneData(['oauth_open_id' => $openId]);
        if (!$oauthData) {
            return ['code' => '0', 'msg' => "没有授权信息"];
        }

        if ($oauthData['oauth_access_token_expires_in'] < time()) { // 如果access_token过期
            $refreshRes = $this->refresh_token($oauthData['oauth_refresh_token'], $oauthData);
            if ($refreshRes['code'] == 0) {
                return ['code' => '0', 'msg' => $refreshRes['msg']];
            } else {
                $accessToken = $refreshRes['oauth_access_token'];
            }
        } else {
            $accessToken = $oauthData['oauth_access_token'];
        }

        if (!$accessToken) {
            return ['code' => '0', 'msg' => "没有获取到access_token"];
        }
        $url = $this->url . '/video/create/';

        $body = [

        ];
        $params = array(
            'open_id' => $openId,
            'access_token' => $accessToken,
            'body' => "{'@9VwKzuuES8gmaXS7ZohtSM780mzrPfCHPJJ4qwKvL1gaa/L/60zdRmYqig357zEBwmOi4mAd96+gp/pfsAZc7Q=='}",

        );

        $result = $this->curl_post($url, $params);
        dump($result);
        die;
        if ($result['data']['error_code'] == 0) {
            return $result['data'];
        } else {
            return false;
        }
    }

    /**
     * 上传视频到文件服务器
     * /video/upload/
     *
     * open_id          string   通过/oauth/access_token/获取，用户唯一标志
     * access_token     string   调用/oauth/access_token/生成的token，此token需要用户授权。
     */
    public function videoUploadPost($video)
    {
        $openId = "7e6d30c1-ecf1-4569-abd8-1d36b7c7eb12";
        $accessToken = "act.eab6d754cb7340a72ad113c26f4234b9ON3JbbedodfwRUUop2GSXrumI3G1";
        $url = $this->url . '/video/upload/';

        $params = array(
            'open_id' => $openId,
            'access_token' => $accessToken,
            'video' => $video,

        );

        $result = $this->curl_post($url, $params);
        dump($result);
        die;
        if ($result['data']['error_code'] == 0) {
            return $result['data'];
        } else {
            return false;
        }
    }

    //请求方法
    public function curl_post($url, $data = array())
    {
//		$data = array_merge(array('client_key'=>$this->key),$data);

        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //执行命令
        $json = curl_exec($curl);

        //关闭URL请求
        curl_close($curl);

        $result = json_decode($json, true);

        return $result;
    }

    public function curl_get($url, $data = array())
    {
        //初始化
        $ch = curl_init();
        //设置选项，包括URL
        $query = http_build_query($data);
        $url = $url . '?' . $query;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);

        $result = json_decode($output, true);

        return $result;
    }


}
