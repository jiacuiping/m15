<?php 

namespace app\admin\controller;

use app\admin\model\Privilege as Privilege;
use app\admin\model\Duration as DurationModel;
/**
 * 特权管理
 *	ControllerList
 */

class Duration extends LoginBase
{
    private $Model;
    private $Privilege;

	public function __construct()
	{
		parent::__construct();
        $this->Model = new DurationModel;
        $this->Privilege = new Privilege;
        $this->assign('modeltext','套餐');
        $this->assign('level',$this->Privilege->GetDataList(array('level_status'=>1)));
	}

	//数据列表
	public function index()
	{
        $map = [];

        $condition['level'] = 0;

        $level = input('param.level');
        if($level && $level != 0){
        	$condition['level'] = $level;
            $map['curation_level'] = $level;
        }

        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Model->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Model->GetListByPage($map,$Nowpage,$limits);

        foreach ($data as $key => $value) {
            $data[$key]['curation_level'] = $this->Privilege->GetField(array('level_id'=>$value['curation_level']),'level_name');
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

    //修改状态
    public function change($id)
    {
        $data = $this->Model->GetOneDataById($id);

        if(!$data) return array('code'=>0,'msg'=>'数据不存在');

        $update['curation_id'] = $id;
        $update['curation_status'] = $data['curation_status'] == 1 ? 0 : 1;

        return $this->Model->UpdateData($update);
    }

    //删除数据
    public function delete($id)
    {
        return $this->Model->DeleteData($id);
    }
}