<?php
namespace app\index\controller;

use app\index\controller\LoginBase;
use app\api\controller\GetData;
use app\admin\model\Kol as KolModel;
use app\admin\model\McnKol;
use app\admin\model\McnGroup as McnGroupModel;
use app\admin\model\McnAgent;
use think\Db;

class McnGroup extends LoginBase
{
	private $GetData;
	private $mcnGroup;
	private $kol;
    private $data;

	public function __construct()
	{
		parent::__construct();

		$this->mcnGroup = new McnGroupModel();
		$this->kol = new KolModel();

		$this->GetData = new GetData;
  //       $this->assign('specification',$this->GetData->GetSpecification('all'));
		// $this->assign('sort',$this->GetData->GetVideoSort());

        $this->data = $this->GetData->GetMcnData();

        if($this->data['code'] != 1 && strpos($_SERVER['REQUEST_URI'],'mcn/prompt/msg/') === false)
            $this->redirect('prompt',['msg'=>$this->data['msg']]);
	}

    //分组管理
    public function index()
    {
        // mcn信息
        $mcnInfo = $this->data['data'];

        // 获取该mcn的分组信息
        $groupWhere = ['group_mcn' => $mcnInfo['mcn_id'], 'group_status' => 1];
        $macGroups =$this->mcnGroup->GetDataList($groupWhere);


        // 查询该mcn的红人
        $kols = $this->kol->GetDataList(['kol_mcn' => $mcnInfo['mcn_id']], '', 'kol_id, kol_nickname, kol_avatar');

        var_dump($tempArr = array_column($kols, null, 'value'));die;
        // 获取分组内的红人id
        $sql = "select mk_group,GROUP_CONCAT(mk_kol) as kolids from m15_mcn_kol where m15_mcn_kol.mk_mcn = {$mcnInfo['mcn_id']} group by mk_group";
        $groupKols = Db::query($sql);


        $groupData = [];
        foreach ($macGroups as $key => $value) {
//            $kolIds =
        }




        // mcn个人信息
        $this->assign('data',$mcnInfo);
        return view();
    }

	public function create()
    {
        return request()->isPost() ? $this->mcnGroup->CreateData(input('post.')) : view('mcn_group/create', [
            'data' => $this->data['data']
        ]);
    }

    //修改数据
    public function update($id = 0)
    {
        $this->assign('data',$this->mcnGroup->GetOneDataById($id));
        return request()->isPost() ? $this->mcnGroup->UpdateData(input('post.')) : view();
    }

    //删除数据
    public function delete($id)
    {
        return $this->mcnGroup->DeleteData($id);
    }



    //提示页
    public function prompt($msg)
    {
        $this->assign('msg',$msg);
        return view();
    }
}
