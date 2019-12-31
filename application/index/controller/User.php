<?php
namespace app\index\controller;

use app\index\controller\LoginBase;
use think\Session;

use app\admin\model\UserVip;
use app\admin\model\LoginLog;
use app\admin\model\UserAccount;
use app\admin\model\UserAddress;
use app\admin\model\User as UserModel;

class User extends LoginBase
{
	public function __construct()
	{
		parent::__construct();

        $this->Vip = new UserVip;
        $this->User = new UserModel;
		$this->LoginLog = new LoginLog;
        $this->Account = new UserAccount;


        $user = session::get('user');

        $selfdy = $this->Account->GetOneData(array('account_user'=>$user['user_id'],'account_is_self'=>1,'account_authstatus'=>1));

        $user['dyaccount'] = $selfdy ? $selfdy['account_nikename'] : '暂未绑定';

        $this->User = $user;
        $this->assign('user',$user);
	}

	//用户主页
    public function index($type = 'log')
    {
        if($type == 'log'){
            $logs = $this->LoginLog->GetDataList(array('log_user'=>$this->User['user_id']));
            $this->assign('machinecode',gethostname());
            $this->assign('logs',$logs);
        }elseif($type == 'address'){

            $obj = new UserAddress;

            $address = $obj->GetOneData(array('address_user'=>$this->User['user_id']));
            $cityinfo['province'] = db('area')->where('pid',0)->select();

            if(!$address){
                $cityinfo['citys'] = $cityinfo['areas'] = array();
                $address['address_province'] = $address['address_city'] = $address['address_area'] = 0;
                $address['address_info'] = $address['address_contact'] = $address['address_mobile'] = '';
            }else{
                $citys = $this->selectCity($address['address_province']);
                $cityinfo['citys'] = $citys['code'] == 1 ? $citys['citys'] : array();
                $areas = $this->selectCity($address['address_city']);
                $cityinfo['areas'] = $areas['code'] == 1 ? $areas['citys'] : array();
            }

            $this->assign('address',$address);
            $this->assign('cityinfos',$cityinfo);
        }

        $this->assign('type',$type);
        
        return view();
    }


    //套餐管理
    public function package()
    {
        return view();
    }

    //任务中心
    public function task()
    {
        return view();
    }

    //特权方法
    public function privilege($type='info')
    {
        $this->assign('vips',db('vip_level')->where('level_status',1)->select());
        $this->assign('type',$type);
        return view();
    }

    //修改地址
    public function ChangeAddress()
    {
        $obj = new UserAddress;

        $data = input('post.');

        //为空判断
        if($data['address_province'] == 0) return array('code'=>0,'msg'=>'请选择省');
        if($data['address_city'] == 0) return array('code'=>0,'msg'=>'请选择市');
        if($data['address_area'] == 0) return array('code'=>0,'msg'=>'请选择区');
        if($data['address_info'] == '') return array('code'=>0,'msg'=>'详细地址不能为空');
        if($data['address_contact'] == '') return array('code'=>0,'msg'=>'联系人不可为空');
        if($data['address_mobile'] == '') return array('code'=>0,'msg'=>'请输入联系电话号码');

        $data['address_user'] = session::get('user.user_id');

        return $obj->CreateData($data);
    }



    //获取下级城市列表
    public function selectCity($adcode)
    {
        $thisinfo = db('area')->where('id',$adcode)->find();

        if(!$thisinfo) return json_encode(array('code'=>0,'msg'=>'地区不存在'));

        $citys = db('area')->where('pid',$thisinfo['id'])->select();

        return array('code'=>1,'msg'=>'获取成功','citys'=>$citys,'level'=>$thisinfo['level']);
    }
}
