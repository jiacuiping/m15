<?php
namespace app\index\controller;

use app\index\controller\LoginBase;
use app\api\controller\GetData;
use app\admin\model\Kol as KolModel;
use app\admin\model\McnKol as McnKolModel;
use app\admin\model\McnAgent as McnAgentModel;
use app\api\controller\Interfaces;
use app\admin\model\McnGroup as McnGroupModel;
use think\Db;


class Mcn extends LoginBase
{
	private $GetData;
    private $data;
    private $mcnGroup;
    private $mcnAgent;
    private $mcnKol;
    private $kol;

	public function __construct()
	{
		parent::__construct();
        $this->mcnGroup = new McnGroupModel();
        $this->mcnAgent = new McnAgentModel();
        $this->mcnKol = new McnKolModel();
        $this->kol = new KolModel();

		$this->GetData = new GetData;
  //       $this->assign('specification',$this->GetData->GetSpecification('all'));
		// $this->assign('sort',$this->GetData->GetVideoSort());

        $this->data = $this->GetData->GetMcnData();

        if($this->data['code'] != 1 && strpos($_SERVER['REQUEST_URI'],'mcn/prompt/msg/') === false)
            $this->redirect('prompt',['msg'=>$this->data['msg']]);
	}

    //数据一览表
    public function data($basisOf='app',$key=0,$type='trend',$day=7,$order="default")
    {
    	$condition = array(
    		'basisOf'	=> 'app',
    		'key'		=> 0,
    		'type'		=> 'trend',
    		'day'		=> 7,
    		'order'		=> 'default',
    	);

        if($type != 'trend') $condition['type'] = $type;
    	
    	if($day != 7) $condition['day'] = $day;

    	if($order != 'default') $condition['order'] = $order;

    	if($basisOf != 'app') $condition['basisOf'] = $basisOf;

    	if($key != 0) $condition['key'] = $key;

    	$this->assign('code',$this->data['code']);

    	if($this->data['code'] == 1){

    		$statistical = $this->GetData->GetMcnStatistical($this->data['data']['mcn_id'],$basisOf,$key);

    		$this->assign('statistical',$statistical);
    		$this->assign('data',$this->data['data']);

			$where['mk_mcn'] = $this->data['data']['mcn_id'];

			if($basisOf != 'app' && $key != 0){
				$basisOf == 'group' ? $where['mk_group'] = $key : $where['mk_agent'] = $key;
			}

			$McnKol = new McnKolModel();

			$kols = $McnKol->GetColumn($where,'mk_kol');

    		if($type == 'trend'){

    			if(!empty($kols))
					$trend = $this->GetData->GetChangeTrend($kols,'mcn',$day);
				else
					$trend = array();

				$this->assign('trend',$trend);
    		}elseif($type == 'kol'){

    			$orderBy = $order == 'default' ? 'kt.kt_fans desc' : $order;

    			$kollist = $this->GetData->GetKolList(array('kol_id'=>array('in',$kols)),1,100,$orderBy);

    			$this->assign('kollist',$kollist);

    		}elseif($type == 'video'){

    			$Kol = new Kol;

    			$uids = $Kol->GetColumn(array('kol_id'=>array('in',$kols)),'kol_uid');

    			$orderBy = $order == 'default' ? 'vt.vt_hot desc' : $order;

    			$video = $this->GetData->GetVideoList(array('video_apiuid'=>array('in',$uids)),1,100,$orderBy);

    			$this->assign('video',$video);
    		}

    	}else
    		$this->assign('msg',$this->data['msg']);

    	$this->assign('condition',$condition);
        return view();
    }


    //KOL管理
    public function Kol($mcn=0)
    {
        if($this->data['code'] == 1){

            $where['mk_mcn'] = $this->data['data']['mcn_id'];

            //if($basisOf != 'app' && $key != 0){
            //   $basisOf == 'group' ? $where['mk_group'] = $key : $where['mk_agent'] = $key;
            //}

            $McnKol = new McnKolModel();
            $McnAgent = new McnAgentModel;

            $kols = $McnKol->field('mk_kol,mk_agent,mk_group,mk_isshow')->where($where)->select();
            $kols = array_column($kols, null, 'mk_kol');
            //GetColumn($where,'mk_kol');

            //$orderBy = $order == 'default' ? 'kt.kt_fans desc' : $order;

            $kol = $this->GetData->GetKolList(array('kol_id'=>array('in',array_column($kols,'mk_kol'))),1,100);
            $kol = array_column($kol, null, 'kol_id');

            foreach ($kol as $key => $value) {
                $info = $kols[$key];

                $kol[$key]['agent'] = $info['mk_agent'] == 0 ? '暂无' : $McnAgent->GetField(array('agent_id'=>$info['mk_agent']),'agent_name');
                $kol[$key]['mk_agent'] = $kols[$key]['mk_agent'];
                $kol[$key]['mk_group'] = $kols[$key]['mk_group'];
                $kol[$key]['mk_isshow'] = $kols[$key]['mk_isshow'];
                $weekinfo = $this->GetData->GetKolIncData($value['kol_id'],7,false);
                $lweekinfo = $this->GetData->GetKolIncData($value['kol_id'],14,false);
                $monthinfo = $this->GetData->GetKolIncData($value['kol_id'],30,false);
                $lmonthinfo = $this->GetData->GetKolIncData($value['kol_id'],60,false);

                $kol[$key]['statistical'] = array(
                    'weekfans'  => $weekinfo['fans'],
                    'monthfans' => $monthinfo['fans'],
                    'lweekfans' => $lweekinfo['fans'] - $weekinfo['fans'],
                    'lmonthfans' => $lmonthinfo['fans'] - $monthinfo['fans'],
                );
            }

            $this->assign('kol',$kol);

            $this->assign('data',$this->data['data']);
        }

    	return view();
    }


    //分组管理
    public function Group()
    {
        // mcn信息
        $mcnInfo = $this->data['data'];

        // 获取该mcn的分组信息
        $groupWhere = ['group_mcn' => $mcnInfo['mcn_id'], 'group_status' => 1];
        $macGroups =$this->mcnGroup->GetDataList($groupWhere);


        // 查询该mcn的红人
        $field = "kol_id, kol_nickname, kol_avatar";
        $kols = $this->kol->GetDataList(['kol_mcn' => $mcnInfo['mcn_id']], '', $field);
        $kols = array_column($kols, null, 'kol_id');

        // 获取分组内的红人id
        $sql = "select mk_group,GROUP_CONCAT(mk_kol) as kolids from m15_mcn_kol where m15_mcn_kol.mk_mcn = {$mcnInfo['mcn_id']} group by mk_group";
        $groupKols = Db::query($sql);
        $groupKols = array_column($groupKols, 'kolids', 'mk_group');


        // 获取分组内的红人
        $groupData = [];
        foreach ($macGroups as $key => $value) {
            $macGroups[$key]['kol_num'] = 0;
            $macGroups[$key]['kols'] = [];

            if(key_exists($value['group_id'], $groupKols)) {
                $kolIds = explode(',', $groupKols[$value['group_id']]);
                $macGroups[$key]['kol_num'] = count($kolIds);

                $temp = [];
                foreach ($kolIds as $kolId) {
                    if(key_exists($kolId, $kols)) {
                        array_push($temp, $kols[$kolId]);
                    }
                }
                $macGroups[$key]['kols'] = $temp;

            }
        }

        // 红人信息
        $this->assign('kols',$kols);

        // 分组信息
        $this->assign('macGroups',$macGroups);

        // mcn个人信息
        $this->assign('data',$mcnInfo);
        return view();
    }


    //经纪人管理
    public function Agent()
    {
        // mcn信息
        $mcnInfo = $this->data['data'];

        // 获取该mcn的经纪人信息
        $agentWhere = ['agent_mcn' => $mcnInfo['mcn_id'], 'agent_status' => 1];
        $macAgents =$this->mcnAgent->GetDataList($agentWhere);


        // 查询该mcn的红人
        $field = "kol_id, kol_nickname, kol_avatar";
        $kols = $this->kol->GetDataList(['kol_mcn' => $mcnInfo['mcn_id']], '', $field);
        $kols = array_column($kols, null, 'kol_id');

        // 获取经纪人的红人id
        $sql = "select mk_agent,GROUP_CONCAT(mk_kol) as kolids from m15_mcn_kol where m15_mcn_kol.mk_mcn = {$mcnInfo['mcn_id']} group by mk_agent";
        $agentKols = Db::query($sql);
        $agentKols = array_column($agentKols, 'kolids', 'mk_agent');


        // 获取分组内的红人
        $groupData = [];
        foreach ($macAgents as $key => $value) {
            $macAgents[$key]['kol_num'] = 0;
            $macAgents[$key]['kols'] = [];

            if(key_exists($value['agent_id'], $agentKols)) {
                $kolIds = explode(',', $agentKols[$value['agent_id']]);
                $macAgents[$key]['kol_num'] = count($kolIds);

                $temp = [];
                foreach ($kolIds as $kolId) {
                    if(key_exists($kolId, $kols)) {
                        array_push($temp, $kols[$kolId]);
                    }
                }
                $macAgents[$key]['kols'] = $temp;

            }
        }

        // 红人信息
        $this->assign('kols',$kols);

        // 分组信息
        $this->assign('macAgents',$macAgents);

        // mcn个人信息
        $this->assign('data',$mcnInfo);
        return view();
    }


    //更改展示状态
    public function KolShow($id,$type)
    {
        $McnKol = new McnKolModel();

        return $McnKol->where('mk_kol',$id)->update(['mk_isshow'=>$type]) ? array('code'=>1,'msg'=>'修改成功') : array('code'=>0,'msg'=>'修改失败');
    }

    // 可认领红人列表
    public function KolList($keyword = '')
    {
        $where = [];
        $where['kol_mcn'] = 0;
        if($keyword) {
            $where['kol_nickname|kol_number'] = ['like', '%' . $keyword . '%'];
        }

        // 根据关键词搜索未被认领的红人
        $list = $this->kol->GetDataList($where);

        // 查询出该mcn发送过的认领记录
        $rel = $this->mcnKol->GetDataList(['mk_mcn' => $this->data['data']['mcn_id'], 'mk_isagree' => 0]);
        $rel = array_column($rel, null, 'mk_kol');

        // 是否已发送过认领
        foreach ($list as $key => $value) {
            $list[$key]['isClaim'] = array_key_exists($value['kol_id'], $rel) ? 1 : 0;
        }

        if($list) {
            return ['code'=>1,'msg'=>'获取成功','data'=>$list];
        } else {
            return ['code'=>0,'msg'=>'获取失败'];
        }
    }

    // 确认认领
    public function confirmClaim($kolId, $isall = 0)
    {
        // mcn信息
        $mcnInfo = $this->data['data'];

        // 判断是单个认领还是批量认领
        if($isall == 0) { // 单个
            // m15_mcn_kol表插入数据
            $data = [
                'mk_mcn' => $mcnInfo['mcn_id'],
                'mk_kol' => $kolId,
                'mk_agent' => 0,
                'mk_group' => 0,
                'mk_isagree' => 0,
                'mk_time' => time(),
            ];
            $relRes = $this->mcnKol->saveData($data);

            // 红人同意认领后m15_kol表修改kol_mcn字段
//            $kolRes = $this->kol->UpdateData(['kol_id' => $kolId, 'kol_mcn' => $mcnInfo['mcn_id']]);

            if($relRes['code']) {
                return ['code'=>1,'msg'=>'发送认领成功'];
            } else {
                return ['code'=>0,'msg'=>'发送认领失败'];
            }

        } else { // 批量
            // m15_mcn_kol表插入数据
            $kolIds = $kolId;
            $data = [];
            foreach ($kolIds as $key => $value) {
                $data[$key] = [
                    'mk_mcn' => $mcnInfo['mcn_id'],
                    'mk_kol' => $value,
                    'mk_agent' => 0,
                    'mk_group' => 0,
                    'mk_isagree' => 0,
                    'mk_time' => time(),
                ];
            }

            // 红人同意认领后m15_kol表修改kol_mcn字段
//            $kolRes = $this->kol->UpdateData(['kol_id' => $kolIds, 'kol_mcn' => $mcnInfo['mcn_id']]);

            $result = $this->mcnKol->insertAll($data);
            if($result) {
                return ['code'=>1,'msg'=>'发送认领成功'];
            } else {
                return ['code'=>0,'msg'=>'发送认领失败'];
            }
        }

    }

    //解除认领
    public function Kolremove($id)
    {
        $McnKol = new McnKolModel();

        // m15_kol表修改kol_mcn字段

        // 删除m15_mcn_kol表数据

        return $McnKol->where('mk_kol',$id)->delete() ? array('code'=>1,'msg'=>'解除认领成功') : array('code'=>0,'msg'=>'解除认领失败');
    }

    //提示页
    public function prompt($msg)
    {
        $this->assign('msg',$msg);
        return view();
    }
}
