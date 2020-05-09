<?php
namespace app\user\controller;
use think\Controller;
class Public extends Controller
{

   public function bottom()
   {

   	  return $this->fetch();
   }

}