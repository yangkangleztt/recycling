<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/3 0003
 * Time: 16:06
 */

namespace app\index\controller;
use app\common\lib\Util;
use think\Controller;
use think\Db;

class Auth extends  Controller
{
    /*
     * 角色列表
     * */
    public function role_list()
    {
        $status = 3;
        $title = '';
        $param = request()->param();
        if(isset($param['title'])){
            $title = $param['title'];
            if(!empty($title)){
                $where['title'] = $title;
            }

        }

        if(isset($param['status'])){
            $status = $param['status'];
            if($status!=3){

                if(!empty($status)){
                    $where['status'] = $status;
                }
            }

        }
        $where['is_del'] = 1;

        $role = Db::name('auth_group')->where($where)->paginate(config('page.page_role'));
        $page = $role->render();

        $this->assign('status',$status);
        $this->assign('title',$title);
        $this->assign('role',$role);
        $this->assign('page',$page);
        return $this->fetch();
    }

    /*
     * 角色 添加
     * */
    public function role_add()
    {
        if(request()->isPost()){
            $data = input('post.');
            if(empty($data['title'])){
                return Util::show(config('code.error'),'角色名称不能为空');
            }
            $is_exit = Db::name('auth_group')->where('title',$data['title'])->find();
            if($is_exit){
                return Util::show(config('code.error'),'该角色名称已经存在');
            }
            $res = Db::name('auth_group')->insert($data);
            if($res){
                return Util::show(config('code.success'),'添加成功');
            }else{
                return Util::show(config('code.error'),'添加失败');
            }
        }else{
            return $this->fetch();
        }

    }

    /*
     * 角色编辑
     * */
    public function role_edit()
    {
        if(request()->isPost()){
            $data = input('post.');
            $title_exit = Db::name('auth_group')->where('title',$data['title'])->find();
            if($title_exit){
                //判断提交名称是否重复
                $roleInfo = Db::name('auth_group')->find($data['id']);
                if($roleInfo['title']!=$data['title']){
                    return Util::show(config('code.error'),'角色名称不能重复');
                }
            }
            $res = Db::name('auth_group')->where('id',$data['id'])->update($data);
            if($res){
                return Util::show(config('code.success'),'修改成功');
            }else{
                return Util::show(config('code.error'),'修改失败');
            }
        }else{
            $id = request()->route('id');
            $roleInfo = Db::name('auth_group')->find($id);
            $this->assign('roleInfo',$roleInfo);
            return $this->fetch();
        }

    }

    /*
     * 角色状态操作
     * */
    public function role_status()
    {
        $data = input('post.');
        $res = Db::name('auth_group')->where('id',$data['id'])->setField('status',$data['status']);
        if($res){
            return Util::show(config('code.success'),'操作成功');
        }else{
            return Util::show(config('code.error'),'操作失败');
        }
    }

    /*
     * 角色删除
     * */
    public function role_del()
    {
        $data = input('post.');

        $res = Db::name('auth_group')->where('id',$data['id'])->setField('is_del',0);
        if($res){
            return Util::show(config('code.success'),'操作成功');
        }else{
            return Util::show(config('code.error'),'操作失败');
        }
    }

    /*
 * 角色批量删除
 * */
    public function role_delAll()
    {
        $data = input('post.');
        $ids = $data['ids'];
        $res = Db::name('auth_group')->where("id in ($ids)")->setField('is_del',0);
        if($res){
            return Util::show(config('code.success'),'操作成功');
        }else{
            return Util::show(config('code.error'),'操作失败');
        }
    }


    /*
     * 管理员列表
     * */
    public function admin_list()
    {
        $type = 3;
        $name = '';
        $param = request()->param();
        $where = null;
        if(isset($param['type'])){

            if($param['type']!=3){
                $type = $param['type'];
                $where[] = array('type','eq',$type);
            }
        }
        if(isset($param['name'])){
            $name = $param['name'];
            if(!empty($name)){
                $where[] = array('name','like',"%$name%");
            }
        }

        $admin = Db::name('admin')->where('is_del',1)->where($where)->paginate(config('page.adminPage'))->each(function ($item){
            $role_id = $item['role_id'];
            $roleInfo = Db::name('auth_group')->where("id in ($role_id)")->select();
            $role_name = '';
            foreach($roleInfo as $k=>$v){
                $role_name .=$v['title'] . ',';
            }
            $role_name = trim($role_name,',');
            $item['role_name'] = $role_name;
            return $item;
        });

        $page = $admin->render();
        $this->assign('name',$name);
        $this->assign('type',$type);
        $this->assign('page',$page);
        $this->assign('admin',$admin);
        return $this->fetch();
    }

    /*
     * 管理员用户添加
     * */
    public function admin_add()
    {
        if(request()->isPost()){
            $data = input('post.');
            if($data['type'] == 2){
                //判断商家id
                if(empty($data['merchant_id'])){
                    return Util::show(config('error'),'请选择商家');
                }
                //判断商家id是否已经被占用
                $is_merchant = Db::name('admin')->where('merchant_id',$data['merchant_id'])->find();
                if($is_merchant){
                    return Util::show(config('error'),'该商家已经被占用');
                }
                $data['role_id'] = 2;
            }
            //检测密码
            if(empty($data['pass'])){
                return Util::show(config('error'),'请输入密码');
            }
            if($data['pass']!==$data['repass']){
                return Util::show(config('error'),'两次密码输入不一致');
            }
            $is_exit = Db::name('admin')->where('name',$data['name'])->find();
            if($is_exit){
                return Util::show(config('error'),'该用户名已经存在');
            }
            $data['password'] = md5($data['pass']);
            unset($data['pass']);
            unset($data['repass']);
            $data['add_time'] = date('Y-m-d H:i:s');
            $res = Db::name('admin')->insert($data);
            if($res){
                return Util::show(config('success'),'添加成功');
            }else{
                return Util::show(config('error'),'添加失败');
            }
        }else{
            //查询角色
            $role = Db::name('auth_group')->where('is_del',1)->select();
            $merchant = Db::name('merchant')->where('is_del',1)->select();

            $this->assign('merchant',$merchant);
            $this->assign('role',$role);
            return $this->fetch();
        }
    }


    /*
     *管理员编辑
     * */
    public function admin_edit()
    {
        if(request()->isPost()){
            $data = input('post.');
            $role_id = $data['role_id'];
            if($data['type'] == 2){
                //代理

                unset($data['role_id']);
                //判断商家id
                if(empty($data['merchant_id'])){
                    return Util::show(config('error'),'请选择商家');
                }
            }

            //检测密码
            if(!empty($data['pass'])){
                if($data['pass']!==$data['repass']){
                    return Util::show(config('error'),'两次密码输入不一致');
                }
                $data['password'] = md5($data['pass']);

            }
            unset($data['pass']);
            unset($data['repass']);
            $data['add_time'] = date('Y-m-d H:i:s');
            $res = Db::name('admin')->where('id',$data['id'])->update($data);
            if($res){
                return Util::show(config('success'),'修改成功');
            }else{
                return Util::show(config('error'),'修改失败');
            }

        }else{
            $id = request()->route('id');
            $role = Db::name('auth_group')->select();
            $merchant = Db::name('merchant')->where('is_del',1)->select();
            $adminInfo = Db::name('admin')->find($id);

            $this->assign('adminInfo',$adminInfo);
            $this->assign('merchant',$merchant);
            $this->assign('role',$role);
            return $this->fetch();
        }
    }

    /*
    * 角色状态操作
    * */
    public function admin_status()
    {
        $data = input('post.');
        $res = Db::name('admin')->where('id',$data['id'])->setField('status',$data['status']);
        if($res){
            return Util::show(config('code.success'),'操作成功');
        }else{
            return Util::show(config('code.error'),'操作失败');
        }
    }

    /*
    * 管理员删除
    * */
    public function admin_del()
    {
        $data = input('post.');
        //判断是否是管理员
        $is_admin = Db::name('admin')->where('id',$data['id'])->find();
        if($is_admin['name'] == 'admin'){
            return Util::show(config('code.error'),'admin用户禁止删除');
        }
        $res = Db::name('admin')->where('id',$data['id'])->setField('is_del',0);
        if($res){
            return Util::show(config('code.success'),'操作成功');
        }else{
            return Util::show(config('code.error'),'操作失败');
        }
    }

    /*
    * 管理员批量删除
    * */
    public function admin_delAll()
    {
        $data = input('post.');

        $ids = trim($data['ids'],',');
        $res = Db::name('admin')->where("id in ($ids)")->where('id','neq',1)->setField('is_del',0);
        if($res){
            return Util::show(config('code.success'),'操作成功');
        }else{
            return Util::show(config('code.error'),'操作失败');
        }
    }


    /*
     * 权限列表
     * */
    public function auth_list()
    {
        $auth = Db::name('auth_rule')->where('status',1)->paginate(config('page.authPage'));

        $page = $auth->render();

        $this->assign('auth',$auth);
        $this->assign('page',$page);
        return $this->fetch();
    }
    /*
     * 权限添加
     * */
    public function auth_add()
    {
        if(request()->isPost()){

        }else{
            return $this->fetch();
        }
    }



}