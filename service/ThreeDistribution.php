<?php

namespace Service;

use app\admin\model\Config;
use app\admin\model\OrderModel;
use app\admin\model\User;
use think\Db;
use think\Exception;
use app\admin\model\AccountRecordModel;
use think\Log;

/**
 * 三级分佣
 */
class ThreeDistribution
{
    public $order;

    public function __construct()
    {
        $this->order = new OrderModel();
    }

    public function addThree($orderid)
    {
        $order = OrderModel::get($orderid);
        $user = User::all();

        $prent = getUpUser($user, $order['user_id']);
        $configOne = Config::get(2)->value / 100;
        $configTwo = Config::get(3)->value / 100;
        $configThree = Config::get(4)->value / 100;

        $threeArray = ["", $configOne, $configTwo, $configThree];
        $account = new AccountRecord();

        Log::record("分销开始!!!");
        Db::startTrans();
        try {
            for ($i = 1; $i <= 3; $i++) {
                if (isset($prent[$i])) {
                    $user = User::where(["id" => $prent[$i]])->value('status');
                    if ($user == "1") {
                        if (User::where(["id" => $prent[$i]])->value('status') == 1) {#冻结不能享受三级分佣
                            $threePrice = ($order['price'] * $order['num']) * $threeArray[$i];
                            $resOne = User::where('id', $prent[$i])
                                ->setInc('account', $threePrice);
                        }
                        if ($resOne) {
                            $account->setAccountRecord($prent[$i], "{$i}级分佣",
                                AccountRecordModel::TYPE_ONE, 1,
                                ($order['price'] * $order['num']) * $threeArray[$i], $order['user_id']);
                            Log::record("{$i}级分佣完成");
                        }
                    }
                }
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
        }
        Log::record("分销结束!!!");

    }
}