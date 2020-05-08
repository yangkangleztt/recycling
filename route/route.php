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
    //商家服务小哥列表
    Route::get('business/business_serve/:id','business/business_serve');
    //服务小哥状态操作
    Route::post('business/serve_status','business/serve_status');

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

    /*
     * 权限管理
     * */
    Route::get('auth/auth_list','auth/auth_list');
    //权限添加
    Route::rule('auth/auth_add','auth/auth_add','GET|POST');
    //权限修改
    Route::get('auth/auth_edit/:id','auth/auth_edit');
    Route::post('auth/auth_edit','auth/auth_edit');

    //权限删除
    Route::post('auth/auth_del','auth/auth_del');
    Route::post('auth/auth_delAll','auth/auth_delAll');

    /*
     * 订单管理
     * */
    //订单列表
    Route::get('order/order_list','order/order_list');
    //订单详情
    Route::get('order/order_detail/:id','order/order_detail');

    /*
     * 会员管理
     * */
    //会员列表
    Route::get('member/member_list','member/member_list');
    //会员反馈
    Route::get('member/member_feedback','member/member_feedback');

    /*
     * 轮播图管理
     * */
    //轮播图列表
    Route::get('banner/banner_list','banner/banner_list');
    //轮播图添加
    Route::rule('banner/banner_add','banner/banner_add','GET|POST');
    //轮播图修改
    Route::get('banner/banner_edit/:id','banner/banner_edit');
    Route::post('banner/banner_edit','banner/banner_edit');
    //轮播图上传接口
    Route::post('banner/banner_upload','banner/banner_upload');
    //轮播图删除
    Route::post('banner/banner_del','banner/banner_del');
    //轮播图全选删除
    Route::post('banner/banner_delAll','banner/banner_delAll');

})->prefix('index/')->middleware('Check');
//后台登陆
Route::rule('index/login/login','index/login/login','GET|POST');
Route::get('/','index/index/index')->middleware('Check');



