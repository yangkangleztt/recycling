<?php
namespace app\user\controller;
use think\Controller;
use think\Db;
use think\Model;
class Index extends Base
{

   public function index()
   {

   // $address = http_curl('http://api.map.baidu.com/geocoder/v2/?output=json&ak=2TGbi6zzFm5rjYKqPPomh9GBwcgLW5sS&location=36.68013,117.06533');
   // var_dump($address);
   // die;
   	  //获取JsapiTicket
	  $signPackage = getJsapiTicket();
	  $this->assign('signPackage',$signPackage);
      return $this->fetch();
   }
   public function get_position()
   {
   	   $data = $_POST;
   	   $userid = session('user_id');
   	   $result['latitude'] = $data['latitude']; //纬度
   	   $result['longitude'] = $data['longitude'];//经度
   	   $ak = config('ak');
   	   $address = http_curl("http://api.map.baidu.com/geocoder/v2/?output=json&ak={$ak}&location={$data['latitude']},{$data['longitude']}");

   	   if($address['status'] == 0){
   	   	 $address_details = $address['result']['addressComponent'];
   	   	 $result['province'] = $address_details['province'];
   	       $result['city'] = $address_details['city'];
   	       $result['district'] = $address_details['district'];
   	   	 $info = Db::name('user')->where('id',$userid)->update($result);
   	   	 return $info;
   	   }
   }

}