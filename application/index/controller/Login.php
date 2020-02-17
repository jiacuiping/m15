<?php
namespace app\index\controller;

use app\admin\model\UserAccount;
use app\api\controller\Interfaces;
use app\index\controller\Base;

use think\Session;

//models
use app\admin\model\Mcn;
use app\admin\model\User;
use app\admin\model\UserVip;
use app\admin\model\VipLevel;
use app\admin\model\LoginLog;
use app\admin\model\UserLogin;
use app\admin\model\Certification;

//interfaces
use app\api\controller\DyInterfaces;

class Login extends Base
{
	public function __construct()
	{
		parent::__construct();

        $this->User = new User;
        $this->Vip = new UserVip;
        $this->Login = new UserLogin;
        $this->VipLevel = new VipLevel;
		$this->Certification = new Certification;
	}

	//手机号登陆方法
    public function login()
    {
    	//先根据当前时间戳判断该用户是否有会员信息符合条件，如没有，则先修改user表中的当前vip等级，然后再存入session信息
    	if(request()->isPost()){
            //接收手机号数据
            $mobile = input('post.mobile');
            if($mobile == '') return array('code'=>0,'msg'=>'用户名不能为空');
            //接收密码数据
            $password = input('post.password');
            if($password == '') return array('code'=>0,'msg'=>'密码不能为空');
            //获取登陆信息
            $login = $this->Login->GetOneData(array('login_mobile'=>$mobile));
            //登陆信息数据判断
            if(!$login) return array('code'=>0,'msg'=>'该用户不存在');
            //密码验证
            if(!PasswordCheck($password,$login['login_password'],$login['login_str'])) return array('code'=>0,'msg'=>'密码不正确');
            //获取用户主信息
            $user = $this->User->GetOneDataById($login['login_user']);

            //账号数据判断
            if(!$user) return array('code'=>0,'msg'=>'账号异常，请联系客服');
            //账号状态判断
            if($user['user_status'] != 1) return array('code'=>0,'msg'=>'该用户已被停用，请联系客服');

            $this->delUser($user, 2);
            //返回成功
            return array('code'=>1,'msg'=>'登陆成功，即将跳转');

    	}else{

    		return view();
    	}
    }


    //获取抖音登录二维码
    public function GetDyQrcode()
    {
        $DyInterfaces = new DyInterfaces;

        $QrCode = $DyInterfaces->GetLoginQrcode();

    }

    //抖音扫码登录
    public function dyqrcode()
    {
        $kolModel = new \app\admin\model\Kol();
        $accountModel = new UserAccount();
        $DyInterfaces = new DyInterfaces;

        // 把code存入session
        $code = input('get.code');
        session::set('code',input('get.code'));



        // 获取access_token
        $data = $DyInterfaces->get_access_token();
        if(!$data) {
            $this->error('登录失败，请稍后再试！','index/index');
        }

        // 获取抖音用户信息
        $userDyInfo = $DyInterfaces->getUserInfo($data['access_token'], $data['open_id']);
        if(!$userDyInfo) {
            $this->error('登录失败，请稍后再试！','index/index');
        }
        // 把信息存入session
        session::set('dy_user',$userDyInfo);

        // kol信息
        $kolData = [
            'kol_platform' => 1,
            'kol_nickname' => $userDyInfo['nickname'],
            'kol_avatar' => $userDyInfo['avatar'],
            'kol_sex' => $userDyInfo['gender'],
            'kol_countries' => $userDyInfo['country'],
            'kol_province' => $userDyInfo['province'],
            'kol_city' => $userDyInfo['city'],
            'kol_open_id' => $userDyInfo['open_id'],
            'kol_union_id' => $userDyInfo['union_id'],
            'kol_account_role' => $userDyInfo['e_account_role'],
        ];


        // 查看kol用户是否已经存在
        $kolInfo = $kolModel->GetOneData(['kol_open_id' => $data['open_id']]);
        if($kolInfo) {
            // 存在，更新
            $kolData['kol_id'] = $kolInfo['kol_id'];
            $kolRes = $kolModel->UpdateData($kolData);
            // 把信息存入session
            session::set('kolInfo',$kolRes['data']);

            // 查看user表是否有数据
            $kolUser = $accountModel->GetOneData(['account_kol' => $kolInfo['kol_id']]);
            if($kolUser) {
                $userInfo = $this->User->GetOneData(['user_id' => $kolUser['account_user']]);

                //账号数据判断
                if(!$userInfo) $this->error('账号异常，请联系客服','index/index');

                //账号状态判断
                if($userInfo['user_status'] != 1) $this->error('该用户已被停用，请联系客服');

                $this->delKol($kolInfo, 2);
                $this->delUser($userInfo, 5);
                $this->success('登录成功', 'index/index');
            } else {

                // 跳转到绑定手机号页面
                $this->redirect('login/bindMobile');
            }

        } else {
            // 存储kol信息
            $saveRes = $kolModel->CreateData($kolData);
            if($saveRes['code'] == 1) {
                // 把信息存入session
                session::set('kolInfo',$saveRes['data']);
                // 跳转到绑定手机号页面
                $this->redirect('login/bindMobile');
            } else {
                $this->error('账号异常，请联系客服','index/index');
            }
        }
    }



    // 绑定手机号
    public function bindMobile()
    {
        if(request()->isPost()){
            $kolInfo = session::get('kolInfo');
            $accountModel = new UserAccount();
            $userLoginModel = new UserLogin();

            $sms_code = session::get('sms_code');
            //接收数据
            $data = input('post.');
            $mobile = $data['mobile'];

            // 验证数据
            if(!CheckMobile($data['mobile'])) {
                return ['code'=>0,'msg'=>'请输入正确的手机号码'];
            }

            /*if($sms_code !== $data['check_code']) {
                return ['code'=>0,'msg'=>'验证码不正确'];
            }*/

            if($data['pass'] !== $data['repass']) {
                return ['code'=>0,'msg'=>'两次输入密码不正确'];
            }

            // 查询手机号是否已经存在
            $userInfo = $this->User->GetOneData(['user_mobile' => $mobile]);
            if($userInfo) {
                return ['code' => 0, 'msg' => '该手机号已存在'];
            }

            // 补充信息
            // user 信息
            $userData = [
                'user_name' => $kolInfo['kol_nickname'],
                'user_type' => 1,
                'user_avatar' => $kolInfo['kol_avatar'],
                'user_sex' => $kolInfo['kol_sex'],
                'user_open_id' => $kolInfo['kol_open_id'],
                'user_union_id' => $kolInfo['kol_union_id'],
                'user_account_role' => $kolInfo['kol_account_role'], // EAccountM - 普通企业号 EAccountS - 认证企业号  EAccountK - 品牌企业号
                'user_mobile' => $mobile
            ];

            // 存储用户信息
            $userRes = $this->User->CreateData($userData);
            if($userRes['code'] == 1) {

                // 存储user_account信息
                $userAccount = [
                    'account_user' => $userRes['id'],
                    'account_kol' => $kolInfo['kol_id'],
                    'account_nikename' => $kolInfo['kol_nickname'],
                    'account_avatar' => $kolInfo['kol_avatar'],
                    'account_is_self' => 1,
                ];

                $accountModel->CreateData($userAccount);

                // 存储user_login
                $password = EncryptionPassword($data['pass']);
                $userLogin = [
                    'login_user' => $userRes['id'],
                    'login_name' => $kolInfo['kol_nickname'],
                    'login_mobile' => $mobile,
                    'login_password' => $password['password'],
                    'login_str' => $password['login_encrypt_str'],
                    'login_dyopenid' => $kolInfo['kol_open_id'],
                    'login_lastip' => $_SERVER['REMOTE_ADDR'],
                    'login_lasttime' => time(),
                    'login_time' => time(),
                ];
                $userLoginModel->CreateData($userLogin);



                $this->delKol($kolInfo, 1);
                $this->delUser($userRes['data'], 5);

                return ['code' => 1, 'msg' => '登录成功！'];
            } else {
                return ['code' => 0, 'msg' => '系统繁忙，请稍后再试！'];
            }
        } else {
            return view();
        }
    }



    /**
     * @param $kolInfo
     * @param int $type 1:新用户，2：已有用户
     */
    public function delKol($kolInfo, $type = 1)
    {
        $DyInterfaces = new DyInterfaces;
        $interfaces = new Interfaces();
        $videoModel = new \app\admin\model\Video();
        if($type == 1) {
            // 存储视频（15条）
            $videoList = $DyInterfaces->videoListGet();
            foreach ($videoList['list'] as $value) {
                $temp = [
                    'video_kol' => $kolInfo['kol_id'],
                    'video_platform' => 1,
                    'video_number' => $value['item_id'],
                    'video_username' => $kolInfo['kol_nickname'],
                    'video_title' => $value['title'],
                    'video_cover' => $value['cover'],
                    'create_time' => $value['create_time'],
                    'video_url' => $value['share_url'],
                ];
                $res = $videoModel->CreateData($temp);
                if($res['code'] == 1) {
//                    $interfaces->GetVideoComment($value['item_id'],$page=1);
                }
            }


        } else {
            // 查询最新一条视频
            $lastVideoTime = $videoModel->GetField(['video_kol' => $kolInfo['kol_id']],'create_time', 'create_time desc');
            // 存储视频（数据表中最新的时间之后的视频）
            $videoList = $DyInterfaces->videoListGet();
            $data = [];
            foreach ($videoList['list'] as $value) {
                if($value['create_time'] < $lastVideoTime) {
                    continue;
                }

                $temp = [
                    'video_kol' => $kolInfo['kol_id'],
                    'video_platform' => 1,
                    'video_number' => $value['item_id'],
                    'video_username' => $kolInfo['kol_nickname'],
                    'video_title' => $value['title'],
                    'video_sharetitle' => $value['title'],
                    'video_cover' => $value['cover'],
                    'create_time' => $value['create_time'],
                    'video_url' => $value['share_url'],
                ];
                $res = $videoModel->CreateData($temp);
                if($res['code'] == 1) {
//                    $interfaces->GetVideoComment($value['item_id'],$page=1);
                }
            }
        }
    }


    //退出方法
    public function logout()
    {
        session::set('user',null);
        $this->redirect('index/login/login');
    }

    //更新登陆记录
    public function SaveLoginLog($user,$type=2)
    {
        $LoginLog = new LoginLog;

        $lastLogin = array(
            'log_user'  => $user,
            'log_type'  => $type,
            'log_code'  => gethostname(),
            'log_ip'    => $_SERVER['REMOTE_ADDR'],
            'log_time'  => time(),
        );

        $LoginLog->CreateData($lastLogin);
    }

    public function delUser($user, $type = 2)
    {

        //开始处理登陆信息...

        //更新登陆记录
        $this->SaveLoginLog($user['user_id'],$type);
        //查询会员信息
        $vip = $this->Vip->GetOneData(array('vip_user'=>$user['user_id'],'vip_start'=>array('LT',time()),'vip_expire'=>array('GT',time())));
        //更新用户会员信息
        $this->User->UpdateData(['user_id'=>$user['user_id'],'user_vlevel'=>empty($vip) ? 0 : $vip['vip_level']]);

        //补全信息...

        //补全会员信息
        if(empty($vip)){
            $user['user_vip'] = array(
                'level_name'     => '免费版',
                'level_icon'    => '',
                'level_desc'    => '普通版用户',
            );
        }else
            $user['user_vip'] = $this->VipLevel->GetOneData(array('level_id'=>$vip['vip_level']));

        //如果用户已提交认证信息
        if($user['user_type'] != 1){
            //补全认证信息
            $user['user_authInfo'] = $this->Certification->GetOneDataById($user['user_certification']);
            //补全关联信息
            switch ($user['user_type']) {
                case 4:
                    $expaObj = new Mcn;
                    break;
            }

            $user['user_expaInfo'] = $expaObj->GetOneDataById($user['user_expansion']);
        }
        //生成session
        session::set('user',$user);

        //返回成功
//        return array('code'=>1,'msg'=>'登陆成功，即将跳转');
    }



}
