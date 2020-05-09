<?php
namespace app\user\controller;
use think\Request;
use think\Controller;
use think\Db;
class Login extends Controller
{

   public function get_user_info(Request $request)
   { 
   	   $result = $request->param();
   	   $code = $result['code'];
   	   $url = $result['state'];



   	   //获取微信用户信息
   	   $userinfo = getWxUserInfo($code);
   	   //查询是否已经存在
   	   $customer = Db::name('user')->where('openid', $userinfo['openid'])->find();
   	   if($customer){
   	   	  //存在用户则
          //更新头像（用户更新微信头像）
   	   	   $data = array(
   	   	 	  'headimgurl'=> $userinfo['headimgurl'],
   	   	 	  'nickname'=> base64_encode($userinfo['nickname']),
   	   	 	   'unionid'=> $userinfo['unionid']
   	   	   );
   	   	  Db::name('user')->where('id',$customer['id'])->update($data);
    	  session('user_id', $customer['id']);
          session('user_nickname', $userinfo['nickname']);
          session('user_headimgurl', $userinfo['headimgurl']);
 
          $this->redirect('/user/Index/index');
   	    }else{
   	   	  $datas['openid'] = $userinfo['openid'];
          $datas['unionid']    = $userinfo['unionid'];
          $datas['nickname']   = base64_encode($userinfo['nickname']);
          $datas['headimgurl'] = $userinfo['headimgurl'];
          $datas['create_time']   = date('Y-m-d H:i:s');
          $datas['login_method'] = 2;
          $customer_id = Db::name('user')->insertGetId($datas);
           if($customer_id){
          	  session('user_nickname', $userinfo['nickname']);
              session('user_headimgurl', $datas['headimgurl']);
              session('user_id', $customer_id);
           
              $this->redirect($url);
          }else{
          	 echo "<script>alert('获取微信授权错误')</script>";
                die;
          }

   	   }

   }

}