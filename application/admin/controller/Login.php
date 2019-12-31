<?php 

namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Request;

use app\admin\controller\Base;
use app\admin\model\Member as MemberModel;


/**
 *	登陆控制器
 */

class Login extends Base
{
	private $MemberModel;

	public function __construct()
	{
		parent::__construct();
		$this->MemberModel = new MemberModel();
	}

	//用户登陆
	public function login()
	{
		if(request()->isPost()){

			$login = input('post.');

			$member = $this->MemberModel->GetMemberInfoByWhere(array('member_mobile'=>$login['mobile']));

			if (!$member) return array('code'=>0,'msg'=>'用户不存在');

			if (!PasswordCheck($login['password'],$member['member_password'],$member['member_encrypt_str'])) return array('code'=>0,'msg'=>'密码不正确');

			if($member['member_forbidden'] != 1) return array('code'=>0,'msg'=>'此用户已被禁止登陆');

			if ($member['member_is_admin'] != 1)	return array('code'=>0,'msg'=>'此用户没有后台权限');
			
			//登陆成功
			$this->MemberModel->UpdateData(array('member_id'=>$member['member_id'],'member_last_ip'=>$_SERVER["REMOTE_ADDR"],'member_last_time'=>time()));

			session::set('member',$member);
			return array('code'=>1,'msg'=>'登陆成功，即将跳转！');

		}else
			return view();
	}

	//用户注册
	public function register()
	{
		if(request()->isPost()){

			$data = input('post.');


		}else
			return view();
	}

	//退出登陆
	public function logout()
	{
		Session::delete('member');
		return array('code'=>1,'msg'=>'退出成功');
	}
}