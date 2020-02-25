<?php
namespace app\admin\model;
use think\Model;
use think\Validate;
/*
 * 商品分类 表数据模型
 **/
class GoodsSort extends Model
{
    //声明主键
    protected $pk = 'sort_id';
    //自动写入时间戳
    protected $autoWriteTimestamp = true;
    //声明添加时间字段
    protected $createTime = 'sort_time';
    //声明修改时间字段
    protected $updateTime = false;
    //关闭自动写入
    //protected $updateTime = false;
    //声明表名
    //protected $table = 'rotate';
    //声明只读字段 该字段写入后不可被修改
    //protected $readonly = ['name','email'];
    protected $rule = [
        'sort_name|分类名'       => 'require',
    ];

    /**
     * 分页读取数据
     * @param array $where   条件
     * @param int   $page    第几页
     * @param int   $limit   每页的条数
     **/
    public function GetListByPage($where=array(), $page=1, $limit=10, $order="sort_id desc")
    {   
        return $this->where($where)->page($page,$limit)->order($order)->select();
    }

    //获取数据列表，不分页
    public function GetDataList($where=array(), $order="sort_id desc")
    {
        return $this->where($where)->order($order)->select();
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

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'添加成功','id'=>$id);
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
     * 字段自增
     * @param array  $param   更新条件
     * @param string $field   自增字段
     * @param int    $number  更新数量
     **/
    public function DataSetInc($param,$field,$number=1)
    {
        $res = $this->where($param)->setInc($field,$number);

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'修改成功');
    }

    /**
     * 字段自减
     * @param array  $param   更新条件
     * @param string $field   自减字段
     * @param int    $number  自减数量
     **/
    public function DataSetDec($param,$field,$number=1)
    {
        $res = $this->where($param)->setDec($field,$number);

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
