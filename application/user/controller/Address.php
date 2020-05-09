<?php
namespace app\user\controller;
use think\Controller;
use think\Db;

use app\user\model\Address as AddressModel;
use app\common\lib\Area;
class Address extends Base
{
   //地址列表
   public function index()
   {
   	  $model  = new AddressModel();
   	  $userid = session('user_id');
   	  $address_data = $model->getList(['uid'=>$userid])->toArray();

   	  if($address_data){
          $this->assign('address_data',$address_data);
   	  	  return $this->fetch();
   	   }else{
   	  	 return $this->fetch('no_data');
   	  }
   }
   //添加地址
   public function user_address()
   {     
      if(request()->isPost()){
           $address_data = $_POST;
           $data['username'] = $address_data['username'];
           $data['phone'] = $address_data['phone'];
           $data['province'] = $address_data['province'];
           $data['city'] = $address_data['city'];
           $data['district'] = $address_data['district'];
           $data['address_details'] = $address_data['address_details'];
           $data['status'] = 1;
           $data['uid'] = session('user_id');
           $data['time'] = date('Y-m-d h:i',time());
           $address  = new AddressModel();
           $res = $address->insert($data);
           if ($res) {
                return array('state'=>'success');
            }else {
                return array('state'=>'error');
            }
        }else{
           
            return $this->fetch();
        }
   	
   }

   public function ajax_Province()
   {
        $province = Area::getProvince();
            return $province;
   }




       /*
     * ajax获取市
     * */
    public function ajax_city()
    {
        $data = input('post.');
        $provinceId = $data['provinceId'];
        if(empty($provinceId)){
            return Util::show(config('code.error'),'参数有误');
        }
        return Area::getCity($provinceId);
    }

    /*
     * ajax获取区
     * */
    public function ajax_area()
    {
        $data = input('post.');
        $cityId = $data['cityId'];
        if(empty($cityId)){
            return Util::show(config('code.error'),'参数有误');
        }
        return Area::getArea($cityId);
    }   
   //地址空
   public function no_data()
   {
   	  return $this->fetch();
   }

   

}