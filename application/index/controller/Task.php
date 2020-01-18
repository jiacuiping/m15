<?php
namespace app\index\controller;

use app\admin\model\Package as PackageModel;
use app\admin\model\UserType;
use app\index\controller\LoginBase;
use think\Session;

use app\admin\model\UserVip;
use app\admin\model\LoginLog;
use app\admin\model\UserAccount;
use app\admin\model\User as UserModel;

class Task extends LoginBase
{
    private $Model;
	public function __construct()
	{
		parent::__construct();
        $this->Model = new PackageModel();

        $this->Vip = new UserVip;
        $this->User = new UserModel;
		$this->LoginLog = new LoginLog;
        $this->Account = new UserAccount;


        $user = session::get('user');

        $selfdy = $this->Account->GetOneData(array('account_user'=>$user['user_id'],'account_is_self'=>1,'account_authstatus'=>1));

        $user['dyaccount'] = $selfdy ? $selfdy['account_nikename'] : '暂未绑定';

        // 用户类型
        $userTypeModel = new UserType();
        $user['user_type_text'] = $userTypeModel->GetField(['type_id' => $user['user_type']], 'type_name');

        $this->User = $user;
        $this->assign('user',$user);
	}


    //添加数据
    public function create()
    {
        return request()->isPost() ? $this->Model->CreateData(input('post.')) : view();
    }

    //修改数据
    public function update($id = 0)
    {
        $this->assign('package',$this->Model->GetOneDataById($id));
        return request()->isPost() ? $this->Model->UpdateData(input('post.')) : view();
    }

    // 详情
    public function detail($id = 0)
    {
        $this->assign('package',$this->Model->GetOneDataById($id));
        return view();
    }

    //删除数据
    public function delete($id)
    {
        return $this->Model->DeleteData($id);
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
