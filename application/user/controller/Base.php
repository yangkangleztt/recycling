<?php
namespace app\user\controller;
use think\Controller;
use think\Db;
class Base extends Controller
{

    public function initialize()
    {
        parent::initialize();
        $share_url = $_SERVER['REQUEST_URI'];
        $url =  request()->module() . '/' . request()->controller() . '/' . request()->action();
        $this->assign('url',$url);
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        $user = Db::name('user')->where('id',session('user_id'))->find();

        if (!session('user_id') || empty($user)) {
           
            $appid       = config('appid');
            $callbackurl = $http_type . $_SERVER['HTTP_HOST'] . "/user/Login/get_user_info";
            $url         = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . urlEncode($callbackurl) . "&response_type=code&scope=snsapi_userinfo&state=" . urlEncode($share_url) . "#wechat_redirect";
            $this->redirect($url);
        } else {
            return;
        }
    }

}