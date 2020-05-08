<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/7 0007
 * Time: 11:40
 */

namespace app\common\lib;

/*
 * 图片上传接口
 * */
class Upload
{
    public static function goUpload($file)
    {
        $fileInfo = $file;
        //允许上传的图片后缀
        $allowExts = config('upload.allowExts');
        $temp = explode('.',$fileInfo['name']);
        //获取后缀名
        $extension = end($temp);
        //判断是否允许
        if(!in_array($extension,$allowExts)){
            return Util::show(config('code.error'),'图片格式有误');
        }
        //文件上传大小小于200kb
        if($fileInfo['size']>config('upload.maxSize')){
            return Util::show('code.error','图片超出限制');
        }
        if($fileInfo['error']>0){
            return Util::show(config('code.error'),'图片上传失败');
        }
        $name = $fileInfo['name'];
        //保存文件路径
        $res = move_uploaded_file($fileInfo['tmp_name'],__DIR__ . '/../../../public/upload/'.$name);
        if(!$res){
            return Util::show(config('code.error'),'图片上传失败');
        }
        $data['url'] = '/upload/' . $name;

        return Util::show(config('code.success'),'图片上传成功',$data);
    }
}