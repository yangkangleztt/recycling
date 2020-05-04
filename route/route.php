<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use app\http\middleware;
/**
 * 后台路由分组
 */
Route::group('index',function (){
    Route::get('index/index','index/index');
    Route::get('index/welcome','index/welcome');
    //商家列表
    Route::get('business/business_list','business/business_list');
    //商家入驻
    Route::rule('business/business_add','business/business_add','GET|POST');
    //商家编辑
    Route::get('business/business_edit/:id','business/business_edit');
    Route::post('business/business_edit','business/business_edit');

    //省市区三级联动
    Route::post('business/ajax_city','business/ajax_city');
    Route::post('business/ajax_area','business/ajax_area');
    //商家运营状态操作
    Route::post('business/business_status','business/business_status');

    //商家删除
    Route::post('business/business_del','business/business_del');
    //商品批量删除
    Route::post('business/businss_dellAll','business/businss_dellAll');

    /*
     * 权限管理
     * */
    Route::get('auth/role_list','auth/role_list');
    //角色添加
    Route::rule('auth/role_add','auth/role_add','GET|POST');
    //角色修改
    Route::get('auth/role_edit/:id','auth/role_edit');
    Route::post('auth/role_edit','auth/role_edit');
    //角色删除
    Route::post('auth/role_del','auth/role_del');
    //角色批量删除
    Route::post('auth/role_delAll','auth/role_delAll');
    //角色状态操作
    Route::post('auth/role_status','auth/role_status');

    /*
     * 管理员列表
     * */
    Route::get('auth/admin_list','auth/admin_list');
    //管理员添加
    Route::rule('auth/admin_add','auth/admin_add','GET|POST');
    //管理员修改
    Route::get('auth/admin_edit/:id','auth/admin_edit');
    Route::post('auth/admin_edit','auth/admin_edit');
    //管理员删除
    Route::post('auth/admin_del','auth/admin_del');
    //管理员批量删除
    Route::post('auth/admin_delAll','auth/admin_delAll');
    //管理员状态操作
    Route::post('auth/admin_status','auth/admin_status');



})->prefix('index/')->middleware('Check');
//后台登陆
Route::rule('index/login/login','index/login/login','GET|POST');
Route::get('/','index/index/index');


