<?php
namespace app\api\controller;

use think\Controller;
use think\Session;

//接口基础类

class Base extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
}
