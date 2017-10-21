<?php

namespace app\admin\controller;

use app\admin\model\Config;
use app\admin\model\User;
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

        $user = [];
        $where["created_at"] = [">",$startTime];
        $where["created_at"] = ["<",$endTime];

        $user["numAll"] = User::count();#累计会员数量
        $user["todayuser"] = User::where($where)->count();#今日会员数量


        $user["account"] = User::sum("account");#会员账户余额统计
        //dump($user);die;
        return $this->fetch("statistics/index");
    }

}
