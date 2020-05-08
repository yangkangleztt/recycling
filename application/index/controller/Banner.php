<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/7 0007
 * Time: 10:17
 */

namespace app\index\controller;
use app\common\lib\Util;
use app\common\lib\Upload;
use think\Db;
use think\Controller;

class Banner extends Controller
{

    /*
     * 轮播图列表
     * */
    public function banner_list()
    {

        $banner = Db::name('banner')->order('id desc')->where('is_del',1)->paginate(config('page.bannerPage'));
        $page = $banner->render();
        $this->assign('banner',$banner);
        $this->assign('page',$page);
        return $this->fetch();
    }

    /*
     * 轮播图添加
     * */
    public function banner_add()
    {
        if(request()->isPost()){
            $data = input('post.');
            unset($data['file']);
            if(empty($data['banner_name'])){
                return Util::show(config('code.error'),'请输入轮播图名称');
            }
            if(empty($data['banner_img'])){
                return Util::show(config('code.error'),'请上传轮播图');
            }
            $res = Db::name('banner')->insert($data);
            if($res){
                return Util::show(config('code.success'),'操作成功');
            }else{
                return Util::show(config('code.success'),'操作失败');
            }
        }else{
            return $this->fetch();
        }
    }


    /*
     * 轮播图编辑
     * */
    public function banner_edit()
    {
        if(request()->isPost()){
            $data = input('post.');
            unset($data['file']);
            if(empty($data['banner_name'])){
                return Util::show(config('code.error'),'请输入轮播图名称');
            }
            if(empty($data['banner_img'])){
                return Util::show(config('code.error'),'请上传轮播图');
            }
            $res = Db::name('banner')->where('id',$data['id'])->update($data);
            if($res){
                return Util::show(config('code.success'),'操作成功');
            }else{
                return Util::show(config('code.success'),'操作失败');
            }
        }else{
            $id = request()->route('id');
            if(!is_numeric($id)){
                return Util::show(config('code.success'),'非法参数');
            }
            $bannerInfo = Db::name('banner')->find($id);
            $this->assign('bannerInfo',$bannerInfo);
            return $this->fetch();
        }
    }

    /*
     * banner图片上传接口
     * */
    public function banner_upload()
    {
        $file = $_FILES['file'];
        $res = Upload::goUpload($file);
        return $res;
    }

    /*
     * 轮播图删除
     * */
    public function banner_del()
    {
        $data = input('post.');
        $id = $data['id'];
        $res = Db::name('banner')->where('id',$data['id'])->setField('is_del',0);
        if($res){
            return Util::show(config('code.success'),'操作成功');
        }else{
            return Util::show(config('code.error'),'操作失败');
        }
    }

    /*
     * 轮播图批量删除
     * */
    public function banner_delAll()
    {
        $data = input('post.');
        $ids = $data['ids'];

        $res = Db::name('banner')->where('id',$ids)->setField('is_del',0);
        if($res){
            return Util::show(config('code.success'),'操作成功');
        }else{
            return Util::show(config('code.error'),'操作失败');
        }
    }
}