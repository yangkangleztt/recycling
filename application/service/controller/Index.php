<?php
namespace app\service\controller;
use think\Controller;
use think\Db;
use app\common\lib\Util;
use app\common\lib\Map;
class Index extends Controller
{
	//订单列表
	public function orderList()
	{
	    $param = input('post.');
        $serve_id = $param['serve_id'];
        if(!isset($param['page'])){
            return Util::show(config('code.error'),'参数有误');
        }
        $page = $param['page'] * 1;
        $pagesize = config('servicePage');
        if($page<=1){
            $leftData = 0;
        }else{
            $leftData = $pagesize * ($page - 1);
        }

		$order = Db::name('order')
            ->alias('a')
            ->join('weight b','a.weight_id = b.id')
            ->join('canme c','a.cname = c.id')
            ->join('address d','a.address_id = d.id')
            ->where('a.serve_id',$serve_id)
            ->order('id desc')
            ->field('a.id,a.order_time,a.address_id,b.depict,c.cname,d.username,d.phone,d.province,d.city,d.district,d.address_details')
            ->limit($leftData,$pagesize)->select();

        //返回用户地址经纬度信息
        return Util::show(config('code.success'),'success',$order);
	}

	//接单成功
    public function getOrder()
    {
        $data = input('post.');

        if(!isset($data['orderId'])){
            return Util::show('code.error','参数有误');
        }
        $orderId = $data['orderId'];
        if(empty($orderId)){
            return Util::show('code.error','参数有误');
        }
        $order = Db::name('order')->find($orderId);
        if(!$order){
            return Util::show('code.error','订单不存在');
        }
        if($order['status']!=0){
            return Util::show('code.error','订单状态不符');
        }
        $res = Db::name('order')->where('id',$orderId)->setField('status',1);
        if($res){
            return Util::show('code.success','接单成功');
        }else{
            return Util::show('code.error','接单失败');
        }
    }

    //商品类别
    public function productsInfo()
    {
        $param = input('post.');
        if(!isset($param['type'])){
            return Util::show('code.error','参数有误');
        }

        $type = $param['type'];

        if($type == 0){

            $type = Db::name('type')->order('id desc')->select();
            return Util::show('code.success','success',$type);

        }elseif($type == 1){

            if(!isset($param['serve_id']) || !isset($param['type_id'])){
                return Util::show('code.error','参数有误');
            }

            $type_id = $param['type_id'];
            $serve_id = $param['serve_id'];
            $serviceInfo = Db::name('serve')->find($serve_id);
            if(!$serviceInfo){
                return Util::show('code.error','信息不存在');
            }
            $products = Db::name('goods')->where('status',1)->where('type_id',$type_id)->where('merchant_id',$serviceInfo['merchant_id'])->order('id desc')->select();

            return Util::show('code.success','success',$products);
        }elseif($type == 2){
            if(!isset($param['productsId'])){
                return Util::show('code.error','参数有误');
            }
            $productsId = $param['productsId'];
            $productsInfo = Db::name('goods')->where('status',1)->where('id',$productsId)->find();
            return Util::show('code.success','success',$productsInfo);
        }

    }

    //获取用户经纬度信息
    public function userPosition()
    {
        $param = input('post.');
        if(!isset($param['address_id'])){
            return Util::show('code.error','参数有误');
        }
        $addressInfo = Db::name('address')->find($param['address_id']);
        if(!$addressInfo){
            return Util::show('code.error','信息不存在');
        }
        $address = $addressInfo['province'] . $addressInfo['city'] . $addressInfo['district'] .  $addressInfo['address_details'];
        $info = Map::addressToGeocoding($address);
        return Util::show('code.success','success',$info);
    }

    //下单
    public function  goOrder()
    {
        $data = input('post.');
        if(!isset($data['order_number'])){
            return Util::show('code.error','参数有误');
        }
        $order_number = $data['order_number'];
        $order = Db::name('order')->where('order_number',$order_number)->find();
        if(!$order){
            return Util::show('code.error','订单不存在');
        }
        if($order['pay_status']!=0){
            return Util::show('code.error','订单状态不符');
        }
        $tree = [];
        $totalPrice = 0;
        $order = $data['order'];
        foreach($order as $k=>$v){
            $goods_id = $v['goods_id'];
            $weight = is_numeric($v['weight']);
            if(empty($weight)){
                return Util::show('code.error','订单信息有误');

            }
            $goodsInfo = Db::name('goods')->find($goods_id);
            if(!$goodsInfo){
                return Util::show('code.error','商品信息不存在');
            }
            $prices = $goodsInfo['prices'];
            $totalPrice  = $totalPrice + $prices * $weight;
            $tree[] = [
                'order_number'      =>$order_number,
                'goods_id'          =>$goods_id,
                'prices'            =>$prices,
                'weight'            =>$weight,
                'total'             =>$prices * $weight,
                'add_time'          =>date('Y-m-d H:i:s')
            ];
        }
        $res = Db::name('order_detail')->insertAll($tree);
        if($res){
            //更新order信息
            Db::name('order')->where('order_number',$order_number)->setField('total_price',$totalPrice);
            $arr = array('total'=>$totalPrice,'pay_status'=>0,'order_number'=>$order_number);
            return Util::show('code.success','下单成功',$arr);
        }else{
            return Util::show('code.error','下单失败');
        }

    }

}