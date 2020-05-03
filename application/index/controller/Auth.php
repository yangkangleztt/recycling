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

}