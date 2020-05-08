<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//无限极查询
function getTree($data,$pid = 0,$level =1){
    static $tree = [];
    foreach($data as $key=>&$value){
        if($value['pid'] == $pid){
            $value['level'] = $level;
            $tree[] = $value;

            getTree($data,$value['id'],$level+1);
        }
    }
    return $tree;
}

//无限极查询 分类处理
function getAuthSort(){
    $auth = Db::name('auth_rule')->where('pid',0)->where('status',1)->select();

    $auth2 = Db::name('auth_rule')->where('pid','neq',0)->where('status',1)->select();
    foreach ($auth as $key => &$value) {


        $value['child'] = getAuth2($auth2,$value['id'],2,$status =2);



    }
    return $auth;
}

function getAuth2($auth,$pid = 0,$level =1,$status = 1){
    static $arr = array();

    if($status == 2){
        $arr = array();

    }
    foreach ($auth as $key => $value) {
        $vpid = $value['pid'];
        $value['level'] = $level;
        if($vpid == $pid){
            //顶级分类
            $arr[] = $value;
            getAuth2($auth,$value['id'],$level+1,1);
        }

    }
    return $arr;
}