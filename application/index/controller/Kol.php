<?php
namespace app\index\controller;

use think\Controller;
use app\admin\model\Kol as KolModel;
use app\api\controller\GetData;
use app\api\controller\Interfaces;

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
        // $Interfaces = new Interfaces;

        // $Interfaces->CreateQrcode('https://www.iesdouyin.com/share/video/6741695209534524676/?region=CN&mid=6664106036007275277&u_code=0&titleType=title','80632952083');

        // die;


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

            $public = $this->GetData->GeetPublicOpinionInfo($kol['kol_uid'],'kol');
            if($public['code'] == 1)
                $this->assign('public',$public['data']);
            else
                $this->assign('public',array());
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


        if($isall && !db('allvideo')->where(array('kol_uid'=>$uid))->find())
            db('allvideo')->insert(array('kol_uid'=>$uid));

        if($KolInfo) return array('code'=>1,'msg'=>'KOL已收录');

        $result = $Interfaces->GetUserInfo($uid);

        return $result ? array('code'=>1,'msg'=>'收录成功') : array('code'=>0,'msg'=>'收录失败');
    }

    //红人对比搜索页
    public function contrast($type='search')
    {
        $this->assign('type',$type);
        return view();
    }

    //红人对比
    public function contrastresult()
    {
        $keyword = input('post.keyword');

        $Kol = New KolModel;

        $result = $Kol->where('kol_nickname|kol_number','like',"%".$keyword."%")->select();

        $this->assign('result',$result);
        return view();
    }
}
