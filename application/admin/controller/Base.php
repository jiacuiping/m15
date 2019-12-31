<?php 

namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\request;
use app\admin\model\Config;

/**
 * 后台基础方法（Justin：2019-03-12）
 */

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

		if(!session::has('isMobile'))
			session::set('ismobile',ismobile() ? true : false);
	}
}