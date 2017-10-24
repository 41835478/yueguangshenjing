<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\Session;

class Orders extends Base
{
    public function index()
    {
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
        $goods=model('admin/Goods')->get($id);
        $this->assign('address',$address);
        $this->assign('goods',$goods);
        $this->assign('num',$num);
        $this->assign('totalMoney',$goods->goods_price*$num);
        return view('orders/index');
    }
}
