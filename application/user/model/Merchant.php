<?php
namespace app\user\model;
use think\Db;
use think\Model;

class Merchant extends Model{
   
    protected $resultSetType = 'collection';

    public function is_merchant($user)
    {
    	if(!empty($data)){
    		return false;
    	}
      

        
        $data['province_name'] = $user['province'];
        $data['city_name'] = $user['city'];
        $data['district_name'] = $user['district'];  
    	$exist = $this->where($data)->find();
    	$info  = $exist?true:false;
    	return $info;
    }


}