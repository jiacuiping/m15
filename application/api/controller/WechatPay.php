<?php

namespace app\api\controller;

use think\Controller;

class WechatPay extends Controller
{
    private $notifyUrl = 'http://new.m15.ltd/index/user/privilege';
    private $appid = 'wx75502ffcfac1522e';
    private $mchid = '1516924601';
    private $apiKey = 'LfNuKo4VMI64iA8MJFrkGXYhfRggXBop';

    public function pay($payMoney, $orderSn, $goodId)
    {
        require ROOT_PATH . 'extend' . DS . 'wxpay/WxPay.Api.php'; //引入微信支付
        $input = new \WxPayUnifiedOrder();//统一下单
        $config = new \WxPayConfig();//配置参数

        $goods_name = '扫码支付' . $payMoney . '元'; //商品名称(自定义)
        // 商品描述
        $input->SetBody($goods_name);
        // 附加数据
//        $input->SetAttach($goods_name);
        // 商户订单号
        $input->SetOut_trade_no($orderSn);
        // 标价金额 (订单总金额，单位为分)
        $input->SetTotal_fee($payMoney * 100);//金额乘以100

//        $input->SetTime_start(date("YmdHis"));// 交易起始时间
//        $input->SetTime_expire(date("YmdHis", time() + 600));// 交易结束时间
//        $input->SetGoods_tag("test");// 订单优惠标记

        // 通知地址
        $input->SetNotify_url($this->notifyUrl); //回调地址
        // 交易类型
        $input->SetTrade_type("NATIVE");
        // 商品ID
        $input->SetProduct_id($goodId);

        $result = \WxPayApi::unifiedOrder($config, $input);
        if ($result['result_code'] == 'SUCCESS' && $result['return_code'] == 'SUCCESS') {
            $url = $result["code_url"];
            return ['code' => 1, 'url' => $url];
        } else {
            return ['code' => 0, 'msg' => $result['err_code_des']];
        }
    }


    // 查询订单
    public function orderquery($outTradeNo)
    {
        $config = array(
            'mch_id' => $this->mchid,
            'appid' => $this->appid,
            'key' => $this->apiKey,
        );
        //$orderName = iconv('GBK','UTF-8',$orderName);
        $unified = array(
            'appid' => $config['appid'],
            'mch_id' => $config['mch_id'],
            'out_trade_no' => $outTradeNo,
            'nonce_str' => self::createNonceStr(),
        );
        $unified['sign'] = self::getSign($unified, $config['key']);
        $responseXml = self::curlPost('https://api.mch.weixin.qq.com/pay/orderquery', self::arrayToXml($unified));
        $queryResult = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($queryResult === false) {
            die('parse xml error');
        }
        if ($queryResult->return_code != 'SUCCESS') {
            die($queryResult->return_msg);
        }
        $trade_state = $queryResult->trade_state;
        $data['code'] = $trade_state=='SUCCESS' ? 0 : 1;
        $data['data'] = $trade_state;
        $data['msg'] = $this->getTradeSTate($trade_state);
        $data['time'] = date('Y-m-d H:i:s');
        return $data;
    }

    public function getTradeSTate($str)
    {
        switch ($str){
            case 'SUCCESS';
                return '支付成功';
            case 'REFUND';
                return '转入退款';
            case 'NOTPAY';
                return '未支付';
            case 'CLOSED';
                return '已关闭';
            case 'REVOKED';
                return '已撤销（刷卡支付）';
            case 'USERPAYING';
                return '用户支付中';
            case 'PAYERROR';
                return '支付失败';
        }
    }

    public static function createNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 获取签名
     */
    public static function getSign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatQueryParaMap($params, false);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }

    protected static function formatQueryParaMap($paraMap, $urlEncode = false)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v) {
                if ($urlEncode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

    public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }

    public static function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}