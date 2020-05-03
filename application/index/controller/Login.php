<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/1 0001
 * Time: 16:47
 */

namespace app\index\controller;
use think\Controller;
use app\common\lib\Util;
use think\Db;

class Login extends Controller
{
    public function login()
    {
        if(request()->isPost()){
            $data = input('post.');

            if(empty($data['name'])){
               return Util::show(config('code.error'),'账号不能为空');
            }

            if(empty($data['password'])){

                return Util::show(config('code.error'),'密码不能为空');
            }
            $name = $data['name'];
            $info = Db::name('admin')->where('name',$name)->find();

            if(!$info){
                //信息不存在
                return Util::show(config('code.error'),'账号或者密码错误');
            }
            if($info['password'] === md5($data['password'])){
                //验证通过
                session('admin_id',$info['id']);
                return Util::show(config('code.success'),'登陆成功');
            }else{
                //登陆失败
                return Util::show(config('code.error'),'账号或者密码错误');
            }
        }else{

            return $this->fetch();
        }

    }
}