<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/1 0001
 * Time: 14:29
 */

namespace app\index\controller;
use app\common\lib\Util;
use think\Controller;
use think\Db;
use app\common\lib\Area;

class Business extends Controller
{
    /**
     * 商家列表
     */
    public function business_list()
    {
        $param = request()->param();

        $start_time = '';
        $end_time = '';
        $agent_level = '';
        $status = 3;
        $merchant_name = '';
        $username = '';
        $phone = '';
        $where['is_del'] = 1;
        //代理开始时间
        if(isset($param['start_time'])) {
            if (!empty($param['start_time'])) {
                $start_time = $param['start_time'];
                $where['start_time'] = array('egt', $start_time);
            }
        }
        //代理截止时间
        if(isset($param['end_time'])) {
            if (!empty($param['end_time'])) {
                $end_time = $param['end_time'];
                $where['end_time'] = array('egt', $end_time);
            }
        }
        //代理级别
        if(isset($param['agent_level'])) {
            if (!empty($param['agent_level'])) {
                $agent_level = $param['agent_level'];
                $where['agent_level'] = $param['agent_level'];
            }
        }
        //商家运营状态
        if(isset($param['status'])){
            if($param['status']!=3){
                $status = $param['status'];
                $where['status'] = $param['status'];
            }
        }
        //商户名称
        if(isset($param['merchant_name'])) {
            if (!empty($param['merchant_name'])) {
                $merchant_name = $param['merchant_name'];

                $where[] = array('merchant_name', 'like', "%$merchant_name%");
            }
        }
        //商户联系人
        if(isset($param['username'])) {
            if (!empty($param['username'])) {
                $username = $param['username'];

                $where[] = array('username', 'like', "%$username%");
            }
        }
        //商户联系人电话
        if(isset($param['phone'])) {
            if (!empty($param['phone'])) {
                $phone = $param['phone'];

                $where[] = array('phone', 'like', "%$phone%");
            }
        }

        $merchant = Db::name('merchant')->order('id desc')->where($where)->paginate(20);

        $page = $merchant->render();
        $this->assign('start_time',$start_time);
        $this->assign('end_time',$end_time);
        $this->assign('agent_level',$agent_level);
        $this->assign('status',$status);
        $this->assign('merchant_name',$merchant_name);
        $this->assign('username',$username);
        $this->assign('phone',$phone);
        $this->assign('page',$page);
        $this->assign('merchant',$merchant);
        return $this->fetch();
    }

    /**
     * 商家入驻
     * @return mixed
     */
    public function business_add()
    {
        if(request()->isPost()){
            $data = input('post.');
            $result = $this->business_handle_data($data);
            $content = json_decode($result->getContent(),true);
            if($content['status'] == 0){
                return $result;
            }
            $data = $content['data'];
            //先判断会员身份
            if(empty($data['uid'])){
                return Util::show(config('code.error'),'请选择会员');
            }
            $userExit = Db::name('merchant')->where('uid',$data['uid'])->find();
            if($userExit){
                //该会员已被占用
                return Util::show(config('code.error'),'该会员已被占用');
            }
            $res = Db::name('merchant')->insert($data);
            if($res){
                //添加成功
                return Util::show(config('code.success'),'添加成功');
            }else{
                return Util::show(config('code.error'),'添加失败');
            }

        }else{
            $province = Area::getProvince();
            $user = Db::name('user')->select();
            $this->assign('user',$user);
            $this->assign('province',$province);
            return $this->fetch();
        }
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

    /*
     * 商家运营状态操作
     * */
    public function business_status()
    {
        $data = input('post.');
        $id = $data['id'];
        $res = Db::name('merchant')->where('id',$id)->setField('status',$data['status']);
        if($res){
            return Util::show(config('code.success'),'操作成功');
        }else{
            return Util::show(config('code.error'),'操作成功');
        }
    }

    /*
     * 商家编辑
     * */
    public function business_edit()
    {
        if(request()->isPost()){
            $data = input('post.');
            $result = $this->business_handle_data($data,1);

            $content = json_decode($result->getContent(),true);
            if($content['status'] == 0){
                return $result;
            }
            if(empty($data['uid'])){
                return Util::show(config('code.error'),'请选择会员');
            }
            //进行修改操作
            $data = $content['data'];
            $uid = $data['uid'];

            $id = $data['merchant_id'];
            unset($data['merchant_id']);
            $merchantInfo = Db::name('merchant')->find($id);
            $oldUid = $merchantInfo['uid'];
            $userExit = Db::name('merchant')->where('uid',$uid)->where('uid','neq',$oldUid)->find();
            if($userExit){
                return Util::show(config('code.error'),'该会员已被占用');
            }
            $res = Db::name('merchant')->where('id',$id)->update($data);
            if($res){
                return Util::show(config('code.success'),'修改成功');
            }else{
                return Util::show(config('code.error'),'修改失败');
            }
        }else{

            $id = request()->route('id');

            $merchantInfo = Db::name('merchant')->find($id);
            $province = Area::getProvince();
            $data = Area::getCity($merchantInfo['province']);
            //json对象转为普通数组
            $info = json_decode($data->getContent(),true);
            $user = Db::name('user')->select();
            $this->assign('user',$user);
            $this->assign('province',$province);
            $this->assign('city',$info['data']['city']);
            $this->assign('area',$info['data']['area']);

            $this->assign('merchantInfo',$merchantInfo);
            return $this->fetch();
        }
    }


    /**
     * @param $data
     * @param int $type
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 商家添加、修改逻辑封装
     */
    public function business_handle_data($data,$type = 0)
    {
        if(empty($data['merchant_name'])){
            return Util::show(config('code.error'),'商户名称不能为空');
        }
        if(empty($data['username'])){
            return Util::show(config('code.error'),'商户联系人不能为空');
        }
        if(empty($data['phone'])){
            return Util::show(config('code.error'),'商户联系电话不能为空');
        }
        if(empty($data['detail_address'])){
            return Util::show(config('code.error'),'商户详细地址不能为空');
        };
        $phoneLength = strlen($data['phone']);
        if($phoneLength!=11){
            return Util::show(config('code.error'),'手机号不合法');
        }
        //手机号判断
        $phoneExit = Db::name('merchant')->where('phone',$data['phone'])->find();
        if($phoneExit){
            if($type == 1){
                //编辑
                $merchantInfo = Db::name('merchant')->field('phone')->find($data['merchant_id']);
                if($merchantInfo['phone'] != $data['phone']){
                    //该手机号已经存在
                    return Util::show(config('code.error'),'该手机号已存在');
                }

            }else{

                //该手机号已经存在
                return Util::show(config('code.error'),'该手机号已存在');
            }
        }

        //添加商家位置名称信息
        $provinceInfo = Area::getSimpleInfo($data['province']);
        $data['province_name'] = $provinceInfo['areaName'];
        //市信息
        $cityInfo = Area::getSimpleInfo($data['city']);
        $data['city_name'] = $cityInfo['areaName'];

        $districtInfo = Area::getSimpleInfo($data['district']);
        $data['district_name'] = $districtInfo['areaName'];

        //代理时间
        $agentTime = explode('~',$data['agent_time']);
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['start_time'] = trim($agentTime['0'],' ');
        $data['end_time'] = trim($agentTime['1'],' ');
        if($data['end_time']<$data['start_time']){
            //提交时间有误
            return Util::show(config('code.error'),'开始时间不能大于结束时间');
        }
        unset($data['agent_time']);
        return Util::show(config('code.success'),'success',$data);
    }


    /*
     * 商家删除
     * */
    public function business_del()
    {
        $data = input('post.');
        $id = $data['id'];
        $res = Db::name('merchant')->where('id',$id)->setField('is_del',0);
        if($res){
            return Util::show(config('code.success'),'删除成功');
        }else{
            return Util::show(config('code.success'),'删除失败');
        }
    }

    /*
     * 商家批量删除
     * */
    public function businss_dellAll()
    {
        $data = input('post.');
        $ids = $data['ids'];
        if(empty($ids)){
            return Util::show(config('code.error'),'参数有误');
        }
        $res = Db::name('merchant')->where("id in ($ids)")->setField('is_del',0);
        if($res){
            return Util::show(config('code.succes'),'删除成功');
        }else{
            return Util::show(config('code.error'),'删除失败');
        }
    }

    //商家服务小哥列表
    public function business_serve($id)
    {
        $full_name = '';
        $nickname = '';
        $where = [];
        $param = request()->param();
        if(isset($param['full_name'])){
            $full_name = $param['full_name'];
            if(!empty($full_name)){
                $where[] = array('a.full_name','like',"$full_name");
            }

        }

        if(isset($param['nickname'])){
            $nickname = $param['nickname'];
            if(!empty($nickname)){
                $where[] = array('c.nickname','like','%'.base64_encode($nickname).'%');
            }

        }

        $serve = Db::name('serve')
            ->alias('a')
            ->join('merchant b','a.merchant_id = b.id')
            ->join('user c','a.user_id = c.id')
            ->where('a.merchant_id',$id)
            ->field('a.*,b.merchant_name,c.nickname')
            ->where($where)
            ->order('a.id desc')
            ->paginate();

        $page = $serve->render();
        $this->assign('full_name',$full_name);
        $this->assign('nickname',$nickname);
        $this->assign('page',$page);
        $this->assign('serve',$serve);
        return $this->fetch();
    }

    //商家服务小哥状态操作
    public function serve_status()
    {
        $data = input('post.');
        $id = $data['id'];
        $status = $data['status'];
        $res = Db::name('serve')->where('id',$id)->setField('status',$status);
        if($res){
            return Util::show(config('code.success'),'操作成功');
        }else{
            return Util::show(config('code.error'),'操作失败');
        }
    }
}