<?php
namespace app\index\controller;

use think\Controller;
use app\api\controller\GetData;

use app\admin\model\Video;

class Goods extends LoginBase
{
    private $GetData;

    public function __construct()
    {
        parent::__construct();
        $this->GetData = new GetData;

        $this->assign('sort',$this->GetData->GetGoodsSort());
        $this->assign('sales',$this->GetData->GetSpecification('sales')['sales']);
    }

    //商品搜索
    public function search()
    {
        $condition = array(
            'keyword'   => '',
            'sort'      => 0,
            'sales'     => 0,
            'order'     => 'gt_index desc',
        );

        if(request()->isPost()){

            $data = input('post.');

            $where = array();

            $order = 'gt_index desc';

            if($data['keyword'] != ''){
                $keyword = $data['keyword'];
                $where['g.gooods_name'] = ['like',"%" . $keyword . "%"];
                $condition['keyword'] = $keyword;
            }

            if(isset($data['sort']) && $data['sort'] != 0){
                $where['g.goods_type'] = $data['sort'];
                $condition['sort'] = $data['sort'];
            }

            if(isset($data['sales']) && $data['sales'] != 0){
                $where['gt.gt_sales'] = array('between',$data['sales']);
                $condition['sales'] = $data['sales'];
            }

            if(isset($data['order']) && $data['order'] != ''){
                $order = $data['order'];
                $condition['order'] = $data['order'];
            }

            $result = $this->GetData->GetGoodsList($where,1,100,$order);

            $this->assign('count',count($result));
            $this->assign('condition',$condition);
            $this->assign('date',date("Y-m-d"));
            $this->assign('result',$result);
            return view('result');

        }else{
            $this->assign('condition',$condition);
            return view();
        }
    }

    //商品详情
    public function info($goods=0,$type="hot")
    {
        if($goods == 0) $goods = array('code'=>0,'msg'=>'参数错误');

        $data = $this->GetData->GetGoodsInfo($goods);

        if($type == 'hot'){

        }elseif($type == 'video'){
            $videos = $this->GetData->GetVideoList(array('video_goods'=>$goods));
            $this->assign('videos',$videos);
        }elseif($type == 'kol'){

            $Video = new Video;

            $ids = $Video->GetColumn(array('video_goods'=>$goods),'video_apiuid');

            $kol = $this->GetData->GetKolList(array('kol_uid'=>array('in',$ids)));

            $this->assign('kol',$kol);
        }

        $this->assign('data',$data['data']);
        $this->assign('code',$data['code']);
        $this->assign('type',$type);
        return view();
    }




    //抖音好物榜
    public function rank()
    {
        $condition = array(
            'keyword'       => '',
            'city'          => 0,
            'sort'          => 0,
            'createtime'    => 7,
            'order'         => 'kt.kt_hot desc',
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
}
