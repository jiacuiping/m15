<?php
namespace app\index\controller;

use think\Session;
use think\Controller;
use app\admin\model\Kol as KolModel;
use app\api\controller\GetData;
use app\api\controller\Interfaces;

use app\admin\model\Mcn;
use app\admin\model\McnKol;
use app\admin\model\Package;
use app\admin\model\Contrast;
use app\admin\model\UserAccount;

class Kol extends LoginBase
{
	private $GetData;

	public function __construct()
	{
		parent::__construct();
		$this->GetData = new GetData;
        $this->assign('specification',$this->GetData->GetSpecification('all'));
        $this->assign('citys',$this->GetData->GetCitys());
		$this->assign('sort',$this->GetData->GetVideoSort());
	}

    //红人搜索
    public function search()
    {
        if(request()->isPost()){

            $data = input('post.');

            $where = array();

            if($data['type'] == 'senior'){

                if($data['sort'] != 0)
                    $where['k.kol_sort'] = $data['sort'];
                
                if($data['fans'] != 0)
                    $where['kt.kt_fans'] = array('between',$data['fans']);
                
                if($data['like'] != 0)
                    $where['kt.kt_like'] = array('between',$data['like']);
                
                if($data['hot'] != 0)
                    $where['kt.kt_hot'] = array('between',$data['hot']);
  
                if($data['province'] != 0)
                    $where['k.kol_province'] = $data['province'];
  
                if(isset($data['sex']) && $data['sex'] != 0)
                    $where['k.kol_sex'] = $data['sex'];
                
                if(isset($data['vname']) && $data['vname'] != '')
                    $where['k.kol_achievement'] = array('like',$data['vname']);

                if($data['keyword'] != '')
                    $where['k.kol_nickname']  = array('like',"%" . $data['keyword'] . "%");

            }else{
                if($data['keyword'] != '')
                    $where['k.kol_nickname']  = array('like',"%" . $data['keyword'] . "%");
            }

            $result = $this->GetData->GetKolList($where);

            $this->assign('count',count($result));
            $this->assign('data',$data);
            $this->assign('result',$result);
            return view('result');

        }else{

            $condition = array(
                'sort'      => 0,
                'fans'      => 0,
                'like'      => 0,
                'hot'       => 0,
                'sex'       => 0,
                'age'       => 0,
                'vname'     => '',
                'province'  => 0,
            );

            $this->assign('condition',$condition);
            return view();
        }
    }

	//红人列表
    public function rank()
    {
		// //筛选：分类、关键词、地区
		// //排序：指数排序、涨粉排序、成长指数、蓝V排行榜
		$condition = array(
            'keyword'       => '',
			'city'          => 0,
			'sort'	        => 0,
			'createtime'    => 7,
			'order'		    => 'kt.kt_hot desc',
            'page'          => 1,
		);

        $where = array();

        $keyword = input('param.keyword');
        if($keyword && $keyword != ''){
            $condition['keyword'] = $keyword;
            $where['k.kol_nickname'] = ['like',"%" . $keyword . "%"];
        }

        $sort = input('param.sort');
        if($sort && $sort != 0){
            $condition['sort'] = $sort;
            $where['k.kol_sort'] = $sort;
        }

        $city = input('param.city');
        if($city && $city != 0){
            $condition['city'] = $city;
            $where['k.kol_province'] = $city;
        }
        
        $order = input('param.order');
        if($order == '' || $order == 'lanv' || $order == 'kt.kt_hot desc'){
            $condition['order'] = 'kt.kt_hot desc';
        }else
            $condition['order'] = $order;

        if($order == 'lanv')
            $where['kol_verifyname'] = array('neq',NULL);

        $createtime = input('param.createtime');
        if($createtime && $createtime !== 0){
            $time = $createtime < 1 ? time() - $createtime*86400 : strtotime("-$createtime day");
            $condition['createtime'] = $createtime;
            $where['k.kol_time'] = array('between',[(int)$time,time()]);
        }

        $page = input('param.page');
        if($page && $page !== 1) $condition['page'] = $page;

        $count      = $this->GetData->GetKolCount($where);
        $allpage    = intval(ceil($count / 50));
        $data       = $this->GetData->GetKolList($where,$condition['page'],100,$condition['order']);

        $this->assign('condition',$condition);
        $this->assign('lasttime',date('Y-m-d'));
        $this->assign('kol',$data);
        $this->assign('count',$count);
		return view();
    }

    //KOL详情
    public function info($kid=0,$type='data',$order='create_time',$goods=0,$keyword='')
    {
        $kol = $this->GetData->GetKolInfo($kid);
        $trend = $this->GetData->GetKolOneTrend($kid);
        $condition = array();
        if($type == 'data'){
            $change = $this->GetData->GetKolIncData($kid,30);
            $recently = $this->GetData->GetChangeTrend($kid,'kolv');
            $trends = $this->GetData->GetChangeTrend($kid);

            $this->assign('trends',$trends);
            $this->assign('change',$change);
            $this->assign('recently',$recently);
        }elseif($type == 'video'){

            $condition = array(
                'keyword'   => '',
                'goods'     => 0,
                'order'     => 'v.create_time desc',
            );

            $where['v.video_apiuid'] = $kol['kol_uid'];

            if($keyword != ''){
                $condition['keyword'] = $keyword;
                $where['v.video_desc'] = ['like',"%" . $keyword . "%"];
            }

            if($goods != 0){
                $condition['goods'] = 1;
                $where['v.video_goods'] = array('neq',0);
            }

            $condition['order'] = $order == 'create_time' ? 'v.create_time desc' : 'vt.vt_hot desc';

            $videos = $this->GetData->GetVideoList($where,1,50,$condition['order']);
            
            $this->assign('videos',$videos);
        }elseif($type == 'fans'){
            $public = $this->GetData->GeetPublicOpinionInfo($kol['kol_id'],'kol');
            if($public['code'] == 1)
                $this->assign('public',$public['data']);
            else
                $this->assign('public',array());
        }elseif($type == 'mcn'){

            $Mcn = new Mcn;
            $McnKol = new McnKol;

            $mcnid = $McnKol->GetField(array('mk_kol'=>$kid),'mk_mcn');

            if($mcnid){

                $Kol = new KolModel;

                $mcninfo = $Mcn->GetOneDataById($mcnid);

                $kolsid = $McnKol->GetColumn(array('mk_mcn'=>$mcnid),'mk_kol');

                $kols = $Kol->GetListByPage(array('kol_id'=>array('in',$kolsid)),1,16);
                
                $this->assign('kols',$kols);
                $this->assign('mcninfo',$mcninfo);
            }
            $this->assign('mcnid',$mcnid);
        }elseif($type == 'task'){
            $Package = new Package;
            $UserAccount = new UserAccount;
            $userId = $UserAccount->GetField(array('account_kol'=>$kid,'account_is_self'=>1),'account_user');
            $task = $userId ? $Package->GetDataList(['package_user' => $userId]) : array();
            $this->assign('task',$task);
        }
        $this->assign('trend',$trend);
        $this->assign('kol',$kol);
        $this->assign('type',$type);
        $this->assign('condition',$condition);
        return view();
    }

    //红人收录
    public function included($keyword)
    {
        $Interfaces = new Interfaces;

        return $Interfaces->SearchUser($keyword);
    }

    //收录
    public function goincluded($uid,$isall=0)
    {
        $Interfaces = new Interfaces;

        $KolInfo = $this->GetData->GetKolInfo($uid,'kol_uid');

        if($KolInfo) return array('code'=>1,'msg'=>'KOL已收录');

        $result = $Interfaces->GetUserInfo($uid);

        return $result ? array('code'=>1,'msg'=>'收录成功') : array('code'=>0,'msg'=>'收录失败');
    }

    //红人对比搜索页
    public function contrast($type='search')
    {
        $keyword = '';
        $kols = array();
        $Kol = new KolModel;
        if(request()->isPost()){
            $keyword = input('post.keyword');
            $kols = $Kol->GetDataList(array('kol_number|kol_nickname'=>array('like',"%" . $keyword . "%")));
            $type = 'contrast';
        }

        if($type == 'history'){
            $Contrast = new Contrast;
            $history = $Contrast->GetDataList(array('contrast_user'=>session::get('user.user_id')));
            foreach ($history as $key => $value) {
                $history[$key]['avatars'] = $Kol->GetColumn(array('kol_id'=>array('in',$value['contrast_kols'])),'kol_avatar');
            }
            $this->assign('history',$history);
        }

        $this->assign('kols',$kols);
        $this->assign('type',$type);
        $this->assign('keyword',$keyword);
        return view();
    }

    //红人对比
    public function contrastresult()
    {
        $Kol = new KolModel;
        $ids = input('param.')['ids'];
        $kols = $this->GetData->GetKolList(array('kol_id'=>array('in',$ids)));
        $this->assign('kols',$kols);
        return view();
    }
}