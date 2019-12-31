<?php 

namespace app\admin\controller;

use app\admin\model\Member as MemberModel;
use app\admin\model\Group as GroupModel;

/**
 * 后台主页（Justin：2019-03-12）

 *	ControllerList
 */

class Member extends LoginBase
{
    private $Member;
	private $Group;

	public function __construct()
	{
		parent::__construct();
        $this->Member = new MemberModel();
		$this->Group = new GroupModel();
	}

	//用户列表
	public function index()
	{
        $map = [];

        $condition['name'] = $condition['mobile'] = '';

        $name = input('param.name');
        if($name && $name !== ""){
        	$condition['name'] = $name;
            $map['member_name'] = ['like',"%" . $name . "%"];
        }

        $mobile = input('param.mobile');
        if($mobile && $mobile != 0){
        	$condition['mobile'] = $mobile;
            $map['member_mobile'] = ['like',"%" . $mobile . "%"];
        }

        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Member->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Member->GetListByPage($map,$Nowpage,$limits);

        foreach ($data as $key => $value) {
            $data[$key]['member_time'] = date('Y-m-d H:i',$value['member_time']);
            $data[$key]['member_last_time'] = $value['member_last_time'] == 0 ? '未登陆' : date('Y-m-d H:i',$value['member_last_time']);
            $data[$key]['member_last_ip'] = CheckIsIP($value['member_last_ip']) ? $value['member_last_ip'] : '未登录';
            $data[$key]['member_groupname'] = $this->Group->GetGroupNameById($value['member_group']);
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

    //创建用户
    public function create()
    {
        $this->assign('groups',$this->Group->GetDataList(array('group_status'=>1)));

        return request()->isPost() ? $this->Member->CreateData(input('post.')) : view();
    }

    //编辑用户
    public function update($id = 0)
    {
        if(request()->isPost()){
            
            $data = input('post.');

            if($data['member_password'] != ''){
                $pass = EncryptionPassword($data['member_password']);
                $data['member_password'] = $pass['password'];
                $data['member_encrypt_str'] = $pass['login_encrypt_str'];
            }else
                unset($data['member_password']);

            return $this->Member->UpdateData($data);

        }else{

            if($id == 0) return json_encode(array('code'=>0,'msg'=>'参数不正确'));
            $this->assign('groups',$this->Group->GetDataList(array('group_status'=>1)));
            $this->assign('data',$this->Member->GetMemberInfoById($id));
            return view();
        }
    }

    //删除用户
    public function delete($id)
    {
        return $this->Member->DeleteData($id);
    }
}