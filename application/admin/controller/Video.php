<?php 

namespace app\admin\controller;

use app\admin\model\Platform;
use app\admin\model\VideoSort;
use app\admin\model\Video as VideoModel;

use app\api\controller\Interfaces;
/**
 * 视频 管理

 *	ControllerList
 */

class Video extends LoginBase
{
    private $Model;
    private $Platform;
    private $VideoSort;
    private $Interfaces;

    /**
     * 构造函数
     **/
	public function __construct()
	{
		parent::__construct();
        $this->Model = new VideoModel;
        $this->Platform = new Platform;
        $this->VideoSort = new VideoSort;
        $this->Interfaces = new Interfaces;
        $this->assign('modeltext','视频');
        $this->assign('secondtext','分类');
        $this->assign('platforms',$this->Platform->GetDataList(array('platform_status'=>1)));
	}


    /**
     * 数据列表
     * @param int   $page           页数
     * @param int   $limit          条数
     **/
	public function sort()
	{
        $map = [];

        //数据筛选
        $condition['name'] = '';

        $name = input('param.name');
        if($name && $name !== ""){
        	$condition['name'] = $name;
            $map['sort_name'] = ['like',"%" . $name . "%"];
        }

        //查询数据
        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->VideoSort->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->VideoSort->GetListByPage($map,$Nowpage,$limits);

        foreach ($data as $key => $value) {
            //循环操作
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
     * 添加分类
     **/
    public function createsort()
    {
        return request()->isPost() ? $this->VideoSort->CreateData(input('post.')) : view();
    }


    /**
     * 修改分类
     * @param int   $id     主键
     **/
    public function updatesort($id = 0)
    {
        $this->assign('data',$this->VideoSort->GetOneDataById($id));
        return request()->isPost() ? $this->VideoSort->UpdateData(input('post.')) : view();
    }


    /**
     * 更改分类状态
     * @param int   $id     主键
     **/
    public function changesort($id)
    {
        $data = $this->VideoSort->GetOneDataById($id);

        if(!$data) return array('code'=>0,'msg'=>'数据不存在');

        $update['sort_id'] = $id;
        $update['sort_status'] = $data['sort_status'] == 1 ? 0 : 1;

        return $this->VideoSort->UpdateData($update);
    }


    /**
     * 删除分类
     * @param int   $id     主键
     **/
    public function deletesort($id)
    {
        return $this->VideoSort->DeleteData($id);
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
        $condition['name'] = '';
        $condition['platform'] = 0;

        $name = input('param.name');
        if($name && $name !== ""){
            $condition['name'] = $name;
            $map['video_title'] = ['like',"%" . $name . "%"];
        }

        $platform = input('param.platform');
        if($platform && $platform != 0){
            $condition['platform'] = $platform;
            $map['video_platform'] = $platform;
        }



        dump($map);

        //查询数据
        $Nowpage    = input('page') ? input('page') : 1;
        $limits     = input('limit') ? input('limit') : 15;
        $count      = $this->Model->GetCount($map);
        $allpage    = intval(ceil($count / $limits));
        $data       = $this->Model->GetListByPage($map,$Nowpage,$limits);


        dump($data);
        foreach ($data as $key => $value) {
            //循环操作
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
        return request()->isPost() ? $this->Model->CreateData(input('post.')) : view();
    }


    /**
     * 修改数据
     * @param int   $id     主键
     **/
    public function update($id = 0)
    {
        $this->assign('data',$this->Model->GetOneDataById($id));
        return request()->isPost() ? $this->Model->UpdateData(input('post.')) : view();
    }


    /**
     * 更改状态
     * @param int   $id     主键
     **/
    public function change($id)
    {
        $data = $this->Model->GetOneDataById($id);

        if(!$data) return array('code'=>0,'msg'=>'数据不存在');

        $update['rotate_id'] = $id;
        $update['rotate_status'] = $data['rotate_status'] == 1 ? 0 : 1;

        return $this->Model->UpdateData($update);
    }


    /**
     * 删除数据
     * @param int   $id     主键
     **/
    public function delete($id)
    {
        return $this->Model->DeleteData($id);
    }
}