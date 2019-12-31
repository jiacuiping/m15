<?php 

namespace app\admin\controller;

use app\admin\model\Privilege as PrivilegeModel;
/**
 * 特权管理
 *	ControllerList
 */

class Privilege extends LoginBase
{
    private $Model;

	public function __construct()
	{
		parent::__construct();
        $this->Model = new PrivilegeModel;
        $this->assign('modeltext','特权');
        $this->assign('secondtext','特权');
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
        $count 		= $this->Model->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Model->GetListByPage($map,$Nowpage,$limits);

        foreach ($data as $key => $value) {
            
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

    //添加数据
    public function create()
    {
        return request()->isPost() ? $this->Model->CreateData(input('post.')) : view();
    }

    //修改数据
    public function update($id = 0)
    {
        $this->assign('data',$this->Model->GetOneDataById($id));
        return request()->isPost() ? $this->Model->UpdateData(input('post.')) : view();
    }

    //修改订单状态
    public function change($id)
    {
        $data = $this->Model->GetOneDataById($id);

        if(!$data) return array('code'=>0,'msg'=>'数据不存在');

        $update['level_id'] = $id;
        $update['level_status'] = $data['level_status'] == 1 ? 0 : 1;

        return $this->Model->UpdateData($update);
    }

    //删除数据
    public function delete($id)
    {
        return $this->Model->DeleteData($id);
    }
}