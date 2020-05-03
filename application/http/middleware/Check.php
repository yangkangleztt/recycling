<?php

namespace app\http\middleware;

class Check
{
    public function handle($request, \Closure $next)
    {
        //检测用户等
        if(!session('admin_id')){
            //用户未登录，调整到登陆
            return redirect('index/login/login');
        }
        return $next($request);
    }
}
