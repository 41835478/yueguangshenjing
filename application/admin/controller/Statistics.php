<?php

namespace app\admin\controller;

use app\admin\model\Config;
use app\admin\model\OrderModel;
use app\admin\model\User;
use app\admin\model\WithdrawalsModel;
use think\Request;

class Statistics extends Base
{
    /**
     * 显示资源列表
     */
    public function index()
    {
        //
        $startTime = date("Y-m-d",strtotime("-1 day"));
        $endTime = date("Y-m-d",strtotime("+1 day"));
        $time = date("Y-m-d H:i:s",time());

        $user = [];
        $where["created_at"] = [">",$startTime];
        $where["created_at"] = ["<",$endTime];

        $user["numAll"] = User::count();#累计会员数量
        $user["todayuser"] = User::where($where)->count();#今日会员数量
        $user["account"] = User::sum("account");#会员账户余额统计
        $user["account_xian"] = User::where(["level"=>User::LEVEL_THREE])->count();#县级市代理数量
        $user["account_di"] = User::where(["level"=>User::LEVEL_FOUR])->count();#地级市代理数量
        $user["account_sheng"] = User::where(["level"=>User::LEVEL_FIVE])->count();#省会城市代理数量
        $user["account_one"] = User::where(["level"=>User::LEVEL_SIX])->count();#一线城市理数量

        $order = [];
        #历史收入总额
        $order["account_price"] = OrderModel::where("status",">",OrderModel::STATUS_ONE)->sum("price");
        #历史累计发货数量
        $order["account_num"] = OrderModel::where("status",">",OrderModel::STATUS_TWO)->count();
        #历史累计订单总量
        $order["account_all"] = OrderModel::count();
        #今日订单数量
        $order["todayuser"] = OrderModel::where($where)->count();

        $withdraw = [];
        #已发放提现总额
        $withdraw['account_price'] = WithdrawalsModel::where(["status"=>WithdrawalsModel::STATUS_TWO])->sum("money");
        #提现申请总额
        $withdraw['account_apply'] = WithdrawalsModel::where(["status"=>WithdrawalsModel::STATUS_ONE])->sum("money");

        return $this->fetch("statistics/index",["user"=>$user,"order"=>$order,"withdraw"=>$withdraw,"time"=>$time]);
    }

}
