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



/**

 * 获得基础access_token

 * @return [type] [description]

 */

function getAccessToken()

{

    $appid     = config('appid');

    $appsecret = config('appsecret');

    if (cache('access_token')) {

        $access_token = cache('access_token');

    } else {

        $url          = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $appsecret;

        $data         = http_curl($url);

        $access_token = $data['access_token'];

        cache('access_token', $access_token, 3600);

    }

    return $access_token;



}

/**

 * 获取用户用户信息并保存

 * @return [type] [description]

 */

function getWxUserInfo($code)

{

    //获取access_token和openid

    $appid     = config('appid');

    $appsecret = config('appsecret');

    $codeurl   = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $appsecret . "&code=" . $code . "&grant_type=authorization_code";

    $data      = http_curl($codeurl);

    if (!isset($data['access_token'])) {

        echo "授权失败";die;

    }

    $access_token = $data['access_token'];

    $openid       = $data['openid'];



    //构造url获取微信用户数据

    $userurl  = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";

    $userinfo = http_curl($userurl);

    //返回用户信息

    return $userinfo;

}

/**

 * 获取用户用户信息并保存

 * @return [type] [description]

 */

function getWxUserInfo2($code)

{

    //获取access_token和openid

    $appid        = config('appid');

    $appsecret    = config('appsecret');

    $codeurl      = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $appsecret . "&code=" . $code . "&grant_type=authorization_code";

    $data         = http_curl($codeurl);

    $access_token = $data['access_token'];

    $openid       = $data['openid'];



    //构造url获取微信用户数据

    $userurl  = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";

    $userinfo = http_curl($userurl);

    //返回用户信息

    return $userinfo;

}

/**

 * 获取JsapiTicket

 * @return [type] [description]

 */

function getJsapiTicket()

{

    $appid        = config('appid');

    $access_token = getAccessToken();

    $url          = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=" . $access_token . "&type=jsapi";

    if (cache('ticket_info')) {

        $ticket_info = cache('ticket_info');

    } else {

        $ticket_info = http_curl($url);

        cache('ticket_info', $ticket_info, 3600);

    }

    $ticket   = $ticket_info['ticket'];

    $chars    = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    $nonceStr = "";

    for ($i = 0; $i < 16; $i++) {

        $nonceStr .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);

    }



    $protocol    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    $url         = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $timestamp   = time();

    $string      = "jsapi_ticket=" . $ticket . "&noncestr=" . $nonceStr . "&timestamp=" . $timestamp . "&url=" . $url;

    $signature   = sha1($string);

    $signPackage = array(

        "appId"     => $appid,

        "nonceStr"  => $nonceStr,

        "timestamp" => $timestamp,

        "url"       => $url,

        "signature" => $signature,

        "rawString" => $string,

    );

    //var_dump($signPackage);

    return $signPackage;

}

/**

 * 发起http请求

 * @param  [type] $url [description]

 * @return [type]      [description]

 */

function http_curl($url)

{

    //用curl传参

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);



    //关闭ssl验证

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);


    curl_setopt($ch, CURLOPT_HEADER, 0);

    $output = curl_exec($ch);

    curl_close($ch);
   
    return json_decode($output, true);

}



function http_post($url, $data)

{

    $ch = curl_init();



    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $output = curl_exec($ch);

    if (curl_errno($ch)) {

        echo 'Errno' . curl_error($ch);

    }

    curl_close($ch);
    
    return json_decode($output, true);

}

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