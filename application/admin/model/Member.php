<?php
namespace app\admin\model;
use think\Model;
use think\Validate;
/*
 * 会员登陆表数据模型
 **/
class Member extends Model
{
    protected $autoWriteTimestamp = true;
    protected $rule = [
        'member_mobile|登陆账号'       => 'require|number|unique:member',
        'member_password|密码'       => 'require',
    ];

    /**
     * 分页读取数据
     * @param array $where   条件
     * @param int   $page    第几页
     * @param int   $limit   每页的条数
     **/
    public function GetListByPage($where=array(), $page=1, $limit=10, $order='member_id desc')
    {   
        return $this->where($where)->page($page,$limit)->order($order)->select();
    }

    //获取数据列表，不分页
    public function GetDataList($where=array(), $order='member_id desc')
    {
        return $this->where($where)->order($order)->select();
    }

    /**
     * 获取总条数
     * @param array $param 主键
     **/
    public function GetCount($where=array())
    {
        return $this->where($where)->count();
    }

    /**
     * 通过主键获取用户信息
     * @param array $id 主键
     **/
    public function GetMemberInfoById($id=0)
    {
        return $this->where('member_id',$id)->find();
    }

    /**
     * 通过条件获取用户信息
     * @param array $where 条件
     **/
    public function GetMemberInfoByWhere($where=array())
    {
        return $this->where($where)->find();  
    }

    /**
     * 添加操作
     * @param array $param 需要添加的数组
     **/
    public function CreateData($param)
    {

        $validate = new Validate($this->rule);
        $result   = $validate->check($param);

        if(!$result)
            return array('code'=>0,'msg'=>$validate->getError());

        $pass = EncryptionPassword($param['member_password']);

        $param['member_password'] = $pass['password'];
        $param['member_encrypt_str'] = $pass['login_encrypt_str'];
        $param['member_time'] = time();

        $res = $this->allowField(true)->save($param);

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'添加成功');
    }

    /**
     * 修改操作
     * @param array $param 需要修改的数组
     **/
    public function UpdateData($param)
    {
        $res = $this->allowField(true)->save($param, ['member_id' => $param['member_id']]);

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'修改成功');
    }

    /**
     * 删除数据
     * @param int $id 删除数据的id
     **/
    public function DeleteData($id)
    {
        return $this->where('member_id',$id)->delete() ? array('code'=>1,'msg'=>'删除成功') : array('code'=>0,'msg'=>'删除失败');
    }
}