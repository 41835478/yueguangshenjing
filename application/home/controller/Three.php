<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\Session;
use app\admin\model\User;
use app\admin\model\OrderModel;

class Three extends Controller
{
    /**
     * 三级分佣
     *
     * @return \think\Response
     */
    public function index($orderId)
    {
        $uid = Session::get('uid');
        $user = User::get($uid);
        $userList = User::all();

        $order = OrderModel::get($orderId);
        if ($order->is_shop == 1) { #店面

        } else {#非店面

        }
        $prent = getUpUser($userList, $user->pid);

        if ($prent[0]) {#1级
            #判断上级是不是代理商
        } elseif ($prent[1]) {#2级

        } elseif ($prent[2]) {#3级

        }

        #先判断这个物品是在哪儿买的
    }
}
