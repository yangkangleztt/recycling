<?php
namespace app\user\controller;
use think\Controller;
use think\Db;
use think\Request;
use app\user\model\Goods as GoodsModel;
use app\user\model\Address as AddressModel;
use app\user\model\Merchant as MerchantModel;

class Goods extends Base
{
	public function index(Request $request)
	{

		 $model  = new GoodsModel();
		 $address = new AddressModel();
		 $uid = session('user_id');
		 $aid =  input('param.aid');
		 if(!empty($aid)){
		 	 $address_info = $address->getInfo(['id'=>$aid,'uid'=>$uid])->toarray();
		 }else{
		 	 $address_info  = '';
		 }
		 $goods_weight = Db::name('weight')->order('id desc')->select();;
		 $this->assign('goods_weight',$goods_weight);
		 $this->assign('address_info',$address_info);
		 return $this->fetch();
	}
	public function order()
	{

	}
	public function is_being()
	{

		$Merchant = new MerchantModel();
		$uid = session('user_id');
		$user = Db::name('user')->where('id',$uid)->field('province,city,district')->find();

		$info = $Merchant->is_merchant($user);
		if($info)
		{
		   	return 1;
		   }else{
		    return 2;
		}
	}
}