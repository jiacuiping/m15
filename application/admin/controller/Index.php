<?php 

namespace app\admin\controller;

use think\session;

/**
 * 后台主页（Justin：2019-03-12）

 *	ControllerList
 *	Index				网站首页
 *	ControlCenter		控制中心
 *	StatisticsCenter	统计中心
 *	DataCenter			数据中心
 *	Welcome				欢迎页
 *	AboutMe				关于我
 */

class Index extends LoginBase
{
	public function Index()
	{
		$adminmenu = GetMenu(1);
		$this->assign('adminmenu',$adminmenu);
		return view();
	}

	//控制中心
	public function ControlCenter()
	{
		return view();
	}

	//统计中心
	public function StatisticsCenter()
	{
		return view();
	}

	//数据中心
	public function DataCenter()
	{
		return view();
	}

	//欢迎页
	public function Welcome()
	{
		$h = date('H');

		switch ($h)
		{
			case ($h > 6 && $h < 11)://早上
				$Greetings = 'Good morning';
				break;  
			case ($h > 10 && $h < 15)://中午
				$Greetings = 'Good noon';
				break;
			case ($h > 14 && $h < 20)://下午
				$Greetings = 'Good afternoon';
				break;
			case ($h > 19 && $h < 22)://晚上
				$Greetings = 'Good evening';
				break;
			case ($h > 0 && $h < 5)://深夜
				$Greetings = 'Good evening';
				break;
			case ($h > 4 && $h < 7)://凌晨
				$Greetings = 'it\'s Midnight';
				break;
			default:
				$Greetings = 'Good morning';
		}

		$this->assign('Greetings',$Greetings);
		return view();
	}

	//关于我
	public function AboutMe()
	{
		return view();
	}
}