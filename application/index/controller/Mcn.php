<?php
namespace app\index\controller;

use app\index\controller\LoginBase;
use app\api\controller\GetData;
use app\admin\model\Kol as KolModel;
use app\admin\model\McnKol;
use app\admin\model\McnAgent;
use app\api\controller\Interfaces;
use app\admin\model\McnGroup as McnGroupModel;
use think\Db;


class Mcn extends LoginBase
{
	private $GetData;
    private $data;
    private $mcnGroup;
    private $kol;

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

			$McnKol = new McnKol;

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

            $McnKol = new McnKol;
            $McnAgent = new McnAgent;

            $kols = $McnKol->field('mk_kol,mk_agent,mk_group,mk_isshow')->where($where)->select();
            //GetColumn($where,'mk_kol');

            //$orderBy = $order == 'default' ? 'kt.kt_fans desc' : $order;

            $kol = $this->GetData->GetKolList(array('kol_id'=>array('in',array_column($kols,'mk_kol'))),1,100);

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
        $kols = $this->kol->GetDataList(['kol_mcn' => $mcnInfo['mcn_id']], '', 'kol_id, kol_nickname, kol_avatar');
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

        // 分组信息
        $this->assign('macGroups',$macGroups);

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


    //经纪人管理
    public function Agent()
    {
    	return view();
    }

    //更改展示状态
    public function KolShow($id,$type)
    {
        $McnKol = new McnKol;

        return $McnKol->where('mk_kol',$id)->update(['mk_isshow'=>$type]) ? array('code'=>1,'msg'=>'修改成功') : array('code'=>0,'msg'=>'修改失败');
    }

    //解除认领
    public function Kolremove($id)
    {
        $McnKol = new McnKol;

        return $McnKol->where('mk_kol',$id)->delete() ? array('code'=>1,'msg'=>'解除认领成功') : array('code'=>0,'msg'=>'解除认领失败');
    }

    //提示页
    public function prompt($msg)
    {
        $this->assign('msg',$msg);
        return view();
    }
}
