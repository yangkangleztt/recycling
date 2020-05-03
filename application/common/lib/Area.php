<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/2 0002
 * Time: 09:15
 */

namespace app\common\lib;
use think\Db;
use app\common\lib\Util;
/**
 * Class Area
 * @package app\common\lib
 * 省市区地址库
 */
class Area
{

    //返回省信息
    public static function getProvince()
    {
        $province = Db::name('area')->where('parentId',-1)->select();
        return $province;
    }

    //返回市信息,默认返回山东省信息
    public static function  getCity($provinceId = 1350)
    {
        $city = Db::name('area')->where('parentId',$provinceId)->select();
        //默认查询第一个市区
        $cityFirst = $city['0']['areaId'];
        $areaFirst = Db::name('area')->where('parentId',$cityFirst)->select();
        $info['city'] = $city;
        $info['area'] = $areaFirst;
        return Util::show(config('code.success'),'success',$info);
    }

    //返回区信息,默认返回济南信息
    public static function  getArea($cityId = 1351)
    {
        $area = Db::name('area')->where('parentId',$cityId)->select();
        return Util::show(config('code.success'),'success',$area);
    }

    //查询具体省市区具体信息
    public static function getSimpleInfo($id)
    {

        $info = Db::name('area')->find($id);
        return $info;
    }
}