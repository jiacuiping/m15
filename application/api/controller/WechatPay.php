<?php
namespace app\api\controller;



use NativePay;
use WxPayUnifiedOrder;

class WechatPay
{

    public function _initialize()
    {

    }
    
    //支付
    public function payQrcode()
    {
        $notify = new NativePay();
        $input = new WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no("sdkphp123456789".date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://paysdk.weixin.qq.com/notify.php");
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id("123456789");

        $result = $notify->GetPayUrl($input);
        $url2 = $result["code_url"];
        dump($url2);die;
    }


    //支付
    public function PayNow($order_sn)
    {
        //获取订单详情
        $order = $this->Order->GetOneData(array('order_sn'=>$order_sn));

        //验证订单状态
        if(!$order) return json_encode(array('code'=>0,'msg'=>'订单不存在'));
        if($order['order_ispay'] == 1) return json_encode(array('code'=>0,'msg'=>'订单已支付'));
        if($order['order_status'] > 9) return json_encode(array('code'=>0,'msg'=>'订单已支付'));
        if(strtotime($order['order_time']) < time()-18000) return json_encode(array('code'=>0,'msg'=>'订单已失效'));

        return $this->unifiedorder($order['order_money']*100,'商品购买',$this->User->GetField(array('user_id'=>$order['order_user']),'user_wxid'));
    }

    //统一下单
	public function unifiedorder($total_fee=0, $body='', $openid='ofbua5ZvdCwNdxFnbmKuaIh6lW9k')
    {
        $data = [
            'appid' => $this->appid,
            'body' => $body,
            'mch_id' => $this->mchid,
            'nonce_str' => $this->nonce_str,
            'notify_url' => $this->notify_url,
            'openid' => $openid,
            'out_trade_no' => time() . rand(11, 99),
            'sign_type' => $this->sign_type,
            'spbill_create_ip' => Request::instance()->ip(),
            'total_fee' => $total_fee * 1,
            'trade_type' => 'JSAPI',
        ];

        $data['sign'] = $this->GetSign($data);

        $xml = $this->arrayToXml($data);

        $result = $this->curl("https://api.mch.weixin.qq.com/pay/unifiedorder",$xml);

        $return = $this->xmlToArray($result);

        $this->package = 'prepay_id=' . $return['prepay_id'];
        $this->renCreatesign();
        $returns = [
            'code'  => 1,
            'appId' => $this->appid,
            'timeStamp' => (string)$this->timestamp,
            'nonceStr' => $this->nonce_str,
            'package' => $this->package,
            'signType' => $this->sign_type,
            'paySign' => $this->resign,
        ];

        return json_encode($returns);
    }

    //二次签名
    public function renCreatesign()
    {
        $build_data = [
            'appId' => $this->appid,
            'nonceStr' => $this->nonce_str,
            'package' => $this->package,
            'signType' => $this->sign_type,
            'timeStamp' => $this->timestamp,
            'key' => $this->key,
        ];
        $result = http_build_query($build_data);
        $put_data = str_replace('%3D', '=', $result); //格式化网址
        $signatrue = md5($put_data);

        $this->resign = strtoupper($signatrue);

    }

    //小程序支付完成修改订单
    public function CallBack($order_sn)
    {

    }

}