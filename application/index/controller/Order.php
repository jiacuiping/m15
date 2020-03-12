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
        if (!$orderSn) {
            $this->assign('data', ['code' => 0, 'msg' => '订单生成失败，请稍后重试！']);
            return view();
        }

        // 生成支付码
        $wechatPay = new WechatPay();
        $res = $wechatPay->pay($payMoney, $orderSn, $durationId);

        $this->assign('data', $res);
        $this->assign('orderSn', $orderSn);

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
        if ($res['code'] == 1) {
            return $orderSn;
        } else {
            return false;
        }

    }


    // 支付成功
    public function paySuccess($orderSn, $payType = 1)
    {
        $orderModel = new OrderModel();
        $userVipModel = new UserVip();
        $userId = session::get('user.user_id');
        $vipLevel = [
            'vip_one_info' => '2',
            'vip_two_info' => '3',
            'vip_three_info' => '4',
            '2' => 'vip_one_info',
            '3' => 'vip_two_info',
            '4' => 'vip_three_info',
        ];

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
        if ($userInfo) {
            $userVipData['vip_id'] = $userInfo['vip_id'];

            // 查看用户在当前时间是否是会员
            $userNowVip = $userVipModel->getUserVip($userId);
            if ($userNowVip) {

                // 当前会员信息
                $nowVip = $userNowVip['vip'];
                $vipInfo = $userNowVip['time'];

//                $vipInfo = json_decode($userInfo[$vipLevel[$nowVip['level_id']]], true);

                if ($orderLevel == 2) { // 此次下单为黄金版
                    if ($orderLevel == $nowVip['level_id']) {

                        $vipTime['vip_start'] = $vipInfo['vip_start'];
                        $vipTime['vip_expire'] = strtotime("+{$durationData['duration_number']} month", $vipInfo['vip_expire']);

                    } else {
                        $vipTime['vip_start'] = $orderInfo['order_paytime'];
                        $vipTime['vip_expire'] = strtotime("+{$durationData['duration_number']} month", $orderData['order_paytime']);
                    }

                    $userVipData['vip_one_info'] = json_encode($vipTime);

                } elseif ($orderLevel == 3) { // 此次下单为钻石版
                    if ($orderLevel == $nowVip['level_id']) {
                        // 如果当前会员是钻石版，续期
                        $vipTime1['vip_start'] = $vipInfo['vip_start'];
                        $vipTime1['vip_expire'] = strtotime("+{$durationData['duration_number']} month", $vipInfo['vip_expire']);
                        $userVipData['vip_two_info'] = json_encode($vipTime1);

                        if($userInfo['vip_one_info']) {
                            // 黄金版
                            // 黄金版剩下的时间
                            $restTime = $vipInfo['vip_expire'] - $orderData['order_paytime'];
                            $vipTime2['vip_start'] = $vipTime1['vip_expire'];
                            $vipTime2['vip_expire'] = $vipTime2['vip_start'] + $restTime;
                            $userVipData['vip_one_info'] = json_encode($vipTime2);
                        }
                    } else {
                        // 如果当前会员是黄金版，先享受钻石版服务，黄金版延后
                        // 钻石版
                        $vipTime1['vip_start'] = $orderInfo['order_paytime'];
                        $vipTime1['vip_expire'] = strtotime("+{$durationData['duration_number']} month", $orderData['order_paytime']);

                        // 黄金版
                        // 黄金版剩下的时间
                        $restTime = $vipInfo['vip_expire'] - $orderData['order_paytime'];
                        $vipTime2['vip_start'] = $vipTime1['vip_expire'];
                        $vipTime2['vip_expire'] = $vipTime2['vip_start'] + $restTime;

                        $userVipData['vip_two_info'] = json_encode($vipTime1);
                        $userVipData['vip_one_info'] = json_encode($vipTime2);
                    }

                } elseif ($orderLevel == 4) { // 此次下单为旗舰版
                    if ($orderLevel == $nowVip['level_id']) {

                        // 如果当前会员是旗舰版，续期
                        $vipTime1['vip_start'] = $vipInfo['vip_start'];
                        $vipTime1['vip_expire'] = strtotime("+{$durationData['duration_number']} month", $vipInfo['vip_expire']);
                        $userVipData['vip_three_info'] = json_encode($vipTime1);

                        if($userInfo['vip_two_info']) {
                            $vip_one_info = json_decode($userInfo['vip_one_info'], true);
                            // 钻石版
                            // 钻石版剩下的时间
                            $restTime = $vip_one_info['vip_expire'] - $vip_one_info['vip_start'];
                            $vipTime2['vip_start'] = $vipTime1['vip_expire'];
                            $vipTime2['vip_expire'] = $vipTime2['vip_start'] + $restTime;
                            $userVipData['vip_one_info'] = json_encode($vipTime2);
                        }

                        if($userInfo['vip_one_info']) {
                            $vip_two_info = json_decode($userInfo['vip_twp_info'], true);
                            // 黄金版
                            // 黄金版剩下的时间
                            $restTime = $vip_two_info['vip_expire'] - $vip_two_info['vip_start'];
                            if(isset($vipTime2)) {
                                $vipTime3['vip_start'] = $vipTime2['vip_expire'];
                                $vipTime3['vip_expire'] = $vipTime3['vip_start'] + $restTime;
                            } else {
                                $vipTime3['vip_start'] = $vipTime1['vip_expire'];
                                $vipTime3['vip_expire'] = $vipTime3['vip_start'] + $restTime;
                            }

                            $userVipData['vip_one_info'] = json_encode($vipTime3);
                        }

                    } else if ($nowVip['level_id'] == 3){
                        // 如果当前会员是钻石版，先享受旗舰版服务，钻石版延后
                        // 旗舰版
                        $vipTime1['vip_start'] = $orderInfo['order_paytime'];
                        $vipTime1['vip_expire'] = strtotime("+{$durationData['duration_number']} month", $orderData['order_paytime']);
                        $userVipData['vip_three_info'] = json_encode($vipTime1);

                        // 钻石版
                        // 钻石版剩下的时间
                        $restTime = $vipInfo['vip_expire'] - $orderData['order_paytime'];
                        $vipTime2['vip_start'] = $vipTime1['vip_expire'];
                        $vipTime2['vip_expire'] = $vipTime2['vip_start'] + $restTime;
                        $userVipData['vip_two_info'] = json_encode($vipTime2);

                        // 是否有黄金版
                        if($userInfo['vip_one_info']) {
                            $vip_one_info = json_decode($userInfo['vip_one_info'], true);

                            // 黄金版剩下的时间
                            $restTime = $vip_one_info['vip_expire'] - $vip_one_info['vip_start'];
                            $vipTime3['vip_start'] = $vipTime2['vip_expire'];
                            $vipTime3['vip_expire'] = $vipTime3['vip_start'] + $restTime;
                            $userVipData['vip_one_info'] = json_encode($vipTime3);
                        }

                    } else if ($nowVip['level_id'] == 2){
                        // 如果当前会员是黄金版，先享受旗舰版服务，黄金版延后

                        // 旗舰版
                        $vipTime1['vip_start'] = $orderInfo['order_paytime'];
                        $vipTime1['vip_expire'] = strtotime("+{$durationData['duration_number']} month", $orderData['order_paytime']);

                        // 黄金版
                        // 黄金版剩下的时间
                        $restTime = $vipInfo['vip_expire'] - $orderData['order_paytime'];
                        $vipTime2['vip_start'] = $vipTime1['vip_expire'];
                        $vipTime2['vip_expire'] = $vipTime2['vip_start'] + $restTime;

                        $userVipData['vip_three_info'] = json_encode($vipTime1);
                        $userVipData['vip_one_info'] = json_encode($vipTime2);
                    }
                }

                $userVipModel->UpdateData($userVipData);

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
