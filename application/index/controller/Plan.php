<?php
namespace app\index\controller;

//计划市场控制器
use think\Session;
use app\admin\model\PlanApply;
use app\admin\model\Kol;
use app\admin\model\KolTrend;
use app\admin\model\UserAccount;
use app\api\controller\GetData;
use app\admin\model\TbgoodsSort;
use app\api\controller\TbInterfaces;
use app\admin\model\Plan as PlanModel;

class Plan extends LoginBase
{
    private $Model;
    private $UserAccount;

    public function __construct()
    {
        parent::__construct();
        $this->Model = new PlanModel;
        $this->UserAccount = new UserAccount;

        if(!$this->UserAccount->GetOneData(array('account_user'=>session::get('user.user_id'),'account_is_self'=>1)) && strpos($_SERVER['REQUEST_URI'],'plan/prompt/msg/') === false)
            $this->redirect('base/prompt',['msg'=>'使用此功能，请使用抖音扫码登录！']);
    }

    //数据列表
    public function index()
    {
        $condition = array(
            'sort'              => 0,
            'keyword'           => '',
            'createtime'        => 0,
            'commission'        => 0,
            'price'             => 0,
            'order'             => 0,
            'page'              => 1,
            'order'             => 'plan_id desc',
        );

        $where = array();

        $sort = input('param.sort');
        if($sort && $sort !== 0){
            $condition['sort'] = $sort;
            $where['plan_goods_sort'] = $sort;
        }

        $keyword = input('param.keyword');
        if($keyword && $keyword != ''){
            $condition['keyword'] = $keyword;
            $where['plan_title'] = ['like',"%" . $keyword . "%"];
        }

        $commission = input('param.commission');
        if($commission && $commission !== 0){
            $condition['commission'] = $commission;
            $where['plan_commission'] = array('between',$commission);
        }

        $price = input('param.price');
        if($price && $price !== 0){
            $condition['price'] = $price;
            $where['plan_discount_price'] = array('between',$price);
        }
        
        $page = input('param.page');
        if($page && $page !== 1) $condition['page'] = $page;

        $order = input('param.order');
        if($order && $order !== 'plan_id desc') $condition['order'] = $order;

        $createtime = input('param.createtime');
        if($createtime && $createtime !== 0){
            $time = $createtime < 1 ? time() - $createtime*86400 : strtotime("-$createtime day");
            $condition['createtime'] = $createtime;
            $where['plan_time'] = array('between',[(int)$time,time()]);
        }

        $count      = $this->Model->GetCount($where);
        $allpage    = intval(ceil($count / 100));
        $data       = $this->Model->GetListByPage($where,$condition['page'],100,$condition['order']);
        $TbGoodsSort = new TbgoodsSort;
        $GetData = new GetData;

        $this->assign('plan',$data);
        $this->assign('condition',$condition);
        $this->assign('date',date('Y-m-d H').':00');
        $this->assign('specification',$GetData->GetSpecification('createtime'));
        $this->assign('sort',$TbGoodsSort->GetDataList(array('sort_parent'=>0,'sort_status'=>1)));
        return view();
    }

    //发布计划
    public function release()
    {
        $user = session::get('user');
        if($user['user_type'] != 3 && $user['user_type'] != 5) return array('code'=>0,'msg'=>'暂无权限!');
        if(request()->isPost()){
            $data = input('post.');
            $data['plan_user'] = $user['user_id'];
            $data['plan_audit'] = $user['user_type'] == 5 ? $user['user_id'] : 0;   //团长发布任务，自动审核通过，广告商发布任务，待团长审核
            $data['plan_start_time'] = strtotime($data['plan_start_time']);
            $data['plan_end_time'] = strtotime($data['plan_end_time']);
            $data['plan_sample_remaining'] = $data['plan_sample_sum'];
            $this->CreateSort($data['plan_goods_sort'],$data['plan_goods_subclass']);
            return $this->Model->CreateData($data);
        }else
            return view();
    }


    //计划详情
    public function info($plan_id=0,$is_show=false)
    {
        $data = $this->Model->GetOneDataById($plan_id);

        $this->assign('is_show',$is_show);
        $this->assign('data',$data);
        return view();
    }

    //报名
    public function apply($plan_id = 0)
    {
        if(request()->isPost()){
            $data = input('post.');
            $PlanApply = new PlanApply;
            $data['apply_user'] = session::get('user.user_id');
            if($PlanApply->GetOneData(array('apply_plan'=>$data['apply_plan'],'apply_user'=>$data['apply_user']))) return array('code'=>0,'msg'=>'您已报名该计划，不能重复报名');
            return $PlanApply->CreateData($data);
        }else{
            $this->assign('planId',$plan_id);
            $this->assign('prov',$this->GetCity());
            return view();
        }
    }


    //我发布的计划
    public function myplan()
    {
        $condition = array(
            'sort'              => 0,
            'keyword'           => '',
            'createtime'        => 0,
            'commission'        => 0,
            'price'             => 0,
            'order'             => 0,
            'page'              => 1,
            'order'             => 'plan_id desc',
        );

        $where['plan_user|plan_audit'] = array('eq',session::get('user.user_id'));

        $sort = input('param.sort');
        if($sort && $sort !== 0){
            $condition['sort'] = $sort;
            $where['plan_goods_sort'] = $sort;
        }

        $keyword = input('param.keyword');
        if($keyword && $keyword != ''){
            $condition['keyword'] = $keyword;
            $where['plan_title'] = ['like',"%" . $keyword . "%"];
        }

        $commission = input('param.commission');
        if($commission && $commission !== 0){
            $condition['commission'] = $commission;
            $where['plan_commission'] = array('between',$commission);
        }

        $price = input('param.price');
        if($price && $price !== 0){
            $condition['price'] = $price;
            $where['plan_discount_price'] = array('between',$price);
        }

        $page = input('param.page');
        if($page && $page !== 1) $condition['page'] = $page;

        $order = input('param.order');
        if($order && $order !== 'plan_id desc') $condition['order'] = $order;

        $createtime = input('param.createtime');
        if($createtime && $createtime !== 0){
            $time = $createtime < 1 ? time() - $createtime*86400 : strtotime("-$createtime day");
            $condition['createtime'] = $createtime;
            $where['plan_time'] = array('between',[(int)$time,time()]);
        }

        $count      = $this->Model->GetCount($where);
        $allpage    = intval(ceil($count / 100));
        $data       = $this->Model->GetListByPage($where,$condition['page'],100,$condition['order']);

        $GetData = new GetData;
        $TbGoodsSort = new TbgoodsSort;

        $this->assign('plan',$data);
        $this->assign('condition',$condition);
        $this->assign('date',date('Y-m-d H').':00');
        $this->assign('specification',$GetData->GetSpecification('createtime'));
        $this->assign('sort',$TbGoodsSort->GetDataList(array('sort_parent'=>0,'sort_status'=>1)));
        return view();
    }

    //查看报名
    public function showapply($plan_id=0)
    {
        $KolModel = new Kol;
        $KolTrend = new KolTrend;
        $PlanApply = new PlanApply;
        $UserAccount = new UserAccount;

        $users = $PlanApply->GetDataList(array('apply_plan'=>$plan_id));

        foreach ($users as $key => $value) {
            $KolId = $UserAccount->GetField(array('account_user'=>$value['apply_user'],'account_is_self'=>1),'account_kol');
            $KolInfo = $KolModel->GetOneData(array('kol_id'=>$KolId));

            $users[$key]['kol'] = $KolInfo;
            $users[$key]['kol']['fans'] = $KolTrend->GetField(array('kt_kol_id'=>$KolId),'kt_fans');
            $users[$key]['complete'] = $PlanApply->GetCount(array('apply_user'=>$value['apply_user'],'apply_status'=>1,'apply_schedule'=>30));
        }



        $this->assign('users',$users);
        return view();
    }


    //已报名的计划
    public function orderplan()
    {
        $condition = array(
            'sort'              => 0,
            'keyword'           => '',
            'createtime'        => 0,
            'commission'        => 0,
            'price'             => 0,
            'order'             => 0,
            'page'              => 1,
            'order'             => 'plan_id desc',
        );

        $PlanApply = new PlanApply;

        $ids = $PlanApply->GetColumn(array('apply_user'=>session::get('user.user_id')),'apply_plan');

        $where['plan_id'] = array('in',$ids);

        $sort = input('param.sort');
        if($sort && $sort !== 0){
            $condition['sort'] = $sort;
            $where['plan_goods_sort'] = $sort;
        }

        $keyword = input('param.keyword');
        if($keyword && $keyword != ''){
            $condition['keyword'] = $keyword;
            $where['plan_title'] = ['like',"%" . $keyword . "%"];
        }

        $commission = input('param.commission');
        if($commission && $commission !== 0){
            $condition['commission'] = $commission;
            $where['plan_commission'] = array('between',$commission);
        }

        $price = input('param.price');
        if($price && $price !== 0){
            $condition['price'] = $price;
            $where['plan_discount_price'] = array('between',$price);
        }

        $page = input('param.page');
        if($page && $page !== 1) $condition['page'] = $page;

        $order = input('param.order');
        if($order && $order !== 'plan_id desc') $condition['order'] = $order;

        $createtime = input('param.createtime');
        if($createtime && $createtime !== 0){
            $time = $createtime < 1 ? time() - $createtime*86400 : strtotime("-$createtime day");
            $condition['createtime'] = $createtime;
            $where['plan_time'] = array('between',[(int)$time,time()]);
        }

        $count      = $this->Model->GetCount($where);
        $allpage    = intval(ceil($count / 100));
        $data       = $this->Model->GetListByPage($where,$condition['page'],100,$condition['order']);

        foreach ($data as $key => $value) {
            $data[$key]['apply'] = $PlanApply->GetOneData(array('apply_plan'=>$value['plan_id'],'apply_user'=>session::get('user.user_id')));
        }

        $GetData = new GetData;
        $TbGoodsSort = new TbgoodsSort;

        $this->assign('plan',$data);
        $this->assign('condition',$condition);
        $this->assign('date',date('Y-m-d H').':00');
        $this->assign('specification',$GetData->GetSpecification('createtime'));
        $this->assign('sort',$TbGoodsSort->GetDataList(array('sort_parent'=>0,'sort_status'=>1)));
        return view();
    }

    //修改报名请求
    public function ChangeStatus($applyid,$status)
    {
        $PlanApply = new PlanApply;

        $applyInfo = $PlanApply->GetOneData(array('apply_id'=>$applyid));
        $planInfo = $PlanApply->GetOneData(array('plan_id'=>$applyInfo['apply_plan']));

        if($planInfo['plan_user'] != session::get('user.user_id') && $planInfo['plan_audit'] != session::get('user.user_id')) return array('code'=>0,'msg'=>'暂无权限');

        return $PlanApply->UpdateData(array('apply_id'=>$applyid,'apply_status'=>$status));
    }

    //请求接口，查询商品信息
    public function GetGoods()
    {
        $goods_id = input('post.goods_id');

        if(!$goods_id) return array('code'=>0,'msg'=>'商品ID参数错误');

        $TbInterfaces = new TbInterfaces;

        return $TbInterfaces->GetGoodsInfo($goods_id);
    }

    //获取下级城市列表
    public function GetCity($adcode = 0)
    {
        if($adcode == 0) return db('area')->where('pid',0)->select();

        $thisinfo = db('area')->where('id',$adcode)->find();

        if(!$thisinfo) return json_encode(array('code'=>0,'msg'=>'地区不存在'));

        $citys = db('area')->where('pid',$thisinfo['id'])->select();

        return array('code'=>1,'msg'=>'获取成功','citys'=>$citys,'level'=>$thisinfo['level']);
    }

    //添加分类
    public function CreateSort($sort,$csort)
    {
        $TbGoodsSort = new TbgoodsSort;

        $sortInfo = $TbGoodsSort->GetOneData(array('sort_name'=>$sort,'sort_level'=>1));

        $id = !$sortInfo ? $TbGoodsSort->CreateData(array('sort_name'=>$sort,'sort_level'=>1))['id'] : $sortInfo['sort_id'];

        if(!$TbGoodsSort->GetOneData(array('sort_name'=>$csort,'sort_level'=>2)))
            db('tbgoods_sort')->insert(array('sort_name'=>$csort,'sort_level'=>2,'sort_parent'=>$id,'sort_time'=>time()));
    }
}