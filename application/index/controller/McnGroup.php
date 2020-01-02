<?php
namespace app\index\controller;

use app\index\controller\LoginBase;
use app\api\controller\GetData;
use app\admin\model\Kol as KolModel;
use app\admin\model\McnKol as McnKolModel;
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
    private $McnKol;

	public function __construct()
	{
		parent::__construct();

		$this->mcnGroup = new McnGroupModel();
		$this->kol = new KolModel();
		$this->McnKol = new McnKolModel();

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

    // 展示可添加的添加红人
    public function viewKol($groupId = 0, $searchText = '')
    {
        // mcn信息
        $mcnInfo = $this->data['data'];

        // 查询 属于该mcn 的红人
        $kols = $this->kol
            ->alias("a")
            ->join('kol_trend', 'a.kol_id = kol_trend.kt_kol_id')
            ->field("a.kol_id, a.kol_number, a.kol_nickname, a.kol_avatar, a.kol_desc, kol_trend.kt_fans, kol_trend.kt_videocount")
            ->where(['a.kol_mcn' => $mcnInfo['mcn_id']]);

        // 判断是否筛选
        if($searchText) {
            $kols = $kols->where('a.kol_nickname|a.kol_number', 'like','%' . $searchText . '%');
        }

        $kols = $kols->select();
        $kols = array_column($kols, null, 'kol_id');

        // 未分组的红人
        $kolIds = $this->McnKol->GetDataList(['mk_mcn' => $mcnInfo['mcn_id'], 'mk_group' => 0]);
        $kolIds = array_column($kolIds, null,'mk_kol');

        $result = array_intersect_key($kols, $kolIds);

        // 红人信息
        $this->assign('kol',$result);
        // mcn个人信息
        $this->assign('data',$mcnInfo);
        // 分组信息
        $this->assign('groupId',$groupId);
        // 筛选信息
        $this->assign('searchText',$searchText);

        return view();
    }

    // 添加红人
    public function addKol()
    {
        // 接收参数
        $groupId = input('post.groupId');
        $kolId = input('post.kolId');

        // 查看分组 和 红人 是否存在
        $groupInfo = $this->mcnGroup->GetOneDataById($groupId);
        if (!$groupInfo) return ['code' => 0, 'msg' => '分组不存在'];

        $KolInfo = $this->kol->GetOneDataById($kolId);
        if (!$KolInfo) return ['code' => 0, 'msg' => '红人不存在'];

        // mcn信息
        $mcnInfo = $this->data['data'];

        $data = [
            'mk_mcn' => $mcnInfo['mcn_id'],
            'mk_kol' => $kolId,
            'mk_group' => $groupId,
        ];

        // 查看关联表 m15_mcn_kol 中是否有该红人的记录
        $relInfo = $this->McnKol->saveData($data);

        if($relInfo['code']) {
            return ['code'=>1,'msg'=>'添加红人成功'];
        } else {
            return ['code'=>0,'msg'=>'添加红人失败'];
        }
    }


    //提示页
    public function prompt($msg)
    {
        $this->assign('msg',$msg);
        return view();
    }
}
