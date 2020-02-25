<?php
namespace app\index\controller;

use think\Controller;
use think\Session;

use app\admin\model\UserAccount;
use app\admin\model\Config;

class Base extends Controller
{
	public function __construct()
	{
		parent::__construct();
		//检测配置文件
		if(!session::has('config')) 
		{
			$config = new Config();
			session::set('config',$config->GetConfig());
		}

		//检测设备类型
		if(!session::has('isMobile'))
			session::set('ismobile',ismobile() ? true : false);
	}

	public function ChheckPrivilege()
	{

	}


    //提示页
    public function prompt($msg)
    {
        $this->assign('msg',$msg);
        return view();
    }
}
