<?php
namespace app\user\controller;
use think\Controller;
use think\Db;
use think\Model;
class Personal extends Base
{

	public function Center()
	{
		$userinfo = Db::name('user')->where('id',session('user_id'))->find();
		$this->assign('nickname',session('user_nickname'));
		$this->assign('userinfo',$userinfo);
		return $this->fetch();
	}


}