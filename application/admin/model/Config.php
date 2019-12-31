<?php
namespace app\admin\model;
use think\Model;
use think\Validate;
/*
 * 网站设置表数据模型
 **/
class Config extends Model
{
    protected $autoWriteTimestamp = true;
    protected $rule = [
    ];

    /**
     * 获取网站配置
     **/
    public function GetConfig()
    {   
        return $this->where('config_id',1)->find();
    }

    /**
     * 修改网站配置
     * @param array $param 需要修改的数组
     **/
    public function UpdateConfig($param)
    {
        $res = $this->allowField(true)->save($param, ['config_id' => 1]);

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'修改成功');
    }
}