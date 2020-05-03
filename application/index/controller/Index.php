<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller
{
    /**
     * 后台首页
     * @return mixed
     */
    public function index()
    {

        return $this->fetch();
    }

    public function welcome()
    {

        return $this->fetch();
    }
    public function read($id)
    {
        echo $id;die;
    }
}
