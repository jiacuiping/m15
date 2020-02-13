<?php
namespace app\index\controller;

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

            $this->delUser($user);
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
        // 把code存入session
        $code = input('get.code');
        session::set('code',input('get.code'));

        $DyInterfaces = new DyInterfaces;

        // 获取access_token
        $data = $DyInterfaces->get_access_token();
        if(!$data) {
            return ['code'=>0,'msg'=>'登录失败，请稍后再试！'];
        }


        // 用户是否登录过
        $userInfo = $this->User->GetOneData(['user_open_id' => $data['open_id']]);
        if($userInfo) {
            //账号数据判断
            if(!$userInfo) $this->error('账号异常，请联系客服','index/index');
//            if(!$userInfo) return array('code'=>0,'msg'=>'账号异常，请联系客服');
            //账号状态判断
            if($userInfo['user_status'] != 1) $this->error('该用户已被停用，请联系客服');
//            if($userInfo['user_status'] != 1) return array('code'=>0,'msg'=>'该用户已被停用，请联系客服');
            $login = $this->delUser($userInfo);
            $this->success('登录成功', 'index/index');
//            $this->redirect('index/index');
        }

        // 获取抖音用户信息
        $userDyInfo = $DyInterfaces->getUserInfo($data['access_token'], $data['open_id']);

        if(!$userDyInfo) {
            return ['code'=>0,'msg'=>'登录失败，请稍后再试！'];
        }

        $userData = [
            'user_name' => $userDyInfo['nickname'],
            'user_type' => 1,
            'user_avatar' => $userDyInfo['avatar'],
            'user_sex' => $userDyInfo['gender'],
            'user_open_id' => $userDyInfo['open_id'],
            'user_union_id' => $userDyInfo['union_id'],
            'user_account_role' => $userDyInfo['e_account_role'], // EAccountM - 普通企业号 EAccountS - 认证企业号  EAccountK - 品牌企业号
        ];

        // 存储用户信息
        $res = $this->User->CreateData($userData);

        if($res && $res['code'] == 1) {
            $this->delUser($res['data']);
            $this->success('登录成功', 'index/index');
//            $this->redirect('index/index');
        } else {
            $this->redirect('index/index');
        }

    }


    //退出方法
    public function logout()
    {
        session::set('user',null);
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

    public function delUser($user)
    {


        //开始处理登陆信息...

        //更新登陆记录
        $this->SaveLoginLog($user['user_id'],2);
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
