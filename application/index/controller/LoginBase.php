<?php
namespace app\index\controller;

use think\Controller;
use think\Session;

use app\admin\model\Config;

class LoginBase extends Controller
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

		//检测登陆信息
		if(!session::has('user')) {
            echo '<script language="javascript">';
            echo 'parent.location.reload();';
            echo '</script>';
        }

	}
}
