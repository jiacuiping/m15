<?php
namespace app\index\controller;

use app\index\controller\LoginBase;
use app\api\controller\GetData;
use app\admin\model\Kol as KolModel;
use app\admin\model\McnKol as McnKolModel;
use app\admin\model\McnAgent as McnAgentModel;
use app\admin\model\McnKol;
use think\Db;

class McnAgent extends LoginBase
{
	private $GetData;
	private $McnAgent;
	private $kol;
    private $data;
    private $McnKol;

	public function __construct()
	{
		parent::__construct();

		$this->kol = new KolModel();
		$this->McnKol = new McnKolModel();
		$this->McnAgent = new McnAgentModel();

		$this->GetData = new GetData;

        $this->data = $this->GetData->GetMcnData();

        if($this->data['code'] != 1 && strpos($_SERVER['REQUEST_URI'],'mcn/prompt/msg/') === false)
            $this->redirect('prompt',['msg'=>$this->data['msg']]);
	}


	public function create()
    {
        return request()->isPost() ? $this->McnAgent->CreateData(input('post.')) : view('mcn_agent/create', [
            'data' => $this->data['data']
        ]);
    }

    //修改经纪人
    public function update($id = 0)
    {
        $this->assign('data',$this->McnAgent->GetOneDataById($id));
        return request()->isPost() ? $this->McnAgent->UpdateData(input('post.')) : view();
    }

    //删除经纪人
    public function delete($id)
    {
        // 删除经纪人的同时把经纪人和红人的关联解除
        return $this->McnAgent->DeleteData($id);
    }

    // 展示可添加的添加红人
    public function viewKol($agentId = 0, $searchText = '')
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
        $kolIds = $this->McnKol->GetDataList(['mk_mcn' => $mcnInfo['mcn_id'], 'mk_agent' => 0]);
        $kolIds = array_column($kolIds, null,'mk_kol');

        $result = array_intersect_key($kols, $kolIds);

        // 红人信息
        $this->assign('kol',$result);
        // mcn个人信息
        $this->assign('data',$mcnInfo);
        // 分组信息
        $this->assign('agentId',$agentId);
        // 筛选信息
        $this->assign('searchText',$searchText);

        return view();
    }

    // 添加红人
    public function addKol()
    {
        // 接收参数
        $agentId = input('post.agentId');
        $kolId = input('post.kolId');

        // 查看分组 和 红人 是否存在
        $agentInfo = $this->McnAgent->GetOneDataById($agentId);
        if (!$agentInfo) return ['code' => 0, 'msg' => '经纪人不存在'];

        $KolInfo = $this->kol->GetOneDataById($kolId);
        if (!$KolInfo) return ['code' => 0, 'msg' => '红人不存在'];

        // mcn信息
        $mcnInfo = $this->data['data'];

        $data = [
            'mk_mcn' => $mcnInfo['mcn_id'],
            'mk_kol' => $kolId,
            'mk_agent' => $agentId,
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
