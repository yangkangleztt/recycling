<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/1 0001
 * Time: 14:29
 */

namespace app\index\controller;
use think\Controller;

class Business extends Controller
{
    /**
     * 商家列表
     */
    public function business_list()
    {
        return $this->fetch();
    }

    /**
     * 商家入驻
     * @return mixed
     */
    public function business_add()
    {
        if(request()->isPost()){
            $data = input('post.');
            dump($data);die;
        }else{
            return $this->fetch();
        }
    }
}