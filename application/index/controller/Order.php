<?php
namespace app\index\controller;

use app\admin\model\UserVip;
use app\admin\model\VipDuration;
use app\api\controller\WechatPay;
use app\admin\model\Order as OrderModel;
use app\index\controller\User;
use think\Session;

class Order extends LoginBase
{
    // 扫码支付
    public function payNow()
    {
        $payMoney = input('param.payMoney');
        $durationId = input('param.durationId');

        // 生成订单
        $orderSn = $this->createOrder($durationId, $payMoney);
        if(!$orderSn) {
            $this->assign('data',['code' => 0, 'msg' => '订单生成失败，请稍后重试！']);
            return view();
        }

        // 生成支付码
        $wechatPay = new WechatPay();
        $res = $wechatPay->pay($payMoney, $orderSn, $durationId);

        $this->assign('data',$res);
        $this->assign('orderSn',$orderSn);

        return view();
    }

    // 生成订单
    public function createOrder($durationId, $payMoney)
    {
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
        $res = $orderModel->CreateData($order);
        if($res['code'] == 1) {
            return $orderSn;
        } else {
            return false;
        }

    }

    /**
     * @param $orderSn
     * @param int $payType
     * vip_one_info  {"vip_start":1583463475,"vip_expire":1588733914} 2020/3/6 10:57:55   2020/5/6 10:58:34
     * vip_two_info  {"vip_start":1591412314,"vip_expire":1596680729} 2020/6/6 10:58:34   2020/8/6 10:25:29
     */

    // 支付成功
    public function paySuccess($orderSn, $payType = 1)
    {
        $vipLevel = [
            '2' => 'vip_one_info',
            '3' => 'vip_two_info',
            '4' => 'vip_three_info',
            'vip_one_info' => '2',
            'vip_two_info' => '3',
            'vip_three_info' => '4',
        ];
        $orderModel = new OrderModel();
        $userVipModel = new UserVip();
        $userId = session::get('user.user_id');

        // 订单修改
        $orderInfo = $orderModel->GetOneData(['order_sn' => $orderSn]);
        $orderData = [];
        $orderData['order_id'] = $orderInfo['order_id'];
        $orderData['order_paytime'] = time();
        $orderData['order_way'] = $payType;
        $orderData['order_status'] = 10;
        $orderModel->UpdateData($orderData);

        // 会员套餐
        $durationModel = new VipDuration();
        $durationData = $durationModel->GetOneData(['duration_id' => $orderInfo['order_key']]);
        $orderLevel = $durationData['duration_level'];

        // 会员信息数据
        $userVipData = [
            'vip_user' => $userId,
            'vip_one_info' => '',
            'vip_two_info' => '',
            'vip_three_info' => '',
        ];

        // 查看是否有会员信息
        $userInfo = $userVipModel->GetOneData(['vip_user' => $userId]);
        if($userInfo) {
            $userVipData['vip_id'] = $userInfo['vip_id'];

            // 查看用户在当前时间是否是会员
            $nowVip = $userVipModel->getUserVip($userId);
            $vipInfo = json_decode($userInfo[$vipLevel[$nowVip['level_id']]], true);
            if($nowVip) {
                 if($orderLevel == $nowVip['level_id']) {

                    $vipTime['vip_start'] = $vipInfo['vip_start'];
                    $vipTime['vip_expire'] = strtotime("+{$durationData['duration_number']} month", $vipInfo['vip_expire']);
                    $userVipData[$vipLevel[$orderLevel]] = json_encode($vipTime);

                    $userVipModel->UpdateData($userVipData);

                } elseif ($orderLevel > $nowVip['level_id']) {

                    $vipTimeH['vip_start'] = $orderData['order_paytime'];
                    $vipTimeH['vip_expire'] = strtotime("+{$durationData['duration_number']} month", $orderData['order_paytime']);
                    $userVipData[$vipLevel[$orderLevel]] = json_encode($vipTimeH);

                    // 低等级剩下的时间
                    $restTime = $vipInfo['vip_expire'] - $orderData['order_paytime'];
                    $vipTimeL['vip_start'] = $vipTimeH['vip_expire'];
                    $vipTimeL['vip_expire'] = $vipTimeH['vip_expire'] + $restTime;
                    $userVipData[$vipLevel[$nowVip['level_id']]] = json_encode($vipTimeL);

                    $userVipModel->UpdateData($userVipData);

                } elseif ($orderLevel < $nowVip['level_id']) {

                    $vipTime['vip_start'] = $vipInfo['vip_expire'];
                    $vipTime['vip_expire'] = strtotime("+{$durationData['duration_number']} month", $vipInfo['vip_expire']);
                    $userVipData[$vipLevel[$orderLevel]] = json_encode($vipTime);
                    $userVipModel->UpdateData($userVipData);
                }

            } else {
                // 存储会员信息
                $vipTime['vip_start'] = $orderInfo['order_paytime'];
                $vipTime['vip_expire'] = strtotime("+{$durationData['duration_number']} month", $orderData['order_paytime']);
                $userVipData[$vipLevel[$orderLevel]] = json_encode($vipTime);
                $userVipModel->UpdateData($userVipData);
            }
        } else {
            // 存储会员信息
            $vipTime['vip_start'] = $orderInfo['order_paytime'];
            $vipTime['vip_expire'] = strtotime("+{$durationData['duration_number']} month");
            $userVipData[$vipLevel[$orderLevel]] = json_encode($vipTime);

            $userVipModel->CreateData($userVipData);
        }

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
