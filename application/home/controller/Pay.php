<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Pay extends Controller
{
    public function pay()//跳转支付页面
    {
        $totalMoney = 0;
        $getId = input('param.order_id', 0, 'intval');
        $order_id = $getId;
        $user='';
        $res=$this->isWeiXin();
        if($res){//说明当前环境是微信端
            $sign=1;
        }else{
            $sign=2;
        }
        if ($order_id) {
            $order = model('admin/OrderModel')->get($order_id);
            $totalMoney = $order->price;
            $user = model('admin/User')->get($order->user_id);
        }
        return view('orders/pay', ['totalMoney' => $totalMoney, 'user' => $user, 'order_id' => $order_id,'sign'=>$sign]);
    }

    public function paySuccess()//支付成功跳转页面
    {
        return view('orders/paySuccess');
    }

    private function isWeiXin()//判断当前环境是否是微信从而决定是否进行网页授权
    {
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')){//说明当前环境是微信
            return true;
        }
        return false;

    }
}
