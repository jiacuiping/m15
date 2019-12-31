<?php 

namespace app\admin\controller;

use app\admin\model\Group as GroupModel;
use app\admin\model\Menu as MenuModel;

/**
 * 后台主页（Justin：2019-03-12）

 *	ControllerList
 */

class Group extends LoginBase
{
    private $Group;
	private $Menu;

	public function __construct()
	{
		parent::__construct();
        $this->Group = new GroupModel();
		$this->Menu = new MenuModel();
	}

	//用户列表
	public function index()
	{
        $map = [];

        $condition['name'] = $condition['mobile'] = '';

        $name = input('param.name');
        if($name && $name !== ""){
        	$condition['name'] = $name;
            $map['group_name'] = ['like',"%" . $name . "%"];
        }

        $mobile = input('param.mobile');
        if($mobile && $mobile != 0){
        	$condition['mobile'] = $mobile;
            $map['member_mobile'] = ['like',"%" . $mobile . "%"];
        }

        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Group->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Group->GetListByPage($map,$Nowpage,$limits);

        foreach ($data as $key => $value) {
            $data[$key]['group_time'] = date('Y-m-d H:i',$value['group_time']);
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
        if(request()->isPost()){
            
            $data = input('post.');

            $data['group_purview'] = empty($data['group_purview']) ? '' : implode(',',array_keys($data['group_purview']));
            $data['group_time'] = time();

            return $this->Group->CreateData($data);

        }else{

            $this->assign('menu',$this->GetMenus());
            return view();
        }
    }

    //编辑用户组
    public function update($id = 0)
    {
        if(request()->isPost()){
            
            $data = input('post.');

            $data['group_purview'] = empty($data['group_purview']) ? '' : implode(',',array_keys($data['group_purview']));

            return $this->Group->UpdateData($data);

        }else{

            if($id == 0) return json_encode(array('code'=>0,'msg'=>'参数不正确'));

            $data = $this->Group->GetGroupInfoById($id);

            if($data['group_purview'] != 'all' && $data['group_purview'] != '')
                $data['group_purview'] = explode(',',$data['group_purview']);

            $this->assign('data',$data);
            $this->assign('menu',$this->GetMenus());
            return view();
        }
    }

    //修改状态
    public function changeStatus($id,$status)
    {
        $status = $status == 'false' ? 0 : 1;

        return $this->Group->UpdateData(array('group_id'=>$id,'group_status'=>$status));
    }

    //删除用户组
    public function delete($id=0)
    {
        return $this->Group->DeleteData($id);
    }


    //获取菜单列表
    public function GetMenus()
    {
        $where['menu_status'] = 1;
        $where['menu_parent'] = 0;

        $menus = $this->Menu->GetListByPage($where);

        foreach ($menus as $key => $value) {

            $item = $this->Menu->GetListByPage(array('menu_parent'=>$value['menu_id'],'menu_status'=>1));

            $menuDatas[] = array('title'=>"<br/><button type='button' class='layui-btn menubats'>".$value['menu_name']."</button>",'items'=>!empty($item) ? $item : array());

        }

        return $menuDatas;
    }

}