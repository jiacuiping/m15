<?php
namespace app\index\controller;

use app\index\controller\LoginBase;

use app\api\controller\Interfaces;

class Index extends LoginBase
{
	public function __construct()
	{
		parent::__construct();
	}

    public function index()
    {
		$indexmenu = GetMenu(1);
		$this->assign('indexmenu',$indexmenu);
        return view();
    }


    public function workbench()
    {
    	return view();
    }
}
