<?php 

namespace app\admin\controller;

use app\admin\model\UserVip;
use app\admin\model\Privilege;
use app\admin\model\UserLogin;
use app\admin\model\User as UserModel;
/**
 * 模版 管理

 *	ControllerList
 */

class User extends LoginBase
{
    private $Model;
    private $UserVip;
    private $UserLogin;
    private $Privilege;

    /**
     * 构造函数
     **/
	public function __construct()
	{
		parent::__construct();
        $this->Model = new UserModel;
        $this->UserVip = new UserVip;
        $this->UserLogin = new UserLogin;
        $this->Privilege = new Privilege;
        $this->assign('modeltext','用户');
        $this->assign('levels',$this->Privilege->GetDataList(array('level_status'=>1)));
	}


    /**
     * 数据列表
     * @param int   $page           页数
     * @param int   $limit          条数
     **/
	public function index()
	{
        $map = [];

        //数据筛选
        $condition['name'] = $condition['model'] = '';
        $condition['sex'] = $condition['vip'] = 0;

        $name = input('param.name');
        if($name && $name !== ""){
            $condition['name'] = $name;
            $map['user_name'] = ['like',"%" . $name . "%"];
        }

        $model = input('param.model');
        if($model && $model !== ""){
            $condition['model'] = $model;
            $map['user_mobile'] = ['like',"%" . $model . "%"];
        }

        $sex = input('param.sex');
        if($sex && $sex != 0){
            $condition['sex'] = $sex;
            $map['user_sex'] = $sex;
        }

        $vip = input('param.vip');
        if($vip && $vip != 0){
        	$condition['vip'] = $vip;
            $map['user_vlevel'] = $vip;
        }

        //查询数据
        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Model->GetCount($map);
        $data 		= $this->Model->GetListByPage($map,$Nowpage,$limits);

        foreach ($data as $key => $value) {
            $data[$key]['user_sex'] = $value['user_sex'] == 1 ? '男' : '女';
            $data[$key]['user_vlevel'] = $value['user_vlevel'] == 0 ? '非会员' : $this->Privilege->GetField(array('level_id'=>$value['user_vlevel']),'level_name');
            $data[$key]['user_avatar'] = "<a href=".$value['user_avatar']." target='_blank'><img src=".$value['user_avatar']." class='imglist'></a>";
        }

        if(input('page'))
        {
            return json(
                ['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data,'datas'=>$map,'condition'=>$condition]
            );
        }
        $this->assign('condition',$condition);
        return $this->fetch();	
	}


    /**
     * 添加数据
     **/
    public function create()
    {
        if(request()->isPost()){

            $data = input('post.');
            //字段验证
            $check = $this->checkInfo($data);

            if($check['code'] == 0) return $check;
            if($data['user_avatar'] == '')
                unset($data['user_avatar']);

            $userResult = $this->Model->CreateData($data);

            if($userResult['code'] == 1){

                $password = EncryptionPassword($data['login_password']);

                $loginData = array(
                    'login_user'        => $userResult['id'],
                    'login_name'        => $data['user_name'],
                    'login_mobile'      => $data['user_mobile'],
                    'login_password'    => $password['password'],
                    'login_str'         => $password['login_encrypt_str']
                );
                //添加登陆信息
                $loginResult = $this->UserLogin->CreateData($loginData);

                //添加特权信息
                if($data['user_vlevel'] != 0){

                    if($data['vip_duration'] == '年')
                        $unit = 'year';
                    else
                        $unit = $data['vip_duration'] == '月' ? 'months' : 'day';

                    $vipData = array(
                        'vip_user'      => $userResult['id'],
                        'vip_level'     => $data['user_vlevel'],
                        'vip_start'     => time(),
                        'vip_expire'    => GetTimestamp($data['vip_number'],$unit)
                    );

                    $VipResult = $this->UserVip->CreateData($vipData);
                }else
                    $VipResult = true;

                return $loginResult && $VipResult ? array('code'=>1,'msg'=>'添加成功') : array('code'=>0,'msg'=>'添加失败');

            }else
                return $userResult;
        }else
            return view();
    }


    /**
     * 修改数据
     * @param int   $id     主键
     **/
    public function update($id = 0)
    {
        if(request()->isPost()){

            $data = input('post.');
            //字段验证
            $check = $this->checkInfo($data,'update');

            if($check['code'] == 0) return $check;

            $userResult = $this->Model->UpdateData($data);


            if($userResult['code'] == 1){

                $logininfo = $this->UserLogin->GetOneData(array('login_user'=>$data['user_id']));

                if($data['user_name'] != $logininfo['login_name'])
                    $login['login_name'] = $data['user_name'];
                if($data['user_mobile'] != $logininfo['login_mobile'])
                    $login['login_mobile'] = $data['user_mobile'];
                if($data['login_password'] != ''){
                    $password = EncryptionPassword($data['login_password']);
                    $login['login_password'] = $password['password'];
                    $login['login_str'] = $password['login_encrypt_str'];
                }

                $loginResult = !isset($login) ? true : $this->UserLogin->UpdateData(array_merge($login,array('login_id'=>$logininfo['login_id'])));

                //添加特权信息
                if($data['user_vlevel'] != 0){
                    
                    $vipinfo = $this->UserVip->GetOneData(array('vip_user'=>$data['user_id']));

                    if(date('Y-m-d H:i',$vipinfo['vip_expire']) != $data['expire'])
                        $vip['vip_expire'] = strtotime($data['expire']);
                    if($vipinfo['vip_level'] != $data['user_vlevel'])
                        $vip['vip_level'] = $data['user_vlevel'];

                    $VipResult = !isset($vip) ? true : $this->UserVip->UpdateData(array_merge($vip,array('vip_id'=>$vipinfo['vip_id'])));
                }else
                    $VipResult = true;


                return $loginResult && $VipResult ? array('code'=>1,'msg'=>'修改成功') : array('code'=>0,'msg'=>'修改失败');

            }else
                return $userResult;

        }else{

            $data = $this->Model->GetOneDataById($id);

            $vip = $this->UserVip->GetOneData(array('vip_user'=>$id,'vip_start'=>array('LT',time()),'vip_expire'=>array('GT',time())));

            $data['expire'] = $vip ? $vip['vip_expire'] : 0;

            $data['viplevel'] = $vip ? $vip['vip_level'] : 0;

            $this->assign('data',$data);
            return view();
        }
    }


    /**
     * 查看详情
     * @param int   $id     主键
     **/
    public function show($id)
    {
        $userdata = $this->Model->GetOneDataById($id);
        $userdata['user_vlevel'] = $userdata['user_vlevel'] == 0 ? '非会员' : $this->Privilege->GetField(array('level_id'=>$userdata['user_vlevel']),'level_name');

        $logindata = $this->UserLogin->GetOneData(array('login_user'=>$id));

        $vipdatas = $this->UserVip->GetDataList(array('vip_user'=>$id));

        foreach ($vipdatas as $key => $value) {
            $vipdatas[$key]['time'] = strtotime($value['vip_time']);
            $vipdatas[$key]['vip_level'] = $this->Privilege->GetField(array('level_id'=>$value['vip_level']),'level_name');
        }

        $this->assign('userdata',$userdata);
        $this->assign('logindata',$logindata);
        $this->assign('vipdatas',$vipdatas);
        return view();
    }


    /**
     * 更改状态
     * @param int   $id     主键
     **/
    public function change($id)
    {
        $data = $this->Model->GetOneDataById($id);

        if(!$data) return array('code'=>0,'msg'=>'数据不存在');

        $update['user_id'] = $id;
        $update['user_status'] = $data['user_status'] == 1 ? 0 : 1;

        return $this->Model->UpdateData($update);
    }


    /**
     * 删除数据
     * @param int   $id     主键
     **/
    public function delete($id)
    {
        //删除用户信息表
        $UserResult = $this->Model->DeleteData($id);
        //删除用户登录表
        $login = $this->UserLogin->GetOneData(array('login_user'=>$id));
        $LoginResult = $login ? $this->UserLogin->DeleteData($login['login_id']) : true;
        //删除用户会员表
        $vips = $this->UserVip->GetColumn(array('vip_user'=>$id),'vip_id');
        $VipResult = $vips ? $this->UserVip->where(array('vip_id'=>array('in',$vips)))->delete() : true;

        return $UserResult && $LoginResult && $VipResult ? array('code'=>1,'msg'=>'删除成功') : array('code'=>0,'msg'=>'删除失败');
    }


    /**
     * 字段验证
     * @param array   $data     验证数组信息
     **/
    public function checkInfo($data,$type='create')
    {
        if($type == 'create'){
            if($data['login_password'] == '') return array('code'=>0,'msg'=>'密码不可为空');
            if(strlen($data['login_password']) < 6) return array('code'=>0,'msg'=>'密码长度不可小于6位');
            if(strlen($data['login_password']) > 16) return array('code'=>0,'msg'=>'密码长度不可超过16位');
        }
        if(!CheckIdCard($data['user_idcard'])) return array('code'=>0,'msg'=>'请输入正确的身份证号');
        return array('code'=>1);
    }
}