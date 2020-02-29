<?php
namespace app\api\controller;
use think\Controller;
class WechatPay extends Controller
{
    public function index(){
        echo ROOT_PATH;die;
        header("Content-type:text/html;charset=utf-8");

        require ROOT_PATH . 'extend' . DS . 'wxpay/WxPay.Api.php'; //引入微信支付
        $input = new \WxPayUnifiedOrder();//统一下单
        $config = new \WxPayConfig();//配置参数

        //$paymoney = input('post.paymoney'); //支付金额
        $paymoney = 1; //测试写死
        $out_trade_no = 'WXPAY'.date("YmdHis"); //商户订单号(自定义)
        $goods_name = '扫码支付'.$paymoney.'元'; //商品名称(自定义)
        $input->SetBody($goods_name);
        $input->SetAttach($goods_name);
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($paymoney*100);//金额乘以100
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://www.xxx.com/wxpaynotify"); //回调地址
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id("123456789");//商品id
        $result = \WxPayApi::unifiedOrder($config, $input);

        if($result['result_code']=='SUCCESS' && $result['return_code']=='SUCCESS') {
            $url = $result["code_url"];
            $this->assign('url',$url);
        }else{
            $this->error('参数错误');
        }
        return view();
    }
}