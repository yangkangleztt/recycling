<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/4 0004
 * Time: 17:46
 */

namespace app\common\lib;


use think\Db;

class Auth
{
    public function authList()
    {
        $authParent = Db::name('auth_rule')->where('status',1)->where('pid',0)->paginate(config('page.authPage'));
        return $authParent;
    }
}