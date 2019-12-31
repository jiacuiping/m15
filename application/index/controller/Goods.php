<?php
namespace app\index\controller;

use think\Controller;
use app\api\controller\GetData;
use app\api\controller\Interfaces;

class Goods extends LoginBase
{
    private $GetData;

    public function __construct()
    {
        parent::__construct();
        $this->GetData = new GetData;
    }

    //红人搜索
    public function search()
    {
        $obj = new Interfaces;
        $obj->GetGoods(1);


        die;

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
