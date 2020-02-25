<?php
namespace app\admin\model;
use think\Model;
use think\Validate;
/*
 * 计划
 **/
class Plan extends Model
{
    //声明主键
    protected $pk = 'plan_id';
    //自动写入时间戳
    protected $autoWriteTimestamp = true;
    //声明添加时间字段
    protected $createTime = 'plan_time';

    protected $rule = [
        'plan_title|计划标题'        => 'require',
        'plan_url|邀请链接'       => 'require',
        'plan_goods_id|关联商品'       => 'require',
        'plan_start_time|计划开始日期'     => 'require',
        'plan_end_time|计划结束日期'       => 'require',
    ];

    /**
     * 分页读取数据
     * @param array $where   条件
     * @param int   $page    第几页
     * @param int   $limit   每页的条数
     * @param string   $order   排序
     **/
    public function GetListByPage($where=array(), $page=1, $limit=10, $order="plan_id desc")
    {   
        return $this->where($where)->page($page,$limit)->order($order)->select();
    }

    //获取数据列表，不分页
    public function GetDataList($where=array(), $order="plan_id desc", $field = "*")
    {
        return $this->field($field)->where($where)->order($order)->select();
    }


    /**
     * 根据条件获取一条数据
     * @param array $param 主键
     **/
    public function GetOneData($where=array())
    {
        return $this->where($where)->find();
    }

    /**
     * 根据主键获取一条数据
     * @param array $param 主键
     **/
    public function GetOneDataById($id=0)
    {
        return $this->where($this->pk,$id)->find();
    }

    /**
     * 获取一列数据
     * @param array  $param 获取条件
     * @param string $field 字段名
     **/
    public function GetColumn($param,$field)
    {
        return $this->where($param)->column($field);
    }

    /**
     * 根据条件获取一个字段
     * @param array $param 主键
     **/
    public function GetField($where=array(),$field)
    {
        return $this->where($where)->value($field);
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
     * 添加操作
     * @param array $param 需要添加的数组
     **/
    public function CreateData($param)
    {
        $validate = new Validate($this->rule);
        $result   = $validate->check($param);

        if(!$result)
            return array('code'=>0,'msg'=>$validate->getError());

        $res = $this->allowField(true)->save($param);

        $id = $this->getLastInsID();

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'发布成功','id'=>$id);
    }

    /**
     * 修改操作
     * @param array $param 需要修改的数组
     **/
    public function UpdateData($param)
    {
        
        $res = $this->allowField(true)->save($param, [$this->pk => $param[$this->pk]]);

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'修改成功');
    }

    /**
     * 删除数据
     * @param int $id 删除数据的id
     **/
    public function DeleteData($id)
    {
        return $this->where($this->pk,$id)->delete() ? array('code'=>1,'msg'=>'删除成功') : array('code'=>0,'msg'=>'删除失败');
    }
}
