<?php
namespace app\admin\model;
use think\Model;
use think\Validate;
/*
 * 红人表数据模型
 **/
class Order extends Model
{
    //声明主键
    protected $pk = 'order_id';
    //自动写入时间戳
    protected $autoWriteTimestamp = true;
    //声明添加时间字段
    protected $createTime = 'order_time';

    protected $rule = [
        'order_user'       => 'require',
    ];

    /**
     * 分页读取数据
     * @param array $where   条件
     * @param int   $page    第几页
     * @param int   $limit   每页的条数
     **/
    public function GetListByPage($where=array(), $page=1, $limit=10, $order="order_id desc")
    {   
        return $this->where($where)->page($page,$limit)->order($order)->select();
    }

    //获取数据列表，不分页
    public function GetDataList($where=array(), $order="order_id desc", $field = "*")
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
     * 删除数据
     * @param int $id 删除数据的id
     **/
    public function DeleteData($id)
    {
        return $this->where($this->pk,$id)->delete() ? array('code'=>1,'msg'=>'删除成功') : array('code'=>0,'msg'=>'删除失败');
    }

    public function getOrderTypeText($orderType)
    {
        $orderTypeText = "开通特权";
        switch ($orderType) {
            case 1:
                $orderTypeText = '购买积分';
                break;
            case 2:
                $orderTypeText = '购买课程';
                break;
            default:
                $orderTypeText = '开通特权';
                break;
        }
        return $orderTypeText;
    }

    public function getOrderInvoiceText($orderInvoice)
    {
        $orderInvoiceText = "未索取";
        switch ($orderInvoice) {
            case 1:
                $orderInvoiceText = '已索取';
                break;
            default:
                $orderInvoiceText = '未索取';
                break;
        }
        return $orderInvoiceText;
    }

    public function getOrderStatusText($orderStatus)
    {
        $orderStatusText = "已下单";
        switch ($orderStatus) {
            case 10:
                $orderStatusText = '已支付';
                break;
            case -1:
                $orderStatusText = '支付失败';
                break;
            default:
                $orderStatusText = '已下单';
                break;
        }
        return $orderStatusText;
    }
}
