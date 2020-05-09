<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/8 0008
 * Time: 13:36
 */

namespace app\service\controller;
use think\Controller;
use think\Db;
use app\common\lib\Util;
class Login extends Controller
{
    public function login()
    {
        header("Content-Type: text/html;charset=utf-8");
        $data = input('post.');

        if(empty($data['name'])){
            return Util::show(config('code.error'),'用户名不能为空');
        }

        if(empty($data['password'])){
            return Util::show(config('code.error'),'密码不能为空');
        }

        $info = Db::name('serve')->where('mobile',$data['name'])->where('status',1)->find();
        if(!$info){
            return Util::show(config('code.error'),'账号不存在或者密码错误');
        }

        if($info['password']!=md5($data['password'])){
            return Util::show(config('code.error'),'账号不存在或者密码错误');
        }

        $arr = array('uid'=>$info['id'],'money'=>$info['money'],'province'=>$info['province'],'city'=>$info['city'],'district'=>$info['district'],'mobile'=>$info['mobile']);
        //登陆成功
        return Util::show(config('code.success'),'success',$arr);
    }
}