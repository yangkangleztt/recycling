<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/7 0007
 * Time: 08:59
 */

namespace app\index\controller;
use think\Db;
use think\Controller;

class Member extends Controller
{
    /*
     * 会员列表
     * */
    public function member_list()
    {
        $login_method = 0;
        $nickname = '';
        $where = null;
        $param = request()->param();
        if(isset($param['nickname'])){
            $nickname = $param['nickname'];
            if(!empty($nickname)){
                $where[] = array('nickname','like','%'.base64_encode($nickname).'%');
            }
        }
        if(isset($param['login_method'])){
            $login_method = $param['login_method'];
            if(!empty($login_method)){
                $where['login_method'] = $login_method;
            }
        }

        $member = Db::name('user')->order('id desc')->where($where)->paginate(config('page.userPage'));
        $page = $member->render();

        $this->assign('login_method',$login_method);
        $this->assign('nickname',$nickname);
        $this->assign('page',$page);
        $this->assign('member',$member);
        return $this->fetch();
    }

    /*
     * 会员反馈
     * */
    public function member_feedback()
    {
        $name = '';
        $phone = '';
        $where = [];
        $param = request()->param();
        if(isset($param['name'])){
            $name = $param['name'];
            if(!empty($name)){
                $where[] = array('name','like',"%$name%");
            }
        }
        if(isset($param['phone'])){
            $phone = $param['phone'];
            if(!empty($phone)){
                $where[] = array('phone','like',"%$phone%");
            }
        }
        $info = Db::name('feedback')->order('id desc')->where($where)->paginate(config('feedbackPage'));
        $page = $info->render();

        $this->assign('name',$name);
        $this->assign('phone',$phone);
        $this->assign('page',$page);
        $this->assign('info',$info);
        return $this->fetch();
    }
}