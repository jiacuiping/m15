<?php
namespace app\api\controller;

use think\Session;
use app\api\controller\Base;

//models
use app\admin\model\User;

//特权验证控制器

class Privilage extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 获取广告位
	 * @param position 	false 	string 	展示位置
	 * @return 数据列表
	 */
	public function CheckPrivilege($checkValue,$checkItem='')
	{
		$level = session::get('vip_level');

		if($checkItem == 'timeBetween'){

		}
	}
}
