<?php
namespace app\index\controller;

use think\Session;
use think\Controller;
use app\api\controller\GetData;
use app\api\controller\Automated;

use app\admin\model\Collect;

class Collection extends LoginBase
{
	private $GetData;

	public function __construct()
	{
		parent::__construct();
		$this->GetData = new GetData;
	}

    //视频
    public function video()
    {
        // $Automated = new Automated;

        // $Automated->ProcessingMusic();

        // die;

        $data = $this->GetData->GetCollectList();
        $this->assign('data',$data);
        return view();
    }


    //音乐
    public function music()
    {
        $data = $this->GetData->GetCollectList('music');
        $this->assign('data',$data);
        return view();
    }


	//话题
    public function topic()
    {
        $data = $this->GetData->GetCollectList('topic');
        $this->assign('data',$data);
        return view();
    }


    //红人
    public function kol()
    {
        $data = $this->GetData->GetCollectList('kol');
        $this->assign('data',$data);
        return view();
    }


    //添加收藏
    public function createCollect($type='video',$key=0)
    {
        $obj = new Collect;

        $coll = array(
            'collect_user'  => session::get('user.user_id'),
            'collect_key'   => $key,
            'collect_type'  => $type,
            'collect_time'  => time(),
        );

        if($obj->where(array('collect_type'=>$type,'collect_key'=>$key,'collect_user'=>session::get('user.user_id')))->find()) return array('code'=>0);

        return $obj->CreateData($coll);
    }



    //取消收藏
    public function cancelCollect($type='video',$key=0)
    {
        $obj = new Collect;

        $result = $obj->where(array('collect_type'=>$type,'collect_key'=>$key,'collect_user'=>session::get('user.user_id')))->delete();

        return $result ? array('code'=>1,'msg'=>'取消收藏成功') : array('code'=>0,'msg'=>'取消收藏失败');
    }
}
