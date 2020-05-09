<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/9 0009
 * Time: 09:08
 */

namespace app\common\lib;
use think\Controller;

class Map extends Controller
{
    public static function addressToGeocoding($address)
    {
        $ak = config('map.ak');
        $url = 'http://api.map.baidu.com/geocoding/v3/?address='.$address.'&output=json&ak='.$ak.'&callback=showLocation';

        $info = self::curl_get($url);
        $arr = explode('(',$info);
        $result = explode(')',$arr[1]);
        $content = json_decode($result['0'],true);

        return $content;


    }
    //发送请求
    public static function curl_get($url)
    {
        //用curl传参

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //关闭ssl验证

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }
}