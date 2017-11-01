<?php

namespace Service;

use app\admin\model\Config;
use app\admin\model\OrderModel;
use app\admin\model\User;
use think\Log;
use think\Db;

/**
 * 补助
 */
class SubsidyService
{
    public $order;

    public function __construct()
    {
        $this->order = new OrderModel();
    }

    public function subsidy($orderid)
    {
        $order = OrderModel::get($orderid);

        $account = new AccountRecord();

        if ($order['sign'] == 1 && $order['send_id'] != "") {#代理发货奖 奖励50
            $user_agent = User::get($order['send_id']);
            $user_agent->setInc("account", (Config::get(10)->value * $order['num']));
            $account->setAccountRecord($order['send_id'], "代理商发货奖",
                12, 2, (Config::get(12)->value * $order['num']),$order['user_id']);
            Log::record("代理商发货补助完成");
            file_put_contents("./log.txt",date("Y-m-d H:i:s",time())."代理商发货补助完成\n",
                FILE_APPEND);
        }
        if($order['send_id'] != ""){
            if ($this->umbrella($order['user_id'], $order['num'],$order['send_id'])) {
                Log::record("伞下购货补助完成");
                file_put_contents("./log.txt",date("Y-m-d H:i:s",time())."伞下购货补助完成\n",
                    FILE_APPEND);
            }else{
                file_put_contents("./log.txt",date("Y-m-d H:i:s",time())."上级没有代理商无需补助\n",
                    FILE_APPEND);
            }

        }else{
            file_put_contents("./log.txt",date("Y-m-d H:i:s",time())."上级没有代理商无需补助\n",
                FILE_APPEND);
        }

        #非店面购买 升级后的代理商每销售1台，给上级额外补助60元/台
        if ($order['is_shop'] == 2 && $order['sign'] != 3) {
            $user_agent = User::get($order['send_id']);
            $user_agent->setInc("account", Config::get(14)->value * $order['num']);
            $account->setAccountRecord($order['send_id'], "招商销售奖",
                3, 2, Config::get(14)->value * $order['num'],$order['user_id']);
            Log::record("升级后的代理商每销售1台 奖励60");
            if($account){
                file_put_contents("./log.txt",date("Y-m-d H:i:s",time()).
                    "奖励成 奖励60\n",FILE_APPEND);
            }else{
                file_put_contents("./log.txt",date("Y-m-d H:i:s",time()).
                    "奖励no 奖励60\n",FILE_APPEND);
            }
            file_put_contents("./log.txt",date("Y-m-d H:i:s",time()).
                "升级后的代理商每销售1台 奖励60\n",FILE_APPEND);
        }
        #店面 一级店面  100元补助，直属代理商减100
        Db::startTrans();
        try {
            if ($order->sign == 2) {
                $user_storefront = User::get($order->shop_id);#查询店面

                if ($user_storefront['level'] == "7") {#一级 补助100 直属代理商余额减去100
                    $user_storefront->setInc("account", (Config::get(11)->value * $order['num']));
                    $account->setAccountRecord($order->shop_id, "一级店面补助",
                        9, 1, (Config::get(11)->value * $order['num']),$order['user_id']);
                    Log::record("1店面补助");
                    file_put_contents("./log.txt",date("Y-m-d H:i:s",time()).
                        $order['user_id']."一级店面补助\n",FILE_APPEND);

                    if ($user_storefront['agency_id'] != 0) {#为0  系统直属店面
                        $user_agent = User::get($user_storefront['agency_id']);

                        $user_agent->setInc("account", ((Config::get(9)->value * $order['num'])
                            - (Config::get(11)->value * $order['num'])));
                        $account->setAccountRecord($order->agency_id, "店面补助直属代理奖励",
                            11, 1, ((Config::get(9)->value * $order['num'])
                                - (Config::get(11)->value * $order['num'])),$order['user_id']);
                        Log::record("1店面补助直属代理扣款");
                        file_put_contents("./log.txt",date("Y-m-d H:i:s",time())."
                        1店面补助直属代理扣款\n",FILE_APPEND);
                    }

                }else{
                    file_put_contents("./log.txt",date("Y-m-d H:i:s",time())."
                        不是1级店面\n",FILE_APPEND);
                }
                if ($user_storefront['level'] == "8") {#二级 补助50 直属代理商余额减去50
                    $user_storefront->setInc("account", (Config::get(12)->value * $order['num']));
                    $account->setAccountRecord($order->shop_id, "二级店面补助",
                        9, 1, (Config::get(12)->value * $order['num']),$order['user_id']);
                    Log::record("二级店面补助");
                    file_put_contents("./log.txt",date("Y-m-d H:i:s",time())." 二级店面补助\n",
                        FILE_APPEND);

                    if ($user_storefront->agency_id != 0) {#为0  系统直属店面
                        $user_agent = User::get($user_storefront['agency_id']);
                        $user_agent->setInc("account", ((Config::get(9)->value * $order['num'])
                            - (Config::get(12)->value * $order['num'])));
                        $account->setAccountRecord($order->agency_id, "店面补助直属代理奖励",
                            11, 2, ((Config::get(9)->value * $order['num'])
                                - (Config::get(12)->value * $order['num'])),$order['user_id']);
                        Log::record("2店面补助直属代理扣款");
                        file_put_contents("./log.txt",date("Y-m-d H:i:s",time()).
                            "2店面补助直属代理扣款\n",FILE_APPEND);
                    }
                }else{
                    file_put_contents("./log.txt",date("Y-m-d H:i:s",time())."
                        不是2级店面\n",FILE_APPEND);
                }

            }else{
                file_put_contents("./log.txt",date("Y-m-d H:i:s",time())."
                        不是店面\n",FILE_APPEND);
            }
            #代理商
//            if ($order['sign'] == 3) {
//                Log::record("平台发货!无需补助");
//                file_put_contents("./log.txt",date("Y-m-d H:i:s",time())."平台发货!无需补助\n",
//                    FILE_APPEND);
//            }

            file_put_contents("./log.txt",date("Y-m-d H:i:s",time()).
                "--------------\n",FILE_APPEND);
            return "wancheng";
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
        }
    }

    #理商伞下购货，额外补助200元/台（同级别不重复拿）
    public function umbrella($id, $num,$sendid)
    {
        $user_list = getUpUser(User::all(), $sendid);
        $account = new AccountRecord();
        for ($i = 0; $i < count($user_list); $i++) {
            if (in_array(User::get($user_list[$i])['level'], [3, 4, 5, 6])) {
                User::get($user_list[$i])->setInc("account", (Config::get(9)->value * $num));
                $account->setAccountRecord($sendid, "代理商伞下购货",
                    11, 2, (Config::get(9)->value * $num - Config::get(12)->value * $num),$id);
                if ($account) {
                    return true;
                }
                break;
            }
        }
    }
}