<?php 

namespace app\admin\controller;

use think\session;
use app\admin\model\Config as ConfigModel;

/**
 * 后台主页（Justin：2019-03-12）

 *	ControllerList
 */

class Setting extends LoginBase
{
	private $Config;


	public function __construct()
	{
		parent::__construct();
		$this->Config = new ConfigModel();
	}


	public function index()
	{
		$this->assign('info',$this->Config->GetConfig());
		return view();
	}


	//修改网站配置
	public function update()
	{
		$data = input('post.');

		if($data['password'] != ''){

			$passInfo = EncryptionPassword($data['password']);

			$passres = db('admins')->where('member_id',1)->update(['member_password'=>$passInfo['password'],'member_encrypt_str'=>$passInfo['login_encrypt_str']]);

		}else
			$passres = false;

		$configres = $this->Config->UpdateConfig($data);

		return $configres || $passres ? array('code'=>1,'msg'=>'修改成功') : array('code'=>0,'msg'=>'修改失败');
	}


}