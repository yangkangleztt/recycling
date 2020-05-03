<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/1 0001
 * Time: 17:30
 */

namespace app\common\lib;


class Util
{
    public static function show($status,$message,$data = [])
    {
        $arr =  [
            'status'      =>$status,
            'message'   =>$message,
            'data'      =>$data
        ];

        return json($arr);
    }
}