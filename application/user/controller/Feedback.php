<?php
namespace app\user\controller;
use think\Controller;
use think\Db;
use think\Model;
use think\Validate;
class Feedback extends Base
{

	public function index()
	{
		return $this->fetch();
	}
	public function feebback_add()
	{

		 $data = $_POST;
         $data['time'] = date('Y-m-d h:i:s',time());
         $data['uid'] = session('user_id');
         $info = Db::name('feedback')->insert($data);
         if($info){
             return array('status'=>'success','msg'=>'提交成功'); 
         }else{
             return array('status'=>'error','msg'=>'提交失败'); 
         }
        
	}
    public function about()
    {
        return $this->fetch();
    }


}