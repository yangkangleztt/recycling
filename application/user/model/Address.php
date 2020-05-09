<?php
namespace app\user\model;
use think\Db;
use think\Model;

class Address extends Model{
   
    protected $resultSetType = 'collection';
    //自动验证
    protected $_validate = array(
        //验证规则array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('username', 'require', '收货人不能为空！'), //必须填写用户名
        array('phone', 'require', '联系方式不能为空'), //必须填写密码
        array('province','require','所在地区不能为空'),
        array('address_details','require','详细地址不能为空'),
    );
    public function getInfo($where)
    {
        if(empty($where)){
            return false;
        }
        $info = $this->where($where)->find();
        return $info;
    }

    public function getList($where)
    {

        $list = $this->where($where)->order('id desc')->select();
        return $list;
    }

    public function insert($data)
    {
        if(empty($data)){
            return false;
        }
        
        $result = $this->insertGetId($data);
         
     

        return $result;
    }

    public function updates($where,$data)
    {
        if(empty($where) || empty($data)){
            return false;
        }
        $result = $this->where($where)->update($data);
        $info   = $result?1:0;
        return $info;
    }
    
    public function del($where)
    {
        if(empty($where)){
            return false;
        }
        $result = $this->where($where)->delete();
        $info   = $result?1:0;
        return $info;
    }
}
