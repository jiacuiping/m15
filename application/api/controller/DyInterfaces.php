<?php

namespace app\api\controller;

use app\admin\model\KolOauth;
use app\admin\model\Publicopinion;
use app\admin\model\UserPublicopinionTime;
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
        // 查看是否已有
        $code = session::get('code');
        $url = $this->url . '/oauth/access_token/';

        $params = array(
            'client_key' => $this->key,
            'client_secret' => $this->secret,
            'code' => $code,
            'grant_type' => 'authorization_code',
        );

        $data = $this->curl_post($url, $params);
        if (isset($data['message']) && $data['message'] == 'success') {

            session::set('access_token', $data['data']['access_token']);
            session::set('open_id', $data['data']['open_id']);

            // 如果已有授权信息，更新
            $kolOauth = new KolOauth();
            $oauthData = $kolOauth->GetOneData(['oauth_open_id' => $data['data']['open_id']]);
            if ($oauthData) {
                if ($oauthData['oauth_access_token_expires_in'] < time()) {
                    // access_token已过期，重新获取
//                    $this->refresh_token($oauthData['oauth_refresh_token'], $oauthData);

                    // access_token已过期，更新
                    $oauthInfo = [
                        'oauth_access_token' => $data['data']['access_token'],
                        'oauth_access_token_expires_in' => time() + $data['data']['expires_in'],
                        'oauth_refresh_token' => $data['data']['refresh_token'],
                        'oauth_refresh_token_expires_in' => time() + 86400 * 30,
                        'oauth_time' => time()
                    ];
                    $kolOauth->CreateData($oauthInfo);
                } else {
                    // access_token未过期，续期
                    $this->refresh_token($oauthData['oauth_refresh_token'], $oauthData);
                }
            }
            return $data['data'];
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
    public function refresh_token($refresh_token, $oauthData)
    {
        // 查看refresh_token是否过期
        if ($oauthData['oauth_refresh_token_expires_in'] < time()) {
            return ['code' => '0', 'msg' => "refresh_token过期，需要用户重新授权"];
        }

        $url = $this->url . '/oauth/refresh_token/';

        $params = array(
            'client_key' => $this->key,
            'grant_type' => "refresh_token",
            'refresh_token' => $refresh_token,
        );

        $result = $this->curl_get($url, $params);
        if (isset($result['message']) && $result['message'] == 'success') {
            // 更新数据表信息
            $kolOauth = new KolOauth();
            $oauthInfo = [
                'oauth_id' => $oauthData['oauth_id'],
                'oauth_access_token' => $result['data']['access_token'],
                'oauth_access_token_expires_in' => time() + $result['data']['expires_in']
            ];
            $res = $kolOauth->UpdateData($oauthInfo);
            if ($res['code'] == 1) {
                return $res['data'];
            } else {
                return false;
            }
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
            'access_token' => $access_token,
            'open_id' => $openid
        );

        $result = $this->curl_post($url, $params);
        if (isset($result['message']) && $result['message'] == 'success') {
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
            'open_id' => $openId,
            'access_token' => $accessToken,
            'cursor' => 0,
            'count' => 10,
        );

        $result = $this->curl_get($url, $params);
        dump($result);
        die;
        if ($result['data']['error_code'] == 0) {
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
    public function fansDataGet($openId, $accessToken)
    {
        $url = $this->url . '/fans/data/';

        $params = array(
            'open_id' => $openId,
            'access_token' => $accessToken,
        );

        $result = $this->curl_get($url, $params);
        if ($result['data']['error_code'] == 0 && $result['data']['fans_data']) {
            $fansData = $result['data']['fans_data'];

            $allFansNum = $fansData['all_fans_num'];

            $data = [];

            // 性别舆情
            $sexData = $fansData['gender_distributions'];
            $sexInfo = [];
            foreach ($sexData as $value) {
                if ($value['item'] == 2) {
                    $sexInfo['女'] = round($value['value'] / $allFansNum, 2);
                } elseif ($value['item'] == 1) {
                    $sexInfo['男'] = round($value['value'] / $allFansNum, 2);
                }
            }
            $data['sexInfo'] = $sexInfo;

            // 年龄舆情
            $ageData = $fansData['age_distributions'];
            $ageInfo = [];
            foreach ($ageData as $value) {
                $key = $value["item"];
                $ageInfo["'$key'"] = round($value['value'] / $allFansNum, 2);
            }
            $data['ageInfo'] = $ageInfo;

            // 地区舆情-省
            $provinceData = $fansData['geographical_distributions'];
            $provinceInfo = [];
            foreach ($provinceData as $value) {
                $provinceInfo[$value["item"]] = round($value['value'] / $allFansNum, 2);
            }
            $data['provinceInfo'] = $provinceInfo;

            // 设备舆情
            $equipmentData = $fansData['device_distributions'];
            $equipmentInfo = [];
            foreach ($equipmentData as $value) {
                $equipmentInfo[$value["item"]] = round($value['value'] / $allFansNum, 2);
            }
            $data['equipmentInfo'] = $equipmentInfo;

            // 兴趣舆情
            $interestData = $fansData['interest_distributions'];
            $interestInfo = [];
            foreach ($interestData as $value) {
                $interestInfo[$value["item"]] = round($value['value'] / $allFansNum, 2);
            }
            $data['interestInfo'] = $interestInfo;

            // 流量舆情
            $trafficData = $fansData['flow_contributions'];
            $trafficInfo = [];
            foreach ($trafficData as $value) {
                if ($value['all_sum'] != 0) {
                    $trafficInfo[$value["flow"]] = round($value['fans_sum'] / $value['all_sum'], 2);
                }
            }
            $data['trafficInfo'] = $trafficInfo;

            // 活跃天数舆情
            $dateData = $fansData['active_days_distributions'];
            $dateInfo = [];
            foreach ($dateData as $value) {
                $dateInfo[$value["item"]] = round($value['value'] / $allFansNum, 2);
            }
            $data['dateInfo'] = $dateInfo;

            return $data;
        } else {
            return false;
        }
    }

    public function saveFansData($openId)
    {
        $kolOauth = new KolOauth();
        $publicOpinionModel = new Publicopinion();
        $accessToken = '';

        // 根据openid获取授权信息
        $oauthData = $kolOauth->GetOneData(['oauth_open_id' => $openId]);
        if (!$oauthData) {
            return ['code' => '0', 'msg' => "没有授权信息"];
        }

        if ($oauthData['oauth_access_token_expires_in'] < time()) { // 如果access_token过期
            $refreshRes = $this->refresh_token($oauthData['oauth_refresh_token'], $oauthData);
            if (!$refreshRes) {
                return ['code' => '0', 'msg' => "调用refresh_token失败"];
            } else {
                $accessToken = $refreshRes['oauth_access_token'];
            }
        } else {
            $accessToken = $oauthData['oauth_access_token'];
        }

        if (!$accessToken) {
            return ['code' => '0', 'msg' => "没有获取到access_token"];
        }

        $data = $this->fansDataGet($openId, $accessToken);
        $insert = array(
            'public_type' => 'kol',
            'public_key' => $oauthData['oauth_kol'],
            'public_age' => json_encode($data['ageInfo']),
            'public_sex' => json_encode($data['sexInfo']),
            'public_pro' => json_encode($data['provinceInfo']),
            'public_equipment' => json_encode($data['equipmentInfo']),
            'public_interest' => json_encode($data['interestInfo']),
            'public_traffic' => json_encode($data['trafficInfo']),
            'public_date' => json_encode($data['dateInfo'])
        );

        // 查看是否已有数据
        $fansInfo = $publicOpinionModel->GetOneData(['public_key' => $oauthData['oauth_kol']]);
        if ($fansInfo) {
            $insert['public_id'] = $fansInfo['public_id'];
            $insert['update_time'] = time();
            $publicOpinionModel->UpdateData($insert);
        } else {
            $insert['create_time'] = time();
            $insert['update_time'] = time();
            $publicOpinionModel->CreateData($insert);
        }
        return ['code' => '1', 'msg' => "完成！"];
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
            'open_id' => $openId,
            'access_token' => $accessToken,
            'cursor' => 0,
            'count' => 10,
        );

        $result = $this->curl_get($url, $params);
        dump($result);
        die;
        if ($result['data']['error_code'] == 0) {
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
            'open_id' => $openId,
            'access_token' => $accessToken,
            'cursor' => 0,
            'count' => 15,
        );

        $result = $this->curl_get($url, $params);
        if ($result['data']['error_code'] == 0) {
            return $result['data'];
        } else {
            return false;
        }
    }

    /**
     * 批量获取视频数据信息
     * /video/data/
     *
     * open_id          string   通过/oauth/access_token/获取，用户唯一标志
     * access_token     string   调用/oauth/access_token/生成的token，此token需要用户授权。
     */
    public function videoDataPost()
    {
        $openId = session::get('open_id');
        $accessToken = session::get('access_token');
        $url = $this->url . '/video/data/';

        $params = array(
            'open_id' => $openId,
            'access_token' => $accessToken,
            'body' => [
                'itemIds' => '@9VwKzuuES8gmaXS7ZohtSM780mzrPfCHPJJ4qwKvL1gaa/L/60zdRmYqig357zEBwmOi4mAd96+gp/pfsAZc7Q=='
            ]

        );

        $result = $this->curl_post($url, $params);
        if ($result['data']['error_code'] == 0) {
            return $result['data'];
        } else {
            return false;
        }
    }


    /**
     * 评论列表
     * /video/comment/list/
     *
     * open_id          string   通过/oauth/access_token/获取，用户唯一标志
     * access_token     string   调用/oauth/access_token/生成的token，此token需要用户授权。
     * cursor           integer  分页游标, 第一页请求cursor是0, response中会返回下一页请求用到的cursor, 同时response还会返回has_more来表明是否有更多的数据。
     * count            integer  每页数量
     * itemId           string   视频id
     */
    public function videoCommentListGet($itemId = '@9VwKzuuES8gmaXS7ZohtSM780mzrPfCHPJJ4qwKvL1gaa/L/60zdRmYqig357zEBwmOi4mAd96+gp/pfsAZc7Q==')
    {
        /*$itemId = "6794358120995900686";
        $interfaces = new Interfaces();
        $res = $interfaces->GetVideoComment($itemId,$page=1);
        dump($res);die;*/

        $openId = session::get('open_id');
        $accessToken = session::get('access_token');
        $url = $this->url . '/video/comment/list/';

        $params = array(
            'open_id' => $openId,
            'access_token' => $accessToken,
            'cursor' => 0,
            'count' => 15,
            'item_id' => $itemId
        );

        $result = $this->curl_get($url, $params);
        dump($result);
        die;
        if (isset($result['data']['error_code']) == 0) {
            return $result['data'];
        } else {
            return false;
        }
    }

    /**
     * 评论回复列表
     * /video/comment/reply/list/
     *
     * open_id          string   通过/oauth/access_token/获取，用户唯一标志
     * access_token     string   调用/oauth/access_token/生成的token，此token需要用户授权。
     * cursor           integer  分页游标, 第一页请求cursor是0, response中会返回下一页请求用到的cursor, 同时response还会返回has_more来表明是否有更多的数据。
     * count            integer  每页数量
     * itemId           string   视频id
     * commentId        string   评论id
     */
    public function videoCommentReplyListGet($commentId)
    {
        $openId = session::get('open_id');
        $accessToken = session::get('access_token');
        $url = $this->url . '/video/comment/reply/list/';

        $params = array(
            'open_id' => $openId,
            'access_token' => $accessToken,
            'cursor' => 0,
            'count' => 15,
            'commentId' => $commentId
        );

        $result = $this->curl_get($url, $params);
        if (isset($result['data']['error_code']) == 0) {
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
            'access_token' => $data['access_token'],
        );

        $result = $this->curl_get($url, $params);
        dump($result);
        die;
        if (isset($result['data']['error_code']) == 0) {
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
            'client_key' => $this->key,
            'client_secret' => $this->secret,
            'grant_type' => 'client_credential',
        );

        $result = $this->curl_post($url, $params);
        if (isset($result['message']) && $result['message'] == 'success') {
            return $result['data'];
        } else {
            return false;
        }
    }

    /**
     * 发送私信消息
     * /im/message/send/
     * 注意：调用本接口，需要授权的抖音用户是企业号
     *
     * open_id          string   通过/oauth/access_token/获取，用户唯一标志
     * access_token     string   调用/oauth/access_token/生成的token，此token需要用户授权。
     * body: toUserId,messageType,content      string Available values : client_credential
     */
    public function imMessageSendPost($toUserId = '923f53dc-8941-4b3e-bbd5-4a96e055525a', $content = 'hello')
    {
        $url = $this->url . '/im/message/send/';
        $openId = session::get('open_id');
        $accessToken = session::get('access_token');

        $params = array(
            'open_id' => $openId,
            'access_token' => $accessToken,
            'body' => [
                'toUserId' => $toUserId,
                'messageType' => 'TEXT',
                'content' => $content,
            ]
        );
        dump($params);

        $result = $this->curl_post($url, $params);
        dump($result);
        die;
        if (isset($result['message']) && $result['message'] == 'success') {
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