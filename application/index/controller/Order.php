<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/6 0006
 * Time: 11:39
 */

namespace app\index\controller;
use think\Controller;
use think\Db;

class Order extends Controller
{
    /*
     * 订单列表
     * */
    public function order_list()
    {
        $param = request()->param();
        $pay_status = 2;
        $status = 4;
        $start = '';
        $end = '';
        $order_number = '';
        $where = null;
        $where2 = null;
        if(isset($param['start']) && isset($param['end'])){
            $start = $param['start'];
            $end = $param['end'];
            if(!empty($start) && !empty($end)){

                $where[] = array('a.place_time','between',[$start,$end]);
            }


        }


        if(isset($param['pay_status'])){
            $pay_status = $param['pay_status'];
            if($pay_status != 2){

                $where['a.pay_status'] = $pay_status;
            }

        }

        if(isset($param['status'])){
            $status = $param['status'];

            if($status!=4){

                $where['a.status'] = $status;
            }

        }

        if(isset($param['order_number'])){
            $order_number = $param['order_number'];

            $where2[] = array('a.order_number', 'like',  "%$order_number%");

        }
//        dump($where);die;
        $order = Db::name('order')
            ->alias('a')
            ->join('user b','a.user_id = b.id')
            ->order('a.id desc')
            ->field('a.*,b.nickname,b.moblie')
            ->where($where)
            ->where($where2)
            ->paginate(config('page.orderPage'));

        $page = $order->render();
        $this->assign('order_number',$order_number);
        $this->assign('status',$status);
        $this->assign('pay_status',$pay_status);
        $this->assign('start',$start);
        $this->assign('end',$end);
        $this->assign('page',$page);
        $this->assign('order',$order);
        return $this->fetch();
    }
    /*
     * 订单详情
     * */
    public function order_detail()
    {
        $id = request()->route('id');
        $orderInfo = Db::name('order')
            ->alias('a')
            ->join('serve b','a.serve_id = b.id')

            ->join('weight d','a.weight_id = d.id')
            ->field('a.*,b.full_name,b.mobile,d.depict')
            ->where('a.id',$id)->find();
        $addressInfo = Db::name('address')->where('id',$orderInfo['address_id'])->find();
        $orderInfo['username'] = $addressInfo['username'];
        $orderInfo['phone'] = $addressInfo['phone'];
        $orderInfo['province'] = $addressInfo['province'];
        $orderInfo['city'] = $addressInfo['city'];
        $orderInfo['district'] = $addressInfo['district'];
        $orderInfo['address_details'] = $addressInfo['address_details'];
        $orderDetail = Db::name('order_detail')
            ->alias('a')
            ->join('goods b','a.goods_id = b.id')
            ->join('canme c','b.type_id = c.id')
            ->join('merchant d','b.merchant_id = d.id')
            ->where('order_number',$orderInfo['order_number'])
            ->field('a.*,b.unit,c.cname,d.merchant_name')
            ->select();

        $this->assign('orderDetail',$orderDetail);
        $this->assign('orderInfo',$orderInfo);
        return $this->fetch();
    }
}