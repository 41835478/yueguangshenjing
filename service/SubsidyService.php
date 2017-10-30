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
        #店面 一级店面  100元补助，直属代理商减100

        Db::startTrans();

        try {
            if ($order->sign == 2) {
                $user_storefront = User::get($order->shop_id);#查询店面

                if ($user_storefront['level'] == "7") {#一级 补助100 直属代理商余额减去10
                    $user_storefront->setInc("account", (Config::get(11)->value * $order->num));
                    $account->setAccountRecord($order->shop_id, "一级店面补助",
                        9, 1, (Config::get(11)->value * $order->num));
                    Log::record("1店面补助");

                    if ($user_storefront->agency_id != 0) {#为0  系统直属店面
                        $user_agent = User::get($user_storefront->agency_id);

                        $user_agent->setInc("account", ((Config::get(9)->value * $order->num)
                            - (Config::get(11)->value * $order->num)));
                        $account->setAccountRecord($order->agency_id, "店面补助直属代理扣款",
                            11, 1, ((Config::get(9)->value * $order->num)
                                - (Config::get(11)->value * $order->num)));
                        Log::record("1店面补助直属代理扣款");
                    }

                }
                if ($user_storefront['level'] == "8") {#二级 补助50 直属代理商余额减去50
                    $user_storefront->setInc("account", (Config::get(12)->value * $order->num));
                    $account->setAccountRecord($order->shop_id, "二级店面补助",
                        9, 1, (Config::get(12)->value * $order->num));
                    Log::record("二级店面补助");

                    if ($user_storefront->agency_id != 0) {#为0  系统直属店面
                        $user_agent = User::get($user_storefront->agency_id);
                        $user_agent->setInc("account", ((Config::get(9)->value * $order->num)
                            - (Config::get(12)->value * $order->num)));
                        $account->setAccountRecord($order->agency_id, "店面补助直属代理扣款",
                            11, 2, ((Config::get(9)->value * $order->num)
                                - (Config::get(12)->value * $order->num)));
                        Log::record("2店面补助直属代理扣款");
                    }
                }

            }
            #代理商
            if ($order->sign == 1 && $order->send_id != "") {#代理发货奖 奖励50
                $user_agent = User::get($order->send_id);
                $user_agent->setInc("account", (Config::get(10)->value * $order->num));
                $account->setAccountRecord($order->send_id, "代理商发货奖",
                    12, 2, (Config::get(10)->value * $order->num));
                Log::record("代理商发货补助完成");

                if ($this->umbrella($order->user_id, $order->num)) {
                    Log::record("伞下购货补助完成");
                }
            }
            if ($order->sign == 3) {
                Log::record("平台发货!无需补助");
            }
            #非店面购买 升级后的代理商每销售1台，给上级额外补助60元/台
            if ($order->is_shop == 2 && $order->type != 2) {
                $user_agent = User::get($order->send_id);
                $user_agent->setInc("account", (Config::get(14)->value * $order->num));
                $account->setAccountRecord($order->send_id, "招商销售奖",
                    3, 2, (Config::get(10)->value * $order->num));
                Log::record("升级后的代理商每销售1台");
            }
            return "wancheng";
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
        }
    }

    #理商伞下购货，额外补助200元/台（同级别不重复拿）
    public function umbrella($id, $num)
    {
        $user_list = getUpUser(User::all(), $id);
        $account = new AccountRecord();
        for ($i = 0; $i < count($user_list); $i++) {
            if (in_array(User::get($user_list[$i])->level, [3, 4, 5, 6])) {
                User::get($user_list[$i])->setInc("account", (Config::get(9)->value * $num));
                $account->setAccountRecord($id, "代理商伞下购货",
                    11, 2, (Config::get(9)->value * $num - Config::get(12)->value * $num));
                if ($account) {
                    return true;
                }
                break;
            }
        }
    }
}