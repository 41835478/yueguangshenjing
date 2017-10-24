<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\Session;

class Orders extends Base
{
    protected $user_id;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->user_id=session('uid');
    }

    public function index()
    {
        $num=$totalMoney=0;
        $id=Session::get('goods_id','home');
        $num=Session::get('num','home');
        $address='';
        $find=model('admin/Address')->where(['user_id'=>session('uid'),'default'=>1])->find();
        if(!$find){
            $mod=model('admin/Address')->where(['user_id'=>session('uid')])->order('id desc')->find();
            if($mod){
                $address=$mod;
            }
        }else{
            $address=$find;
        }
        if($id){
            $goods=model('admin/Goods')->get($id);
            $totalMoney=$goods->goods_price*$num;
        }else{
            $goods='';
        }
        $this->assign('address',$address);
        $this->assign('goods',$goods);
        $this->assign('num',$num);
        $this->assign('totalMoney',$totalMoney);
        return view('orders/index');
    }

    public function create()//立即购买
    {
        $date=input('post.');
        $num=0;
        $id=Session::get('goods_id','home');
        $num=Session::get('num','home');
        if($id&&$num){
            $this->createOrder($id,$num,$date);
        }else{
            return json(['status'=>false,'message'=>'请先购买产品']);
        }
    }

    public function createOrder($id,$num,$data)//生成订单
    {
        $date['user_id']=$this->user_id;
        $date['order_code']=$this->order_num();
        $date['is_shop']=$data['is_shop'];
        $date['shop_name']=$data['shop_name'];
        $date['status']=1;
        $date['address_id']=$data['address_id'];
    }

    public function getAddress($id)//获取地址id
    {

    }

    public function order_num()//生成订单号
    {
        $code = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderCode = $code[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderCode;
    }
}
