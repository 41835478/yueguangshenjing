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
                            $threePrice = $order['price'] * $threeArray[$i];
                            $resOne = User::where('id', $prent[$i])
                                ->setInc('account', $threePrice);
                        if ($resOne) {
                            $account->setAccountRecord($prent[$i], "{$i}级分佣",
                                AccountRecordModel::TYPE_ONE, 1,
                                $order['price'] * $threeArray[$i], $order['user_id']);
                            Log::record("{$i}级分佣完成");
                            file_put_contents("./log.txt",date("Y-m-d H:i:s",time()).
                                "用户:".$order['user_id'] ."  {$i}级返佣".$order['price'] * $threeArray[$i]."\n",FILE_APPEND);
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