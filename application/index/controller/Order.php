<?php
namespace app\index\controller;

use app\admin\model\UserVip;
use app\admin\model\VipDuration;
use app\api\controller\WechatPay;
use app\admin\model\Order as OrderModel;
use think\Session;

class Order extends LoginBase
{
    // 扫码支付
    public function payNow()
    {
        $payMoney = 0.01;//input('param.payMoney');
        $durationId = input('param.durationId');

        // 生成订单
        $userId = session::get('user.user_id');
        $orderSn = GetOrderSn($userId, 0);
        $order = [
            'order_user' => $userId,
            'order_sn' => $orderSn,
            'order_type' => 0,
            'order_key' => $durationId,
            'order_price' => $payMoney,
            'order_paytime' => time(),
            'order_payprice' => $payMoney,
//            'order_way' => 1,
            'order_status' => 0,
            'order_time' => time(),
        ];
        $orderModel = new OrderModel();
        $orderModel->CreateData($order);

        $wechatPay = new WechatPay();
        $res = $wechatPay->pay($payMoney, $orderSn, $durationId);
        $this->assign('data',$res);
        $this->assign('orderSn',$orderSn);

        return view();
    }

    // 支付成功
    public function paySuccess($orderSn)
    {
        // 订单修改
        $orderModel = new OrderModel();
        $orderInfo = $orderModel->GetOneData(['order_sn' => $orderSn]);
        $orderData = [];
        $orderData['order_id'] = $orderInfo['order_id'];
        $orderData['order_paytime'] = time();
        $orderData['order_way'] = 1;
        $orderData['order_status'] = 10;
        $orderModel->UpdateData($orderData);

        // 套餐
        $durationModel = new VipDuration();
        $durationData = $durationModel->GetOneData(['duration_id' => $orderInfo['order_key']]);
        $vipExpire = strtotime("+{$durationData['duration_number']} month");

        // user_vip
        $userVipModel = new UserVip();
        $userId = session::get('user.user_id');

        // 查看是否已有
        $userInfo = $userVipModel->GetOneData(['vip_user' => $userId]);
        $userVipData = [];
        if($userInfo) {
            $userVipData['vip_id'] = $userInfo['vip_id'];
            if($userInfo['vip_expire'] == 0) {

            } else {

            }
        } else {
            $userVipData = [
                'vip_user' => $userId,
                'vip_level' => $orderInfo['order_key'],
                'vip_start' => $orderInfo['order_paytime'],
                'vip_expire' => $vipExpire,
                'vip_order' => $orderInfo['order_id'],
                'vip_grade' => '',
                'vip_time' => time(),
            ];
        }

        $userVipModel->CreateData($userVipData);

        $this->redirect('User/privilege', ['type' => 'members']);
    }

    // 查询订单
    public function queryWxOrder($orderSn)
    {
        $wxPayModel = new WechatPay();
        $result = $wxPayModel->orderquery($orderSn);
        return $result;
    }
}
