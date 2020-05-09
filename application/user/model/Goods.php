<?php
namespace app\user\model;
use think\Db;
use think\Model;

class Goods extends Model{
   
    protected $resultSetType = 'collection';

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
        $data['create_time'] = time();
        $data['user_id']     = session('UserId');
        $result = $this->insert($data);
        return $this->getLastSql();
        $info   = $result?$result:0;
        return $info;
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
