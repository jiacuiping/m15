<?php
namespace app\index\controller;

use app\admin\controller\Upload;
use app\admin\model\Certification;
use app\admin\model\Order;
use app\admin\model\UserType;
use app\index\controller\LoginBase;
use think\Session;

use app\admin\model\UserVip;
use app\admin\model\LoginLog;
use app\admin\model\UserAccount;
use app\admin\model\UserAddress;
use app\admin\model\User as UserModel;

class User extends LoginBase
{
	public function __construct()
	{
		parent::__construct();

        $this->Vip = new UserVip;
        $this->User = new UserModel;
		$this->LoginLog = new LoginLog;
        $this->Account = new UserAccount;


        $user = session::get('user');

        $selfdy = $this->Account->GetOneData(array('account_user'=>$user['user_id'],'account_is_self'=>1,'account_authstatus'=>1));

        $user['dyaccount'] = $selfdy ? $selfdy['account_nikename'] : '暂未绑定';

        $this->User = $user;
        $this->assign('user',$user);
	}

	//用户主页
    public function index($type = 'log')
    {
        if($type == 'log'){
            $logs = $this->LoginLog->GetDataList(array('log_user'=>$this->User['user_id']));
            $this->assign('machinecode',gethostname());
            $this->assign('logs',$logs);
        }elseif($type == 'auth'){
            // 查询认证类型
            $userTypeModel = new UserType();
            $userTypeList = $userTypeModel->GetDataList(['type_is_cert' => 1]);
            $userTypeList = array_column($userTypeList, null, 'type_id');
            // 如果用户已认证，查出认证信息
            $user = session::get('user');
            $certInfo = [];
            if(array_key_exists($user['user_type'], $userTypeList)) {
                $certificationModel = new Certification();
                $certInfo = $certificationModel->GetOneDataById($user['user_certification']);

                $certInfo['certification_status_text'] = $certificationModel->getStatusText($certInfo['certification_status']);

                $this->assign('isNew',0);
            } else {

                $certInfo = ['certification_buimg' => '', 'certification_OpeningPermit' => '', 'certification_LegalPerson' => '',
                    'certification_CreditCode' => '', 'certification_bank_account' => '', 'certification_bankCode' => '',
                    'certification_IdCard' => '', 'certification_organization_name' => '', 'certification_status' => 0, 'certification_id' => 0
                    ];
                $this->assign('isNew',1);
            }
            $this->assign('user',$user);
            $this->assign('certInfo',$certInfo);
            $this->assign('userTypeList',$userTypeList);
        }

        $this->assign('type',$type);
        
        return view();
    }


    //套餐管理
    public function package()
    {
        return view();
    }

    //任务中心
    public function task()
    {
        return view();
    }

    //特权方法

    /**
     * @param string $type info:会员信息  invoice:索取发票  members:会员开通记录  integral:积分充值记录
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function privilege($type='info')
    {
        if($type == 'info') {
            $this->assign('vips',db('vip_level')->where('level_status',1)->select());
        } elseif ($type == 'invoice'){
            $userId = session::get('user.user_id');

            // 获取消费记录
            $orderModel = new Order();
            $orderList = $orderModel->GetDataList(['order_user' => $userId, 'order_status' => 10, 'order_invoice' => 0]);
            foreach ($orderList as $key => $value) {
                $orderList[$key]['order_type_text'] = $orderModel->getOrderTypeText($value['order_type']);
                $orderList[$key]['order_invoice_text'] = $orderModel->getOrderInvoiceText($value['order_invoice']);
            }

            // 总金额
            $sumPrice = array_sum(array_map(function($val){return $val['order_payprice'];}, $orderList));

            // 地址信息
            $cityinfo['province'] = db('area')->where('pid',0)->select();
            $cityinfo['citys'] = $cityinfo['areas'] = array();

            $this->assign('orderList',$orderList);
            $this->assign('sumPrice',$sumPrice);
            $this->assign('cityinfos',$cityinfo);
        }

        $this->assign('type',$type);
        return view();
    }


    //修改地址
    public function ChangeAddress()
    {
        $userId = session::get('user.user_id');
        $obj = new UserAddress;

        $data = input('post.');

        //为空判断
        if($data['address_province'] == 0) return array('code'=>0,'msg'=>'请选择省');
        if($data['address_city'] == 0) return array('code'=>0,'msg'=>'请选择市');
        if($data['address_area'] == 0) return array('code'=>0,'msg'=>'请选择区');
        if($data['address_info'] == '') return array('code'=>0,'msg'=>'详细地址不能为空');
        if($data['address_contact'] == '') return array('code'=>0,'msg'=>'联系人不可为空');
        if($data['address_mobile'] == '') return array('code'=>0,'msg'=>'请输入联系电话号码');

        $data['address_user'] = $userId;

        // 判断是否已有地址
        $address = $obj->GetOneData(['address_user' => $userId]);
        if($address) {
            $data['address_id'] = $address['address_id'];
            return $obj->UpdateData($data);
        } else {
            return $obj->CreateData($data);
        }
    }

    // 保存认证信息
    public function saveCertification()
    {
        $userId = session::get('user.user_id');
        $data = input('post.');

        //为空判断
        if($data['certification_organization_name'] == '') return array('code'=>0,'msg'=>'请输入机构名称');
        if($data['certification_CreditCode'] == '') return array('code'=>0,'msg'=>'请输入统一社会信用代码');
        if($data['certification_LegalPerson'] == '') return array('code'=>0,'msg'=>'请输入法人姓名');
        if($data['certification_IdCard'] == '' || !CheckIdCard($data['certification_IdCard'])) return array('code'=>0,'msg'=>'请输入合法的身份证号');
        if($data['certification_bank_account'] == '') return array('code'=>0,'msg'=>'请输入对公账户');
        if($data['certification_bankCode'] == '') return array('code'=>0,'msg'=>'请输入法人银行卡');
        if($data['certification_buimg'] == '') return array('code'=>0,'msg'=>'请上传营业执照');
        if($data['certification_OpeningPermit'] == '') return array('code'=>0,'msg'=>'请上传开户许可证');

        // 保存认证信息
        $certificationModel = new Certification();
        if($data['certification_id']) {
            $data['certification_status'] = 0;
            return $certificationModel->UpdateData($data);
        } else {
            $res = $certificationModel->CreateData($data);
        }
        if($res['code']) {
            // 保存成功，更新user表user_certification字段
            $userModel = new \app\admin\model\User();
            $userData = ['user_id' => $userId, 'user_certification' => $res['id']];
            $userInfo = $userModel->UpdateData($userData);
            if($userInfo['code']) {
                session::set('user.user_certification', $res['id']);
                return ['code'=>1,'msg'=>'认证信息保存成功'];
            } else {
                return ['code'=>0,'msg'=>'认证信息保存失败'];
            }
        } else {
            return $res;
        }
    }



    //获取下级城市列表
    public function selectCity($adcode)
    {
        $thisinfo = db('area')->where('id',$adcode)->find();

        if(!$thisinfo) return json_encode(array('code'=>0,'msg'=>'地区不存在'));

        $citys = db('area')->where('pid',$thisinfo['id'])->select();

        return array('code'=>1,'msg'=>'获取成功','citys'=>$citys,'level'=>$thisinfo['level']);
    }

    // 上传图片
    public function UploadImage($path='uploads')
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . $path);

            return $info ? array('code'=>1,'msg'=>'上传成功','path'=>'/'.$path.'/'.$info->getSaveName()) : array('code'=>0,'msg'=>$file->getError());
        }
    }
}
