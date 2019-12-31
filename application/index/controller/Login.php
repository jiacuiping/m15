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
                    'level_name'    => '免费版',
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

        dump($QrCode);die;
    }

    //抖音扫码登录
    public function dyqrcode()
    {
        dump(input('post.'));
        echo "<hr/>";
        dump(input('get.'));
        echo "<hr/>";
        dump(input('pamar.'));
        die;
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
}
